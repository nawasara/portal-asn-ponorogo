# deploy/

Berkas untuk menjalankan Portal ASN di produksi: PHP-FPM di belakang nginx,
aset dibangun saat image dibuat.

## Kenapa ada, padahal sudah ada docker-compose.yml di akar

`docker-compose.yml` di akar proyek adalah milik **Laravel Sail**, perkakas
pengembangan. Sampai 20 September 2026 produksi berjalan dengan berkas itu, dan
akibatnya nyata:

- Xdebug ikut terpasang.
- Vite dev server berjalan, dengan **port 5173 terbuka ke publik**.
- Seluruh direktori proyek di-bind ke dalam container, jadi kode yang dilayani
  adalah apa pun yang kebetulan ada di disk, bukan hasil build yang tercatat.

Berkas di direktori ini menggantikannya. Polanya meniru `deploy/` milik
`nandur-panguripan` yang sudah lebih dulu terbukti.

## Menjalankan

Dari akar proyek, bukan dari dalam `deploy/`:

```bash
docker compose --env-file .env -f deploy/docker-compose.yml build app
docker compose --env-file .env -f deploy/docker-compose.yml up -d
```

`--env-file .env` wajib disebut. Interpolasi variabel compose membaca `.env` di
direktori berkas compose (`deploy/`), bukan di akar, sehingga tanpa argumen itu
`APP_HOST_PORT` kosong dan port jatuh ke nilai bawaan.

## Yang perlu diketahui sebelum menyunting

**`DB_HOST` harus alamat VLAN, bukan `111.1.1.100`.** Dari dalam VLAN 10,
`111.1.1.100` menerima koneksi TCP tetapi tidak pernah mengirim banner MySQL,
sehingga setiap permintaan menggantung sampai worker PHP-FPM habis dan nginx
mencatat 499. Pakai `10.1.1.23`, yaitu basis data yang sama dilihat dari dalam
VLAN.

**`bootstrap/cache/*.php` diabaikan lewat `.dockerignore`.** Berkas itu dibuat
ketika paket dev masih terpasang, jadi memuat rujukan ke `PailServiceProvider`.
Membawanya ke image `--no-dev` membuat Laravel menolak boot. Yang menyesatkan:
`bootstrap/providers.php` sendiri bersih, jadi mencarinya di sana buntu.

**Migrasi tidak berjalan otomatis.** Entrypoint melewatinya kecuali
`RUN_MIGRATIONS=true`, supaya deploy tidak pernah mengubah skema produksi tanpa
disengaja. Untuk menjalankannya sekali:

```bash
docker compose -f deploy/docker-compose.yml exec app php artisan migrate --force
```

**Port hanya terbuka di loopback.** cloudflared di host yang meneruskan trafik
ke `127.0.0.1:8100`, jadi port itu tidak perlu terjangkau dari jaringan.
