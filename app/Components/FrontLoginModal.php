<?php

namespace App\Components;

use Camezilla\Components\Component;

class FrontLoginModal extends Component {

    protected function build(): void { ?>
        <div class="modal-overlay" id="loginModal">
            <div class="modal-content">
                <button class="close-modal" id="closeModalBtn">&times;</button>
                <h2 class="modal-title">Accedi</h2>

                <form id="loginForm">
                    <div class="form-group">
                        <label for="email">Email Istituzionale</label>
                        <input type="email" id="email" placeholder="nome.cognome@studenti.iti.edu.it" required>
                        <small id="emailError" style="display:none;"></small>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" placeholder="Inserisci la tua password" required>
                    </div>

                    <button type="submit" class="modal-submit-btn">Entra</button>
                </form>
            </div>
        </div>
    <?php }
}