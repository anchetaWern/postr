<?php
session_start();
require_once('class.networks.php');
$nets = new networks();
$res = $nets->postTumblrVideo("http://vimeo.com/42219816");
print_r($res);
?>