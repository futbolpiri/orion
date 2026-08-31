<?php
require_once "db.php";
require_once "translations.php";
$conn->set_charset("utf8mb4");

// Dil kontrolü
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'tr';

// blogID kontrolü
if (isset($_GET['blogID']) && is_numeric($_GET['blogID'])) {
    $blogID = intval($_GET['blogID']);

    // Veritabanından blog çek
    $sql = "SELECT * FROM blogs WHERE blogID = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $blogID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $blog = $result->fetch_assoc();

            // Dil uzantılarını kontrol et
            $title_col = "blogTitle_" . $lang;
            $desc_col = "description_" . $lang;
            $content_col = "content_" . $lang;

            // Eğer sütun boşsa Türkçeye geri düş
            $blogTitle = !empty($blog[$title_col]) ? $blog[$title_col] : $blog["blogTitle_tr"];
            $blogDescription = !empty($blog[$desc_col]) ? $blog[$desc_col] : $blog["description_tr"];
            $blogContent = !empty($blog[$content_col]) ? $blog[$content_col] : $blog["content_tr"];

            $blogSource = $blog['source'];
            $blogDate = date('d M Y', strtotime($blog['blogDate']));

            // Resim çek
            $imgURL_1 = "";
            if (!empty($blog['imgID_1'])) {
                $imgQuery = "SELECT imgURL FROM images WHERE imgID = ?";
                $imgStmt = $conn->prepare($imgQuery);
                if ($imgStmt) {
                    $imgStmt->bind_param("i", $blog['imgID_1']);
                    $imgStmt->execute();
                    $imgResult = $imgStmt->get_result();
                    if ($imgResult->num_rows > 0) {
                        $imgRow = $imgResult->fetch_assoc();
                        $imgURL_1 = $imgRow['imgURL'];
                    }
                }
            }
        } else {
            die("Blog bulunamadı.");
        }

        $stmt->close();
    } else {
        die("Sorgu hazırlanırken hata oluştu.");
    }
} else {
    die("Geçersiz blog ID.");
}

$conn->close();
?>


<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orion | <?php echo htmlspecialchars($blogTitle); ?></title>
    <link rel="icon" href="assets/img/logo/orion-beyaz.png" type="image/png">
    <?php require_once "style.php"; ?>
    <link rel="canonical" href="https://orioninovasyon.com/default.php" />
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
    <h1 class="bannerTitles"><?php echo htmlspecialchars($blogTitle); ?></h1>
    </div>
    <!--== End Page Header Area ==-->

    <main class="content">
        <section class="article">
            <h1><?php echo htmlspecialchars($blogTitle); ?></h1>
            <p class="date"><?php echo $blogDate; ?></p>

            <?php if (!empty($imgURL_1)): ?>
                <div class="image-container">
                    <img src="<?php echo htmlspecialchars($imgURL_1); ?>" alt="<?php echo htmlspecialchars($blogTitle); ?>" class="article-image">
                </div>
            <?php endif; ?>


            <p class="content"><?php echo nl2br(htmlspecialchars($blogContent)); ?></p>
            <footer class="sources">
                <p>Kaynakça: <a href="<?php echo htmlspecialchars($blogSource); ?>" target="_blank"><?php echo htmlspecialchars($blogSource); ?></a></p>
            </footer>
        </section>
    </main>

    <?php require_once "bottommenu.php"; ?>
    <?php require_once "script.php"; ?>
</body>

</html>
