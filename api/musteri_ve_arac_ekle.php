<?php
header('Content-Type: application/json');
require_once 'db_config.php';

$data = json_decode(file_get_contents('php://input'), true);

// Müşteri bilgileri
$ad = $data['ad'] ?? '';
$soyad = $data['soyad'] ?? '';
$telefon = $data['telefon'] ?? null;
$email = $data['email'] ?? null;
$adres = $data['adres'] ?? null;

// Araç bilgileri
$plaka = strtoupper(str_replace(' ', '', $data['plaka'] ?? '')); // Plakayı büyük harf yap ve boşlukları kaldır
$marka = $data['marka'] ?? null;
$model = $data['model'] ?? null;
$yil = $data['yil'] ?? null;

// Zorunlu alan kontrolü
if (empty($ad) || empty($soyad) || empty($plaka)) {
    echo json_encode(['success' => false, 'message' => 'Ad, soyad ve plaka alanları zorunludur.']);
    exit();
}

// Veritabanı işlemleri (Transaction ile)
$db->beginTransaction();

try {
    // 1. Plakanın zaten kayıtlı olup olmadığını kontrol et
    $stmt = $db->prepare("SELECT arac_id FROM araclar WHERE plaka = ?");
    $stmt->execute([$plaka]);
    if ($stmt->fetch()) {
        throw new Exception("Bu plaka zaten sisteme kayıtlı.");
    }

    // 2. Müşteriyi ekle
    $stmt = $db->prepare("INSERT INTO musteriler (ad, soyad, telefon, email, adres) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$ad, $soyad, $telefon, $email, $adres]);
    $musteri_id = $db->lastInsertId();

    // 3. Aracı ekle
    $stmt = $db->prepare("INSERT INTO araclar (musteri_id, plaka, marka, model, yil) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$musteri_id, $plaka, $marka, $model, $yil]);

    // Her şey yolundaysa işlemi onayla
    $db->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Müşteri ve araç başarıyla kaydedildi.',
        'plaka' => $plaka // JavaScript'in iş emri formunu doldurması için
    ]);

} catch (Exception $e) {
    // Bir hata olursa işlemi geri al
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage()]);
}
?>
