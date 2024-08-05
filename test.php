<?php
require __DIR__ . '/vendor/autoload.php';
use Spatie\Browsershot\Browsershot;

$html = '<h1>Test</h1>';
$pdf = Browsershot::html($html)->noSandbox()->pdf();
file_put_contents('/var/www/tawtheef/storage/app/test.pdf', $pdf);
