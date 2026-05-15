<?php

namespace App\Layouts;

use App\Components\Navbar;
use Camezilla\Layouts\Layout;

class MainLayout extends Layout {

    public function __construct(string $title) {
        parent::__construct($title);
    }

    protected function build(): void { ?>
        <!DOCTYPE html>
        <html lang="<?= get_language() ?>">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <link rel="stylesheet" href="<?= resource('css/style.css') ?>">
                <title>
                    <?= $this->title ?>
                </title>
            </head>
            <body class="body">

                <?= new Navbar() ?>

                <main class="main">
                    <?php $this->render_content(); ?>
                </main>

            </body>
        </html>
    <?php }
}