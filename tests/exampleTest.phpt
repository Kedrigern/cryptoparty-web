<?php
use Tester\Assert;

$container =  require __DIR__ . "/bootstrap.php";

Assert::same( 'Hello John', 'Hello John' );