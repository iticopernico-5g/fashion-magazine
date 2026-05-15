<?php

namespace App\Components;

use Camezilla\Components\Component;
use App\Models\Article;

class ArticleForm extends Component {

    private string $mode;
    private string $redirect;
    private ?Article $article;

    public function __construct(string $redirect, ?Article $article = null) {
        parent::__construct();
        $this->mode = $article ? 'update' : 'create';
        $this->redirect = $redirect;
        $this->article = $article;
    }
    
    protected function build(): void { ?>
        <h3><?= $this->mode === 'edit' ? 'Edit Article' : 'Add Article' ?></h3>
        <form method="post" action="<?= action('article.php', $this->mode, $this->redirect)  ?>">
            <input type="hidden" name="id" value="<?= $this->article ? $this->article->get_id() : '' ?>">
            <input type="text" name="title" placeholder="Title" value="<?= $this->article ? $this->article->get_title() : '' ?>" required>
            <input type="text" name="description" placeholder="Description" value="<?= $this->article ? $this->article->get_description() : '' ?>" required>
            <input type="text" name="text" placeholder="Text" value="<?= $this->article ? $this->article->get_text() : '' ?>" required>
            <button type="submit"><?= $this->mode === 'edit' ? 'Update' : 'Create' ?></button>
        </form>
    <?php }
}