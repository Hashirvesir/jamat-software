<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$m = App\Models\Member::with('community')->first();
$logoPath = public_path('logo.jpeg');

function makeCardPdf(string $view, array $data, string $logoPath): array {
    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];
    $tempDir = storage_path('app/mpdf_temp');
    if (!file_exists($tempDir)) mkdir($tempDir, 0777, true);

    $mpdf = new \Mpdf\Mpdf([
        'mode'          => 'utf-8',
        'format'        => [85.725, 53.975],
        'margin_left'   => 0,
        'margin_right'  => 0,
        'margin_top'    => 0,
        'margin_bottom' => 0,
        'tempDir'       => $tempDir,
        'fontDir'       => array_merge($fontDirs, [resource_path('fonts')]),
        'fontdata'      => $fontData + [
            'noto' => ['R' => 'NotoNaskhArabic.ttf'],
            'urdu' => ['R' => 'NotoNaskhArabic.ttf'],
        ],
        'default_font'  => 'sans-serif',
        'autoScriptToLang' => true,
        'autoLangToFont'   => true,
    ]);
    $mpdf->autoPageBreak = false;

    $html = view($view, $data)->render();
    $mpdf->WriteHTML($html);
    $content = $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);

    // Count pages by checking FPDI
    $fpdi = new \setasign\Fpdi\Fpdi();
    $pages = $fpdi->setSourceFile(\setasign\Fpdi\PdfParser\StreamReader::createByString($content));
    return ['pages' => $pages, 'size' => strlen($content), 'view' => $view];
}

$front = makeCardPdf('members.card-front-pdf', ['member' => $m], $logoPath);
echo "Front: {$front['pages']} pages, {$front['size']} bytes\n";

$back = makeCardPdf('members.card-back-pdf', ['member' => $m], $logoPath);
echo "Back:  {$back['pages']} pages, {$back['size']} bytes\n";
