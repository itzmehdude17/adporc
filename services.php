<?php
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function loadJson($file) {
    $path = __DIR__ . '/data/' . $file;
    if (!file_exists($path)) return null;
    $raw = file_get_contents($path);
    return $raw ? json_decode($raw, true) : null;
}

$site          = loadJson('site.json')         ?: [];
$services_page = loadJson('services_page.json') ?: [];
$faqs          = loadJson('faqs.json')          ?: [];

$phone       = $site['clinic']['phone']      ?? '01950-935236';
$address_en  = $site['clinic']['address_en'] ?? '270/1 Dholaipar, South Jatrabari, Dhaka-1204';
$address_bn  = $site['clinic']['address_bn'] ?? '২৭০/১ ধোলাইপাড়, দক্ষিণ যাত্রাবাড়ী, ঢাকা-১২০৪';
$maps_url    = $site['clinic']['maps_url']   ?? 'https://maps.app.goo.gl/GWgL6zbSVLHhMkFY9';
$maps_footer = $site['clinic']['maps_url_footer'] ?? 'https://maps.app.goo.gl/hpFe4M5S28s8ggV57';
$social      = $site['social']               ?? [];
$schedule_en = $site['schedule']['en']       ?? "Saturday to Friday:<br>10:00am - 01:00pm<br>02:00pm - 10:00pm";
$schedule_bn = $site['schedule']['bn']       ?? 'প্রতিদিন সকাল ১০টা থেকে দুপুর ১টা <br>এবং বিকাল ৩টা থেকে রাত ১০টা';
$footer_text_en = $site['footer']['text_en'] ?? "One of the best physiotherapy center in Dhaka city. Feel better, move easier, and get back to living your life with simple and effective physiotherapy care.";
$footer_text_bn = $site['footer']['text_bn'] ?? "ঢাকা শহরের অন্যতম সেরা ফিজিওথেরাপি সেন্টার।";
$google_sheet_url = $site['google_sheet_appointment_url'] ?? '';
$ga_id      = $site['analytics']['gtag_id']    ?? 'G-BJMDW5QDN2';
$clarity_id = $site['analytics']['clarity_id'] ?? 'uh2rk88w4t';
$gtm_id     = $site['analytics']['gtm_id']     ?? 'GTM-5DZHWJDS';

$sp_banner      = $services_page['banner']   ?? '/assets/images/banners/banner-1.jpg';
$sp_title_en    = $services_page['title_en'] ?? 'Our Services';
$sp_title_bn    = $services_page['title_bn'] ?? 'আমাদের সেবা সমূহ';
$conditions_en  = $services_page['conditions_en'] ?? [
    'Neck pain and stiffness','Low back pain','Knee pain','Ankle and heel pain',
    'Headache and migraine-related neck strain','Shoulder pain and frozen shoulder',
    'Tingling, heaviness, or weakness in hands and feet',
    'Spine problems including cervical and lumbar spondylosis',
    'Disc prolapse / PLID','Slipped disc / Spondylolisthesis',
    'Joint pain (hip, elbow, wrist, ankle)','Stroke and paralysis rehabilitation',
    "Sudden facial paralysis (Bell's palsy)",'Sports injuries and muscle strain','ACL Injury'
];
$conditions_bn  = $services_page['conditions_bn'] ?? [
    'ঘাড় ব্যথা ও শক্তভাব','কোমর ব্যথা','হাঁটু ব্যথা','পায়ের গোড়ালির ব্যথা',
    'মাথা ব্যথা ও মাইগ্রেইন-সম্পর্কিত ঘাড়ের টান','কাঁধের ব্যথা ও ফ্রোজেন শোল্ডার',
    'হাত-পা ঝিনঝিন, ভারী বা অবস লাগা','স্পন্ডাইলোসিস সমস্যা (ঘাড় ও কোমরে)',
    'ডিস্ক প্রলাপ্স / পিএলআইডি','স্লিপড ডিস্ক / স্পন্ডিলোলিস্থেসিস',
    'বিভিন্ন জয়েন্টের ব্যথা (হিপ, কনুই, কবজি, গোড়ালি)',
    'হঠাৎ মুখ বেকে যাওয়া (বেলস্‌ পালসি)','স্ট্রোক ও প্যারালাইসিস পুনর্বাসন',
    'খেলাধুলাজনিত আঘাত ও পেশির টান','ACL ইনজুরি'
];
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
    <link rel="canonical" href="https://adporc.com/services">
    <link rel="shortcut icon" href="/assets/images/adporc-icon.png" type="image/png">
    <link rel="apple-touch-icon" href="/assets/images/adporc-icon.png">
    <meta name="theme-color" content="#000000">
    <title>Services | ADPORC</title>
    <meta name="description" content="Explore physiotherapy services at ADPORC — neck pain, back pain, knee pain, frozen shoulder, stroke, Bell's palsy, ACL injury, and more. Certified care in Jatrabari, Dhaka.">
    <meta name="keywords" content="adporc services, physiotherapy services dhaka, neck pain treatment, back pain physiotherapy, knee pain treatment, frozen shoulder physio, stroke rehabilitation, bells palsy, ACL injury physiotherapy, jatrabari physiotherapy">
    <meta name="physiotherapist" content="Dr Saddam Hossain, Senior Consultant Physiotherapist, Course Co-ordinator JBFCPHS">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="ADPORC">
    <meta property="og:title" content="Services | ADPORC">
    <meta property="og:description" content="Certified physiotherapy services for neck pain, back pain, knee pain, stroke, and more at ADPORC Jatrabari, Dhaka.">
    <meta property="og:image" content="/assets/images/og-image.jpeg">
    <meta property="og:url" content="https://adporc.com/services">
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
          "@type": "PhysicalTherapy",
          "name": "Services | ADPORC",
          "url": "https://adporc.com/services",
          "image": "https://adporc.com/assets/images/og-image.jpeg",
          "description": "Certified physiotherapy services for neck pain, back pain, knee pain, stroke, and more at ADPORC Jatrabari, Dhaka.",
          "telephone": "+8801950935236",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Jatrabari, Dhaka",
            "addressLocality": "Dhaka", "addressRegion": "BD",
            "postalCode": "1204", "addressCountry": "BD"
          },
          "geo": { "@type": "GeoCoordinates", "latitude": "23.7104", "longitude": "90.4074" },
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
    <a href="#services-content" class="skip-to-content">Skip to main content</a>
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
      <section class="section about" id="services-content">
        <div class="container">
          <figure class="about-banner">
            <img src="<?= h($sp_banner) ?>" width="470" height="538" loading="lazy" alt="ADPORC physiotherapy services" class="w-100">
          </figure>
          <div class="about-content">
            <h1 class="section-subtitle text-center" data-translation
              data-lang-eng="<?= h($sp_title_en) ?>"
              data-lang-ban="<?= h($sp_title_bn) ?>"></h1>
            <!-- english content -->
            <article class="blog-content" id="blog-en">
              <h2 class="section-text">The conditions we provide physiotherapy care for:</h2>
              <ol class="section-text">
                <?php foreach ($conditions_en as $item): ?>
                <li><?= h($item) ?></li>
                <?php endforeach; ?>
              </ol>
              <p class="section-text">
                Every treatment is delivered by a certified physiotherapist in Dhaka, ensuring safe, effective care tailored to your unique condition.
                At ADPORC, we don't just treat — we care, educate, and empower.
              </p>
              <h2 class="section-text"><i class="fa-solid fa-calendar-check"></i> Book Your Appointment Today!</h2>
              <p class="section-text section-text-1">
                Take the first step toward a pain-free life. Visit ADPORC and discover why patients from Jatrabari, Shonir Akhra, Puran Dhaka, and Narayanganj trust us as the most reliable physiotherapy center in Jatrabari, Dhaka-1204.
              </p>
              <ul class="section-text">
                <li><i class="fa-solid fa-location-dot"></i> <b>Location:</b> <a href="<?= h($maps_footer) ?>" target="_blank" rel="noopener noreferrer"><?= h($address_en) ?></a></li>
                <li><i class="fa-solid fa-phone"></i> <b>Call:</b> <a href="tel:+880<?= h(str_replace('-', '', $phone)) ?>" target="_blank" rel="noopener noreferrer"><?= h($phone) ?></a></li>
                <li><i class="fa-solid fa-globe"></i> <b>Website:</b> <a href="https://adporc.com" target="_blank" rel="noopener noreferrer">adporc.com</a></li>
              </ul>
            </article>
            <!-- bangla content -->
            <article class="blog-content" id="blog-bn">
              <h2 class="section-text">যেসব সমস্যায় আমরা ফিজিওথেরাপি সেবা প্রদান করি:</h2>
              <ol class="section-text">
                <?php foreach ($conditions_bn as $item): ?>
                <li><?= h($item) ?></li>
                <?php endforeach; ?>
              </ol>
              <p class="section-text">
                আমরা নিশ্চিত করি, প্রতিটি রোগী পান সার্টিফায়েড ফিজিওথেরাপিস্ট দ্বারা পরিচালিত যত্নবান এবং নিরাপদ চিকিৎসা। ADPORC বিশ্বাস করে — সঠিক চিকিৎসা শুরু হয় যত্ন ও বোঝাপড়ার থেকে।
              </p>
              <h2 class="section-text"><i class="fa-solid fa-calendar-check"></i> আজই বুক করুন আপনার অ্যাপয়েন্টমেন্ট!</h2>
              <p class="section-text">
                ব্যথামুক্ত জীবনের দিকে আজই প্রথম পদক্ষেপ নিন। ADPORC-এ আসুন — যাত্রাবাড়ী, শনির আখড়া, পুরান ঢাকা ও নারায়ণগঞ্জের অসংখ্য রোগীদের বিশ্বস্ত ফিজিওথেরাপি সেন্টার।
              </p>
              <ul class="section-text">
                <li><i class="fa-solid fa-location-dot"></i> <b>ঠিকানা:</b> <a href="<?= h($maps_footer) ?>" target="_blank" rel="noopener noreferrer"><?= h($address_bn) ?></a></li>
                <li><i class="fa-solid fa-phone"></i> <b>ফোন:</b> <a href="tel:+880<?= h(str_replace('-', '', $phone)) ?>" target="_blank" rel="noopener noreferrer"><?= h($phone) ?></a></li>
                <li><i class="fa-solid fa-globe"></i> <b>ওয়েবসাইট:</b> <a href="https://adporc.com" target="_blank" rel="noopener noreferrer">adporc.com</a></li>
              </ul>
            </article>
          </div>
        </div>
      </section>
    </main>

    <!-- FAQ section -->
    <section class="section faq" id="faq" aria-label="faq">
      <div class="container">
        <h2 class="section-subtitle text-center" data-translation data-lang-eng="FAQ" data-lang-ban="সাধারণ জিজ্ঞাসা"></h2>
        <div class="faq-list">
          <?php foreach ($faqs as $i => $faq): $n = $i + 1; ?>
          <div class="faq-item <?= $n ?>">
            <button class="faq-question" aria-expanded="false"
              data-translation
              data-lang-eng="<?= h($faq['question_en'] ?? '') ?>"
              data-lang-ban="<?= h($faq['question_bn'] ?? '') ?>"><?= h($faq['question_en'] ?? '') ?>
              <i class="fa-solid fa-chevron-down"></i>
            </button>
            <div class="faq-answer"
              data-translation
              data-lang-eng="<?= h($faq['answer_en'] ?? '') ?>"
              data-lang-ban="<?= h($faq['answer_bn'] ?? '') ?>"><?= h($faq['answer_en'] ?? '') ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

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
