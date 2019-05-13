<?php

require "vendor/autoload.php";
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

$client = new GuzzleHttp\Client();

echo $_COOKIE["TestCookie"]; 
