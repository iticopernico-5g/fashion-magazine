<?php 

namespace App\Components;

use Camezilla\Components\Component;
use App\Models\Article;

class ArticleItem extends Component {

    private Article $article;

    public function __construct(Article $article) {
        parent::__construct();
        $this->article = $article;
    }

    protected function build(): void { ?>
        <div>
            <h4><?= $this->article->get_title() ?></h4>
            <p><?= $this->article->get_description() ?></p>
            <p><?= $this->article->get_text() ?></p>
            <div>
                <form method="post" action="<?= action('article.php', 'delete', 'index.php') ?>">
                    <input type="hidden" name="id" value="<?= $this->article->get_id() ?>">
                    <button type="submit">Delete</button>
                </form>
                <a href="<?= page('article/edit.php', ['id' => $this->article->get_id()]) ?>">
                    Edit
                </a>
            </div>
        </div>
    <?php }
}