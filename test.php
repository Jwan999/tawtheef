<?php
require __DIR__ . '/vendor/autoload.php';
use Barryvdh\DomPDF\Facade\Pdf;

$html = '<h1>Test</h1>';
$pdf = Pdf::loadHtml($html);
$pdf->save('/var/www/tawtheef/storage/app/test.pdf');
echo "PDF generated successfully.\n";
