# Domain Expiry Tracker (DET)

[Türkçe](#türkçe) | [English](#english)

---

## English

**Domain Expiry Tracker (DET)** is a lightweight PHP-based web application designed to help you track the expiration dates of your domains and receive early warnings before they drop.

### 🌟 Features

- **Auto WHOIS Parsing:** Automatically fetches expiration dates for various TLDs (.com, .net, .org, .tr, .de, etc.).
- **Drop Date Calculation:** Calculates the estimated drop date (65 days after expiry).
- **Email Notifications:** Integrated Cron Job sends email alerts for domains dropping soon.
- **Secure Authentication:** Modern password hashing using `password_hash()`.
- **Responsive Dashboard:** Built with AdminLTE 3 for a smooth user experience.
- **Easy Installer:** Quick setup wizard for database and SMTP configuration.

### 🚀 Installation

1. Upload the files to your PHP server.
2. Navigate to `installer.php` in your browser.
3. Follow the steps to configure your database and SMTP settings.
4. Set up a Cron Job for `cronJob.php` to run daily.

### 🔒 Security & Requirements

- **PHP 7.4+** recommended.
- **MySQL** database.
- **PHPMailer** for notifications.

---

## Türkçe

**Domain Expiry Tracker (DET)**, alan adlarınızın sona erme tarihlerini takip etmenize ve düşmeden önce erken uyarılar almanıza yardımcı olan hafif, PHP tabanlı bir web uygulamasıdır.

### 🌟 Özellikler

- **Otomatik WHOIS Ayrıştırma:** Çeşitli uzantılar (.com, .net, .org, .tr, .de vb.) için bitiş tarihlerini otomatik çeker.
- **Düşme Tarihi Hesaplama:** Bitiş tarihinden 65 gün sonrasını tahmini düşme tarihi olarak hesaplar.
- **E-posta Bildirimleri:** Entegre Cron Job, düşmek üzere olan domainler için e-posta uyarıları gönderir.
- **Güvenli Kimlik Doğrulama:** `password_hash()` kullanılarak modern şifreleme sağlanmıştır.
- **Duyarlı Panel:** Akıcı bir kullanıcı deneyimi için AdminLTE 3 ile oluşturulmuştur.
- **Kolay Kurulum:** Veritabanı ve SMTP ayarları için hızlı kurulum sihirbazı mevcuttur.

### 🚀 Kurulum

1. Dosyaları PHP sunucunuza yükleyin.
2. Tarayıcınızda `installer.php` sayfasını açın.
3. Veritabanı ve SMTP ayarlarınızı yapılandırmak için adımları izleyin.
4. `cronJob.php` dosyasını günlük olarak çalıştıracak bir Cron Job ayarlayın.

### 🔒 Güvenlik ve Gereksinimler

- **PHP 7.4+** önerilir.
- **MySQL** veritabanı.
- **PHPMailer** bildirimler için kullanılır.

---

_Created by [Hackonomist](https://github.com/onuraksoy)_
