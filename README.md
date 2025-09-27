# Neama

Neama is a restaurant and food ordering API built with **Laravel 11**, connecting **users** and **restaurants**.  
It supports account registration, meal management, order tracking, image uploads, and provides a structured RESTful API for integrations.

## API Testing

You can test the endpoints using:

-   Postman
-   Thunder Client (VS Code extension)
-   cURL

---

## Table of Contents

-   [ Project Overview](#-project-overview)
-   [ Requirements](#-requirements)
-   [ Installation & Setup](#-installation--setup)
    -   [1. Clone the Repository](#1-clone-the-repository)
    -   [2. Install Dependencies](#2-install-dependencies)
    -   [3. Configure Environment](#3-configure-environment)
    -   [4. Set Up the Database](#4-set-up-the-database)
    -   [5. Run the Application](#5-run-the-application)
-   [ Database Structure](#-database-structure)
-   [ API Documentation](#-api-documentation)
-   [ Sample Credentials](#-sample-credentials)

---

## Project Overview

**Neama** is a **RESTful API backend** built with **Laravel 11**, designed to manage:

-   **Handle user and restaurant registration**
-   **Manage meals for each restaurant**
-   **Track orders between users and restaurants**
-   **Upload and manage meal images**
-   **Provide a well-structured API for all operations**
-   Role-based access for **users** and **restaurants**

This project serves as the backend API for a restaurant & food ordering system.  
It can be consumed by a **mobile app** or **frontend client**.

---

## Tech Stack

-   **Framework:** Laravel 11.x
-   **Language:** PHP ^8.2
-   **Authentication:** Laravel Sanctum
-   **Database:** MySQL
-   **Package Manager:** Composer
-   **Testing:** PHPUnit / Mockery
-   **Code Style:** Laravel Pint

---

## Requirements

| Component | Version |
| --------- | ------- |
| PHP       | ≥ 8.2   |
| Composer  | Latest  |
| Laravel   | 11.x    |
| Database  | MySQL   |

---

## Installation & Setup

### 1. Clone the Repository

git clone https://github.com/NourAlkinj/naema.git
cd naema

### 2. Install Dependencies

composer install

### 3. Configure Environment

cp .env.example .env
php artisan key:generate

### 4. Set Up the Database

php artisan migrate --seed

### 5. Run the Application

php artisan serve

### System Roles

| Role           | Permissions                                                         | Dashboard / API Access |
| -------------- | ------------------------------------------------------------------- | ---------------------- |
| **User**       | Register & login, browse restaurants & meals, create & track orders | `/api/user`            |
| **Restaurant** | Register & login, manage own meals, view & update orders            | `/api/restaurant`      |

### Database Structure

| Table        | Columns                                                                             |
| ------------ | ----------------------------------------------------------------------------------- |
| users        | id, name, phone_number, email, password, avatar, location, lang, lat, images        |
| restaurants  | id, name, phone_number, email, password, avatar, location, lang, lat, brief, images |
| meals        | id, meal_name, restaurant_id, images, quantity, created_date, expire_date, price    |
| orders       | id, user_id, meal_id, restaurant_id, OrderStatus_id, quantity, total_price          |
| order_status | id, status_title                                                                    |

# API Documentation

## 1. Authentication

| Method | Endpoint                              | Description         |
| ------ | ------------------------------------- | ------------------- |
| POST   | `/api/user/register-user`             | Register user       |
| POST   | `/api/user/login-user`                | User login          |
| POST   | `/api/restaurant/register-restaurant` | Register restaurant |
| POST   | `/api/restaurant/login-restaurant`    | Restaurant login    |

---

## 2. Users

| Method | Endpoint                                   | Description                    |
| ------ | ------------------------------------------ | ------------------------------ |
| GET    | `/api/user/get-user/{id}`                  | Get user by ID                 |
| GET    | `/api/user/get-all-restaurants`            | Get all restaurants            |
| GET    | `/api/user/get-all-restaurants-with-meals` | Get all restaurants with meals |
| POST   | `/api/user/update-user/{id}`               | Update user profile            |

---

## 3. Restaurants

| Method | Endpoint                                         | Description               |
| ------ | ------------------------------------------------ | ------------------------- |
| GET    | `/api/restaurant/get-restaurant-with-meals/{id}` | Get restaurant with meals |
| POST   | `/api/restaurant/update-restaurant/{id}`         | Update restaurant profile |

---

## 4. Meals

| Method | Endpoint                         | Description      |
| ------ | -------------------------------- | ---------------- |
| GET    | `/api/meal/get-latest-meals`     | Get latest meals |
| GET    | `/api/meal/get-meal/{mealId}`    | Get meal by ID   |
| POST   | `/api/meal/add-new-meal`         | Add new meal     |
| POST   | `/api/meal/update-meal/{id}`     | Update meal      |
| GET    | `/api/meal/delete-meal/{mealId}` | Delete meal      |

---

## 5. Orders

| Method | Endpoint                                               | Description                 |
| ------ | ------------------------------------------------------ | --------------------------- |
| GET    | `/api/order/get-orders-by-userId/{id}`                 | Get orders by user ID       |
| GET    | `/api/order/get-orders-by-restaurantId/{restaurantId}` | Get orders by restaurant ID |
| GET    | `/api/order/get-all-orders`                            | Get all orders              |
| POST   | `/api/order/create-order`                              | Create new order            |
| POST   | `/api/order/update-order-status`                       | Update order status         |
| GET    | `/api/order/delete-order/{id}`                         | Delete order                |

---

## 6. Images

| Method | Endpoint                    | Description    |
| ------ | --------------------------- | -------------- |
| POST   | `/api/image/upload-images`  | Upload images  |
| GET    | `/api/image/get-image-path` | Get image path |

### Sample Responses

**User Orders Response**

```json
{
    "status": "success",
    "user": "customer1",
    "orders": [
        {
            "order_id": 1,
            "status": "Processing",
            "created_at": "2025-03-03",
            "meal": {
                "meal_id": 1,
                "meal_name": "Shawarma",
                "price": 1000,
                "quantity": 3,
                "total_price": 3000,
                "restaurant": {
                    "id": 1,
                    "name": "My Chicken",
                    "location": "Lattakia",
                    "phone_number": "3347882"
                }
            }
        },
        {
            "order_id": 7,
            "status": "Out for Delivery",
            "created_at": "2025-03-03",
            "meal": {
                "meal_id": 4,
                "meal_name": "Crispy",
                "price": 2500,
                "quantity": 2,
                "total_price": 5000,
                "restaurant": {
                    "id": 1,
                    "name": "My Chicken",
                    "location": "Lattakia",
                    "phone_number": "3347882"
                }
            }
        },
        {
            "order_id": 10,
            "status": "Out for Delivery",
            "created_at": "2025-03-03",
            "meal": {
                "meal_id": 2,
                "meal_name": "Humberger",
                "price": 1100,
                "quantity": 2,
                "total_price": 2200,
                "restaurant": {
                    "id": 2,
                    "name": "Lefah",
                    "location": "Jableh",
                    "phone_number": "356677"
                }
            }
        }
    ]
}
```

**Meals Response**

```json
[
    {
        "id": 1,
        "meal_name": "Shawarma",
        "price": 1000,
        "quantity": 10,
        "images": null,
        "created_date": "2025-03-14T08:07:00.000000Z",
        "expire_date": "2025-03-17T08:07:00.000000Z",
        "restaurant": {
            "id": 1,
            "name": "My Chicken",
            "location": "Lattakia"
        }
    },
    {
        "id": 2,
        "meal_name": "Shawarma",
        "price": 1100,
        "quantity": 8,
        "images": [
            "meals_images/ZG3x2GgAHBz40swEDHBmlgTAAf2DbQP1UVYm3HKE.jpg",
            "meals_images/ERP3j6QyYzrfRAG933hiQb2cbbtJJ4JMnXVsxjgL.jpg"
        ],
        "created_date": "2025-03-14T08:07:00.000000Z",
        "expire_date": "2025-03-17T08:07:00.000000Z",
        "restaurant": {
            "id": 2,
            "name": "Lefah",
            "location": "Jableh"
        }
    },
    {
        "id": 3,
        "meal_name": "Zinger",
        "price": 2000,
        "quantity": 5,
        "images": null,
        "created_date": "2025-03-14T08:07:00.000000Z",
        "expire_date": "2025-03-17T08:07:00.000000Z",
        "restaurant": {
            "id": 1,
            "name": "My Chicken",
            "location": "Lattakia"
        }
    }
]
```

### Sample Credentials

| Role       | Email                  | Password |
| ---------- | ---------------------- | -------- |
| Restaurant | restaurant@example.com | 12345678 |
| User       | user@example.com       | 12345678 |
