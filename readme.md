# Nofuzz Blog Tutorial
This is a tutorial on how to make a REST API application using the Nofuzz framework. 

The result of this tutorial is a number of endpoints, complete with middleware, authentication and reading & writing to the databases. 


---
## Installing all dependencies
After installing this tutorial to a new folder, you need to run composers udate to download all dependencies.

```txt
$ composer update
```

---
# Tutorial

## Design the application - Steps
The following steps were taken when designing this tutorial application:

1. Define the API
2. Create the Database (see [MySql Schema](schema.mysql.sql))
3. Create the Database objects & DAO objects
4. Create the Controllers
5. Create the Middleware
6. Define the Routes
7. Test it! 


## 1 - Define the API
Here wea re going to define the API endpoints that will make up the whole API. The API endpoints are grouped into logical groups as per below:

*Register*
```txt
POST    /blog/api/v1/register           Register a new Account
```
*Auth*
```txt
POST    /blog/api/v1/signin             Sign In (obtain session/token)
DELETE  /blog/api/v1/signout            Sign Out (remove session/token)
```
*Accounts*
```txt
GET     /blog/api/v1/accounts           Get list of Accounts
GET     /blog/api/v1/accounts/{id}      Get an Account
POST    /blog/api/v1/accounts           Create an Account
PUT     /blog/api/v1/accounts           Update an Account
DELETE  /blog/api/v1/accounts           Remove an account
```
*Blogs*
```txt
GET     /blog/api/v1/blogs              Get list of Blogs
GET     /blog/api/v1/blogs/{id}         Get a specific blog
POST    /blog/api/v1/blogs              Create new Blog
PUT     /blog/api/v1/blogs              Update a Blog
DELETE  /blog/api/v1/blogs              Remove a Blog
```
*Articles*
```txt
GET     /blog/api/v1/articles           Get list of articles
GET     /blog/api/v1/articles/{id}      Get a specific article
POST    /blog/api/v1/articles           Create new Article
PUT     /blog/api/v1/articles           Update an article
DELETE  /blog/api/v1/articles           Remove an article
```
*Comments*
```txt
GET     /blog/api/v1/comments           Get list of comments
GET     /blog/api/v1/comments/{id}      Get a specific comment
POST    /blog/api/v1/comments           Create new comment
PUT     /blog/api/v1/comments           Update a comment
DELETE  /blog/api/v1/comments           Remove a comment
```


## 2 - Create the Database
Please see [MySql Schema](schema.mysql.sql) for the complete Schema DDL. Use your favorite tool to create the database.

> This app uses a MySQL Database by default, but is easily converted to using Firebird, PostgreSql, Oracle or any other DB.

## 3 - Create the Database objects & DAO objects
In the `/app/Db` folder you will find all the classes that deal with the database, or represent a row in the database. The DAO objects are responsible for actually reading/writing to/from the DB. 

## 4 - Create the Controllers
In the `/app/Controllers` folder you will find all the Controller classes for each endpoint group. These controllers are:
- RegisterController.php
- SignInController.php
- SignOutController.php
- AccountsController.php
- BlogsController.php
- ArticlesController.php
- CommentsController.php

## 5 - Create the Middleware
In the `/app/Middleware` folder you will find all the Middleware classes. 

There are four types of Middleware:
- Common Before request handling
- Common After request handling
- Group specific Before request handling
- Group specific After request handling

### Common Before Middleware
The Middleware that is commonly run before any request processing:
- CorsBeforeMiddleware.php
- RequestIdBeforeMiddleware.php

### Common After Middleware
The Middleware that run After a request are:
- RequestIdAfterMiddleware.php
- ResponseLogAfterMiddleware.php
- ResponseTimeAfterMiddleware.php

### Group specific Before Middleware
The Middleware for the Public Route Group are:
- `none`

The Middleware for the Secure Route Group are:
- AuthBeforeMiddleware.php (except: SignIn/Register endpoints)

### Group specific After Middleware
No After Middleware defined.

## 6 - Define the Routes
We must define the routes or Endpoints the API will responsd to, and what code to run on each endpoint. Here we define two groups of endpoints: **Anonymous** and **Authenticated**.

Anonymous (public) endpoints:
> The public endpoints do not require Authentication information to be sent with the requests.

| URI                           | Controller |
|-------------------------------|----------------------------------------|
|`/blog/api/v1/register`        | `RegisterController`
|`/blog/api/v1/signin`          | `SignInController`

Authenticated (secure) endpoints:
> The secure endpoints require Authentication information to be sent with the requests.

| URI                           | Controller |
|-------------------------------|----------------------------------------|
|`/blog/api/v1/signout`         | `SignOutController`
|`/blog/api/v1/accounts[/{id}]` | `AccountsController`
|`/blog/api/v1/blogs[/{id}]`    | `BlogsController`
|`/blog/api/v1/articles[/{id}]` | `ArticlesController`
|`/blog/api/v1/comments[/{id}]` | `CommentsController`

### routes.json
```json
{
  "common": {
    "before": [
      "\\App\\Middleware\\RequestIdBeforeMiddleware",
      "\\App\\Middleware\\CorsBeforeMiddleware"
    ],
    "after": [
      "\\App\\Middleware\\RequestIdAfterMiddleware",
      "\\App\\Middleware\\ResponseTimeAfterMiddleware",
      "\\App\\Middleware\\ResponseLogAfterMiddleware"
    ]
  },
  "groups": [
    {
      "name": "public",
      "notes": "No access restrictions",
      "prefix": "/blog/api/v1",
      "before": [],
      "routes": [
        { "path":"/register", "handler":"\\App\\Controllers\\RegisterController" },
        { "path":"/signin", "handler":"\\App\\Controllers\\SignInController" }
      ]
      "after": []
    },
    {
      "name":"secure",
      "notes": "Basic Auth or JWT token needed to access",
      "prefix": "/blog/api/v1",
      "before": [
        "\\App\\Middleware\\AuthBeforeMiddleware"
      ],
      "routes": [
        { "path":"/signout", "handler":"\\App\\Controllers\\SignOutController" },
        { "path":"/accounts[/{id}]", "handler":"\\App\\Controllers\\AccountsController" },
        { "path":"/blogs[/{id}]", "handler":"\\App\\Controllers\\BlogsController" },
        { "path":"/articles[/{id}]", "handler":"\\App\\Controllers\\ArticlesController" },
        { "path":"/comments[/{id}]", "handler":"\\App\\Controllers\\CommentsController" }
      ],
      "after": []
    }
  ]
}
```

## 7 - Set config options (`config.json`)
## 8 - Test it!





When registering users, the passwords are hashed before they are stored, and the algorithm used is OpenSSL's SHA256.

The API will cover the following areas:
- Registering accounts
- User Sign-in/Sign-out
- Create, Edit, Delete of Blogs
- Create, Edit, Delete of Blog-Articles
- Create, Edit, Delete of Blog-Article-Comments

The API will __NOT__ cover the following:
- A user interface (web page) for the Blog
- EMail send/receive features


## Authentication and Authorization
The API will check the headers of incoming requests for `Basic Authentication` info, or a `JWT Token`.

Using the `Basic Authentication` is recommended only when a SSL connection is awailable to the REST API server.


## Middleware
- Basic Auth decoding
- JWT Token decoding


## Controllers
- AccountsController
- AuthController
- BlogsController
- ArticlesController
- CommentsController




## REST API Endpoints
*Auth*
```txt
POST    /blog/api/v1/auth               Sign In (obtain session)
DELETE  /blog/api/v1/auth               Sign Out (remove session)
```
*Accounts*
```txt
GET     /blog/api/v1/accounts           Get list of Accounts
GET     /blog/api/v1/accounts/{id}      Get an Account
POST    /blog/api/v1/accounts           Create an Account
PUT     /blog/api/v1/accounts           Update an Account
DELETE  /blog/api/v1/accounts           Remove an account
```
*Blogs*
```txt
GET     /blog/api/v1/blogs              Get list of Blogs
GET     /blog/api/v1/blogs/{id}         Get a specific blog
POST    /blog/api/v1/blogs              Create new Blog
PUT     /blog/api/v1/blogs              Update a Blog
DELETE  /blog/api/v1/blogs              Remove a Blog
```
*Articles*
```txt
GET     /blog/api/v1/articles           Get list of articles
GET     /blog/api/v1/articles/{id}      Get a specific article
POST    /blog/api/v1/articles           Create new Article
PUT     /blog/api/v1/articles           Update an article
DELETE  /blog/api/v1/articles           Remove an article
```
*Comments*
```txt
GET     /blog/api/v1/comments           Get list of comments
GET     /blog/api/v1/comments/{id}      Get a specific comment
POST    /blog/api/v1/comments           Create new comment
PUT     /blog/api/v1/comments           Update a comment
DELETE  /blog/api/v1/comments           Remove a comment
```

## Models
### Accounts
```json
{
  "id": 0,
  "created_dt": "string",
  "modified_dt": "string",
  "uuid": "string",
  "login_name": "string",
  "first_name": "string",
  "last_name": "string",
  "jwt_secret": "string",
  "pw_salt": "string",
  "pw_hash": "string",
  "pw_iterations": 0,
  "status": 0
}
```

### Tokens
```json
{
  "id": 0,
  "created_dt": "string",
  "modified_dt": "string",
  "account_id": 0,
  "sessionid": "string",
  "expires_dt": "string",
  "status": 0
}
```

### Blogs
```json
{
  "id": 0,
  "created_dt": "string",
  "modified_dt": "string",
  "uuid": "string",
  "account_id": 0,
  "title": "string",
  "description": "string",
  "status": 0
}
```

### Articles
```json
{
  "id": 0,
  "created_dt": "string",
  "modified_dt": "string",
  "uuid": "string",
  "blog_id": 0,
  "title": "string",
  "body": "string",
  "status": 0
}
```

### Comments
```json
{
  "id": 0,
  "created_dt": "",
  "modified_dt": "",
  "uuid": "string",
  "article_id": 0,
  "account_id": 0,
  "comment": "",
  "status": 0
}
```


