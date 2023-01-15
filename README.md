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
- add estimated time columns for start & end to appointments table => DONE
- Calculate the time to access to the appointment address from the user(agent) address => DONE
- add one hour to the estimated time and save it to appointment => DONE

TODO:
- check entered postal codes if in uk or not
- convert postal codes to address using google API and show it in API
- validate received resonse from google api
- apply estimation to edit request
- check appointment date (ex: not in the past) before deletion or creation
.....
