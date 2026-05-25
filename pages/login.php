<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Components\Login;
use App\Layouts\MainLayout;
use App\Models\Role;
use Camezilla\Pages\Page;

$page = new class extends Page {

    public function __construct() {

        $mode = $_GET['mode'] ?? 'login';

        parent::__construct(new MainLayout("Login"), function ($mode) { ?>

            <?= new Login($mode) ?>
            
        <?php }, $mode);
    }
};

echo $page->render();