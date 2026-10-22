<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Helvetica, Arial, urdu, noto, sans-serif;
    }
    @page {
        margin: 0;
        padding: 0;
    }
    body {
        margin: 0;
        padding: 0;
        background: #ffffff;
    }
    .urdu {
        font-family: urdu, noto, sans-serif;
    }

    /* ── Header Bar ── */
    .hdr {
        width: 100%;
        background-color: #064433;
        padding: 3pt 5pt 2.5pt 5pt;
        border-bottom: 1.5pt solid #c9a227;
    }
    .hdr td { vertical-align: middle; }
    .hdr-logo {
        width: 16pt;
        height: 16pt;
        border-radius: 50%;
    }
    .hdr-org {
        color: #ffffff;
        font-size: 8pt;
        font-weight: bold;
        letter-spacing: 0.3pt;
        line-height: 1;
    }
    .hdr-sub {
        color: #c3ddd0;
        font-size: 4pt;
        letter-spacing: 0.2pt;
        margin-top: 0.8pt;
        line-height: 1;
    }
    .hdr-id {
        background-color: #0d5a42;
        border: 0.5pt solid #c9a227;
        color: #ffffff;
        font-size: 5pt;
        font-weight: bold;
        padding: 1.5pt 3pt;
        text-align: center;
    }

    /* ── Body ── */
    .bdy {
        padding: 3pt 5pt 1.5pt 5pt;
    }
    .bdy td { vertical-align: top; padding: 0; }

    /* Labels & Values */
    .lbl {
        font-size: 3.8pt;
        text-transform: uppercase;
        letter-spacing: 0.2pt;
        color: #5c6e66;
        font-weight: bold;
        line-height: 1;
        margin-bottom: 0.2pt;
    }
    .val {
        font-size: 6pt;
        font-weight: bold;
        color: #0f2a1f;
        border-bottom: 0.4pt solid #dce4df;
        padding-bottom: 0.4pt;
        margin-bottom: 1.2pt;
        line-height: 1.1;
    }
    .val-lg {
        font-size: 7pt;
        font-weight: bold;
        color: #064433;
        border-bottom: 0.5pt solid #c9a227;
        padding-bottom: 0.4pt;
        margin-bottom: 1.2pt;
        line-height: 1.1;
    }
    .val-cnic {
        font-size: 6.5pt;
        font-weight: bold;
        color: #064433;
        letter-spacing: 0.3pt;
        border-bottom: 0.4pt solid #dce4df;
        padding-bottom: 0.4pt;
        margin-bottom: 1.2pt;
        line-height: 1.1;
    }

    /* Photo */
    .photo-wrap {
        width: 46pt;
        text-align: center;
    }
    .photo-box {
        width: 43pt;
        height: 45pt;
        border: 0.8pt solid #064433;
        overflow: hidden;
        background-color: #eef4f0;
    }
    .photo-box img {
        width: 43pt;
        height: 45pt;
    }
    .type-lbl {
        background-color: #064433;
        color: #ffffff;
        font-size: 4pt;
        font-weight: bold;
        text-align: center;
        padding: 1pt 1pt;
        margin-top: 1pt;
        letter-spacing: 0.2pt;
        line-height: 1.2;
    }

    /* Signature */
    .sig-area {
        text-align: right;
        padding-top: 0.5pt;
    }
    .sig-line {
        width: 80pt;
        border-bottom: 0.5pt solid #c9a227;
        margin-left: auto;
        margin-bottom: 0.8pt;
    }
    .sig-txt {
        font-size: 3.8pt;
        color: #064433;
        font-weight: bold;
    }

    /* Footer stripe */
    .ftr {
        width: 100%;
        background-color: #064433;
        height: 2pt;
    }

    /* ── Back Side ── */
    .sec-title {
        font-size: 6pt;
        font-weight: bold;
        color: #064433;
        border-bottom: 0.5pt solid #064433;
        padding-bottom: 1pt;
        margin-bottom: 2pt;
        letter-spacing: 0.3pt;
    }
    .info-row {
        font-size: 5.2pt;
        color: #1a2b23;
        margin-bottom: 1pt;
        line-height: 1.4;
    }
    .info-row strong {
        color: #064433;
    }
    .stamp-area {
        width: 54pt;
        height: 30pt;
        border: 0.6pt dashed #c9a227;
        background-color: #fdfdf8;
        text-align: center;
        padding-top: 5pt;
    }
    .stamp-head {
        font-size: 4.5pt;
        font-weight: bold;
        color: #8a7c30;
        text-transform: uppercase;
        letter-spacing: 0.2pt;
    }
    .stamp-ur {
        font-size: 4pt;
        color: #8a7c30;
        margin-top: 1pt;
    }
    .disc {
        background-color: #f2f6f3;
        border: 0.4pt solid #d0ddd6;
        padding: 1.5pt 3pt;
        margin-top: 2pt;
        text-align: center;
    }
    .disc-en {
        font-size: 3.8pt;
        color: #4a5c54;
        line-height: 1.2;
    }
    .disc-ur {
        font-size: 4pt;
        color: #064433;
        margin-top: 0.5pt;
        line-height: 1.2;
    }
</style>
