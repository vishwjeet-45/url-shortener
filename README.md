# URL Shortener – Laravel Backend Assignment

This project is created as part of the **Sembark Tech Backend Developer Assignment**.

It is a simple **URL Shortener system** built using **Laravel**. The main focus of this project is **role-based access**, **company-based data separation**, and **secure short URL handling**.

---

## Project Overview

The application allows managing short URLs with different user roles. The system supports multiple companies, and users belong to only one company.

Short URLs are **not publicly accessible directly** and always redirect to the original URL in a controlled way.

---

## Tech Stack

* Framework: Laravel 12
* Language: PHP 8.2
* Database: MySQL
* Authentication: Laravel Breeze
* Testing: Pest PHP

---

## User Roles

The system has the following roles:

* **SuperAdmin**
* **Admin**
* **Member**

Roles are used to control what a user can see and do in the system.

---

## Role Rules

| Role       | What they can see                           | Can create short URL |
| ---------- | ------------------------------------------- | -------------------- |
| SuperAdmin | All short URLs from all companies           | No                   |
| Admin      | Short URLs not created in their own company | No                   |
| Member     | Short URLs not created by themselves        | No                   |

No role is allowed to create short URLs as per assignment rules.

---

## Company Rules

* System supports multiple companies
* Each company can have multiple users
* A user belongs to only one company
* SuperAdmin does not belong to any company
* Data is filtered based on company and user role

---

## URL Behavior

* Short URLs are **not publicly visible** as plain data
* When a short URL is accessed, it redirects to the original URL
* Redirection works without login

---

## Features

* Role-based access control
* Company-based data filtering
* Secure short URL redirection
* CSV export of short URLs
* Date-based filtering of URLs
* Feature tests using Pest

---



## Setup Instructions

### Requirements

* PHP 8.2 or higher
* Composer
* Node.js & NPM
* MySQL

### Installation Steps

```bash
# Clone repository
git clone https://github.com/vishwjeet-45/url-shortener.git
cd url-shortener

# Install PHP packages
composer install

# Install frontend packages
npm install

# Create environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Update database details in .env file

# Run migrations
php artisan migrate

# Seed database (creates SuperAdmin)
php artisan db:seed

# Build frontend
npm run dev

# Start server
php artisan serve
```

Project will run at:

```
http://localhost:8000
```

---

## Default SuperAdmin Login

After seeding the database:

```
Email: superadmin@example.com
Password: password
```

---

## Testing

Feature tests are written using **Pest PHP**.

Run all tests:

```bash
php artisan test
```

Run only short URL tests:

```bash
php artisan test --filter=ShortUrlTest
```

Tests include:

* SuperAdmin cannot create short URLs
* Admin cannot create short URLs
* Member cannot create short URLs
* Admin visibility rules
* Member visibility rules
* Short URL redirection

---

## AI Tool Usage

AI tools were used only for learning and support purposes:

* GitHub Copilot was used for code autocomplete and syntax suggestions
* AI assistance was used to understand Laravel and Pest testing concepts

All logic, structure, and final implementation were written and understood by me.

---

## Author

**Vishwjeet**
GitHub: [https://github.com/vishwjeet-45](https://github.com/vishwjeet-45)


