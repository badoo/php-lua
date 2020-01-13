--TEST--
Catch Exception from PHP callback 
--SKIPIF--
<?php if (!extension_loaded("lua")) print "skip"; ?>
--FILE--
<?php 

function throw_me() {
	throw new \Exception('It happened');
}

function call_me() {
	return "called after exception";
}

$l = new lua();
$l->registerCallback('throw_me', 'throw_me');
$l->registerCallback('call_me', 'call_me');

$l->eval(<<<'CODE'
local t, err = pcall(throw_me)

print("Exception caught in Lua: " .. tostring(t) .. ", message: " .. tostring(err) .. "\n")

local called = call_me()
print(called .. "\n")
CODE
);

try {
	$l->eval(<<<'CODE'
	local value = throw_me()
CODE
);
} catch (\Throwable $e) {
	print "Caught in PHP: {$e->getMessage()}\n";
}

?>
--EXPECTF--
Exception caught in Lua: false, message: It happened
called after exception
Caught in PHP: It happened
