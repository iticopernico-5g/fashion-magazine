<?php

namespace App\Components;

use Camezilla\Components\Component;

class FrontHeader extends Component {

    protected function build(): void { ?>
        <header class="site-header">
            <div class="header-container">
                <button class="mobile-menu-btn" type="button">☰</button>
                <div class="logo">I T I</div>
                <div class="header-actions">
                    <button class="login-header-btn" type="button">ACCEDI</button>
                </div>
            </div>
        </header>
    <?php }
}