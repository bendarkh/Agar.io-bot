<?php include 'templates/header.php'; ?>

<h1>Admin Paneli</h1>

<!-- Yeni Müşteri ve Araç Ekleme Formu (Bir sonraki adımda eklenecek) -->
<div class="form-section" id="yeni-kayit-formu">
    <h2>Yeni Müşteri ve Araç Kaydı</h2>
    <p>Sisteme ilk kez gelen müşteri ve aracı buradan kaydedin. Kayıt sonrası bu araca otomatik olarak iş emri açılabilir.</p>
    <form id="yeniKayitForm">
        <div style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <h3>Müşteri Bilgileri</h3>
                <div class="form-group">
                    <label for="ad">Ad</label>
                    <input type="text" id="ad" name="ad" required>
                </div>
                <div class="form-group">
                    <label for="soyad">Soyad</label>
                    <input type="text" id="soyad" name="soyad" required>
                </div>
                <div class="form-group">
                    <label for="telefon">Telefon</label>
                    <input type="tel" id="telefon" name="telefon">
                </div>
                <div class="form-group">
                    <label for="adres">Adres</label>
                    <textarea id="adres" name="adres"></textarea>
                </div>
            </div>
            <div style="flex: 1;">
                <h3>Araç Bilgileri</h3>
                <div class="form-group">
                    <label for="yeni_plaka">Plaka</label>
                    <input type="text" id="yeni_plaka" name="plaka" required>
                </div>
                <div class="form-group">
                    <label for="marka">Marka</label>
                    <input type="text" id="marka" name="marka">
                </div>
                <div class="form-group">
                    <label for="model">Model</label>
                    <input type="text" id="model" name="model">
                </div>
                <div class="form-group">
                    <label for="yil">Yıl</label>
                    <input type="number" id="yil" name="yil" min="1988" max="<?php echo date('Y'); ?>">
                </div>
            </div>
        </div>
        <button type="submit">Müşteri ve Aracı Kaydet</button>
    </form>
    <div id="yeniKayitResponseMessage"></div>
</div>

<!-- Mevcut İş Emri Açma Formu -->
<div class="form-section" id="is-emri-formu">
    <h2>Yeni İş Emri Aç</h2>
    <p>Sisteme daha önce kaydedilmiş bir araç için yeni iş emri açın.</p>
    <form id="yeniIsEmriForm">
        <div class="form-group">
            <label for="plaka">Araç Plakası</label>
            <input type="text" id="plaka" name="plaka" placeholder="Örn: 34 ABC 123" required>
        </div>
        <div class="form-group">
            <label for="yapilacak_islem">Yapılacak İşlem Açıklaması</label>
            <textarea id="yapilacak_islem" name="yapilacak_islem" placeholder="Yapılacak işlemleri detaylıca yazın..." required></textarea>
        </div>
        <button type="submit">İş Emri Oluştur</button>
    </form>
    <div id="response-message"></div>
</div>

<!-- Stok Yönetimi Alanı -->
<div class="form-section" id="stok-yonetimi">
    <h2>Stok Yönetimi</h2>
    <form id="yeniStokForm">
        <div style="display: flex; gap: 15px; align-items: flex-end;">
            <div class="form-group" style="flex: 3;">
                <label for="parca_adi">Parça Adı</label>
                <input type="text" id="parca_adi" name="parca_adi" required>
            </div>
            <div class="form-group" style="flex: 2;">
                <label for="parca_kodu">Parça Kodu</label>
                <input type="text" id="parca_kodu" name="parca_kodu">
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="adet">Adet</label>
                <input type="number" id="adet" name="adet" value="1" min="0" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="alis_fiyati">Alış Fiyatı (₺)</label>
                <input type="number" id="alis_fiyati" name="alis_fiyati" step="0.01" min="0">
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="satis_fiyati">Satış Fiyatı (₺)</label>
                <input type="number" id="satis_fiyati" name="satis_fiyati" step="0.01" min="0" required>
            </div>
            <button type="submit" style="flex: 1; height: 40px;">Stok Ekle</button>
        </div>
    </form>
    <div id="stokResponseMessage" style="margin-top: 15px;"></div>
</div>

<!-- Stok Görüntüleme Alanı -->
<div class="form-section" id="stok-goruntuleme">
    <h2>Stok Durumu</h2>
    <table id="stok-tablosu">
        <thead>
            <tr>
                <th>Parça Adı</th>
                <th>Parça Kodu</th>
                <th>Adet</th>
                <th>Satış Fiyatı</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <!-- Stok verileri buraya Ajax ile yüklenecek -->
        </tbody>
    </table>
</div>

<?php include 'templates/footer.php'; ?>
