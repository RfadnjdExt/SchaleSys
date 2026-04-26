# SchaleSys 🛡️

**Sistem Informasi Akademik (SIAKAD)** with a premium, glassmorphism design inspired by the General Student Council (Schale) aesthetics.

![SchaleSys Dashboard](SchaleSys/src/lib/assets/dashboard-bg.png)

## 📋 Overview

SchaleSys is a modern academic management platform built with **SvelteKit** and **Supabase**. It provides a comprehensive solution for managing academic data while delivering a unique, immersive user experience ("Schale" Theme).

## ✨ Key Features

-   **🎨 Global Design System**:
    -   Fully responsive "Glassmorphism" UI (`bg-white/80`, `backdrop-blur`).
    -   Consistent visual identity (Inter font, rounded components, premium animations).
    -   Dark mode support.
-   **👥 Role-Based Access Control**:
    -   **Admin**: Full control over Faculty, Students, and Courses.
    -   **Dosen (Faculty)**: Manage grades and view student data.
    -   **Mahasiswa (Student)**: View KRS (Study Plan) and Transcripts.
-   **📚 Academic Modules**:
    -   **Dashboard**: Real-time System status and quick stats.
    -   **Mahasiswa**: CRUD operations for student data.
    -   **Mata Kuliah**: Course inventory management.
    -   **KRS**: Study plan card generation and printing.
    -   **Nilai**: Grade transcript visualization with IPK calculation.

## 🛠️ Tech Stack

-   **Framework**: [SvelteKit](https://kit.svelte.dev/)
-   **Styling**: [Tailwind CSS](https://tailwindcss.com/)
-   **Database**: [Supabase](https://supabase.com/) (PostgreSQL)
-   **Runtime**: [Bun](https://bun.sh/) (Recommended) or Node.js

## 🚀 Getting Started

### Prerequisites

-   Bun (runtime)
-   Supabase Project (URL & Key)

### Installation

1.  **Clone the repository**:
    ```bash
    git clone https://github.com/your-username/SchaleSys.git
    cd SchaleSys/SchaleSys
    ```

2.  **Install dependencies**:
    ```bash
    bun install
    ```

3.  **Environment Setup**:
    Create a `.env` file based on `.env.example`:
    ```env
    PUBLIC_SUPABASE_URL=your_supabase_url
    PUBLIC_SUPABASE_PUBLISHABLE_DEFAULT_KEY=your_supabase_key
    ```

4.  **Seed Data** (Optional):
    Populate the database with initial testing data (uses `trickcal.json` & `blue_archive.json` assets):
    ```bash
    bun run scripts/seed.js
    ```

5.  **Run Development Server**:
    ```bash
    bun dev
    ```

## 📄 License

SchaleSys is open-source software.
