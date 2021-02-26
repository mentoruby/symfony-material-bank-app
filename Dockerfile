# FROM ubuntu:20.04

FROM php:8.0.2-apache as myappupdate
RUN apt-get update && \
    apt-get install -my zip unzip && \
    a2enmod rewrite
# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

FROM myappupdate as myappconfig
ARG app_path=/var/www/symfony-material-bank-app
# copy all project files to app folder
COPY ./ ${app_path}/
# rename .env.sample to .env (suppose to create new based on .env.sample)
# move customized port setting to apache port config folder
# move new VirtualHost setting to apache site config folder
# add root user to apache group
# change owner of app folder to apache user
# grant access permissions of app folder
# enable new VirtualHost setting in apache
RUN mv ${app_path}/.env.sample ${app_path}/.env && \
  mv ${app_path}/apache-config/ports.conf /etc/apache2/ && \
  mv ${app_path}/apache-config/symfony-material-bank-app.conf /etc/apache2/sites-available/ && \
  usermod -a -G www-data root && \
  chown www-data:www-data ${app_path} -R && \
  chmod -R 755 ${app_path} && \
  a2ensite symfony-material-bank-app.conf
WORKDIR ${app_path}
USER www-data

FROM myappconfig as myappcompose
# read composer.json and install all necessary packages, no need to run scripts defined inside
RUN composer install --no-scripts
# start apache automatically in foreground
CMD ["apache2-foreground"]
