🇬🇧 **English** · 🇮🇩 [Bahasa Indonesia](#bahasa-indonesia)

# Mikhmon for Windows: Local Mikhmon v3 Server for PC (RouterOS v6 and v7)

**Mikhmon for Windows** runs [Mikhmon v3 Community Edition](https://github.com/mikhmon-pro/mikhmon-v3) on your Windows PC. You don't need XAMPP or a separate PHP install. Extract the ZIP, double-click `MikhmonServer.cmd`, and open Mikhmon in your browser. It works with **RouterOS v6 and RouterOS v7**.

> ### ⚡ Prefer not to keep a PC running?
> **[Mikhmon Online at mikhmon.pro: just Rp10.000/month, tunnel included for up to 100 routers →](https://mikhmon.pro/fast?utm_source=github&utm_medium=readme&utm_campaign=mikhmon-windows)**

## Download

**[Download Mikhmon for Windows (ZIP) from mikhmon.pro](https://mikhmon.pro/download)**. PHP 8.5 and Mikhmon v3 3.21.2 are already inside, so it's ready to run.

**Needs:** 64-bit Windows 10 or 11. If PHP can't start, the menu links you to the [Microsoft Visual C++ Redistributable (x64)](https://aka.ms/vs/17/release/vc_redist.x64.exe), which most PCs already have.

## How to use

1. Extract the ZIP to a folder, for example `C:\Mikhmon`.
2. Double-click **`MikhmonServer.cmd`**.
3. Mikhmon opens in your default browser at http://127.0.0.1:8080. If port 8080 is busy, the server picks the next free one (8088, 8090, 8181 or 8888).
4. Log in with `mikhmon` / `1234` and add your router.

The menu shows the address for this PC and for other devices on the same network, and lets you open the browser, restart or stop the server, and read the server log. Closing the window keeps the server running in the background.

**Start without the menu:** run `MikhmonServer.cmd start` or `MikhmonServer.cmd stop`, for example from a desktop shortcut or Task Scheduler to start Mikhmon when Windows starts.

## What's inside

| Folder | Contents |
|---|---|
| `mikhmon/` | Mikhmon v3 Community Edition 3.21, with RouterOS v6 and v7 support ([changes](https://github.com/mikhmon-pro/mikhmon-v3#whats-new-in-v321-community-edition)) |
| `php/` | Portable PHP 8.5 (`php.zip`, extracted on first run to `m-server.exe`) |
| `MikhmonServer.cmd` | The start and stop menu |

---

## Bahasa Indonesia

🇬🇧 [English](#mikhmon-for-windows-local-mikhmon-v3-server-for-pc-routeros-v6-and-v7) · 🇮🇩 **Bahasa Indonesia**

**Mikhmon untuk Windows** menjalankan [Mikhmon v3 Community Edition](https://github.com/mikhmon-pro/mikhmon-v3) di PC Windows kamu. Tidak perlu XAMPP atau install PHP terpisah. Ekstrak ZIP-nya, klik dua kali `MikhmonServer.cmd`, lalu buka Mikhmon di browser. Sudah mendukung **RouterOS v6 dan RouterOS v7**.

> ### ⚡ Malas menyalakan PC terus?
> **[Mikhmon Online di mikhmon.pro: cuma Rp10.000/bulan, sudah termasuk tunnel untuk hingga 100 router →](https://mikhmon.pro/fast?utm_source=github&utm_medium=readme&utm_campaign=mikhmon-windows)**

### Download

**[Download Mikhmon untuk Windows (ZIP) di mikhmon.pro](https://mikhmon.pro/download)**. PHP 8.5 dan Mikhmon v3 3.21.2 sudah ada di dalamnya, jadi langsung bisa dijalankan.

**Butuh:** Windows 10 atau 11 64-bit. Kalau PHP tidak bisa jalan, menu akan mengarahkan kamu ke [Microsoft Visual C++ Redistributable (x64)](https://aka.ms/vs/17/release/vc_redist.x64.exe), yang biasanya sudah ada di kebanyakan PC.

### Cara pakai

1. Ekstrak ZIP ke sebuah folder, misalnya `C:\Mikhmon`.
2. Klik dua kali **`MikhmonServer.cmd`**.
3. Mikhmon terbuka di browser default di http://127.0.0.1:8080. Kalau port 8080 sedang dipakai, server memilih port kosong berikutnya (8088, 8090, 8181 atau 8888).
4. Login dengan `mikhmon` / `1234` lalu tambahkan router kamu.

Menu menampilkan alamat untuk PC ini dan untuk perangkat lain di jaringan yang sama, serta tombol untuk membuka browser, restart atau stop server, dan melihat log server. Menutup jendela tidak mematikan server, server tetap jalan di belakang.

**Jalankan tanpa menu:** pakai `MikhmonServer.cmd start` atau `MikhmonServer.cmd stop`, misalnya dari shortcut desktop atau Task Scheduler supaya Mikhmon otomatis jalan saat Windows menyala.

### Isi paket

| Folder | Isi |
|---|---|
| `mikhmon/` | Mikhmon v3 Community Edition 3.21, mendukung RouterOS v6 dan v7 ([perubahan](https://github.com/mikhmon-pro/mikhmon-v3#yang-baru-di-v321-community-edition)) |
| `php/` | PHP 8.5 portable (`php.zip`, diekstrak saat pertama kali jalan menjadi `m-server.exe`) |
| `MikhmonServer.cmd` | Menu untuk menyalakan dan mematikan server |

---

## Credits / Kredit

Mikhmon and Mikhmon Webserver were created by **Laksamadi Guko** ([laksa19](https://github.com/laksa19)). This is a fork of [laksa19/Mikhmon-Webserver](https://github.com/laksa19/Mikhmon-Webserver). Mikhmon is licensed under the GNU GPL v2.

Mikhmon dan Mikhmon Webserver dibuat oleh **Laksamadi Guko** ([laksa19](https://github.com/laksa19)). Ini adalah fork dari [laksa19/Mikhmon-Webserver](https://github.com/laksa19/Mikhmon-Webserver). Mikhmon berlisensi GNU GPL v2.

---

Maintained by [Mikhmon Pro](https://mikhmon.pro). **[Try Mikhmon Online: Rp10.000/month, tunnel included for up to 100 routers](https://mikhmon.pro/fast?utm_source=github&utm_medium=readme&utm_campaign=mikhmon-windows)**

Dikelola oleh [Mikhmon Pro](https://mikhmon.pro). **[Coba Mikhmon Online: Rp10.000/bulan, sudah termasuk tunnel untuk hingga 100 router](https://mikhmon.pro/fast?utm_source=github&utm_medium=readme&utm_campaign=mikhmon-windows)**
