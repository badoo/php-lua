--TEST--
register php function to lua
--SKIPIF--
<?php if (!extension_loaded("lua")) print "skip"; ?>
--FILE--
<?php 

$l = new lua();

class A {
   public static function intro($foo, $bar) {
		echo '1: ', $foo, $bar, "\n";
  }
}   
    
$l->registerCallback("callphp", array("A", "intro")); 

$l->eval(<<<CODE
	callphp("foo", "bar");
CODE
);

Class B {
	public function intro(array $bar) {
			echo '2: ', join(",", $bar), "\n";
	}
}

$l->registerCallback("callphp2", array(new B, "intro"));


$l->registerCallback("echo", "var_dump");

$l->eval(<<<CODE
	callphp2({"foo", "bar"});
	echo("maybe");
CODE
);
?>
--EXPECTF--
1: foobar
2: foo,bar
string(5) "maybe"
