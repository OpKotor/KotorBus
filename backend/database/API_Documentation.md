# API Dokumentacija

Ova dokumentacija opisuje API endpoint-e za proces rezervacije.

---

## 1. Kreiranje rezervacije

**Endpoint**: `/api/reservations`  
**Metoda**: `POST`  
**Opis**: Kreira novu rezervaciju sa unetim podacima.

### Podaci za slanje:
```json
{
  "company_name": "Naziv firme",
  "country": "Država",
  "license_plate": "AB-123-CD",
  "email": "email@primer.com",
  "drop_off_time_slot_id": 1,
  "pick_up_time_slot_id": 2,
  "vehicle_type_id": 1,
  "is_data_accurate": true,
  "is_terms_accepted": true
}
```
- `company_name` *(opciono)*: Naziv prevoznika ili firme (ako je potrebno).
- `country`: Država registracije vozila.
- `license_plate`: Registarske oznake vozila.
- `email`: Kontakt email.
- `drop_off_time_slot_id`: ID termina za istovar.
- `pick_up_time_slot_id`: ID termina za ukrcavanje.
- `vehicle_type_id`: Tip vozila (`1` autobus, `2` kamion, itd.).
- `is_data_accurate`: Da li su podaci tačni (checkbox).
- `is_terms_accepted`: Da li su uslovi prihvaćeni (checkbox).

### Odgovor:
- **201 Created**: Rezervacija je uspešno kreirana.  
  ```json
  {
    "status": "success",
    "reservation_id": 123,
    "message": "Rezervacija uspešno kreirana."
  }
  ```
- **400 Bad Request**: Nedostaju potrebni podaci ili su podaci nevalidni.
- **422 Unprocessable Entity**: Greške u validaciji podataka.  
  ```json
  {
    "status": "error",
    "errors": {
      "email": ["Email je obavezan."],
      "license_plate": ["Registarska oznaka mora biti jedinstvena."]
    }
  }
  ```

---

## 2. Plaćanje rezervacije

**Endpoint**: `/api/reservations/pay`  
**Metoda**: `POST`  
**Opis**: Obradjuje plaćanje za rezervaciju.

### Podaci za slanje:
```json
{
  "reservation_id": 123,
  "payment_method": "credit_card" // ili "bank_transfer", "cash"
}
```

### Odgovor:
- **200 OK**: Plaćanje je uspešno.
  ```json
  {
    "status": "success",
    "message": "Plaćanje uspešno."
  }
  ```
- **400 Bad Request**: Greška u podacima za plaćanje.
- **402 Payment Required**: Plaćanje nije uspelo.

---

## 3. Slanje potvrde e-mailom

**Endpoint**: `/api/reservations/confirm`  
**Metoda**: `POST`  
**Opis**: Šalje potvrdu rezervacije na unetu e-mail adresu.

### Podaci za slanje:
```json
{
  "reservation_id": 123
}
```

### Odgovor:
- **200 OK**: Potvrda je poslata.
- **404 Not Found**: Rezervacija nije pronađena.
  ```json
  {
    "status": "error",
    "message": "Rezervacija nije pronađena."
  }
  ```

---

## 4. Pregled dostupnih termina (opciono)

**Endpoint**: `/api/time-slots?date=2025-06-01`  
**Metoda**: `GET`  
**Opis**: Vraća dostupne termine za izabrani dan.

### Odgovor:
- **200 OK**
  ```json
  [
    {
      "id": 1,
      "time": "08:00-09:00",
      "available": true,
      "remaining": 5
    },
    ...
  ]
  ```

---

## Napomene
- Svi zahtevi i odgovori koriste UTF-8 i `application/json`.
- Zaštićene rute zahtevaju autentifikaciju putem API tokena ili sesije (ako je primenjivo).
- Sva polja proveravati prema aktuelnoj bazi/migracijama!
