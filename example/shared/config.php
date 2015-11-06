<?php

$cloudcompliUrl = 'http://localhost/cloudcompli/public';

$scriptUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].(($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? (':'.$_SERVER['SERVER_PORT']) : '').$_SERVER['SCRIPT_NAME'];

$clientId = 'demoapp';

$clientSecret = 'demopass';