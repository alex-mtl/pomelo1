# POMELO

### How to install and run this application

> **Requirement**: host should have docker installed
```
## Checkout and pull this repo to a new folder (eg: pml)
git clone git@github.com:alex-mtl/pomelo.git pml
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
Just vist `http://localhost:8080/` in your browser.
If that port 8080 is busy you can change it in `docker-compose.yml`
and rebuild docker container:
```
docker-compose up --build -d
``` 

Please note:
- Clinic Only one clinic is to be supported
  - A clinic has only one attribute, its name, which also acts as its unique identifier (ID)

- Patient
  - A clinic can have one or several patients
  - A patient has the following attributes:
    - First name
    - Last name
  - The combination of the first and last names acts as an ID

- Provider
  - A clinic can have one or several providers
  - A provider has the following attributes:
    - First name
    - Last name
  - The combination of the first and last names acts as an ID

- Availability
  - An availability is a time-slot during which a provider is able to treat a patient, if the latter
has booked an appointment
  - Each provider has one or several availabilities everyday
  - An availability has the following attributes:
    - Start date and time (timestamp)
    - End date and time (timestamp)
  - Availabilities are 15-minutes time-slots (8:00, 8:15, 8:30, etc.)

- Appointment
  - An availability that's been chosen by a patient is converted into an appointment upon
booking (one availability = one appointment)
  - An appointment has the following attributes:
     - An appointed patient
     - An appointed provider
     - Start date and time (timestamp)
     - End date and time (timestamp)
  - As appointments are booked on availabilities, they are also 15-minutes time-slots
