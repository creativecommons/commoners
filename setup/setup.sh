#!/bin/bash

################################################################################
# TO RE-EXPORT CONTENT FROM LIVE
################################################################################

# THIS CODE IS NOT MEANT TO BE RUN AS PART OF THIS SCRIPT!
# COPY AND RUN THE CODE IN THE IF ON LIVE, NOT THE MACHINE TO INSTALL ON!
# THEN COPY THE RESULTING FILES INTO PLACE HERE.
: '

    WPXMLFILE="/tmp/commoners-wordpress.xml"
    GFJSONFILE="/tmp/gravityforms-export.json"
    WPEMAILSFILE="/tmp/commoners-emails.json"

    wp export --post_type=page --stdout > "${WPXMLFILE}"

    wp plugin install gravityformscli --activate
    wp gf form export --dir=/tmp
    cp "/tmp/gravityforms-export-$(date -u +%Y-%m-%d).json" "${GFJSONFILE}"

    wp option list --format=json --search=ccgn-email-* > "${WPEMAILSFILE}"

'


################################################################################
# BEFORE STARTING
################################################################################

: '

    # INSTALL APACHE, MYSQL

    sudo apt install php-gd

    # CREATE WORDPRESS SITE CONFIG FOR THIS SITE

'


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


################################################################################
# Install wp-cli
################################################################################

if [ ! $(which wp) ]; then

    pushd /tmp
    wget https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    php wp-cli.phar --info
    chmod +x wp-cli.phar
    sudo mv wp-cli.phar /usr/local/bin/wp
    popd

    # FIX DOWNLOAD BUG AS OF 2017-10-12
    #sudo wp cli update --nightly

fi


################################################################################
# Install and configure WordPress
################################################################################

cd "${WPROOT}"
${WPCLI} core download

${WPCLI} config create \
    --dbname="${DBNAME}" --dbuser="${DBUSER}" --dbpass="${DBPASSWORD}"

${WPCLI} core install \
    --url="${SITEURL}" --title="${SITENAME}" \
    --admin_user="${ADMINNAME}" --admin_password="${ADMINPASSWORD}" \
    --skip-email --admin_email="${ADMINEMAIL}"

# So BuddyPress activates
${WPCLI} rewrite structure '/%year%/%monthnum%/%day%/%postname%/'


################################################################################
# Fetch and link our repos
################################################################################

# Handle the repo already being present, for dev machines
if [ ! -d "${GITROOT}/commoners" ]; then
    cd "${GITROOT}"
    sudo git clone https://github.com/creativecommons/commoners.git
    sudo chown -R "${WEBUSER}:${WEBGROUP}" commoners
fi
cd "${WPROOT}/wp-content/plugins"
sudo -u "${WEBUSER}" ln -s "${GITROOT}/commoners/plugins/cas-maestro"
sudo -u "${WEBUSER}" ln -s "${GITROOT}/commoners/plugins/cc-global-network"
cd "${WPROOT}/wp-content/themes"
sudo -u "${WEBUSER}" ln -s "${GITROOT}/commoners/themes/cc-commoners"


################################################################################
# Set up plugins
################################################################################

cd "${WPROOT}"

${WPCLI} plugin delete hello

# To make the admin a member later
wp package install buddypress/wp-cli-buddypress

${WPCLI} plugin install multiple-roles --activate
${WPCLI} plugin install if-menu --activate
${WPCLI} plugin install akismet --activate

${WPCLI} plugin install buddypress --activate
#${WPCLI} bp component deactivate activity
#${WPCLI} bp component deactivate notifications
#${WPCLI} bp component activate groups
${WPCLI} bp component activate xprofile

# For importing pages
${WPCLI} plugin install wordpress-importer --activate

${WPCLI} plugin install gravityformscli --activate
${WPCLI} gf install --key="${GFORMSKEY}"
${WPCLI} plugin activate gravityforms

${WPCLI} option update wpCAS_settings \
   '{"cas_version": "1.0",
   "server_hostname": "login.creativecommons.org",
   "server_port": "443",
   "server_path": ""}' --format=json

${WPCLI} option update wpCAS_settings '{"cas_menu_location":"sidebar","new_user":"1","email_suffix":"","cas_version":"2.0","server_hostname":"login.creativecommons.org","server_port":"443","server_path":"","e-mail_registration":"2","global_sender":"info@creativecommons.org","full_name":"","welcome_mail":{"send_user":true,"send_global":false,"subject":"","user_body":"","global_body":""},"wait_mail":{"send_user":true,"send_global":false,"subject":"","user_body":"","global_body":""},"ldap_protocol":"3","ldap_server":"","ldap_username_rdn":"","ldap_password":"","ldap_basedn":"","ldap_port":null}' --format=json

#${WPCLI} plugin activate cas-maestro

#${WPCLI} option update rg_gforms_key "${GFORMSKEY}"

${WPCLI} plugin activate cc-global-network


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

${WPCLI} option update show_on_front "page"
${WPCLI} option update page_on_front "${WPFRONTPAGE}"


################################################################################
# Copy in emails
################################################################################

for i in $(seq 0 $(expr $(jq '. | length' "${WPEMAILSFILE}") - 1)); do
    key=$(jq .[$i].option_name "${WPEMAILSFILE}")
    value=$(jq .[$i].option_value "${WPEMAILSFILE}")
    wp option update "${key}" "${value}"
done
