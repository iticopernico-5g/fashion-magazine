<?php

namespace App\Components;

use Camezilla\Components\Component;

class FrontLoginModal extends Component {

    protected function build(): void { ?>
        <div class="modal-overlay" id="loginModal">
            <div class="modal-content">
                <button class="close-modal" id="closeModalBtn">&times;</button>

                <!-- TAB LOGIN -->
                <div class="modal-tab-content active" id="login-tab">
                    <h2 class="modal-title">Accedi</h2>

                    <form id="loginForm" class="auth-form">
                        <div class="form-group">
                            <label for="login-email">Email Istituzionale</label>
                            <input type="email" id="login-email" name="email" placeholder="nome.cognome@studenti.iti.edu.it" required>
                            <small class="error-message" style="display:none;"></small>
                        </div>

                        <div class="form-group">
                            <label for="login-password">Password</label>
                            <input type="password" id="login-password" name="password" placeholder="Inserisci la tua password" required>
                        </div>

                        <button type="submit" class="modal-submit-btn">Accedi</button>
                    </form>
                </div>

                <!-- TAB REGISTER -->
                <div class="modal-tab-content" id="register-tab">
                    <h2 class="modal-title">Crea un account</h2>

                    <form id="registerForm" class="auth-form">
                        <div class="form-group">
                            <label for="reg-first-name">Nome</label>
                            <input type="text" id="reg-first-name" name="first_name" placeholder="Mario" required>
                        </div>

                        <div class="form-group">
                            <label for="reg-last-name">Cognome</label>
                            <input type="text" id="reg-last-name" name="last_name" placeholder="Rossi" required>
                        </div>

                        <div class="form-group">
                            <label for="reg-email">Email Istituzionale</label>
                            <input type="email" id="reg-email" name="email" placeholder="mario.rossi@studenti.iti.edu.it" required>
                            <small class="error-message" style="display:none;"></small>
                        </div>

                        <div class="form-group">
                            <label for="reg-password">Password</label>
                            <input type="password" id="reg-password" name="password" placeholder="Crea una password sicura" required>
                        </div>

                        <button type="submit" class="modal-submit-btn">Registrati</button>
                    </form>
                </div>
            </div>
        </div>
    <?php }
}