<?php include 'templates/header.php'; ?>

<h1>İş Emri Durum Sorgulama</h1>
<p>Aracınızın plakasını girerek servis durumunu kontrol edebilirsiniz.</p>

<div class="form-section">
    <form id="takipForm">
        <div class="form-group">
            <label for="takip_plaka">Araç Plakası</label>
            <input type="text" id="takip_plaka" name="plaka" placeholder="Plakanızı bitişik yazınız" required>
        </div>
        <button type="submit">Sorgula</button>
    </form>
</div>

<div id="takipSonuc">
    <!-- Sorgu sonuçları buraya Ajax ile yüklenecek -->
</div>

<?php include 'templates/footer.php'; ?>
