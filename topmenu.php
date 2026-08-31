    <!--== End Header Area ==-->



    <!--== Start Header Area ==-->
  <!--== Start Header Area ==-->
<!--== Start Header Area ==-->
<header class="header-area">
  <div class="container container-wide">
    <div class="row align-items-center">
      <!-- Logo -->
      <div class="col-lg-3 text-center text-md-center col-md-6">
        <div class="site-logo">
          <a href="default.php?lang=<?php echo $lang; ?>">
            <img src="assets/img/logo/orionbeyaz.svg" alt="Logo" />
          </a>
        </div>
      </div>

      <!-- Menü (Masaüstü ve Tablet için) -->
      <div class="col-lg-8 d-none d-lg-block">
        <nav class="site-navigation">
          <ul class="main-menu nav">
            <li><a href="default.php?lang=<?php echo $lang; ?>"><?php echo $translations[$lang]['home']; ?></a></li>
            <li><a href="about.php?lang=<?php echo $lang; ?>"><?php echo $translations[$lang]['corporate']; ?></a></li>
            <li><a href="products.php?lang=<?php echo $lang; ?>"><?php echo $translations[$lang]['products']; ?></a></li>
            <li><a href="blog.php?lang=<?php echo $lang; ?>"><?php echo $translations[$lang]['blogs']; ?></a></li>
            <li><a href="news.php?lang=<?php echo $lang; ?>"><?php echo $translations[$lang]['news']; ?></a></li>
            <li><a href="gallery.php?lang=<?php echo $lang; ?>"><?php echo $translations[$lang]['gallery']; ?></a></li>
            <li><a href="contact.php?lang=<?php echo $lang; ?>"><?php echo $translations[$lang]['contact']; ?></a></li>
          </ul>
        </nav>
      </div>

      <!-- Mobil Menü Butonu -->
      <div class="responsive-menu d-lg-none col-md-6">
        <button class="btn-menu">
          <i class="fa fa-bars"></i>
        </button>
      </div>

      <!-- Dil Seçimi -->
      <div class="header-contact col-lg-1">
        <form id="langForm" method="get" onchange="document.getElementById('langForm').submit();">
          <select name="lang" class="lang-select" style="width: auto; display: inline-block;">
            <option value="tr" <?php if ($lang == 'tr') echo 'selected'; ?>>TR</option>
            <option value="en" <?php if ($lang == 'en') echo 'selected'; ?>>EN</option>
            <option value="ru" <?php if ($lang == 'ru') echo 'selected'; ?>>RU</option>
          </select>
          <?php
            $currentUrl = strtok($_SERVER["REQUEST_URI"], '?');
            $query = $_GET;
            unset($query['lang']);
            foreach ($query as $key => $value) {
              echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
            }
          ?>
        </form>
      </div>
    </div>
  </div>
</header>

<!-- Mobil Menü İçin Logo ve Dil Seçimi (Kopyalanacak) -->
<div class="res-mobile-menu-extras d-none">
  <div class="mobile-logo text-center mb-3">
    <a href="default.php?lang=<?php echo $lang; ?>">
      <img src="assets/img/logo/orionnn.svg" alt="Logo" style="max-width: 200px; margin: 0;" />
    </a>
  </div>
  <div class="mobile-lang-select text-center mb-3">
    <form id="mobileLangForm" method="get" onchange="document.getElementById('mobileLangForm').submit();">
      <select name="lang" class="lang-select" style="width: 100px; margin: 0 auto;">
        <option value="tr" <?php if ($lang == 'tr') echo 'selected'; ?>>TR</option>
        <option value="en" <?php if ($lang == 'en') echo 'selected'; ?>>EN</option>
        <option value="ru" <?php if ($lang == 'ru') echo 'selected'; ?>>RU</option>
      </select>
      <?php
        $currentUrl = strtok($_SERVER["REQUEST_URI"], '?');
        $query = $_GET;
        unset($query['lang']);
        foreach ($query as $key => $value) {
          echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
        }
      ?>
    </form>
  </div>
</div>

