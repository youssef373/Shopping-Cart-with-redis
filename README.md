# 🛒 Redis Shopping Cart - Laravel E-commerce System

A comprehensive e-commerce shopping cart system built with **Laravel 12**, **Redis**, **Livewire 3**, **Alpine.js**, and **Filament 3** to demonstrate advanced Redis concepts and modern Laravel development practices.

## 📋 Project Overview

This project is designed as a learning platform to understand Redis data structures and operations in a real-world e-commerce context. It showcases:

- **Redis Hashes** for cart storage
- **Redis Sets** for wishlist management
- **Redis Lists** for recently viewed products
- **Redis Sorted Sets** for analytics and rankings
- **Redis TTL** for cart expiration
- **Distributed Locking** for stock management
- **Real-time Updates** with Livewire
- **Interactive UI** with Alpine.js
- **Admin Panel** with Filament 3

## 🚀 Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Database**: MySQL (persistent data)
- **Cache/Session**: Redis (Predis client)
- **Frontend**: Livewire 3, Alpine.js, Tailwind CSS 4
- **Admin Panel**: Filament 3
- **Authentication**: Laravel Breeze (Livewire)

## ✨ Core Features

### 1. Product Management (Filament Admin)
- Complete CRUD operations
- Category management with hierarchical structure
- Bulk actions (delete, update stock, change prices)
- Product image uploads with media library
- Stock management and alerts
- Product variants (sizes, colors)
- Import/export products (CSV)
- Rich text editor for descriptions

### 2. Shopping Cart (Redis Hashes + Livewire)
- Store cart data in Redis using hashes
- Real-time cart updates without page reload
- Add/update/remove items
- Cart expiration after 7 days of inactivity
- Guest cart support with session ID
- Merge guest cart with user cart on login
- Mini cart dropdown in navbar

### 3. Recently Viewed Products (Redis Lists)
- Track last 10 viewed products per user
- Use Redis LPUSH and LTRIM commands
- Display in carousel on product pages

### 4. Wishlist (Redis Sets)
- Store wishlist items in Redis sets
- Add/remove with heart icon toggle
- Move items from wishlist to cart
- Real-time wishlist count

### 5. Abandoned Cart Recovery
- Detect carts inactive for 24 hours
- Queue email notifications
- Dashboard statistics in Filament
- View abandoned carts in admin panel

### 6. Cart Analytics (Filament Dashboard)
- Track cart events (add/remove)
- Store statistics in Redis sorted sets
- Most added products ranking
- Cart abandonment rate
- Average cart value
- Custom Filament widgets with charts

### 7. Product Recommendations
- "Frequently bought together" using Redis sets
- Track product pairs from checkouts
- Display recommendations based on cart contents

### 8. Flash Sales / Limited Stock
- Redis-based concurrent stock updates
- Distributed locking to prevent overselling
- Real-time stock counter
- Countdown timer with Alpine.js
- Flash sale management in admin

### 9. User Session Enhancements
- Store user preferences in Redis
- Cache user profile data
- Quick access to recent orders

### 10. Admin Dashboard (Filament)
- Custom dashboard with widgets
- View all active carts
- Monitor Redis cache metrics
- Clear specific user carts
- Product popularity metrics
- Cache management panel
- Revenue statistics
- Low stock alerts

## 📦 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL
- Redis Server
- XAMPP (or similar local server)

### Step 1: Install Dependencies

```bash
# Install PHP dependencies
composer require predis/predis
composer require livewire/livewire
composer require filamentphp/filament:"^3.0"
composer require laravel/breeze --dev

# Install Node dependencies
npm install alpinejs
npm install
```

### Step 2: Environment Configuration

Update your `.env` file:

```env
APP_NAME="Redis Shopping Cart"
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=redis_cart
DB_USERNAME=root
DB_PASSWORD=

# Redis Configuration
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Session & Cache
SESSION_DRIVER=redis
CACHE_STORE=redis
QUEUE_CONNECTION=redis
```

### Step 3: Database Setup

```bash
# Create database
mysql -u root -e "CREATE DATABASE redis_cart;"

# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed
```

### Step 4: Install Breeze Authentication

```bash
php artisan breeze:install livewire
php artisan migrate
npm install && npm run build
```

### Step 5: Install Filament

```bash
php artisan filament:install --panels
php artisan make:filament-user
```

### Step 6: Build Assets

```bash
npm run dev
```

### Step 7: Start Development Server

```bash
php artisan serve
```

Visit:
- **Frontend**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin

## 🗂️ Project Structure

```
app/
├── Filament/
│   ├── Resources/
│   │   ├── ProductResource.php
│   │   ├── CategoryResource.php
│   │   ├── OrderResource.php
│   │   └── UserResource.php
│   └── Widgets/
│       ├── CartAnalyticsWidget.php
│       └── RevenueWidget.php
├── Http/
│   └── Livewire/
│       ├── CartComponent.php
│       ├── MiniCartComponent.php
│       ├── ProductListComponent.php
│       ├── WishlistComponent.php
│       └── CheckoutComponent.php
├── Services/
│   ├── CartService.php
│   ├── WishlistService.php
│   └── RecentlyViewedService.php
├── Repositories/
│   └── RedisCartRepository.php
└── Models/
    ├── Product.php
    ├── Category.php
    ├── Order.php
    └── OrderItem.php
```

## 🔑 Redis Key Structure

| Key Pattern | Type | Description |
|------------|------|-------------|
| `cart:{user_id}` | Hash | User's shopping cart items |
| `wishlist:{user_id}` | Set | User's wishlist product IDs |
| `recent:{user_id}` | List | Recently viewed product IDs (max 10) |
| `product:{product_id}` | String | Cached product data (JSON) |
| `popular_products` | Sorted Set | Product popularity ranking |
| `views:{product_id}` | String | Product view counter |
| `stock:{product_id}` | String | Real-time stock count |
| `cart_timestamp:{user_id}` | String | Last cart activity timestamp |
| `abandoned_carts` | Set | User IDs with abandoned carts |
| `flash_sale:{product_id}` | Hash | Flash sale details |

## 📚 Redis Concepts Demonstrated

### 1. **Hashes** (Shopping Cart)
```php
// Store cart items
Redis::hset('cart:123', 'product_1', json_encode([
    'quantity' => 2,
    'price' => 29.99,
    'name' => 'Product Name'
]));

// Get all cart items
$cart = Redis::hgetall('cart:123');
```

### 2. **Sets** (Wishlist)
```php
// Add to wishlist
Redis::sadd('wishlist:123', 1, 2, 3);

// Check if product in wishlist
$exists = Redis::sismember('wishlist:123', 1);

// Remove from wishlist
Redis::srem('wishlist:123', 1);
```

### 3. **Lists** (Recently Viewed)
```php
// Add to recently viewed (keep last 10)
Redis::lpush('recent:123', 5);
Redis::ltrim('recent:123', 0, 9);

// Get recently viewed
$recent = Redis::lrange('recent:123', 0, 9);
```

### 4. **Sorted Sets** (Analytics)
```php
// Increment product popularity score
Redis::zincrby('popular_products', 1, 'product_5');

// Get top 10 popular products
$popular = Redis::zrevrange('popular_products', 0, 9, 'WITHSCORES');
```

### 5. **TTL** (Cart Expiration)
```php
// Set cart expiration (7 days)
Redis::expire('cart:123', 60 * 60 * 24 * 7);

// Check remaining TTL
$ttl = Redis::ttl('cart:123');
```

### 6. **Distributed Locking** (Stock Management)
```php
// Acquire lock for stock update
$lock = Cache::lock('stock:product_5', 10);
if ($lock->get()) {
    // Update stock safely
    $lock->release();
}
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 📖 Learning Path

This project is structured to teach Redis concepts progressively:

1. **Basic Operations**: Start with simple cart operations (HSET, HGET, HDEL)
2. **Data Structures**: Explore Sets for wishlist, Lists for history
3. **Advanced Features**: Sorted Sets for rankings, TTL for expiration
4. **Performance**: Caching strategies, pipeline operations
5. **Concurrency**: Distributed locks, atomic operations
6. **Analytics**: Real-time metrics, aggregations

## 🤝 Contributing

This is a learning project. Feel free to:
- Add new Redis features
- Improve code quality
- Enhance documentation
- Report issues

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- Laravel Framework
- Redis
- Livewire
- Alpine.js
- Filament
- Tailwind CSS

---

**Built with ❤️ for learning Redis and modern Laravel development**
