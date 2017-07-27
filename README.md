
# Dependencies

    apt install apache2 mysql-client libapache2-mod-php php7.0-fpm php-mysql php-curl php-dom

# Configuration

## WordPress

    a2enmod rewrite
    apachectl restart


Install Buddypress.

Install the included version of CAS Maestro, and the included Commoners plugin.

Install https://github.com/creativecommons/buddypress-role-field-groups
and use it to control access to field groups that only vouched members should
see.

## CAS Maestro

Make sure that CAS Version is 2.0 .

Enable E-mail suffix with empty suffix.
