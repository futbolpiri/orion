<!--== Start Modern Footer Area Wrapper ==-->
<footer class="footer-area">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget about-widget">
                        <a href="default.php"><img src="assets/img/logo/orionbeyaz.svg" alt="Orion Logo" class="footer-logo" /></a>
                        <p class="footer-text"><?php echo $translations[$lang]['slogan']; ?></p>
                        <ul class="social-icons">
                            <li><a href="https://www.linkedin.com/company/orioni%CC%87novasyon/"  target="blank"><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="https://www.instagram.com/orioninovasyon/"  target="blank"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="https://www.youtube.com/@orioninovasyon" target="blank"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <ul class="footer-links">
                        <li><a href="about.php?lang=<?php echo $lang; ?>#about" title="Hakkımızda"><?php echo $translations[$lang]['corporate']; ?></a></li>
                        <li><a href="about.php?lang=<?php echo $lang; ?>#missionvision" title="Misyon & Vizyon"><?php echo $translations[$lang]['missionvision']; ?></a></li>
                        <li><a href="about.php?lang=<?php echo $lang; ?>#policy" title="Kalite ve Çevre Politikası"><?php echo $translations[$lang]['quality']; ?></a></li>
                        <li><a href="about.php?lang=<?php echo $lang; ?>#certificates" title="Sertifikalar"><?php echo $translations[$lang]['certificates']; ?></a></li>

                        </ul>
                    </div>
                </div>
                
                <!-- Support -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <ul class="footer-links">
                            <li><a href="products.php?lang=<?php echo $lang; ?>#products" title="Ürünler Sayfası"><?php echo $translations[$lang]['products']; ?></a></li>
                            <li><a href="blog.php?lang=<?php echo $lang; ?>#blog" title="Blog Sayfası"><?php echo $translations[$lang]['blogs']; ?></a></li>
                            <li><a href="news.php?lang=<?php echo $lang; ?>#news" title="Duyurular Sayfası"><?php echo $translations[$lang]['news']; ?></a></li>
                            <li><a href="gallery.php?lang=<?php echo $lang; ?>#gallery" title="Galeri Sayfası"><?php echo $translations[$lang]['gallery']; ?></a></li>
                            <li><a href="contact.php?lang=<?php echo $lang; ?>#contact" title="İletişim Sayfası"><?php echo $translations[$lang]['contact']; ?></a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget contact-widget">
                        <p><i class="fas fa-map-marker-alt"></i> İTOB OSB Mah. İTOB Atatürk Cad. No: 27 <br>  Menderes / İZMİR</p>
                        <p><i class="fa-solid fa-envelope"></i> <a href="mailto:info@orioninovasyon.com">info@orioninovasyon.com</a></p>
                        <p><i class="fas fa-phone-alt"></i><a href="tel:02322552222">0232 255 22 22</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Copyright Area -->
    <div class="footer-bottom">
        <div class="container text-center">
        <p><?php echo str_replace('%year%', date('Y'), $translations[$lang]['footer_copy']); ?></p>
        </div>
    </div>
</footer>
<!--== End Modern Footer Area Wrapper ==-->

    <!-- Scroll Top Button -->
    <button class="btn-scroll-top"><i class="ion-chevron-up"></i></button>


    <!--== Start Responsive Menu Wrapper ==-->
    <aside class="off-canvas-wrapper off-canvas-menu">
        <div class="off-canvas-overlay"></div>
        <div class="off-canvas-inner">
            <!-- Start Off Canvas Content -->
            <div class="off-canvas-content">
                <div class="off-canvas-header">
                    <div class="close-btn">
                        <button class="btn-close"><i class="ion-android-close"></i></button>
                    </div>
                </div>

                <!-- Content Auto Generate Form Main Menu Here -->
                <div class="res-mobile-menu mobile-menu">

                </div>
            </div>
        </div>
    </aside>
    <!--== End Responsive Menu Wrapper ==-->