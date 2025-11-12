document.addEventListener('DOMContentLoaded', function() {

    const takipForm = document.getElementById('takipForm');
    const takipSonuc = document.getElementById('takipSonuc');

    takipForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const plaka = document.getElementById('takip_plaka').value.trim();
        if (!plaka) {
            renderSonuc({ success: false, message: 'Lütfen bir plaka giriniz.' });
            return;
        }

        // Önceki sonuçları temizle ve bekleme mesajı göster
        takipSonuc.innerHTML = '<p>Sonuçlar yükleniyor...</p>';

        fetch(`api/is_emri_sorgula.php?plaka=${encodeURIComponent(plaka)}`)
            .then(response => response.json())
            .then(data => {
                renderSonuc(data);
            })
            .catch(error => {
                console.error('Hata:', error);
                renderSonuc({ success: false, message: 'Sorgulama sırasında bir hata oluştu.' });
            });
    });

    function renderSonuc(response) {
        // Sonuç alanını temizle
        takipSonuc.innerHTML = '';

        if (!response.success) {
            takipSonuc.innerHTML = `<div class="error" style="display:block;">${response.message}</div>`;
            return;
        }

        const data = response.data;
        const ilkKayit = data[0]; // Müşteri ve araç bilgileri tüm kayıtlarda aynı olacak

        let html = `
            <h3>${ilkKayit.marka || ''} ${ilkKayit.model || ''} - ${ilkKayit.plaka}</h3>
            <p><strong>Müşteri:</strong> ${ilkKayit.ad} ${ilkKayit.soyad}</p>
            <hr>
            <h4>İş Emri Geçmişi</h4>
        `;

        if (ilkKayit.is_emri_id === null) {
            html += '<p>Bu araca ait hiç iş emri bulunmuyor.</p>';
        } else {
            html += '<table>';
            html += `
                <thead>
                    <tr>
                        <th>Durum</th>
                        <th>Açılış Tarihi</th>
                        <th>Yapılan İşlem</th>
                    </tr>
                </thead>
                <tbody>
            `;
            data.forEach(isEmri => {
                html += `
                    <tr>
                        <td>${isEmri.durum}</td>
                        <td>${isEmri.acilis_tarihi}</td>
                        <td>${isEmri.yapilacak_islem}</td>
                    </tr>
                `;
            });
            html += '</tbody></table>';
        }

        takipSonuc.innerHTML = html;
    }
});
