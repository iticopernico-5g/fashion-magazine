<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Components\ArticleDetails;
use App\Layouts\MainLayout;
use App\Services\ArticleService;
use Camezilla\Pages\Page;

$page = new class extends Page {

    public function __construct() {

        $articleService = new ArticleService();
        $article = $articleService->get_by_id($_GET['id']);

        parent::__construct(new MainLayout($article ? $article->get_title() : "Articolo"), function () use ($article) { ?>

            <?php if (!$article): ?>
                <div style="text-align:center;padding:80px 20px">
                    <h2>Articolo non trovato</h2>
                    <a href="<?= page('index.php') ?>" style="color:#111;text-decoration:underline;font-size:.9rem;letter-spacing:1px;text-transform:uppercase">← Torna alla home</a>
                </div>
            <?php else: ?>
                <?= new ArticleDetails($article) ?>
            <?php endif; ?>

        <?php });
    }
};

echo $page->render();