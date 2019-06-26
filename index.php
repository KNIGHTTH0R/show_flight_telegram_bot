<?php

$userName = isset($_GET['name']) ? ', ' . $_GET['name'] : '';

echo "Hello world{$userName}";