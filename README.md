# Shory 📖

Shory is an interactive web application that allows you to create, read, and manage interactive stories (similar to "choose your own adventure" books). 

Designed to provide a seamless writing process and an immersive reading experience, the application uses a modern architecture without full page reloads thanks to Hotwire.

---
## Core Features

* **Story Management:** Create stories and manage their metadata (Title, Summary, Author).
* **Genre Classification:** Dynamic and colored tag system based on PHP Enums (Science Fiction, Fantasy, Thriller, etc.).
* **Customizable Visual Themes:** Select a reading template (Light, Dark) that is dynamically injected into each story.
* **Chapter Editor:** Write chapters with full **Markdown** support for rich text formatting.
* **Branching Narratives:** Link chapters together to create choices and multiple story paths.
* **Fluid Navigation (SPA-like):** Fast transitions, dynamic forms (auto-submit with debouncing), and URL updates without reloading, powered by the **Hotwire** ecosystem (Turbo & Stimulus).

---
## Technologies Used

* **Backend:** PHP 8.x, Symfony 6/7, Doctrine ORM
* **Frontend:** Twig, Bootstrap 5, Bootstrap Icons
* **Javascript:** Stimulus, Turbo (Hotwire)
* **Database:** MySQL / PostgreSQL / SQLite

---
## Prerequisites

Make sure you have the following installed on your machine:

* PHP 8.1 or higher
* Composer
* Symfony CLI
* Node.js & npm (or Yarn)
* A database server

---
## Installation

Follow these steps to run the project locally.

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/Enoraelle/shory.git](https://github.com/Enoraelle/shory.git)
   cd shory
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install and compile Frontend assets:**
   ```bash
   npm install
   npm run build
   # or for development mode: npm run watch
   ```

4. **Configure the database:**
   Duplicate the `.env` file to `.env.local` and update the `DATABASE_URL` variable with your database credentials.

5. **Create the database and run migrations:**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

6. **Start the development server:**
   ```bash
   symfony server:start -d
   ```
   Then, navigate to `http://localhost:8000` in your browser.

---
## Author

* **Enoraelle** - [GitHub Profile](https://github.com/Enoraelle)

---
## License

This project is licensed under the MIT License - see the LICENSE.md file for details.
