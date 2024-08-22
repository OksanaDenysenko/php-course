<?php

$config = require 'config.php'; // database settings file

$conn = new mysqli(
    $config['dbhost'],
    $config['dbuser'],
    $config['dbpass'],
    $config['dbname']
);




