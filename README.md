# ToDo List Application

A comprehensive task management application built with Laravel 12, featuring a modern admin panel with Filament, RESTful API endpoints, and a beautiful frontend interface.

## 🚀 Features

### Core Functionality
- **User Authentication** - Secure login/registration system
- **Task Management** - Create, edit, delete, and organize tasks
- **Task Categories** - Organize tasks by categories with color coding
- **Priority Levels** - Set task priorities (Low, Medium, High, Critical)
- **Status Tracking** - Track task progress (Pending, In Progress, Completed, Cancelled)
- **Task Assignment** - Assign tasks to team members
- **Due Date Management** - Set and track task deadlines
- **Comments System** - Add comments and discussions to tasks
- **Real-time Updates** - AJAX-powered status updates

### Admin Panel (Filament)
- **User Management** - Manage users and their permissions
- **Task Administration** - Full CRUD operations for tasks
- **Category Management** - Create and manage task categories
- **Priority Management** - Configure priority levels
- **Status Management** - Manage task statuses
- **Comment Moderation** - Review and manage task comments
- **Advanced Filtering** - Powerful search and filter capabilities
- **Bulk Operations** - Perform actions on multiple items

### API Endpoints
- **Authentication API** - Login, register, logout, user info
- **Tasks API** - Full CRUD operations with filtering
- **Categories API** - Category management
- **Comments API** - Comment management
- **Statistics API** - Task statistics and analytics

### Frontend Features
- **Responsive Design** - Works on desktop, tablet, and mobile
- **Modern UI** - Bootstrap 5 with FontAwesome 6 icons
- **Interactive Elements** - jQuery-powered dynamic updates
- **Search & Filter** - Advanced filtering and search capabilities
- **Real-time Updates** - AJAX-powered status changes
- **User-friendly Interface** - Intuitive navigation and design

## 🛠 Technology Stack

- **Backend**: Laravel 12
- **Admin Panel**: Filament 3
- **Frontend**: Blade templates, Bootstrap 5, jQuery, FontAwesome 6
- **Database**: MySQL (configured by default)
- **API**: RESTful API with Laravel Sanctum
- **Authentication**: Laravel's built-in authentication
- **Styling**: Bootstrap 5 with custom CSS
- **Icons**: FontAwesome 6

## 📋 Prerequisites

Before running this project, make sure you have the following installed:

- **PHP** 8.2 or higher
- **Composer** 2.0 or higher
- **Node.js** 16 or higher (for asset compilation)
- **Git**

## 🚀 Installation & Setup

### Option 1: Automated Setup (Recommended)

#### For Linux/Mac:
```bash
git clone <repository-url>
cd todo
chmod +x setup.sh
./setup.sh
```

#### For Windows:
```bash
git clone <repository-url>
cd todo
setup.bat
```

### Option 2: Manual Setup

### 1. Clone the Repository

```bash
git clone <repository-url>
cd todo
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

The application is configured to use MySQL by default. You'll need to configure your database settings in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_app
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Create Database

```bash
# Create MySQL database
mysql -u root -p -e "CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed
```

### 5. Install Filament Admin Panel

```bash
php artisan filament:install --panels
php artisan vendor:publish --tag=filament-config
```

### 6. Create Admin User

```bash
php artisan make:filament-user
```

Follow the prompts to create your admin user.

### 7. Install Additional Packages

```bash
# Install image processing package
composer require intervention/image

# Install Flatpickr for date/time picker
composer require coolsam/flatpickr:^3.2
php artisan vendor:publish --tag="coolsam-flatpickr-config"

# Install TinyMCE editor
composer require mohamedsabil83/filament-forms-tinyeditor
php artisan vendor:publish --tag="filament-forms-tinyeditor-config"
```

### 8. Set Up File Storage

```bash
# Create storage link
php artisan storage:link

# Set proper permissions (Linux/Mac only)
chmod -R 775 storage bootstrap/cache
```

### 9. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

**Note**: The automated setup scripts will handle all these steps for you, including database configuration, package installation, and admin user creation.

## 📱 Usage

### Frontend Access

1. **Home Page**: `http://localhost:8000`
   - View application features and benefits
   - Access login/registration

2. **User Registration**: `http://localhost:8000/register`
   - Create a new user account

3. **User Login**: `http://localhost:8000/login`
   - Access your account

4. **Task Dashboard**: `http://localhost:8000/todos`
   - View, create, and manage your tasks
   - Filter and search tasks
   - Add comments to tasks

### Admin Panel Access

1. **Admin Login**: `http://localhost:8000/admin`
   - Use the admin credentials created during setup

2. **Admin Features**:
   - User Management
   - Task Administration
   - Category Management
   - Priority Management
   - Status Management
   - Comment Moderation

### API Access

The API is available at `/api` endpoints:

- **Authentication**: `/api/auth/*`
- **Tasks**: `/api/todos/*`
- **Categories**: `/api/categories/*`
- **Comments**: `/api/comments/*`

## 🗄 Database Structure

### Tables

- **users** - User accounts and authentication
- **todos** - Task information and relationships
- **categories** - Task categories with color coding
- **priorities** - Priority levels with hierarchy
- **statuses** - Task status definitions
- **comments** - Task comments and discussions

### Relationships

- Users can create and be assigned tasks
- Tasks belong to categories, priorities, and statuses
- Comments are associated with tasks and users
- All relationships are properly indexed for performance

## 🔧 Configuration

### Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="ToDo List"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration (SQLite by default)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite

FILAMENT_PATH=admin
```

**Note**: The setup scripts automatically configure SQLite. To use MySQL or PostgreSQL instead, update the database configuration in `.env`:

```env
# For MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_app
DB_USERNAME=your_username
DB_PASSWORD=your_password

# For PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=todo_app
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Customization

1. **Styling**: Modify `resources/views/layouts/app.blade.php`
2. **Admin Panel**: Customize Filament resources in `app/Filament/Resources/`
3. **API**: Modify controllers in `app/Http/Controllers/Api/`
4. **Database**: Edit migrations in `database/migrations/`

## 🧪 Testing

### Run Tests

```bash
php artisan test
```

### API Testing

Use tools like Postman or curl to test API endpoints:

```bash
# Example: Get user's tasks
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/todos
```

## 📊 Performance Optimization

### Database Optimization

- All foreign keys are properly indexed
- Eager loading implemented for relationships
- Query optimization for large datasets

### Frontend Optimization

- Minified CSS and JavaScript
- Optimized images and assets
- CDN-ready asset loading

## 🔒 Security Features

- CSRF protection on all forms
- Input validation and sanitization
- User authorization checks
- API authentication with Sanctum
- SQL injection prevention
- XSS protection

## 🚀 Deployment

### Production Setup

1. **Environment Configuration**:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Database Migration**:
   ```bash
   php artisan migrate --force
   ```

3. **Cache Configuration**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **File Permissions**:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

### Server Requirements

- PHP 8.2+
- SQLite 3 (included with PHP)
- Web server (Apache/Nginx)
- SSL certificate (recommended)

**Note**: This application is configured to use SQLite by default, which requires no additional database server setup. For production deployments, you can switch to MySQL or PostgreSQL by updating the database configuration in `.env`.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## 📝 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🆘 Support

For support and questions:

1. Check the documentation
2. Review existing issues
3. Create a new issue with detailed information

## 📈 Roadmap

- [ ] Mobile app development
- [ ] Real-time notifications
- [ ] Advanced reporting
- [ ] Team collaboration features
- [ ] Calendar integration
- [ ] Email notifications
- [ ] File attachments
- [ ] Time tracking

## 🎯 Quick Start Commands

### Using Automated Setup (Recommended)
```bash
# Complete setup in one go
git clone <repository-url> && cd todo
./setup.sh  # Linux/Mac
# OR
setup.bat   # Windows
```

### Manual Setup
```bash
# Complete manual setup
git clone <repository-url> && cd todo
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan filament:install --panels
php artisan make:filament-user
php artisan serve
```

Visit `http://localhost:8000` to start using the application!
