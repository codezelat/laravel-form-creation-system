# 📋 Laravel Dynamic Form Builder System

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.1-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

A powerful, intuitive form builder system built with Laravel 12 that enables creation, management, and analysis of dynamic forms with advanced submission tracking and export capabilities.

[Features](#-features) • [Installation](#-installation) • [Usage](#-usage) • [Documentation](#-documentation) • [Contributing](#-contributing)

</div>

---

## 📖 Table of Contents

- [Overview](#-overview)
- [Key Features](#-features)
- [Technology Stack](#-technology-stack)
- [System Architecture](#-system-architecture)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage Guide](#-usage-guide)
- [API Reference](#-api-reference)
- [Security Features](#-security-features)
- [Advanced Features](#-advanced-features)
- [Database Schema](#-database-schema)
- [Troubleshooting](#-troubleshooting)
- [Testing](#-testing)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🎯 Overview

The **Laravel Dynamic Form Builder System** is a comprehensive solution for creating and managing custom forms with a drag-and-drop interface. Built for organizations that need to collect data efficiently, this system offers enterprise-grade features including real-time validation, spam protection, advanced analytics, and intelligent field matching for form evolution.

### Why Use This System?

- **🎨 Visual Form Builder**: Create complex forms without writing code
- **📊 Real-time Analytics**: Track submission patterns and insights
- **🔒 Security First**: Built-in CAPTCHA and spam protection
- **📈 Export Capabilities**: Generate Excel reports with one click
- **🔄 Backward Compatibility**: Intelligent field matching handles form modifications
- **⚡ Auto-save**: Never lose your work with automatic draft saving
- **📱 Responsive Design**: Perfect experience on all devices

---

## ✨ Features

### Form Building

- **🎨 Drag-and-Drop Interface**: Intuitive visual form builder with live preview
- **📝 Multiple Field Types**:
  - Short text input
  - Long text (textarea)
  - Dropdown select
  - Radio buttons
  - Checkboxes
  - File uploads
  - Email validation
  - Number input
  - Date picker
- **🎨 Theme Customization**: 6 color themes (Blue, Green, Purple, Red, Yellow, Indigo)
- **⚙️ Field Configuration**:
  - Required/optional fields
  - Custom labels
  - Option management for select/radio/checkbox
  - File type restrictions and size limits
  - Drag-to-reorder fields

### Form Management

- **📊 Dashboard Overview**: Real-time statistics and recent forms
- **🔍 Search & Filter**: Find forms quickly with advanced search
- **📤 Publish/Unpublish**: Control form visibility and access
- **🔗 Custom URLs**: SEO-friendly slugs for each form
- **🗑️ Safe Deletion**: Automatic cleanup of associated files and submissions
- **⏰ Auto-save**: Changes saved automatically every few seconds

### Submission Handling

- **🛡️ Cloudflare Turnstile**: Bot protection for all public forms
- **📥 File Upload Support**: Secure file storage with validation
- **🌐 IP Tracking**: Record submitter information for analytics
- **📸 Field Snapshots**: Maintain historical accuracy when forms change
- **🔍 Detailed View**: Examine individual submissions with full context

### Analytics & Reporting

- **📊 Submission Analytics**:
  - Total submissions count
  - Today's submissions
  - Weekly trends
  - Time-based charts
- **📋 Paginated Results**: Easy navigation through large datasets
- **🔍 Search Submissions**: Find specific responses quickly
- **📊 Excel Export**: One-click export with:
  - Professional formatting
  - Clickable file links
  - Auto-sized columns
  - Color-coded headers
  - Required field indicators

### Security Features

- **🔐 Admin Authentication**: Environment-based secure login
- **🛡️ CSRF Protection**: Laravel's built-in security
- **🤖 Bot Prevention**: Cloudflare Turnstile integration
- **🔒 Hidden Admin Panel**: Obscured URL path (`/hidden-admin`)
- **📝 Audit Trail**: IP and user agent logging
- **🚫 Status Controls**: Active/inactive form states

### Developer Features

- **🔄 Backward Compatibility**: Intelligent field matching for form modifications
- **🛠️ Artisan Commands**: CLI tools for maintenance
- **📦 Modular Architecture**: Clean, maintainable code structure
- **🎯 RESTful API**: JSON responses for all operations
- **🧪 Testing Ready**: PHPUnit configured and ready
- **📚 Well Documented**: Comprehensive inline documentation

---

## 🛠️ Technology Stack

### Backend

- **Laravel 12.0** - PHP framework with latest features
- **PHP 8.2+** - Modern PHP with improved performance
- **SQLite** - Default database (MySQL/PostgreSQL supported)
- **Doctrine DBAL** - Database abstraction layer
- **Maatwebsite Excel** - Excel export functionality

### Frontend

- **Tailwind CSS 4.1** - Utility-first CSS framework
- **Vite 7.0** - Lightning-fast frontend tooling
- **Alpine.js** (via Laravel) - Minimal JavaScript framework
- **Axios** - HTTP client for AJAX requests
- **SortableJS** - Drag-and-drop functionality

### Services & Tools

- **Cloudflare Turnstile** - Bot protection
- **Laravel Vite Plugin** - Asset bundling
- **Concurrently** - Development server management
- **Laravel Pail** - Real-time log viewing

### Development Tools

- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework
- **Faker** - Test data generation
- **Laravel Sail** - Docker development environment

---

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     User Interface Layer                      │
├─────────────────────────────────────────────────────────────┤
│  Public Forms        │     Admin Panel (Hidden URL)          │
│  - Form Display      │     - Dashboard                       │
│  - Submission        │     - Form Builder                    │
│  - Turnstile         │     - Analytics                       │
└──────────────┬───────┴─────────────────┬────────────────────┘
               │                         │
               ▼                         ▼
┌─────────────────────────────────────────────────────────────┐
│                     Controller Layer                          │
├─────────────────────────────────────────────────────────────┤
│  FormController      │     AdminAuthController               │
│  - CRUD Operations   │     - Authentication                  │
│  - Submissions       │     - Dashboard Data                  │
│  - File Handling     │     - Export Management               │
└──────────────┬───────┴─────────────────┬────────────────────┘
               │                         │
               ▼                         ▼
┌─────────────────────────────────────────────────────────────┐
│                      Service Layer                            │
├─────────────────────────────────────────────────────────────┤
│  TurnstileService    │     FormSubmissionsExport             │
│  - Bot Verification  │     - Excel Generation                │
│  - API Integration   │     - Data Formatting                 │
└──────────────┬───────┴─────────────────┬────────────────────┘
               │                         │
               ▼                         ▼
┌─────────────────────────────────────────────────────────────┐
│                       Model Layer                             │
├─────────────────────────────────────────────────────────────┤
│  Form Model          │  FormField Model  │  FormSubmission   │
│  - Relationships     │  - Validation     │  - Field Snapshot │
│  - Auto-slug         │  - Ordering       │  - Smart Matching │
└──────────────┬───────┴───────────────────┴────────────────────┘
               │
               ▼
┌─────────────────────────────────────────────────────────────┐
│                      Database Layer                           │
├─────────────────────────────────────────────────────────────┤
│  forms              │  form_fields      │  form_submissions  │
│  - Title, Slug      │  - Type, Label    │  - Data, Snapshot  │
│  - Status, Color    │  - Options        │  - Files, Metadata │
└─────────────────────────────────────────────────────────────┘
```

---

## 📦 Installation

### Prerequisites

Ensure your system meets these requirements:

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.0
- **NPM** or **Yarn**
- **SQLite** (or MySQL/PostgreSQL)
- **Git**

### Step-by-Step Installation

#### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-form-creation-system.git
cd laravel-form-creation-system
```

#### 2. Install PHP Dependencies

```bash
composer install
```

#### 3. Install Node Dependencies

```bash
npm install
```

#### 4. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 5. Configure Environment Variables

Edit `.env` file with your settings:

```dotenv
# Application
APP_NAME="Form Builder System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (SQLite by default)
DB_CONNECTION=sqlite

# Admin Credentials
ADMIN_USERNAME=admin
ADMIN_PASSWORD=your-secure-password

# Cloudflare Turnstile (Required for public forms)
TURNSTILE_SITE_KEY=your-site-key
TURNSTILE_SECRET_KEY=your-secret-key

# Session (Required for admin authentication)
SESSION_DRIVER=database
```

#### 6. Create Database

For SQLite (default):

```bash
touch database/database.sqlite
```

For MySQL:

```bash
# Create database manually
mysql -u root -p -e "CREATE DATABASE form_builder;"
```

#### 7. Run Migrations

```bash
php artisan migrate
```

#### 8. Create Storage Link

```bash
php artisan storage:link
```

#### 9. Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

#### 10. Start Development Server

```bash
# Option 1: Simple server
php artisan serve

# Option 2: Full development stack (recommended)
composer run dev
```

The application will be available at `http://localhost:8000`

---

## ⚙️ Configuration

### Cloudflare Turnstile Setup

Cloudflare Turnstile provides free bot protection:

1. Visit [Cloudflare Turnstile Dashboard](https://dash.cloudflare.com/?to=/:account/turnstile)
2. Create a new site
3. Copy your **Site Key** and **Secret Key**
4. Add to `.env`:

```dotenv
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA
```

### Admin Authentication

Set strong credentials in `.env`:

```dotenv
ADMIN_USERNAME=your-admin-username
ADMIN_PASSWORD=your-secure-password-here
```

**Important**: In production, use a strong password with:

- Minimum 12 characters
- Mix of uppercase and lowercase
- Numbers and special characters

### Database Configuration

#### SQLite (Default)

```dotenv
DB_CONNECTION=sqlite
```

#### MySQL

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=form_builder
DB_USERNAME=root
DB_PASSWORD=your-password
```

#### PostgreSQL

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=form_builder
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

### File Upload Configuration

Configure file upload limits in `php.ini`:

```ini
upload_max_filesize = 10M
post_max_size = 10M
```

Or in `.env` for Laravel Valet:

```dotenv
UPLOAD_MAX_FILESIZE=10485760
```

### Queue Configuration

For better performance with file uploads:

```dotenv
QUEUE_CONNECTION=database
```

Run queue worker:

```bash
php artisan queue:work
```

---

## 📚 Usage Guide

### Accessing the Admin Panel

1. Navigate to `http://localhost:8000/hidden-admin`
2. Login with credentials from `.env`
3. You'll be redirected to the dashboard

### Creating Your First Form

#### Step 1: Navigate to Form Builder

- Click **"Create New Form"** on the dashboard
- Or go to `/hidden-admin/forms/create`

#### Step 2: Configure Form Settings

- **Title**: Enter a descriptive form title
- **Description**: Add optional instructions
- **Theme Color**: Choose from 6 color schemes

#### Step 3: Add Fields

Click field types from the sidebar:

- **Short Text**: Single-line input
- **Long Text**: Multi-line textarea
- **Dropdown**: Select from options
- **Radio Buttons**: Single choice from options
- **Checkboxes**: Multiple selections
- **File Upload**: Document/image uploads
- **Email**: Validated email input
- **Number**: Numeric input only
- **Date**: Date picker

#### Step 4: Configure Each Field

- **Label**: Field name visible to users
- **Required**: Toggle mandatory fields
- **Options**: For dropdown/radio/checkbox (one per line)
- **File Settings**: For file uploads:
  - Accepted types (comma-separated)
  - Maximum size in MB

#### Step 5: Reorder Fields

- Drag and drop fields to reorder
- Use the drag handle on the left

#### Step 6: Save and Publish

- Changes auto-save as drafts
- Click **"Publish Form"** when ready
- Choose form status:
  - **Active**: Accepts submissions
  - **Inactive**: Visible but locked
- Optionally customize the URL slug

### Managing Forms

#### View All Forms

Navigate to `/hidden-admin/forms`:

- See all forms with submission counts
- Search by title or description
- Quick actions: View, Analytics, Export, Delete

#### Edit Form

- Click form title or edit icon
- Modify fields and settings
- Changes save automatically
- Re-publish to update live version

#### View Analytics

Click **"Analytics"** on any form:

- **Metrics**: Total, today, and weekly submissions
- **Submission List**: Paginated with search
- **Individual View**: Click any submission for details

#### Export Submissions

- Click **"Export"** button in analytics
- Downloads formatted Excel file with:
  - All submission data
  - Clickable file links
  - Professional styling
  - Timestamp information

### Viewing Submissions

#### Submission List

- Shows recent submissions
- Displays preview of responses
- Click to view full details

#### Submission Details

- Complete response data
- Field labels and values
- Downloadable file attachments
- Submitter metadata (IP, user agent)
- Submission timestamp

### Form Statuses

#### Draft

- Form is being created/edited
- Not publicly accessible
- Can be edited freely

#### Published - Active

- Live and accepting submissions
- Publicly accessible via URL
- Protected by Turnstile

#### Published - Inactive

- Visible but not accepting submissions
- Shows "locked" message to users
- Admins can still view

### Public Form Submissions

#### User Experience

1. User visits form URL (e.g., `/form/contact-us-abc123`)
2. Sees branded form with SITC styling
3. Fills out required fields
4. Completes Turnstile verification
5. Submits form
6. Sees success message

#### File Uploads

- Files stored in `/storage/app/public/submissions/{form_id}/{submission_id}/`
- Validated against allowed types and size
- Unique filenames prevent conflicts

---

## 🔌 API Reference

### Admin Routes

All admin routes require authentication and are prefixed with `/hidden-admin`.

#### Authentication

```http
POST /hidden-admin
Content-Type: application/x-www-form-urlencoded

username=admin&password=secret
```

#### Create/Update Form

```http
POST /hidden-admin/forms/store
Content-Type: application/json

{
  "id": null,
  "title": "Contact Form",
  "description": "Get in touch with us",
  "color": "blue",
  "fields": [
    {
      "type": "text",
      "label": "Full Name",
      "required": true
    },
    {
      "type": "email",
      "label": "Email Address",
      "required": true
    }
  ]
}
```

**Response:**

```json
{
  "success": true,
  "form_id": 1,
  "message": "Form saved successfully"
}
```

#### Publish Form

```http
POST /hidden-admin/forms/{id}/publish
Content-Type: application/json

{
  "slug": "contact-us",
  "form_status": "active"
}
```

**Response:**

```json
{
  "success": true,
  "slug": "contact-us-abc123",
  "url": "http://localhost:8000/form/contact-us-abc123"
}
```

#### Get Form Data

```http
GET /hidden-admin/forms/{id}/data
```

**Response:**

```json
{
  "success": true,
  "form": {
    "id": 1,
    "title": "Contact Form",
    "fields": [...]
  }
}
```

#### Delete Form

```http
DELETE /hidden-admin/forms/{id}
```

### Public Routes

#### View Form

```http
GET /form/{slug}
```

#### Submit Form

```http
POST /form/{slug}/submit
Content-Type: multipart/form-data

field_1=John+Doe&
field_2=john@example.com&
cf-turnstile-response=token
```

**Response:**

```json
{
  "success": true,
  "message": "Form submitted successfully!"
}
```

---

## 🔒 Security Features

### Authentication

- **Session-based** admin authentication
- **Environment variables** for credentials
- **Hidden admin URL** to prevent discovery
- **CSRF protection** on all forms

### Bot Protection

- **Cloudflare Turnstile** on all public forms
- Server-side token verification
- IP address logging
- Rate limiting capable

### File Upload Security

- Type validation (whitelist approach)
- Size restrictions per field
- Unique storage paths
- Automatic cleanup on deletion

### Data Protection

- **SQL injection prevention** via Eloquent ORM
- **XSS protection** via Blade templating
- **Mass assignment protection** via `$fillable`
- **Input validation** on all endpoints

### Best Practices

- Environment-based configuration
- Secure session handling
- Regular security updates
- Error logging without exposure

---

## 🚀 Advanced Features

### Field Snapshot System

The most sophisticated feature enabling backward compatibility:

#### The Problem

When a form is modified after receiving submissions:

- Field IDs change
- Field order changes
- Fields are added/removed
- Old submissions become unreadable

#### The Solution

**Field Snapshots** - Every submission stores the form structure at submission time:

```php
{
  "field_snapshot": [
    {
      "id": 1,
      "type": "text",
      "label": "Full Name",
      "order": 0,
      "required": true
    },
    {
      "id": 2,
      "type": "email",
      "label": "Email",
      "order": 1,
      "required": true
    }
  ]
}
```

#### Intelligent Matching Algorithm

When displaying old submissions, the system uses a scoring algorithm:

```php
Score Components:
- Label Match (60%): Exact or similar text
- Type Match (20%): Same field type
- Position Match (10%): Same order in form
- Required Status (5%): Same required flag
- Options Similarity (5%): For select/radio/checkbox
```

**Example**: If "Full Name" field is later renamed to "Name", the system:

1. Calculates similarity: 75% match
2. Confidence threshold: 50%
3. **Result**: Successfully matches and displays data

#### Backfill Command

For existing installations without snapshots:

```bash
# Preview changes
php artisan submissions:backfill-snapshots --dry-run

# Apply changes
php artisan submissions:backfill-snapshots
```

### Auto-Save System

Forms save automatically while building:

- **Frequency**: Every 5 seconds of inactivity
- **Status**: Visual indicator shows save state
- **Draft Mode**: All changes saved as drafts
- **No Data Loss**: Continue where you left off

### Excel Export Features

Professional exports with:

- **Formatted Headers**: Bold, colored background
- **Auto-sized Columns**: Perfect width for content
- **Clickable Links**: File uploads become hyperlinks
- **Required Indicators**: Asterisk (\*) on required fields
- **Metadata**: Submission ID, date, time, IP, user agent
- **Custom Styling**: Professional appearance

### Form Status Management

Three-tier status system:

```
Draft → Published (Active) → Published (Inactive)
  ↓           ↓                      ↓
Hidden    Accepting            Visible but
          Submissions          Locked
```

Benefits:

- **Draft**: Work in progress, private
- **Active**: Fully operational
- **Inactive**: Temporarily disable without unpublishing

---

## 💾 Database Schema

### Tables Overview

```sql
┌──────────────────┐         ┌──────────────────┐         ┌───────────────────┐
│     forms        │         │   form_fields    │         │ form_submissions  │
├──────────────────┤         ├──────────────────┤         ├───────────────────┤
│ id               │────┐    │ id               │     ┌───│ id                │
│ title            │    │    │ form_id          │────┐│   │ form_id           │
│ description      │    └───→│ type             │    ││   │ submission_data   │
│ color            │         │ label            │    ││   │ field_snapshot    │
│ slug (unique)    │         │ required         │    ││   │ files             │
│ status           │         │ options          │    ││   │ ip_address        │
│ form_status      │         │ file_settings    │    ││   │ user_agent        │
│ settings         │         │ order            │    ││   │ submitted_at      │
│ timestamps       │         │ timestamps       │    ││   │ timestamps        │
└──────────────────┘         └──────────────────┘    ││   └───────────────────┘
                                   ▲                  ││
                                   └──────────────────┘│
                                                       │
                                   Form has many ─────┘
                                   submissions
```

### Detailed Schema

#### `forms` Table

| Column        | Type         | Description                     |
| ------------- | ------------ | ------------------------------- |
| `id`          | bigint       | Primary key                     |
| `title`       | varchar(255) | Form display name               |
| `description` | text         | Optional form instructions      |
| `color`       | varchar(50)  | Theme color (blue, green, etc.) |
| `slug`        | varchar(255) | Unique URL identifier           |
| `status`      | enum         | draft or published              |
| `form_status` | enum         | active or inactive              |
| `settings`    | json         | Additional configuration        |
| `created_at`  | timestamp    | Creation date                   |
| `updated_at`  | timestamp    | Last modified date              |

#### `form_fields` Table

| Column          | Type         | Description                       |
| --------------- | ------------ | --------------------------------- |
| `id`            | bigint       | Primary key                       |
| `form_id`       | bigint       | Foreign key to forms              |
| `type`          | varchar(50)  | Field type (text, email, etc.)    |
| `label`         | varchar(255) | Field display name                |
| `required`      | boolean      | Mandatory field flag              |
| `options`       | json         | Options for select/radio/checkbox |
| `file_settings` | json         | Upload restrictions               |
| `order`         | integer      | Display position                  |
| `created_at`    | timestamp    | Creation date                     |
| `updated_at`    | timestamp    | Last modified date                |

#### `form_submissions` Table

| Column            | Type        | Description                  |
| ----------------- | ----------- | ---------------------------- |
| `id`              | bigint      | Primary key                  |
| `form_id`         | bigint      | Foreign key to forms         |
| `submission_data` | json        | All field responses          |
| `field_snapshot`  | json        | Form structure at submission |
| `files`           | json        | Uploaded file paths          |
| `ip_address`      | varchar(45) | Submitter IP                 |
| `user_agent`      | text        | Browser information          |
| `submitted_at`    | timestamp   | Submission date/time         |
| `created_at`      | timestamp   | Record creation              |
| `updated_at`      | timestamp   | Last modified                |

### Relationships

```php
// Form Model
public function fields(): HasMany
public function submissions(): HasMany

// FormField Model
public function form(): BelongsTo

// FormSubmission Model
public function form(): BelongsTo
```

---

## 🐛 Troubleshooting

### Common Issues

#### Issue: "Target class [AdminAuth] does not exist"

**Solution**: Clear configuration cache

```bash
php artisan config:clear
php artisan cache:clear
```

#### Issue: "Vite manifest not found"

**Solution**: Build frontend assets

```bash
npm run build
```

#### Issue: "Storage directory not found"

**Solution**: Create storage link

```bash
php artisan storage:link
chmod -R 775 storage
```

#### Issue: Admin login not working

**Solution**: Check environment variables

```bash
# Verify .env has correct values
grep "ADMIN_" .env

# Clear session data
php artisan session:flush
```

#### Issue: Turnstile verification fails

**Solution**:

1. Verify keys in `.env` are correct
2. Check domain is allowed in Cloudflare dashboard
3. Test with different IP (some IPs may be blocked)
4. Check logs: `storage/logs/laravel.log`

#### Issue: File uploads failing

**Solution**:

```bash
# Check permissions
chmod -R 775 storage/app/public

# Verify storage link exists
ls -la public/storage

# Check PHP upload limits
php -i | grep upload_max_filesize
```

#### Issue: Database connection error

**Solution**:

```bash
# For SQLite
touch database/database.sqlite
chmod 664 database/database.sqlite

# For MySQL
php artisan migrate:fresh
```

### Debug Mode

Enable detailed error messages:

```dotenv
APP_DEBUG=true
LOG_LEVEL=debug
```

View real-time logs:

```bash
php artisan pail
```

### Performance Issues

#### Optimize Application

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Clear All Caches

```bash
php artisan optimize:clear
```

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter FormControllerTest

# Run with coverage
php artisan test --coverage
```

### Creating Tests

```bash
# Generate test class
php artisan make:test FormSubmissionTest
```

Example test:

```php
public function test_form_can_be_created()
{
    $response = $this->post('/hidden-admin/forms/store', [
        'title' => 'Test Form',
        'color' => 'blue',
        'fields' => [
            ['type' => 'text', 'label' => 'Name', 'required' => true]
        ]
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('forms', ['title' => 'Test Form']);
}
```

---

## 🤝 Contributing

We welcome contributions! Here's how you can help:

### Getting Started

1. **Fork** the repository
2. **Clone** your fork

```bash
git clone https://github.com/yourusername/laravel-form-creation-system.git
```

3. **Create** a feature branch

```bash
git checkout -b feature/amazing-feature
```

4. **Commit** your changes

```bash
git commit -m 'Add amazing feature'
```

5. **Push** to the branch

```bash
git push origin feature/amazing-feature
```

6. **Open** a Pull Request

### Coding Standards

- Follow **PSR-12** coding standard
- Use **Laravel best practices**
- Write **meaningful commit messages**
- Add **tests** for new features
- Update **documentation** as needed

#### Run Code Style Fixer

```bash
./vendor/bin/pint
```

### Areas for Contribution

- 🐛 Bug fixes
- ✨ New field types
- 🌐 Translations
- 📝 Documentation improvements
- 🎨 UI/UX enhancements
- ⚡ Performance optimizations
- 🧪 Additional tests

### Pull Request Guidelines

- Clearly describe the problem and solution
- Include relevant issue numbers
- Update README if needed
- Ensure tests pass
- Follow existing code style

---

## 📄 License

This project is licensed under the **MIT License** - see the LICENSE file for details.

```
MIT License

Copyright (c) 2025 Laravel Form Builder System

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions...
```

---

## 👥 Authors & Acknowledgments

### Core Team

- **Codezela Technologies** - System architecture and implementation

### Acknowledgments

- **Laravel Team** - For the amazing framework
- **Cloudflare** - For Turnstile bot protection
- **Tailwind CSS** - For the utility-first CSS framework
- **SortableJS** - For drag-and-drop functionality
- **Open Source Community** - For inspiration and tools

---

## 📞 Support & Contact

### Documentation

- **Issues**: [GitHub Issues](https://github.com/yourusername/laravel-form-creation-system/issues)
- **Discussions**: [GitHub Discussions](https://github.com/yourusername/laravel-form-creation-system/discussions)

---

## 🌟 Star History

If this project helped you, please consider giving it a ⭐️!

---

## 📊 Project Status

![Build Status](https://img.shields.io/badge/build-passing-brightgreen)
![Maintained](https://img.shields.io/badge/maintained-yes-brightgreen)
![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen)

**Current Version**: 1.0.0  
**Last Updated**: December 2025  

---

<div align="center">

**Built with ❤️ using Laravel**

[⬆ Back to Top](#-laravel-dynamic-form-builder-system)

</div>
