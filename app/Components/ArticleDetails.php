<?php 

namespace App\Components;   

use Camezilla\Components\Component;
use App\Models\Article;

class ArticleDetails extends Component {

    private Article $article;

    public function __construct(Article $article) {
        parent::__construct();
        $this->article = $article;
    }

    protected function build(): void { ?>
        <article class="article-detail">
            <div class="article-detail-meta">
                <span class="category-tag">
                    <?= htmlspecialchars(strtoupper($this->article->get_category()->value)) ?>
                </span>
                <span class="card-date"><?= $this->article->get_date()->format('d/m/Y') ?></span>
            </div>

            <h1 class="article-detail-title"><?= htmlspecialchars($this->article->get_title()) ?></h1>

            <?php if ($this->article->get_description()): ?>
                <p class="article-detail-description"><?= htmlspecialchars($this->article->get_description()) ?></p>
            <?php endif; ?>

            <p class="article-detail-author">di <strong><?= htmlspecialchars($this->article->get_author()) ?></strong></p>

            <?php if ($this->article->get_text()): ?>
                <div class="article-detail-text">
                    <?= nl2br(htmlspecialchars($this->article->get_text())) ?>
                </div>
            <?php endif; ?>

            <?php if ($this->article->get_link()): ?>
                <a href="<?= htmlspecialchars($this->article->get_link()) ?>" target="_blank" class="article-detail-link">
                    Fonte originale →
                </a>
            <?php endif; ?>

            <a href="<?= page('index.php') ?>" class="read-more-btn" style="display:inline-block;margin-top:2rem">
                ← Torna alla home
            </a>
        </article>

        <style>
        .article-detail { max-width: 760px; margin: 2rem auto; padding: 0 1rem; }
        .article-detail-meta { display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem; }
        .article-detail-title { font-size: 2rem; line-height: 1.2; margin-bottom: 1rem; }
        .article-detail-description { font-size: 1.1rem; color: #555; font-style: italic; margin-bottom: 1rem; border-left: 3px solid #e8c946; padding-left: 1rem; }
        .article-detail-author { font-size: .9rem; color: #888; margin-bottom: 2rem; }
        .article-detail-text { font-size: 1rem; line-height: 1.9; color: #333; }
        .article-detail-text p { margin-bottom: 1rem; }
        .article-detail-link { display: inline-block; margin-top: 2rem; color: #111; font-weight: 600; text-decoration: underline; }
        </style>
    <?php }
}