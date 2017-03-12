# Models
These are the representations of the Tables as JSON documents/models. These are also returned by the API Endpoints.

## Accounts
```json
{
  "id": 0,
  "created_dt": "1970-01-01 00:00:00",
  "modified_dt": "1970-01-01 00:00:00",
  "uuid": "",
  "login_name": "",
  "first_name": "",
  "last_name": "",
  "email": "",
  "jwt_secret": "",
  "pw_salt": "",
  "pw_hash": "",
  "pw_iterations": 0,
  "status": 0
}
```

## Blogs
```json
{
  "id": 0,
  "created_dt": "1970-01-01 00:00:00",
  "modified_dt": "1970-01-01 00:00:00",
  "uuid": "",
  "account_id": 0,
  "title": "",
  "description": "",
  "status": 0
}
```

## Articles
```json
{
  "id": 0,
  "created_dt": "1970-01-01 00:00:00",
  "modified_dt": "1970-01-01 00:00:00",
  "uuid": "",
  "blog_id": 0,
  "title": "",
  "body": "",
  "status": 0
}
```

## Comments
```json
{
  "id": 0,
  "created_dt": "1970-01-01 00:00:00",
  "modified_dt": "1970-01-01 00:00:00",
  "uuid": "",
  "article_id": 0,
  "account_id": 0,
  "comment": "",
  "status": 0
}
```

## Tokens
```json
{
  "id": 0,
  "created_dt": "1970-01-01 00:00:00",
  "modified_dt": "1970-01-01 00:00:00",
  "sessionid": "",
  "account_id": 0,
  "expires_dt": "1970-01-01 00:00:00",
  "status": 0
}
```
