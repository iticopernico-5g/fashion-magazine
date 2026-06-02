<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Services\ArticleService;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { http_response_code(400); exit(); }

$articleService = new ArticleService();
$article = $articleService->get_by_id($id);

if (!$article || !$article->get_image()) {
    http_response_code(404);
    exit();
}

$imageData = $article->get_image();

// Detect mime type from binary
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->buffer($imageData) ?: 'image/jpeg';

header('Content-Type: ' . $mime);
header('Cache-Control: public, max-age=86400');
echo $imageData;
