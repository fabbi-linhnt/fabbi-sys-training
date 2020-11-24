<?php
require("../vendor/autoload.php");
$openapi = \OpenApi\scan('../app/Http/Controllers');
echo $openapi->toJson();
