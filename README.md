# CaféFlow - POS & Restaurant Ordering System

CaféFlow is a modern, full-featured Point of Sale (POS) and ordering management system designed specifically for cafes and restaurants. Built with Laravel 12 and Tailwind CSS, it provides a seamless experience for both staff and customers.

## 🚀 Features

### 💻 POS Terminal
- Efficient interface for staff to take walk-in orders.
- Quick product selection by category.
- Real-time cart management.
- Multiple payment methods (Cash, Card, Mobile).

### 🌐 Online Ordering
- Customer-facing website for browsing the menu.
- Online cart and checkout system.
- Track active orders from the homepage.

### 👨‍🍳 Kitchen & Order Management
- Live order screen for kitchen and waitstaff.
- Real-time status updates (Pending, Preparing, Ready, Completed, Cancelled).
- Automatic payment status updates on completion.

### 📊 Analytics Dashboard
- Comprehensive overview of daily sales and order volume.
- Visual sales charts for the past week.
- Quick stats for pending and preparing orders.
- Recent order history.

### 📋 Menu Management
- Full CRUD operations for Categories and Products.
- Dynamic category icons.
- Availability toggle for menu items.
- Product image and description management.

## 👥 User Roles & Permissions

- **Admin**: Full access to all features, including the dashboard and menu management.
- **Cashier**: Access to the POS terminal and order management.
- **Kitchen**: Access to the kitchen screen to manage order statuses.
- **Waiter**: Access to order management and POS.
- **Customer**: Access to the online ordering website.

## 🛠️ Tech Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Frontend**: [Tailwind CSS](https://tailwindcss.com), [Vite](https://vitejs.dev)
- **Database**: MySQL
- **Language**: PHP 8.2+

## ⚙️ Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd cafeflow
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration:**
   - Create a MySQL database named `cafeflow`.
   - Update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in your `.env` file.

5. **Run Migrations & Seed:**
   - You can use the provided SQL dump for initial data:
     ```bash
     mysql -u your_user -p cafeflow < cafeflow.sql
     ```
   - Or run standard migrations:
     ```bash
     php artisan migrate
     ```

6. **Build Assets:**
   ```bash
   npm run build
   ```

7. **Start the Server:**
   ```bash
   php artisan serve
   ```

## 🔐 Default Credentials (Testing)

All accounts use the password: `password`

| Role | Email |
| :--- | :--- |
| **Admin** | admin@cafeflow.com |
| **Cashier** | cashier@cafeflow.com |
| **Kitchen** | kitchen@cafeflow.com |
| **Waiter** | waiter@cafeflow.com |
| **Customer** | alice@gmail.com |

## 📜 License

The CaféFlow system is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
