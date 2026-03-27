<?php
http_response_code(401);
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function loadJson($file) {
    $path = __DIR__ . '/data/' . $file;
    if (!file_exists($path)) return null;
    $raw = file_get_contents($path);
    return $raw ? json_decode($raw, true) : null;
}
$site       = loadJson('site.json') ?: [];
$phone      = $site['clinic']['phone']      ?? '01950-935236';
$address_en = $site['clinic']['address_en'] ?? '270/1 Dholaipar, South Jatrabari, Dhaka-1204';
$address_bn = $site['clinic']['address_bn'] ?? '২৭০/১ ধোলাইপাড়, দক্ষিণ যাত্রাবাড়ী, ঢাকা-১২০৪';
$maps_url   = $site['clinic']['maps_url']   ?? 'https://maps.app.goo.gl/GWgL6zbSVLHhMkFY9';
$social     = $site['social']               ?? [];
$ga_id      = $site['analytics']['gtag_id']    ?? 'G-BJMDW5QDN2';
$clarity_id = $site['analytics']['clarity_id'] ?? 'uh2rk88w4t';
$google_sheet_url = $site['google_sheet_appointment_url'] ?? '';
?>
<!-- itzmehdude -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" href="/assets/images/adporc-icon.png" type="image/png">
    <link rel="apple-touch-icon" href="/assets/images/adporc-icon.png">
    <title>401 Unauthorized | ADPORC</title>
    <!-- css -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="/assets/css/style.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Tiro+Bangla:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- Google tag -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= h($ga_id) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= h($ga_id) ?>');
    </script>
  </head>
  <body id="top">
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
              <img src="/assets/images/ADPORC11.png" alt="ADPORC physiotherapy center">
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

    <!-- main -->
    <main>
      <section class="section error" id="error" aria-label="401 error"
        style="min-height:60vh;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:120px 25px;">
        <img src="/adporc-logo-rounded.png" alt="ADPORC logo" style="width:100px;margin-bottom:20px;border-radius:50%;">
        <h1 data-translation
          style="font-size:7rem;color:var(--carolina-red);font-weight:800;line-height:1;"
          data-lang-eng="401" data-lang-ban="৪০১">401</h1>
        <h2 data-translation
          style="font-size:2.4rem;color:var(--oxford-red-1);margin:10px 0 20px;"
          data-lang-eng="Unauthorized"
          data-lang-ban="অননুমোদিত">Unauthorized</h2>
        <p class="card-text" data-translation
          style="max-width:500px;margin-bottom:30px;"
          data-lang-eng="You are not authorized to access this page. Please log in and try again."
          data-lang-ban="আপনি এই পৃষ্ঠায় প্রবেশ করতে অনুমোদিত নন। অনুগ্রহ করে লগইন করে আবার চেষ্টা করুন।">
          You are not authorized to access this page. Please log in and try again.
        </p>
        <div style="display:flex;gap:15px;flex-wrap:wrap;justify-content:center;margin-top:10px;">
          <a href="/admin" class="btn" data-translation data-lang-eng="Go to Login" data-lang-ban="লগইনে যান">Go to Login</a>
          <a href="/home" class="btn" style="background-image:none;background-color:var(--oxford-red-2);"
            data-translation data-lang-eng="Go to Homepage" data-lang-ban="হোমপেজে ফিরে যান">Go to Homepage</a>
        </div>
      </section>
    </main>

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
    <script src="/assets/js/script.js" defer></script>
  </body>
</html>
