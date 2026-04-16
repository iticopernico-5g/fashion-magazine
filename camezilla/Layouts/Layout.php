<?php
namespace Camezilla\Layouts;

use Camezilla\Components\Component;

abstract class Layout extends Component {

    protected string $title;
    protected $content_callable = null;

    public function __construct(string $title) {
        $this->title = $title;
        parent::__construct(null);
    }

    public function set_content_callable(callable $content): void {
        $this->content_callable = $content;
    }

    protected function render_content(): void {
        if ($this->content_callable) {
            ($this->content_callable)();
        }
    }

    protected function get_content(): Component {
        return $this->children[0];
    }
}
