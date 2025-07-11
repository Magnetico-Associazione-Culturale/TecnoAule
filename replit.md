# TecnoAule - Room Booking System

## Overview

TecnoAule is a modern web application for booking rooms within the "Tecnopolo" complex (approximately 1500 sqm), managed by "Magnetico Associazione Culturale". The system provides a booking.com-style interface for room reservations with interactive mapping, user management, and administrative controls using PHP backend with SQLite database.

## User Preferences

Preferred communication style: Simple, everyday language.

## Recent Changes

**July 11, 2025**: Implemented separate tessera reference system from registered users
- Renamed members table to "tessere" for tessera reference database (159 authentic records)
- Created new "registered_users" table for actual user accounts
- Modified registration system to validate tessera existence and prevent duplicate associations
- Updated login/authentication to use JOIN between registered_users and tessere tables
- Separated tessera database (reference only) from registered users system
- Fixed admin dashboard to display contact information from new user structure
- Updated all database queries to use new architecture throughout the system

**July 11, 2025**: Complete database migration and contact management system
- Migrated complete member database from Excel file with 159 authentic records
- Added phone field to members table for comprehensive contact information
- Enhanced admin panel to display email and phone contacts for each booking
- Implemented clickable contact links (mailto: and tel:) in admin interface
- Removed all test/placeholder data while preserving admin access (admin/admin123)
- Fixed interactive SVG map scrolling issues and visual improvements
- Added room images to map information panel for better user experience
- Optimized database structure with foreign key constraints
- Created automated Excel import system using Python/pandas integration
- Enhanced booking management with direct contact capabilities for administrators
- Updated admin panel column header to "Utente & Contatti" for clarity

**July 10, 2025**: Complete redesign with modern navigation structure and mobile optimizations
- Implemented top header with Magnetico branding and user actions
- Added bottom navigation bar with 5 sections: Home, Mappa, Area Riservata, WhatsApp, Virtual Tour
- Created virtual tour page with interactive placeholder and area navigation
- Updated responsive design for mobile-first experience
- Enhanced hero section with improved call-to-action buttons
- Added mobile popup functionality for interactive map with touch-friendly room details
- Fixed logout functionality with dedicated logout.php page
- Implemented bulleted equipment lists in mobile popups with black price display
- Admin login uses separate modal with username/password (admin/admin123)

## System Architecture

### Frontend Architecture
- **Technology Stack**: Modern web technologies with responsive design
- **Styling**: CSS custom properties with orange (#ff6600) and black brand colors
- **JavaScript Modules**: 
  - `main.js` - Core utilities and common functionality
  - `booking.js` - Booking flow and room selection logic
  - `map.js` - Interactive map with room visualization
- **UI Pattern**: Booking.com-inspired interface with mobile-first responsive design

### Backend Architecture
- **Authentication**: JWT-based authentication system
- **User Roles**: Regular users and administrators with role-based access control
- **API Design**: RESTful endpoints for room management, bookings, and user operations

### Key Design Decisions

**Monolithic Frontend Structure**: Single-page application approach with modular JavaScript files for better maintainability and faster loading.

**Component-Based UI**: Reusable UI components following Bootstrap-style patterns for consistency across the application.

**Interactive Mapping**: SVG-based room visualization allowing users to see physical layout and click rooms directly for booking.

## Key Components

### User-Facing Features
1. **Homepage**: Two primary actions - "Prenota un'aula" (Book a room) and "Mappa" (Map view)
2. **Room Booking System**: 
   - Room listing with photos, capacity, and amenities
   - Date/time selection with availability checking
   - Membership discount system (100% discount for cardholders)
   - Real-time price calculation
3. **Interactive Map**: Visual representation of Tecnopolo layout with clickable room markers
4. **User Account Management**: Registration, login, and booking history

### Administrative Features
1. **Booking Management**: Approve/reject booking requests
2. **Room Administration**: Add/edit rooms with photos, capacity, amenities, and pricing
3. **Calendar Management**: Block dates for maintenance or reserved events
4. **Price Management**: Dynamic pricing control per room

### Core Entities
- **Rooms**: Name, capacity, amenities (projector, interactive whiteboard, A/C, audio), photos, pricing
- **Bookings**: Date, time slots, user, room, status (pending/approved/rejected), final price
- **Users**: Registration info, membership status, booking history
- **Memberships**: Tessera (card) number system for discount eligibility

## Data Flow

### Booking Process
1. User selects date/time filters → System checks room availability
2. User browses available rooms → System displays real-time pricing
3. User enters membership info (optional) → System applies discounts
4. Booking submission → Admin approval workflow → Confirmation

### Administrative Workflow
1. Admin receives booking requests → Review and approve/reject
2. Admin manages room inventory → Updates reflect immediately in booking system
3. Admin sets pricing → Dynamic calculation in booking flow

## External Dependencies

### Core Libraries
- **Bootstrap**: UI framework for responsive design and components
- **Font Awesome**: Icon library for UI elements
- **Modern CSS**: Custom properties for brand theming

### Browser APIs
- **Local Storage**: Session management and temporary data storage
- **Fetch API**: HTTP requests for backend communication
- **DOM Manipulation**: Dynamic content updates and interactive features

## Deployment Strategy

### Architecture Decisions
- **Static Assets**: CSS and JavaScript files served from `/assets` directory
- **Modular Loading**: JavaScript modules loaded on-demand for better performance
- **Progressive Enhancement**: Core functionality works without JavaScript, enhanced with interactive features

### Performance Considerations
- **Image Optimization**: Room photos optimized for web delivery
- **Lazy Loading**: Map and room images loaded as needed
- **Caching Strategy**: Static assets cached with appropriate headers

### Security Measures
- **Input Validation**: Client-side and server-side validation for all user inputs
- **Authentication**: Secure JWT token management
- **Role-Based Access**: Administrative functions protected by role verification
- **CSRF Protection**: Form submissions protected against cross-site request forgery

The application follows modern web development best practices with emphasis on user experience, administrative efficiency, and maintainable code structure. The booking system prioritizes clarity and ease of use while providing comprehensive management tools for the Magnetico Associazione Culturale team.