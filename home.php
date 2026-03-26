<?php
// Load site data from JSON files
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function loadJson($file) {
    $path = __DIR__ . '/data/' . $file;
    if (!file_exists($path)) return null;
    $raw = file_get_contents($path);
    return $raw ? json_decode($raw, true) : null;
}

$site  = loadJson('site.json')  ?: [];
$home  = loadJson('home.json')  ?: [];
$team  = loadJson('team.json')  ?: [];
$faqs  = loadJson('faqs.json')  ?: [];
$blogs = loadJson('blogs.json') ?: [];

$hero     = $home['hero']     ?? [];
$about    = $home['about']    ?? [];
$cta      = $home['cta']      ?? [];
$services = $home['services'] ?? [];

$phone    = $site['phone']    ?? '01950-935236';
$address_en = $site['address_en'] ?? '270/1 Dholaipar, South Jatrabari, Dhaka-1204';
$address_bn = $site['address_bn'] ?? '২৭০/১ ধোলাইপাড়, দক্ষিণ যাত্রাবাড়ী, ঢাকা-১২০৪';
$maps_url = $site['maps_url'] ?? 'https://maps.app.goo.gl/GWgL6zbSVLHhMkFY9';
$social   = $site['social']   ?? [];
$schedule_en = $site['schedule_en'] ?? "Saturday to Friday:<br>10:00am - 01:00pm<br>02:00pm - 10:00pm";
$schedule_bn = $site['schedule_bn'] ?? 'প্রতিদিন সকাল ১০টা থেকে দুপুর ১টা <br>এবং বিকাল ৩টা থেকে রাত ১০টা';
$footer_text_en = $site['footer_text_en'] ?? "One of the best physiotherapy center in Dhaka city.";
$footer_text_bn = $site['footer_text_bn'] ?? "ঢাকা শহরের অন্যতম সেরা ফিজিওথেরাপি সেন্টার।";
$google_sheet_url = $site['google_sheet_url'] ?? '';

$ga_id      = $site['analytics']['ga_id'] ?? 'G-BJMDW5QDN2';
$clarity_id = $site['analytics']['clarity_id'] ?? 'uh2rk88w4t';
$gtm_id     = $site['analytics']['gtm_id'] ?? 'GTM-5DZHWJDS';
?>
<!-- itzmehdude -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- meta-data start -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="en, bn">
    <meta name="author" content="Dr. Saddam Hossain, Consultant Physiotherapist">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://adporc.com/home">
    <link rel="shortcut icon" href="/assets/images/adporc-icon.png" type="image/png">
    <link rel="apple-touch-icon" href="/assets/images/adporc-icon.png">
    <meta name="theme-color" content="#000000">
    <title>Home | ADPORC</title>
    <meta name="description" content="ADPORC - One of the best physiotherapy centers in Dhaka city for pain, paralysis, and sports injury treatment. Book your session with Dr. Saddam Hossain.">
    <meta name="keywords" id="meta-keywords" content="adporc, asia digital physiotherapy, asia digital physiotherapy orthopedic rehabilitation center, dr saddam hossain, best physiotherapy dhaka, best physiotherapy jatrabari, ফিজিওথেরাপি যাত্রাবাড়ী, ঢাকায় সেরা ফিজিওথেরাপি">
    <meta name="physiotherapist" content="Dr Saddam Hossain, Senior Consultant Physiotherapist, Course Co-ordinator JBFCPHS">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="ADPORC">
    <meta property="og:title" content="Home | ADPORC">
    <meta property="og:description" content="ADPORC - One of the best physiotherapy centers in Dhaka city for pain, paralysis, and sports injury treatment.">
    <meta property="og:image" content="/assets/images/og-image.jpeg">
    <meta property="og:url" content="https://adporc.com/home">
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
          "name": "Home | ADPORC",
          "url": "https://adporc.com/home",
          "image": "https://adporc.com/assets/images/og-image.jpeg",
          "description": "ADPORC - One of the best physiotherapy centers in Dhaka city for pain, paralysis, and sports injury treatment.",
          "telephone": "+8801950935236",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?= h($address_en) ?>",
            "addressLocality": "Dhaka", "addressRegion": "BD",
            "postalCode": "1204", "addressCountry": "BD"
          },
          "geo": { "@type": "GeoCoordinates", "latitude": "23.7104", "longitude": "90.4074" },
          "sameAs": [
            "<?= h($social['facebook'] ?? 'https://www.facebook.com/@adporc/') ?>",
            "https://www.instagram.com/@adporc/",
            "https://www.tiktok.com/@adporc/",
            "<?= h($social['youtube'] ?? 'https://www.youtube.com/@adporc') ?>",
            "<?= h($social['linkedin'] ?? 'https://www.linkedin.com/company/adporc/posts/') ?>"
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
    <a href="#home" class="skip-to-content">Skip to main content</a>
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
      <article>
        <!-- #hero -->
        <section class="section hero" id="home"
          style="background-image: url('<?= h($hero['bg_image'] ?? '/assets/images/hero-bg.png') ?>')"
          aria-label="hero">
          <div class="container">
            <div class="hero-content">
              <p class="section-subtitle" data-translation
                data-lang-eng="<?= h($hero['subtitle_en'] ?? 'Welcome to ADPORC') ?>"
                data-lang-ban="<?= h($hero['subtitle_bn'] ?? 'এডিপিওআরসি-তে স্বাগতম') ?>"><?= h($hero['subtitle_en'] ?? 'Welcome to ADPORC') ?></p>
              <h1 class="h2 hero-title" data-translation
                data-lang-eng="<?= h($hero['title_en'] ?? 'Asia Digital Physiotherapy & Orthopedic Rehabilitation Center') ?>"
                data-lang-ban="<?= h($hero['title_bn'] ?? 'এশিয়া ডিজিটাল ফিজিওথেরাপি এন্ড অর্থোপেডিক রিহ্যাবিলিটেশন সেন্টার') ?>"><?= h($hero['title_en'] ?? 'Asia Digital Physiotherapy & Orthopedic Rehabilitation Center') ?></h1>
              <p class="hero-text" data-translation
                data-lang-eng="<?= h($hero['text_en'] ?? 'One of the best physiotherapy centers in Dhaka city for the treatment of pain, paralysis & sports injury.') ?>"
                data-lang-ban="<?= h($hero['text_bn'] ?? 'ঢাকার মধ্যে অন্যতম সেরা একটি ফিজিওথেরাপি সেন্টার।') ?>"><?= h($hero['text_en'] ?? '') ?></p>
              <form id="phoneForm" class="hero-form" action="<?= h($google_sheet_url) ?>">
                <input type="tel" name="phone_number" aria-label="phone" placeholder="Your Phone Number..." required class="phone-field font-eng" pattern="[0-9]{11}" title="Please enter a valid 11-digit phone number">
                <button type="submit" class="btn font-eng">Get Call Back</button>
              </form>
            </div>
            <figure class="hero-banner">
              <a href="/team/dr-saddam-hossain-pt">
                <img src="<?= h($hero['banner_image'] ?? '/assets/images/hero-banner.png') ?>" width="587" height="839"
                  alt="<?= h($hero['banner_alt'] ?? 'best physiotherapy center in dholaipar') ?>" class="w-100">
              </a>
            </figure>
          </div>
        </section>

        <!-- #service -->
        <section class="section service" id="service" aria-label="service">
          <div class="container">
            <p class="section-subtitle text-center" data-translation
              data-lang-eng="<?= h($services[0]['subtitle_en'] ?? 'Our Services') ?>"
              data-lang-ban="<?= h($services[0]['subtitle_bn'] ?? 'আমাদের সেবা সমূহ') ?>"><?= h($services[0]['subtitle_en'] ?? 'Our Services') ?></p>
            <h2 class="h2 section-title text-center" data-translation
              data-lang-eng="<?= h($home['services_title_en'] ?? 'Advanced Physiotherapy') ?>"
              data-lang-ban="<?= h($home['services_title_bn'] ?? 'উন্নত ফিজিওথেরাপি') ?>"></h2>
            <ul class="service-list">
              <?php
              $svcInserted = false;
              foreach ($services as $idx => $svc):
                // Insert the center banner image after 3rd item (index 2)
                if ($idx === 3 && !$svcInserted):
                  $svcInserted = true;
              ?>
              <li class="service-banner">
                <figure>
                  <img src="/assets/images/icons/health-care.png" width="409" height="467" loading="lazy" alt="best physiotherapy center in Dhaka" class="w-100">
                </figure>
              </li>
              <?php endif; ?>
              <li>
                <div class="service-card">
                  <div class="card-icon">
                    <a href="<?= h($svc['link'] ?? '#') ?>">
                      <img src="<?= h($svc['icon'] ?? '') ?>" width="100" loading="lazy" alt="best physiotherapy center in Dhaka" class="w-100">
                    </a>
                  </div>
                  <div>
                    <h3 class="h3 card-title" data-translation
                      data-lang-eng="<?= h($svc['title_en'] ?? '') ?>"
                      data-lang-ban="<?= h($svc['title_bn'] ?? '') ?>"><?= h($svc['title_en'] ?? '') ?></h3>
                    <p class="card-text" data-translation
                      data-lang-eng="<?= h($svc['desc_en'] ?? '') ?>"
                      data-lang-ban="<?= h($svc['desc_bn'] ?? '') ?>"></p>
                  </div>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </section>

        <!-- #about -->
        <section class="section about" id="about" aria-label="about">
          <div class="container">
            <figure class="about-banner">
              <a href="/about-us">
                <img src="<?= h($about['banner'] ?? '/assets/images/banners/banner-6.jpeg') ?>" width="470" height="538" loading="lazy" alt="best physiotherapy center in Dhaka" class="w-100">
              </a>
            </figure>
            <div class="about-content">
              <p class="section-subtitle text-center" data-translation
                data-lang-eng="<?= h($about['subtitle_en'] ?? 'About Us') ?>"
                data-lang-ban="<?= h($about['subtitle_bn'] ?? 'আমাদের সম্পর্কে') ?>"></p>
              <h2 class="h2 section-title" data-translation
                data-lang-eng="<?= h($about['title_en'] ?? 'We Care For Your Health') ?>"
                data-lang-ban="<?= h($about['title_bn'] ?? 'আপনার সু-স্বাস্থ্যের ব্যাপারে আমরা যত্নবান') ?>"></h2>
              <p class="section-text section-text-1" data-translation
                data-lang-eng="<?= h($about['text_en'] ?? '') ?>"
                data-lang-ban="<?= h($about['text_bn'] ?? '') ?>"></p>
              <a href="/about-us" class="btn" data-translation data-lang-eng="Read more" data-lang-ban="বিস্তারিত পড়ুন">Read more</a>
            </div>
          </div>
        </section>

        <!-- #physiotherapist -->
        <section class="section doctor" aria-label="doctor" id="our-team">
          <div class="container">
            <p class="section-subtitle text-center" data-translation data-lang-eng="Our Doctors" data-lang-ban="আমাদের ডাক্তাররা">Our Doctors</p>
            <h2 class="h2 section-title text-center" data-translation
              data-lang-eng="Best Expert Clinical Physiotherapist"
              data-lang-ban="সেরা বিশেষজ্ঞ ক্লিনিক্যাল ফিজিওথেরাপিস্ট">Best Expert Clinical Physiotherapist</h2>
            <ul class="has-scrollbar">
              <?php foreach ($team as $member): ?>
              <li class="scrollbar-item">
                <div class="doctor-card">
                  <div class="card-banner img-holder" style="--width: 460; --height: 500;">
                    <?php if (!empty($member['profile_url'])): ?>
                    <a href="<?= h($member['profile_url']) ?>">
                      <img src="<?= h($member['photo'] ?? '') ?>" width="460" height="500" loading="lazy"
                        alt="<?= h($member['photo_alt'] ?? 'physiotherapist dhaka') ?>" class="img-cover">
                    </a>
                    <?php else: ?>
                    <img src="<?= h($member['photo'] ?? '') ?>" width="460" height="500" loading="lazy"
                      alt="<?= h($member['photo_alt'] ?? 'physiotherapist dhaka') ?>" class="img-cover">
                    <?php endif; ?>
                  </div>
                  <h3 class="h3">
                    <?php if (!empty($member['profile_url'])): ?>
                    <a href="<?= h($member['profile_url']) ?>" class="card-title" data-translation
                      data-lang-eng="<?= h($member['name_en'] ?? '') ?>"
                      data-lang-ban="<?= h($member['name_bn'] ?? '') ?>"><?= h($member['name_en'] ?? '') ?></a>
                    <?php else: ?>
                    <span class="card-title" data-translation
                      data-lang-eng="<?= h($member['name_en'] ?? '') ?>"
                      data-lang-ban="<?= h($member['name_bn'] ?? '') ?>"><?= h($member['name_en'] ?? '') ?></span>
                    <?php endif; ?>
                  </h3>
                  <p class="card-subtitle" data-translation
                    data-lang-eng="<?= h($member['role_en'] ?? '') ?>"
                    data-lang-ban="<?= h($member['role_bn'] ?? '') ?>"><?= h($member['role_en'] ?? '') ?></p>
                  <?php
                  $hasSocial = !empty($member['social']['facebook']) || !empty($member['social']['twitter']) || !empty($member['social']['linkedin']);
                  if ($hasSocial): ?>
                  <ul class="card-social-list">
                    <?php if (!empty($member['social']['facebook'])): ?>
                    <li><a href="<?= h($member['social']['facebook']) ?>" target="_blank" rel="noopener noreferrer" class="card-social-link" aria-label="Facebook"><ion-icon name="logo-facebook"></ion-icon></a></li>
                    <?php endif; ?>
                    <?php if (!empty($member['social']['twitter'])): ?>
                    <li><a href="<?= h($member['social']['twitter']) ?>" target="_blank" rel="noopener noreferrer" class="card-social-link" aria-label="Twitter"><ion-icon name="logo-twitter"></ion-icon></a></li>
                    <?php endif; ?>
                    <?php if (!empty($member['social']['linkedin'])): ?>
                    <li><a href="<?= h($member['social']['linkedin']) ?>" target="_blank" rel="noopener noreferrer" class="card-social-link" aria-label="LinkedIn"><ion-icon name="logo-linkedin"></ion-icon></a></li>
                    <?php endif; ?>
                  </ul>
                  <?php endif; ?>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
            <div class="carousel-controls">
              <button class="carousel-btn carousel-prev" aria-label="Previous doctor" data-carousel-prev>
                <ion-icon name="chevron-back-outline"></ion-icon>
              </button>
              <button class="carousel-btn carousel-next" aria-label="Next doctor" data-carousel-next>
                <ion-icon name="chevron-forward-outline"></ion-icon>
              </button>
            </div>
          </div>
        </section>

        <!-- #CTA -->
        <section class="section cta" aria-label="cta">
          <div class="container">
            <figure class="cta-banner">
              <a href="/team/dr-saddam-hossain-pt">
                <img src="<?= h($cta['banner'] ?? '/assets/images/cta-banner.png') ?>" width="1056" height="1076" loading="lazy"
                  alt="best physiotherapy center in dholaipar" class="w-100">
              </a>
            </figure>
            <div class="cta-content">
              <h2 class="section-subtitle" data-translation
                data-lang-eng="<?= h($cta['subtitle_en'] ?? "We're Open and Ready to Welcome You!") ?>"
                data-lang-ban="<?= h($cta['subtitle_bn'] ?? 'আপনাকে স্বাগত জানাতে আমরা প্রস্তুত!') ?>"><?= h($cta['subtitle_en'] ?? "We're Open and Ready to Welcome You!") ?></h2>
              <p class="section-title" data-translation
                data-lang-eng="<?= h($cta['text_en'] ?? 'Schedule your appointment today and take the first step toward a healthier, happier you.') ?>"
                data-lang-ban="<?= h($cta['text_bn'] ?? 'আজই আপনার অ্যাপয়েন্টমেন্ট শিডিউল করুন।') ?>"><?= h($cta['text_en'] ?? '') ?></p>
              <a class="btn appointment-btn" id="appointmentButton2" data-translation data-lang-eng="Book an appointment" data-lang-ban="অ্যাপয়েন্টমেন্ট বুক করুন">Book an appointment</a>
            </div>
          </div>
        </section>

        <!-- #blogs -->
        <section class="section blog" id="blog" aria-label="blog">
          <div class="container">
            <p class="section-subtitle text-center" data-translation data-lang-eng="Our Blogs" data-lang-ban="আমাদের ব্লগ সমূহ">Our Blogs</p>
            <h3 class="h3 section-title text-center" data-translation
              data-lang-eng="ADPORC <br> Physiotherapy Blogs"
              data-lang-ban="ADPORC<br>ফিজিওথেরাপি ব্লগসমূহ">ADPORC <br> Physiotherapy Blogs</h3>
            <div class="blog-list">
              <?php foreach ($blogs as $blog): ?>
              <div class="blog-card">
                <figure class="card-banner">
                  <img src="<?= h($blog['banner'] ?? '') ?>" loading="lazy" alt="blog-img" class="img-cover">
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
            <div class="blog-load-more-wrap">
              <button class="btn load-more-btn" id="loadMoreBlogs" data-translation
                data-lang-eng="Load More Blogs" data-lang-ban="আরও ব্লগ দেখুন">Load More Blogs</button>
            </div>
          </div>
        </section>
      </article>
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
          <!-- other links -->
          <ul class="footer-list">
            <li><p class="footer-list-title" data-translation data-lang-eng="Other Links" data-lang-ban="অন্যান্য লিংক">Other Links</p></li>
            <li><a href="/home" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Home" data-lang-ban="হোম">Home</span></a></li>
            <li><a href="/about-us" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="About Us" data-lang-ban="আমাদের সম্পর্কে">About Us</span></a></li>
            <li><a href="/services" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Our Services" data-lang-ban="আমাদের সেবা সমূহ">Our Services</span></a></li>
            <li><a href="/home#our-team" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Our Team" data-lang-ban="আমাদের টিম">Our Team</span></a></li>
            <li><a href="/blogs" class="footer-link"><ion-icon name="add-outline"></ion-icon><span class="span" data-translation data-lang-eng="Latest Blog" data-lang-ban="সর্বশেষ ব্লগ">Latest Blog</span></a></li>
          </ul>
          <!-- our services -->
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
          <!-- contact us -->
          <ul class="footer-list">
            <li><p class="footer-list-title" data-translation data-lang-eng="Contact Us" data-lang-ban="যোগাযোগ">Contact Us</p></li>
            <li class="footer-item">
              <div class="item-icon"><ion-icon name="location-outline"></ion-icon></div>
              <a href="<?= h($maps_url) ?>" class="footer-link" target="_blank" rel="noopener noreferrer"
                data-translation
                data-lang-eng="<?= h($address_en) ?>"
                data-lang-ban="<?= h($address_bn) ?>"><?= h($address_en) ?></a>
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
      <!-- footer-bottom -->
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
