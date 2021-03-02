# POMELO

### How to install and run this application

> **Requirement**: host should have docker installed
```
## Checkout and pull this repo to a new folder (eg: pml)
git clone git@github.com:alex-mtl/pomelo1.git pml
## Build application services (php, mysql, nginx) using docker
cd pml
docker-compose up --build -d
## Run bash terminal in docker container
docker-compose exec php bash
```
> Following steps need to be executed inside  bash terminal in php docker container
```
## Build php application and install all dependencies with using composer
composer install
## Copy .env.example to .env
cp .env.example .env
## Execute DB migration
./artisan migrate
## Run tests
./artisan test
```
If tests executed successfully we can check application using web browser.

It is exposed on port 8080.

Just visit `http://localhost:8080/` in your browser.

If that port 8080 is busy you can change it in `docker-compose.yml`
and rebuild docker container:
```
docker-compose up --build -d
``` 

Since it is 99% api backend application it is more convenient
to explore and test it not through the browser but using Postman

You can download and import Postman [collection](https://github.com/alex-mtl/pomelo1/blob/master/docker/Pomelo%20Localhost.postman_environment.json)
and Postman [environmen](https://github.com/alex-mtl/pomelo1/blob/master/docker/Pomelo%20Localhost.postman_environment.json)
