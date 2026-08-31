<?php
require_once "db.php";  // Veritabanı bağlantısı
require_once "translations.php";  // Çevirileri içeri al
$conn->set_charset("utf8mb4");

// Dil kontrolü: URL'den `lang` parametresi al, yoksa Türkçe
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'tr';

// Güvenlik: Sadece izin verilen diller
$allowed_langs = ['tr', 'en', 'ar', 'ru', 'es'];
if (!in_array($lang, $allowed_langs)) {
    $lang = 'tr'; // Varsayılan dil: Türkçe
}

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
?>



<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orion | Kalite ve Çevre Politikası</title>
    <link rel="icon" href="assets/img/logo/orion-beyaz.png" type="image/png">
    <link rel="canonical" href="https://orioninovasyon.com/default.php" />
    <?php require_once "style.php"; ?>
</head>

<body>
    <?php require_once "topmenu.php"; ?> 

    <div class="page-content-wrapper sm-top">
        <div class="policy-content-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12 policycol">
                        <div class="policy-content">
                            <h2 class="policy-title"><?php echo htmlspecialchars($policyItem['title']); ?></h2>
                            <p class="policy-text"><?php echo nl2br(htmlspecialchars($policyItem['content'])); ?></p>
                            <img src="assets/img/logo/orionn.svg" alt="Orion Logo" class="policylogo"/>
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
