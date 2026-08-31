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

// Duyuruları veritabanından çekme
$sql = "SELECT blogID, blogTitle_tr, blogTitle_en, blogTitle_ar, blogTitle_ru, blogTitle_es,
               description_tr, description_en, description_ar, description_ru, description_es,
               blogDate, imgID_1 
        FROM blogs";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$blogItems = [];  // Duyuruları tutacağımız dizi

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Dilin doğru sütununu alıyoruz
        $blogTitle = htmlspecialchars($row['blogTitle_' . $lang]);
        $description = htmlspecialchars($row['description_' . $lang]);
        $blogDate = $row['blogDate'];  // Duyuru tarihi
        $imgID_1 = $row['imgID_1'];

        // imgID_1'i kullanarak images tablosundan resim URL'sini çekme
        $imgQuery = "SELECT imgURL FROM images WHERE imgID = ?";
        $imgStmt = $conn->prepare($imgQuery);
        $imgStmt->bind_param("i", $imgID_1);  // Parametreyi bağla (integer)
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();

        if ($imgResult->num_rows > 0) {
            $imgRow = $imgResult->fetch_assoc();
            $imgURL_1 = htmlspecialchars($imgRow['imgURL']);  // Birinci resmin URL'si
        } else {
            $imgURL_1 = "";  // Resim bulunamadıysa boş bırak
        }

        // Her duyuru için veriyi dizimize ekliyoruz
        $blogItems[] = [
            'blogID' => $row['blogID'],
            'blogTitle' => $blogTitle,
            'description' => $description,
            'blogDate' => $blogDate,
            'imgURL_1' => $imgURL_1
        ];
    }
} else {
    $blogItems = [];  // Eğer blog yoksa boş dizi
}
?>

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orion | <?php echo $translations[$lang]['blogs']; ?></title>
    <meta name="robots" content="index, follow">
    <link rel="icon" href="/orion-beyaz.png" type="image/png">
    <link rel="canonical" href="https://orioninovasyon.com/default.php" />

    <?php require_once "style.php"; ?>
</head>

<body>

    <?php require_once "topmenu.php"; ?>
    <!--== Start Page Header Area ==-->
    <div class="page-header-wrap bg-img" data-bg="assets/img/banner/kirmizibeyazbanner.png">
    <h1 class="bannerTitles"><?php echo $translations[$lang]['blogs']; ?></h1>
    </div>
    <!--== End Page Header Area ==-->

    <!--== Start Page Content Wrapper ==-->
    <div class="page-content-wrapper sp-y">
        <div class="blog-page-content-wrap">
            <div class="container container-wide">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="blog-content-wrapper">
                            <div class="row blog-grid">
                                <?php if (count($blogItems) > 0): ?>
                                    <!-- Döngü ile duyuruları listele -->
                                    <?php foreach ($blogItems as $blogItem): ?>
                                        <div>
                                            <div class="blog-item">
                                                <a href="blog-details.php?blogID=<?php echo $blogItem['blogID']; ?>&lang=<?php echo $lang; ?>">
                                                    <figure class="blog-item__thumb">
                                                        <img src="<?php echo $blogItem['imgURL_1']; ?>" alt="Blog" />
                                                    </figure>
                                                    <div class="blog-item__info">
                                                        <div class="post-date">
                                                            <span><?php echo date('d', strtotime($blogItem['blogDate'])); ?></span>
                                                            <span><?php echo date('M', strtotime($blogItem['blogDate'])); ?></span>
                                                        </div>
                                                        <h2 class="post-title"><?php echo $blogItem['blogTitle']; ?></h2>
                                                        <p class="post-excerpt"><?php echo $blogItem['description']; ?></p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Blog mevcut değil.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--== End Page Content Wrapper ==-->
    
    <?php require_once "bottommenu.php"; ?>
    <?php require_once "script.php"; ?>

</body>

</html>
