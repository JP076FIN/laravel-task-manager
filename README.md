# Laravel Task Manager

This is part of HBO-ICT 1st year assignments.

## ✅ Script Kiddie Assignment (5pt)

This Laravel project demonstrates:

- 🔐 Protected route: `/home` (accessible only to authenticated users)
- 🧾 User registration via `/register`
- 🔑 Login form includes "Remember Me" checkbox for persistent login

### 🔍 How to Test

1. Visit `/register` to create a new account, or click "Register here"
2. Log in at `/login`, optionally check "Remember Me"
3. Access `/home` — this route is protected with `auth` middleware
4. Try logging out and back in, or closing and reopening the browser to test persistent login

---

Laravel Breeze is used for basic authentication scaffolding. Blade components were replaced with manual input handling using Tailwind-styled HTML.

