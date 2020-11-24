<?php
require("../vendor/autoload.php");
$openapi = \OpenApi\scan('../app/Http/Controllers');
$spec = json_encode($openapi, JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/swagger/api.json', $spec);
echo $openapi->toJson();
