=== CAS Maestro ===
Contributors: vaurdan, jpargana, ricardobaeta, sandrof
Donate link: https://dsi.tecnico.ulisboa.pt
Tags: cas, maestro, central, centralized, authentication, auth, service, system, server, phpCAS, integration, ldap
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

CAS Maestro allows you to configure your centralized authentication service, CAS, for an integrated log in with WordPress. LDAP is optional.

== Description ==

If you have a CAS service and you want to authenticate your users in WordPress with the same credentials, you can use this plugin to get the job done.

The users that attempt to start their sessions in WordPress, will be redirected to the CAS single sign-on page, where their sessions starts. If the user data is valid, they are redirected back to WordPress. If the credentials already exist in your WordPress the user will be authenticated. Otherwise, if the user was pre-registered in the configuration page, the user will be created.

CAS Maestro can also connect to a LDAP server to access personal data to be used in user profile.

Features included:

* Full integration with the WordPress authentication system
* One of the most secure CAS plugins for WordPress
* Possibility to pre-register some known users, with the desired role
* LDAP integration for user data fill, such as name and e-mail 
* Validation mechanisms to avoid getting blocked in case of misconfiguration
* Mail notification for pre-registered users
* Network activation allowed (todo: network panel for configuration)

== Installation ==

1. Install Cas Maestro either via the WordPress.org plugin directory, or by uploading the files to your server (`/wp-content/plugins/`) 
2. Activation can be made in 'Plugins' menu
3. Configure carefully Cas Maestro through plugin's page

**ATTENTION** If for some reason you are unable to access the administrator panel, you can disable the CAS Maestro behavior by adding the code line define('WPCAS_BYPASS',true); to `wp-config.php` file. That way you can configure CAS Maestro before revert the previous instruction.

**Did you know...** If you leave empty fields in CAS Maestro configuration, the plugin will ask you to fill fields before final activation. Therefore you can use WordPress login system before the configuration conclusion.

== Frequently Asked Questions ==

= In case that I cannot access the content manager due to a misconfiguration of this plugin, what steps should I perform? =

You can bypass the CAS Authentication logging-in on http://www.example.com/wp-login.php?wp. This will allow you to login using your WordPress account.

Beside that, you can temporary disable the WordPress behavior doing the following: 

1. Edit the file wp-config.php and search for `define('WP_DEBUG', false)`su; definition
2. Before that definition, write `define('WPCAS_BYPASS',true)`;
3. Reconfigure the plugin and remove the line that was added.

Alternatively, you may simply uninstall CAS Maestro as follows:

1. Remove the directory of plugin CAS Maestro
2. Perform access according to the login WordPess
3. Reinstall CAS Maestro

= It is possible to login using WordPress accounts? =
Yes. But the login URL is slighty different: you must login over `/wp-login.php?wp` URL. This will give access to the standard WordPress login form so you can use both authentication methods.

= I want to change the capability that allows users to edit the users allowed to register. How can I do it? =
There is a filter `cas_maestro_change_users_capability` that can be used to change the capability. You can add the following to your functions.php:
`function change_casmaestro_capabilities($old) {
	return 'your_new_capability';
}
add_filter('cas_maestro_change_users_capability', 'change_casmaestro_capabilities');`
By default, the capability is `edit_posts`.

== Screenshots ==

1. The full CAS Maestro settings page
2. CAS Server settings
3. Mailing options

== Changelog ==

= 1.1.3 =
* Users with 'edit_posts' capability can now edit only the authorized users (this can be changed using a filter - see FAQ)

= 1.1.2 =
* Fixed bug with wrong type of the CAS Server version

= 1.1.1 =
* CAS Server Path is no longer a mandatory field 

= 1.1 =
* Bypass to the CAS authentication implemented using a query parameter
* Minor bug fixes

= 1.0.4 =
* phpCAS deprecated functions replaced

= 1.0.3 =
* Minor bug fixes

= 1.0.2 =
* Fixed php short tag bug
* Fixed 'Undefined index' notices (thanks [zacwaz](http://wordpress.org/support/profile/zacwaz))

= 1.0.1 =
* Bug fix with includes, ready for WordPress 3.9

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.1 =
New version with CAS Auth bypass
