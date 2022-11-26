<?php

return [
    'mode'                     => '',
    'format'                   => 'A3',
    'default_font_size'        => '12',
    'default_font'             => 'sans-serif',
    'margin_left'              => 10,
    'margin_right'             => 10,
    'margin_top'               => 10,
    'margin_bottom'            => 10,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'P',
    'title'                    => 'Laravel mPDF',
    'subject'                  => 'Aktaa',
    'author'                   => 'Aktaa Dental',
    'watermark'                => 'Aktaa',
    'show_watermark'           => false,
    'show_watermark_image'     => false,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => public_path('images/logo.png'),
    'watermark_image_alpha'    => 0.2,
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',
    'auto_language_detection'  => false,
    'temp_dir'                 => storage_path('app'),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
    'custom_font_dir'  => public_path('fonts/'), // don't forget the trailing slash!
    'custom_font_data' => [
        'tajawal' => [ // must be lowercase and snake_case
            'R'  => 'Tajawal-Regular.ttf',    // regular font
            'B'  => 'Tajawal-Bold.ttf',       // optional: bold font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ]
        // ...add as many as you want.
    ]
];
