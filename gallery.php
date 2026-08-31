<?php
// Veritabanı bağlantısını dahil et
require_once 'db.php';  // db.php dosyasının doğru yolunu verdiğinizden emin olun
require_once "translations.php";  // Çevirileri içeri al
// Dil kontrolü: URL'den lang parametresi al, yoksa Türkçe
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'tr';

// Güvenlik: Sadece izin verilen diller
$allowed_langs = ['tr', 'en', 'ar', 'ru', 'es'];
if (!in_array($lang, $allowed_langs)) {
    $lang = 'tr'; // Varsayılan dil: Türkçe
}
$conn->set_charset("utf8mb4");
// Veritabanından haber verilerini çekme (Prepared statement ile SQL Injection koruması)
$sql = "SELECT * FROM news";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Veritabanı sorgusunun sonucu, her haberin fotoğraflarını içerecek şekilde döngüye alınıyor
$news_images = [];  // Haberlerin fotoğraflarını ve başlıklarını tutacak dizi

if ($result->num_rows > 0) {
    // Her satır için verileri al
    while($row = $result->fetch_assoc()) {
        // Her haberin resimlerini almak için bir sorgu
        $images = [];
        for ($i = 1; $i <= 10; $i++) {
            if ($row["imgID_$i"]) {
                // Resim verilerini almak için prepared statement kullanıyoruz
                $image_query = "SELECT imgURL FROM images WHERE imgID = ?";
                $image_stmt = $conn->prepare($image_query);
                $image_stmt->bind_param("i", $row["imgID_$i"]);
                $image_stmt->execute();
                $image_result = $image_stmt->get_result();
                
                if ($image_result->num_rows > 0) {
                    $image_row = $image_result->fetch_assoc();
                    // Resim URL'sini ve haber başlığını dil parametresine göre tutuyoruz
                    $images[] = [
                        'url' => $image_row['imgURL'], 
                        'title_tr' => $row['newsTitle_tr'],
                        'title_en' => $row['newsTitle_en'],
                        'title_ar' => $row['newsTitle_ar'],
                        'title_ru' => $row['newsTitle_ru'],
                        'title_es' => $row['newsTitle_es']
                    ];
                }
            }
        }
        // Haberlerin fotoğraflarını ve başlıklarını birleştiriyoruz
        if (count($images) > 0) {
            $news_images[] = $images;
        }
    }
}
?>

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orion | <?php echo $translations[$lang]['gallery']; ?></title>
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
    <!-- Yükleme Animasyonu -->

    <?php require_once "topmenu.php"; ?>
    
    <!--== Start Page Header Area ==-->
    <div class="page-header-wrap bg-img" data-bg="assets/img/banner/kirmizibeyazbanner.png">
    <h1 class="bannerTitles"><?php echo $translations[$lang]['gallery']; ?></h1>
    </div>
    <!--== End Page Header Area ==-->

    <!--== Start Page Content Wrapper ==-->
    <div class="page-content-wrapper sp-y">
        <div class="gallery-page-content">
            <div class="container container-wide">
            <div class="row mtn-30 image-gallery">
    <?php
    // Veritabanı sorgusu sonucu döngüyle galeri öğelerini listeleme
    foreach ($news_images as $images) {
        foreach ($images as $image) {
            // İlgili dilde başlıkları al
            $imageTitle = htmlspecialchars($image['title_' . $lang]); // Dil seçeneğine göre başlık
    ?>
        <div class="col-sm-6 col-lg-3">
            <div class="gallery-item" data-mfp-src="<?php echo htmlspecialchars($image['url']); ?>">
                <img src="<?php echo htmlspecialchars($image['url']); ?>" alt="gallery" />
                <div class="gallery-item__text">
                    <h3><?php echo $imageTitle; ?></h3>  <!-- Başlık -->
                </div>
            </div>
        </div>
    <?php
        }
    }
    ?>
</div>
            </div>
        </div>
    </div>
    <!--== End Page Content Wrapper ==-->

    <?php require_once "bottommenu.php"; ?>
    <?php require_once "script.php"; ?>
</body>
</html>
