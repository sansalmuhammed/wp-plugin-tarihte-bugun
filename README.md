# wp-plugin-tarihte-bugun

Bu WordPress eklentisi şunları sağlar:

Admin panelinde "Tarihte Bugün" menüsü
Her gün için birden fazla tarihi olay ekleyebilme
Tarih ve olay detaylarını veritabanında saklama
Otomatik olarak günün tarihine göre olayları gösterme
Responsive tasarım
Eklentiyi kullanmak için:

Dosyayı WordPress'in plugins klasörüne yükleyin
WordPress admin panelinden eklentiyi aktifleştirin
"Tarihte Bugün" menüsünden olayları ekleyin
Sayfanıza veya gönderinize [tarihte_bugun] kısa kodunu ekleyin
Eklenti otomatik olarak o günün tarihine ait olayları gösterecektir.

Kullanım:

Eklentiyi WordPress'e yükleyin
Admin panelinden olayları kategorileriyle birlikte ekleyin
[tarihte_bugun] kısa kodunu kullanarak sayfanızda gösterin
Kategorilere göre filtreleme yapabilir, tarih seçebilirsiniz

Dosya yapısını modüler hale getirdim:

includes/constants.php: Sabitler ve kategori tanımlamaları
assets/css/style.css: CSS stilleri ayrı dosyada
tarihte-bugun.php: Ana plugin dosyası
Olay kategorileri ekledim:

Doğum (👶)
Ölüm (⚰️)
Savaş (⚔️)
Barış (🕊️)
Keşif/İcat (🔬)
Spor (🏆)
Kültür/Sanat (🎭)
Siyasi (🏛️)
Doğa Olayı (🌍)
Genel (📅)
Yeni özellikler:

Her kategori için özel renkler ve stiller
Kategori filtreleme sistemi
Hover efektleri ve animasyonlar
Daha modern ve kullanıcı dostu arayüz
Responsive tasarım
Veritabanı yapısını güncelledim:

kategori sütunu eklendi
