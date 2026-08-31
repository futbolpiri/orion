<?php
require_once "db.php";  // Veritabanı bağlantısı
require_once "translations.php";  // Çevirileri içeri al
$conn->set_charset("utf8mb4");

// Dil kontrolü: URL'den lang parametresi al, yoksa Türkçe
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'tr';

// Güvenlik: Sadece izin verilen diller
$allowed_langs = ['tr', 'en', 'ar', 'ru', 'es'];
if (!in_array($lang, $allowed_langs)) {
    $lang = 'tr'; // Varsayılan dil: Türkçe
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form verilerini güvenli bir şekilde alıyoruz
    $name = htmlspecialchars(trim($_POST["name"]), ENT_QUOTES, 'UTF-8'); // Ad Soyad
    $email = htmlspecialchars(trim($_POST["email"]), ENT_QUOTES, 'UTF-8'); // Email
    $subject = htmlspecialchars(trim($_POST["subject"]), ENT_QUOTES, 'UTF-8'); // Konu
    $message = htmlspecialchars(trim($_POST["message"]), ENT_QUOTES, 'UTF-8'); // Mesaj

    // Form verilerinin doğruluğunu kontrol et
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "Lütfen tüm alanları doldurun.";
        exit;
    }

    // E-posta adresinin geçerli olup olmadığını kontrol et
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Geçersiz e-posta adresi.";
        exit;
    }

    // E-posta içeriği oluştur
    $to = "info@orioninovasyon.com"; // Alıcı e-posta adresi
    $mailContent = "Ad Soyad: " . $name . "\n" . 
                   "Email: " . $email . "\n" . 
                   "Konu: " . $subject . "\n" . 
                   "Mesaj: \n" . $message;

    // E-posta başlığı ve başlıkları
    $subject = "Website İletişim Formu Mesajı: " . $name; // Konu kısmına kullanıcı adı ekleniyor
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n"; // UTF-8 karakter seti
    $headers .= "X-Mailer: PHP/" . phpversion(); // E-posta kaynağını belirtmek için

    // Mail gönderimi
    if (mail($to, $subject, $mailContent, $headers)) {
        echo "Mesajınız başarıyla gönderildi.";
    } else {
        echo "Mesaj gönderilirken bir hata oluştu.";
    }
}
?>

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orion | <?php echo $translations[$lang]['contact']; ?></title>
    <link rel="icon" href="/orion-beyaz.png" type="image/png">
    <link rel="canonical" href="https://orioninovasyon.com/default.php" />
    <?php require_once "style.php"; ?>
</head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JJSRD5SQR2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-JJSRD5SQR2');
</script>
<body>


    <?php require_once "topmenu.php"; ?>

    <!--== Start Page Header Area ==-->
    <div class="page-header-wrap bg-img" data-bg="assets/img/banner/kirmizibeyazbanner.png">
    <h1 class="bannerTitles"><?php echo $translations[$lang]['contact']; ?></h1>
    </div>
    <!--== End Page Header Area ==-->

    <!--== Start Page Content Wrapper ==-->
    <div class="page-content-wrapper sm-top">
        <div class="contact-page-content">
            <div class="contact-info-wrapper">
                <div class="container">
                    <div class="row mtn-30">
                        <div class="col-sm-6 col-lg-4">
                            <div class="contact-info-item">
                                <div class="con-info-icon">
                                    <i class="ion-ios-location-outline"></i>
                                </div>
                                <div class="con-info-txt">
                                    <h4><?php echo $translations[$lang]['address']; ?></h4>
                                    <p>İTOB OSB MAH. İTOB ATATÜRK CAD. NO: 27 MENDERES / İZMİR</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4">
                            <div class="contact-info-item">
                                <div class="con-info-icon">
                                    <i class="ion-iphone"></i>
                                </div>
                                <div class="con-info-txt">
                                    <h4><?php echo $translations[$lang]['contact']; ?></h4>
                                    <p>Tel: <a href="tel:02322552222">0232 255 22 22</a></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4">
                            <div class="contact-info-item">
                                <div class="con-info-icon">
                                    <i class="ion-ios-email-outline"></i>
                                </div>
                                <div class="con-info-txt">
                                    <h4><?php echo $translations[$lang]['contactus']; ?></h4>
                                    <p><a href="mailto:info@orioninovasyon.com">info@orioninovasyon.com</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-separator-with-logo">
                <div class="line"></div>
                <img src="assets/img/logo/orionn.svg" alt="Logo" class="separator-logo"/>
                <div class="line"></div>
            </div>
<!--
            <div class="contact-form-wrapper sm-top">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="contact-form-content">
                                <h2><?php echo $translations[$lang]['contactus']; ?></h2>
                                <div class="contact-form-wrap">
                                    <form action="contact.php" method="POST" id="contact-form">
                                        <div class="contact-form-inner">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-item">
                                                        <label class="sr-only" for="name"><?php echo $translations[$lang]['fullname']; ?></label>
                                                        <input type="text" name="name" id="name" placeholder="<?php echo $translations[$lang]['fullname']; ?>" required />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-item">
                                                        <label class="sr-only" for="email"><?php echo $translations[$lang]['email']; ?></label>
                                                        <input type="email" name="email" id="email" placeholder="<?php echo $translations[$lang]['email']; ?>" required />
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="input-item">
                                                        <label class="sr-only" for="subject"><?php echo $translations[$lang]['subject']; ?></label>
                                                        <input type="text" name="subject" id="subject" placeholder="<?php echo $translations[$lang]['subject']; ?>" required />
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="input-item">
                                                        <label class="sr-only" for="message"><?php echo $translations[$lang]['message']; ?></label>
                                                        <textarea name="message" id="message" cols="30" rows="8" placeholder="<?php echo $translations[$lang]['message']; ?>" required></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="input-item">
                                                        <button class="btn btn-brand"><?php echo $translations[$lang]['send']; ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-separator-with-logo">
                <div class="line"></div>
                <img src="assets/img/logo/orionn.svg" alt="Logo" class="separator-logo"/>
                <div class="line"></div>
            </div>-->

            <div class="contact-map-wrapper sm-top">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d493.8312849648772!2d27.207451309928803!3d38.19521648727462!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14b9590042a7b47f%3A0xf0e201d569152de!2sOrion%20%C4%B0novasyon%20End%C3%BCstriyel%20San.%20Tic.%20Ltd.%20%C5%9Eti.!5e0!3m2!1str!2str!4v1741257595192!5m2!1str!2str" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <?php require_once "bottommenu.php"; ?>
    <?php require_once "script.php"; ?>
</body>

</html>
