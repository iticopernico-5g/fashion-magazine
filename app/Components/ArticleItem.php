<?php

namespace App\Components;

use Camezilla\Components\Component;
use App\Models\Article;

class ArticleItem extends Component
{
    private Article $article;

    public function __construct(Article $article)
    {
        parent::__construct();
        $this->article = $article;
    }

    protected function build(): void
    { ?>
        <article class="news-card">
            <div class="card-image-wrapper">
                <span class="category-tag">
                    <?= htmlspecialchars(strtoupper($this->article->get_category()->value)) ?>
                </span>

                <img
                    src="<?= resource('images/albero.jpg') ?>"
                    alt="<?= htmlspecialchars($this->article->get_title()) ?>"
                    class="card-image">
            </div>

            <div class="card-content">
                <p class="card-date">
                    <?= $this->article->get_date()->format('d/m/Y') ?>
                </p>

                <h2 class="card-title">
                    <?= htmlspecialchars($this->article->get_title()) ?>
                </h2>

                <p class="card-excerpt">
                    <?= htmlspecialchars($this->article->get_description() ?: $this->article->get_summary() ?: '') ?>
                </p>

                <a href="<?= page('article.php', ['id' => $this->article->get_id()]) ?>" class="read-more-btn">Continua a leggere</a>
            </div>
        </article>
<?php }
}
