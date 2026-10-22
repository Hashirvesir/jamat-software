<?php

namespace App\Support;

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class MpdfPdf
{
    protected Mpdf $mpdf;
    protected string $html = '';

    public function __construct(array $config = [])
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $tempDir = storage_path('app/mpdf_temp');
        if (!file_exists($tempDir)) {
            @mkdir($tempDir, 0777, true);
        }

        $baseConfig = [
            'mode' => 'utf-8',
            'format' => $config['format'] ?? 'A4',
            'margin_left' => $config['margin_left'] ?? 12,
            'margin_right' => $config['margin_right'] ?? 12,
            'margin_top' => $config['margin_top'] ?? 12,
            'margin_bottom' => $config['margin_bottom'] ?? 12,
            'tempDir' => $tempDir,
            'fontDir' => array_merge($fontDirs, [
                resource_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'noto' => [
                    'R' => 'NotoNaskhArabic.ttf',
                ],
                'urdu' => [
                    'R' => 'NotoNaskhArabic.ttf',
                ],
            ],
            'default_font' => 'sans-serif',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ];

        $mergedConfig = array_merge($baseConfig, $config);
        $this->mpdf = new Mpdf($mergedConfig);

        // Set watermark if logo exists
        $logoPath = public_path('logo.jpeg');
        if (file_exists($logoPath) && empty($config['no_watermark'])) {
            $this->mpdf->SetWatermarkImage($logoPath, 0.08, [120, 120]);
            $this->mpdf->showWatermarkImage = true;
        }
    }

    /**
     * Build a 2-page ID card PDF.
     * Front and back are rendered from SEPARATE views so mPDF never
     * inserts an accidental blank page from a <pagebreak/> tag.
     * Logo is added as a transparent centred watermark on every page.
     *
     * @param string $frontView  e.g. 'members.card-front-pdf'
     * @param string $backView   e.g. 'members.card-back-pdf'
     * @param array  $data       Data passed to both views
     */
    public static function loadCard(string $frontView, string $backView, array $data = []): self
    {
        $instance = new self([
            'format'       => [85.725, 53.975],
            'margin_left'  => 0,
            'margin_right' => 0,
            'margin_top'   => 0,
            'margin_bottom'=> 0,
            'no_watermark' => true,
        ]);
        $instance->mpdf->autoPageBreak = false;

        // Faint logo watermark centred on every page
        $logoPath = public_path('logo.jpeg');
        if (file_exists($logoPath)) {
            $instance->mpdf->SetWatermarkImage($logoPath, 0.07, [28, 28], [29, 15]);
            $instance->mpdf->showWatermarkImage = true;
        }

        // Page 1 – Front
        $instance->mpdf->WriteHTML(View::make($frontView, $data)->render());
        // Page 2 – Back
        $instance->mpdf->AddPage();
        $instance->mpdf->WriteHTML(View::make($backView, $data)->render());

        return $instance;
    }

    /** @deprecated Use loadCard() instead */
    public static function loadCardView(string $frontView, string $backView, array $data = []): self
    {
        return self::loadCard($frontView, $backView, $data);
    }

    public static function loadView(string $view, array $data = [], array $mergeData = [], array $config = []): self
    {
        $instance = new self($config);
        $instance->html = View::make($view, $data, $mergeData)->render();
        $instance->mpdf->WriteHTML($instance->html);
        return $instance;
    }

    public static function loadHtml(string $html, array $config = []): self
    {
        $instance = new self($config);
        $instance->html = $html;
        $instance->mpdf->WriteHTML($html);
        return $instance;
    }

    public function getMpdf(): Mpdf
    {
        return $this->mpdf;
    }

    public function output(): string
    {
        return $this->mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
    }

    public function download(string $filename = 'document.pdf'): Response
    {
        $content = $this->output();
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function stream(string $filename = 'document.pdf'): Response
    {
        $content = $this->output();
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
