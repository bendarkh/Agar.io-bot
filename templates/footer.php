    </div> <!-- .container kapanışı -->

    <!-- JavaScript dosyalarını sayfa sonunda yüklüyoruz -->
    <!-- Hangi sayfa olduğuna bağlı olarak ilgili script'i yükleyeceğiz -->
    <?php
        // Geçerli sayfanın adını alıyoruz
        $current_page = basename($_SERVER['PHP_SELF']);

        if ($current_page == 'index.php') {
            echo '<script src="js/main.js"></script>';
        } elseif ($current_page == 'takip.php') {
            echo '<script src="js/takip.js"></script>';
        }
    ?>
</body>
</html>
