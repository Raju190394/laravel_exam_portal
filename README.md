# 🎓 Online Examination System (Laravel Exam Portal)

A professional, robust, and feature-rich Online Examination System built with **Laravel 10**, **Tailwind CSS v4**, and **Alpine.js**. This system allows educational institutions to manage courses, students, and conduct timed examinations with ease.

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)

---

## 🚀 Key Features

### 👨‍💼 Administrator / Examiner Features
- **Dashboard**: Real-time overview of exams, students, and system stats.
- **Course Management**: Create and manage multiple departments/courses.
- **Student Management**: Register students and enroll them in multiple courses simultaneously.
- **Exam Builder**: 
    - Create timed exams with custom passing marks and negative marking.
    - **Bulk Import**: Import hundreds of questions instantly from a **Word (.docx)** file.
    - **Rich Media**: Support for images in both questions and individual options.
    - **Scheduling**: Set specific dates and times for exams to become active/inactive.
    - **Randomization**: Scramble questions and options for each student to prevent cheating.

### 👨‍🎓 Student Features
- **Personalized Exam List**: View only the exams related to enrolled courses.
- **Timed Assessment**: Real-time countdown timer during exams.
- **Modern Exam Interface**: Clean, distraction-free environment for taking tests.
- **Instant Results**: Detailed scorecards and performance feedback after submission.

---

## 🛠️ Installation & Setup

Follow these steps to get the project running locally:

### 1. Clone the Repository
```bash
git clone https://github.com/Raju190394/laravel_exam_portal.git
cd laravel_exam_portal
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
Copy the example environment file and update your database credentials:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup & Seeding
This will create all tables and populate the system with a Super Admin, a Student, and sample Courses:
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 5. Compile Assets & Run
Open two terminals:
- **Terminal 1 (Backend)**: `php artisan serve`
- **Terminal 2 (Frontend)**: `npm run dev`

---

## 🔐 Credentials (Default)

| Role | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `super@admin.com` | `password` |
| **Student** | `student@student.com` | `password` |

---

## 📝 Word File Import Format

To import questions via Word, use this format:
- Start questions with `1.` or `Q:`.
- Use `A) B) C) D)` for options.
- Append `(Correct)` to the right answer.
- Paste images directly inside the Word file for visual questions.

---

## 🤝 Contributing
Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


