<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Components\ArticleGroup;
use App\Layouts\MainLayout;
use App\Models\Category;
use App\Services\ArticleService;
use Camezilla\Pages\Page;

$page = new class extends Page {

    public function __construct() {
        
        $articleService = new ArticleService();
        $articles = $articleService->get_by_category(Category::Sport);

        parent::__construct(new MainLayout("Sport"), function () use ($articles) { ?>

            <?= new ArticleGroup($articles) ?>

        <?php });
    }
};

echo $page->render();