<?php
if (is_readable(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
} else {
    die('Konfigurationsdatei nicht gefunden');
}
ob_start();
header('Cache-Control: no cache');
session_cache_limiter('private_no_expire');
session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/index.php';
?>
<!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="./assets/main.css">
            <script src="./script.js"></script>
            <title>IMDB</title>
            <style type="text/css">
                .details {
                    float: right;
                }
            </style>
        </head>
        <body>
            
        </body>
    </html>