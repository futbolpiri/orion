<?php
// Veritabanı bağlantısı
require_once "db.php";  // Veritabanı bağlantısı
require_once "translations.php";  // Çevirileri içeri al
$conn->set_charset("utf8mb4");
$lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'tr';
// Sertifikaları veritabanından çekme
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

// Veritabanı bağlantısını kapat
mysqli_close($conn);
?>

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orion | <?php echo $translations[$lang]['certificates']; ?></title>
    <link rel="icon" href="/orion-beyaz.png" type="image/png">
    <link rel="canonical" href="https://orioninovasyon.com/default.php" />
    <?php require_once "style.php"; ?>
</head>

<body>

    <?php require_once "topmenu.php"; ?> 


    <!-- Sertifikalar Galerisi -->
    <div class="certificates-container">
        <h2 class="certificates-title"><?php echo $translations[$lang]['certificates']; ?></h2>
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

</html>
