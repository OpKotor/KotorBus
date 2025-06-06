# Laravel Backend Projekat

## O projektu

Ovo je backend aplikacije izgrađen korišćenjem Laravel Framework-a. 
Projekt služi kao glavni dio aplikacije, obrađuje API endpointove, operacije s bazom podataka, autentikaciju i još mnogo toga.

## Funkcionalnosti

- Sigurna autentikacija koristeći Laravel Sanctum.
- Kontrola pristupa bazirana na ulogama uz pomoć middleware-a.
- RESTful API za upravljanje resursima.
- Upravljanje redovima zadataka sa podrškom za Redis.
- Optimizacija performansi i keširanje.

## Primer API Endpointa
- `GET /api/admins` - Lista svih admin korisnika.
- `POST /api/admins` - Kreira novog admina. (Zahtijeva autentifikaciju)
- `PUT /api/admins/{id}` - Ažurira admina na osnovu ID-a.
- `DELETE /api/admins/{id}` - Briše admina.

## ER Dijagram Baze Podataka
![ER Dijagram](link_do_slike.jpg)

## Preduslovi

Prije pokretanja ovog projekta, osigurajte da imate sljedeće instalirano:

- PHP verzija 8.2 ili novija
- Composer
- MySQL ili druga podržana baza podataka
- Node.js i npm
- Redis (opciono, za redove zadataka i keširanje)

## Instalacija

Slijedite ove korake za postavljanje projekta lokalno:

1. Klonirajte repozitorijum:
   ```bash
   git clone https://github.com/GoranOpK/backend.git
   cd backend
   ```

2. Instalirajte PHP zavisnosti:
   ```bash
   composer install
   ```

3. Instalirajte Node.js zavisnosti:
   ```bash
   npm install
   ```

4. Postavite `.env` fajl:
   ```bash
   cp .env.example .env
   ```

5. Generišite ključeve aplikacije:
   ```bash
   php artisan key:generate
   ```

6. Postavite bazu podataka:
   - Konfigurišite `.env` fajl sa podacima vaše baze podataka.
   - Pokrenite migracije:
     ```bash
     php artisan migrate
     ```

7. (Opcionalno) Postavite red zadataka:
   - Konfigurišite `.env` fajl za korišćenje `redis` ili `database` kao driver-a za redove zadataka.

## Pokretanje aplikacije

1. Pokrenite razvojni server:
   ```bash
   php artisan serve
   ```

2. Pokrenite radnik za redove zadataka:
   ```bash
   php artisan queue:work
   ```

3. (Opcionalno) Izgradite frontend resurse pomoću Vite-a (ako je potrebno):
   ```bash
   npm run dev
   ```

## Testiranje

Pokrenite testove da osigurate da sve funkcioniše kako treba:
```bash
php artisan test
```

## FAQ
**P: Da li je Redis obavezan za pokretanje projekta?**  
O: Ne, Redis je opcionalan, ali se preporučuje za bolje performanse redova zadataka.

**P: Kako pokrenuti testove za pojedinačne module?**  
O: Testove možete pokrenuti za određeni fajl koristeći:
```bash
php artisan test --filter=TestImeKlase
```

## Dokumentacija
Kompletna API dokumentacija je dostupna na [API Dokumentacija](http://localhost/api/documentation).

## Doprinos

Doprinosi su dobrodošli! Ako želite doprinijeti, slijedite ove korake:

1. Fork-ujte repozitorijum.
2. Kreirajte novu granu za vašu funkcionalnost ili ispravku greške:
   ```bash
   git checkout -b feature/naziv-funkcionalnosti
   ```
3. Napravite commit vaših izmjena:
   ```bash
   git commit -m "Dodaj funkcionalnost: naziv-funkcionalnosti"
   ```
4. Push-ujte granu:
   ```bash
   git push origin feature/naziv-funkcionalnosti
   ```
5. Otvorite pull request.

## Sigurnosne Napomene
- Nikada ne dijelite `.env` fajl javno.
- Osigurajte da su svi API ključevi i lozinke šifrovani.
- Koristite HTTPS za sva produkcijska okruženja.

## Licenca

Ovaj projekat je licenciran pod MIT Licencom. Pogledajte `LICENSE` fajl za više informacija.