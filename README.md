## Project Details:

### Requirements:

- PHP 7.1 or higher.

- composer.

### Project Setup:

- in the project root run this command.

```
$ composer install
```
- Configuring the Database.

The database connection information is stored as an environment variable called ```DATABASE_URL```. For development, you can find and customize this inside ```.env```:
```
# .env

# customize this line!
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
```

- next we run this commands:

=> for database creation.
```
$ php bin/console doctrine:database:create 
```
=> to create the database structure.
``` 
$ php bin/console doctrine:schema:create
```
- To start the server, run:
```
$ php -S 127.0.0.1:8000 -t public
```
now we have the app running on ``` http://127.0.0.1:8000/ ```
