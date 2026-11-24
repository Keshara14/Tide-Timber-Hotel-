# Tide & Timber – Coastal Villa Booking System

Tide & Timber is a complete villa reservation website for a fictional boutique coastal villa in Weligama, Sri Lanka. It uses HTML5, CSS3, vanilla JavaScript, core PHP, PDO and MySQL.

## Implemented features

- Responsive public villa website
- Dynamic rooms and room details
- Date and guest availability search
- Guest registration and login
- Session-based role authentication
- Booking requests with server-side price calculation
- Personal booking history and Pending booking cancellation
- Administrator dashboard and room management
- Reservation filtering and status management
- Overlap protection before confirming a reservation

## Project structure

Public PHP pages are in the project root. The `admin` folder contains administrator pages, `includes` contains shared database and layout code, `actions` processes forms, `assets` contains CSS, JavaScript and local SVG image fallbacks, and `database` contains the MySQL setup script.

## Requirements

- Windows, macOS or Linux
- XAMPP or WAMP with Apache, PHP 8 and MySQL
- A modern web browser

## XAMPP installation

1. Install XAMPP.
2. Copy the `coastal-villa-booking` folder into `C:\xampp\htdocs\`.
3. Start Apache and MySQL from the XAMPP Control Panel.
4. Open `http://localhost/phpmyadmin`.
5. Import `coastal-villa-booking/database/db_structure.sql`.

If using WAMP instead, copy the folder into `C:\wamp64\www\` and start WAMP services.

## Database setup

The SQL file creates `coastal_villa_db`, all required tables, foreign keys, indexes and sample rooms. Database settings are in `includes/config.php`:

- Host: `localhost`
- Database: `coastal_villa_db`
- Username: `root`
- Password: blank

## Sample accounts

The SQL file installs both accounts with the password `password`.

- Guest: `guest@example.com` / `password`
- Administrator: `admin@tideandtimber.lk` / `password`

Change these passwords after deployment. PHP `password_hash()` is used for new registrations.

## Running the project

Visit:

`http://localhost/coastal-villa-booking/`

The administrator login redirects to:

`http://localhost/coastal-villa-booking/admin/dashboard.php`

## Booking process

1. A visitor searches with check-in, check-out and guest count.
2. PHP verifies dates and finds active rooms with enough capacity.
3. Rooms are removed if a Confirmed booking overlaps the requested dates.
4. Logged-in guests submit the booking form.
5. PHP recalculates nights and price using the room price from MySQL.
6. The booking is stored with Pending status.
7. An administrator can Confirm or Cancel it.
8. Confirmation is blocked if another Confirmed reservation already overlaps.

## Security

- PDO prepared statements for database queries
- Password hashing with `password_hash()`
- Password verification with `password_verify()`
- Session login and session-ID regeneration
- Role checks for administrator pages
- Server-side form and date validation
- HTML escaping with `htmlspecialchars()`
- Guest booking ownership checks

## Testing

1. Register a guest and try duplicate registration.
2. Log in and search for rooms.
3. Submit a Pending booking and confirm it appears in My Bookings.
4. Test cancellation of that Pending booking.
5. Log in as administrator and manage rooms and bookings.
6. Attempt to confirm two overlapping reservations to verify overlap protection.
7. Test the mobile menu and resize the browser to tablet and phone widths.

For local production-style behavior, disable PHP display_errors in `php.ini` and keep `log_errors` enabled.

## Images

The project includes original local SVG placeholders for the villa and room cards. Replace these files or update `room_image` values with the `.jpg`, `.png` or `.svg` paths of your own licensed images. No external image service is required.

## AI assistance declaration

This project was created with AI assistance for code generation and project organization. The student is responsible for reviewing the implementation, importing the database, running the application and explaining the code during the practical assessment.
