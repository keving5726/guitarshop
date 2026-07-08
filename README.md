# Guitarshop
**Guitarshop** is a test project for an online guitar shop developed using vanilla **PHP**.

## Table of contents
- [Docker Support](#docker-support)
- [Requirements](#requirements)
- [Configuration](#configuration)
  - [Web Server](#web-server)
  - [Environment Variables](#environment-variables)
  - [Run Migrations](#run-migrations)
- [Package Installations](#package-installations)
- [Enjoy the Project!](#enjoy-the-project)
- [License](#license)

## Docker Support
**Guitarshop** can be easily run using **Docker**, which simplifies the setup process and ensures a consistent development environment.
Just run the following command in the project root:
```bash
docker compose up -d
```

## Requirements
To run **Guitarshop**, ensure your environment meets the following requirements:
- **PHP**: Version **8.4** or higher.
- **Node.js**: Version **20.18.2** (LTS/Iron).
- **Yarn**: Version **4.4.1**.
- **Database**: Compatible with **MariaDB**, **MySQL**, **PostgreSQL**, **SQLite**.
- **Web Server**: Compatible with **Nginx**, **Apache**, **Lighttpd**, **LiteSpeed**, etc.

## Configuration

### Web Server
The `public` directory acts as the document root. In this **Nginx** example the `public` directory is located at `/var/www/guitarshop/public/`:
```nginx
server {
  listen 80 default_server;
  listen [::]:80 default_server;

  server_name guitarshop.local;

  root /var/www/guitarshop/public;

  add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

  access_log /var/log/nginx/guitarshop.access.log;
  error_log /var/log/nginx/guitarshop.error.log;

  location / {
    # try to serve file directly, fallback to index.php
    try_files $uri /index.php$is_args$args;
  }

  # pass PHP scripts to FastCGI server
  #
  #location ~ ^/index\.php(/|$)\.php$ {
  #    include snippets/fastcgi-php.conf;
  #
  #    # With php-fpm (or other unix sockets):
  #    fastcgi_pass unix:/run/php/php8.4-fpm.sock;
  #    # With php-cgi (or other tcp sockets):
  #    fastcgi_pass 127.0.0.1:9000;
  #}

  location ~ \.php$ {
    return 404;
  }
}
```

### Environment Variables
Copy the `.env.dist` file to create your own `.env` file. You will find several example variables that you need to configure according to your environment.
Modify the values of the variables in the `.env` file to match your specific configuration.
You can use the `.env.example` file as a guide.

### Run Migrations
Run the following commands to migrate and load the database:
```bash
php bin/console migrate
php bin/console load
```

## Package Installations
You can use **YARN** to install the necessary packages. Follow these steps:
- Install the packages:
`yarn install`
- Build the project:
`yarn build`

## Enjoy the Project!
Thank you for checking out **Guitarshop**! We hope you enjoy using and contributing to this project. If you have any questions or feedback, feel free to reach out!

## :scroll: License

This project is open-source and licensed under the **MIT License**. You are free to use, modify, and distribute it, even for commercial purposes, as long as the original copyright notice is included.

For more information, please see the [LICENSE](LICENSE) file.
