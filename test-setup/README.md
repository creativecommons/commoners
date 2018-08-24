# WARNING

**THESE SCRIPTS WILL ERASE DIRECTORIES AND DROP DATABASES. READ THEM,
UNDERSTAND THEM, RUN THEM IN A CONTAINER OR ON A VM NOT ON A MACHINE WITH
USEFUL DATA ON.**


## WARNING 2 (WARNING 1 FOR ZERO-INDEXED WARNINGS)

The cc-global-network plugin sends lots of emails. Be careful to either
redirect PHP's emails to a text file or only use email addresses that you
control.

Note that some of these emails are sent by WordPress cron jobs.


# If You Change the cc-global-network Plugin

Test this code and fix any breakages!


# Installation

## Requirements

GNU/Linux with Apache 2, MySQL and PHP installed.

## Assumptions

These instructions assume you will be installing into:

    /var/www/commonerstest/

with the WordPress install going in:

    /var/www/commonerstest/html/

They also assume that you will be serving your local test site at:

    http://commonerstest.localhost/

If any of these assumptions are false, edit the Apache 2 config in this
directory and the `config.sh` file in the `setup/` directory next to this one
*VERY CAREFULLY*.

## Files To Modify and Install

### config.sh

Copy config.sh.template within the `setup/` directory next to this one and
set your database, email and GravityForms license key details.

### /etc/hosts

If you will be using your computer's hosts file to resolve the site name,
add the following line to `/etc/hosts`:

    127.0.0.1       commonerstest.localhost

### commonerstest.localhost.conf

Copy `commonerstest.localhost.conf` into `/etc/apache2/sites-available/` then
enable the site

    cp commonerstest.localhost.conf /etc/apache2/sites-available/
    a2ensite commonerstest.localhost

## Full Installation

From within this directory, having carefully configured `config.sh` in the
`setup/` directory adjacent to it, run:

    ./delete-and-recreate-test-setup.sh

**THIS WILL DELETE THE CONTENTS OF THE WORDPRESS INSTALL DIRECTORY AND DROP THE
WORDPRESS DATABASE SPECIFIED IN** `setup/config.sh`

It will then install the `wp` command line tool and use that to install all the
plugins.

While doing that, it will clone the `commoners` Git project and link the
plugins and theme from that under `wp-config/`.

It will then restore pages, forms and configuration settings from data files in
`setup/`.

Finally it will run the `test-setup.sh` script in this directory to create
test data - WordPress accounts and Global Network Application GravityForms
entries and WordPress User object metadata in various states.

## What This Doesn't Do

The setup script does not enable CAS. You will need to enable CAS and update
the admin login (WordPress User ID 1) if you wish to test the User-facing UI for
the application workflow.
