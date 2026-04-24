# AB Pet Grooming Admin Panel

A professional, feature-rich admin management system for pet grooming businesses built with **HTML, PHP, CSS, and JavaScript** featuring a beautiful **soft lavender theme**.

## 🎨 Features

### Authentication & Security
- **Simple Login System** - Username/password authentication with session management
- **Session Timeout** - Automatic logout after 1 hour of inactivity
- **Role-Based Access** - Admin-only access to all management features
- **Secure Logout** - Proper session destruction

### Dashboard
- **Analytics Dashboard** - Interactive charts showing business metrics
- **Daily Statistics** - Bar charts displaying daily bookings and boarding data
- **Weekly Trends** - Line charts showing weekly performance trends
- **Monthly Overview** - Comprehensive monthly analytics
- **Quick Stats** - Cards displaying:
  - Total Customers
  - Total Bookings
  - Active Boarding
  - Pending Reviews

### Bookings Management
- **Booking Table** - Complete list of all bookings with details
- **Status Management** - Update booking status (Pending, Confirmed, Completed, Cancelled)
- **Search & Filter** - Search by booking ID, customer, or pet name
- **Status Filtering** - Filter bookings by status
- **Revenue Tracking** - Total revenue calculation
- **Quick Actions** - Edit and delete bookings

### Boarding Management
- **Check-In/Check-Out Tracking** - Track pet boarding dates and times
- **Duration Calculation** - Automatic duration calculation
- **Status Management** - Active, Completed, or Cancelled status
- **Search & Filter** - Find boardings by customer or pet
- **Revenue Tracking** - Boarding revenue statistics

### Services Management
- **Service Listing** - View all available grooming services
- **Service Details** - Name, category, duration, and pricing
- **Add Services** - Modal form to create new services
- **Edit & Delete** - Manage existing services
- **Service Categories** - Organize services by type

### Pets Management
- **Pet Registry** - Register and manage all pets
- **Pet Information** - Name, breed, age, weight, color
- **Medical Notes** - Track medical conditions and allergies
- **Vaccination Status** - Track vaccination records
- **Owner Information** - Link pets to customers
- **Pet Search** - Find pets by name or owner

### Gallery Management
- **Drag-and-Drop Upload** - Intuitive drag-and-drop interface
- **Image Preview** - Thumbnail gallery view
- **Image Management** - Upload, view, and delete images
- **File Validation** - Automatic file size and type checking
- **Responsive Grid** - Mobile-friendly gallery layout

### Reviews Management
- **Review Approval** - Approve or reject customer reviews
- **Star Ratings** - Visual star rating display (1-5 stars)
- **Review Filtering** - Filter by status (Pending, Approved, Rejected)
- **Search Functionality** - Search reviews by customer name or content
- **Statistics** - Average rating and review count

### User Interface
- **Soft Lavender Theme** - Beautiful gradient color scheme
- **Responsive Design** - Works on desktop, tablet, and mobile
- **Sidebar Navigation** - Easy access to all admin sections
- **Interactive Charts** - Chart.js integration for analytics
- **Status Badges** - Color-coded status indicators
- **Modal Forms** - Clean form interfaces
- **Data Tables** - Organized data presentation

## 📁 Project Structure

```
pet_grooming_admin/
├── index.php                 # Redirect to login or dashboard
├── login.php                 # Admin login page
├── logout.php                # Logout handler
├── dashboard.php             # Main dashboard with analytics
├── bookings.php              # Manage bookings
├── boarding.php              # Manage boarding
├── services.php              # Manage services
├── pets.php                  # Manage pets
├── gallery.php               # Gallery upload and management
├── reviews.php               # Approve reviews
├── config.php                # Database configuration
├── auth_check.php            # Authentication check
├── database.sql              # Database schema
├── css/
│   └── style.css             # Main stylesheet with lavender theme
├── js/
│   └── main.js               # JavaScript utilities and functions
├── includes/
│   ├── header.php            # Header and sidebar component
│   └── footer.php            # Footer component
└── README.md                 # This file
```

## 🎯 Color Scheme (Soft Lavender)

- **Primary Light**: `#E6D9F5` - Light lavender background
- **Primary**: `#D4C5E8` - Main lavender color
- **Primary Dark**: `#B8A8D8` - Dark lavender accents
- **Accent**: `#A89FCC` - Secondary accent
- **Text Dark**: `#4A4A4A` - Main text color
- **Text Light**: `#7A7A7A` - Secondary text color
- **Background**: `#F8F6FC` - Page background
- **White**: `#FFFFFF` - Card backgrounds

## 🚀 Getting Started

### Prerequisites
- PHP 7.4+ or higher
- MySQL/MariaDB database
- Modern web browser (Chrome, Firefox, Safari, Edge)

### Installation

1. **Clone or extract the project**
   ```bash
   cd pet_grooming_admin
   ```

2. **Create the database**
   ```bash
   mysql -u root -p < database.sql
   ```

3. **Update database configuration** (if needed)
   Edit `config.php` and update:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'pet_grooming_admin');
   ```

4. **Start PHP built-in server**
   ```bash
   php -S localhost:8000
   ```

5. **Access the application**
   - Open your browser and navigate to: `http://localhost:8000`
   - Login with demo credentials:
     - **Username**: `admin`
     - **Password**: `admin123`

### Using with Apache/Nginx

1. Copy the project folder to your web root
2. Create a virtual host or access via your domain
3. Update database credentials in `config.php`
4. Access the application through your web server

## 📊 Database Schema

### Tables

- **admin_users** - Admin user accounts
- **customers** - Customer information
- **pets** - Pet registry with medical information
- **services** - Available grooming services
- **bookings** - Booking records with status tracking
- **boarding** - Pet boarding records
- **gallery** - Gallery images
- **reviews** - Customer reviews with ratings

## 🔐 Security Features

- **Session Management** - Secure PHP sessions with timeout
- **Authentication Check** - All pages require login
- **SQL Injection Prevention** - Prepared statements (when using database)
- **XSS Protection** - HTML entity encoding
- **CSRF Protection** - Session-based validation
- **Password Security** - Hashed passwords (ready for implementation)

## 📱 Responsive Design

The admin panel is fully responsive and works on:
- **Desktop** (1920px and above)
- **Laptop** (1024px - 1920px)
- **Tablet** (768px - 1024px)
- **Mobile** (320px - 768px)

## 🎨 Customization

### Changing Colors

Edit `css/style.css` and modify the CSS variables:
```css
:root {
    --primary-light: #E6D9F5;
    --primary: #D4C5E8;
    --primary-dark: #B8A8D8;
    /* ... other colors ... */
}
```

### Adding New Pages

1. Create a new PHP file (e.g., `customers.php`)
2. Include the header: `require_once 'includes/header.php';`
3. Add your content
4. Include the footer: `require_once 'includes/footer.php';`
5. Add navigation link in `includes/header.php`

### Modifying the Sidebar

Edit `includes/header.php` to add/remove navigation items:
```php
<li>
    <a href="your-page.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'your-page.php' ? 'active' : ''; ?>">
        <i class="fas fa-icon"></i>
        <span>Your Page</span>
    </a>
</li>
```

## 📈 Analytics & Charts

The dashboard uses **Chart.js** for interactive analytics:
- **Daily Chart** - Bar chart showing daily bookings
- **Weekly Chart** - Line chart showing weekly trends
- **Monthly Chart** - Line chart showing monthly overview

Charts are responsive and automatically adjust to screen size.

## 🔧 JavaScript Utilities

Available functions in `js/main.js`:

- `openModal(modalId)` - Open a modal
- `closeModal(modalId)` - Close a modal
- `showAlert(message, type)` - Display alert messages
- `filterTable(inputId, tableId)` - Filter table by search input
- `deleteRow(id, type)` - Delete a row with confirmation
- `updateStatus(id, status, type)` - Update item status
- `createChart(canvasId, type, labels, data, label)` - Create a chart
- `formatCurrency(amount)` - Format numbers as currency
- `formatDate(date)` - Format dates

## 📝 Demo Data

The application comes with sample data for demonstration:
- 2 customers
- 3 pets
- 4 services
- 4 bookings
- 3 boarding records
- 4 gallery images
- 4 reviews

This data is stored in PHP arrays and can be easily replaced with database queries.

## 🐛 Known Limitations

- Demo version uses static data (not connected to database)
- Image uploads are validated but not persisted
- Forms are functional but don't save to database yet
- Some features show "coming soon" messages

## 📚 Future Enhancements

- [ ] Database integration for all CRUD operations
- [ ] Email notifications for bookings
- [ ] SMS reminders
- [ ] Payment processing
- [ ] Customer portal
- [ ] Advanced reporting
- [ ] Export to PDF/Excel
- [ ] Multi-admin support with roles
- [ ] Appointment scheduling
- [ ] Inventory management

## 🤝 Support

For issues or questions, please refer to the code comments or contact the development team.

## 📄 License

This project is provided as-is for AB Pet Grooming business use.

---

**Version**: 1.0.0  
**Last Updated**: March 2026  
**Built with**: HTML, PHP, CSS, JavaScript, Chart.js
