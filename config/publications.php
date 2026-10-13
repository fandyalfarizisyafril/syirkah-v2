<?php

return [
    'timezone' => 'Asia/Jakarta',
    'max_upload_kb' => (int) env('PUBLICATIONS_MAX_UPLOAD_KB', 10240),
    'max_thumbnail_kb' => (int) env('PUBLICATIONS_MAX_THUMBNAIL_KB', 2048),
];
