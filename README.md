# Companies Restful API

Built with Lumen and PostgreSQL

The API to share the company's information for the logged users.

## Installation

Copy .env.example to .env file.

XDebug added for local development.
XDEBUG_HOST need to be specified for particular OS in an .env file.
For example `host.docker.internal` for Windows.
Port for XDebug used 9009 by default but could be changed in .env file.

Need to add a line to the hosts file
`127.0.0.1 api.loc`

Run `./bin/init` file to up docker containers and run migrations.
Next time it is enough for start run `./bin/run` script.

## Usage

### Endpoints
Register user.
```
POST http://api.loc/api/user/register
Content-Type: application/json

{"email": "correct@email.com", "first_name": "first_name", "last_name": "last_name", "phone": "phone", "password": "password"}
```

Sign-in user
```
POST http://api.loc/api/user/sign-in
Content-Type: application/json

{"email": "correct@email.com", "password": "password"}
```

Password recovery
```
POST http://api.loc/api/user/recover-password
Content-Type: application/json

{"email": "email"}
```

Update password
```
POST http://api.loc/api/user/update-password
Content-Type: application/json

{"token": "password_restore_token", "password": "password", "password_confirmation": "password"}
```

Get list of user`s companies
```
GET /api/user/companies
Authorization: api_token
```

Create a company for authenticated user
```
POST /api/user/companies
Content-Type: application/json
Authorization: api_token

{"title": "title", "phone": "phone", "description": "description"}
```
