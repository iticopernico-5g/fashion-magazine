<?php

namespace App\Components;

use Camezilla\Components\Component;

class FrontHeader extends Component {

    protected function build(): void { ?>
        <header class="site-header">
            <div class="header-container">
                <a href="<?= page('index.php') ?>" class="logo">Copernico News</a>
                <div class="header-actions">
                    <?php
                        $role = get_session_item('authentication.role');
                        if (is_user_authenticated() && in_array($role, ['admin', 'student'], true)):
                    ?>
                        <a href="<?= page('users.php') ?>" class="login-header-btn login-header-btn--outline">PANNELLO</a>
                    <?php endif; ?>

                    <?php if (is_user_authenticated()): ?>
                        <a href="<?= action('account.php', 'logout', 'index.php') ?>" class="login-header-btn">ESCI</a>
                    <?php else: ?>
                        <button class="login-header-btn" type="button">ACCEDI</button>
                    <?php endif; ?>
                </div>
            </div>
        </header>
    <?php }
}