<?php
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function loadJson($file) {
    $path = __DIR__ . '/data/' . $file;
    if (!file_exists($path)) return null;
    $raw = file_get_contents($path);
    return $raw ? json_decode($raw, true) : null;
}

$site  = loadJson('site.json')  ?: [];
$blogs = loadJson('blogs.json') ?: [];

$phone       = $site['clinic']['phone']      ?? '01950-935236';
$address_en  = $site['clinic']['address_en'] ?? '270/1 Dholaipar, South Jatrabari, Dhaka-1204';
$address_bn  = $site['clinic']['address_bn'] ?? '২৭০/১ ধোলাইপাড়, দক্ষিণ যাত্রাবাড়ী, ঢাকা-১২০৪';
$maps_url    = $site['clinic']['maps_url']   ?? 'https://maps.app.goo.gl/GWgL6zbSVLHhMkFY9';
$social      = $site['social']              ?? [];
$schedule_en = $site['schedule']['en']      ?? "Saturday to Friday:<br>10:00am - 01:00pm<br>02:00pm - 10:00pm";
$schedule_bn = $site['schedule']['bn']      ?? 'প্রতিদিন সকাল ১০টা থেকে দুপুর ১টা <br>এবং বিকাল ৩টা থেকে রাত ১০টা';
$footer_text_en = $site['footer']['text_en'] ?? "One of the best physiotherapy center in Dhaka city. Feel better, move easier, and get back to living your life with simple and effective physiotherapy care.";
$footer_text_bn = $site['footer']['text_bn'] ?? "ঢাকা শহরের অন্যতম সেরা ফিজিওথেরাপি সেন্টার।";
$google_sheet_url = $site['google_sheet_appointment_url'] ?? '';
$ga_id      = $site['analytics']['gtag_id']    ?? 'G-BJMDW5QDN2';
$clarity_id = $site['analytics']['clarity_id'] ?? 'uh2rk88w4t';
$gtm_id     = $site['analytics']['gtm_id']     ?? 'GTM-5DZHWJDS';
?>
<!-- itzmehdude -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="en, bn">
    <meta name="author" content="Dr. Saddam Hossain, Consultant Physiotherapist">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://adporc.com/blogs">
    <link rel="shortcut icon" href="/assets/images/adporc-icon.png" type="image/png">
    <link rel="apple-touch-icon" href="/assets/images/adporc-icon.png">
    <meta name="theme-color" content="#000000">
    <title>Blogs | ADPORC</title>
    <meta name="description" content="Read informative blogs by ADPORC about physiotherapy, pain relief, sports rehab, and postural tips. Written by experts for public awareness. Book your session with Dr. Saddam Hossain.">
    <meta name="keywords" content="adporc, asia digital physiotherapy, best physiotherapy dhaka, best physiotherapy jatrabari, dr saddam hossain, physiotherapy blog bangla, ফিজিওথেরাপি যাত্রাবাড়ী, ঢাকায় সেরা ফিজিওথেরাপি">
    <meta name="physiotherapist" content="Dr Saddam Hossain, Senior Consultant Physiotherapist, Course Co-ordinator JBFCPHS">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="ADPORC">
    <meta property="og:title" content="Blogs | ADPORC">
    <meta property="og:description" content="Jatrabari's one of the best physiotherapy center.">
    <meta property="og:image" content="/assets/images/og-image.jpeg">
    <meta property="og:url" content="https://adporc.com/blogs">
    <meta name="facebook-domain-verification" content="u5hks6vsnjk4jmb7ammgdmc9ybhk3o"/>
    <!-- css -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="/assets/css/style.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Tiro+Bangla:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- JSON-LD schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        { "@type": "WebSite", "name": "ADPORC", "url": "https://adporc.com" },
        {
          "@type": "Blog",
          "name": "Blogs | ADPORC",
          "url": "https://adporc.com/blogs",
          "image": "https://adporc.com/assets/images/og-image.jpeg",
          "description": "ADPORC physiotherapy blogs — pain management, sports rehab, paralysis recovery, and more.",
          "telephone": "+8801950935236",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Jatrabari, Dhaka",
            "addressLocality": "Dhaka", "addressRegion": "BD",
            "postalCode": "1204", "addressCountry": "BD"
          },
          "sameAs": [
            "<?= h($social['facebook'] ?? 'https://www.facebook.com/adporc') ?>",
            "<?= h($social['youtube']  ?? 'https://www.youtube.com/@adporc') ?>",
            "<?= h($social['linkedin'] ?? 'https://www.linkedin.com/company/adporc/posts/') ?>",
            "https://www.upscrolled.com/adporc/"
          ],
          "logo": "https://adporc.com/assets/images/adporc-icon.png"
        }
      ]
    }
    </script>
    <!-- Google tag -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= h($ga_id) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= h($ga_id) ?>');
    </script>
    <!-- Microsoft Clarity -->
    <script>
      (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i+"?ref=bwt";
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
      })(window, document, "clarity", "script", "<?= h($clarity_id) ?>");
    </script>
  </head>
  <body id="top">
    <a href="#blog" class="skip-to-content">Skip to main content</a>
    <!-- #header -->
    <header class="header">
      <div class="header-wrapper">
        <div class="header-top">
          <div class="container">
            <ul class="contact-list">
              <li class="contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <a href="<?= h($maps_url) ?>" target="_blank" rel="noopener noreferrer" data-translation
                  data-lang-eng="<?= h($address_en) ?>"
                  data-lang-ban="<?= h($address_bn) ?>"><?= h($address_en) ?></a>
              </li>
              <li class="contact-item">
                <i class="fa-solid fa-phone"></i>
                <a href="tel:+880<?= h(str_replace('-', '', $phone)) ?>" target="_blank" rel="noopener noreferrer" class="font-eng"><?= h($phone) ?></a>
              </li>
            </ul>
            <div class="header-actions">
              <ul class="social-list">
                <li><a href="<?= h($social['facebook'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Facebook"><ion-icon name="logo-facebook"></ion-icon></a></li>
                <li><a href="<?= h($social['youtube'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="YouTube"><ion-icon name="logo-youtube"></ion-icon></a></li>
                <li><a href="<?= h($social['linkedin'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="LinkedIn"><ion-icon name="logo-linkedin"></ion-icon></a></li>
              </ul>
              <div class="language-switch">
                <button id="languageToggle" class="lang-btn" data-translation data-lang-eng="BAN" data-lang-ban="ENG">ENG</button>
              </div>
            </div>
          </div>
        </div>
        <div class="header-bottom">
          <div class="container">
            <a class="logo" href="/home">
              <img src="/assets/images/ADPORC11.png" alt="best physiotherapy center in Jatrabari, Dhaka">
              <span>ADPORC</span>
            </a>
            <nav class="navbar" data-navbar>
              <ul class="navbar-list">
                <li><a href="/home" class="navbar-link" data-nav-link data-translation data-lang-eng="Home" data-lang-ban="হোম">Home</a></li>
                <li><a href="/services" class="navbar-link" data-nav-link data-translation data-lang-eng="Services" data-lang-ban="সেবা সমূহ">Services</a></li>
                <li><a href="/about-us" class="navbar-link" data-nav-link data-translation data-lang-eng="About Us" data-lang-ban="আমাদের সম্পর্কে">About Us</a></li>
                <li><a href="/blogs" class="navbar-link" data-nav-link data-translation data-lang-eng="Blogs" data-lang-ban="ব্লগ সমূহ">Blogs</a></li>
              </ul>
            </nav>
            <a class="btn appointment-btn" id="appointmentButton" data-translation data-lang-eng="Book an appointment" data-lang-ban="অ্যাপয়েন্টমেন্ট বুক করুন">Book an appointment</a>
            <button class="nav-toggle-btn" aria-label="Toggle menu" data-nav-toggler>
              <ion-icon name="menu-sharp" aria-hidden="true" class="menu-icon"></ion-icon>
              <ion-icon name="close-sharp" aria-hidden="true" class="close-icon"></ion-icon>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- pop-up appointment form -->
    <div class="popup-overlay" id="popupForm">
      <div class="popup-content">
        <button class="popup-close-btn" id="popupCloseBtn" aria-label="Close form">
          <ion-icon name="close-outline"></ion-icon>
        </button>
        <h2 class="popup-title" data-translation data-lang-eng="Book an Appointment" data-lang-ban="অ্যাপয়েন্টমেন্ট বুক করুন">Book an Appointment</h2>
        <form id="appointmentForm" name="submit-to-google-sheet" class="appointment-form"
          action="<?= h($google_sheet_url) ?>">
          <div class="form-group">
            <label for="name" data-translation data-lang-eng="Name" data-lang-ban="নাম">Name</label>
            <input name="Name" type="text" id="name" placeholder="Enter your name" required>
          </div>
          <div class="form-group">
            <label for="phone" data-translation data-lang-eng="Phone Number" data-lang-ban="ফোন নম্বর">Phone Number</label>
            <input name="Phone" type="tel" id="phone" placeholder="Enter your phone number" required pattern="[0-9]{11}" title="Please enter a valid 11-digit phone number">
          </div>
          <div class="form-group">
            <label for="age" data-translation data-lang-eng="Age" data-lang-ban="বয়স">Age</label>
            <input name="Age" type="tel" id="age" placeholder="Enter your age">
          </div>
          <div class="form-group">
            <label for="gender" data-translation data-lang-eng="Gender" data-lang-ban="লিঙ্গ">Gender</label>
            <select class="form-control" name="Gender" id="gender" required>
              <option value="" disabled selected hidden data-translation data-lang-eng="Select your gender (Binary Only)" data-lang-ban="আপনার লিঙ্গ নির্বাচন করুন (শুধুমাত্র বাইনারি)"></option>
              <option value="male" data-translation data-lang-eng="Male" data-lang-ban="পুরুষ">Male</option>
              <option value="female" data-translation data-lang-eng="Female" data-lang-ban="স্ত্রী">Female</option>
            </select>
          </div>
          <div class="form-group">
            <label for="address" data-translation data-lang-eng="Address" data-lang-ban="ঠিকানা">Address</label>
            <input name="Address" type="text" id="address" placeholder="Enter your address" required>
          </div>
          <div class="form-group">
            <label for="complaint" data-translation data-lang-eng="Chief Complaint" data-lang-ban="মূল সমস্যা">Chief Complaint</label>
            <textarea name="Complaint" id="complaint" placeholder="Enter your chief complaint" rows="4" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-block" data-translation data-lang-eng="Submit" data-lang-ban="জমা দিন">Submit</button>
        </form>
      </div>
    </div>

    <!-- main-body -->
    <main>
      <section class="section blog" id="blog" aria-label="blog">
        <div class="container">
          <h1 class="section-subtitle text-center" data-translation
            data-lang-eng="ADPORC<br>Physiotherapy Blogs"
            data-lang-ban="ADPORC<br>ফিজিওথেরাপি ব্লগসমূহ">ADPORC <br> Physiotherapy Blogs</h1>
          <p class="section-title" data-translation
            data-lang-eng="<b>Latest Blogs</b>"
            data-lang-ban="<b>সর্বশেষ ব্লগসমূহ</b>"><b>Latest Blogs</b></p>
          <div class="blog-list">
            <?php foreach ($blogs as $blog): ?>
            <div class="blog-card">
              <figure class="card-banner">
                <img src="<?= h($blog['banner'] ?? '') ?>" loading="lazy" alt="<?= h($blog['title_en'] ?? 'ADPORC blog') ?>" class="img-cover">
                <div class="card-badge">
                  <ion-icon name="calendar-outline"></ion-icon>
                  <time class="time" datetime="<?= h($blog['datetime'] ?? '') ?>" data-translation
                    data-lang-eng="<?= h($blog['date_en'] ?? '') ?>"
                    data-lang-ban="<?= h($blog['date_bn'] ?? '') ?>"><?= h($blog['date_en'] ?? '') ?></time>
                </div>
              </figure>
              <div class="card-content">
                <h3 class="h3 card-title">
                  <a href="/blogs/<?= h($blog['slug'] ?? '#') ?>" class="card-title" data-translation
                    data-lang-eng="<?= h($blog['title_en'] ?? '') ?>"
                    data-lang-ban="<?= h($blog['title_bn'] ?? '') ?>"><?= h($blog['title_en'] ?? '') ?></a>
                </h3>
                <p class="card-text" data-translation
                  data-lang-eng="<?= h($blog['excerpt_en'] ?? '') ?>"
                  data-lang-ban="<?= h($blog['excerpt_bn'] ?? '') ?>"></p>
                <a href="/blogs/<?= h($blog['slug'] ?? '#') ?>" class="btn" data-translation data-lang-eng="Read More" data-lang-ban="বিস্তারিত পড়ুন">Read More</a>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    </main>

    <!-- marquee container -->
    <div class="marquee-container">
      <div class="marquee">
        <span><img src="/adporc-logo-rounded.png" alt="best physiotherapy center in Dhaka" class="marquee-logo">Asia Digital Physiotherapy &amp; Orthopedic Rehabilitation Center</span>
        <span><img src="/adporc-logo-rounded.png" alt="best physiotherapy center in Dhaka" class="marquee-logo">Asia Digital Physiotherapy &amp; Orthopedic Rehabilitation Center</span>
        <span><img src="/adporc-logo-rounded.png" alt="best physiotherapy center in Dhaka" class="marquee-logo">Asia Digital Physiotherapy &amp; Orthopedic Rehabilitation Center</span>
      </div>
    </div>

    <!-- #footer -->
    <footer class="footer">
      <div class="footer-top section">
        <div class="container">
          <div class="footer-brand">
            <a href="/home" class="logo font-eng">ADPORC</a>
            <p class="footer-text" data-translation
              data-lang-eng="<?= h($footer_text_en) ?>"
              data-lang-ban="<?= h($footer_text_bn) ?>"></p>
            <div class="schedule footer-list-title">
              <div class="schedule-icon"><ion-icon name="time-outline"></ion-icon></div>
              <span class="span" data-translation
                data-lang-eng="<?= h($schedule_en) ?>"
                data-lang-ban="<?= h($schedule_bn) ?>"></span>
            </div>
          </div>
          <ul class="footer-list">
            <li><p class="footer-list-title" data-translation data-lang-eng="Other Links" data-lang-ban="অন্যান্য লিংক">Other Links</p></li>
            <li><a href="/home" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Home" data-lang-ban="হোম">Home</span></a></li>
            <li><a href="/about-us" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="About Us" data-lang-ban="আমাদের সম্পর্কে">About Us</span></a></li>
            <li><a href="/services" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Our Services" data-lang-ban="আমাদের সেবা সমূহ">Our Services</span></a></li>
            <li><a href="/home#our-team" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Our Team" data-lang-ban="আমাদের টিম">Our Team</span></a></li>
            <li><a href="/blogs" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Latest Blog" data-lang-ban="সর্বশেষ ব্লগ">Latest Blog</span></a></li>
          </ul>
          <ul class="footer-list">
            <li><p class="footer-list-title" data-translation data-lang-eng="Our Services" data-lang-ban="আমাদের সেবা সমূহ">Our Services</p></li>
            <li><a href="/blogs/neck-pain-physiotherapy-treatment-dhaka" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Neck Pain" data-lang-ban="ঘাড় ব্যথা">Neck Pain</span></a></li>
            <li><a href="/blogs/frozen-shoulder-physiotherapy-treatment-dhaka" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Frozen Shoulder" data-lang-ban="ফ্রোজেন শোল্ডার">Frozen Shoulder</span></a></li>
            <li><a href="/blogs/back-pain-physiotherapy-treatment-dhaka" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Back Pain" data-lang-ban="ব্যাক পেইন">Back Pain</span></a></li>
            <li><a href="/blogs/plid-physiotherapy-treatment-dhaka" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="PLID" data-lang-ban="পিএলআইডি">PLID</span></a></li>
            <li><a href="/blogs/knee-pain-physiotherapy-treatment-dhaka" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Knee Pain" data-lang-ban="হাঁটু ব্যথা">Knee Pain</span></a></li>
            <li><a href="/blogs/acl-injury-physiotherapy-treatment-dhaka" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="ACL Injury" data-lang-ban="লিগামেন্ট ইনজুরি">ACL Injury</span></a></li>
            <li><a href="/blogs/best-stroke-physiotherapy-management-dhaka" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Stroke" data-lang-ban="স্ট্রোক">Stroke</span></a></li>
            <li><a href="/blogs/bells-palsy-physiotherapy-treatment-dhaka" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Bell's Palsy" data-lang-ban="বেলস্ পালসি">Bell's Palsy</span></a></li>
            <li><a href="/blogs/advance-electrotherapy-adporc-dhaka" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Advanced Electrotherapy" data-lang-ban="আধুনিক ইলেকট্রোথেরাপি">Advanced Electrotherapy</span></a></li>
          </ul>
          <ul class="footer-list">
            <li><p class="footer-list-title" data-translation data-lang-eng="Contact Us" data-lang-ban="যোগাযোগ">Contact Us</p></li>
            <li class="footer-item">
              <div class="item-icon"><ion-icon name="location-outline"></ion-icon></div>
              <a href="<?= h($maps_url) ?>" class="footer-link" target="_blank" rel="noopener noreferrer"
                data-translation data-lang-eng="<?= h($address_en) ?>" data-lang-ban="<?= h($address_bn) ?>"><?= h($address_en) ?></a>
            </li>
            <li class="footer-item">
              <div class="item-icon"><ion-icon name="call-outline"></ion-icon></div>
              <a href="tel:+880<?= h(str_replace('-', '', $phone)) ?>" class="footer-link font-eng" target="_blank" rel="noopener noreferrer"><?= h($phone) ?></a>
            </li>
            <li class="footer-item">
              <div class="item-icon"><ion-icon name="mail-outline"></ion-icon></div>
              <a href="mailto:info@adporc.com" target="_blank" rel="noopener noreferrer" class="footer-link font-eng">info@adporc.com</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="footer-left">
          <ul class="social-list">
            <li><a href="<?= h($social['facebook'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Facebook"><ion-icon name="logo-facebook"></ion-icon></a></li>
            <li><a href="https://www.instagram.com/adporc" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Instagram"><ion-icon name="logo-instagram"></ion-icon></a></li>
            <li><a href="https://www.tiktok.com/@adporc" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="TikTok"><ion-icon name="logo-tiktok"></ion-icon></a></li>
            <li><a href="<?= h($social['youtube'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="YouTube"><ion-icon name="logo-youtube"></ion-icon></a></li>
            <li><a href="<?= h($social['linkedin'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="LinkedIn"><ion-icon name="logo-linkedin"></ion-icon></a></li>
          </ul>
          <p class="copyright font-eng">&copy; 2025-26 All Rights Reserved by <a href="https://www.adporc.com/" target="_blank" rel="noopener noreferrer">ADPORC</a></p>
        </div>
        <p class="developer font-eng"><a href="https://www.youtube.com/@itzmehdude" target="_blank" rel="noopener noreferrer">itzmehdude</a></p>
      </div>
    </footer>

    <!-- floating buttons -->
    <div class="floating-buttons">
      <div class="chat-toggle">
        <button class="chat-toggle-btn" id="chatToggleBtn" aria-label="Chat with us">
          <i class="fa-solid fa-comments"></i>
        </button>
        <div class="chaty-channel-list" id="chatOptions">
          <a href="tel:+8801950935236" target="_blank" rel="noopener noreferrer" class="chat-option" data-service="phone" aria-label="Call us"><i class="fa-solid fa-phone"></i></a>
          <a href="<?= h($social['messenger'] ?? 'https://web.messenger.com/t/111178477047534?text=Assalamualaikum%2C%20') ?>" target="_blank" rel="noopener noreferrer" class="chat-option" data-service="messenger" aria-label="Messenger"><i class="fa-brands fa-facebook-messenger"></i></a>
          <a href="<?= h($social['whatsapp'] ?? 'https://api.whatsapp.com/send/?phone=8801950935236&text=Assalamualaikum%2C%20') ?>" target="_blank" rel="noopener noreferrer" class="chat-option" data-service="whatsapp" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>
      <a href="#top" class="back-top-btn" aria-label="Back to Top"><ion-icon name="caret-up" aria-hidden="true"></ion-icon></a>
    </div>

    <!-- scripts -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="/assets/js/script.js" defer></script>
    <!-- GTM noscript -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= h($gtm_id) ?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  </body>
</html>
