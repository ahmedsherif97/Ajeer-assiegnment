# Ajeer Technical Assessment — Multi-Gateway & Services Demo

This repository contains the completion of the Senior Laravel Developer technical assessment. The project is a unified, scalable Laravel monolith divided into two primary domains: a **Multi-Gateway Payment Demo** (Task 1) and a **Maintenance Services Booking System** (Task 2).

## 🏗 Architecture & Design Patterns

To ensure high-traffic readiness, separation of concerns, and maintainability, this project implements several key architectural decisions:

* **Strategy & Factory Patterns (Task 1):** Payment gateways are implemented using a common `PaymentGatewayInterface` and resolved dynamically via a `GatewayFactory`. Adding a new gateway requires zero changes to the core processing logic—simply create the new class and register it.
* **Config-Driven Availability:** Gateway availability (by City and Module) is evaluated via database relationships and heavily cached using `Cache::remember` to reduce database load during transaction processing.
* **Polymorphic Relationships (Task 2):** The Cart and Booking systems utilize polymorphic relationships (`purchasable` / `bookable`) to seamlessly handle both standalone Maintenance Services and bundled Packages without schema duplication.
* **Service Classes:** Fat controllers are avoided. Business logic (Subscription validation, Cart aggregation, Booking fulfillment via DB Transactions) is abstracted into dedicated Service classes.

---

## ⚙️ System Prerequisites

* **PHP:** 8.2 or higher
* **Composer:** 2.x
* **Database:** MySQL 8.0+ / MariaDB
* **Laravel:** 11.x

---

## 🚀 Local Setup Instructions

Follow these steps to get the project running locally:

### 1. Clone the Repository
```bash
git clone <your-repository-url>
cd payment-gateway-demo
composer install
cp .env.example .env
php artisan key:generate

```
###

Open the .env file and configure your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ajeer_demo
DB_USERNAME=root
DB_PASSWORD=
```
### 2. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```
🚦 API Documentation & Workflow
A comprehensive Postman collection is included to test the sequence flows.
(Note: Import the Ajeer_Assessment.postman_collection.json file provided in the repository root into your Postman workspace).

Task 1: Multi-Gateway Payments
Endpoint: POST /api/v1/payments/process

Flow: Accepts a gateway ID, city ID, module ID, and amount. The system validates availability rules, processes the transaction, and logs success/failures securely.

Task 2: Service Booking Flow
Auth: POST /api/v1/register or /login (Returns a Sanctum Bearer Token).

Trial: POST /api/v1/subscriptions/trial (Activates a 14-day trial required for booking).

Browse: GET /api/v1/services or /packages (Publicly accessible catalog).

Cart: POST /api/v1/cart/add (Add a service or package to the cart using its polymorphic type).

Checkout: POST /api/v1/bookings/checkout (Validates subscription, processes the cart total, creates the booking, and clears the cart inside a secure database transaction).
