<?php 

namespace App\Components;

use Camezilla\Components\Component;
use App\Models\Article;

class ArticleGroup extends Component {

    private array $article;

    public function __construct(array $article) {
        parent::__construct();
        $this->article = $article;
    }

    protected function build(): void { ?>
        <?= get_action_error() ?>
        <h3>Articles Group</h3>
        <div>
            <?php foreach ($this->article as $article): ?>
                <?= new ArticleItem($article) ?>
            <?php endforeach; ?>
        </div>
    <?php }
}