<?php
require_once "db.php";  // Veritabanı bağlantısı
require_once "translations.php";  // Çevirileri içeri al
$lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'tr';
$conn->set_charset("utf8mb4");

// Dil ayarını kontrol et, varsayılan dil 'tr'
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'tr';

// newsID parametresini al
if (isset($_GET['newsID']) && is_numeric($_GET['newsID'])) {
    $newsID = (int) $_GET['newsID'];
} else {
    die("Geçersiz haber ID!");
}

// Veritabanından duyuru detaylarını çekme
$sql = "SELECT newsTitle_$lang, description_$lang, newsDate, content_$lang, 
               imgID_1, imgID_2, imgID_3, imgID_4, imgID_5, imgID_6, imgID_7, imgID_8, imgID_9, imgID_10 
        FROM news WHERE newsID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $newsID);
$stmt->execute();
$result = $stmt->get_result();

// Sonuç varsa, içeriği göster
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $newsTitle = $row["newsTitle_$lang"];  // Dil bazlı başlık
    $description = $row["description_$lang"];  // Dil bazlı açıklama
    $newsDate = $row['newsDate'];
    $content = $row["content_$lang"];  // Dil bazlı içerik

    // Fotoğraf ID'lerini al
    $imgIDs = [];
    for ($i = 1; $i <= 10; $i++) {
        if (!empty($row["imgID_$i"])) {
            $imgIDs[] = $row["imgID_$i"];
        }
    }
} else {
    die("Haber bulunamadı!");
}

// Fotoğrafların URL'lerini al
$imgURLs = [];
foreach ($imgIDs as $imgID) {
    $imgQuery = "SELECT imgURL FROM images WHERE imgID = ?";
    $imgStmt = $conn->prepare($imgQuery);
    $imgStmt->bind_param("i", $imgID);
    $imgStmt->execute();
    $imgResult = $imgStmt->get_result();

    if ($imgResult->num_rows > 0) {
        $imgRow = $imgResult->fetch_assoc();
        $imgURLs[] = $imgRow['imgURL'];
    } else {
        $imgURLs[] = "assets/img/default-news.jpg"; // Varsayılan resim
    }
}

$stmt->close();
$conn->close();
?>

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orion | <?php echo htmlspecialchars($newsTitle); ?></title>
    <link rel="icon" href="assets/img/logo/orion-beyaz.png" type="image/png">
    <link rel="canonical" href="https://orioninovasyon.com/default.php" />
    <?php require_once "style.php"; ?>

    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
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
    <div class="page-header-wrap bg-img" data-bg="assets/img/banner/kirmizibeyazbanner.png">
    <h1 class="bannerTitles"><?php echo htmlspecialchars($newsTitle); ?></h1>
    </div>
    <!--== End Page Header Area ==-->

    <!--== Start Page Content Wrapper ==-->
    <div class="announcement">
        <div class="announcement-header">
            <h2><?php echo htmlspecialchars($newsTitle); ?></h2>
            <span class="date"><?php echo date('d M Y', strtotime($newsDate)); ?></span>
        </div>

        <!-- Duyuru İçeriği -->
        <div class="announcement-content">
            <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
        </div>

        <!-- Fotoğraflar -->
        <div class="announcement-images">
            <?php foreach ($imgURLs as $index => $imgURL): ?>
                <a href="<?php echo $imgURL; ?>" class="popup-image" data-title="Photo <?php echo $index + 1; ?>">
                    <img src="<?php echo $imgURL; ?>" alt="Duyuru Fotoğrafı <?php echo $index + 1; ?>" class="thumbnail">
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <!--== End Page Content Wrapper ==-->

    <?php require_once "bottommenu.php"; ?>

    <?php require_once "script.php"; ?>

    <!-- Magnific Popup JS -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.popup-image').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                },
                image: {
                    titleSrc: 'data-title',
                    verticalFit: true
                }
            });
        });
    </script>
</body>

</html>
