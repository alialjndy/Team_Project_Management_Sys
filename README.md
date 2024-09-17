# Team Project Management System

## Overview

The Team Project Management System is designed to facilitate project management, allowing users to create projects, assign tasks, track activities, and manage users. This system is ideal for organizing work within teams, providing clear role definitions and permissions.

## Features:

-   Project Management: Create and manage projects.
-   Task Management: Assign and track tasks.
-   Activity Tracking: Monitor activities associated with projects and tasks.
-   User Management: Manage user roles and permissions.

## Roles and Permissions

### System-Level Roles:

1. ### Admin:

    - Can create projects and assign roles at the system level.
    - Can perform CRUD (Create, Read, Update, Delete) operations on users.

2. Manager:

    - Assign projects to their own manager

3. Member:

    - Includes roles like Developer and Tester with specific permissions.

## Project-Level Roles:

1. ### Project Manager:

    - Can perform CRUD operations on tasks within their project.
    - Can assign tasks to project members, such as Developers and Testers.
    - Get participant user in project

2. ### Developer:

    - Can update the status of tasks within the project they are working on.

3. ### Tester:

    - Can add notes to tasks.

## Requirments

-   PHP Version 8.3 or earlier
-   Laravel Version 11 or earlier
-   composer
-   XAMPP: Local development environment (or a similar solution)

## API Endpoints

1. ### Authentication

    - POST /api/login: Log in with email and password
    - POST /api/logout: Log out the current user
    - GET /api/me: display info currently user

2. ### UserManagement

    - POST /api/ManagementUser : Create user by (Admin only)
    - PUT /api/ManagementUser/{user_id} : Update user by id (Admin only)
    - GET /api/ManagementUser : Get All User (Admin only)
    - GET /api/ManagementUser/{user_id} : GET user by ID (Admin only)
    - DELETE /api/ManagementUser{user_id} : soft DELETE user by id (Admin only)
    - POST /api/forceDelete/{user_id} : Force DELETE user by id (Admin only)
    - POST /api/RestoreUser/{user_id} : Restore soft deleted user by id (Admin only)

3. ### ProjectManagement

    - POST /api/ManagementProject : Create project by (Admin only)
    - PUT /api/ManagementProject/{project_id} : Update project by id (Admin only)
    - GET /api/ManagementProject : Get All project (Admin only)
    - GET /api/ManagementProject/{project_id} : GET project by ID (Admin only)
    - DELETE /api/ManagementProject{project_id} : soft DELETE project by id (Admin only)
    - POST /api/ManagementProject/forceDelete/{project_id} : Force DELETE project by id (Admin only)
    - POST /api/ManagementProject/RestoreProject/{project_id} : Restore soft deleted project by id (Admin only)

    - POST /api/Assign_System_Role : Assign System Role To User (Admin only)
    - POST /api/projects/{projectID}/assign-user : Assign Project To Manager (manager system level only)
    - GET /api/projects/{projectID}/users : Get All Participant in project
    - POST /api/project/{project_id}/AssignTaskToDeveloper : Assign Task In Project To Developer (manger only (project lever))
    - GET /api/getLastTaskInProject/{project_id} : Get Last Task in the project
    - GET /api/getOldTaskInProject/{project_id} : Get End Task in the project
    - GET /api/getHighestPriority : Get highest priority Task in the project

4. ### Activity
    - POST /api/Activity : create Activity
    - PUT /api/Activity/{Activity_id} : update Activity
    - GET /api/Activity : Get All Activity
    - GET /api/Activity/{Activity_id} : Get Activity by id

## Postman Collection:

You can access the Postman collection for this project by following this [link](https://documenter.getpostman.com/view/37833857/2sAXqqcNSX). The collection includes all the necessary API requests for testing the application.
