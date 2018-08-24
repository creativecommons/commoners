#!/bin/bash

# DO NOT RUN ON A LIVE SERVER, THIS WILL END THE WORLD


################################################################################
# Config
################################################################################

source "$(dirname "$0")/../setup/config.sh"

pushd "${WPROOT}"


################################################################################
# Make the admin a super-user so they can see the Global Network admin views
################################################################################

wp user add-role 1 membership-council-member
wp user add-role 1 membership-final-approver
wp user add-role 1 membership-cc-legal


################################################################################
# Create some users
################################################################################

function createWPUser {
    local name=$1
    wp user create "${name}" "${name}@localhost.local" --role='new-user' \
       > /dev/null
    wp user get "${name}" --field=ID
}

function createIMCMember {
    local num=$1
    local name="IMCMember${num}"
    local ID=$(createWPUser "${name}")
    wp user add-role "${ID}" 'membership-council-member' > /dev/null
    echo "${ID}"
}

function createLegal {
    local num=$1
    local name="CCLegal${num}"
    local ID=$(createWPUser "${name}")
    wp user add-role "${ID}" 'membership-cc-legal' > /dev/null
    echo "${ID}"
}

# This member will *not* have the correct form entries for an application,
# and will also *not* have the correct BuddyPress XProfile information.
# We create them like this to allow the Applications below to refer to them!

function createIndividualMember {
    local num=$1
    local name="Member${num}"
    local ID=$(createWPUser "${name}")
    wp user add-role "${ID}" 'subscriber' > /dev/null
    # A nasty hack until wp bp supports setting member types
    echo "bp_set_member_type( ${ID}, 'individual-member' ); exit;" \
     | wp shell > /dev/null
    echo "${ID}"
}

# These are ID numbers

IM1=$(createIndividualMember 1)
IM2=$(createIndividualMember 2)
IM3=$(createIndividualMember 3)
IM4=$(createIndividualMember 4)

IMC1=$(createIMCMember 1)
IMC2=$(createIMCMember 2)
IMC3=$(createIMCMember 3)
IMC4=$(createIMCMember 4)

# Create the final approver role

wp user add-role "${IMC1}" 'membership-final-approver'

L1=$(createLegal 1)
L2=$(createLegal 2)


################################################################################
# Create Individual Applicants
################################################################################

# These applicants *will* have the correct application forms and states

function gfFormIdByName {
    wp gf form form_list --format=csv | grep "${1}" | cut -f1 -d,
}

GFAGREE=$(gfFormIdByName 'Agree To Terms')
GFINDIVIDUALDETAILS=$(gfFormIdByName 'Applicant Details')
GFVOUCHERS=$(gfFormIdByName 'Choose Vouchers')
GFFINAL=$(gfFormIdByName 'Final Approval')
GFINSTITUTIONDETAILS=$(gfFormIdByName 'Institution Details')
GFLEGAL=$(gfFormIdByName 'LegalApproval')
GFPRE=$(gfFormIdByName 'Pre Approval')
GFSIGN=$(gfFormIdByName 'Sign The Charter')
# Note case of "on"! :-(
GFVOTE=$(gfFormIdByName 'Vote on Membership')
GFVOUCH=$(gfFormIdByName 'Vouch For Applicant')

function gfCreateEntry {
    local user_id=$1
    local form_id=$2
    local extra="$3"
    wp gf entry create \
       "{\"form_id\": ${form_id}, \"created_by\": ${user_id}, ${extra}}" \
       > /dev/null
}

function gfAgreeTerms {
    local user_id=$1
    gfCreateEntry "${user_id}" \
                  "${GFAGREE}" \
                  '"3.1": "Yes", "5.1": "Read and Understood", "5.2": "Privacy Consent", "5.3": "Vouching Consent"'
}

function gfSignCharter {
    local user_id=$1
    gfCreateEntry "${user_id}" \
                  "${GFSIGN}" \
                  "\"2.1\": \"I have read and agree with the Charter\", \"3\": \"Signature of User ${user_id}\""
}

function gfChooseVouchers {
    local user_id=$1
    local voucher1=$2
    local voucher2=$3
    gfCreateEntry "${user_id}" \
                  "${GFVOUCH}" \
                  "\"1\": \"${voucher1}\", \"2\": \"${voucher2}\""
}

function gfIndividualDetails {
    local user_id=$1
    local country="$2"
    local language="$3"
    gfCreateEntry "${user_id}" \
                  "${GFINDIVIDUALDETAILS}" \
                  "\"1\": \"Individual Applicant ${user_id}\", \"2\": \"Brief biography of individual applicant ${user_id}\", \"3\": \"Statement of interest of individual applicant ${user_id}\", \"5\": \"[\\\"Copyright Reform Platform\\\",\\\"Open Education Platform\\\"]\", \"6\": \"${language}\", \"7\": \"${country}\", \"9\": \"socialmedia.site/${user_id}\", \"15\": \"No\", \"18.1\": \"Yes\", \"20\": \"${country}\""
}

function createIndividualApplicant {
    local num=$1
    local country=$2
    local language=$3
    local voucher1=$4
    local voucher2=$5
    local name="Applicant${num}"
    local ID=$(createWPUser "${name}")
    wp user add-role "${ID}" 'new-user' > /dev/null
    # Set individual applicant type
    echo "ccgn_user_set_individual_applicant( ${ID} ); exit;" \
        | wp shell > /dev/null
    gfAgreeTerms "${ID}"
    gfSignCharter "${ID}"
    gfChooseVouchers "${ID}" "${voucher1}" "${voucher2}"
    gfIndividualDetails "${ID}" "${country}" "${language}"
    echo "${ID}"
}

INDIVIDUALAPPLICANT1=$(createIndividualApplicant 1 \
                                                 "Canada" "English" \
                                                 "${IM1}" "${IM2}")
INDIVIDUALAPPLICANT2=$(createIndividualApplicant 2 \
                                                 "Canada" "French"  \
                                                 "${IM2}" "${IM3}")
INDIVIDUALAPPLICANT3=$(createIndividualApplicant 3 \
                                                 "France" "French" \
                                                 "${IM3}" "${IM4}")
INDIVIDUALAPPLICANT4=$(createIndividualApplicant 4 \
                                                 "Germany" "German" \
                                                 "${IM4}" "${IM5}")
INDIVIDUALAPPLICANT5=$(createIndividualApplicant 5 \
                                                 "Spain" "Spanish" \
                                                 "${IM5}" "${IM6}")
INDIVIDUALAPPLICANT6=$(createIndividualApplicant 6 \
                                                 "Canada" "French" \
                                                 "${IM1}" "${IM2}")


################################################################################
# Move individual applicants through the process
################################################################################

function gfSpamCheck {
    local applicant_id=$1
    local status="$2"
    local note="$3"
    gfCreateEntry "${IMC1}" \
                  "${GFPRE}" \
                  "\"1\": \"${status}\", \"4\": \"${applicant_id}\", \"5\": \"${note}\""
    if [ "${status}" = "Yes" ]; then
        echo "ccgn_user_level_set_pre_approved( ${applicant_id} ); exit;" \
            | wp shell
    elif [ "${status}" = "Update Details" ]; then
        echo "ccgn_registration_user_set_stage( ${applicant_id}, CCGN_APPLICATION_STATE_UPDATE_DETAILS); exit;" \
            | wp shell
    elif [ "${status}" = "No" ]; then
        echo "ccgn_user_level_set_rejected( ${applicant_id} ); exit;" \
            | wp shell
    fi
}

gfSpamCheck "${INDIVIDUALAPPLICANT1}" "Yes" ""
gfSpamCheck "${INDIVIDUALAPPLICANT2}" "Yes" ""
gfSpamCheck "${INDIVIDUALAPPLICANT3}" "Yes" ""
gfSpamCheck "${INDIVIDUALAPPLICANT4}" "Yes" ""
gfSpamCheck "${INDIVIDUALAPPLICANT5}" "Update Details" \
            "Please add more detail to your bio."
gfSpamCheck "${INDIVIDUALAPPLICANT6}" "No" ""

function gfVote {
    local applicant_id=$1
    local voter=$2
    local status="$3"
    gfCreateEntry "${voter}" \
                  "${GFVOTE}" \
                  "\"2\": \"${status}\", \"4\": \"${applicant_id}\""

}

gfVote "${INDIVIDUALAPPLICANT1}" "${IMC1}" "Yes"
gfVote "${INDIVIDUALAPPLICANT2}" "${IMC1}" "Yes"
gfVote "${INDIVIDUALAPPLICANT3}" "${IMC1}" "Yes"
gfVote "${INDIVIDUALAPPLICANT4}" "${IMC2}" "Yes"
gfVote "${INDIVIDUALAPPLICANT1}" "${IMC2}" "Yes"
gfVote "${INDIVIDUALAPPLICANT2}" "${IMC3}" "Yes"
gfVote "${INDIVIDUALAPPLICANT3}" "${IMC3}" "Yes"
gfVote "${INDIVIDUALAPPLICANT4}" "${IMC4}" "No"

# Vouchers must have been requested above!

function gfVouch {
    local applicant_id=$1
    local voucher=$2
    local status="$3"
    local note="$4"
    gfCreateEntry "${voucher}" \
                  "${GFVOUCH}" \
                  "\"3\": \"${status}\", \"4\": \"${note}\", \"7\": \"${applicant_id}\", \"8\": \"Yes\""
    if [ "${status}" = "Cannot" ]; then
        echo "ccgn_registration_user_set_stage( ${applicant_id}, CCGN_APPLICATION_STATE_UPDATE_VOUCHERS ); exit;" \
            | wp shell
    fi
}

gfVouch "${INDIVIDUALAPPLICANT1}" "${IM1}" "Yes" "They are awesome."
gfVouch "${INDIVIDUALAPPLICANT1}" "${IM2}" "Yes" "They are really awesome."
gfVouch "${INDIVIDUALAPPLICANT2}" "${IM2}" "Yes" "They are totally awesome."
gfVouch "${INDIVIDUALAPPLICANT2}" "${IM3}" "Yes" "They are incredibly awesome."
gfVouch "${INDIVIDUALAPPLICANT3}" "${IM3}" "Yes" "They are awesome."
gfVouch "${INDIVIDUALAPPLICANT3}" "${IM4}" "Cannot" ""
gfVouch "${INDIVIDUALAPPLICANT4}" "${IM4}" "Cannot" ""
gfVouch "${INDIVIDUALAPPLICANT4}" "${IM1}" "No" ""

function gfFinalApproval {
    local applicant_id=$1
    local status="$2"
    gfCreateEntry "${IMC1}" \
                  "${GFFINAL}" \
                  "\"1\": \"${status}\", \"3\": \"${applicant_id}\""
    if [ "${status}" = "Yes" ]; then
        echo "ccgn_user_level_set_approved( ${applicant_id} ); ccgn_create_profile( ${applicant_id} ); exit;" \
             | wp shell
    else
        echo "ccgn_user_level_set_rejected( ${applicant_id} ); exit;" \
             | wp shell
    fi
}

gfFinalApproval "${INDIVIDUALAPPLICANT1}" "Yes"
gfFinalApproval "${INDIVIDUALAPPLICANT4}" "No"


################################################################################
# Create Institutional Applicants
################################################################################

# Agree to terms

# Choose vouchers

# Institution details

# Spam check

# Vote

# Vouch

# Final Approval

# Legal Approval

################################################################################
# Done!
################################################################################

popd
