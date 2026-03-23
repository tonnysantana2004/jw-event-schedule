# JW Event Schedule

A simple WordPress plugin to manage events: custom post type, event types, event details, a simple attendance system, and email notifications when events are created, updated or canceled.

This README explains installation, basic usage, available features and REST examples so a non-technical user can get started quickly.

## Live Project

1. You can access the project in my website: [Click to access](https://jackwestin.tonnysantana.com/events)

---

## Quick Install

1. Upload

   - Download the zip file of the repository, and upload it on your wordpress website.

2. Activate
	- Go to Plugins → Installed Plugins and activate "JW Event Schedule".

## Changing the source code

1. Run the following commands whenever you change some gutenberg block
	- `npm run build` (or `npm run start` while developing)
	- `composer dump-autoload` or `composer require/install` if you change vendor code.

---

## What this plugin provides

- A new custom post type: `Event` (slug: `event`, archive: `/events`)
- A taxonomy: `Event Type` (slug: `event-type`)
- Custom meta fields for events:
  - `event_date` — timestamp for event date and time
  - `event_location` — free text location
  - `attendance_list` — an array of user IDs who confirmed attendance
- Gutenberg blocks:
  - `Listing Grid` — a block to show a grid/listing of events on any post/page
- Private REST endpoints to toggle attendance:
  - `POST /wp-json/jwes/v1/attendance` — add an attendance entry
  - `DELETE /wp-json/jwes/v1/attendance` — remove an attendance entry
- Notification emails:
  - Sends email notifications when an event is created (published), updated, or moved to trash (canceled) so that attendees can be informed.
- Admin improvements:
  - Event list columns shows the "Event Date" and "Event Location" meta values
  - Archive filtering by a `date_range`

---

## Creating and Managing Events (Admin)

1. In the admin menu go to Events → Add New.
2. Fill in:
   - Title
   - Excerpt
   - Content (description of the event)
   - Assign an Event Type
3. Event meta:
   - Use the editor sidebar (SideBar block or block editor meta fields) to set:
     - Event Date
     - Event Location
4. Publish the event.

After publishing:

- The event will appear on the events archive (`/events`) and already with a custom template pre-made.
- The attendance list is stored in a post meta key `attendance_list`.

---

## Frontend: Attending an Event

- Only logged-in users can toggle attendance.

REST endpoints:

- Add attendance

  - POST /wp-json/jwes/v1/attendance
  - Payload:
    - `user_id` — integer (current user)
    - `post_id` — integer (event post ID)
  - Permission: user must be logged in
  - Response: JSON with message, updated `attendance` array and timestamp

- Remove attendance
  - DELETE /wp-json/jwes/v1/attendance
  - Payload:
    - `user_id` — integer
    - `post_id` — integer
  - Permission: user must be logged in
  - Response: JSON with message, updated `attendance` array and timestamp

## Gutenbeg

- Listing Grid (`jw-event-schedule/listing-grid`)

  - Place on any page to display a grid of events. The block is registered for use in the block editor.

- SideBar (`jw-event-schedule/sidebar`)
  - Editor sidebar integration that shows and allows editing of the event meta fields when editing an `event` post type.

---

## Admin Columns & Frontend Filtering

- The admin Events list shows two custom columns:

  - Event Date — formatted according to WordPress default timezone
  - Event Location

- Archive date filtering
  - You can filter the events archive via a `date_range` GET parameter. The plugin expects a range using the word `to`, for example:
    - `/events?date_range=2024-01-01 to 2024-01-31`
  - This adjusts the archive query to only return events whose `event_date` field falls between the provided start and end.

---

## Notifications

- The plugin hooks into post saves for the `event` post type and sends email notifications about:

  - New event published
  - Event updated
  - Event moved to trash (canceled)

- Notifications are sent to users related to the event (for example users in the attendance list). Emails use WordPress `wp_mail()`.

---

## Where to look in the code

- Main plugin file: `jw-event-schedule/jw-event-schedule.php`
- Post type and REST endpoints: `jw-event-schedule/src/PostType.php`
- Frontend scripts and styles: `jw-event-schedule/assets/frontend/`
- Gutenberg scripts, blocks, and build sources: `jw-event-schedule/src/blocks/` and `jw-event-schedule/build/blocks/`
- Notifications behavior: `jw-event-schedule/src/Notifications.php`
