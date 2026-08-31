<?php
require_once "db.php";
$conn->set_charset("utf8mb4");
require_once "translations.php";  // Çevirileri içeri al
$lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'tr';

// Dil ayarı
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'tr';
$validLangs = ['tr', 'en', 'ar', 'ru', 'es'];
if (!in_array($lang, $validLangs)) $lang = 'tr';
?>

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orion | İhracat Ağımız</title>
    <link rel="icon" href="/orion-beyaz.png" type="image/png">
    <link rel="canonical" href="https://orioninovasyon.com/default.php" />
    <?php require_once "style.php"; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
    <!-- Yükleme Animasyonu -->
    <div id="loader" class="loader">
        <div class="spinner"></div>
    </div>

    <?php require_once "topmenu.php"; ?>
    <!--== Start Page Header Area ==-->
    <div class="page-header-wrap bg-img" data-bg="assets/img/banner/cephe.jpg">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="page-header-content">
                        <div class="page-header-content-inner">
                            <h1><?php echo $translations[$lang]['export']; ?></h1>
                            <ul class="breadcrumb">
                                <li><a href="default.php"><?php echo $translations[$lang]['home']; ?></a></li>
                                <li class="current"><a href="about.html"><?php echo $translations[$lang]['export']; ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--== End Page Header Area ==-->

    <!--== End Header Area ==-->
    <div class="export-section">
        <div class="container">
            <div class="row">
                <!-- İstatistikler Bölümü -->
                <div class="col-lg-4">
                    <div class="stats-box">
                        <h2 class="stat-number">30+</h2>
                        <p class="stat-text">İhracat Yapılan Ülke</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="stats-box">
                        <h2 class="stat-number">100+</h2>
                        <p class="stat-text">Müşteri</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="stats-box">
                        <h2 class="stat-number">10 Yıl</h2>
                        <p class="stat-text">Sektörde Deneyim</p>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-12 text-center">
                    <h3 class="section-title">İhracat Yaptığımız Ülkeler</h3>
                    <p>30'dan fazla ülkeye ihracat yaparak dünya çapında güçlü bir varlık sağlıyoruz.</p>
                    <div class="map-container">
                        <img src="assets/img/extra/World-Map-PNG-Photos.png" alt="World Map">
                        <!-- İkonlar -->
                        <div class="icon" data-country="Almanya" style="top: 30%; left: 50%;"></div>
                        <div class="icon" data-country="Fransa" style="top: 32%; left: 48%;"></div>
                        <div class="icon" data-country="İngiltere" style="top: 28%; left: 46%;"></div>
                        <div class="icon" data-country="İtalya" style="top: 35%; left: 50%;"></div>
                        <div class="icon" data-country="İspanya" style="top: 37%; left: 45%;"></div>
                        <div class="icon" data-country="Hollanda" style="top: 29%; left: 48%;"></div>
                        <div class="icon" data-country="Rusya" style="top: 20%; left: 58%;"></div>
                        <div class="icon" data-country="Ukrayna" style="top: 24%; left: 56%;"></div>
                        <div class="icon" data-country="Kazakistan" style="top: 27%; left: 65%;"></div>
                        <div class="icon" data-country="Özbekistan" style="top: 35%; left: 67%;"></div>
                        <div class="icon" data-country="Kırgızistan" style="top: 37%; left: 70%;"></div>
                        <div class="icon" data-country="Türkmenistan" style="top: 36%; left: 66%;"></div>
                        <div class="icon" data-country="Tacikistan" style="top: 39%; left: 69%;"></div>
                        <div class="icon" data-country="Afganistan" style="top: 42%; left: 70%;"></div>
                        <div class="icon" data-country="Çin" style="top: 44%; left: 76%;"></div>
                        <div class="icon" data-country="Hindistan" style="top: 51%; left: 68%;"></div>
                        <div class="icon" data-country="Pakistan" style="top: 46%; left: 65%;"></div>
                        <div class="icon" data-country="BAE (Dubai)" style="top: 46%; left: 61%;"></div>
                        <div class="icon" data-country="Suudi Arabistan" style="top: 50%; left: 59%;"></div>
                        <div class="icon" data-country="Irak" style="top: 40%; left: 58%;"></div>
                        <div class="icon" data-country="İran" style="top: 42%; left: 60%;"></div>
                        <div class="icon" data-country="Mısır" style="top: 44%; left: 54%;"></div>
                        <div class="icon" data-country="Cezayir" style="top: 42%; left: 48%;"></div>
                        <div class="icon" data-country="Fas" style="top: 40%; left: 45%;"></div>
                        <div class="icon" data-country="Tunus" style="top: 41%; left: 50%;"></div>
                        <div class="icon" data-country="Libya" style="top: 43%; left: 52%;"></div>
                        <div class="icon" data-country="Güney Afrika" style="top: 80%; left: 53%;"></div>
                        <div class="icon" data-country="Nijerya" style="top: 57%; left: 49%;"></div>
                        <div class="icon" data-country="Kenya" style="top: 60%; left: 58%;"></div>
                    </div>
                    <div class="countries-list mt-5">
                        <h4 class="countries-list-title">İhracat Yaptığımız Ülkeler</h4>
                        <ul class="countries-list-items">
                            <!-- Ülkeler listesi burada -->
                            <?php 
                                $countries = ["Almanya", "Fransa", "İngiltere", "İtalya", "İspanya", "Hollanda", "Rusya", 
                                "Ukrayna", "Kazakistan", "Özbekistan", "Kırgızistan", "Türkmenistan", "Tacikistan", "Afganistan", 
                                "Çin", "Hindistan", "Pakistan", "BAE (Dubai)", "Suudi Arabistan", "Irak", "İran", "Mısır", "Cezayir", 
                                "Fas", "Tunus", "Libya", "Güney Afrika", "Nijerya", "Kenya"];
                                foreach ($countries as $country) {
                                    echo "<li>" . htmlspecialchars($country) . "</li>";
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tooltip için boş alan -->
    <div id="tooltip" class="tooltip"></div>

    <?php require_once "bottommenu.php"; ?>

    <?php require_once "script.php"; ?>
    
    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const icons = document.querySelectorAll('.icon');
            const tooltip = document.getElementById('tooltip');

            icons.forEach(function (icon) {
                icon.addEventListener('mouseover', function (e) {
                    const country = e.target.getAttribute('data-country');
                    tooltip.innerHTML = country;  // Tooltip içeriğini ülke adıyla güncelle
                    tooltip.style.top = e.pageY + 10 + 'px'; // Tooltip konumunu güncelle
                    tooltip.style.left = e.pageX + 10 + 'px'; // Tooltip konumunu güncelle
                    tooltip.style.display = 'block'; // Tooltip'i göster
                });

                icon.addEventListener('mouseout', function () {
                    tooltip.style.display = 'none'; // Tooltip'i gizle
                });
            });
        });
    </script>
</body>

</html>
