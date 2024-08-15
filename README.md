Guitarshop

Guitarshop is a project about an online shop developed with vanilla PHP.

Requirements:

* PHP >= 7.4
* Yarn or NPM
* DB: MySQL or PostgreSQL
* Server Web (Nginx, Apache, Lighttpd, LiteSpeed, etc)

Configuration:

* Public Directory: You should configure your web server's document / web root to be the "public" directory. The "index.php" in this directory serves as the front controller for all HTTP requests entering your application.

* Configuration Files: All of the configuration files are stored in the "config" directory. Each option is documented, so feel free to look through the files and get familiar with the options available to you.

* Database Configuration: Edit the file "database.php" in the "config" directory and execute the commands: "php bin/console migrate" then "php bin/console load".

Package Installations:

* You should use NPM or YARN to install the packages.
* Execute the command "yarn install" or "npm install".
* Execute the command "yarn run build" or "npm run build".

Enjoy the project
