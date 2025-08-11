# TickNShop – PHP Watch Shop

Welcome to **TickNShop**, a PHP-based web project featuring a stylish, gender-neutral watch store tailored for both men and women.

---

##  Table of Contents

- [About](#about)  
- [Features](#features)  
- [Tech Stack](#tech-stack)  
- [Installation](#installation)  
- [Usage](#usage)  
- [Project Structure](#project-structure)  
- [Contributing](#contributing)

---

## About

TickNShop is a simple and elegant watch store built using PHP, HTML, CSS, and MySQL. The project showcases a gender-neutral design, offering both men’s and women’s watches in a single, inclusive storefront.

---

## Features

- Responsive product listings for men and women.  
- Product details and filtering via `product.php`.  
- Backend powered by `db.php` (PHP + MySQL).  
- Clean, modern styling using `style.css`.  
- Easily extensible for future features like search, cart, or admin panel.

---

## Tech Stack

- **Backend**: PHP  
- **Database**: MySQL (via `db.php`)  
- **Frontend**: HTML, CSS (`style.css`)  
- **Web Server**: Apache or any PHP-compatible server

---

## Installation

1. Clone the repository:  
   ```bash
   git clone https://github.com/Hetvipatel5/TickNShop.git
   ```
2. Import your MySQL database schema (if provided) or create your own.  
3. Update database connection details in `db.php`.  
4. Deploy all files to your PHP-enabled web server’s document root.  
5. Open `index.php` in your browser to see the shop in action.

---

## Usage

- Browse the watch catalog from the homepage (`index.php`).  
- Click on a product thumbnail to view details via `product.php`.  
- Admins can update product listings through the database.

---

## Project Structure

```
TickNShop/
├── index.php       # Homepage and catalog
├── product.php     # Product detail pages
├── db.php          # Database connection and queries
├── style.css       # Website styling
└── images/         # Contains product watch images
```

---

## Contributing

Contributions are welcome! Feel free to:
- Add search and filter functionalities
- Implement a shopping cart or checkout system
- Enhance responsive design for mobile devices

Please make a fork, commit your changes, and create a pull request.

---

## Contributors

- **Hetvi Patel** – Developer & Designer  
- **Misa Patel** – Developer & Designer