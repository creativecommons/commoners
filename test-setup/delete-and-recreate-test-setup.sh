#!/bin/bash

################################################################################
# Config
################################################################################

source "$(dirname "$0")/../setup/config.sh"


################################################################################
# Warn the unwary
################################################################################

echo -e "\e[1m\e[91m!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\e[0m"
echo -e "\e[1m\e[91mTHIS SCRIPT WILL ERASE THE CONTENT OF THE TARGET DIRECTORY ${WPROOT} AND WILL DROP THE TARGET DATABASE ${DBNAME} BEFORE RECREATING IT.\e[0m"
echo -e "\e[1m\e[91mREPEAT: THIS WILL ERASE THE DIRECTORY ${WPROOT} AND THE DATABASE ${DBNAME}.\e[0m"
echo -e "\e[1m\e[91m!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\e[0m"
echo "You will also need to enter your MySQL root user password, and possibly your user password for sudo."
read -p "Enter y to continue, enter any other input to cancel: " -n 1 -r
echo
if [ "$REPLY" != "y" ]; then
    echo "Stopping."
    exit 0
fi


################################################################################
# Clean up filesystem ready for install
################################################################################

sudo rm -rf "${WPROOT}/"*


################################################################################
# Create database
################################################################################

echo "Prepare to enter your MySQL root user password."

mysql -u root -p -e "drop database ${DBNAME}; create database ${DBNAME}; grant all on ${DBNAME}.* to '${DBUSER}' identified by '${DBPASSWORD}';"


################################################################################
# Run initial install
################################################################################

"$(dirname "$0")/../setup/setup.sh"

################################################################################
# Run test data creation
################################################################################

"$(dirname "$0")/test-setup.sh"
