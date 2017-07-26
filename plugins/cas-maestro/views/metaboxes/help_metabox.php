<p><?php _e('In case you can not access the content manager due to a misconfiguration of this plugin, the following steps should be performed:', 'CAS_Maestro'); ?></p>
<ol>
	<li><?php _e('Remove the directory of plugin CAS Maestro', 'CAS_Maestro'); ?></li>
	<li><?php _e('Perform access according to the login WordPess', 'CAS_Maestro'); ?></li>
	<li><?php _e('Reinstall CAS Maestro', 'CAS_Maestro'); ?></li>
</ol>
<p><?php _e('Alternatively, you may simply disable the behavior of CAS Maestro as follows:', 'CAS_Maestro');?></p>
<ol>
	<li><?php echo sprintf(__('Edit the file %s and search for %s definition', 'CAS_Maestro'),'wp-config.php',"<pre>define('WP_DEBUG', false);</pre>"); ?></li>
	<li><?php echo sprintf(__('Before that definition, write %s', 'CAS_Maestro'), "<pre>define('WPCAS_BYPASS',true);</pre>"); ?></li>
	<li><?php _e('Reconfigure the plugin and remove the line that was added.', 'CAS_Maestro'); ?></li>
</ol>

