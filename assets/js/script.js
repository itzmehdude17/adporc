// itzmehdude //

'use strict';

// addEvent on element //
const addEventOnElem = function (elem, type, callback) {
  if (elem.length > 1) {
    for (let i = 0; i < elem.length; i++) {
      elem[i].addEventListener(type, callback);
    }
  } else {
    elem.addEventListener(type, callback);
  }
}

// navbar toggle //
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

// language button //
document.addEventListener("DOMContentLoaded", () => {
  const langToggle = document.getElementById("languageToggle");
  let currentLang = localStorage.getItem("lang") || "ENG";

  // set initial language //
  setLanguage(currentLang);

  if (langToggle) {
    langToggle.addEventListener("click", () => {
      currentLang = currentLang === "ENG" ? "BAN" : "ENG";
      setLanguage(currentLang);
      localStorage.setItem("lang", currentLang); // save user preference //
    });
  }
});

function setLanguage(lang) {
  // toggle the language class on <body> //
  document.body.classList.toggle("lang-bn", lang === "BAN");
  document.body.classList.toggle("lang-eng", lang === "ENG");

  // update the translations for text content
  document.querySelectorAll("[data-translation]").forEach(element => {
    const translationKey = lang === "BAN" ? "data-lang-ban" : "data-lang-eng";
    if (element.hasAttribute(translationKey)) {
      element.innerHTML = element.getAttribute(translationKey);
    }
  });

  // update the language switch button text //
  const langToggle = document.getElementById("languageToggle");
  if (langToggle) {
    langToggle.textContent = lang === "BAN" ? "ENG" : "BAN";
  }

  // update placeholder translations //
  const placeholders = [
    { id: "name", eng: "Enter your name", ban: "আপনার নাম লিখুন" },
    { id: "phone", eng: "Enter your phone number", ban: "আপনার ফোন নম্বর লিখুন" },
    { id: "address", eng: "Enter your address", ban: "আপনার ঠিকানা লিখুন" },
    { id: "complaint", eng: "Enter your chief complaint", ban: "আপনার প্রধান অভিযোগ লিখুন" }
  ];

  placeholders.forEach(field => {
    const input = document.getElementById(field.id);
    if (input) {
      input.setAttribute("placeholder", lang === "BAN" ? field.ban : field.eng);
    }
  });
}

// pop-up form functionality //
document.addEventListener("DOMContentLoaded", () => {
  const popupForm = document.getElementById("popupForm");
  const appointmentButton = document.querySelector(".btn[data-lang-eng='Book an appointment']");
  const closeButton = document.getElementById("popupCloseBtn"); // define the close button
  const appointmentForm = document.getElementById("appointmentForm");

  // toggle the pop-up form visibility //
  const togglePopup = () => {
    popupForm.style.display = popupForm.style.display === "flex" ? "none" : "flex";
  };

  // open or close the form when clicking the "Book an appointment" button //
  appointmentButton.addEventListener("click", (e) => {
    e.preventDefault();
    togglePopup();
  });

  // close the form when clicking the close button //
  closeButton.addEventListener("click", () => {
    popupForm.style.display = "none";
  });

  // close the pop-up form when clicking outside the form //
  popupForm.addEventListener("click", (e) => {
    if (e.target === popupForm) {
      popupForm.style.display = "none";
    }
  });

  // handle form submission //
  appointmentForm.addEventListener("submit", async (e) => {
    e.preventDefault();
  
    const formData = new FormData(appointmentForm);
    const data = {
      name: formData.get('name'),
      phone: formData.get('phone'),
      address: formData.get('address'),
      complaint: formData.get('complaint'),
    };
  
    console.log('Form Data:', data); // log data for debugging
  
    try {
      const response = await fetch('https://cors-anywhere.herokuapp.com/https://script.google.com/macros/s/AKfycbxzG7teiR4Vt3ZFWVxxwsSR9jbytq-hVa3s6AW0hEJa2DizDWbGGIbKx6OH0knn1Buo/exec', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
      });
  
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
  
      const result = await response.json();
      console.log('Response:', result); // log response for debugging
  
      if (result.status === 'success') {
        alert("Thank you! Your appointment has been submitted successfully.");
        appointmentForm.reset(); // reset the form
      } else {
        alert("Submission failed. Please try again.");
      }
    } catch (error) {
      console.error("Error:", error); // log error for debugging
      alert("There was an error. Please try again later.");
    }
  });

  // chat toggle functionality //
  const chatToggleBtn = document.getElementById("chatToggleBtn");
  const chatOptions = document.querySelector(".chaty-channel-list");

  chatToggleBtn.addEventListener("click", () => {
    chatOptions.classList.toggle("active");
  });

  // redirect to messaging services //
  document.querySelectorAll(".chaty-channel-list a").forEach(link => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const service = e.target.getAttribute("data-service");
      if (service === "phone") {
        window.location.href = "tel:+1234567890"; // replace with actual phone number
      } else if (service === "messenger") {
        window.open("https://m.me/yourusername", "_blank"); // replace with actual Messenger link
      } else if (service === "whatsapp") {
        window.open("https://wa.me/1234567890", "_blank"); // replace with actual WhatsApp number
      }
    });
  });
  
});

document.addEventListener("DOMContentLoaded", () => {
  const backToTopBtn = document.querySelector(".back-top-btn");

  // show or hide the button based on scroll position //
  window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
      backToTopBtn.classList.add("active");
    } else {
      backToTopBtn.classList.remove("active");
    }
  });

  // smooth scroll to the top when the button is clicked //
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
