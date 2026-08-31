# Expense Tracker

## Project Overview
A personal expense management application built with Laravel and Livewire. Users can create, edit, and track expenses by category, filter and search transactions, manage categories, and analyze spending patterns through reports with interactive charts. All data is user-scoped and requires authentication. Amounts are tracked in **Toman**.

## Key Features

### Authentication & Profile
- Registration, Login, Email Verification, Password Reset / Confirmation (Laravel Breeze)
- Profile update and account deletion

### Dashboard
- Paginated expense list (10 per page) with category badge and styles
- Total spending sum for current filtered view
- Search by description
- Filter by category and date range (from / to)
- Inline edit via modal and delete with validation

### Create Expense
- Form with date (defaults to today), category select, amount, and description
- Validation: `date` required, `category_id` must exist, `amount` numeric min 0, `description` nullable max 1000
- Redirect to dashboard on success

### Categories
- CRUD for categories (name, description, color)
- 7 predefined colors: orange, blue, purple, pink, yellow, green, gray (defined in `config/categories.php`)
- List displays expense count and total amount per category
- Modal-based create/edit with validation (`name` max 30, `description` required)

### Reports
- **Filters:** Date range (defaults to current month) and multi-select category pills
- **Stats:** Total Expenses (Toman), Transaction Count, Average Weekly Spending
- **Breakdown by Category:** Toggle between Pie Chart and Bar Chart (SVG), plus table with total amount and percentage per category
- **Spending Over Time:** Line Chart (SVG bezier) with Weekly / Monthly toggle, plus table with transactions and total per period
- Charts implemented as Livewire components: `App\Livewire\Chart\PieChart`, `BarChart`, `LineChart`

## Technologies Used
- **Backend:** PHP 8.1+, Laravel 10, Eloquent ORM
- **Frontend / Interactivity:** Livewire 3, Blade, Tailwind CSS (via Laravel Breeze)
- **Auth:** Laravel Breeze, Laravel Sanctum
- **Database:** MySQL / SQLite (configurable via `config/database.php`), Migrations
- **Tooling:** Composer, Laravel Pint, PHPUnit

## Project Structure
```
app/Livewire/         -> Dashboard, CreateExpense, Categories, Reports, Sidebar, Chart/*
app/Models/           -> Expense, Category, User
resources/views/livewire/ -> Blade views + charts/*
config/categories.php -> Color definitions
routes/web.php        -> dashboard, categories, reports, expenses/create (auth middleware)
```

## Requirements
- PHP ^8.1
- Composer
- Laravel 10, Livewire ^3.6

## License
MIT
