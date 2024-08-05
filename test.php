<?php
require __DIR__ . '/vendor/autoload.php';
use Spatie\Browsershot\Browsershot;

$html = '<h1>Test</h1>';
$pdf = Browsershot::html($html)
    ->setChromePath('/snap/bin/chromium')
    ->noSandbox()
    ->setNodeBinary('/usr/bin/node')
    ->setNpmBinary('/usr/bin/npm')
    ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
    ->setOption('env', ['PUPPETEER_CACHE_DIR' => '/var/www/.cache/puppeteer'])
    ->pdf();

file_put_contents('/var/www/tawtheef/storage/app/test.pdf', $pdf);
echo "PDF generated successfully.\n";
