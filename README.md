V Estate By Husam
-------------------------
------------------------
how to run the app:

1- clone the repo to your pc

2- run: composer install

3- create database & configure environment variables with the database name,  username & password

4- run: php artisan migrate

5- add your Google API Token to environment variables as GOOGLE_API_TOKEN

--------------
TODO:
- add estimated time columns for start & end to appointments table
- convert postal codes to address using
- Calculate the time to access to the appointment address from the user(agent) address
- add one hour to the estimated time and save it to appointment
...
