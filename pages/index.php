<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Components\ArticleGroup;
use App\Layouts\MainLayout;
use App\Services\ArticleService;
use Camezilla\Pages\Page;

$page = new class extends Page {

    public function __construct() {
        
        // $articleService = new ArticleService();
        // $recentArticles = $articleService->get_recent();

        parent::__construct(new MainLayout("Home"), function () { ?>

            home

        <?php });
    }
};

echo $page->render();