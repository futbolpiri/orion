<?php
require_once "db.php";
require_once "translations.php";
$conn->set_charset("utf8mb4");

// Dil ayarı
$lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'tr';
$validLangs = ['tr', 'en', 'ar', 'ru', 'es'];
if (!in_array($lang, $validLangs)) $lang = 'tr';

// Veritabanından Misyon & Vizyon çek
$sql = "SELECT * FROM missionvision WHERE id = ?";
$stmt = $conn->prepare($sql);
$id = 1;
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

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
    <title>Orion | Misyon & Vizyon</title>
    <link rel="icon" href="assets/img/logo/orion-beyaz.png" type="image/png">
    <?php require_once "style.php"; ?>
    <link rel="canonical" href="https://orioninovasyon.com/missionvision.php" />
</head>

<body>

<?php require_once "topmenu.php"; ?>

<!--== Start Page Content Wrapper ==-->

<section class="section-block bg-light">
  <div class="container py-5">
    <h4 class="text-left mb-4 mt-4 about-title"><?php echo $lang == 'tr' ? 'Misyonumuz' : 'Our Mission'; ?></h4>
    <p class="text-left"><?php echo nl2br(htmlspecialchars($row['missionContent_' . $lang])); ?></p>
  </div>
</section>

<div class="section-separator-with-logo">
  <div class="line"></div>
  <img src="assets/img/logo/orionn.svg" alt="Logo" class="separator-logo"/>
  <div class="line"></div>
</div>

<section class="section-block">
  <div class="container py-5">
    <h4 class="text-left mb-4 mt-4 about-title"><?php echo $lang == 'tr' ? 'Vizyonumuz' : 'Our Vision'; ?></h4>
    <p class="text-left"><?php echo nl2br(htmlspecialchars($row['visionContent_' . $lang])); ?></p>
  </div>
</section>

<div class="section-separator-with-logo">
  <div class="line"></div>
  <img src="assets/img/logo/orionn.svg" alt="Logo" class="separator-logo"/>
  <div class="line"></div>
</div>

<!--== End Page Content Wrapper ==-->

<?php require_once "bottommenu.php"; ?>
<?php require_once "script.php"; ?>

</body>
</html>
