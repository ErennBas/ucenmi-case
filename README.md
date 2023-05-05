# Ucenmi Case

Projeyi, istenilen hususlara göre bitirdim fakat maalesef yeteneklerimi sergileyecek ölçüde yetiştiremedim. 2 yıldır PHP den uzak kalmanın dezavantajları diyelim. Bundan kaynaklı bazı kısımları yapmadan geçmek zorunda kaldım. Bu kısımlardan bazıları; oop, validasyonlar. Ayrıca blog resmi için file upload ile uğraşmak istemedim çünkü çok geç kaldığımı hissettim dolayısıyla veritabanına statik olarak bir görsel belirledim.

Maalesef ki yetenekerimi bu case' de sergileyemediğimi düşünüyorum.

## Installation for Frontend

Use the package manager npm

```bash
cd frontend
npm i
```
Serve on Quasar

```bash
quasar dev
```

## Installation for Backend

```bash
cd backend
```

Create Database ucenmi

```bash
CREATE DATABASE ucenmi;
mysql -u <username> -p ucenmi < ucenmi.sql
```

Edit database configurations on "backend\database\config.php" file.


```bash
composer install
php -S localhost:3100
```
