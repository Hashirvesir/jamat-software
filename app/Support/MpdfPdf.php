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

    public static function loadCard(string $view, array $data = []): self
    {
        $instance = new self([
            'format' => [85.725, 53.975],
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'no_watermark' => true,
        ]);
        $instance->mpdf->autoPageBreak = false;

        // Add logo as centered watermark on card pages
        $logoPath = public_path('logo.jpeg');
        if (file_exists($logoPath)) {
            $instance->mpdf->SetWatermarkImage($logoPath, 0.06, [30, 30], [28, 14]);
            $instance->mpdf->showWatermarkImage = true;
        }

        $html = View::make($view, $data)->render();
        $instance->mpdf->WriteHTML($html);
        return $instance;
    }

    public static function loadCardView(string $frontView, string $backView, array $data = []): self
    {
        $instance = new self([
            'format' => [85.725, 53.975],
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'no_watermark' => true,
        ]);
        $instance->mpdf->autoPageBreak = false;

        $frontHtml = View::make($frontView, $data)->render();
        $backHtml = View::make($backView, $data)->render();

        $instance->mpdf->WriteHTML($frontHtml);
        $instance->mpdf->AddPage();
        $instance->mpdf->WriteHTML($backHtml);

        return $instance;
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
