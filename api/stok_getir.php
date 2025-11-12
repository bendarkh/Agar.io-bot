<?php
header('Content-Type: application/json');

// Veritabanı bağlantısını dahil et
require_once 'db_config.php';

try {
    // Stoktaki tüm parçaları getir
    $stmt = $db->query("SELECT parca_id, parca_adi, parca_kodu, adet, satis_fiyati FROM stok ORDER BY parca_adi ASC");
    $stok_listesi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $stok_listesi]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?>
