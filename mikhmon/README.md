🇬🇧 **English** · 🇮🇩 [Bahasa Indonesia](#bahasa-indonesia)

# Mikhmon v3: MikroTik Hotspot Monitor for RouterOS v6 and v7

**Mikhmon v3 Community Edition** is the classic Mikhmon (MikroTik Hotspot Monitor) web app, updated to work on **RouterOS v6 and RouterOS v7**. You can generate and print hotspot vouchers, manage hotspot users and user profiles, and see sales reports, all from your browser. You don't need User Manager or RADIUS.

> ### ⚡ Prefer not to install anything?
> **[Mikhmon Online at mikhmon.pro: just Rp10.000/month, tunnel included for up to 100 routers →](https://mikhmon.pro/fast?utm_source=github&utm_medium=readme&utm_campaign=mikhmon-v3)**
>
> It's the same Mikhmon in your browser, with no PC or server to keep running.

## Download

| Package | For | Link |
|---|---|---|
| **Mikhmon v3 (ZIP)** | A PHP web server, hosting, STB/Armbian or Raspberry Pi | [Download from mikhmon.pro](https://mikhmon.pro/download) |
| **Mikhmon for Windows** | Running locally on a Windows PC (PHP included, double-click to start) | [Download from mikhmon.pro](https://mikhmon.pro/download) · [source](https://github.com/mikhmon-pro/mikhmon-windows) |

You can also use **Code → Download ZIP** on this page.

## What's new in v3.21 (Community Edition)

- **RouterOS v7 support.** RouterOS 7.10 and later print dates as `2026-11-04` instead of `nov/04/2026`. The old on-login scripts wrote broken expiry dates and sales records on RouterOS v7. The new scripts handle both formats.
- **Voucher expiry fixed on v6 and v7.** The expiry monitor reads both date formats, so expired users are removed or set to notice mode at the right time.
- **Sales reports keep working.** Sales records are always written in Mikhmon's original date format, so daily, monthly and resume reports stay correct after you upgrade RouterOS.
- **API parsing fix.** Values that contain `=` (scripts, comments) are no longer cut off.
- Tested on live RouterOS 6.49 and 7.23.

> After upgrading, open each **User Profile** in Mikhmon and click **Save** once. This writes the new on-login script to your router.

## Features

- Hotspot users: add, generate in bulk, edit, reset, remove and export (CSV or script)
- User profiles with validity, price, expiry mode (remove or notice, with or without sales record) and MAC lock
- Voucher printing with editable templates, QR codes and quick print
- Live sales report, plus daily and monthly selling reports and a resume
- Dashboard with CPU, memory and traffic, active users, hosts, IP bindings, DHCP leases and logs
- Multiple routers (sessions), themes and languages (ID, EN, ES, TL, TR)

## Install

**On a PHP server (Linux, hosting, STB, Raspberry Pi):** extract the ZIP into your web root and open it in a browser. PHP 7.x or 8.x with the `sockets` extension is enough.

**With Docker:**

```bash
docker run --rm -p 8080:80 -v "$PWD":/var/www/html php:8.2-apache
```

Then open http://localhost:8080.

**On Windows:** use [Mikhmon for Windows](https://mikhmon.pro/download). Extract it and run `MikhmonServer.cmd`.

The default login is `mikhmon` / `1234`. Change it in **Settings → Admin** after your first login.

---

## Bahasa Indonesia

🇬🇧 [English](#mikhmon-v3-mikrotik-hotspot-monitor-for-routeros-v6-and-v7) · 🇮🇩 **Bahasa Indonesia**

**Mikhmon v3 Community Edition** adalah Mikhmon (MikroTik Hotspot Monitor) versi klasik yang diperbarui agar jalan di **RouterOS v6 dan RouterOS v7**. Kamu bisa membuat dan mencetak voucher hotspot, mengelola user dan user profile hotspot, serta melihat laporan penjualan, semuanya dari browser. Tidak perlu User Manager atau RADIUS.

> ### ⚡ Malas install?
> **[Mikhmon Online di mikhmon.pro: cuma Rp10.000/bulan, sudah termasuk tunnel untuk hingga 100 router →](https://mikhmon.pro/fast?utm_source=github&utm_medium=readme&utm_campaign=mikhmon-v3)**
>
> Mikhmon yang sama di browser, tanpa PC atau server yang harus terus menyala.

### Download

| Paket | Untuk | Link |
|---|---|---|
| **Mikhmon v3 (ZIP)** | Web server PHP, hosting, STB/Armbian atau Raspberry Pi | [Download di mikhmon.pro](https://mikhmon.pro/download) |
| **Mikhmon untuk Windows** | Dijalankan langsung di PC Windows (PHP sudah termasuk, cukup klik dua kali) | [Download di mikhmon.pro](https://mikhmon.pro/download) · [source](https://github.com/mikhmon-pro/mikhmon-windows) |

Kamu juga bisa memakai **Code → Download ZIP** di halaman ini.

### Yang baru di v3.21 (Community Edition)

- **Mendukung RouterOS v7.** RouterOS 7.10 ke atas menulis tanggal sebagai `2026-11-04`, bukan `nov/04/2026`. Script on-login lama menulis tanggal kedaluwarsa dan catatan penjualan yang rusak di RouterOS v7. Script baru memahami kedua format.
- **Masa aktif voucher diperbaiki di v6 dan v7.** Monitor kedaluwarsa membaca kedua format tanggal, jadi user yang habis masa aktifnya dihapus atau masuk mode notice tepat waktu.
- **Laporan penjualan tetap jalan.** Catatan penjualan selalu ditulis dalam format tanggal asli Mikhmon, jadi laporan harian, bulanan dan resume tetap benar setelah RouterOS di-upgrade.
- **Perbaikan parsing API.** Nilai yang mengandung `=` (script, comment) tidak lagi terpotong.
- Sudah diuji langsung di RouterOS 6.49 dan 7.23.

> Setelah update, buka setiap **User Profile** di Mikhmon lalu klik **Save** satu kali. Ini akan menulis script on-login yang baru ke router kamu.

### Fitur

- User hotspot: tambah, generate massal, edit, reset, hapus dan export (CSV atau script)
- User profile dengan masa aktif, harga, mode kedaluwarsa (remove atau notice, dengan atau tanpa catatan penjualan) dan MAC lock
- Cetak voucher dengan template yang bisa diedit, QR code dan quick print
- Laporan penjualan live, plus laporan penjualan harian dan bulanan serta resume
- Dashboard dengan CPU, memori dan traffic, user aktif, host, IP binding, DHCP lease dan log
- Banyak router (session), tema dan bahasa (ID, EN, ES, TL, TR)

### Instalasi

**Di server PHP (Linux, hosting, STB, Raspberry Pi):** ekstrak ZIP ke web root lalu buka di browser. Cukup PHP 7.x atau 8.x dengan ekstensi `sockets`.

**Dengan Docker:**

```bash
docker run --rm -p 8080:80 -v "$PWD":/var/www/html php:8.2-apache
```

Lalu buka http://localhost:8080.

**Di Windows:** pakai [Mikhmon untuk Windows](https://mikhmon.pro/download). Ekstrak lalu jalankan `MikhmonServer.cmd`.

Login default: `mikhmon` / `1234`. Ganti di **Settings → Admin** setelah login pertama.

---

## Credits and license / Kredit dan lisensi

Mikhmon was created by **Laksamadi Guko** ([laksa19](https://github.com/laksa19)). This Community Edition is a fork of [laksa19/mikhmonv3](https://github.com/laksa19/mikhmonv3) and keeps the original author's credits. The original changelog is in [CHANGELOG-ORIGINAL.md](CHANGELOG-ORIGINAL.md).

The RouterOS API class is based on [BenMenking/routeros-api](https://github.com/BenMenking/routeros-api).

Licensed under the [GNU GPL v2](LICENSE).

Mikhmon dibuat oleh **Laksamadi Guko** ([laksa19](https://github.com/laksa19)). Community Edition ini adalah fork dari [laksa19/mikhmonv3](https://github.com/laksa19/mikhmonv3) dan tetap mencantumkan kredit pembuat aslinya. Changelog asli ada di [CHANGELOG-ORIGINAL.md](CHANGELOG-ORIGINAL.md). Class RouterOS API berbasis [BenMenking/routeros-api](https://github.com/BenMenking/routeros-api). Berlisensi [GNU GPL v2](LICENSE).

---

Maintained by [Mikhmon Pro](https://mikhmon.pro). **[Try Mikhmon Online: Rp10.000/month, tunnel included for up to 100 routers](https://mikhmon.pro/fast?utm_source=github&utm_medium=readme&utm_campaign=mikhmon-v3)**

Dikelola oleh [Mikhmon Pro](https://mikhmon.pro). **[Coba Mikhmon Online: Rp10.000/bulan, sudah termasuk tunnel untuk hingga 100 router](https://mikhmon.pro/fast?utm_source=github&utm_medium=readme&utm_campaign=mikhmon-v3)**
