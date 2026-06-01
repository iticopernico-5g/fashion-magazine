<?php

namespace App\Components;

use Camezilla\Components\Component;
use App\Models\User;

class UserItem extends Component {

    private ?User $user;

    public function __construct(?User $user = null) {
        parent::__construct();   
        $this->user = $user;
    }

    protected function build(): void { ?>
        <tr>
            <td><?= $this->user?->get_id() ?></td>
            <td><?= htmlspecialchars($this->user?->get_first_name() ?? '') ?></td>
            <td><?= htmlspecialchars($this->user?->get_last_name() ?? '') ?></td>
            <td><?= htmlspecialchars($this->user?->get_email() ?? '') ?></td>
            <td>
                <span class="status-badge status-<?= $this->user?->get_role()?->value ?>">
                    <?= htmlspecialchars($this->user?->get_role()?->value ?? '') ?>
                </span>
            </td>
        </tr>
    <?php }
}