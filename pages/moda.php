<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Components\ArticleGroup;
use App\Layouts\MainLayout;
use App\Services\ArticleService;
use App\Models\Category;
use Camezilla\Pages\Page;

$page = new class extends Page {

    public function __construct() {

        $articleService = new ArticleService();

        // Default: fashion
        $selectedCategory = Category::Fashion;

        // Lettura filtro da querystring: ?cat=fashion
        if (isset($_GET['cat']) && is_string($_GET['cat'])) {
            $cat = trim($_GET['cat']);

            $from = Category::tryFrom($cat);
            if ($from !== null) {
                $selectedCategory = $from;
            }
        }

        $articles = $articleService->get_by_category($selectedCategory);

        parent::__construct(new MainLayout("Moda"), function () use ($articles, $selectedCategory) { ?>

            <section class="hero">
                <h1 class="hero-title">
                    <a href="<?= page('index.php') ?>" class="title-gray">Recenti</a>
                    <a href="<?= page('moda.php') ?>" class="title-black title-underline-yellow">Moda</a>
                </h1>
                <?php
                $subtitles = [
                    'fashion'             => 'Stile, tendenze e couture.',
                    'sport'               => 'Competizioni, risultati e spirito di squadra.',
                    'social_activity'     => 'Iniziative, comunità e impatto sociale.',
                    'charity'             => 'Solidarietà, raccolta fondi e buone cause.',
                    'challenges'          => 'Sfide, competizioni e talenti a confronto.',
                    'style_recommendations' => 'Consigli di stile per ogni occasione.',
                    'events'              => 'Eventi, mostre e appuntamenti da non perdere.',
                    'other'               => 'Storie, curiosità e tutto il resto.',
                ];
                ?>
                <p class="hero-subtitle"><?= $subtitles[$selectedCategory->value] ?? 'Esplora i contenuti.' ?></p>
            </section>

            <section class="category-filters">
                <?php
                    // Label leggibili per ciascun value dell'enum
                    $labels = [
                        'sport' => 'SPORT',
                        'fashion' => 'FASHION',
                        'social_activity' => 'SOCIAL',
                        'charity' => 'CHARITY',
                        'challenges' => 'CHALLENGES',
                        'style_recommendations' => 'CONSIGLI',
                        'events' => 'EVENTI',
                        'other' => 'ALTRO',
                    ];

                    // Filtri: chiavi stringa (value dell'enum) => label
                    $filters = [];
                    foreach (Category::cases() as $c) {
                        $filters[$c->value] = $labels[$c->value] ?? strtoupper($c->value);
                    }
                ?>

                <?php foreach ($filters as $cat => $label): ?>
                    <?php
                        // $cat è string (es. "events")
                        $activeClass = ($selectedCategory->value === $cat) ? ' active-filter' : '';
                        $href = page('moda.php') . '?cat=' . urlencode($cat);
                    ?>
                    <a class="filter-btn<?= $activeClass ?>" href="<?= $href ?>">
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php endforeach; ?>
            </section>

            <?= new ArticleGroup("Categoria: " . strtoupper($selectedCategory->value), $articles) ?>

        <?php });
    }
};

echo $page->render();