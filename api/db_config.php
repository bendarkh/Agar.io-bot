<?php
// Veritabanı bağlantı bilgileri
define('DB_HOST', 'UZAK_SUNUCU_IP_ADRESI'); // Uzak SQL sunucusunun adresi
define('DB_USER', 'KULLANICI_ADI');       // Veritabanı kullanıcı adı
define('DB_PASS', 'SIFRE');               // Veritabanı şifresi
define('DB_NAME', 'VERITABANI_ADI');       // Veritabanı adı

// PDO ile veritabanı bağlantısı kurma
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    // Hata modunu ayarlama
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Bağlantı hatası durumunda JSON formatında hata mesajı döndürme
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Veritabanı bağlantı hatası: ' . $e->getMessage()]);
    exit();
}
?>
