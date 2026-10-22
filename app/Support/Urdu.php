<?php

namespace App\Support;

use ArPHP\I18N\Arabic;

/**
 * Dompdf has no Arabic/Urdu text-shaping engine: it draws one isolated-form
 * glyph per character instead of selecting the correct joined glyph based on
 * context. ArPHP's utf8Glyphs() pre-computes the visually-joined Arabic
 * presentation-form codepoints ahead of time, so dompdf only ever has to
 * look up one glyph per character with no shaping required. The output is
 * already in left-to-right visual order, so render it WITHOUT direction:rtl.
 */
class Urdu
{
    protected static ?Arabic $arabic = null;

    public static function shape(string $text): string
    {
        if (static::$arabic === null) {
            static::$arabic = new Arabic();
        }

        // Pre-normalize Urdu characters so ArPHP's shaping engine recognizes them
        // in its Arabic contextual tables and joins letters correctly without breaks:
        $normalized = str_replace(
            ['ک', 'ہ', 'ھ', 'ۂ', 'ۃ'],
            ['ك', 'ه', 'ه', 'ة', 'ة'],
            $text
        );

        return static::$arabic->utf8Glyphs($normalized);
    }
}
