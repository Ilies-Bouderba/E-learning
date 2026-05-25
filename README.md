# 🎓 EduMex — E-Learning Platform

**Full-stack web application** for managing online courses, quizzes, exams, and student progress. Built with Laravel 12 and Livewire 3.

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=flat&logo=laravel)
![Livewire](https://img.shields.io/badge/Livewire-3-blue?style=flat&logo=livewire)
![PHP](https://img.shields.io/badge/PHP-8.4-purple?style=flat&logo=php)
![SQL Server](https://img.shields.io/badge/SQL%20Server-2019-CC2927?style=flat&logo=microsoft-sql-server)

---

## 📋 Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [Installation](#installation)
- [Environment Variables](#environment-variables)
- [Default Accounts](#default-accounts)
- [Database Schema](#database-schema)
- [Project Structure](#project-structure)
- [Useful Commands](#useful-commands)
- [Scheduler Setup](#scheduler-setup)
- [Developer Notes](#developer-notes)

---

## Overview

EduMex is a full-featured e-learning platform that supports three user roles:

| Role | Description |
|------|-------------|
| **Admin** | Platform management, user accounts, departments |
| **Teacher** | Course creation, quizzes, exams, grading |
| **Student** | Enroll in courses, take assessments, track progress |

Each role has a dedicated dashboard with role-specific functionality including course management, chapter content, quizzes, AI-graded exams, announcements, and real-time notifications.

---

## Tech Stack

| Category | Technology |
|----------|------------|
| Backend | Laravel 12 (PHP 8.4) |
| Frontend | Livewire 3 (reactive UI, no JavaScript required) |
| Database | Microsoft SQL Server 2019/2021 |
| Styling | Custom CSS design system (neo-brutalist theme) |
| Icons | Lucide SVG (inline, no external dependencies) |
| AI Grading | OpenRouter API (GPT-3.5-turbo) |
| Mail | SMTP (Mailtrap for development) |
| Scheduler | Laravel task scheduler |
| Fonts | Syne (headings) + DM Sans (body) |

---

## Features

### 🔐 Authentication
- Email + password login
- Role-based access control (Admin / Teacher / Student)
- Admin-mediated password reset system
- Forgot password functionality

### 👑 Admin Dashboard
- Platform-wide statistics overview
- Manage teacher accounts (create, edit, delete)
- Manage student accounts (create, edit, delete)
- Manage departments with icon picker
- Password reset request queue with email notification

### 👨‍🏫 Teacher Dashboard
- Course management (create, edit, delete)
  - Optional password protection per course
  - Department categorization
  - Icon picker for visual identification
- Chapter management with file attachments (PDF, video, images, other)
- Course announcements with automatic notifications
- Quiz builder (multiple-choice, multiple options, publish/unpublish)
- Exam builder (open-ended, start/end dates, duration timer, point values)
- Grade Book — full grade table with CSV export and color coding
- Leaderboard — ranked student performance per course

### 👨‍🎓 Student Dashboard
- Enrolled courses with progress tracking
- Browse and enroll in courses (password-protected require key)
- Chapter view with progress tracking
- Comment on chapters and reply to others
- Take quizzes — auto-scored on submission
- Take exams — countdown timer with auto-submit, AI-graded answers
- View results for completed assessments
- Leaderboard — see rank among classmates

### 🔔 Notifications System
- Bell icon in navbar with unread count badge
- Database-stored notifications (persistent across sessions)
- Triggers: new announcements, graded exams
- Mark individual or all notifications as read

### ⏰ Exam Reminders (Automated)
- Email reminders sent at: 1 day, 3 hours, 1 hour, and 10 minutes before exam start
- Duplicate prevention via cache keys
- Manual trigger: `php artisan exams:send-reminders`

---

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18 + npm
- Microsoft SQL Server 2019 or 2021
- Microsoft ODBC Driver 17 for SQL Server
- Laravel Herd (recommended on Windows) or similar local server

### Steps

```bash
# 1. Clone or extract the project

# 2. Install PHP dependencies
composer install

# 3. Install frontend dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure your .env file (see Environment Variables section)

# 7. Run migrations and seeders
php artisan migrate:fresh --seed

# 8. Build frontend assets
npm run build

# 9. Start the application
php artisan serve
# OR use Laravel Herd — place in Herd directory
```

## Default Accounts

After running the seeders, the following accounts are available:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@edumex.com | admin1234 |
| Teacher | teacher@test.com | password |
| Student | student@test.com | password |

---

## Database Schema

| Table | Description |
|-------|-------------|
| `users` | All users (admin/teacher/student) |
| `departments` | Academic departments |
| `cours` | Courses (table name 'cours', model 'Course') |
| `chapters` | Course chapters |
| `attachments` | Files attached to chapters |
| `enrollments` | Student-course enrollment records |
| `student_progress` | Per-chapter completion tracking |
| `chapter_comments` | Comments on chapters |
| `comment_replies` | Replies to chapter comments |
| `announcements` | Course announcements |
| `quizzes` | Multiple-choice quizzes |
| `quiz_questions` | Questions within a quiz |
| `quiz_options` | Answer options per question |
| `quiz_attempts` | Student quiz submissions + scores |
| `exams` | Open-ended exams with dates + timer |
| `exam_questions` | Questions within an exam |
| `exam_attempts` | Student exam submissions + AI grades |
| `notifications` | Database notifications |
| `password_reset_requests` | Admin-mediated password reset requests |
| `cache` / `jobs` / `sessions` | Laravel system tables |

---

## Useful Commands

| Command | Description |
|---------|-------------|
| `php artisan migrate:fresh --seed` | Reset DB and seed default data |
| `php artisan db:seed` | Seed without resetting |
| `php artisan optimize:clear` | Clear all caches |
| `php artisan route:list` | List all registered routes |
| `php artisan exams:send-reminders` | Manually trigger exam reminders |
| `php artisan schedule:run` | Run the task scheduler once |
| `php artisan tinker` | Interactive PHP shell |
