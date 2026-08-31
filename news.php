<?php
require_once "db.php";  // Veritabanı bağlantısı
require_once "translations.php";  // Çevirileri içeri al

// Dil parametresi alınır, varsayılan Türkçe
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'tr';
$conn->set_charset("utf8mb4");

// Duyuruları veritabanından çekme (Yeniden eskiye sıralama)
$sql = "SELECT 
            newsID, 
            newsTitle_tr, description_tr, newsTitle_en, description_en, newsTitle_ar, description_ar, 
            newsTitle_ru, description_ru, newsTitle_es, description_es, 
            newsDate, imgID_1 
        FROM news 
        ORDER BY newsID DESC";
$result = $conn->query($sql);

$newsItems = [];
$errorMessage = ""; // Hata mesajı için değişken

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $newsID = $row['newsID'];
        $newsTitle = htmlspecialchars($row['newsTitle_' . $lang]);  // Dil seçeneğine göre başlık
        $description = htmlspecialchars($row['description_' . $lang]);  // Dil seçeneğine göre açıklama
        $newsDate = $row['newsDate'];
        $imgID_1 = $row['imgID_1'];

        // Resim URL'sini çekme - Güvenli sorgu kullanımı
        $imgQuery = "SELECT imgURL FROM images WHERE imgID = ?";
        if ($imgStmt = $conn->prepare($imgQuery)) {
            $imgStmt->bind_param("i", $imgID_1);
            $imgStmt->execute();
            $imgResult = $imgStmt->get_result();

            if ($imgResult->num_rows > 0) {
                $imgRow = $imgResult->fetch_assoc();
                $imgURL_1 = htmlspecialchars($imgRow['imgURL']);  // XSS koruması
            } else {
                $imgURL_1 = "assets/img/default-news.jpg"; // Varsayılan resim
            }

            $imgStmt->close();
        } else {
            $imgURL_1 = "assets/img/default-news.jpg";  // Varsayılan resim
        }

        $newsItems[] = [
            'newsID' => $newsID,
            'newsTitle' => $newsTitle,
            'description' => $description,
            'newsDate' => $newsDate,
            'imgURL_1' => $imgURL_1
        ];
    }
} else {
    // Veri bulunamadığında hata mesajı atama
    $errorMessage = "Duyuru bulunamadı.";
}
?>
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orion | <?php echo $translations[$lang]['news']; ?></title>
    <link rel="icon" href="/orion-beyaz.png" type="image/png">
    <link rel="canonical" href="https://orioninovasyon.com/default.php" />
    <?php require_once "style.php"; ?>
</head>

<body>
    <!-- Yükleme Animasyonu -->
    <div id="loader" class="loader">
        <div class="spinner"></div>
    </div>

    <?php require_once "topmenu.php"; ?>
    <!--== Start Page Header Area ==-->
    <div class="page-header-wrap bg-img" data-bg="assets/img/banner/kirmizibeyazbanner.png">
    <h1 class="bannerTitles"><?php echo $translations[$lang]['news']; ?></h1>
    </div>
    <!--== End Page Header Area ==-->

    <div class="page-content-wrapper sp-y">
        <div class="news-page-content-wrap">
            <div class="container container-wide">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="news-content-wrapper">
                            <div class="row news-grid">
                                <?php if (count($newsItems) > 0): ?>
                                    <!-- Döngü ile duyuruları listele -->
                                    <?php foreach ($newsItems as $newsItem): ?>
                                        <div>
                                            <div class="news-item">
                                                <a href="news.details.php?newsID=<?php echo $newsItem['newsID']; ?>&lang=<?php echo $lang; ?>">
                                                    <figure class="news-item__thumb">
                                                        <img src="<?php echo $newsItem['imgURL_1']; ?>" alt="News" />
                                                    </figure>
                                                    <div class="news-item__info">
                                                        <div class="post-date">
                                                            <span><?php echo date('d', strtotime($newsItem['newsDate'])); ?></span>
                                                            <span><?php echo date('M', strtotime($newsItem['newsDate'])); ?></span>
                                                        </div>
                                                        <h2 class="post-title"><?php echo $newsItem['newsTitle']; ?></h2>
                                                        <p class="post-excerpt"><?php echo $newsItem['description']; ?></p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Eğer haber bulunmazsa, sadece burada hata mesajı görüntülenir -->
                                    <p><?php echo $errorMessage; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "bottommenu.php"; ?>
    <?php require_once "script.php"; ?>
</body>
</html>    
