'use strict';

// Utility function
const addEventOnElem = (elem, type, callback) => {
  if (!elem) return;

  if (elem instanceof NodeList || Array.isArray(elem)) {
    elem.forEach(el => el && el.addEventListener(type, callback));
    return;
  }

  elem.addEventListener(type, callback);
};

// DOM Ready (ONLY ONCE)
document.addEventListener("DOMContentLoaded", () => {
  initNavbar();
  initLanguageToggle();
  initPageViews();
  initPopupForm();
  initFormSubmissions();
  initChatToggle();
  initBackToTopButton();
  initFaqAccordion();
  initLoadMoreBlogs();
  initCarousel();
});

// Navbar
function initNavbar() {
  const navbar = document.querySelector("[data-navbar]");
  const navbarLinks = document.querySelectorAll("[data-nav-link]");
  const navbarToggler = document.querySelector("[data-nav-toggler]");

  if (!navbar || !navbarToggler) return;

  const toggleNav = () => {
    navbar.classList.toggle("active");
    navbarToggler.classList.toggle("active");
  };

  addEventOnElem(navbarToggler, "click", toggleNav);

  const closeNav = () => {
    navbar.classList.remove("active");
    navbarToggler.classList.remove("active");
  };

  addEventOnElem(navbarLinks, "click", closeNav);
}

// Language Toggle
function initLanguageToggle() {
  const langToggle = document.getElementById("languageToggle");
  if (!langToggle) return;

  const blogEn = document.getElementById("blog-en");
  const blogBn = document.getElementById("blog-bn");
  let currentLang = localStorage.getItem("lang") || "ENG";

  function setLanguage(lang) {
    document.body.classList.remove("lang-eng", "lang-bn");
    document.body.classList.add(lang === "BAN" ? "lang-bn" : "lang-eng");

    if (blogEn && blogBn) {
      blogEn.style.display = lang === "BAN" ? "none" : "block";
      blogBn.style.display = lang === "BAN" ? "block" : "none";
      langToggle.textContent = lang === "BAN" ? "ENG" : "BAN";
    }

    document.querySelectorAll("[data-translation]").forEach(el => {
      const key = lang === "BAN" ? "data-lang-ban" : "data-lang-eng";
      if (el.hasAttribute(key)) el.innerHTML = el.getAttribute(key);
    });

    localStorage.setItem("lang", lang);
    document.dispatchEvent(new CustomEvent("languageChanged", { detail: { lang } }));
  }

  setLanguage(currentLang);

  langToggle.addEventListener("click", () => {
    currentLang = currentLang === "ENG" ? "BAN" : "ENG";
    setLanguage(currentLang);
  });
}

// Toast notification
function showToast(message, type = "success") {
  const existing = document.querySelector(".toast-notification");
  if (existing) existing.remove();

  const toast = document.createElement("div");
  toast.className = `toast-notification toast-${type}`;
  toast.textContent = message;
  document.body.appendChild(toast);

  requestAnimationFrame(() => toast.classList.add("show"));
  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// Form Submissions
const APPOINTMENT_SHEET_URL = "https://script.google.com/macros/s/AKfycbzwG_e3As_Ymeadtfi1cbkZyg3q7xVhmfUhCB-ZuKpg3LVikxJKT-mfLwPj5HThC-jfvQ/exec";
const PHONE_SHEET_URL = "https://script.google.com/macros/s/AKfycbyyhyKWODeLPB9fDxxE7MisAK3c4mG5MfgXImHRyfk3MCkV2AOFSv-9zXhfvmJEznlq0Q/exec";

function getFormAction(form, fallback) {
  const action = form.getAttribute("action");
  return (action && action.startsWith("https://script.google.com/")) ? action : fallback;
}

function initFormSubmissions() {
  const appointmentForm = document.getElementById("appointmentForm");
  const phoneForm = document.getElementById("phoneForm");

  if (appointmentForm) {
    appointmentForm.addEventListener("submit", e => {
      e.preventDefault();
      const submitBtn = appointmentForm.querySelector("[type='submit']");
      const originalText = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = "Submitting...";

      const formData = new URLSearchParams(new FormData(appointmentForm));
      fetch(getFormAction(appointmentForm, APPOINTMENT_SHEET_URL), {
        method: "POST",
        mode: "no-cors",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: formData
      })
      .then(() => {
        showToast("Submitted successfully!");
        appointmentForm.reset();
        const popup = document.getElementById("popupForm");
        if (popup) popup.style.display = "none";
      })
      .catch(() => showToast("Submission failed. Please try again.", "error"))
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      });
    });
  }

  if (phoneForm) {
    phoneForm.addEventListener("submit", e => {
      e.preventDefault();
      const submitBtn = phoneForm.querySelector("[type='submit']");
      const originalText = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = "Submitting...";

      const formData = new URLSearchParams(new FormData(phoneForm));
      fetch(getFormAction(phoneForm, PHONE_SHEET_URL), {
        method: "POST",
        mode: "no-cors",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: formData
      })
      .then(() => {
        showToast("Thank you! We'll call you soon.");
        phoneForm.reset();
      })
      .catch(() => showToast("Submission failed. Please try again.", "error"))
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      });
    });
  }
}

// Popup Form
function initPopupForm() {
  const popupForm = document.getElementById("popupForm");
  const buttons = document.querySelectorAll(".appointment-btn");
  const closeBtn = document.getElementById("popupCloseBtn");

  if (!popupForm || !buttons.length) return;

  buttons.forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      popupForm.style.display = "flex";
    });
  });

  if (closeBtn) {
    closeBtn.addEventListener("click", () => {
      popupForm.style.display = "none";
    });
  }

  popupForm.addEventListener("click", e => {
    if (e.target === popupForm) popupForm.style.display = "none";
  });
}

// Chat Toggle
function initChatToggle() {
  const btn = document.getElementById("chatToggleBtn");
  const list = document.querySelector(".chaty-channel-list");
  if (!btn || !list) return;

  btn.addEventListener("click", e => {
    e.stopPropagation();
    list.classList.toggle("active");
  });

  document.addEventListener("click", e => {
    if (!list.contains(e.target)) list.classList.remove("active");
  });
}

// Back To Top
function initBackToTopButton() {
  const btn = document.querySelector(".back-top-btn");
  if (!btn) return;

  window.addEventListener("scroll", () => {
    btn.classList.toggle("active", window.scrollY > 300);
  });

  btn.addEventListener("click", e => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}

// FAQ Accordion
function initFaqAccordion() {
  const questions = document.querySelectorAll(".faq-question");
  if (!questions.length) return;

  questions.forEach(btn => {
    btn.addEventListener("click", () => {
      const item = btn.parentElement;
      const open = item.classList.contains("open");

      document.querySelectorAll(".faq-item").forEach(i => {
        i.classList.remove("open");
        const q = i.querySelector(".faq-question");
        if (q) q.setAttribute("aria-expanded", "false");
      });

      if (!open) {
        item.classList.add("open");
        btn.setAttribute("aria-expanded", "true");
      }
    });
  });
}

// Views count
function initPageViews() {
  // Only for blog pages
  if (!location.pathname.startsWith("/blogs/")) return;

  const blogMeta = document.querySelector(".blog-meta");
  if (!blogMeta) return;

  const slug = location.pathname.replace(/\/$/, "");
  let cachedCount = null;

  function render(lang) {
    // Remove old view UI (important because language toggle rewrites innerHTML)
    const old = blogMeta.querySelector(".page-views");
    if (old) old.remove();

    const label = (lang === "BAN") ? "ভিউ" : "Views";
    const val = (cachedCount === null) ? "" : String(cachedCount);

    blogMeta.insertAdjacentHTML(
      "beforeend",
      `<span class="page-views"> | ${label}: <span id="pageViewCount">${val}</span></span>`
    );
  }

  // Initial render using saved lang
  const currentLang = localStorage.getItem("lang") || "ENG";
  render(currentLang);

  // Re-render after language changes (because your translator overwrites blog-meta)
  document.addEventListener("languageChanged", (e) => {
    render(e.detail?.lang || (localStorage.getItem("lang") || "ENG"));
  });

  // Hit the server counter
  fetch(`/api/views.php?slug=${encodeURIComponent(slug)}`, { cache: "no-store" })
    .then(r => r.json())
    .then(data => {
      if (!data || !data.ok) return;
      cachedCount = data.count;

      // Update current visible number
      const el = document.getElementById("pageViewCount");
      if (el) el.textContent = String(cachedCount);
    })
    .catch(() => {
      // fail silently (don't break page)
    });
}

// Load More Blogs
function initLoadMoreBlogs() {
  const btn = document.getElementById("loadMoreBlogs");
  const blogList = document.querySelector(".blog-list");
  if (!btn || !blogList) return;

  btn.addEventListener("click", () => {
    blogList.classList.add("show-all");
    btn.style.display = "none";
  });
}

// Doctor Carousel
function initCarousel() {
  const scrollbar = document.querySelector(".has-scrollbar");
  const prevBtn = document.querySelector("[data-carousel-prev]");
  const nextBtn = document.querySelector("[data-carousel-next]");
  if (!scrollbar || !prevBtn || !nextBtn) return;

  const getScrollAmount = () => {
    const item = scrollbar.querySelector(".scrollbar-item");
    return item ? item.offsetWidth + 30 : 300;
  };

  prevBtn.addEventListener("click", () => {
    scrollbar.scrollBy({ left: -getScrollAmount(), behavior: "smooth" });
  });

  nextBtn.addEventListener("click", () => {
    scrollbar.scrollBy({ left: getScrollAmount(), behavior: "smooth" });
  });
}
