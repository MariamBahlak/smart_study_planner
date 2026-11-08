# 🎓 Smart Study Planner & AI Assistant
A modern, secure, and production-ready Web Application designed to help students organize academic tasks, boost productivity, and interact with an integrated AI study assistant.
## ✨ Features
- Glassmorphism UI: A sleek, modern dark-themed user interface built with advanced CSS3 and responsive layouts.
- Secure Authentication: Strict server-side validation, password complexity requirements with real-time UI feedback, secure password hashing (bcrypt), and strict email domain existence checks.
- Task Management: Add, view, complete/undo, and delete academic study tasks dynamically linked per user via secure database sessions.
- AI Study Assistant: Integrated with Google's Gemini AI API via cURL to provide instant, concise, and professional study recommendations and answers.
## 🛠️ Tech Stack
- Frontend: HTML5, CSS3 (Glassmorphism design system), JavaScript (Fetch API, asynchronous UI handlers).
- Backend: PHP (PDO for secure database abstraction, session management).
- Database: MySQL (relational schema for users and tasks).
- AI Integration: Google Generative AI API (gemini-3.8-flash).
## 📁 Project Structure
study_pro/
│
├── config/
│   └── database.php
├── controllers/
│   ├── add_task.php
│   ├── ai_process.php
│   ├── delete_task.php
│   ├── login_process.php
│   ├── logout.php
│   ├── register_process.php
│   └── update_task.php
├── public/
│   ├── dashboard.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── register.php
│   └── style.css
└── README.md
## 🚀 Getting Started
- To get a local copy up and running, follow these simple steps:
  ### Prerequisites
- Install a local server environment that supports PHP and MySQL (e.g., XAMPP, WampServer, or MAMP).
- A web browser (Google Chrome, Firefox, etc.).
- A valid Google Gemini API Key from Google AI Studio.
### Installation & Setup
1. Clone or Download the Repository:
Clone the repository or copy the study_pro project folder into your local server's root directory (e.g., htdocs in XAMPP):
git clone https://github.com/YOUR_USERNAME/smart-study-planner.git
2. Create the Database:
Open your browser and go to http://localhost/phpmyadmin/.
Create a new database named study_planner_db.
Import the required tables (users and tasks) into the database.
3. Configure Database Connection:
Open config/database.php and update your database credentials if necessary:
$host = 'localhost';
$db   = 'study_planner_db';
$user = 'root';
$pass = '';
4. Configure Gemini API Key:
Open controllers/ai_process.php and paste your Google Gemini API key into the $apiKey variable:
$apiKey = "YOUR_ACTUAL_API_KEY_HERE";
5. Run the Application:
Open your browser and navigate to the project's public directory:
http://localhost/study_pro/public/index.php
