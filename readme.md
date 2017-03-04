# Nofuzz Blog Tutorial
This is a tutorial will show how to make a Blog system REST API using the [Nofuzz framework](https://github.com/Celarius/nofuzz-framework).

The result of this tutorial is a number of endpoints, complete with authentication and reading & writing to the database.

The Tutorial covers the creation of API endpoints for:
- Registering accounts
- Authenticating User
- List, Create, Edit, Delete of Blogs
- List, Create, Edit, Delete of Blog-Articles (posts)
- List, Create, Edit, Delete of Blog-Article-Comments

The API will __NOT__ cover the following:
- A user interface (web page) for the Blog

## Install
Download the repository to a new directory, and configure your web server to point to the `/public` folder.

After installing the files, you need to run composers update to download all dependencies.
```txt
$ composer update -o --no-dev
```

## Configure
Change the values in `app/Config/config.json` to mach your setup. Specifically the Database Host and Username & Password must be changed to match your config.


# Things to know before you start

## Authentication and Authorization
Only the Registeration and SignIn endpoints have no authentication checks. The other API endpoints will use a `JWT token` (See [jwt.io](https://jwt.io)) to authenticate the requests.

A `JWT Token` is obtained by calling the SignIn endpoint with correct credentials. Once obtained a client needs to pass the token to all other endpints via the `Authorization: Bearer <token>` header.

## Models
The JSON models of all the tables are docuemnted in the [Models.md](doc/models.md)


# Tutorial
*Design the application - Steps*
The following steps were taken when designing this tutorial application:

1. The API
2. The Database
3. Controllers
4. Middleware
5. Routes definitions


## 1 - The API
Here we're going to define the API endpoints that will make up the whole API. The API endpoints are grouped into logical groups as per below:

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
GET     /blog/api/v1/accounts[/{id}]    Get account(s) 
POST    /blog/api/v1/accounts           Create an account
PUT     /blog/api/v1/accounts           Update an account
DELETE  /blog/api/v1/accounts           Remove an account
```
*Blogs*
```txt
GET     /blog/api/v1/blogs[/{id}]       Get blog(s)
POST    /blog/api/v1/blogs              Create a blog
PUT     /blog/api/v1/blogs              Update a blog
DELETE  /blog/api/v1/blogs              Remove a blog
```
*Articles*
```txt
GET     /blog/api/v1/articles[/{id}]    Get article(s)
POST    /blog/api/v1/articles           Create an article
PUT     /blog/api/v1/articles           Update an article
DELETE  /blog/api/v1/articles           Remove an article
```
*Comments*
```txt
GET     /blog/api/v1/comments[/{id}]    Get comment(s)
POST    /blog/api/v1/comments           Create a comment
PUT     /blog/api/v1/comments           Update a comment
DELETE  /blog/api/v1/comments           Remove a comment
```


## 2 - The Database
Please see [MySql Schema](doc/schema.mysql.sql) for the complete Schema DDL. Use your favorite tool to create the database.

> This app uses a MySQL Database by default, but is easily converted to using Firebird, PostgreSql, Oracle or any other DB.

## 4 - Controllers
In the `/app/Controllers` folder you will find all the Controller classes for each endpoint group. These controllers are:
- RegisterController.php
- SignInController.php
- SignOutController.php
- AccountsController.php
- BlogsController.php
- ArticlesController.php
- CommentsController.php

## 5 - Middleware
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
The Middleware that run After each request are:
- RequestIdAfterMiddleware.php
- ResponseTimeAfterMiddleware.php
- ResponseLogAfterMiddleware.php

### Group specific Before Middleware
The Middleware for the *Authenticated* endpoints are:
- AuthBeforeMiddleware.php

### Group specific After Middleware
No After Middleware defined.

## 6 - Routes definitions
The `routes.json` file contains all the mappings between the Endpoints and the Controllers. We specify each endpoint, and the Controller that will handle it.

THe routes are divided into two groups, *Anonynnous* and *Authenticated*. For each group we specify different Middleware.
