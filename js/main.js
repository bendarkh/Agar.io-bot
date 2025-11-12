// Sayfa tamamen yüklendiğinde JavaScript kodlarının çalışmasını sağla
document.addEventListener('DOMContentLoaded', function() {

    const yeniIsEmriForm = document.getElementById('yeniIsEmriForm');
    const responseMessage = document.getElementById('response-message');
    const yeniKayitForm = document.getElementById('yeniKayitForm');
    const yeniKayitResponseMessage = document.getElementById('yeniKayitResponseMessage');
    const stokTablosuBody = document.querySelector('#stok-tablosu tbody');

    // 1. Yeni Müşteri ve Araç Kayıt Formunu Yönetme
    yeniKayitForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(yeniKayitForm);
        const data = Object.fromEntries(formData.entries());

        fetch('api/musteri_ve_arac_ekle.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            gosterMesaj(data.message, data.success ? 'success' : 'error', yeniKayitResponseMessage);
            if (data.success) {
                yeniKayitForm.reset();
                // İş emri formundaki plaka alanını doldur
                document.getElementById('plaka').value = data.plaka;
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            gosterMesaj('Bir hata oluştu. Lütfen konsolu kontrol edin.', 'error', yeniKayitResponseMessage);
        });
    });


    // 2. İş Emri Formunu Yönetme
    yeniIsEmriForm.addEventListener('submit', function(event) {
        // Formun varsayılan davranışını (sayfa yenileme) engelle
        event.preventDefault();

        const plaka = document.getElementById('plaka').value.trim();
        const yapilacakIslem = document.getElementById('yapilacak_islem').value.trim();

        // Basit bir doğrulama
        if (!plaka || !yapilacakIslem) {
            gosterMesaj('Lütfen tüm alanları doldurun.', 'error');
            return;
        }

        const formData = {
            plaka: plaka,
            yapilacak_islem: yapilacakIslem
        };

        // Fetch API kullanarak veriyi sunucuya gönder
        fetch('api/is_emri_olustur.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                gosterMesaj(data.message, 'success', responseMessage);
                yeniIsEmriForm.reset(); // Formu temizle
            } else {
                gosterMesaj(data.message, 'error', responseMessage);
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            gosterMesaj('Bir hata oluştu. Lütfen konsolu kontrol edin.', 'error', responseMessage);
        });
    });

    // 2. Stok Verilerini Çekme ve Görüntüleme
    function stokVerileriniYukle() {
        fetch('api/stok_getir.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tablo içeriğini temizle
                    stokTablosuBody.innerHTML = '';

                    // Veri yoksa bilgi mesajı göster
                    if(data.data.length === 0) {
                        const tr = document.createElement('tr');
                        const td = document.createElement('td');
                        td.setAttribute('colspan', 4);
                        td.textContent = 'Stokta hiç ürün bulunmuyor.';
                        td.style.textAlign = 'center';
                        tr.appendChild(td);
                        stokTablosuBody.appendChild(tr);
                        return;
                    }

                    // Her bir stok elemanı için tabloya yeni bir satır ekle
                    data.data.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td style="padding: 10px; border: 1px solid #ddd;">${item.parca_adi}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">${item.parca_kodu}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">${item.adet}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">${parseFloat(item.satis_fiyati).toFixed(2)} TL</td>
                        `;
                        stokTablosuBody.appendChild(tr);
                    });
                } else {
                    gosterMesaj(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Stok verileri çekilirken hata:', error);
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.setAttribute('colspan', 4);
                td.textContent = 'Stok verileri yüklenemedi.';
                td.style.textAlign = 'center';
                td.style.color = 'red';
                tr.appendChild(td);
                stokTablosuBody.appendChild(tr);
            });
    }

    // Yardımcı Mesaj Gösterme Fonksiyonu (Birden çok form için)
    function gosterMesaj(mesaj, tip, responseElement) {
        responseElement.textContent = mesaj;
        responseElement.className = tip; // 'success' veya 'error'
        responseElement.style.display = 'block';

        // Mesajı 5 saniye sonra gizle
        setTimeout(() => {
            responseElement.style.display = 'none';
        }, 5000);
    }

    // Sayfa yüklendiğinde stok verilerini otomatik olarak çek
    stokVerileriniYukle();
});
