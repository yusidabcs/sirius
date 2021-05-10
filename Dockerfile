FROM php:7.4-apache

#Copy source to container
COPY src/ /var/www/html/

#setup apache with the modules I need
RUN ln -s /etc/apache2/mods-available/expires.load /etc/apache2/mods-enabled/expires.load && \
    ln -s /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/headers.load && \
    ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load

#AWS likes to have unzip :-)
RUN apt-get update && \
    apt-get install -y unzip

#install the php extensions using the docker official approved image
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN install-php-extensions gd imap intl mysqli opcache tidy zip

#Production stops display of errors and some other things
#RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

#Copy in Default
RUN rm /etc/apache2/sites-enabled/000-default.conf
COPY 000-default.conf /etc/apache2/sites-enabled/

#install composer for php dependencies 
COPY --from=composer /usr/bin/composer /usr/bin/composer

#now see if we can get the sdk installed
RUN composer require aws/aws-sdk-php

#now we need phpmailer
RUN composer require phpmailer/phpmailer

#now we need cron-expression
RUN composer require dragonmantank/cron-expression

#now we need dompdf for CV
RUN composer require dompdf/dompdf

#now we need PhpSpreadsheet for export excel
RUN composer require phpoffice/phpspreadsheet


#-- FROM HERE REMOVE ONCE IT IS WORKING --

#Add SSHD as I need to get in
RUN apt-get update && \
    apt-get install -y openssh-server && \
    mkdir /var/run/sshd && \
    mkdir /root/.ssh && \
    sed -i 's/#*PasswordAuthentication yes/PasswordAuthentication no/g' /etc/ssh/sshd_config

COPY authorized_keys /root/.ssh
RUN chown root:root /root/.ssh/authorized_keys && \
    chmod 600 /root/.ssh/authorized_keys 

RUN apt-get install -y vim && \
    sed -i 's/# export LS_OPTIONS=/export LS_OPTIONS=/g' /root/.bashrc && \
    sed -i 's/# eval "`dircolors`"/eval "`dircolors`"/g' /root/.bashrc && \
    sed -i 's/# alias ls=/alias ls=/g' /root/.bashrc && \
    sed -i "s/# alias ll='ls \$LS_OPTIONS -l'/alias ll='ls \$LS_OPTIONS -al'/g" /root/.bashrc

EXPOSE 22 80

COPY script.sh script.sh
RUN chmod +x script.sh
CMD ["./script.sh"]
