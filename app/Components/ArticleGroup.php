<?php

namespace App\Components;

use Camezilla\Components\Component;

class ArticleGroup extends Component
{
    private array $articles;
    private string $title;

    public function __construct(string $title, array $articles)
    {
        parent::__construct();
        $this->title = $title;
        $this->articles = $articles;
    }

    protected function build(): void
    { ?>
        <section class="news-grid">
            <?php if (empty($this->articles)): ?>
                <p>Nessun articolo trovato.</p>
            <?php else: ?>
                <?php foreach ($this->articles as $article): ?>
                    <?= new ArticleItem($article) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    <?php }
}