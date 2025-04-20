'use strict';

/** addEvent on element **/

const addEventOnElem = function (elem, type, callback) {
  if (elem.length > 1) {
    for (let i = 0; i < elem.length; i++) {
      elem[i].addEventListener(type, callback);
    }
  } else {
    elem.addEventListener(type, callback);
  }
}


/** navbar toggle **/

const navbar = document.querySelector("[data-navbar]");
const navbarLinks = document.querySelectorAll("[data-nav-link]");
const navbarToggler = document.querySelector("[data-nav-toggler]");

const toggleNav = function () {
  navbar.classList.toggle("active");
  navbarToggler.classList.toggle("active");
}

addEventOnElem(navbarToggler, "click", toggleNav);

const closeNav = function () {
  navbar.classList.remove("active");
  navbarToggler.classList.remove("active");
}

addEventOnElem(navbarLinks, "click", closeNav);


/** language btn **/

document.addEventListener("DOMContentLoaded", () => {
  const langToggle = document.getElementById("languageToggle");
  let currentLang = localStorage.getItem("lang") || "ENG";

  // Set initial language
  setLanguage(currentLang);

  if (langToggle) {
    langToggle.addEventListener("click", () => {
      currentLang = currentLang === "ENG" ? "BAN" : "ENG";
      setLanguage(currentLang);
      localStorage.setItem("lang", currentLang); // Save user preference
    });
  }
});

function setLanguage(lang) {
  // Toggle the language class on <body>
  document.body.classList.toggle("lang-bn", lang === "BAN");
  document.body.classList.toggle("lang-eng", lang === "ENG");

  // Update the translations
  document.querySelectorAll("[data-translation]").forEach(element => {
    const translationKey = lang === "BAN" ? "data-lang-ban" : "data-lang-eng";
    if (element.hasAttribute(translationKey)) {
      element.innerHTML = element.getAttribute(translationKey);
    }
  });

  // Update the language switch button text
  const langToggle = document.getElementById("languageToggle");
  if (langToggle) {
    langToggle.textContent = lang === "BAN" ? "ENG" : "BAN";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const backToTopBtn = document.querySelector(".back-top-btn");

  // Show or hide the button based on scroll position
  window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
      backToTopBtn.classList.add("active");
    } else {
      backToTopBtn.classList.remove("active");
    }
  });

  // Smooth scroll to the top when the button is clicked
  backToTopBtn.addEventListener("click", (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById('phoneForm');

  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const phoneNumber = form.phone_number.value;
      console.log('Phone number entered:', phoneNumber);

      try {
        const response = await fetch('https://script.google.com/macros/s/AKfycbwc1eWO3ygaS_a1xoEz_I7w3Ue0JitPQ3YWzvxOtkPoOS-jqZKV-dfnYhB1oREEgfmh/exec', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ phone_number: phoneNumber }),
        });

        const result = await response.json();
        console.log('Response:', result);

        if (result.status === 'success') {
          alert('Thank  you! Your phone number has been submitted successfully.');
          form.reset();
        } else {
          alert('There was an error. Please try again.');
        }
      } catch (error) {
        console.error('Error occurred:', error);
        alert('There was an error. Please try again.');
      }
    });
  }
});