<?php
/**
 * Output the search form markup.
 *
 * @since 2.7.0
 * @version 3.0.0
 */
$search_value= (isset($_REQUEST['members_search'])) ? esc_attr($_REQUEST['members_search']) : '';
?>

<div id="<?php echo esc_attr( bp_current_component() ); ?>-dir-search" class="dir-search" role="search">
	<form action="" method="get" id="search-<?php echo esc_attr( bp_current_component() ); ?>-form">
		<label for="<?php bp_search_input_name(); ?>" class="bp-screen-reader-text"><?php bp_search_placeholder(); ?></label>
		<input type="text" name="<?php echo esc_attr( bp_core_get_component_search_query_arg() ); ?>" value="<?php echo $search_value ?>" id="<?php bp_search_input_name(); ?>" placeholder="<?php bp_search_placeholder(); ?>" />

		<input type="submit" id="<?php echo esc_attr( bp_get_search_input_name() ); ?>_submit" name="<?php bp_search_input_name(); ?>_submit" value="<?php esc_attr_e( 'Search', 'buddypress' ); ?>" />
	</form>
</div><!-- #<?php echo esc_attr( bp_current_component() ); ?>-dir-search -->
