document.addEventListener('DOMContentLoaded', function () {
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }

  // Smooth scroll for view works button
  const viewWorksBtn = document.getElementById('viewWorksBtn');
  if (viewWorksBtn) {
    viewWorksBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const target = document.querySelector('#featured');
      if (target) target.scrollIntoView({ behavior: 'smooth' });
    });
  }

  // Profile dropdown (desktop)
  const profileBtn = document.getElementById('profileBtn');
  const profileDropdown = document.getElementById('profileDropdown');

  if (profileBtn && profileDropdown) {
    profileBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isHidden = profileDropdown.classList.contains('hidden');
      if (isHidden) {
        profileDropdown.classList.remove('hidden');
        profileBtn.setAttribute('aria-expanded', 'true');
      } else {
        profileDropdown.classList.add('hidden');
        profileBtn.setAttribute('aria-expanded', 'false');
      }
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
      if (!profileDropdown.classList.contains('hidden')) {
        if (!profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
          profileDropdown.classList.add('hidden');
          profileBtn.setAttribute('aria-expanded', 'false');
        }
      }
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        if (!profileDropdown.classList.contains('hidden')) {
          profileDropdown.classList.add('hidden');
          profileBtn.setAttribute('aria-expanded', 'false');
        }
      }
    });
  }
});
