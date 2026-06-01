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