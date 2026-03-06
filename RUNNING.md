# Running the Project

This guide provides instructions on how to set up and run the **systeme_reclamation** Symfony project locally.

## Prerequisites

1.  **PHP 8.4** or higher.
2.  **Composer**.
3.  **PostgreSQL** (running locally).
4.  **Symfony CLI** (optional but recommended).

## Setup Instructions

### 1. Install Dependencies
Run the following command to install the project dependencies:
```bash
composer install
```

### 2. Configure Database
Ensure your PostgreSQL server is running. The connection string is already configured in `.env`:
`DATABASE_URL="postgresql://postgres:postgres@localhost:5432/systeme_reclamation?charset=utf8"`

Run these commands to create the database and run migrations:
```bash
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
```

### 3. Start the Application

#### Option A: Using Symfony CLI (Recommended)
This starts a high-performance local web server.
```bash
symfony server:start
```
*Note: You can also use `symfony serve -d` to run it in the background.*

#### Option B: Using PHP Built-in Server
If you don't have the Symfony CLI installed, you can use:
```bash
php -S localhost:8000 -t public
```

### 4. Asset Management
The project uses Symfony AssetMapper. Assets are automatically handled, but you can install missing ones with:
```bash
php bin/console importmap:install
```

## Useful Commands
- **Clear Cache:** `php bin/console cache:clear`
- **View Routes:** `php bin/console debug:router`
- **Make Entity:** `php bin/console make:entity`
