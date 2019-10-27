--TEST--
Loadstring
--SKIPIF--
<?php if (!extension_loaded("lua")) print "skip"; ?>
--FILE--
<?php 

$l = new lua();
try {
    $l->loadstring(<<<CODE
            function test(cb1)
            local cb2 = cb1("called from lua")
            cb2("returned from php")
            -- unfinished function
CODE , "my code");


} catch(\LuaException $e) {
    echo $e->getMessage();
}
?>
--EXPECTF--
[string "my code"]:4: 'end' expected (to close 'function' at line 1) near <eof>
