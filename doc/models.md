# Models
These are the representations of the Tables as JSON documents/models.

## Accounts
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

## Blogs
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

## Articles
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

## Comments
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

## Tokens
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
