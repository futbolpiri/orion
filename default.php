<?php
require_once "db.php";
$conn->set_charset("utf8mb4");
require_once "translations.php";  // Çevirileri içeri al


// Dil ayarı
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'tr';
$validLangs = ['tr', 'en', 'ar', 'ru', 'es'];
if (!in_array($lang, $validLangs)) $lang = 'tr';

// SLİDER BAŞLANGIÇ
$sql = "SELECT * FROM slider";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$sliders = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $h3title = htmlspecialchars($row['h3title_' . $lang], ENT_QUOTES, 'UTF-8');
        $h2title = htmlspecialchars($row['h2title_' . $lang], ENT_QUOTES, 'UTF-8');
        $button = htmlspecialchars($row['button_' . $lang], ENT_QUOTES, 'UTF-8');
        $imgID = (int)$row['imgID'];

        $imgQuery = "SELECT imgURL FROM images WHERE imgID = ?";
        $imgStmt = $conn->prepare($imgQuery);
        $imgStmt->bind_param("i", $imgID);
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();

        $sliderImgURL = ($imgResult->num_rows > 0) ? $imgResult->fetch_assoc()['imgURL'] : "";

        $sliders[] = [
            'h3title' => $h3title,
            'h2title' => $h2title,
            'button' => $button,
            'imgURL' => $sliderImgURL
        ];
    }
}
// SLİDER BİTİŞ

// SON 3 DUYURU
$sql = "SELECT newsID, newsTitle_$lang AS newsTitle, description_$lang AS description, newsDate, imgID_1 FROM news ORDER BY newsDate DESC LIMIT 3";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$latestNews = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imgQuery = "SELECT imgURL FROM images WHERE imgID = ?";
        $imgStmt = $conn->prepare($imgQuery);
        $imgStmt->bind_param("i", $row['imgID_1']);
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();
        $imgURL = ($imgResult->num_rows > 0) ? $imgResult->fetch_assoc()['imgURL'] : "assets/img/default-news.jpg";
        
        $latestNews[] = [
            'newsID' => (int)$row['newsID'],
            'newsTitle' => htmlspecialchars($row['newsTitle'], ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'),
            'newsDate' => $row['newsDate'],
            'imgURL' => $imgURL
        ];
    }
}
?>
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-FVBZKSJQ19"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-FVBZKSJQ19');
</script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Orion | <?php echo $translations[$lang]['home']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Orion İnovasyon, İzmir İTOB'da yer alan yenilikçi bir balata üreticisidir.">
    <link rel="icon" href="/orion-beyaz.png" type="image/png">
    <?php require_once "style.php"; ?>
</head>
<body>

    <?php require_once "topmenu.php"; ?>

    <!-- Slider -->
    <div class="slider-area-wrapper">
        <div class="slider-content-active">
            <?php foreach ($sliders as $slider): ?>
                <div class="slider-slide-item bg-img" style="background-image: url('<?php echo $slider['imgURL']; ?>');">
                    <div class="container container-wide h-100">
                        <div class="row align-items-center h-100">
                            <div class="col-lg-12">
                                <div class="slide-content">
                                    <div class="slide-content-inner">
                                        <h3><?php echo $slider['h3title']; ?></h3>
                                        <h2><?php echo $slider['h2title']; ?></h2>
                                        <a class="btn btn-white" href="products.php?lang=<?php echo $lang; ?>"><?php echo $slider['button']; ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Banner Alanı -->
    <!-- Banner Alanı -->
    <div class="banner-area-wrapper banner-mt">
        <div class="container container-wide">
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="banner-item">
                        <a href="about.php?lang=<?php echo $lang; ?>" class="banner-item__img">
                            <div class="banner-item__img-wrapper">
                                <img src="assets/img/orionfabrika/IMG_2095.JPEG" alt="Kurumsal" />
                            </div>
                            <div class="banner-item__overlay"><h2><?php echo $translations[$lang]['corporate']; ?></h2></div>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="banner-item">
                        <a href="export.php?lang=<?php echo $lang; ?>" class="banner-item__img">
                            <div class="banner-item__img-wrapper">
                                <img src="assets/img/orionfabrika/IMG_2048.JPG" alt="İhracat" />
                            </div>
                            <div class="banner-item__overlay"><h2><?php echo $translations[$lang]['export']; ?></h2></div>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="banner-item">
                        <a href="products.php?lang=<?php echo $lang; ?>" class="banner-item__img">
                            <div class="banner-item__img-wrapper">
                                <img src="assets/img/orionfabrika/mavikutu.JPG" alt="Ürünler" />
                            </div>
                            <div class="banner-item__overlay"><h2><?php echo $translations[$lang]['products']; ?></h2></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Katalog Çağrısı -->
    <div class="call-to-action-area">
        <div class="call-to-action-content-area">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="call-to-action-txt-anasayfa">
                            <h2><?php echo $translations[$lang]['catalog_title']; ?></h2>
                            <p><a href="assets/documans/OrionCatalog.pdf" target="_blank"><button class="pdfbuton"><?php echo $translations[$lang]['catalog_button']; ?></button></a></p>
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
    <!-- Duyurular -->
    <div class="duyurular-area-wrapper">
        <div class="container">
            <div class="row">
                <?php foreach ($latestNews as $newsItem): ?>
                    <div class="col-md-4">
                    
                        <div class="duyuru-item">
                            
                            <a href="news.details.php?newsID=<?php echo $newsItem['newsID']; ?>&lang=<?php echo $lang; ?>">
                                <img src="<?php echo $newsItem['imgURL']; ?>" alt="<?php echo $newsItem['newsTitle']; ?>" class="duyuru-img" />
                                <div class="duyuru-content">
                                    <h3 class="duyuru-title"><?php echo $newsItem['newsTitle']; ?></h3>
                                    <p class="duyuru-text"><?php echo mb_substr($newsItem['description'], 0, 80) . '...'; ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php require_once "bottommenu.php"; ?>
    <?php require_once "script.php"; ?>
</body>
</html>
