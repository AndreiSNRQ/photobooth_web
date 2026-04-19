# 📸 Photobooth Web System

Laravel-based backend for the Photobooth system.  
Handles sessions, photo storage, payments, gallery, and admin dashboard.

---

## Stack
- **Laravel 12** — API + Web
- **SQLite** (dev) / MySQL (prod)
- **Local disk** or **Cloudinary** for image storage
- **Bootstrap 5** — Gallery & Admin UI

---

## Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed          # loads 10 demo sessions
php artisan storage:link
php artisan serve
```

Visit:
- Admin → http://localhost:8000/admin
- Gallery → http://localhost:8000/gallery/{SESSION_ID}

---

## .env Key Settings

```env
PHOTOBOOTH_STORAGE_DRIVER=local        # or: cloudinary
PHOTOBOOTH_SESSION_EXPIRY_HOURS=24
PHOTOBOOTH_DEFAULT_PRICE=100
PHOTOBOOTH_API_KEY=                    # set a secret key for kiosk auth

# Cloudinary (only if storage driver = cloudinary)
CLOUDINARY_URL=cloudinary://KEY:SECRET@CLOUD_NAME
```

---

## API Reference (consumed by Kiosk & PWA)

All routes prefixed: `/api/v1`  
Header required: `X-API-Key: <your key>` (if `PHOTOBOOTH_API_KEY` is set)

### Sessions
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/sessions` | Create new session → returns `session_id`, `qr_code_url` |
| GET  | `/sessions/{id}` | Get session + photos + payment |
| POST | `/sessions/{id}/complete` | Mark complete + send email |

**POST /sessions body:**
```json
{
  "template": "classic-4",
  "total_shots": 4,
  "customer_name": "Juan",
  "customer_email": "juan@email.com"
}
```

### Photos
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/sessions/{id}/photos` | Upload a photo (multipart) |
| GET  | `/sessions/{id}/photos` | List all photos |

**POST /photos fields:**
- `photo` — image file (max 10MB)
- `shot_number` — integer
- `is_collage` — boolean

### Payments
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/sessions/{id}/payment` | Create payment record |
| POST | `/sessions/{id}/payment/confirm` | Mark as paid |
| GET  | `/sessions/{id}/payment` | Get payment status |

**POST /payment body:**
```json
{
  "amount": 100,
  "method": "gcash",
  "payment_type": "pay_now"
}
```

---

## System Flow

```
Kiosk (.exe)
  │
  ├─ POST /api/v1/sessions          → create session + QR
  ├─ POST /api/v1/sessions/{id}/payment  → record payment
  ├─ POST /api/v1/sessions/{id}/photos   → upload each shot + collage
  └─ POST /api/v1/sessions/{id}/complete → finalize + email

User scans QR
  └─ GET /gallery/{session_id}      → view & download photos

Admin
  └─ GET /admin                     → dashboard, sessions, payments
```
