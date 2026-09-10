// ============================================
// ATS — Association pour le Développement Social
// ============================================

document.addEventListener('DOMContentLoaded', () => {

  // ----- Année dans le footer -----
  const yearEl = document.getElementById('year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  // ----- Menu mobile -----
  const navToggle = document.getElementById('navToggle');
  const navLinks = document.getElementById('navLinks');

  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
      const isOpen = navLinks.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('is-open');
        navToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // ----- Lightbox vidéo des projets -----
  const lightbox = document.getElementById('lightbox');
  const lightboxBackdrop = document.getElementById('lightboxBackdrop');
  const lightboxClose = document.getElementById('lightboxClose');
  const lightboxVideo = document.getElementById('lightboxVideo');
  const lightboxTitle = document.getElementById('lightboxTitle');
  const lightboxDesc = document.getElementById('lightboxDesc');
  const projectCards = document.querySelectorAll('.project-card');

  function openLightbox(videoSrc, title, desc) {
    lightboxVideo.setAttribute('src', videoSrc);
    lightboxTitle.textContent = title;
    lightboxDesc.textContent = desc;
    lightbox.classList.add('is-open');
    lightbox.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    lightboxVideo.play().catch(() => { /* autoplay peut être bloqué, l'utilisateur cliquera play */ });
  }

  function closeLightbox() {
    lightbox.classList.remove('is-open');
    lightbox.setAttribute('aria-hidden', 'true');
    lightboxVideo.pause();
    lightboxVideo.removeAttribute('src');
    lightboxVideo.load();
    document.body.style.overflow = '';
  }

  projectCards.forEach(card => {
    card.addEventListener('click', () => {
      const videoSrc = card.getAttribute('data-video');
      const title = card.getAttribute('data-title');
      const desc = card.getAttribute('data-desc');
      openLightbox(videoSrc, title, desc);
    });
  });

  if (lightboxBackdrop) lightboxBackdrop.addEventListener('click', closeLightbox);
  if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && lightbox.classList.contains('is-open')) closeLightbox();
  });

  // ----- Formulaire de contact (envoi vers contact.php) -----
  const contactForm = document.getElementById('contactForm');
  const formNote = document.getElementById('formNote');
  const submitBtn = document.getElementById('cfSubmit');

  if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      formNote.classList.remove('is-error');
      formNote.textContent = '';
      submitBtn.disabled = true;
      submitBtn.textContent = 'Envoi en cours…';

      try {
        const response = await fetch(contactForm.getAttribute('action'), {
          method: 'POST',
          headers: { 'Accept': 'application/json' },
          body: new FormData(contactForm)
        });

        const data = await response.json();

        if (data.success) {
          formNote.textContent = data.message;
          contactForm.reset();
        } else {
          formNote.classList.add('is-error');
          formNote.textContent = data.message || "Une erreur est survenue. Merci de réessayer.";
        }
      } catch (err) {
        formNote.classList.add('is-error');
        formNote.textContent = "Impossible d'envoyer le message pour le moment. Merci de réessayer ou de nous écrire directement par email.";
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Envoyer le message';
      }
    });
  }

  // ----- Nav : fond plus marqué au scroll -----
  const nav = document.getElementById('nav');
  let lastState = false;
  window.addEventListener('scroll', () => {
    const scrolled = window.scrollY > 40;
    if (scrolled !== lastState) {
      nav.style.background = scrolled ? 'rgba(8,15,28,0.85)' : 'rgba(8,15,28,0.55)';
      lastState = scrolled;
    }
  }, { passive: true });

});
