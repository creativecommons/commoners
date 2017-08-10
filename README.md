
# Dependencies

    apt install apache2 mysql-client libapache2-mod-php php7.0-fpm php-mysql php-curl php-dom

# Configuration

## WordPress

    a2enmod rewrite
    apachectl restart


Install Buddypress.

Install the included version of CAS Maestro, and the included Commoners plugin.

## CAS Maestro

Make sure that CAS Version is 2.0 .

Enable E-mail suffix with empty suffix.

## Commoners

To vouch for pre-existing users, run this in the database with USERID set to
the WordPress ID of the user to autovouch:

set @userid=XXXXXX;
insert into wp_commoners_vouches(autovouch, description, vouchee, voucher) values (1, 'Automatically vouched', @userid, 0);
insert into wp_commoners_vouches(autovouch, description, vouchee, voucher) values (1, 'Automatically vouched', @userid, 0);
insert into wp_commoners_vouches(autovouch, description, vouchee, voucher) values (1, 'Automatically vouched', @userid, 0);


# Design Notes

## CAS Maestro

Our version changes how usernames are taken from the CAS server to sue the CCID
global nickname.

## Commoners

Commoners is a monolithic plugin that supports the registration and vouching
functions that we need.

### Registration

Commoners removes those parts of the BuddyPress User Profile UI that clash with
the use of CAS for login - the ability to change the user email and nickname,
etc.

### Vouching

Commoners uses WordPress's APIs to determine whether a user is logged in, and
if so whether they are an admin or not.

It uses its own database table to track how many vouches a user has received.
The user interface for vouching is implemented as hooks into Buddypress's APIs.

Commoners uses Buddypress's APIs to control each user's access to profile
information based on whether they are logged in, vouched, or can vouch.

The user levels that result from this logic, and that the code considers, are:

* PUBLIC - The user is not logged in. Anything they can see can be seen by the
entire world.

* REGISTERED - The user has created a CCID login and is registered in WordPress
as a Subscriber (which is sufficient to give them access to BuddyPress
features).

* VOUCHED - The user has received a single vouch from another vouched user.

* CAN VOUCH - The user has received three vouches from other vouched users. They
can now vouch other users themselves.

* ADMIN - The user is a WordPress Administrator.
