<?php

namespace App\Components;

use Camezilla\Components\Component;

class Footer extends Component
{

    protected function build(): void 
    { ?>
        <footer class="site-footer">
            <div class="footer-content">

                <h2>Istituzione Scolastica ITI</h2>

                <p>Via Roma, 123 - 00100 Roma</p>

                <p>Email: contatti@edu.it</p>
            
            </div>
        </footer>
<?php }
}