<?php

namespace App\Components;

use Camezilla\Components\Component;
use App\Components\UserItem;

class UserGroup extends Component {

    private array $users;

    public function __construct(array $users) {
        parent::__construct();
        $this->users = $users;
    }

    protected function build(): void { ?>
        <table class="articles-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Email</th>
                    <th>Ruolo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($this->users)): ?>
                    <tr><td colspan="5" class="empty-row">Nessun utente trovato.</td></tr>
                <?php else: ?>
                    <?php foreach ($this->users as $user): ?>
                        <?= new UserItem($user) ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php }
}