# AracorpPOS 🏪

A modern Point of Sale system built with Laravel + Python.

## Screenshots

### Cashier Screen
<img width="1828" height="863" alt="Screenshot 2026-07-04 100046" src="https://github.com/user-attachments/assets/0dd9dc24-9e69-44ce-96ef-124f58942c70" />

### Analytics Dashboard
<img width="1858" height="870" alt="Screenshot 2026-07-04 100022" src="https://github.com/user-attachments/assets/850c9fe5-e62f-4180-83af-0f98d24142d9" />

## Tech Stack
- Laravel 13 + Filament
- Python Flask + Pandas
- MySQL

## Features
- Product & inventory management
- Real-time cashier screen with cart
- Automatic stock tracking via observers
- Python-powered sales analytics
- Low stock alerts

## Architecture
Laravel handles business logic and UI.
Python reads the same MySQL database
and serves analytics via REST API.

## Setup
1. Clone the repo
2. `composer install`
3. `cp .env.example .env`
4. `php artisan migrate`
5. `cd analytics && pip install -r requirements.txt`
6. Run Laravel: `php artisan serve`
7. Run Python: `python analytics.py`
