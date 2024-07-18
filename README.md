# Test Repository

This repository is for testing purposes only. It includes a basic CRUD (Create, Read, Update, Delete) API implementation using the Slim 4 framework and a file-based database.

## Installation

Clone the repository:
```
git clone git@github.com:yugo412/perqara.git yugo-test \
 && cd yugo-test \
 && composer install
```

Run the server with these preferred method.

> [!NOTE]  
> Please make sure port 8080 is not used by any services in the host machine.

### Using PHP Built-in Server

```
php -S localhost:8080 -t public
```

The `8080` port is mandatory since it will be used as default host by the documentation.

### Docker Compose

```
docker-compose up -d
```

To ensure the application is running correctly, either use the built-in server or Docker Compose. Please access the URL [http://localhost:8080](http://localhost:8080) to see the index page.

Rest API documentation can be accessed from URL [http://localhost:8080/doc](http://localhost:8080/doc)

## Tests

The tests can be run using the command below:

```
composer test
```

Or, for more complete report, we can use phpunit binary.

```
./vendor/bin/phpunit --testdox
```