# SPDO


## Example connection
```php

$dbh = new SPDO("mysql:host=localhost;dbname=DATABASE_NAME", "USERNAME" ,"PASSWORD");

$dbh = new SPDO("firebird:dbname=localhost:/path/to/database.fdb", "USERNAME" ,"PASSWORD");

$dbh = new SPDO("sqlite:/path/to/database.sqlite");
```


## Queries

```php
$dbh->executeQuery($sql, $binds)

```


## Select

```php

```


## Insert

```php

```


## Update

```php

```
