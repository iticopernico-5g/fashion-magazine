<?php
namespace Camezilla\Components;

abstract class Component {

    protected $children;

    public function __construct(Component|string|callable|null ...$children) {
        $this->children = $children;
        $this->on_create();
    }

    final public function render(): string {
        $this->on_before_render();

        ob_start();
        $this->build();
        $output = ob_get_clean();
        
        $this->on_after_render();

        return $output;
    }

    protected function render_children(): void {
        foreach ($this->children as $child) {
            if ($child instanceof Component) {
                echo $child->render();
            } elseif (is_string($child)) {
                echo e($child);
            }
            elseif (is_callable($child)) {
                $result = call_user_func($child);
                if (is_string($result)) {
                    echo e($result);
                } elseif ($result instanceof Component) {
                    echo $result->render();
                }
            }
        }
    }

    public function set_children(Component|string|null ...$children): void {
        $this->children = $children;
    }

    public function __toString(): string {
        return $this->render();
    }

    abstract protected function build(): void;

    protected function on_create(): void {}
    protected function on_before_render(): void {}
    protected function on_after_render(): void {}
}
