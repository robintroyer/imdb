<?php
    if (is_readable(__DIR__ . '/config.php')) {
        require __DIR__ . '/config.php';
    } else {
        die('Konfigurationsdatei nicht gefunden');
    }
    require __DIR__ . '/vendor/autoload.php';
    $storage = new Database();
    $configDB = new stdClass();
    $configDB->host = $DB_HOST;
    $configDB->user = $DB_USER;
    $configDB->pass = $DB_PASS;
    // $configDB->name = $DB_NAME;
    $storage->initialize($configDB);
?>
<!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
            <title>Hello, world!</title>
        </head>
        <body>

        </body>
    </html>