# Student Portal (PHP) — Azure App Service
A minimal PHP app to demo course **enrollments**, **attendance**, and **schedules**.  
Data is saved to JSON files in your App Service's writable folder (`/home/data` or `D:\home\data`).

## Files
- `index.php` — home
- `enrollments.php` — add/view enrollments (JSON: `enrollments.json`)
- `attendance.php` — mark attendance (JSON: `attendance.json`)
- `schedules.php` — manage schedules (JSON: `schedules.json`)
- `lib.php` — helpers (JSON load/save, HTML header/footer, XSS-safe output)
- `styles.css` — simple styling

## Deploy (Zip Deploy via Kudu)
1. Create **App Service**: Runtime **PHP 8.x**, OS **Linux** (or Windows), any free/basic plan.
2. Zip this folder and upload to **Kudu** → `site/wwwroot` → **Extract**.
3. Browse: `https://<yourapp>.azurewebsites.net/index.php` (and the other pages).
