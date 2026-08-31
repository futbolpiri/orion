<?php
require_once "db.php";  // Veritabanı bağlantısı
require_once "translations.php";  // Çevirileri içeri al
$conn->set_charset("utf8mb4");
$lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'tr';

// Arama sorgusunu al ve temizle
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// SQL sorgusunu hazırlıyoruz
$sql = "SELECT p.productID, p.productName_tr, p.productCode, p.imgID_1, p.imgID_2, 
               GROUP_CONCAT(DISTINCT pw.wvaCode ORDER BY pw.wvaCode SEPARATOR ', ') AS wvaCodes
        FROM products p
        LEFT JOIN product_wva pw ON p.productID = pw.productID";

// Eğer arama sorgusu varsa, WHERE ifadesini ekle
if (!empty($search)) {
    $sql .= " WHERE p.productID LIKE ? OR p.productName_tr LIKE ? OR p.productCode LIKE ? OR pw.wvaCode LIKE ?";
}

// GROUP BY ifadesi
$sql .= " GROUP BY p.productID, p.productName_tr, p.productCode, p.imgID_1, p.imgID_2";

// Prepared statement ile sorgu hazırlıyoruz
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('SQL sorgusu hazırlama hatası: ' . $conn->error);
}

// Parametreleri bağla ve sorguyu çalıştır
if (!empty($search)) {
    $searchParam = "%" . $search . "%";
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();
$products = [];

while ($row = $result->fetch_assoc()) {
    $productID = $row['productID'];
    $productName = $row['productName_tr'];
    $productCode = $row['productCode'];
    $imgID_1 = $row['imgID_1'];
    $imgID_2 = $row['imgID_2'];
    $wvaCode = $row['wvaCodes'];

    // Resim URL'lerini çekmek için fonksiyon
    if (!function_exists('getImageURL')) {
        function getImageURL($imgID, $conn) {
            $defaultImage = "assets/img/no-image.png";  // Varsayılan resim
            if (empty($imgID)) {
                return $defaultImage;
            }

            // Resim URL'sini almak için sorgu
            $imgQuery = "SELECT imgURL FROM images WHERE imgID = ?";
            $stmt = $conn->prepare($imgQuery);
            $stmt->bind_param("i", $imgID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_assoc()['imgURL'];
            } else {
                return $defaultImage;
            }
        }
    }

    // Resim URL'lerini al
    $imgURL_1 = getImageURL($imgID_1, $conn);
    $imgURL_2 = getImageURL($imgID_2, $conn);

    // Ürünleri diziye ekle
    $products[] = [
        'productID' => $productID,
        'productName' => $productName,
        'productCode' => $productCode,
        'imgURL_1' => $imgURL_1,
        'imgURL_2' => $imgURL_2,
        'wvaCode' => $wvaCode
    ];
}
?>

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orion | <?php echo $translations[$lang]['products']; ?></title>
    <meta name="description" content="Orion İnovasyon'un yüksek kaliteli endüstriyel ve ağır vasıta balata çözümlerini keşfedin. Güvenli ve dayanıklı fren sistemleri için ideal çözümler!">
    <meta name="robots" content="index, follow">
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
    <h1 class="bannerTitles"><?php echo $translations[$lang]['products']; ?></h1>
    </div>
    <!--== End Page Header Area ==-->

    <!-- Arama Formu -->
    <div id="products" class="search-form">
        <form action="products.php" method="get">
            <input type="text" name="search" placeholder="<?php echo $translations[$lang]['search']; ?>" value="<?php echo htmlspecialchars($search); ?>" />
            <button type="submit"><?php echo $translations[$lang]['search_button']; ?></button>
        </form>
    </div>

<!-- Ürünleri Listeleme -->
<div class="page-content-wrapper sp-y">
    <div class="shop-page-product">
        <div class="container container-wide">
            <div class="product-wrapper product-layout layout-grid">
                <div class="row mtn-30">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="product-item">
                                    <div class="product-item__thumb">
                                        <a href="single-product.php?productID=<?php echo $product['productID']; ?>" 
                                           title="<?php echo htmlspecialchars($product['productName'] . ' | Ağır Vasıta Balata | Fren Balatası'); ?>"
                                           aria-label="<?php echo htmlspecialchars($product['productName'] . ' | Fren Balatası | Ağır Vasıta Balata'); ?>">
                                            <img class="thumb-primary" src="<?php echo $product['imgURL_1']; ?>" 
                                                 alt="<?php echo htmlspecialchars($product['productName'] . ' | Balata | Fren Balatası | Ağır Vasıta Balata'); ?>" />
                                            <img class="thumb-secondary" src="<?php echo $product['imgURL_2']; ?>" 
                                                 alt="<?php echo htmlspecialchars($product['productName'] . ' | Balata | Fren Balatası | Ağır Vasıta Balata'); ?>" />
                                        </a>
                                    </div>
                                    <div class="product-item__content">
                                        <h4 class="product-title">
                                            <a href="single-product.php?productID=<?php echo $product['productID']; ?>">
                                                <?php echo htmlspecialchars($product['productName']); ?>
                                            </a>
                                        </h4>
                                        <p><?php echo $translations[$lang]['orioncode']; ?> <strong><?php echo htmlspecialchars($product['productCode']); ?></strong></p>
                                        <p><?php echo $translations[$lang]['wvacode']; ?> <strong><?php echo htmlspecialchars($product['wvaCode']) ?: 'Bilgi yok'; ?></strong></p>
                                        <p class="hidden-text"><?php echo htmlspecialchars($product['productName']); ?>, ağır vasıtalar için yüksek performanslı bir <strong>balata</strong> modelidir. Endüstriyel kullanım için üretilmiş <strong>fren balatası</strong> seçeneklerimizi keşfedin.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align:center; width:100%;"><?php echo $translations[$lang]['notfound']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SEO Meta Açıklaması -->
<meta name="description" content="Ağır vasıta balataları ve yüksek kaliteli fren balataları. Orion'un dayanıklı ve uzun ömürlü balata modellerini keşfedin.">


    <?php require_once "bottommenu.php"; ?>
    <?php require_once "script.php"; ?>
</body>
</html> 