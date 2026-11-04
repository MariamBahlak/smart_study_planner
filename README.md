# 🚀 Smart Study Planner

A secure, clean, and modular web application built for managing study tasks efficiently. Developed following software engineering principles like **Clean Architecture** and **Separation of Concerns**, utilizing vanilla PHP (PDO) and MySQL.

---

## ✨ Features
- **Create**: Add new study tasks with input validation.
- **Read**: Dynamically fetch and display all tasks ordered by latest first.
- **Update**: Toggle task completion status (Completed / Pending) with a single click.
- **Delete**: Remove tasks safely with a confirmation prompt.
- **Security**: Prevent SQL Injection using PDO Prepared Statements and protect against XSS using output sanitization (`htmlspecialchars`).

---

## 🛠️ Tech Stack
- **Backend**: PHP (PDO - PHP Data Objects)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3 (Modern Flexbox layout, responsive design)
- **Server**: Apache (XAMPP local environment)

---

## 📂 Project Architecture (Clean Architecture)
```text
study_pro/
│
├── assets/
│   └── CSS/
│       └── style.css      # External modern UI stylesheets
│
├── config/
│   └── database.php       # Centralized database connection using PDO
│
├── controllers/
│   ├── add_task.php       # Handles task creation logic
│   ├── delete_task.php    # Handles task deletion logic
│   └── update_task.php    # Handles task status toggle logic
│
├── public/
│   └── index.php          # Main presentation view (UI)
│
└── README.md              # Project documentation