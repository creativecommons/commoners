var last_cas_server_data, aux_last_cas_server_data;
var last_ldap_server_data, aux_last_ldap_server_data;
//result texts
var characters_error = 'Minimum amount of chars is 3';
var checking_html = casmaestro.checking_html;

jQuery(document).ready(function() {
		//the min chars for username	

		// CAS init
		last_cas_server_data = {
			s: jQuery('#server_hostname_inp').val(),
			p: jQuery('#server_port_inp').val()
		};

		aux_last_cas_server_data = jQuery.extend({}, last_cas_server_data);

		// LDAP init
		last_ldap_server_data = {
			s: jQuery('#ldap_server').val(), 
			v: jQuery('#ldap_proto').val(), 
			u: jQuery('#ldap_user').val(), 
			pw: jQuery('#ldap_pass').val(), 
			bdn: jQuery('#ldap_bdn').val()
		}

		aux_last_ldap_server_data = jQuery.extend({}, last_ldap_server_data);

		//when button is clicked
		jQuery('#server_hostname_inp, #server_port_inp').focusout(function() {
			//run the character number check
			
			//else show the cheking_text and run the function to check
			aux_last_cas_server_data.s = jQuery('#server_hostname_inp').val();
			aux_last_cas_server_data.p = jQuery('#server_port_inp').val();

			check_cas();
		});

		jQuery('#ldap_proto, #ldap_server, #ldap_user, #ldap_pass, #ldap_bdn').focusout(function(){
			//run the character number check
			
			//else show the cheking_text and run the function to check
			aux_last_ldap_server_data.s = jQuery('#ldap_server').val();
			aux_last_ldap_server_data.v = jQuery('#ldap_proto').val();
			aux_last_ldap_server_data.u = jQuery('#ldap_user').val();
			aux_last_ldap_server_data.pw = jQuery('#ldap_pass').val();
			aux_last_ldap_server_data.bdn = jQuery('#ldap_bdn').val();

			check_ldap();
		});

  });

//function to check username availability
function check_cas(){

	//get the username
	if ((last_cas_server_data.s == aux_last_cas_server_data.s && last_cas_server_data.p == aux_last_cas_server_data.p) || !aux_last_cas_server_data.s || !aux_last_cas_server_data.p) {
		if (!aux_last_cas_server_data.s || !aux_last_cas_server_data.p) 
			jQuery('#username_availability_result').html('').removeClass('checking not-responding avaiable');
		return;
	}

	jQuery('#username_availability_result').html(checking_html).addClass('checking').removeClass('not-responding avaiable');

	var dir = casmaestro.url + 'ajax/validate_cas.php';
	//use ajax to run the check
	jQuery.post(dir, aux_last_cas_server_data,
		function(result){
			//if the result is 1
			if(result=='1'){
				//show that the username is available
				jQuery('#username_availability_result').html(casmaestro.cas_respond).addClass('avaiable').removeClass('not-responding checking');
			} else {
				//show that the username is NOT available
				jQuery('#username_availability_result').html(casmaestro.cas_not_respond).addClass('not-responding').removeClass('avaiable checking');
			}
		}
	);

	last_cas_server_data = jQuery.extend({}, aux_last_cas_server_data);
}

function check_ldap(){
	//get the username
	if ((last_ldap_server_data.s == aux_last_ldap_server_data.s && last_ldap_server_data.v == aux_last_ldap_server_data.v && last_ldap_server_data.u == aux_last_ldap_server_data.u && last_ldap_server_data.pw == aux_last_ldap_server_data.pw && last_ldap_server_data.bdn == aux_last_ldap_server_data.bdn) || !aux_last_ldap_server_data.s || !aux_last_ldap_server_data.v || !aux_last_ldap_server_data.u) {
		if (!aux_last_ldap_server_data.s || !aux_last_ldap_server_data.v || !aux_last_ldap_server_data.u)
			jQuery('#ldap_availability_result').html('').removeClass('checking not-responding avaiable');
		return;
	}

	jQuery('#ldap_availability_result').html(checking_html).addClass('checking').removeClass('not-responding avaiable');

	var dir = casmaestro.url + 'ajax/validate_ldap.php';
	//use ajax to run the check
	jQuery.post(dir, aux_last_ldap_server_data,
		function(result){
			//if the result is 1
			if(result == 1){
				//show that the username is available
				jQuery('#ldap_availability_result').html(casmaestro.ldap_respond).addClass('avaiable').removeClass('not-responding checking');

			}else{
				//show that the username is NOT available
				jQuery('#ldap_availability_result').html(casmaestro.ldap_not_respond).addClass('not-responding').removeClass('avaiable checking');
			}
	});

	last_ldap_server_data = jQuery.extend({}, aux_last_ldap_server_data);
}