# FAYdev Labs Landing Page

A personal portfolio landing page for FAYdev Labs. This website presents FAY as a Product Builder and Full-Stack Developer, featuring selected work, product categories, thinking process, and build-in-public journey links.

## Overview

This project is a single-page website built with PHP, CSS, and JavaScript without a framework or build process. It focuses on fast deployment, a modern visual style, lightweight performance, and an easy-to-understand structure.

## Features

- Hero section with primary CTAs
- Featured project showcase
- Product category cards
- About FAY section
- Social cards for GitHub, LinkedIn, Instagram, and Threads
- Responsive navigation
- Lightweight JavaScript-based animations
- Separate favicon and image assets
- `robots.txt` and `sitemap.xml`

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
├── docs/
│   └── PRD.md
├── partials/
│   ├── footer.php
│   └── header.php
├── index.php
├── robots.txt
└── sitemap.xml
```

## Tech Stack

- PHP
- HTML
- CSS
- JavaScript
- Font Awesome
- Google Fonts
- Apache/XAMPP or PHP hosting

## Running Locally

### Option 1: XAMPP

1. Place the project folder inside the `htdocs` directory.
2. Start Apache from the XAMPP Control Panel.
3. Open the project in your browser using a local URL, for example:

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
- `partials/header.php` — initial HTML structure, metadata, favicon, stylesheet, and navigation
- `partials/footer.php` — footer and script loading
- `assets/css/styles.css` — main styling
- `assets/js/script.js` — frontend interactions and animations
- `docs/PRD.md` — project product requirements document

## Development Notes

- No dependency manager is used.
- No build process is required.
- External assets are loaded directly through CDN.
- Some project content still uses placeholders and can be replaced with real projects.
- `README.md` is the main project documentation.

## Roadmap

- Replace project placeholders with real screenshots and links
- Add a project archive
- Add a what's next section
- Improve SEO further
- Add multi-language support
- Add a dark/light toggle