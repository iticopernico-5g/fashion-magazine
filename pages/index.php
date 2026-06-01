<?php

require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Components\ArticleGroup;
use App\Layouts\MainLayout;
use App\Services\ArticleService;
use Camezilla\Pages\Page;

$page = new class extends Page {

    public function __construct() {

        $articleService = new ArticleService();
        $recentArticles = $articleService->get_recent(5);

        parent::__construct(new MainLayout("Home"), function () use ($recentArticles) { ?>

            <section class="hero">
                <h1 class="hero-title">
                    <a href="<?= page('index.php') ?>" class="title-black title-underline-yellow">Recenti</a>
                    <a href="<?= page('moda.php') ?>" class="title-gray">Moda</a>
                </h1>
                <p class="hero-subtitle">Notizie, moda e cultura dall'ITI.</p>
            </section>

            <?= new ArticleGroup("Recenti", $recentArticles) ?>

        <?php });
    }
};

echo $page->render();