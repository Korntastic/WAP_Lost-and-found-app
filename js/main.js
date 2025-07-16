// LOGIN MODAL TOGGLE
const openLogin = document.getElementById('openLogin');
const loginModal = document.getElementById('loginModal');
const closeLogin = document.getElementById('closeLogin');

if (openLogin && loginModal && closeLogin) {
  openLogin.addEventListener('click', () => {
    loginModal.classList.add('visible');
  });

  closeLogin.addEventListener('click', () => {
    loginModal.classList.remove('visible');
  });

  loginModal.querySelector('.modal-backdrop')?.addEventListener('click', () => {
    loginModal.classList.remove('visible');
  });
}

// REGISTER MODAL TOGGLE
const openRegister = document.getElementById('openRegister');
const registerModal = document.getElementById('registerModal');
const closeRegister = document.getElementById('closeRegister');
const backToLogin = document.getElementById('backToLogin');

if (openRegister && registerModal && loginModal) {
  openRegister.addEventListener('click', e => {
    e.preventDefault();
    loginModal.classList.remove('visible');
    registerModal.classList.add('visible');
  });
}

if (closeRegister && registerModal) {
  closeRegister.addEventListener('click', () => {
    registerModal.classList.remove('visible');
  });

  registerModal.querySelector('.modal-backdrop')?.addEventListener('click', () => {
    registerModal.classList.remove('visible');
  });
}

if (backToLogin && registerModal && loginModal) {
  backToLogin.addEventListener('click', e => {
    e.preventDefault();
    registerModal.classList.remove('visible');
    loginModal.classList.add('visible');
  });
}

// REPORT LOST MODAL
const openReportLostNav  = document.getElementById('openReportLostNav');
const openReportLostHero = document.getElementById('openReportLostHero');
const reportLostModal    = document.getElementById('reportLostModal');
const closeReportLost    = document.getElementById('closeReportLost');

if (reportLostModal && closeReportLost) {
  [openReportLostNav, openReportLostHero].forEach(btn => {
    if (btn) {
      btn.addEventListener('click', e => {
        e.preventDefault();
        reportLostModal.classList.add('visible');
      });
    }
  });

  closeReportLost.addEventListener('click', () => {
    reportLostModal.classList.remove('visible');
  });

  reportLostModal.querySelector('.modal-backdrop')?.addEventListener('click', () => {
    reportLostModal.classList.remove('visible');
  });
}
