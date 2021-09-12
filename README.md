# eWallets Setup Guide

## Introduction
This repository contains scripts that can be used to set up the eWallet API.
The API allows users to create multiple wallets. It also allows users to send and receive money using their wallet address. Users can top up their wallets with this API.

The API documentation is available [here](https://documenter.getpostman.com/view/7306778/U16krRDb).


## Prerequisites
This API relies on MySQL, PHP 7+ and composer for any meaningful work, so make sure you have all the required libraries installed either locally or remote, depending on your setup. See [https://laravel.com/docs/8.x/installation](https://laravel.com/docs/8.x/installation) for information about setting up Laravel on your machine

## Quick Start
You should have the all the necessary libraries installed on your machine after following all the steps in the URI given in the ```Prerequisites``` section.

### Step 1: Clone project
Clone the repository using the git command below:

````
$ cd ~
$ git clone git@github.com:Pharoouzy/ewallet.git
$ cd ewallet
````

### Step 1: Update Environment Variables
Copy the .env.example file to .env and update the following by replacing the Xs with your actual values in the .env file:

NB: You can get a sandbox Mail credentials by setting up an account with [https://mailtrap.io/register/signup](https://mailtrap.io/register/signup)
````
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=XXX
DB_USERNAME=XXX
DB_PASSWORD=XXX

MAIL_MAILER=XXXXXXXX
MAIL_HOST=XXXXXXXX
MAIL_PORT=XXXXXXXX
MAIL_USERNAME=XXXXXXXX
MAIL_PASSWORD=XXXXXXXX
MAIL_ENCRYPTION=XXXXXXXX
MAIL_FROM_ADDRESS=XXXXXXXX
MAIL_FROM_NAME=XXXXXXXX
````

### Step 2: Generate Application Key

From your project directory, run the following command to generate the application encryption key


````
$ php artisan key:generate
````

### Step 3: Install Application Dependencies
From the project directory, install all dependencies with the command below:

````
$ composer install
````
### Step 4: Run database migration and seed
When all the steps above have been completed, you then proceed to run the command below for database migration and seed
````
$ php artisan migrate --seed
````
The *--seed* can be ignored is you do not want any dummy data in the database.

### Step 5: Run the application

Start the application by running the command below, the API resources should be accessible via [http://localhost:8000](http://localhost:8000)

NB: The application port (8000) might be different, check your console to confirm the port number.
````
$ php artisan serve
````

### Step 6: Setup and run Unit test
Lastly, Copy the .env.testing.example file to .env.testing and update the following by replacing the Xs with your actual values in the .env file:

````
MAIL_MAILER=XXXXXXXX
MAIL_HOST=XXXXXXXX
MAIL_PORT=XXXXXXXX
MAIL_USERNAME=XXXXXXXX
MAIL_PASSWORD=XXXXXXXX
MAIL_ENCRYPTION=XXXXXXXX
MAIL_FROM_ADDRESS=XXXXXXXX
MAIL_FROM_NAME=XXXXXXXX
````
Create a database file named ```testing.sqlite``` inside ````ewallet/database/```` directory to setup database for testing purposes and run the unit test with the command below

````
$ php artisan test
````

### Miscellaneous
All the required endpoints for this assessment are provided below:


- #### *POST*: Create Wallet - [http://localhost:8080/api/v1/wallets](http://localhost:8080/api/v1/wallets)

```json
{
    "name": "Flex",
    "type": {
        "name": "Savings",
        "min_balance": 100,
        "monthly_interest_rate": 2
    }
}
```

- ####  *GET*: Get all users in the system - [http://localhost:8080/api/v1/users](http://localhost:8080/api/v1/users)

- #### *GET*: Get a user’s detail including the wallets they own and the transaction history of that user - [http://localhost:8080/api/v1/users/:id](http://localhost:8080/api/v1/users/:id)

- #### *GET*: Get all wallets in the system - [http://localhost:8080/api/v1/wallets](http://localhost:8080/api/v1/wallets)

- #### *GET*: Get a wallet’s detail including its owner, type and the transaction history of that wallet - [http://localhost:8080/api/v1/wallets/:id](http://localhost:8080/api/v1/wallets/:id)

- #### *GET*: Gets the count of users, count of wallets, total wallet balance, total volume of transactions - [http://localhost:8080/api/v1/reports](http://localhost:8080/api/v1/reports)

- #### *POST*: Send money from one wallet to another - [http://localhost:8080/api/v1/wallets/:id](http://localhost:8080/api/v1/wallets/:id)

```json
{
    "wallet_address": "{{wallet_address}}",
    "amount": 5000
}
```
