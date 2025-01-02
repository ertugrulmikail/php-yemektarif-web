<?php

abstract class admins
{
    const id = 'int';
    const username = 'string';
    const password = 'string';
}

abstract class users
{
    const id = 'int';
    const ad = 'string';
    const soyad = 'string';
    const email = 'string';
    const kullanici_adi = 'string';
    const sifre = 'string';
    const profil_resmi = 'image';
}

abstract class defterler
{
    const id = 'int';
    const tarif_id = 'int';
    const kullanici_id = 'int';
}

abstract class kategoriler
{
    const id = 'int';
    const kategori_adi = 'string';
}

abstract class tarifler
{
    const id = 'int';
    const kullanici_id = 'int';
    const kategori_id = 'int';
    const tarif_adi = 'string';
    const malzemeler = 'string';
    const hazirlanisi = 'string';
    const kisi_sayisi = 'string';
    const hazirlanma_suresi = 'string';
    const pisirme_suresi = 'string';
    const tarif_gorseli = 'string';
    const durum = 'string';
    const revize_sebebi = 'string';
    const olusturulma_tarihi = 'date';
}

abstract class yorumlar
{
    const id = 'int';
    const tarif_id = 'int';
    const kullanici_id = 'int';
    const yorum_metni = 'string'; // veya 'text'
    const olusturulma_tarihi = 'timestamp';
}

function getClassByProcessId($processId)
{
    switch ($processId) {
        case 1:
            return 'admins';
        case 2:
            return 'users';
        case 3:
            return 'kategoriler';
        case 4:
            return 'tarifler';
        case 5:
            return 'yorumlar';
        case 6:
            return 'tarifler';
        default:
            return null;
    }
}
