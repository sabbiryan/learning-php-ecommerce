# A Simple Online Store using RAW PHP

### How to run this application

Clone this repository first 

    git clone https://github.com/sabbiryan/my-online-store.php.git

On this repository you will find following folders:

    Source
        App
        Database
    
Follow the following steps to configure and run this app.

1. Go to ./Source folder then copy App folder and past it to apache server htdocs or your define localhost directory.

2. Open  localhost:80/phpmyadmin to access the mysql database. And create a new database named "db_mystore"

3. You will find a sql script inside of repository in location ./Source/Database. Import this script from phpmyadmin panel.

4. Define your database connection configuration on ./Source/App/include/onlineStoreUser.php

Now, Open browser and hit localhost:80/App and you will be ask for username and password.

Default cridentials:
      
      Username: admin,
      Password: admin
  
Thanks

