<?php
// Veritabanı bağlantısını dahil et
require_once "db.php";
require_once "translations.php";  // Çevirileri içeri al

$conn->set_charset("utf8mb4");

// Dil ayarını kontrol et
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'tr';
$validLangs = ['tr', 'en', 'ar', 'ru', 'es'];
if (!in_array($lang, $validLangs)) $lang = 'tr';  // Geçersiz dil durumunda varsayılan 'tr' kullan

// Hazırlanmış ifade ile 'about' tablosundaki veriyi al
$query = "SELECT 
                title1_tr, content1_tr, 
                title2_tr, content2_tr,
                title1_en, content1_en,
                title2_en, content2_en,
                title1_ar, content1_ar,
                title2_ar, content2_ar,
                title1_ru, content1_ru,
                title2_ru, content2_ru,
                title1_es, content1_es,
                title2_es, content2_es
          FROM about WHERE id = ?";

$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    // Parametreyi bağla (id değeri, integer olarak)
    $id = 1;
    mysqli_stmt_bind_param($stmt, "i", $id);  // 'i' integer tipi için

    // Sorguyu çalıştır
    mysqli_stmt_execute($stmt);

    // Sonuçları al
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Veriyi al
        $row = mysqli_fetch_assoc($result);
        
        // Dil seçimine göre doğru sütunu al
        $title1 = $row['title1_' . $lang];
        $content1 = $row['content1_' . $lang];
        $title2 = $row['title2_' . $lang];
        $content2 = $row['content2_' . $lang];
    } else {
        echo "Veri bulunamadı.";
    }

    // Hazırlanmış ifadeyi kapat
    mysqli_stmt_close($stmt);
} else {
    echo "Sorgu hazırlanırken bir hata oluştu.";
}

$query = "SELECT imgName, imgURL FROM images WHERE folderName = 'certificates'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$certificates = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $certificates[] = $row;
    }
} else {
    echo "Sertifika bulunamadı.";
}

$sql = "SELECT * FROM missionvision WHERE id = ?";
$stmt = $conn->prepare($sql);
$id = 1;
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();


// Dinamik sütun adları
$titleColumn = "policyTitle_" . $lang;
$contentColumn = "policyContent_" . $lang;

// SQL sorgusu: Veritabanından doğru dili çekecek şekilde
$sql = "SELECT policyID, $titleColumn AS title, $contentColumn AS content, created_at 
        FROM policy 
        ORDER BY created_at DESC 
        LIMIT 1";

if ($stmt = $conn->prepare($sql)) {
    $stmt->execute();
    $result = $stmt->get_result();
    
    $policyItem = [];

    if ($result->num_rows > 0) {
        $policyItem = $result->fetch_assoc();
    } else {
        $policyItem['title'] = 'Kalite ve Çevre Politikası';
        $policyItem['content'] = 'Henüz bir içerik eklenmemiştir.';
    }

    $stmt->close();
} else {
    die('Veritabanı sorgusu hazırlama hatası: ' . $conn->error);
}

mysqli_close($conn);  // Bağlantıyı kapat
?>
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orion | <?php echo $translations[$lang]['corporate']; ?></title>
    <link rel="icon" href="assets/img/logo/orion-beyaz.png" type="image/png">
    <?php require_once "style.php"; ?>
    <link rel="canonical" href="https://orioninovasyon.com/default.php" />
</head>
<body id="about">

    <?php require_once "topmenu.php"; ?> 

    <!--== Start Page Content Wrapper ==-->
    <section  class="section-block bg-light">
  <div  class="container py-5 about-container">
    <h4 class="text-center mb-4 mt-4 about-title"><?php echo $translations[$lang]['about']; ?></a></li></h4>
    <p class="text-center"><?php echo nl2br(htmlspecialchars($content1)); ?></p>
  </div>
</section>

<div class="section-separator-with-logo">
  <div class="line"></div>
  <img src="assets/img/logo/orionn.svg" alt="Logo" class="separator-logo" />
  <div class="line"></div>
</div>


<section class="section-block">
  <div class="container py-5">
    <div class="row align-items-center">
      <div class="col-md-6">
        <img src="assets/img/orionfabrika/IMG_2329.JPEG" class="img-fluid rounded shadow" alt="Fabrika">
      </div>
      <div class="col-md-6">
        <img src="assets/img/orionfabrika/IMG_2332.JPEG" class="img-fluid rounded shadow" alt="Fabrika">
      </div>
      <div class="col-md-6">
        <h3 class="mb-3"><?php echo htmlspecialchars($title2); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($content2)); ?></p>
      </div>
    </div>
  </div>
</section>

<div class="section-separator-with-logo">
  <div class="line"></div>
  <img src="assets/img/logo/orionn.svg" alt="Logo" class="separator-logo"/>
  <div class="line"></div>
</div>


<section class="section-block bg-light">
  <div class="container py-5 mb-5 text-center">
    <div class="ratio ratio-16x9">
      <iframe src="https://www.youtube.com/embed/beKLhk0XDXk" title="YouTube video" allowfullscreen></iframe>
    </div>
  </div>
</section>


    <!--== End Page Content Wrapper ==-->  

    <section id="missionvision" class="section-block bg-light">
    <div  class="section-separator-with-logo">
  <div class="line"></div>
  <img src="assets/img/logo/orionn.svg" alt="Logo" class="separator-logo"/>
  <div class="line"></div>
</div>
  <div class="container py-5">
    <h4 class="text-center mb-4 mt-4 about-title"><?php echo $translations[$lang]['ourmission']; ?></h4>
    <p class="text-center"><?php echo nl2br(htmlspecialchars($row['missionContent_' . $lang])); ?></p>
  </div>
  </section>


<section class="section-block bg-light">
  <div class="container py-5">
    <h4 class="text-center mb-4 mt-4 about-title"><?php echo $translations[$lang]['ourvision']; ?></h4>
    <p class="text-center"><?php echo nl2br(htmlspecialchars($row['visionContent_' . $lang])); ?></p>
  </div>
</section>

<div class="section-separator-with-logo">
  <div class="line"></div>
  <img src="assets/img/logo/orionn.svg" alt="Logo" class="separator-logo"/>
  <div class="line"></div>
</div>

<!--== End Page Content Wrapper ==-->

    <!--== End Header Area ==-->
    <div id="export" class="export-section">
        <div class="container">
            <div class="row">
                <!-- İstatistikler Bölümü -->
                <div class="col-lg-4">
                    <div class="stats-box">
                        <h2 class="stat-number">30+</h2>
                        <p class="stat-text"><?php echo $translations[$lang]['Exporter']; ?></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="stats-box">
                        <h2 class="stat-number">100+</h2>
                        <p class="stat-text"><?php echo $translations[$lang]['customer']; ?></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="stats-box">
                        <h2 class="stat-number">10 <?php echo $translations[$lang]['year']; ?></h2>
                        <p class="stat-text"><?php echo $translations[$lang]['experience']; ?></p>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-12 text-center">
                    <h3 class="section-title"><?php echo $translations[$lang]['export']; ?></h3>
                    <p><?php echo $translations[$lang]['global_presence']; ?></p>
                    <div class="map-container">
                        <img src="assets/img/extra/World-Map-PNG-Photos.png" alt="World Map">
                        <!-- İkonlar -->
                        <div class="icon" data-country="<?php echo $translations[$lang]['Germany']; ?>" style="top: 30%; left: 50%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['France']; ?>" style="top: 32%; left: 48%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['UK']; ?>" style="top: 28%; left: 46%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Italy']; ?>" style="top: 35%; left: 50%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Spain']; ?>" style="top: 37%; left: 45%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Netherlands']; ?>" style="top: 29%; left: 48%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Russia']; ?>" style="top: 20%; left: 58%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Ukraine']; ?>" style="top: 24%; left: 56%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Kazakhstan']; ?>" style="top: 27%; left: 65%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Uzbekistan']; ?>" style="top: 35%; left: 67%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Kyrgyzstan']; ?>" style="top: 37%; left: 70%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Turkmenistan']; ?>" style="top: 36%; left: 66%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Tajikistan']; ?>" style="top: 39%; left: 69%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Afghanistan']; ?>" style="top: 42%; left: 70%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['China']; ?>" style="top: 44%; left: 76%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['India']; ?>" style="top: 51%; left: 68%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Pakistan']; ?>" style="top: 46%; left: 65%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['UAE']; ?>" style="top: 46%; left: 61%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['SaudiArabia']; ?>" style="top: 50%; left: 59%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Iraq']; ?>" style="top: 40%; left: 58%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Iran']; ?>" style="top: 42%; left: 60%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Egypt']; ?>" style="top: 44%; left: 54%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Algeria']; ?>" style="top: 42%; left: 48%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Morocco']; ?>" style="top: 40%; left: 45%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Tunisia']; ?>" style="top: 41%; left: 50%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Libya']; ?>" style="top: 43%; left: 52%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['SouthAfrica']; ?>" style="top: 80%; left: 53%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Nigeria']; ?>" style="top: 57%; left: 49%;"></div>
                        <div class="icon" data-country="<?php echo $translations[$lang]['Kenya']; ?>" style="top: 60%; left: 58%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tooltip için boş alan -->
    <div id="tooltip" class="tooltip"></div>

    <div class="section-separator-with-logo">
  <div class="line"></div>
  <img src="assets/img/logo/orionn.svg" alt="Logo" class="separator-logo"/>
  <div class="line"></div>
</div>


    <section id="policy" class="section-block bg-light py-5">
  <div  class="container py-5">
    <h4 class="text-center mb-4 mt-4 about-title"><?php echo htmlspecialchars($policyItem['title']); ?></h4>
    <p class="text-center"><?php echo nl2br(htmlspecialchars($policyItem['content'])); ?></p>
  </div>
  </section>

  <div class="section-separator-with-logo">
  <div class="line"></div>
  <img src="assets/img/logo/orionn.svg" alt="Logo" class="separator-logo"/>
  <div class="line"></div>
</div>

        <!-- Sertifikalar Galerisi -->

        <section id="certificates" class="section-block bg-light py-5">
          <div  class="container py-5 mb-5">
          <h2 class="text-center mb-4 mt-4 about-title"><?php echo $translations[$lang]['certificates']; ?></h2>
        <div class="certificates-list">
            <?php if (!empty($certificates)): ?>
                <div class="row">
                    <?php foreach ($certificates as $certificate): ?>
                        <div class="col-sm-6 col-lg-3">
                            <div class="gallery-item" data-mfp-src="<?php echo htmlspecialchars($certificate['imgURL']); ?>">
                                <img src="<?php echo htmlspecialchars($certificate['imgURL']); ?>" alt="gallery"/>
                                <div class="gallery-item__text">
                                    <h3><?php echo htmlspecialchars($certificate['imgName']); ?></h3>  <!-- Başlık -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-certificates">Henüz sertifika eklenmemiş.</p>
            <?php endif; ?>
        </div>
          </div>
        </section>

    
    <?php require_once "bottommenu.php"; ?> 
    <?php require_once "script.php"; ?>
</body>
<script>$(document).ready(function() {
    // Magnific Popup'ı başlatıyoruz
    $('.gallery-item').magnificPopup({
        type: 'image',   // Popup tipi: resim
        gallery: {
            enabled: true  // Galeri modunu aktif hale getiriyoruz
        }
    });
});
</script>

    <?php require_once "bottommenu.php"; ?> 
    <?php require_once "script.php"; ?>
</body>
</html>
