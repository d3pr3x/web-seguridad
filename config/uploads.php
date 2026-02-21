<?php

return [
    /*
    | Tamaños máximos en KB (parametrizable por env).
    */
    'max_document_kb' => (int) env('UPLOAD_MAX_DOCUMENT_KB', 10240), // 10 MB
    'max_image_kb' => (int) env('UPLOAD_MAX_IMAGE_KB', 5120), // 5 MB
    'max_image_width' => (int) env('UPLOAD_MAX_IMAGE_WIDTH', 3000),
    'max_image_height' => (int) env('UPLOAD_MAX_IMAGE_HEIGHT', 3000),

    'document_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
    'image_mimes' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif'],

    'document_mimetypes' => [
        'application/pdf',
        'image/jpeg',
        'image/png',
    ],
    'image_mimetypes' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/heic',
        'image/heif',
    ],
];
