<?php
header('Content-Type: application/json');
require_once 'db_config.php';

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

try {
    switch ($action) {
        case 'ekle':
            // Yeni stok ekleme
            $stmt = $db->prepare("INSERT INTO stok (parca_adi, parca_kodu, adet, alis_fiyati, satis_fiyati) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['parca_adi'],
                $data['parca_kodu'] ?? null,
                $data['adet'],
                $data['alis_fiyati'] ?? null,
                $data['satis_fiyati']
            ]);
            echo json_encode(['success' => true, 'message' => 'Stok başarıyla eklendi.']);
            break;

        case 'guncelle_adet':
            // Stok adedini artırma/azaltma
            $parca_id = $data['parca_id'];
            $yeni_adet = $data['yeni_adet'];
            if ($yeni_adet < 0) $yeni_adet = 0; // Adet eksiye düşmesin

            $stmt = $db->prepare("UPDATE stok SET adet = ? WHERE parca_id = ?");
            $stmt->execute([$yeni_adet, $parca_id]);
            echo json_encode(['success' => true, 'message' => 'Stok adedi güncellendi.']);
            break;

        case 'sil':
            // Stoktan ürünü silme
            $parca_id = $data['parca_id'];
            // Önce bu parçayı kullanan iş emri detayı var mı diye kontrol edilebilir (Foreign Key kısıtlaması).
            // Şimdilik doğrudan silelim.
            $stmt = $db->prepare("DELETE FROM stok WHERE parca_id = ?");
            $stmt->execute([$parca_id]);
            echo json_encode(['success' => true, 'message' => 'Stok kalemi silindi.']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Geçersiz işlem.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?>
