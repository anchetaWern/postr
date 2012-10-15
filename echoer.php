<?php
session_start();
require_once('class.networks.php');
$nets = new networks();
echo $nets->getConfig('TUMBLR_LOGIN');
?>