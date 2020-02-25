--TEST--
Int type support for Lua 5.3
--SKIPIF--
<?php
if (!extension_loaded("lua")) print "skip lua extension missing";
if (\Lua::getVersionInt() < 503) print "skip works only with Lua >= 5.3";
?>
--FILE--
<?php 

function get_int() {
	return 769010842;
}

$l = new \Lua();
$l->registerCallback('get_int', 'get_int');

$l->eval(<<<'CODE'
local v = get_int();
print(v, ' ', type(v), '\n')

function int_from_lua()
	return 1
end
CODE);

$v = $l->call('int_from_lua');
var_dump($v);

?>
--EXPECT--
769010842 number
int(1)