<?php

namespace App\Components;

use Camezilla\Components\Component;

class Footer extends Component
{

    protected function build(): void 
    { ?>
        <footer class="site-footer">
            <div class="footer-content">

                <h2>Copernico News</h2>
                <p>Via Pontegradella, 1 — 44123 Ferrara (FE)</p>
                <p>Tel: 0532 62484 &nbsp;|&nbsp; Email: feis01400v@istruzione.it</p>
                <p style="margin-top:12px;font-size:12px;color:#555;letter-spacing:1px;text-transform:uppercase">© <?= date('Y') ?> Copernico News — Ferrara</p>
            
            </div>
        </footer>
<?php }
}