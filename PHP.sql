use demob;

select * from demob.users;
select * from demob.dogrulama_kodlari;
select * from demob.tarifler;
select * from demob.kategoriler;
select * from demob.defterler;
select * from demob.yorumlar;



create table users(
 id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
 ad varchar(15) not null,
 soyad varchar(15) not null,
 email varchar(25) not null,
 kullanici_adi varchar(25) not null,
 sifre varchar(25) not null,
 profil_resmi VARCHAR(255)
);

CREATE TABLE dogrulama_kodlari (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    dogrulama_kodu INT NOT NULL,
    olusturma_zamani TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tarifler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id INT NOT NULL,
    kategori_id INT NOT NULL,
    tarif_adi VARCHAR(255) NOT NULL,
    malzemeler TEXT NOT NULL,
    hazirlanisi TEXT NOT NULL,
    kisi_sayisi VARCHAR(10) NOT NULL,
    hazirlanma_suresi VARCHAR(10) NOT NULL,
    pisirme_suresi VARCHAR(10) NOT NULL,
    tarif_gorseli VARCHAR(255) NOT NULL,
    durum VARCHAR(50) DEFAULT 'Beklemede',
    revize_sebebi TEXT,
    olusturulma_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE kategoriler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_adi VARCHAR(50) NOT NULL
);

CREATE TABLE defterler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tarif_id INT NOT NULL,
    kullanici_id INT NOT NULL
);

CREATE TABLE yorumlar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tarif_id INT NOT NULL,
    kullanici_id INT NOT NULL,
    yorum_metni TEXT NOT NULL,
    olusturulma_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



INSERT INTO kategoriler (kategori_adi) VALUES
('Çorbalar'),
('Mezeler ve Atıştırmalıklar'),
('Ana Yemekler'),
('Et Yemekleri'),
('Tavuk Yemekleri'),
('Balık ve Deniz Ürünleri'),
('Sebze Yemekleri'),
('Baklagil Yemekleri'),
('Pilav ve Makarna'),
('Risotto'),
('Salatalar'),
('Tahıllı Salatalar'),
('Proteinli Salatalar'),
('Meyveli Salatalar'),
('Hamur İşleri'),
('Börekler'),
('Poğaçalar'),
('Ekmekler'),
('Pide ve Gözleme'),
('Tatlılar'),
('Şerbetli Tatlılar'),
('Sütlü Tatlılar'),
('Kek ve Pastalar'),
('Kurabiyeler'),
('Dondurma ve Soğuk Tatlılar'),
('Kahvaltılıklar'),
('Omletler'),
('Kahvaltı Tabağı'),
('İçecekler'),
('Soğuk İçecekler'),
('Sıcak İçecekler'),
('Kokteyller'),
('Alkolsüz Kokteyller'),
('Diyet ve Sağlıklı Tarifler'),
('Vejetaryen ve Vegan Tarifler'),
('Glutensiz Tarifler'),
('Düşük Karbonhidratlı Tarifler');