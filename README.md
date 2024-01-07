# Task Management System
This project allows users to create, update and delete tasks. Users also have the ability to change the status of the task from `open` to `complete`.

<br />

# Table of Contents
- [Task Management System](#task-management-system)
- [Table of Contents](#table-of-contents)
  - [Features](#features)
  - [How to run project](#how-to-run-project)
  - [License](#license)

<br />

## Features
- User authentication and authorization.
- Users can perform CRUD actions on tasks.
- Users can update the status of a task.
- All authenticated routes are logged to the db.

<br />

## How to run project
1. Clone the git repository.
   ```
   git clone
   ```
2. Install composer dependencies by running the command below.
   ```
   composer install
   ```
3. Generate and application key.
   ```
    php artisan key:generate
   ```
4. Setup .env by duplicating the `.env.example` file into a `.env` file then insert the relevant environment variables.
5. Migrate the database.
   ```
    php artisan migrate
   ```
6. Start server by running the command `php artisan serve`.

<br />

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
