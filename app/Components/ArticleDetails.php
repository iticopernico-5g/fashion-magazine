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

        <div class="vogue-article">

            <!-- KICKER -->
            <div class="vogue-kicker">
                <span class="vogue-category"><?= htmlspecialchars(strtoupper(str_replace('_', ' ', $this->article->get_category()->value))) ?></span>
                <span class="vogue-kicker-line"></span>
            </div>

            <!-- TITOLO -->
            <h1 class="vogue-title"><?= htmlspecialchars($this->article->get_title()) ?></h1>

            <!-- SOMMARIO / DESCRIZIONE -->
            <?php if ($this->article->get_description()): ?>
                <p class="vogue-standfirst"><?= htmlspecialchars($this->article->get_description()) ?></p>
            <?php elseif ($this->article->get_summary()): ?>
                <p class="vogue-standfirst"><?= htmlspecialchars($this->article->get_summary()) ?></p>
            <?php endif; ?>

            <!-- META -->
            <div class="vogue-meta">
                <span class="vogue-author">di <strong><?= htmlspecialchars($this->article->get_author()) ?></strong></span>
                <span class="vogue-meta-sep">—</span>
                <time class="vogue-date"><?= $this->article->get_date()->format('d F Y') ?></time>
            </div>

            <!-- DIVISORE -->
            <div class="vogue-rule"></div>

            <!-- IMMAGINE HERO -->
            <figure class="vogue-hero-image">
                <img src="<?= resource('images/albero.jpg') ?>" alt="<?= htmlspecialchars($this->article->get_title()) ?>">
            </figure>

            <!-- CORPO TESTO -->
            <?php if ($this->article->get_text()): ?>
                <div class="vogue-body">
                    <?php
                    $paragraphs = array_filter(explode("\n", $this->article->get_text()));
                    $first = true;
                    foreach ($paragraphs as $para):
                        $para = trim($para);
                        if (!$para) continue;
                    ?>
                        <p <?= $first ? 'class="vogue-drop-cap"' : '' ?>><?= htmlspecialchars($para) ?></p>
                    <?php
                        $first = false;
                    endforeach;
                    ?>
                </div>
            <?php endif; ?>

            <!-- LINK FONTE -->
            <?php if ($this->article->get_link()): ?>
                <div class="vogue-source">
                    <span>Leggi la fonte originale</span>
                    <a href="<?= htmlspecialchars($this->article->get_link()) ?>" target="_blank" rel="noopener">
                        <?= htmlspecialchars($this->article->get_link()) ?> →
                    </a>
                </div>
            <?php endif; ?>

            <!-- FOOTER ARTICOLO -->
            <div class="vogue-article-footer">
                <a href="<?= page('index.php') ?>" class="vogue-back">← Torna alla home</a>
            </div>

        </div>

    <?php }
}
