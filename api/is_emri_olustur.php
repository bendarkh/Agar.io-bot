<?php
header('Content-Type: application/json');

// Veritabanı bağlantısını dahil et
require_once 'db_config.php';

// Gelen verileri al (POST metodu ile)
$data = json_decode(file_get_contents('php://input'), true);

$plaka = $data['plaka'] ?? '';
$yapilacak_islem = $data['yapilacak_islem'] ?? '';

// Gerekli alanların kontrolü
if (empty($plaka) || empty($yapilacak_islem)) {
    echo json_encode(['success' => false, 'message' => 'Plaka ve yapılacak işlem alanları zorunludur.']);
    exit();
}

try {
    // 1. Plakaya ait araç var mı kontrol et
    $stmt = $db->prepare("SELECT arac_id FROM araclar WHERE plaka = ?");
    $stmt->execute([$plaka]);
    $arac = $stmt->fetch(PDO::FETCH_ASSOC);

    $arac_id = null;
    if ($arac) {
        $arac_id = $arac['arac_id'];
    } else {
        // Araç bulunamazsa, önce varsayılan bir müşteri oluşturup sonra aracı ekleyebiliriz.
        // Şimdilik basit tutmak adına, araç yoksa hata döndürelim.
        // İlerleyen adımlarda müşteri/araç ekleme ekranı yapılabilir.
        echo json_encode(['success' => false, 'message' => 'Bu plakaya sahip bir araç bulunamadı. Lütfen önce aracı sisteme kaydedin.']);
        exit();
    }

    // 2. İş emrini oluştur
    $stmt = $db->prepare("INSERT INTO is_emirleri (arac_id, yapilacak_islem, durum) VALUES (?, ?, ?)");
    $stmt->execute([$arac_id, $yapilacak_islem, 'Açık']);
    $is_emri_id = $db->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'İş emri başarıyla oluşturuldu.',
        'is_emri_id' => $is_emri_id
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?>
