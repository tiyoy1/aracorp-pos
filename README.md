# AracorpPOS

A Point of Sale system built with Laravel + Python.

## Tech Stack
- Laravel 13 + Filament (admin panel + cashier)
- Python Flask + Pandas (sales analytics)
- MySQL (shared database)

## Features
- Product & inventory management
- Real-time cashier screen
- Automatic stock tracking via observers
- Python-powered sales analytics dashboard
- Low stock alerts

## Architecture
Laravel handles business logic and UI.
Python reads the same database and provides
intelligent analytics via REST API.
