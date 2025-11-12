-- Müşteri Bilgileri
CREATE TABLE musteriler (
    musteri_id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(100) NOT NULL,
    soyad VARCHAR(100) NOT NULL,
    telefon VARCHAR(15) UNIQUE,
    email VARCHAR(100) UNIQUE,
    adres TEXT,
    kayit_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Motosiklet Bilgileri
CREATE TABLE araclar (
    arac_id INT AUTO_INCREMENT PRIMARY KEY,
    musteri_id INT NOT NULL,
    plaka VARCHAR(20) NOT NULL UNIQUE,
    marka VARCHAR(50),
    model VARCHAR(50),
    yil INT,
    FOREIGN KEY (musteri_id) REFERENCES musteriler(musteri_id)
);

-- Stok (Yedek Parça) Bilgileri
CREATE TABLE stok (
    parca_id INT AUTO_INCREMENT PRIMARY KEY,
    parca_adi VARCHAR(255) NOT NULL,
    parca_kodu VARCHAR(100) UNIQUE,
    aciklama TEXT,
    adet INT NOT NULL DEFAULT 0,
    alis_fiyati DECIMAL(10, 2),
    satis_fiyati DECIMAL(10, 2),
    guncelleme_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- İş Emri Bilgileri
CREATE TABLE is_emirleri (
    is_emri_id INT AUTO_INCREMENT PRIMARY KEY,
    arac_id INT NOT NULL,
    acilis_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    kapanis_tarihi TIMESTAMP NULL,
    yapilacak_islem TEXT NOT NULL,
    durum VARCHAR(50) DEFAULT 'Açık', -- Örn: Açık, Devam Ediyor, Tamamlandı, İptal
    toplam_tutar DECIMAL(10, 2) DEFAULT 0.00,
    FOREIGN KEY (arac_id) REFERENCES araclar(arac_id)
);

-- İş Emrinde Kullanılan Parçalar
CREATE TABLE is_emri_detaylari (
    detay_id INT AUTO_INCREMENT PRIMARY KEY,
    is_emri_id INT NOT NULL,
    parca_id INT NOT NULL,
    kullanilan_adet INT NOT NULL,
    birim_fiyat DECIMAL(10, 2), -- O anki satış fiyatı
    FOREIGN KEY (is_emri_id) REFERENCES is_emirleri(is_emri_id),
    FOREIGN KEY (parca_id) REFERENCES stok(parca_id)
);

-- Yıllık Planlama (Basit bir örnek)
CREATE TABLE yillik_planlama (
    plan_id INT AUTO_INCREMENT PRIMARY KEY,
    yil INT NOT NULL,
    ay INT NOT NULL,
    hedef_ciro DECIMAL(15, 2),
    gerceklesen_ciro DECIMAL(15, 2) DEFAULT 0.00,
    aciklama TEXT
);

-- Örnek Veri Ekleme (Opsiyonel, test için)
INSERT INTO musteriler (ad, soyad, telefon) VALUES ('Ahmet', 'Yılmaz', '5551234567');
INSERT INTO araclar (musteri_id, plaka, marka, model) VALUES (1, '34 ABC 123', 'Honda', 'PCX');
INSERT INTO stok (parca_adi, parca_kodu, adet, satis_fiyati) VALUES ('Fren Balatası Ön', 'HON-PCX-FRN-01', 10, 250.00);
