# causewecan-website

Guild website project (Laravel-based structure) with:

- Discord Single Sign-On (SSO) login
- Blog on the homepage
- Interactive guild calendar
- WoW guild roster from Stormforge API/Armory
- Downloads page with file links sourced from a Discord channel
- Discord role-based permission system for calendar administration
- Discord bot for syncing server roles into MariaDB
- Polished dark mode UI (gradient background, glass cards, improved tables/forms)
- Docker setup for app, database, and bot
- GHCR publish workflow for container image deployment

---

## Features

### 1) Discord SSO Login
Users authenticate via Discord OAuth and are stored in the `users` table.

### 2) Homepage Blog
The root route (`/`) shows blog posts from `blog_posts`.

### 3) Interactive Calendar
- All authenticated users can view the calendar.
- Only users with calendar admin permissions can create events.

### 4) Discord Role-based Calendar Admin Permissions
Calendar write permissions are based on Discord roles. The sync bot authenticates only via a bot API key (`DISCORD_BOT_API_KEY`) and does not use a Discord client ID for bot auth:

- Discord role IDs are configured in `.env` (`DISCORD_ADMIN_ROLE_IDS`).
- A Discord bot syncs guild member roles into:
  - `discord_user_roles`
  - `discord_role_mappings`
- During sync, `users.is_calendar_admin` is updated automatically.
- Laravel middleware protects event creation routes.

### 5) Downloads from Discord Channel
The `/downloads` page lists attachment links from a configured Discord channel.

- Configure channel ID via `DISCORD_DOWNLOADS_CHANNEL_ID` in `.env`.
- The app reads channel messages via Discord API using `DISCORD_BOT_API_KEY`.

### 6) Stormforge Roster Integration
Roster data is loaded from a configurable Stormforge endpoint:

`GET {STORMFORGE_API_BASE_URL}/guild/roster`

If the API request fails, the app falls back to previously synced data in `guild_members`.

> Note: Stormforge endpoints may vary. If your account exposes different endpoints or payloads, update `app/Services/StormforgeService.php`.

---

## Environment Configuration

Copy and edit environment variables:

```bash
cp .env.example .env
```

Key variables:

```env
# App + DB
APP_URL=http://localhost
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=causewecan
DB_USERNAME=causewecan
DB_PASSWORD=secret

# Discord OAuth
DISCORD_CLIENT_ID=
DISCORD_CLIENT_SECRET=
DISCORD_REDIRECT_URI=http://localhost/auth/discord/callback

# Discord bot + role permissions
DISCORD_BOT_API_KEY=
DISCORD_GUILD_ID=1462238148632252436
DISCORD_ADMIN_ROLE_IDS=1462238620449771594,1462238763072880888
DISCORD_SYNC_INTERVAL_SECONDS=300
DISCORD_DOWNLOADS_CHANNEL_ID=

# Stormforge
STORMFORGE_API_BASE_URL=https://logs.stormforge.gg/api
STORMFORGE_ARMORY_BASE_URL=https://logs.stormforge.gg/en
STORMFORGE_API_KEY=
STORMFORGE_GUILD_NAME=Cause%20we%20Can
STORMFORGE_REALM=frostmourne

```

---

## Database Setup

### Option A: Laravel migrations (recommended)

```bash
composer install
php artisan key:generate
php artisan migrate
```

### Option B: External MariaDB SQL files
Use:

- `database/sql/001_schema.sql`
- `database/sql/002_seed.sql`

Example import:

```bash
mysql -h <host> -u <user> -p <database> < database/sql/001_schema.sql
mysql -h <host> -u <user> -p <database> < database/sql/002_seed.sql
```

---

## Docker Setup (App + MariaDB + Discord Bot)

Start all services:

```bash
docker compose up --build -d
```

View logs:

```bash
docker compose logs -f app
docker compose logs -f discord-bot
docker compose logs -f mariadb
```

Stop services:

```bash
docker compose down
```

Website URL:

- `http://localhost:8080`

---

## Discord Bot Setup Notes

Required bot permissions/intents:

- **Guilds** intent
- **Server Members Intent** (Privileged Intent) enabled in Discord Developer Portal

Recommended bot permissions on your server:

- View Channels
- Read Member List

Setup steps:

1. Create Discord application and bot.
2. Enable Server Members Intent.
3. Invite bot to your guild/server (ID: `1462238148632252436`).
4. Put bot API key, guild ID, and downloads channel ID in `.env`.
5. Define admin role IDs in `DISCORD_ADMIN_ROLE_IDS` (`Guild Master`: `1462238620449771594`, `Guild Officer`: `1462238763072880888`).
6. Run `docker compose up --build -d`.

---

## Stormforge API Setup Notes

1. Add your Stormforge API key to `.env` as `STORMFORGE_API_KEY`.
2. Confirm the exact endpoint(s) available for your account profile.
3. If needed, adjust request URL or payload parsing in:
   - `app/Services/StormforgeService.php`

Useful checks:

```bash
# Check API reachability (example)
curl -H "Authorization: Bearer <STORMFORGE_API_KEY>" \
  "https://logs.stormforge.gg/api/guild/roster?guild=Cause%20we%20Can&realm=<realm>"
```

---

## GHCR Publish

The workflow `.github/workflows/docker-publish.yml` pushes image tags to:

```text
ghcr.io/<owner>/<repo>:latest
```

Manual local build/push example:

```bash
docker build -t ghcr.io/<owner>/<repo>:latest .
docker push ghcr.io/<owner>/<repo>:latest
```

---

## Project Structure (high level)

- `app/Http/Controllers/*` — auth, blog, calendar, roster, downloads controllers
- `app/Http/Middleware/EnsureCalendarAdmin.php` — calendar admin access control
- `app/Support/CalendarPermissionResolver.php` — resolves role-based calendar permissions
- `app/Services/StormforgeService.php` — Stormforge roster integration
- `app/Services/DiscordDownloadsService.php` — Discord downloads feed integration
- `database/migrations/*` — Laravel migration files
- `database/sql/*` — external MariaDB SQL files
- `bot/*` — Discord role sync bot
- `docker-compose.yml` — app + DB + bot stack
- `.github/workflows/docker-publish.yml` — GHCR image publishing
