# syntax=docker/dockerfile:1

FROM php:8.4-fpm
LABEL org.opencontainers.image.authors="keving5726@gmail.com"
RUN docker-php-ext-install pdo pdo_mysql bcmath
WORKDIR /var/www/guitarshop
COPY . .
COPY --from=build /app/public/build public/build/
COPY --from=build /app/node_modules node_modules/
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
EXPOSE 9000
CMD ["php-fpm"]
