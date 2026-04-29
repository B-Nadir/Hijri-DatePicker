<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20Logo%20%26%20Wordmark/-/light.svg" width="400" alt="Laravel Logo"></a></p>

# Hijri DatePicker

## About Hijri DatePicker

The Hijri DatePicker is a sophisticated, interactive date selection component built on the [Laravel](https://laravel.com) framework. It provides a unique, dual-mode interface for selecting both Hijri and Gregorian dates, optimized for precision and ease of use.

The system leverages:
- **Laravel 11.x** for the robust application foundation.
- **Tailwind CSS** for flexible, utility-first styling.
- **Alpine.js** for high-performance, reactive state management.
- **Custom Logic**: Intelligent date conversion between Hijri and Gregorian systems.

## Features

- **Dual-Mode Selection**: Toggle between Hijri and Gregorian calendars with real-time conversion.
- **Dynamic Highlighting**: Active mode is highlighted with primary colors, while the secondary mode remains visible for reference.
- **Premium Aesthetics**: Rounded designs, glassmorphism effects, and smooth transitions.
- **Typography Integration**: Uses **Al-Kanz** for Hijri dates and **Helvetica/Arial** for Gregorian/English text.
- **Input Fields**: Automatically populates form fields with formatted dates in both English and Arabic.
- **Responsive Popover**: A fully mobile-responsive calendar popover that intelligently floats above other content.

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM

### Setup Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/B-Nadir/Hijri-DatePicker.git
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment configuration:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Compile assets & serve:**
   ```bash
   npm run dev
   php artisan serve
   ```

## License

The Hijri DatePicker is open-sourced software licensed under the [MIT license](LICENSE).

## Credits

Developed with ❤️ by [Burhanuddin Nadir](https://github.com/B-Nadir).
