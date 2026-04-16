<?php

namespace Camezilla\Pages;

use Camezilla\Layouts\Layout;
use Camezilla\Components\Component;

abstract class Page {

    protected Layout $layout;
    protected $content;
    protected $data;

    public function __construct(Layout $layout, callable $content, $data = null) {
        $this->layout = $layout;
        $this->content = $content;
        $this->data = $data;
    }

    public function render(): string {
        $content = $this->content;
        $data = $this->data;
        $this->layout->set_content_callable(function() use ($content, $data) {
            $content($data);
        });

        return $this->layout->render();
    }
}