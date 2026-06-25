document.addEventListener("DOMContentLoaded", () => {
  const hamburger = document.getElementById("rwHamburger");
  const navLinks = document.getElementById("rwNavLinks");
  const dropdownToggles = document.querySelectorAll(".rw-has-dropdown > a");

  // Hamburger toggle
  hamburger.addEventListener("click", () => {
    navLinks.classList.toggle("active");
    hamburger.classList.toggle("active");
    const expanded = hamburger.classList.contains("active");
    hamburger.setAttribute("aria-expanded", expanded);
  });

  // Dropdown toggle for mobile
  dropdownToggles.forEach(link => {
    link.addEventListener("click", (e) => {
      if (window.innerWidth <= 768) {
        e.preventDefault();
        const parentLi = link.parentElement;
        parentLi.classList.toggle("active");
      }
    });
  });

  // Auto close menu on link click (mobile)
  document.querySelectorAll(".rw-nav-links a").forEach(link => {
    link.addEventListener("click", () => {
      if (window.innerWidth <= 768) {
        navLinks.classList.remove("active");
        hamburger.classList.remove("active");
        hamburger.setAttribute("aria-expanded", false);
      }
    });
  });
});


  // Auto-hide message after 3 seconds
  const msgBox = document.getElementById('msg-box');
  if (msgBox) {
      setTimeout(() => {
          msgBox.style.transition = "opacity 0.5s";
          msgBox.style.opacity = 0;
          setTimeout(() => msgBox.remove(), 500);
      }, 3000);
  }
