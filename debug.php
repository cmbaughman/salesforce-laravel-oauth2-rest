<?php
require 'vendor/autoload.php';
$batch = new \Frankkessler\Salesforce\DataObjects\BinaryBatch(['batchZip' => 'test/path/here']);
var_dump($batch->batchZip);
