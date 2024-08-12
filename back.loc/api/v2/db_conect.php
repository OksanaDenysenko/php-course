<?php
require 'config.php'; // database settings file

$config = require 'config.php';

$conn = new mysqli(
    $config['dbhost'],
    $config['dbuser'],
    $config['dbpass'],
    $config['dbname']
);




