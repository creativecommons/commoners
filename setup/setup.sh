#!/bin/bash

################################################################################
# BEFORE STARTING
################################################################################

# ON DEV
## wp export --post__in=${IMPORTPAGES} --stdout > "${WPXMLFILE}"
## scp "${WPXMLFILE}" SERVER:${WPXMLFILE}

## ON DEV:
## wp plugin install gravityformscli --activate
## wp gf form export --dir=/tmp
## scp "${GFJSONFILE}" SERVER:${GFJSONFILE}

## ON SERVER
# Copy $GFARCHIVE into position


################################################################################
# Config
################################################################################

source "$(dirname "$0")/config.sh"


################################################################################
# Check for paths and files
################################################################################

if [ ! -d ${GITROOT} ]; then
    echo "Git checkout directory ${GITROOT} doesn't exist"
    exit
fi

if [ ! -d ${WPROOT} ]; then
    echo "WordPress install directory ${WPROOT} doesn't exist"
    exit
fi

if [ ! -f ${WPXMLFILE} ]; then
    echo "Cannot find ${WPXMLFILE}"
    exit
fi

if [ ! -f ${GFJSONFILE} ]; then
    echo "Cannot find ${GFJSONFILE}"
    exit
fi

if [ ! -f ${GFARCHIVE} ]; then
    echo "Cannot find ${GFARCHIVE}"
    exit
fi

################################################################################
# Install wp-cli
################################################################################

pushd /tmp
wget https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
php wp-cli.phar --info
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
popd

# FIX DOWNLOAD BUG AS OF 2017-10-12
sudo wp cli update --nightly


################################################################################
# Clean up ready for install
################################################################################

rm -rf "${WPROOT}/*"


################################################################################
# Create database
################################################################################

mysql -u root -p -e "create database ${DBNAME}; grant all on ${DBNAME}.* to '${DBUSER}' identified by '${DBPASSWORD}';"


################################################################################
# Install and configure WordPress
################################################################################

cd "${WPROOT}"
${WPCLI} core download

${WPCLI} config create \
    --dbname=${DBNAME} --dbuser=${DBUSER} --dbpass=${DBPASSWORD}

${WPCLI} core install \
    --url='${SITEURL}' --title='${SITENAME}' \
    --admin_user='${ADMINNAME}' --admin_password='${ADMINPASSWORD}' \
    --skip-email --admin_email='${ADMINEMAIL}'

# So BuddyPress activates
${WPCLI} rewrite structure '/%year%/%monthnum%/%day%/%postname%/'


################################################################################
# Fetch and link our repos
################################################################################

cd "${GITROOT}"
sudo git clone https://github.com/creativecommons/commoners.git
sudo chown -R "${WEBUSER}:${WEBGROUP}" commoners
cd "${WPROOT}/wp-content/plugins"
ln -s "${GITROOT}/commoners/plugins/cas-maestro"
ln -s "${GITROOT}/commoners/plugins/cc-global-network"
cd "${WPROOT}/wp-content/themes"
ln -s "{$GITROOT}/commoners/themes/cc-commoners"


################################################################################
# Install gravityforms
################################################################################

sudo -u www-root unzip -d "${WPROOT}/wp-content/plugins/" "${GFARCHIVE}"


################################################################################
# Set up plugins
################################################################################

cd "${WPROOT}"

wp plugin delete hello

${WPCLI} plugin install akismet --activate
${WPCLI} plugin install buddypress --activate
# For importing pages
${WPCLI} plugin install wordpress-importer --activate

${WPCLI} plugin activate gravityforms

${WPCLI} plugin install gravityformscli --activate

${WPCLI} plugin activate cc-global-network

${WPCLI} option update wpCAS_settings \
   '{"cas_version": "1.0",
   "server_hostname": "login.creativecommons.org",
   "server_port": "443",
   "server_path": ""}' --format=json

${WPCLI} option update rg_gforms_key "${GFORMSKEY}"


################################################################################
# Set up theme
################################################################################

${WPCLI} theme activate cc-commoners


################################################################################
# Remove the default post and page
################################################################################

${WPCLI} post delete ${DELETEPAGES}


################################################################################
# Copy in forms
################################################################################

${WPCLI} gf form import "${GFJSONFILE}"


################################################################################
# Copy in content
################################################################################

${WPCLI} import --authors=skip "${WPXMLFILE}"

${WPCLI} option update page_on_front "${WPFRONTPAGE}"
