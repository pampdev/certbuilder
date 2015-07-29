<?php 
return [

    'wkhtmltopdf_bin' => '/usr/local/bin/wkhtmltopdf',

    'settings' => [
        // Page orientation set to 'Portrait' or 'Landscape'.
        'orientation'     => 'Landscape',

        // A series (e.g. A4, A3), B series (e.g. B5, B3), Executive, Folio, Legal, Letter, Tabloid.
        'page_size'       => 'A4',

        // Page height in millimeters.
        // Page width in millimeters.
        // Both values must be provided and will override the 'page-size' setting.
        // .. enable this

        // Page margins in millimeters for 'top', 'bottom', 'left' and 'right'.
        // Default '10 mm' if not specified.
        'margins' => [
            'top'       => '0.0',
            'bottom'    => '0.0',
            'left'      => '0.0',
            'right'     => '0.0',
        ],

        // PDF will be generated in grayscale. Default 'false' if not set.
        // ..enable this
        // 'grayscale' => 'false',
    ]

];