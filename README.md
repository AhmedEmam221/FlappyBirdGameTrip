# Flappy Bird Competition Platform

A complete, production-ready web application for a Flappy Bird-style game competition where the top 3 players with the highest valid scores win prizes.

## Table of Contents
1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Running the Application](#running-the-application)
4. [Admin Guide](#admin-guide)
5. [API Endpoints](#api-endpoints)
6. [Security](#security)
7. [Known Limitations](#known-limitations)

## Installation

### Prerequisites
- PHP 8.0+
- MySQL 8.x
- Composer
- Node.js (for asset building, optional - Tailwind CDN used)

### Setup Steps

1. **Clone the repository** (or extract the project)
   ```
   cd C:\xampp\htdocs\KhaledHult
   ```

2. **Install PHP dependencies**
   ```
   composer install
   ```

3. **Configure the environment**
   ```
   cp .env.example .env
   ```
   Edit `.env`:
   ```
   APP_NAME=Flappy Challenge
   APP_URL=http://localhost
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=khaledhult
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate application key**
   ```
   php artisan key:generate
   ```

5. **Run migrations**
   ```
   php artisan migrate
   ```

6. **Create first admin user**
   ```
   php artisan tinker
   User::create(['name' => 'Admin', 'email' => 'admin@flappychallenge.com', 
     'password' => Hash::make('password'), 'role' => 'admin'])
   ```

7. **Start the development server**
   ```
   php artisan serve
   ```

8. **Access the application**
   - Public: http://127.0.0.1:8000
   - Admin: http://127.0.0.1:8000/admin (login with created credentials)

## Configuration

### Environment Variables (.env)
```
APP_NAME=Flappy Challenge
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=khaledhult
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MEMCACHED_HOST=127.0.0.1
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

INSTAGRAM_PAGE_URL=https://www.instagram.com/YOUR_PAGE_USERNAME/
WEBSITE_DESCRIPTION=Play, score, compete, and win!

COMPETITION_STATUS=active
NUM_WINNERS=3
MAX_ACCEPTED_SCORE=999999
MAX_SUBMISSIONS_PER_SESSION=5
```

### Admin Configuration
All settings are configurable via the Admin Dashboard (`/admin/settings`):
- Website name and description
- Instagram page URL (must be valid HTTPS Instagram profile URL)
- Favicon upload (ICO, PNG, SVG formats, max 2MB)
- Competition status (active/paused/finished)
- Maximum accepted score
- Maximum submissions per session
- Competition start/end dates
- Number of leaderboard entries
- Prize descriptions for 1st, 2nd, and 3rd place
- Tie-breaking rule (earliest submission or random)
- Whether new submissions are allowed
- Contact information and competition rules

## Game Rules

### Player Flow
1. Visitor opens website and plays Flappy Bird-style game
2. When game ends, final score is displayed
3. Player enters Instagram username (validated format: `@username` or `username`)
4. Player clicks "Follow Us on Instagram" button which opens the configured Instagram URL in a new tab
5. Player follows the Instagram page and returns to submit
6. Player submits Instagram username and score
7. Submission enters as `pending_verification` awaiting admin review
8. Administrator approves or rejects the entry
9. Approved scores appear on the public leaderboard

### Score Validation
Scores are server-validated against:
- Game session expiry and token verification
- Maximum accepted score configuration
- Elapsed time from session start
- Duplicate session submission prevention
- Negative scores and malformed values are rejected

### Verification Workflow
- Instagram follow is **self-reported**, not programmatically verified
- Website opens Instagram URL in new tab when player clicks the follow button
- Player confirms follow and submits
- Entry marked `pending_verification` until admin reviews
- Admin can approve or reject with review notes
- Only approved entries appear on the official leaderboard

### Tie-Breaking
When scores are tied, the earliest submission time determines higher ranking.

## Admin Guide

### Dashboard Overview
Summary cards displaying:
- Total game sessions
- Total score submissions
- Pending submissions (awaiting review)
- Approved submissions
- Rejected submissions
- Unique Instagram usernames
- Instagram link clicks
- Current top three players

### Score Management
Table with:
- Instagram username and submitted score
- Verification status (pending/approved/rejected)
- Submission timestamp and game session reference
- Review notes and rejection reasons
- Actions: Approve, Reject, Flag for review
- Search by Instagram username
- Filter by status, score range, and date range
- Sort and paginate submissions
- Export entries to CSV

### Finalizing Competition
When the competition ends:
1. Navigate to `/admin/competition/finalize`
2. View all approved entries sorted by score then submission time
3. Top 3 winners are displayed
4. A snapshot of final standings is preserved and cannot be silently changed
5. Announce winners publicly

## API Endpoints

### Public Routes
| Method | Path | Description |
|--------|------|-------------|
| GET | `/` | Landing page |
| GET | `/play` | Flappy Bird game |
| GET | `/game-session/create` | Create game session (JSON) |
| POST | `/game-session` | Store game session |
| POST | `/score/submit` | Submit score |
| GET | `/leaderboard` | Leaderboard page |
| GET | `/rules` | Competition rules |
| GET | `/privacy` | Privacy policy |
| GET | `/instagram/visit` | Record click, redirect to Instagram |

### Admin Routes (auth + admin middleware)
| Method | Path | Description |
|--------|------|-------------|
| GET | `/admin` | Dashboard overview |
| GET | `/admin/entries` | Submissions management |
| GET | `/admin/players` | Players management |
| GET | `/admin/analytics` | Competition analytics |
| GET | `/admin/settings` | Website settings |
| POST | `/admin/settings` | Save settings |
| POST | `/admin/favicon` | Upload favicon |
| POST | `/admin/competition/finalize` | Finalize winners |
| POST | `/admin/entries/{entry}/approve` | Approve entry |
| POST | `/admin/entries/{entry}/reject` | Reject entry |
| POST | `/admin/entries/{entry}/flag` | Flag entry |
| GET | `/admin/exports/entries.csv` | Export to CSV |

## Security Protections

### Implemented
- Server-validated game sessions (prevents client-side score manipulation)
- Rate limiting per session (configurable)
- Duplicate session submission prevention
- CSRF protection on all forms
- Admin middleware restricting `/admin` to authenticated admins only
- XSS escaping on all user-generated content (usernames)
- Instagram URL validation (HTTPS-only)
- Password hashing via Laravel's bcrypt
- Secure session settings in production
- Audit logging of all admin actions
- Rate-limited login attempts

### Known Limitations
1. **Instagram verification**: Self-reported only - cannot programmatically verify follows via ordinary website means
2. **Score anti-cheat**: Server-validated but not perfect - determined attackers may manipulate browser-side game state
3. **Favicon processing**: Basic upload, no image optimization or multiple size generation
4. **No API rate limiting beyond session-level**: IP-level rate limiting not implemented
5. **Missing seeders/factories**: Database seeders not created - manual testing recommended
6. **Email notifications**: Not implemented - admin must manually check dashboard

## Deployment

### Production Checklist
1. Set `APP_DEBUG=false` in `.env`
2. Configure proper web server (Apache/Nginx) rewrite rules for Laravel
3. Set up HTTPS certificate
4. Configure task scheduler if using queues
5. Set up automated backups of MySQL database
6. Update `INSTAGRAM_PAGE_URL` to actual Instagram profile
7. Replace favicon with actual branding
8. Set competition start/end dates in admin settings
9. Review and configure max accepted score
10. Test all flows before going live

### Hosting Recommendations
- Laravel Forge or Ploi for easy Laravel deployment
- Shared hosting may work but requires PHP 8.0+ and MySQL 8.x
- VPS recommended for full control over configuration

---
Built with Laravel 9, PHP 8.0, MySQL, HTML5 Canvas, and vanilla JavaScript."# FlappyBirdGameTrip" 
