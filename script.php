    <!--=======================Javascript============================-->
    <!-- build:js assets/js/app.min.js -->
    <!--=== Modernizr Min Js ===-->
    <script src="assets/js/modernizr-3.6.0.min.js"></script>
    <!--=== jQuery Min Js ===-->
    <script src="assets/js/jquery.min.js"></script>
    <!--=== jQuery Migration Min Js ===-->
    <script src="assets/js/jquery-migrate.min.js"></script>
    <!--=== Popper Min Js ===-->
    <script src="assets/js/popper.min.js"></script>
    <!--=== Bootstrap Min Js ===-->
    <script src="assets/js/bootstrap.min.js"></script>
    <!--=== Slicknav Min Js ===-->
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <!--=== Magnific Popup Min Js ===-->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!--=== Slick Slider Min Js ===-->
    <script src="assets/js/slick.min.js"></script>
    <!--=== Nice Select Min Js ===-->
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <!--=== Leaflet Min Js ===-->
    <script src="assets/js/leaflet.min.js"></script>
    <!--=== Countdown Js ===-->
    <script src="assets/js/countdown.js"></script>

    <!--=== Active Js ===-->
    <script src="assets/js/active.js"></script>
    <!-- endbuild -->

    <script>// Sayfa bağlantısına tıklanmasını izleriz
// Sayfa yüklenmeye başladığında animasyonu göster
window.addEventListener('DOMContentLoaded', function() {
    var loader = document.getElementById("loader");
    loader.style.visibility = "visible"; // Animasyonu görünür yap
    loader.style.opacity = "1"; // Animasyonu görünür yap
});

// Sayfa tamamen yüklendiğinde animasyonu gizle
window.addEventListener('load', function() {
    var loader = document.getElementById("loader");

    // Animasyonu gizle (1 saniyede kaybolacak)
    setTimeout(function() {
        loader.style.opacity = "0";  // Görünürlüğü sıfırlama
        setTimeout(function() {
            loader.style.visibility = "hidden";  // Tamamen gizler
        }, 1000); // 1 saniye sonra visibility'yi gizleriz
    }, 500);  // Sayfa tam yüklendikten sonra animasyonu gizle
});
</script>