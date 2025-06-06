# KotorBus – Laravel Baza Podataka i Seedovanje

Ovaj projekat koristi **Laravel migracije** i **seedere** za upravljanje strukturom baze i početnim podacima, kao i posebne SQL fajlove za procedure i funkcije.

---

## 📦 Struktura foldera

```
database/
├── migrations/         # Laravel migracije – definicija svih tabela
├── seeders/            # Laravel seederi – početni podaci za tabele
├── sql/
    ├── procedures.sql  # MySQL stored procedure (procedura)
```

---

## 🛠️ Kako koristiti ovaj projekat

### 1. Instalacija zavisnosti

Pokreni u root folderu:
```bash
composer install
```

### 2. Konfiguriši .env za pristup bazi

Postavi podatke za MySQL u `.env` fajlu:
```
DB_DATABASE=ime_baze
DB_USERNAME=korisnik
DB_PASSWORD=lozinka
```

### 3. Migracije – Kreiranje/podešavanje tabela

Pokreni:
```bash
php artisan migrate
```

### 4. Seedovanje – Ubacivanje početnih podataka

Pokreni:
```bash
php artisan db:seed
```

### 5. Ubacivanje procedura i funkcija

Pokreni SQL fajlove ručno ili automatski:
- Ručno (npr. iz phpMyAdmin-a ili MySQL konzole):
  - `database/sql/procedures.sql`
- **Opciono:** Dodaj u migraciju ako želiš automatski unos:
    ```php
    // Primer za migraciju
    DB::unprepared(file_get_contents(database_path('sql/procedures.sql')));
    ```
---

## 🔄 Preporuke

- **U budućnosti koristi isključivo Laravel migracije i seedere** za sve što se tiče strukture i inicijalnih podataka.
- **SQL fajlove sa procedurama/funkcijama drži u `database/sql/`** i redovno ažuriraj.

---

