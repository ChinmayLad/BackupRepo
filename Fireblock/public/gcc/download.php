<?php

session_start();
$filename = $_SESSION['filename'];

header('Content-disposition: attachment; filename='.$filename);
header('Content-type: text/plain');
readfile($filename);

header('Location: /');