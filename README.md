# Academic Appointment Management API

##  About the Project

This repository contains the backend REST API of a web-based academic management system developed as part of a diploma thesis.

The system supports the management of academic roles such as Year Lead Professors (PPA) and Teaching Assistant Students (AA) at Universidad Central “Marta Abreu” de Las Villas (UCLV).

It centralizes information, reduces manual administrative work, improves traceability, and supports the generation and management of official academic resolutions.

---

##  Purpose

The main goal of this system is to optimize the academic appointment process by:

* Standardizing data collection
* Reducing errors in manual workflows
* Improving document generation efficiency
* Enabling historical tracking of appointments
* Supporting institutional decision-making

---

##  Main Features

*  Authentication and role-based access
*  User management
*  Management of Year Lead Professors (PPA)
*  Management of Teaching Assistant Students (AA)
*  Appointment, ratification, and removal workflows
*  Generation and management of official documents
*  Historical records and traceability
*  Search and filtering functionality
*  Audit logging of system actions

---

##  User Roles

* Department Head
* Teaching Vice Dean
* Dean

Each role has specific permissions aligned with institutional processes.

---

##  Technologies Used

* PHP 8+
* Laravel 11
* REST API architecture
* MySQL (InnoDB)
* Laravel Eloquent ORM
* Laravel Sanctum (Authentication)
* Database migrations and seeders

---

##  System Architecture

This API is part of a client-server architecture:

* **Backend:** Laravel REST API (this repository)
* **Frontend:** Vue.js application
* **Database:** MySQL

The API handles business logic, validation, authentication, and data persistence, while the frontend consumes the services.

---

##  Project Structure

```txt
app/
routes/
database/
config/
storage/
```

---

##  API Overview

### Authentication

* POST `/api/login`
* POST `/api/logout`
* GET `/api/user`

---

### PPA Management

* GET `/api/ppa`
* POST `/api/ppa`
* PUT `/api/ppa/{id}`
* DELETE `/api/ppa/{id}`

---

### AA Management

* GET `/api/aa`
* POST `/api/aa`
* PUT `/api/aa/{id}`
* DELETE `/api/aa/{id}`

---

### Documents

* GET `/api/documents`
* POST `/api/documents/generate`
* GET `/api/documents/{id}`

---

### Search & History

* GET `/api/search`
* GET `/api/history`

---

##  Access Control

* Public access: none
* Authenticated users: access based on role
* Role-based permissions enforced at backend level

---

##  Installation

Clone the repository:

```bash
git clone https://github.com/dayanarojasdrp/api-laravel.git
cd api-laravel
```

Install dependencies:

```bash
composer install
```

Copy environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Configure database in `.env`, then run:

```bash
php artisan migrate
```

(Optional) Seed database:

```bash
php artisan db:seed
```

Start the server:

```bash
php artisan serve
```

---

##  Environment Variables

Configure at least:

* DB_DATABASE
* DB_USERNAME
* DB_PASSWORD
* APP_URL

---

##  Testing & Validation

The system was validated using:

* Functional testing (black-box testing)
* Test data generated through seeders
* Real workflow simulation based on institutional processes

---

##  Future Improvements

* Integration with institutional authentication systems
* Advanced reporting and analytics
* Improved document generation formats
* API performance optimization
* Automated testing coverage
* Deployment configuration

---

##  Thesis Context

This API is part of a diploma thesis focused on improving academic management processes at UCLV.

The system addresses issues such as:

* Information dispersion
* Lack of traceability
* Manual document workflows
* Inefficient administrative processes

---

##  Author

Developed by Dayana Rojas

