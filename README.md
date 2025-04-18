# AliceCRM

## This web site is used to easily communicate between the company and its various business contacts.


### Architecture and Technologies

````
Framework: Symfony 6.2 , Boostrap 5+
Language: PHP 6.2
SQL 
Front-end: HTML, CSS, JavaScript, Twig templating
Composer


````

#### External APIs and Services:

````
Google Calendar
Google Custom Search API
Google reCAPTCHA
Mailjet for email delivery

````

### Security

````
CSRF tokens in forms

Role and permission management

````


### Installation and configuration

````
git clone https://github.com/NasEpsi/AliceCRM.git

````
# install dependencies 
composer install 

## Configure the .env file with your database settings and API keys:

````
### Google reCAPTCHA keys
RECAPTCHA3_KEY=XXXXXXXXXXXXXXXXXXX
RECAPTCHA3_SECRET=XXXXXXXXXXXXXXXXXXXXXXX

### Google Custom Search API key
APP_GOOGLESEARCH_API_KEY=XXXXXXXXXXXXX

### Mailjet parameters
MAILER_DSN=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

````
### Write down these in your console :

````
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load

The fixtures will create : 
- 1 admin account (email: po@po.com, password : Pa$$w0rd!)
- 20 accounts with randoms email and the same password 

````

## Context 

````
The administrator assigns manually predifined roles:
- Collaborator
- Accountant 
- Client 

````

## Key Functionnality 

# Client Site Referencing with Google Custom Search API

````
Description:
For each contract, the platform displays the client's website name,
the financial informations of the contract and under 


along with its referencing by keywords, retrieved dynamically via the Google Custom Search API

Key Points:

Dynamic API calls to retrieve search data
Display of search results within the contract interface

````

# Tariff Zones Management

````
Description:
The administrator can enter a tariff zone by providing its name and the corresponding price 

Key Points:
A dedicated interface for entering and updating tariff zones
Integration with other parts of the application ( ex: contracts to apply the correct tariffs based on the zone) 

````

## Other Functionnality...

### Some Symfony lines

````
# Start the server 
symfony serve

# Stop the server 
symfony server:stop

````

### Tests 

Not yet 