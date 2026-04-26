# Changelog

Plain-language summary of recent changes, based on the project commit history through April 26, 2026.

## Latest Updates

### New School Updates / School News Module

The site now has a dedicated place for school updates, announcements, news, and event-related posts.

What this means for users:
- Visitors can browse school updates from a public page.
- Staff can create and manage updates from the admin side.
- Updates can include formatted text, featured images, and images inside the content.
- Updates can be searched and filtered, making it easier to find older posts.
- The main navigation now includes the school updates section.

### School Years Can Now Be Managed

School years are now handled as their own records in the system.

What this means for users:
- Staff can add school years.
- School years can be updated when details change.
- School years can be removed if they are not already being used by calendar records.
- Calendar and gallery content can be organized by school year more clearly.

### Improved School Calendar

The school calendar page has been redesigned and made easier to use.

What this means for users:
- Events are easier to scan by month.
- The calendar works better on mobile phones and smaller screens.
- Logged-in staff get a clearer management area for calendar entries.
- Calendar lists now support pagination, so large sets of events are easier to manage.

### Better Galleries Page

The galleries page has been redesigned with a cleaner and more responsive layout.

What this means for users:
- Gallery cards are easier to browse.
- Galleries can be filtered by academic year.
- Missing or broken images now show a fallback image instead of leaving an awkward blank space.
- The full gallery view has been improved for a better browsing experience.

### New Recent Activity Section on the Homepage

The homepage now shows recent activity from the school.

What this means for users:
- Visitors can quickly see recent news, events, and gallery activity.
- If the system does not have live content yet, the homepage can still show school-branded preview content instead of looking empty.

### Better Login Support for Existing Users

The login system was improved so existing accounts continue to work after security upgrades.

What this means for users:
- Existing users can still sign in with their current passwords.
- After a successful login, the system quietly upgrades the password storage to the newer security method.
- Users do not need to manually reset their password just because the system was upgraded.

### Stronger Admin Security

Admin and staff access has been tightened.

What this means for users:
- Sensitive actions now require the right admin permissions.
- Forms and admin actions have stronger validation.
- Rich text content is cleaned before it is shown, helping prevent unsafe content from being published.
- Uploads and API requests have better safeguards.

### Frontend Website Foundation Added

The public website and admin-facing frontend were built out with the main school pages and reusable interface parts.

What this means for users:
- The site now has pages for home, about, academics, school calendar, school operations, galleries, and school updates.
- Login and logout flows are available.
- Shared layout, navigation, footer, loading, and notification features are in place.
- The design works across common desktop and mobile screen sizes.

### Backend System Foundation Added

The backend system was set up to store and manage the school data.

What this means for users:
- The system can now manage users, school years, school calendars, announcements, galleries, and school updates.
- The database structure and starter data are in place.
- API endpoints exist so the website can load and manage school information.

### Easier Deployment

A deployment bundle process was added.

What this means for project maintainers:
- The project can generate the frontend and package the backend and frontend together.
- A zip file is created for easier upload or handoff.
- Sensitive backend files such as the local environment file are excluded from the deployment package.

### Maintenance and Setup Improvements

Several behind-the-scenes improvements were made to keep the project easier to run and maintain.

What this means for project maintainers:
- Development settings were updated for local frontend and backend communication.
- Backend dependencies were updated to newer stable versions.
- Quality check scripts were added for both frontend and backend.
- Testing support was added for backend, frontend, browser, and load testing.
- Project documentation was added under the `tasks/` and `docs/` folders.
