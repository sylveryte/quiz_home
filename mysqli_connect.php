<?php

//echo 'db connecting...';

DEFINE('DB_USER','preeti');
DEFINE('DB_PASSWORD','pretty');
DEFINE('DB_NAME','tree');
DEFINE('DB_HOST','localhost');

$dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);


if (!$dbc)
    {
        die('Could not connect: ' . mysqli_error());
    }

//echo '...db connected<br />';
?>