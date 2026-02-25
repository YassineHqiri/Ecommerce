# AtlasTech Solutions - System Architecture Overview

## 1. SYSTEM ARCHITECTURE OVERVIEW

### 1.1 Architecture Diagram
```
┌─────────────────────────────────────────────────────────────────┐
│                        VPS SERVER                                │
│  ┌─────────────────────┐     ┌─────────────────────────────┐  │
│  │   React SPA (Nginx) │     │    Laravel API (PHP-FPM)     │  │
│  │   Port: 80/443      │     │    Port: 8000 (internal)     │  │
│  └─────────┬───────────┘     └──────────────┬──────────────┘  │
│            │                                  │                  │
│            │        ┌──────────────────┐      │                  │
│            └──────►│   MySQL Database  │◄─────┘                  │
│                     │   Port: 4306     │                         │
│                     └──────────────────┘                         │
└─────────────────────────────────────────────────────────────────┘
```

### 1.2 Technology Stack
- **Backend**: Laravel 10+ (REST API)
- **Frontend**: React 18+ with Vite
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Web Server**: Nginx (Reverse Proxy)
- **HTTPS**: Let's Encrypt

### 1.3 Clean Architecture Layers
```
┌────────────────────────────────────────┐
│           Presentation Layer           │
│  (Controllers, API Resources, Requests)│
├────────────────────────────────────────┤
│           Business Logic Layer         │
│          (Services, Policies)          │
├────────────────────────────────────────┤
│              Data Layer                │
│      (Models, Migrations, Repositories)│
└────────────────────────────────────────┘
```

### 1.4 API Endpoints Structure
```
PUBLIC API:
├── GET    /api/service-packs
├── POST   /api/orders
├── POST   /api/contact

ADMIN API (Protected):
├── POST   /api/admin/login
├── POST   /api/admin/logout
├── GET    /api/admin/dashboard
├── GET    /api/admin/orders
├── PATCH  /api/admin/orders/{id}
├── GET    /api/admin/service-packs
├── POST   /api/admin/service-packs
├── PUT    /api/admin/service-packs/{id}
└── DELETE /api/admin/service-packs/{id}
```

---

## 2. DATABASE DESIGN

### 2.1 Database: atlastech

### 2.2 Tables

#### users (Admins Only)
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(255) | NOT NULL |
| email | VARCHAR(255) | UNIQUE, NOT NULL |
| password | VARCHAR(255) | NOT NULL (bcrypt) |
| role | ENUM('admin', 'super_admin') | DEFAULT 'admin' |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### service_packs
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(255) | NOT NULL |
| description | TEXT | NULLABLE |
| price | DECIMAL(10,2) | NOT NULL |
| features | JSON | NOT NULL |
| is_active | BOOLEAN | DEFAULT TRUE |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### orders
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT |
| customer_name | VARCHAR(255) | NOT NULL |
| email | VARCHAR(255) | NOT NULL |
| phone | VARCHAR(50) | NULLABLE |
| selected_pack_id | BIGINT UNSIGNED | FOREIGN KEY |
| status | ENUM('pending','completed','cancelled') | DEFAULT 'pending' |
| notes | TEXT | NULLABLE |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### contact_messages
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(255) | NOT NULL |
| email | VARCHAR(255) | NOT NULL |
| message | TEXT | NOT NULL |
| is_read | BOOLEAN | DEFAULT FALSE |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

---

## 3. BACKEND IMPLEMENTATION PLAN

### 3.1 Laravel Project Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── PublicController.php
│   │   │   └── Admin/
│   │   │       ├── AuthController.php
│   │   │       ├── DashboardController.php
│   │   │       ├── OrderController.php
│   │   │       └── ServicePackController.php
│   ├── Middleware/
│   │   ├── AdminAuthenticate.php
│   │   └── RateLimitAdmin.php
│   └── Requests/
│       ├── StoreOrderRequest.php
│       ├── StoreContactRequest.php
│       ├── LoginRequest.php
│       └── StoreServicePackRequest.php
├── Models/
│   ├── User.php
│   ├── ServicePack.php
│   ├── Order.php
│   └── ContactMessage.php
├── Policies/
│   ├── OrderPolicy.php
│   └── ServicePackPolicy.php
├── Providers/
│   └── AppServiceProvider.php
└── Services/
    ├── OrderService.php
    └── ServicePackService.php

config/
├── cors.php
├── sanctum.php
└── session.php

database/
├── migrations/
└── seeders/
    ├── DatabaseSeeder.php
    ├── ServicePackSeeder.php
    └── AdminUserSeeder.php

routes/
├── api.php
└── channels.php
```

### 3.2 Implementation Steps
1. Install Laravel + Sanctum
2. Configure database connection
3. Create migrations
4. Create models with relationships
5. Create API resources
6. Create FormRequest validations
7. Create Controllers
8. Create middleware for admin protection
9. Configure API routes
10. Add CORS configuration
11. Configure rate limiting
12. Add security headers
13. Create seeders
14. Test API endpoints

---

## 4. FRONTEND IMPLEMENTATION PLAN

### 4.1 React Project Structure (Vite)
```
src/
├── components/
│   ├── common/
│   │   ├── Button.jsx
│   │   ├── Input.jsx
│   │   ├── Card.jsx
│   │   ├── Loader.jsx
│   │   └── Modal.jsx
│   ├── layout/
│   │   ├── Header.jsx
│   │   ├── Footer.jsx
│   │   └── AdminLayout.jsx
│   └── public/
│       ├── Hero.jsx
│       ├── ServiceCard.jsx
│       └── ContactForm.jsx
├── pages/
│   ├── public/
│   │   ├── Home.jsx
│   │   ├── Services.jsx
│   │   ├── About.jsx
│   │   ├── Contact.jsx
│   │   └── OrderForm.jsx
│   └── admin/
│       ├── Login.jsx
│       ├── Dashboard.jsx
│       ├── Orders.jsx
│       └── ServicePacks.jsx
├── context/
│   └── AuthContext.jsx
├── services/
│   ├── api.js
│   ├── authService.js
│   └── orderService.js
├── hooks/
│   ├── useAuth.js
│   └── useOrders.js
├── routes/
│   ├── AppRoutes.jsx
│   └── PrivateRoute.jsx
├── styles/
│   └── index.css
├── App.jsx
└── main.jsx
```

### 4.2 Implementation Steps
1. Initialize React with Vite
2. Install dependencies (axios, react-router-dom, etc.)
3. Setup Axios with base URL and interceptors
4. Create AuthContext for global state
5. Create reusable UI components
6. Implement public pages
7. Implement admin pages with protected routes
8. Add animations (framer-motion or CSS)
9. Style with responsive design
10. Build for production

---

## 5. SECURITY EXPLANATION

### 5.1 Authentication & Authorization
- **Laravel Sanctum**: Token-based authentication for SPA
- **Password Hashing**: bcrypt with cost factor 10
- **Role-based Access**: Admin vs Super Admin
- **Token Expiration**: 1 hour for API tokens

### 5.2 Input Validation
- **FormRequest Classes**: Server-side validation
- **Required Fields**: All user inputs validated
- **Email Validation**: RFC 822 compliant
- **Sanitization**: HTML special characters escaped

### 5.3 Protection Against OWASP Top 10
1. **SQL Injection**: Eloquent ORM with parameter binding
2. **XSS**: Blade escaping, React automatic escaping
3. **CSRF**: Sanctum CSRF cookies
4. **Broken Auth**: Rate limiting, session timeout
5. **Sensitive Data Exposure**: Environment variables, HTTPS
6. **XML External Entities**: Disabled by default in Laravel
7. **Broken Access Control**: Middleware + Policies
8. **Security Misconfiguration**: Secure headers, minimal debug
9. **Insecure Deserialization**: JSON API, no serialization
10. **Insufficient Logging**: Activity logging implemented

### 5.4 Secure Headers Configuration
```php
// In App/Http/Middleware/TrustProxies
- X-Frame-Options: DENY
- X-Content-Type-Options: nosniff
- X-XSS-Protection: 1; mode=block
- Strict-Transport-Security: max-age=31536000
- Content-Security-Policy: configured for API
```

### 5.5 API Security
- **Rate Limiting**: 60 requests/minute general, 10/minute login
- **CORS**: Configured allowed origins only
- **Input Sanitization**: All user input sanitized
- **Error Handling**: No stack traces in production

---

## 6. DEPLOYMENT PLAN

### 6.1 VPS Requirements
- Ubuntu 22.04 LTS
- PHP 8.2+
- Node.js 18+
- MySQL 8.0
- Nginx
- Composer

### 6.2 Deployment Steps

#### Backend (Laravel)
```bash
# 1. Clone and setup
git clone atlastech-backend /var/www/atlastech-api
cd /var/www/atlastech-api
composer install --optimize-autoloader --no-dev

# 2. Environment configuration
cp .env.example .env
php artisan key:generate

# 3. Database configuration in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=4306
DB_DATABASE=atlastech
DB_USERNAME=atlastech_user
DB_PASSWORD=secure_password_here

# 4. Run migrations
php artisan migrate --force

# 5. Seed data
php artisan db:seed --force

# 6. Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 7. Set permissions
chown -R www-data:www-data /var/www/atlastech-api
chmod -R 755 /var/www/atlastech-api
chmod -R 775 /var/www/atlastech-api/storage
chmod -R 775 /var/www/atlastech-api/bootstrap/cache
```

#### Frontend (React)
```bash
# 1. Build production
npm run build

# 2. Copy to server
scp -r build/* user@vps:/var/www/atlastech-front/

# 3. Configure Nginx
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name atlastech.com www.atlastech.com;
    root /var/www/atlastech-front/build;
    index index.html;

    # React SPA - all routes to index.html
    location / {
        try_files $uri $uri/ /index.html;
    }

    # Laravel API proxy
    location /api/ {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

#### HTTPS Setup (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d atlastech.com -d www.atlastech.com

# Auto-renewal
sudo certbot renew --dry-run
```

### 6.3 Environment Variables
```env
# Backend (.env)
APP_NAME=AtlasTech
APP_ENV=production
APP_DEBUG=false
APP_URL=https://atlastech.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=atlastech
DB_USERNAME=atlastech_user
DB_PASSWORD=strong_random_password

SESSION_DRIVER=database
SESSION_LIFETIME=120

SANCTUM_STATEFUL_DOMAINS=atlastech.com

CORS_ALLOWED_ORIGINS=https://atlastech.com

LOG_CHANNEL=daily
LOG_LEVEL=warning
```

### 6.4 Post-Deployment Checklist
- [ ] SSL certificate installed and working
- [ ] Debug mode disabled
- [ ] Cache cleared and optimized
- [ ] File permissions set correctly
- [ ] Database migrations run
- [ ] Admin account created
- [ ] API rate limiting active
- [ ] Security headers configured
- [ ] Logs being written
- [ ] Backup strategy in place

---

## 7. DEVELOPMENT ROADMAP

### Phase 1: Foundation (Days 1-2)
- [ ] Setup Laravel project with Sanctum
- [ ] Configure database and migrations
- [ ] Create models and relationships
- [ ] Setup CORS and security middleware

### Phase 2: Backend API (Days 3-5)
- [ ] Implement Public API endpoints
- [ ] Implement Admin API endpoints
- [ ] Add authentication flow
- [ ] Implement rate limiting
- [ ] Add request validation
- [ ] Create seeders

### Phase 3: Frontend Setup (Days 6-7)
- [ ] Initialize React with Vite
- [ ] Setup routing
- [ ] Create Axios service
- [ ] Implement AuthContext

### Phase 4: Public Pages (Days 8-10)
- [ ] Create reusable components
- [ ] Implement Home page
- [ ] Implement Services page
- [ ] Implement About page
- [ ] Implement Contact page
- [ ] Implement Order Form

### Phase 5: Admin Panel (Days 11-13)
- [ ] Create Admin Layout
- [ ] Implement Login page
- [ ] Implement Dashboard
- [ ] Implement Orders management
- [ ] Implement Service Packs management

### Phase 6: Security & Deployment (Days 14-15)
- [ ] Security audit
- [ ] Performance optimization
- [ ] Deploy to VPS
- [ ] Configure SSL
- [ ] Testing and QA

---

## 8. UI DESIGN SUGGESTIONS

### Color Palette
- **Primary**: #0F172A (Dark Navy)
- **Secondary**: #3B82F6 (Blue)
- **Accent**: #10B981 (Emerald)
- **Background**: #F8FAFC (Light Gray)
- **Text Primary**: #1E293B
- **Text Secondary**: #64748B

### Typography
- **Headings**: Inter Bold
- **Body**: Inter Regular
- **Accent**: JetBrains Mono (for prices)

### Layout Approach
- **Mobile First**: Flexbox + Grid
- **Breakpoints**: 640px, 768px, 1024px, 1280px
- **Spacing**: 8px base unit (0.5rem)

### Animations
- **Page Transitions**: Fade in (300ms ease)
- **Hover Effects**: Scale 1.02 (200ms)
- **Loading States**: Skeleton screens
- **Scroll Animations**: Fade up on scroll

### Component Style
- **Buttons**: Rounded corners (8px), subtle shadow
- **Cards**: White background, light border, shadow on hover
- **Forms**: Floating labels, validation states
- **Tables**: Striped rows, hover highlight
