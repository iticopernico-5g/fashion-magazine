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
      const response = await fetch('actions/account.php?path=login', {
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

        // Apri nuova tab se admin/student, altrimenti reload
        setTimeout(() => {
          if (data.role === 'admin' || data.role === 'student') {
            window.open(data.redirect, '_blank');
          }
          // Reload la pagina attuale (rimane aperta)
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
      const response = await fetch('actions/account.php?path=register', {
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

// ===== ESPANSIONE "CONTINUA A LEGGERE" =====
const articlesData = {
  hero: {
    fullContent: `
      <p>Il 15 maggio 2026 è stato inaugurato il nuovo laboratorio di design dell'ITI, uno spazio all'avanguardia di oltre 300 mq dedicato alla creatività e all'innovazione.</p>
      <p>Il laboratorio è dotato di 25 postazioni iMac con software professionali, plotter da stampa di grande formato, stampanti 3D e un'area dedicata alla realtà virtuale.</p>
      <p>I corsi che utilizzeranno il nuovo laboratorio partiranno già dal prossimo anno scolastico.</p>
    `
  },
  card1: {
    fullContent: `
      <p>La squadra dell'ITI ha conquistato il primo posto alle Olimpiadi Nazionali di Informatica (Milano, 20–22 maggio 2026).</p>
      <p>La vittoria qualifica l'ITI per le Olimpiadi Europee di Informatica che si terranno a Praga a settembre 2026.</p>
    `
  },
  card2: {
    fullContent: `
      <p>Oltre 3.500 euro raccolti durante la giornata di beneficenza organizzata dagli studenti dell'ITI.</p>
      <p>Il ricavato sosterrà un progetto per la costruzione di una piccola biblioteca in Senegal.</p>
    `
  },
    mcard1: {
    fullContent: `
      <p>Minimalismo e stile: un outfit essenziale ma curato, perfetto per la vita scolastica.</p>
      <p>Dettagli: palette neutra, linee pulite, accessori ridotti al minimo.</p>
    `
  },
  mcard2: {
    fullContent: `
      <p>Dietro le quinte della sfilata: preparazione, prove e ultimi ritocchi prima di andare in passerella.</p>
      <p>Un lavoro di squadra tra designer, modelle/i e laboratorio.</p>
    `
  }
};

function createFullContentContainer(content) {
  const container = document.createElement('div');
  container.className = 'card-full-content';
  container.innerHTML = content;

  const collapseBtn = document.createElement('button');
  collapseBtn.className = 'collapse-btn';
  collapseBtn.type = 'button';
  collapseBtn.textContent = 'Leggi meno ▲';

  collapseBtn.addEventListener('click', function (e) {
    e.stopPropagation();

    const card = this.closest('.news-card') || this.closest('.hero-article');
    if (!card) return;

    card.classList.remove('expanded');

    const readMoreBtn = card.querySelector('.read-more-btn');
    if (readMoreBtn) readMoreBtn.textContent = 'Continua a leggere';

    setTimeout(() => {
      const fullContent = card.querySelector('.card-full-content');
      if (fullContent) fullContent.remove();
    }, 200);
  });

  container.appendChild(collapseBtn);
  return container;
}

document.addEventListener('click', function (e) {
  const readMoreBtn = e.target.closest('.read-more-btn');
  if (!readMoreBtn) return;

  e.preventDefault();

  const card = readMoreBtn.closest('.hero-article') || readMoreBtn.closest('.news-card');
  if (!card) return;

  if (card.classList.contains('expanded')) return;

  const isHero = readMoreBtn.classList.contains('read-more-hero');
  const contentId = isHero ? readMoreBtn.dataset.article : readMoreBtn.dataset.card;

  const articleData = articlesData[contentId];

  const fullContentContainer = createFullContentContainer(
    articleData?.fullContent ?? '<p>Contenuto completo in fase di caricamento...</p>'
  );

  card.appendChild(fullContentContainer);
  fullContentContainer.offsetHeight; // force reflow
  card.classList.add('expanded');

  readMoreBtn.textContent = 'Articolo espanso ▼';
});