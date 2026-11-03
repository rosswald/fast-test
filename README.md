# fast-test.local — PHP + Nginx

Docker with PHP 8.5 with GD support and SQLite

## Start

From this directory:

```bash
docker compose up -d --build
```

Open http://fast-test.local

## hosts file

```text
127.0.0.1 fast-test.local
```

## Project layout

- `public/` — only web-accessible files; Nginx document root
- `src/` — application PHP files, outside the document root
- `database/` — SQLite database file and schema sql file to populate the database. If the database is missing it is 
                created and populated with schema; Schema contains blank tables and users admin and test
- `download/` - A location for exe file that is served when Download button is clicked
- `docker/php/` — PHP-FPM image and local PHP settings
- `docker/nginx/` — Nginx virtual host configuration

