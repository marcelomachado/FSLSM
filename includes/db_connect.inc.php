<?php
    include_once 'psl-config.inc.php';
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    $mysqli->set_charset('utf8');
