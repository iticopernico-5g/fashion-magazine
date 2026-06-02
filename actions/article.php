<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Models\Article;
use App\Models\Category;
use App\Models\Status;
use App\Services\ArticleService;
use Camezilla\Dispatchers\Dispatcher;

$dispatcher = new Dispatcher(page('not-found.php'), page('error.php'));
$articleService = new ArticleService();

$dispatcher->post('create', function($params) use ($articleService) {
    require_user_authentication();

    $image = null;
    if (!empty($_FILES['image']['tmp_name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    $article = new Article(
        null,
        $params['title'] ?: null,
        $params['description'] ?: null,
        $params['summary'] ?: null,
        Category::from($params['category']),
        $params['link'] ?: null,
        $image,
        $params['text'] ?: null,
        $params['author'] ?: null,
        new DateTime($params['date']),
        Status::from($params['status'])
    );
    
    try {
        $articleService->create($article);
        Dispatcher::ok_redirect();
    } catch (Exception $e) {
        Dispatcher::error_go_back($e->getMessage());
    }
});

$dispatcher->post('update', function($params) use ($articleService) {
    require_user_authentication();

    $image = null;
    if (!empty($_FILES['image']['tmp_name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    $article = new Article(
        $params['id'],
        $params['title'] ?: null,
        $params['description'] ?: null,
        $params['summary'] ?: null,
        Category::from($params['category']),
        $params['link'] ?: null,
        $image,
        $params['text'] ?: null,
        $params['author'] ?: null,
        new DateTime($params['date']),
        Status::from($params['status'])
    );
    
    try {
        $articleService->update($article);
        Dispatcher::ok_redirect();
    } catch (Exception $e) {
        Dispatcher::error_go_back($e->getMessage());
    }
});

$dispatcher->post('delete', function($params) use ($articleService) {
    require_user_authentication();
    $article = new Article($params['id'], null, null, null, null, null, null, null, null, null, null);

    try {
        $articleService->delete($article);
        Dispatcher::ok_redirect();
    } catch (Exception $e) {
        Dispatcher::error_go_back($e->getMessage());
    }
});

$dispatcher->dispatch();