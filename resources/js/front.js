// ===== CARD ARTICOLO CLICCABILE =====
document.addEventListener('click', function(e) {
    const card = e.target.closest('.news-card[data-url]');
    if (!card) return;
    if (e.target.closest('button')) return;
    window.location.href = card.dataset.url;
});

// ===== GESTIONE MODALE =====
const loginBtn = document.querySelector('.login-header-btn');
const modal = document.getElementById('loginModal');
const closeBtn = document.getElementById('closeModalBtn');

if (loginBtn && modal && closeBtn) {
  loginBtn.addEventListener('click', () => {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  });

  closeBtn.addEventListener('click', () => {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  });

  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  });
}

// ===== TAB SWITCHER (LOGIN / REGISTRAZIONE) =====
const tabBtns = document.querySelectorAll('.modal-tab-btn');
const tabContents = document.querySelectorAll('.modal-tab-content');

if (tabBtns.length > 0) {
  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      // Rimuovi active da tutti
      tabBtns.forEach(b => b.classList.remove('active'));
      tabContents.forEach(c => c.classList.remove('active'));

      // Aggiungi active al tab cliccato
      btn.classList.add('active');
      const tabName = btn.dataset.tab;
      document.getElementById(`${tabName}-tab`).classList.add('active');
    });
  });
}

// ===== POPUP DI CONFERMA / ERRORE =====
function showPopup(type, title, message) {
  const popup = document.createElement('div');
  popup.className = `popup popup-${type}`;
  popup.innerHTML = `
    <div class="popup-content">
      <div class="popup-icon">
        ${type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ'}
      </div>
      <h3 class="popup-title">${title}</h3>
      <p class="popup-message">${message}</p>
      <button class="popup-btn">OK</button>
    </div>
  `;

  document.body.appendChild(popup);

  // Mostra popup con animazione
  setTimeout(() => popup.classList.add('active'), 10);

  // Chiudi popup
  const closePopupBtn = popup.querySelector('.popup-btn');
  closePopupBtn.addEventListener('click', () => {
    popup.classList.remove('active');
    setTimeout(() => popup.remove(), 300);
  });

  // Chiudi popup dopo 5 secondi (solo per success)
  if (type === 'success') {
    setTimeout(() => {
      if (popup.parentNode) {
        popup.classList.remove('active');
        setTimeout(() => popup.remove(), 300);
      }
    }, 5000);
  }
}

// ===== LOGIN FORM =====
const loginForm = document.getElementById('loginForm');
if (loginForm) {
  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.querySelector('#login-email').value;
    const password = document.querySelector('#login-password').value;

    if (!email || !password) {
      showPopup('error', 'Errore', 'Inserisci email e password');
      return;
    }

    try {
      const response = await fetch(window.BASE_URL + '/actions/account.php?path=login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
          email: email,
          password: password
        })
      });

      const data = await response.json();

      if (data.ok) {
        // Login riuscito
        showPopup('success', 'Accesso effettuato', `Benvenuto, ${data.email}!`);

        // Chiudi modal
        setTimeout(() => {
          modal.classList.remove('active');
          document.body.style.overflow = '';
        }, 1000);

        // Admin/student: apri pannello in nuova tab, index rimane aperta
        // Viewer: ricarica l'header per mostrare "Esci"
        setTimeout(() => {
          if (data.role === 'admin' || data.role === 'student') {
            window.open(data.redirect, '_blank');
          }
          window.location.reload();
        }, 1500);
      } else {
        // Login fallito
        showPopup('error', 'Errore di accesso', data.error || 'Email o password errati');
      }
    } catch (error) {
      console.error('Errore:', error);
      showPopup('error', 'Errore di connessione', 'Prova di nuovo più tardi');
    }
  });
}

// ===== REGISTRAZIONE FORM =====
const registerForm = document.getElementById('registerForm');
if (registerForm) {
  registerForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const firstName = document.querySelector('#reg-first-name').value;
    const lastName = document.querySelector('#reg-last-name').value;
    const email = document.querySelector('#reg-email').value;
    const password = document.querySelector('#reg-password').value;

    if (!firstName || !lastName || !email || !password) {
      showPopup('error', 'Errore', 'Compila tutti i campi');
      return;
    }

    try {
      const response = await fetch(window.BASE_URL + '/actions/account.php?path=register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
          first_name: firstName,
          last_name: lastName,
          email: email,
          password: password,
          role: 'viewer' // Default: i nuovi utenti sono viewer
        })
      });

      const data = await response.json();

      if (data.ok) {
        // Registrazione riuscita
        showPopup('success', 'Registrazione completata', 'Benvenuto! Puoi accedere con le tue credenziali.');

        // Chiudi form registrazione e torna a login
        setTimeout(() => {
          document.querySelector('[data-tab="login"]').click();
        }, 2000);

        // Reset form
        registerForm.reset();
      } else {
        // Registrazione fallita
        showPopup('error', 'Errore di registrazione', data.error || 'Questa email è già registrata');
      }
    } catch (error) {
      console.error('Errore:', error);
      showPopup('error', 'Errore di connessione', 'Prova di nuovo più tardi');
    }
  });
}

