<p align="center"> <h1 align="center">Inventory and POS System</h1>
This is a web-based Inventory and Point of Sale (POS) system developed using Laravel (backend) and Bootstrap (frontend). The system enables effective management of products, sales, suppliers, services, returns, inventory, and payments.
</p>

<p>
<h1 font="bold">🧩 Features</h1>
📦 Inventory Management<br>
Track product quantities and restocking schedules.<br>
Manage supplier details and inventory updates.<br>

🧾 POS (Point of Sale) <br>
Create sales receipts and order items. <br>
Track services sold alongside products.<br>
Supports multiple payment methods. <br>

🔁 Returns Handling <br>
Manage returned products with reasons and return dates. <br>

📈 History and Reporting <br>
Monitor total sales per order. <br>
Track historical transaction data.<br>
</p>

<p>
<h1 font="bold">🛠️ Technologies Used</h1> <br>
Backend: Laravel (PHP) <br> 
Frontend: Bootstrap (HTML/CSS/JS) <br>
Database: MySQL <br>
</p>

# 1. Clone the repository
git clone https://github.com/yelyail/doubleK.git
cd doubleK

# 2. Install PHP dependencies
composer install

# 3. Copy and configure environment file
cp .env.example .env
php artisan key:generate

# 4. Set up your database
Create a MySQL database
Update DB settings in .env file

# 5. Run migrations (create tables)
php artisan migrate

# 6. Serve the Laravel app
php artisan serve
