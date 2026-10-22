<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$m = App\Models\Member::with('community')->first();
$pdf = App\Support\MpdfPdf::loadCard('members.card-pdf', ['member' => $m]);
file_put_contents('card_check.pdf', $pdf->output());
file_put_contents('preview.html', view('members.card-pdf', ['member' => $m])->render());

$fpdi = new \setasign\Fpdi\Fpdi();
$pageCount = $fpdi->setSourceFile('card_check.pdf');
echo "Page Count: $pageCount\n";
for ($i = 1; $i <= $pageCount; $i++) {
    $tpl = $fpdi->importPage($i);
    $size = $fpdi->getTemplateSize($tpl);
    echo "Page $i: {$size['width']} x {$size['height']} ({$size['orientation']})\n";
}
