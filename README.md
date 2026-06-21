# FAYdev Labs Landing Page

A personal portfolio landing page for FAYdev Labs. The site presents FAY as a Product Builder and Full-Stack Developer, featuring selected work, product categories, contact flow, and build-in-public links.

## Overview

This project is a PHP-based landing page with lightweight CSS and JavaScript. It does not use a frontend framework or build process, but it now includes server-side configuration, PHPMailer email replies, database persistence for incoming contact messages, and Cloudinary helper utilities for image uploads.

## Features

- Hero section with primary CTAs
- Featured project showcase and project detail pages
- Product category cards
- About FAY section
- Contact form with reason-based confirmation replies
- Incoming contact messages saved to a MySQL `emails` table before sending SMTP replies
- Dark/light theme support, including improved dark theme styling for the contact section
- Social cards for GitHub, LinkedIn, Instagram, and Threads
- Responsive navigation
- Lightweight JavaScript-based interactions and animations
- Environment-based app, database, email, and Cloudinary configuration
- `robots.txt` and `sitemap.xml`

## Recent Branch Updates

This branch adds and updates the following areas:

- Added `.env.example` entries for app settings, database credentials, email sender, Cloudinary credentials, and base path.
- Updated `config/app.php` to load `.env`, define `ROOT_PATH`, and expose app constants.
- Added `config/database.php` with a PDO singleton connection for MySQL.
- Added `includes/emails.php` to validate and save incoming contact form messages.
- Updated `send-email.php` so contact messages must be saved to the database before the SMTP reply is sent.
- Added `config/cloudinary.php` with upload and delete helpers for Cloudinary images.
- Improved dark theme colors for the contact copy, form, fields, notes, and select options.

## Project Structure

```text
.
├── assets/
│   ├── css/
│   │   └── styles.css
│   ├── favicon/
│   ├── images/
│   └── js/
│       └── script.js
├── config/
│   ├── app.php
│   ├── cloudinary.php
│   └── database.php
├── includes/
│   ├── emails.php
│   ├── Markdown.php
│   └── projects.php
├── partials/
│   ├── footer.php
│   └── header.php
├── index.php
├── project.php
├── projects.php
├── send-email.php
├── robots.txt
└── sitemap.xml
```

## Tech Stack

- PHP
- MySQL / MariaDB via PDO
- PHPMailer
- HTML
- CSS
- JavaScript
- Font Awesome
- Google Fonts
- Cloudinary Upload API helper
- Apache/XAMPP or PHP hosting

## Requirements

- PHP 8+
- Composer
- MySQL or MariaDB
- PHP extensions: `pdo_mysql`, `curl`
- SMTP mailbox credentials for outgoing confirmation emails

## Environment Setup

1. Copy the example environment file:

```bash
cp .env.example .env
```

2. Update `.env` with local or production values:

```env
APP_NAME="FAY Portfolio"
APP_URL="http://localhost/faydev/faylabs-dashboard"
APP_ENV="development"
BASE_PATH=/faydev/faylabs-dashboard

DB_HOST=localhost
DB_NAME=faylabs_dashboard
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4

EMAIL_PASSWORD=email_password
MAIL_FROM_ADDRESS=admin@faylabs.my.id

CLOUDINARY_CLOUD_NAME=name
CLOUDINARY_API_KEY=key
CLOUDINARY_API_SECRET=secret
CLOUDINARY_FOLDER=portfolio
```

3. Install PHP dependencies:

```bash
composer install
```

## Database Setup

Create a database matching `DB_NAME`, then create the `emails` table used by the contact form:

```sql
CREATE TABLE emails (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sender_name VARCHAR(255) NULL,
  sender_email VARCHAR(255) NOT NULL,
  recipient_email VARCHAR(255) NOT NULL,
  subject VARCHAR(255) NOT NULL,
  body TEXT NOT NULL,
  direction ENUM('incoming', 'outgoing') NOT NULL DEFAULT 'incoming',
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  sent_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

The contact form calls `saveIncomingEmail()` before sending a confirmation email. If the message cannot be saved, the user is redirected with `status=failed` and no SMTP reply is sent.

## Running Locally

### Option 1: XAMPP

1. Place the project folder inside the `htdocs` directory.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Create and configure the database listed in `.env`.
4. Open the project in your browser using a local URL, for example:

```text
http://localhost/faylabs-landing-page/
```

### Option 2: PHP Built-in Server

Run this command from the project root:

```bash
php -S localhost:8000
```

Then open:

```text
http://localhost:8000
```

## Important Files

- `index.php` — main landing page content
- `projects.php` — project listing page
- `project.php` — project detail page
- `partials/header.php` — initial HTML structure, metadata, favicon, stylesheet, and navigation
- `partials/footer.php` — footer and script loading
- `assets/css/styles.css` — main styling and theme rules
- `assets/js/script.js` — frontend interactions and animations
- `config/app.php` — `.env` loader and app constants
- `config/database.php` — MySQL PDO connection
- `config/cloudinary.php` — Cloudinary upload/delete helper
- `includes/emails.php` — contact email persistence helpers
- `send-email.php` — contact form validation, database save, and SMTP confirmation email

## Development Notes

- No frontend dependency manager or build process is required.
- PHP dependencies are managed with Composer.
- External frontend assets are loaded directly through CDN.
- Keep secrets in `.env`; do not commit production credentials.
- Contact form persistence depends on the `emails` table being available.
- `README.md` is the main project documentation.

## Roadmap

- Replace project placeholders with real screenshots and links
- Add migrations or SQL setup files for database tables
- Add an admin view for incoming contact messages
- Add project image upload flows backed by Cloudinary
- Improve SEO further
- Add multi-language support
