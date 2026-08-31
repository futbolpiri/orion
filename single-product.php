<?php
require_once "db.php";
require_once "translations.php";  // Çevirileri içeri al
$conn->set_charset("utf8mb4");

// Dil kontrolü: URL'den lang parametresi al, yoksa Türkçe
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'tr';

// Güvenlik: Sadece izin verilen diller
$allowed_langs = ['tr', 'en', 'ar', 'ru', 'es'];
if (!in_array($lang, $allowed_langs)) {
    $lang = 'tr'; // Varsayılan dil: Türkçe
}
if (isset($_GET['productID'])) {
    $productID = intval($_GET['productID']); // Güvenlik için integer'a çevir
} else {
    die("Ürün bulunamadı.");
}

// Ürün bilgilerini çek
$sql = "SELECT * FROM products WHERE productID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Ürün bulunamadı.");
}

$product = $result->fetch_assoc();

// Ürün resimlerini al
$images = [];
for ($i = 1; $i <= 5; $i++) {
    $imgID = $product["imgID_" . $i];
    if (!empty($imgID)) {
        $imgQuery = "SELECT imgURL FROM images WHERE imgID = ?";
        $imgStmt = $conn->prepare($imgQuery);
        $imgStmt->bind_param("i", $imgID);
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();
        if ($imgResult->num_rows > 0) {
            $imgRow = $imgResult->fetch_assoc();
            $images[] = htmlspecialchars($imgRow['imgURL']); // XSS güvenliği için htmlspecialchars
        }
    }
}

// Ürüne ait tüm WVA kodlarını al
$wvaCodes = [];
$wvaQuery = "SELECT wvaCode FROM product_wva WHERE productID = ?";
$wvaStmt = $conn->prepare($wvaQuery);
$wvaStmt->bind_param("i", $productID);
$wvaStmt->execute();
$wvaResult = $wvaStmt->get_result();
while ($wvaRow = $wvaResult->fetch_assoc()) {
    $wvaCodes[] = htmlspecialchars($wvaRow['wvaCode']); // XSS güvenliği için htmlspecialchars
}
?>

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orion | <?php echo htmlspecialchars($product['productName_tr']); ?></title>
    <link rel="icon" href="assets/img/logo/orion-beyaz.png" type="image/png">
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
    <div id="loader" class="loader">
        <div class="spinner"></div>
    </div>
    <?php require_once "topmenu.php"; ?>

    <div class="page-header-wrap bg-img" data-bg="assets/img/banner/kirmizibeyazbanner.png">
    <h1 class="bannerTitles"><?php echo htmlspecialchars($product['productName_tr']); ?></h1>
    </div>

    <div class="page-content-wrapper sp-y">
        <div class="product-details-page-content">
            <div class="container container-wide">
                <div class="row">
                    <div class="col-md-5">
                        <div class="product-thumb-area">
                            <div class="product-details-thumbnail">
                                <div class="product-thumbnail-slider" id="thumb-gallery">
                                    <?php foreach ($images as $index => $img): ?>
                                        <?php if ($index === 0): ?>
                                            <figure class="pro-thumb-item">
                                                <a href="<?php echo $img; ?>" class="popup-image" data-title="Photo">
                                                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($product['productName_tr']); ?>" class="big-photo" />
                                                </a>
                                            </figure>
                                        <?php else: ?>
                                            <figure class="pro-thumb-item">
                                                <a href="<?php echo $img; ?>" class="popup-image" data-title="Photo">
                                                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($product['productName_tr']); ?>" class="small-photo" />
                                                </a>
                                            </figure>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="product-details-thumbnail-nav">
                                <?php foreach ($images as $img): ?>
                                    <figure class="pro-thumb-item">
                                        <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($product['productName_tr']); ?>" />
                                    </figure>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="product-details-info-content-wrap">
                            <div class="prod-details-info-content">
                                <h2 class="product-title"><?php echo htmlspecialchars($product['productName_tr']); ?></h2>
                                <p class="product-description"><?php echo nl2br(htmlspecialchars($product['productContent_tr'])); ?></p>
                                <div class="product-config">
                                    <div class="table-responsive">
                                        <table class="table table-bordered product-table">
                                            <tr>
                                                <th class="config-label"><?php echo $translations[$lang]['orioncode']; ?></th>
                                                <td class="config-option"><?php echo htmlspecialchars($product['productCode']); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="config-label"><?php echo $translations[$lang]['wvacode']; ?></th>
                                                <td class="config-option">
                                                    <?php if (!empty($wvaCodes)): ?>
                                                        <ul>
                                                            <?php foreach ($wvaCodes as $wvaCode): ?>
                                                                <li><?php echo htmlspecialchars($wvaCode); ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php else: ?>
                                                        Belirtilmemiş
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="config-label"><?php echo $translations[$lang]['vehicle_model']; ?></th>
                                                <td class="config-option">
                                                    <?php
                                                        if (!empty($product['vehicle_model'])) {
                                                            echo htmlspecialchars($product['vehicle_model']);
                                                        } else {
                                                            echo $translations[$lang]['not_specified']; // veya sabit yazacaksan "Belirtilmemiş" de diyebilirsin
                                                        }
                                                    ?>
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "bottommenu.php"; ?>
    <?php require_once "script.php"; ?>

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

            $('.small-photo').on('click', function() {
                var newSrc = $(this).attr('src');
                $('.big-photo').attr('src', newSrc);
            });
        });
    </script>
</body>
</html>
