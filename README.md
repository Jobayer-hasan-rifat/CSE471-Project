# REMS - Reservation and Event Management System

## About REMS

REMS (Reservation and Event Management System) is a comprehensive web-based solution designed specifically for BRAC University's Office of Co-curricular Activities (OCA) to efficiently manage club activities, events, and reservations. This system streamlines the communication and management processes between OCA, clubs, and administrative staff.

### User Roles
1. **OCA (Office of Co-curricular Activities)**
   - Manage all club activities
   - Review and approve event requests
   - Handle budget allocations
   - Create announcements
   - Process transactions
   - Real-time chat with clubs
   - Monitor club performances

2. **Clubs**
   - Create and manage events
   - Access central calendar for event planning
   - Submit fund requests
   - Manage club information and profiles
   - Communicate with OCA
   - View venue availability

3. **Admin**
   - Manage venue inventory (Auditorium, Theatre Room, etc.)
   - Add new clubs to the system
   - User management
   - System configuration

### Key Features
- Centralized Event Calendar
- Venue Management System
  - Auditorium booking
  - Theatre Room reservation
  - Multipurpose Hall scheduling
  - Club Room allocation
- Budget Management
- Real-time Chat Communication
- Club Information Management
- Event Request and Approval System
- Announcement System
- Transaction Processing
- User Authentication and Authorization

### Benefits
- Streamlined club management process
- Efficient event scheduling and conflict prevention
- Improved communication between OCA and clubs
- Centralized budget tracking and management
- Paperless transaction processing
- Easy venue reservation system
- Real-time updates and notifications
- Transparent club activities monitoring

## Technologies Used

### Backend Framework
- Laravel 10.x (PHP Framework)
- MySQL Database

### Frontend Technologies
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- Blade Template Engine
- jQuery

### Additional Tools & Libraries
- Laravel Mix (Asset Compilation)
- Laravel Sanctum (API Authentication)
- Laravel Echo (Real-time Events)
- Pusher (WebSocket Integration)

## Installation & Setup

1. Clone the repository:
```bash
git clone https://github.com/yourusername/REMS.git
cd REMS
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in .env file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run database migrations:
```bash
php artisan migrate
```

8. Compile assets:
```bash
npm run dev
```

9. Start the development server:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Conclusion

REMS is a tailored solution for BRAC University's OCA department that revolutionizes how club activities and events are managed. By providing a centralized platform for event management, venue booking, and club administration, it significantly reduces manual workload and improves efficiency. The system's role-based access control ensures secure and appropriate access to features, while its real-time communication capabilities enhance collaboration between OCA and clubs. This comprehensive solution helps maintain organized and efficient club operations within BRAC University.
