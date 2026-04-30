# Notification Gateway Service

## Requirements

- Docker & Docker Compose

---

## Quick Start

```bash
# 1. Clone repository
git clone <repository-url>
cd notification-service

# 2. Copy .env file
cp .env.example .env

# 3. Start Docker containers
docker compose up -d

# 4. Install dependencies
docker compose exec php composer install

# 5. Generate application key
docker compose exec php php artisan key:generate

# 6. Run migrations
docker compose exec php php artisan migrate

# 7. Done! Service available at http://localhost:8000
```

---

## API Documentation

**Endpoint:** `POST /api/notification`

**Request:**
```json
{
  "to": "user@example.com",
  "message": "Hello, World!"
}
```

**Response:**
```json
{
  "channel": "email",
  "status": "success",
  "message": "Hello, World!",
  "meta": []
}
```

### Examples

```bash
# Email
curl -X POST http://localhost:8000/api/notification \
  -H "Content-Type: application/json" \
  -d '{"to": "test@example.com", "message": "Test email"}'

# SMS
curl -X POST http://localhost:8000/api/notification \
  -H "Content-Type: application/json" \
  -d '{"to": "+380123456789", "message": "Test SMS"}'
```

---

## Testing

```bash
docker compose exec php php artisan test
```

---

## License

MIT License
