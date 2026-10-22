<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$m = App\Models\Member::with('community')->first();
if (!$m) { echo "No member found\n"; exit(1); }

echo "Member: {$m->name} (ID: {$m->id})\n";

// Generate card PDF
$pdf = App\Support\MpdfPdf::loadCard('members.card-pdf', ['member' => $m]);
$content = $pdf->output();
file_put_contents('test_card.pdf', $content);
echo "Card PDF: " . strlen($content) . " bytes\n";

// Check page count
$fpdi = new \setasign\Fpdi\Fpdi();
$pages = $fpdi->setSourceFile('test_card.pdf');
echo "Pages: $pages\n";
for ($i = 1; $i <= $pages; $i++) {
    $tpl = $fpdi->importPage($i);
    $s = $fpdi->getTemplateSize($tpl);
    echo "  Page $i: {$s['width']}mm x {$s['height']}mm\n";
}

// Save rendered HTML for visual check
file_put_contents('test_card_preview.html', view('members.card-pdf', ['member' => $m])->render());
echo "Saved test_card_preview.html for browser check\n";
echo "Done!\n";
