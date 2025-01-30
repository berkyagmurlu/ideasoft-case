# IdeaSoft Case Study - E-commerce API

## Özellikler

- JWT tabanlı kimlik doğrulama
- Kategori yönetimi
- Ürün yönetimi
- Sipariş işlemleri
- Gelişmiş indirim sistemi:
  - Toplam tutar indirimi
  - Kategori bazlı çoklu ürün indirimi
  - Kategori bazlı miktar indirimi
  - VIP müşteri indirimi (gelir bazlı)
  - Sadık müşteri indirimi (üyelik süresi bazlı)
- Swagger API dokümantasyonu
- Docker tabanlı geliştirme ortamı

## Teknolojiler

- PHP 8.3
- Laravel 11
- MySQL 8.0
- Docker & Docker Compose
- JWT Authentication
- Swagger/OpenAPI
- PHPUnit

## Kurulum

### Gereksinimler

- Docker ve Docker Compose
- Git

### Adımlar

1. Docker containerlarını başlatın:
```bash
docker-compose up -d --build
```

2. Bağımlılıkları yükleyin:
```bash
docker compose exec app composer install
```

3. Veritabanını oluşturun:
```bash
docker compose exec app php artisan migrate --seed
```

4. Swagger dökümanını generate edelim:
```bash
docker compose exec app php artisan l5-swagger:generate
```

## API Dokümantasyonu

Swagger API dokümantasyonuna aşağıdaki URL'den erişebilirsiniz:
```
http://localhost/api/documentation
```

### Postman Collection

API'yi test etmek için Postman collection'ını kullanabilirsiniz. Collection dosyası projenin kök dizininde `IdeaSoft-Case.postman_collection.json` adıyla bulunmaktadır.

Collection'ı kullanmak için:
1. Postman'i açın
2. "Import" butonuna tıklayın
3. `IdeaSoft-Case.postman_collection.json` dosyasını seçin
4. Collection içindeki environment variables'ları güncelleyin:
   - `base_url`: API'nin çalıştığı URL (varsayılan: http://localhost)
   - `token`: Login endpoint'inden aldığınız JWT token

### Test Kullanıcıları

1. Admin Kullanıcı:
   - Email: admin@example.com
   - Şifre: password

2. Test Kullanıcı:
   - Email: test@example.com
   - Şifre: password

## İndirim Sistemi

### 1. Toplam Tutar İndirimi
- 1000$ üzeri alışverişlerde %10 indirim

### 2. Kategori Bazlı Miktar İndirimleri
- Kitaplarda: 2 Al 1 Bedava
- Giyimde: 3 Al 1 Bedava

### 3. Kategori Bazlı Çoklu İndirimler
- Elektronik ürünlerde %15 indirim
- Spor ekipmanlarında %20 indirim

### 4. VIP Müşteri İndirimi
- Toplam geliri 10,000$ ve üzeri olan müşteriler
- Tüm siparişlerde %25 indirim

### 5. Sadık Müşteri İndirimi
- 12 ay ve üzeri üyeliği olan müşteriler
- Tüm siparişlerde %15 indirim

## Proje Yapısı

### Katmanlı Mimari
- Controllers: API endpoint'lerinin yönetimi
- Services: İş mantığının uygulanması
- Repositories: Veritabanı işlemleri
- Models: Veritabanı modelleri
- Requests: Form validasyonları
- Interfaces: Servis ve repository arayüzleri

### Veritabanı Şeması
- users: Kullanıcı bilgileri
- categories: Ürün kategorileri
- products: Ürünler
- orders: Siparişler
- order_items: Sipariş detayları
- discounts: İndirim kuralları

## API Endpoint'leri

### Kimlik Doğrulama
- POST /api/v1/auth/register
- POST /api/v1/auth/login
- POST /api/v1/auth/logout
- GET /api/v1/auth/me

### Kategoriler
- GET /api/v1/categories
- POST /api/v1/categories
- GET /api/v1/categories/{id}
- PUT /api/v1/categories/{id}
- DELETE /api/v1/categories/{id}

### Ürünler
- GET /api/v1/products
- POST /api/v1/products
- GET /api/v1/products/{id}
- PUT /api/v1/products/{id}
- DELETE /api/v1/products/{id}
- GET /api/v1/categories/{category}/products

### Siparişler
- GET /api/v1/orders
- POST /api/v1/orders
- GET /api/v1/orders/{id}
- PATCH /api/v1/orders/{id}/status

### İndirimler
- GET /api/v1/discounts
- POST /api/v1/discounts
- GET /api/v1/discounts/{id}
- PUT /api/v1/discounts/{id}
- DELETE /api/v1/discounts/{id}
- GET /api/v1/orders/{order}/discounts