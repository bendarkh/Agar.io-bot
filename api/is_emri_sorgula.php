<?php
header('Content-Type: application/json');
require_once 'db_config.php';

// GET parametresi olarak gelen plaka bilgisini al
$plaka = strtoupper(str_replace(' ', '', $_GET['plaka'] ?? ''));

if (empty($plaka)) {
    echo json_encode(['success' => false, 'message' => 'Lütfen bir plaka giriniz.']);
    exit();
}

try {
    // Plakaya ait aracı ve müşteriyi, bu araca ait tüm iş emirlerini birleştirerek getir
    $stmt = $db->prepare("
        SELECT
            a.plaka, a.marka, a.model,
            m.ad, m.soyad,
            ie.is_emri_id,
            ie.yapilacak_islem,
            ie.durum,
            DATE_FORMAT(ie.acilis_tarihi, '%d.%m.%Y %H:%i') AS acilis_tarihi,
            DATE_FORMAT(ie.kapanis_tarihi, '%d.%m.%Y %H:%i') AS kapanis_tarihi
        FROM araclar a
        JOIN musteriler m ON a.musteri_id = m.musteri_id
        LEFT JOIN is_emirleri ie ON a.arac_id = ie.arac_id
        WHERE a.plaka = ?
        ORDER BY ie.acilis_tarihi DESC
    ");

    $stmt->execute([$plaka]);
    $sonuclar = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($sonuclar)) {
        echo json_encode(['success' => false, 'message' => 'Bu plakaya ait kayıt bulunamadı.']);
    } else {
        echo json_encode(['success' => true, 'data' => $sonuclar]);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?>
