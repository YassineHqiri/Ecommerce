# AtlasTech Solutions - Full-Stack Web Application

A secure, production-ready commercial web application built with Laravel (REST API) and React (SPA) for AtlasTech Solutions - a company selling web development service packs to SMEs.

## Project Structure

```
atlastech-docs/          # Documentation
├── SPEC.md              # System architecture overview
├── DATABASE.md          # Database schema details
├── API.md               # API documentation
├── SECURITY.md          # Security implementation details
└── DEPLOYMENT.md        # Deployment guide

atlastech-backend/       # Laravel REST API
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Resources/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── config/
└── routes/

atlastech-frontend/     # React SPA
├── src/
│   ├── components/
│   ├── pages/
│   ├── context/
│   ├── routes/
│   ├── services/
│   └── styles/
├── package.json
└── vite.config.js

deployment/             # Deployment configurations
├── nginx.conf
├── nginx-ssl.conf
└── deploy.sh
```

## Technology Stack

- **Backend**: Laravel 10+ (REST API)
- **Frontend**: React 18+ with Vite
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Styling**: Tailwind CSS
- **Animations**: Framer Motion

## Features

### Public Website
- Home Page with hero section and CTAs
- Services Page with service pack listings
- About Page with company information
- Contact Page with form validation
- Order Form with service pack selection

### Admin Panel
- Secure login/logout
- Dashboard with statistics
- Order management (view, update status, delete)
- Service pack management (CRUD)
- Protected routes with authentication

## Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0
- NPM or Yarn

### Backend Setup

```bash
# Navigate to backend
cd atlastech-backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# Then run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed

# Start development server
php artisan serve
```

### Frontend Setup

```bash
# Navigate to frontend
cd atlastech-frontend

# Install dependencies
npm install

# Start development server
npm run dev
```

### Admin Credentials
Set a strong password in `.env` before running `php artisan db:seed`:
```
ADMIN_SEED_PASSWORD=YourStrongPassword123!
```
- Admin: admin@atlastech.com
- Manager: manager@atlastech.com
(Both use the same password from ADMIN_SEED_PASSWORD)

If not set, a random password is generated (check console output when seeding).

## API Endpoints

### Public Endpoints
```
GET    /api/public/service-packs    # Get all active service packs
POST   /api/public/orders          # Create new order
POST   /api/public/contact          # Submit contact form
```

### Admin Endpoints (Protected)
```
POST   /api/admin/login            # Admin login
POST   /api/admin/logout            # Admin logout
GET    /api/admin/me                # Get current admin
GET    /api/admin/dashboard         # Get dashboard statistics
GET    /api/admin/orders            # List all orders
PATCH  /api/admin/orders/{id}       # Update order status
DELETE /api/admin/orders/{id}       # Delete order
GET    /api/admin/service-packs     # List service packs
POST   /api/admin/service-packs     # Create service pack
PUT    /api/admin/service-packs/{id}# Update service pack
DELETE /api/admin/service-packs/{id}# Delete service pack
```

## Security Features

- Laravel Sanctum for API authentication
- Password hashing with bcrypt
- Role-based authorization (admin/super_admin)
- API rate limiting
- FormRequest validation
- SQL injection protection via Eloquent
- XSS protection via Blade escaping
- CORS configuration
- Security headers (CSP, X-Frame-Options, etc.)
- Activity logging

## Deployment

### Production Build

```bash
# Backend
cd atlastech-backend
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Frontend
cd atlastech-frontend
npm run build
```

See `deployment/` folder for Nginx and server configuration.

## Environment Variables

### Backend (.env)
```
APP_NAME="AtlasTech Solutions"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://atlastech.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=atlastech
DB_USERNAME=atlastech_user
DB_PASSWORD=your_secure_password

SANCTUM_STATEFUL_DOMAINS=atlastech.com
CORS_ALLOWED_ORIGINS=https://atlastech.com
```

### Frontend
Create `.env` file (optional):
```
# Only needed if API is on a different domain. If frontend & API share the same domain, leave empty.
VITE_API_URL=https://atlastech.com/api
```

### AWS / Services Not Loading
If service packs don't load on AWS:
1. **Same domain**: If frontend and backend share a domain (e.g. both at `your-domain.com`), no `VITE_API_URL` needed — the app uses `/api` automatically.
2. **Different domains**: Set `VITE_API_URL=https://your-api-domain.com/api` before `npm run build`.
3. **CORS**: Add your frontend URL to `CORS_ALLOWED_ORIGINS` in backend `.env` (comma-separated).
4. **Admin login**: Hidden from public nav. Access directly at `/admin/login`.

## License

MIT License
