<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pre-shapes Arabic/Urdu text into joined presentation-form glyphs
        // before Dompdf renders it, since Dompdf has no text-shaping engine
        // of its own and would otherwise draw each letter in isolation.
        Blade::directive('urdu', function ($expression) {
            return "<?php echo e(\App\Support\Urdu::shape({$expression})); ?>";
        });

        // Register MpdfPdf as PDF / Mpdf helper
        if (!class_exists('PDF', false)) {
            class_alias(\App\Support\MpdfPdf::class, 'PDF');
        }
        if (!class_exists('MpdfPDF', false)) {
            class_alias(\App\Support\MpdfPdf::class, 'MpdfPDF');
        }
    }
}
