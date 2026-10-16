# 🚀 Student Mental Health Assessment & Referral System

## Comprehensive Setup & Installation Guide

Welcome! This step-by-step guide is designed specifically for students and beginners. Follow these intuitive instructions to get the system up and running on your local machine using **XAMPP** (or any local web server like WampServer or Laragon).

## 📋 System Requirements

Before you begin, ensure you have installed:

1. **XAMPP** (PHP 8.0+ and MySQL) — [Download XAMPP](https://www.apachefriends.org/index.html "null")
    
2. A Web Browser (Google Chrome, Firefox, MS Edge, or Brave)
    
3. A Code Editor (e.g., VS Code) _[Optional]_
    

## 📂 Step 1: Place Project Files in the Web Directory

1. Open your XAMPP installation folder (typically `C:\xampp\` on Windows).
    
2. Locate and open the `htdocs` folder (`C:\xampp\htdocs\`).
    
3. Place your project folder here so the path looks like this:
    
    ```
    C:\xampp\htdocs\student-mental-health-system\
    ```
    

### 📁 Expected Folder Structure

Ensure your directory contains the following files and folders:

```
student-mental-health-system/
│
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
│
├── config/
│   └── database.php
│
├── database/
│   ├── migrate.php
│   └── seed.php
│
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
│
├── student/
│   ├── dashboard.php
│   └── take_assessment.php
│
├── counselor/
│   └── dashboard.php
│
├── index.php
├── README.md
└── SETUP_GUIDE.md
```

## ⚡ Step 2: Start Apache and MySQL Servers

1. Open the **XAMPP Control Panel**.
    
2. Click **Start** next to **Apache**.
    
3. Click **Start** next to **MySQL**.
    
4. Ensure both modules turn green and show active port numbers.
    

## 🛠️ Step 3: Run Database Migration & Data Seeding

The project includes automated PHP scripts to create the database, build tables, and insert sample accounts.

### 1. Execute the Migration Script

Open your browser and visit:

```
http://localhost/student-mental-health-system/database/migrate.php
```

- **What this does:** Creates the `mental_health_db` database and all required tables (`users`, `assessments`, `referrals`).
    
- **Expected Output:** _"Database migration completed successfully! All tables created."_
    

### 2. Execute the Data Seed Script

Next, visit:

```
http://localhost/student-mental-health-system/database/seed.php
```

- **What this does:** Populates test accounts and initial mental health assessments so you can test logins right away.
    
- **Expected Output:** _"Database seeded successfully!"_
    

## 🔑 Step 4: Login & Test Credentials

Now navigate to the login page:

```
http://localhost/student-mental-health-system/auth/login.php
```

You can log in using either of the seeded default accounts:

|   |   |   |   |
|---|---|---|---|
|**User Role**|**Email Address**|**Password**|**Features / Permissions**|
|**Student**|`student@college.edu`|`password123`|Submit mental health screenings & view intervention history.|
|**Counselor**|`counselor@college.edu`|`password123`|View all student assessments & update referral statuses.|

## 🔍 Step 5: System Features & Testing Workflow

### 🎓 Testing as a Student:

1. Log in with `student@college.edu` / `password123`.
    
2. Click **Take New Mental Health Assessment**.
    
3. Rate your stress level (1–10) and anxiety score (1–10), enter notes, and submit.
    
4. Review your past submissions on the **Student Dashboard**.
    

### 🩺 Testing as a Counselor:

1. Log out from the student account.
    
2. Log in with `counselor@college.edu` / `password123`.
    
3. View all student mental health screenings in real-time.
    
4. Update the **Status** of any student referral (e.g., from _Pending_ to _In Progress_ or _Resolved_) and click **Save**.
    

## ❓ Frequently Asked Questions & Troubleshooting

### Q1: `SQLSTATE[3D000]: Invalid catalog name: 1046 No database selected`

- **Fix:** Ensure `config/database.php` specifies the database name in the PDO connection string:
    
    ```
    $pdo = new PDO("mysql:host=$host;dbname=mental_health_db;charset=utf8mb4", $username, $password);
    ```
    

### Q2: `404 Not Found` when opening dashboard pages

- **Fix:** Ensure your files match the correct directory path:
    
    - `student/dashboard.php`
        
    - `student/take_assessment.php`
        
    - `counselor/dashboard.php`
        

### Q3: How do I create a new student account?

- Visit `http://localhost/student-mental-health-system/auth/register.php` and complete the sign-up form.
    

## 🛡️ License & Support

This system is created for educational purposes to support campus mental health screenings, referrals, and intervention tracking.