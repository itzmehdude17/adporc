// itzmehdude //

'use strict';

// utility function to add event listeners to multiple elements //
const addEventOnElem = (elem, type, callback) => {
  if (elem.length > 1) {
    elem.forEach((el) => el.addEventListener(type, callback));
  } else {
    elem.addEventListener(type, callback);
  }
};

// initialize all functionality on DOMContentLoaded //
document.addEventListener("DOMContentLoaded", () => {
  initNavbar();
  initLanguageToggle();
  initPopupForm();
  // initFormSubmissions(); // REMOVE or COMMENT OUT
  initChatToggle();
  initBackToTopButton();
  // initBlogCards(); // REMOVE or COMMENT OUT
});

// navbar toggle functionality //
function initNavbar() {
  const navbar = document.querySelector("[data-navbar]");
  const navbarLinks = document.querySelectorAll("[data-nav-link]");
  const navbarToggler = document.querySelector("[data-nav-toggler]");

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

function initLanguageToggle() {
  const langToggle = document.getElementById("languageToggle");
  const blogEn = document.getElementById("blog-en");
  const blogBn = document.getElementById("blog-bn");
  let currentLang = localStorage.getItem("lang") || "ENG";

  function setLanguage(lang) {
    // toggle blog articles if they exist
    if (blogEn && blogBn) {
      if (lang === "BAN") {
        blogEn.style.display = "none";
        blogBn.style.display = "block";
        langToggle.textContent = "ENG";
      } else {
        blogEn.style.display = "block";
        blogBn.style.display = "none";
        langToggle.textContent = "BAN";
      }
      localStorage.setItem("lang", lang);
    } else if (langToggle) {
      // if not on blog page, just update button text
      langToggle.textContent = lang === "BAN" ? "ENG" : "BAN";
    }

    // update all data-translation elements everywhere
    document.querySelectorAll("[data-translation]").forEach((element) => {
      const translationKey = lang === "BAN" ? "data-lang-ban" : "data-lang-eng";
      if (element.hasAttribute(translationKey)) {
        element.innerHTML = element.getAttribute(translationKey);
      }
    });

    // placeholders
    const placeholders = [
      { id: "name", eng: "Enter your name", ban: "আপনার নাম লিখুন" },
      { id: "phone", eng: "Enter your phone number", ban: "আপনার ফোন নম্বর লিখুন" },
      { id: "age", eng: "Enter your age", ban: "সংখ্যায় আপনার বয়স লিখুন" },
      { id: "gender", eng: "Select your gender", ban: "আপনার লিঙ্গ নির্বাচন করুন" },
      { id: "address", eng: "Enter your address", ban: "আপনার ঠিকানা লিখুন" },
      { id: "complaint", eng: "Enter your chief complaint", ban: "আপনার মূল সমস্যা সম্পর্কে লিখুন" },
    ];

    placeholders.forEach((field) => {
      const input = document.getElementById(field.id);
      if (input) {
        input.setAttribute("placeholder", lang === "BAN" ? field.ban : field.eng);
      }
    });
  }

  setLanguage(currentLang);

  if (langToggle) {
    langToggle.addEventListener("click", () => {
      currentLang = currentLang === "ENG" ? "BAN" : "ENG";
      setLanguage(currentLang);
      localStorage.setItem("lang", currentLang);
    });
  }
}

document.addEventListener("DOMContentLoaded", function() {
  initLanguageToggle();
});

// form submission functionality //
document.addEventListener("DOMContentLoaded", function() {
  const appointmentForm = document.getElementById("appointmentForm");
  if (appointmentForm) {
    appointmentForm.addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(appointmentForm);
      fetch("https://script.google.com/macros/s/AKfycbzwG_e3As_Ymeadtfi1cbkZyg3q7xVhmfUhCB-ZuKpg3LVikxJKT-mfLwPj5HThC-jfvQ/exec", {
        method: "POST",
        body: formData
      })
        .then((response) => {
          alert("Submitted Successfully!");
          appointmentForm.reset();
          document.getElementById("popupForm").style.display = "none";
        })
        .catch((error) => {
          alert("Submission failed. Please try again.");
        });
    });
  }
});

// phone number submission functionality //
document.addEventListener("DOMContentLoaded", function() {
  const phoneForm = document.getElementById("phoneForm");
  if (phoneForm) {
    phoneForm.addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(phoneForm);
      fetch("https://script.google.com/macros/s/AKfycbzckbkLPqBm0C71cwKK3t_F8lE9tQdg3qO4s4_zh3Gs3c6kZI52-iWWgOJc_qop0byHyA/exec", { 
        method: "POST", body: formData })
        .then((response) => {
          alert("Thank you! We'll call you soon.");
          phoneForm.reset();
        })
        .catch((error) => {
          alert("Submission failed. Please try again.");
        });
    });
  }
});

// pop-up form functionality //
function initPopupForm() {
  const popupForm = document.getElementById("popupForm");
  const appointmentButtons = document.querySelectorAll(".appointment-btn");
  const closeButton = document.getElementById("popupCloseBtn");

  const togglePopup = () => {
    popupForm.style.display = popupForm.style.display === "flex" ? "none" : "flex";
  };

  appointmentButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      popupForm.style.display = "flex";
    });
  });

  if (closeButton) {
    closeButton.addEventListener("click", () => {
      popupForm.style.display = "none";
    });
  }

  if (popupForm) {
    popupForm.addEventListener("click", (e) => {
      if (e.target === popupForm) {
        popupForm.style.display = "none";
      }
    });
  }
}

// chat toggle functionality //
function initChatToggle() {
  const chatToggleBtn = document.getElementById("chatToggleBtn");
  const chatOptions = document.querySelector(".chaty-channel-list");

  if (chatToggleBtn && chatOptions) {
    chatToggleBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      chatOptions.classList.toggle("active");
    });

    document.addEventListener("click", (e) => {
      if (!chatOptions.contains(e.target) && e.target !== chatToggleBtn) {
        chatOptions.classList.remove("active");
      }
    });
  }
}

// back-to-top button functionality //
function initBackToTopButton() {
  const backToTopBtn = document.querySelector(".back-top-btn");

  if (backToTopBtn) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        backToTopBtn.classList.add("active");
      } else {
        backToTopBtn.classList.remove("active");
      }
    });

    backToTopBtn.addEventListener("click", (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  }
}