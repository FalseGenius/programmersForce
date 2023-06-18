# Spatie- Roles and Permission | User IP/Location APIs 

## Project Description

This project leverages spatie package in order to assign roles and permissions to a user. User is added to the system by a super-admin [A role is assigned here]. Once added, user can trigger checkin and checkout APIs.

Checkin API - Captures authorized user's ip, and checkin time.
Checkout API - Captures authorized user's ip, calculates their stay_duration and saves the details in postgreSQL.


### To spin up the server

Clone the repo and run the following commands

Execute:
```bash
composer install
php artisan migrate:fresh --seed
php artisan serve 
```




### Login and Register endpoints

Login Endpoint is available at "http://127.0.0.1:8000/api/login" via POST request. 

Inputs required (coming from frontend | postman):

```bash
{
    "email":"superadmin@123.com",
    "password":"qazQAZ123"
}
```

Output 

```bash
{
    "token":<authToken>
}
```


Once the super-admin logs in, they can register users and assign them roles [admin or user]. Once user is registered, they can log into the system.
User with role "admin" is allowed CRUD operations, and they can register users as well, but they cannot assign roles. 
User with role "user" is allowed to view their data only. They do not have sufficient permissions for other operations.


Register Endpoint is available at "http://127.0.0.1:8000/api/register" via POST request. 

Inputs required (coming from frontend | postman):

```bash
{
    "username":"hello",
    "email":"hello@123.com",
    "password":"qazQAZ123",
    "role":"user" // Only super-admin is allowed to include this. If admin includes this, default role [default role: user] gets used
}
```


### CRUD endpoints [Check app/Http/Controllers/UserController for more details]

#### Auth token required for CRUD APIs and Checkin/Checkout APIs

View all users -  Endpoint is available at "http://127.0.0.1:8000/api/login" via GET request.
 - Super-admin can view all users
 - User with role "admin" can view all all users except super-admin details
 - User with role "user" is not authorized to view any other user

View one user -  Endpoint is available at "http://127.0.0.1:8000/api/login/<id>" via GET request.
 - Super-admin can view any user by providing the id
 - User with role "admin" can view any user by providing id [Super-admin excluded]
 - User with role "user" is authorized to view their own information by providing id
    
Delete user -  Endpoint is available at "http://127.0.0.1:8000/api/users/<id>" via DELETE request.
 - Super-admin can delete any user by providing the id
 - User with role "admin" can delete any user by providing id [Super-admin and other admins excluded]
 - User with role "user" is not authorized to trigger this API
 
Update role -  Endpoint is available at "http://127.0.0.1:8000/api/users/<id>" via PUT request.
 - Only Super-admin can edit user roles by providing the id and input field "role"
    


### Checkin and Checkout APIs
    
Checkin Endpoint - Available at http://127.0.0.1:8000/api/checkin via GET request
Checkout Endpoint - Available at http://127.0.0.1:8000/api/checkout via GET request
    
Checkin API saves user ip, location and checkin time to the database.
Checkout API calculates the stay_duration of a user, and checks them out of the system [Ends their session].

At the end of each day, stay_duration of each user is summmed-up and stored [View app\Console\Commands\CalculateDailySessions.php for details]
