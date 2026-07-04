# AracorpPOS 🏪

A modern Point of Sale system built with Laravel + Python.

## Screenshots

### Cashier Screen
<img width="1832" height="865" alt="image" src="https://github.com/user-attachments/assets/d73fd5d8-27b7-4aba-bdbb-72fde7cdaa32" />

### Analytics Dashboard
<img width="1857" height="873" alt="image" src="https://github.com/user-attachments/assets/67f15bc2-a344-480e-85c9-9cbfc3bfbf82" />

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
