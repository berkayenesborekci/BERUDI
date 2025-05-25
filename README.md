# 🚗 BERUDI - Premium Audi Sport Car Rental
   ## ✨ Features

   ### 👤 For Customers
   - **🔍 Browse Premium Vehicles**: View collection of high-end Audi sports cars
   - **🎯 Advanced Search**: Find cars by model, price, and specifications  
   - **📅 Easy Reservations**: Book vehicles with simple date selection
   - **📱 Responsive Design**: Optimized for all devices

   ### 👨‍💼 For Administrators  
   - **📊 Dashboard**: Monitor users, vehicles, and reservations
   - **👥 User Management**: Manage registered customers
   - **🚗 Vehicle Management**: Add, edit, and manage car inventory
   - **✅ Reservation Control**: Approve, confirm, or cancel bookings

   ## 🛠️ Tech Stack

   - **Backend**: PHP 7.4+, MySQL 5.7+
   - **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 4.5.2
   - **Libraries**: jQuery 3.6.0

   ## 🚀 Quick Start

   ### Prerequisites
   - PHP 7.4+
   - MySQL 5.7+
   - Web server (Apache/Nginx)

   ### Installation

   1. **Download & Setup**
      ```bash
      # Download from GitHub or clone
      git clone https://github.com/berkayenesborekci/BERUDI.git
      cd BERUDI
      ```

   2. **Database Setup**
      ```sql
      mysql -u root -p
      CREATE DATABASE berudi_db;
      USE berudi_db;
      source database.sql;
      ```

   3. **Configure Database**
      ```php
      $servername = "localhost";
      $username = "root";           
      $password = "";               
      $dbname = "berudi_db";
      ```

   4. **Create Admin User**
      ```bash
      # Visit: http://localhost/BERUDI/create_admin.php
      # This will create admin account: admin / admin123
      ```

   5. **Access Application**
      - **Website**: `http://localhost/BERUDI`
      - **Admin Panel**: `http://localhost/BERUDI/admin`

   ## 📁 Project Structure

   ```
   BERUDI/
   ├── 📁 admin/              # Admin panel
   │   ├── index.php         # Dashboard
   │   ├── vehicles.php      # Vehicle management
   │   └── users.php         # User management
   ├── 📁 assets/            # Static assets
   │   ├── css/style.css     # Custom styles
   │   └── images/           # Vehicle images
   ├── 📄 index.php          # Homepage
   ├── 📄 login.php          # Authentication
   ├── 📄 register.php       # User registration
   ├── 📄 vehicles.php       # Vehicle catalog
   ├── 📄 reserve.php        # Reservation system
   ├── 📄 database.sql       # Database schema
   └── 📄 config.php         # Configuration
   ```

   ## 🔐 Security Features

   - ✅ Password hashing with PHP's `password_hash()`
   - ✅ SQL injection prevention via prepared statements
   - ✅ Session-based authentication
   - ✅ Input validation and sanitization
   - ✅ Role-based access control

   ## 📱 Responsive Design

   Fully responsive design tested on:
   - 🖥️ **Desktop** (1200px+)
   - 📱 **Tablet** (768px - 1199px)  
   - 📱 **Mobile** (< 768px)

   ## 🗄️ Database Schema

   ### Tables
   - **users**: Customer and admin accounts
   - **vehicles**: Car inventory with specifications
   - **reservations**: Booking management system

   ## ⚙️ Configuration

   ### Environment Setup
   ```php
   // config.php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "berudi_db";
   ```

   ### Admin Access
   1. Run `create_admin.php` to create admin user
   2. Login with: `admin` / `admin123`
   3. Access admin panel at `/admin/`

   ## 🤝 Contributing

   1. Fork the project
   2. Create feature branch (`git checkout -b feature/AmazingFeature`)
   3. Commit changes (`git commit -m 'Add AmazingFeature'`)
   4. Push to branch (`git push origin feature/AmazingFeature`)
   5. Open Pull Request

   ## 📄 License

   This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

   ## 🙏 Acknowledgments

   - Bootstrap team for the amazing CSS framework
   - jQuery community for the JavaScript library
   - Audi for inspiring the premium car rental concept

   ## 📞 Support

   If you have any questions or need help, please:
   - 🐛 [Open an issue](https://github.com/berkayenesborekci/BERUDI/issues)
   - 💬 [Start a discussion](https://github.com/berkayenesborekci/BERUDI/discussions)

   ---
