<div class="wrap">
	    <div id="icon-themes" class="icon32"><br></div>
        <h2><?php echo __('CAS Maestro Settings','CAS_Maestro')?></h2>
        <?php
            if ( isset( $_GET['success'] ) && 'true' == esc_attr( $_GET['success'] ) 
                && !isset($_GET['error'])) 
                    echo '<div class="updated" ><p>'.__('CAS Maestro settings has been updated.', 'CAS_Maestro').'</p></div>';
            if(isset($_GET['error']))
                echo '<div class="error"><p>'.__('CAS Maestro settings has been updated, yet there\'s still information that needs to be filled.','CAS_Maestro').'</p></div>';
        ?>
        <form method="post" class="cas_form">
           <?php
                wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false );
                wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false );
            ?>
          <div id="poststuff" class="columns metabox-holder">
            <div class="postbox-container column-primary">
            <?php do_meta_boxes($this->current_page_hook, 'main', $this); ?>
            </div>
            <div class="postbox-container column-secondary">
            <?php do_meta_boxes($this->current_page_hook, 'side', $this); ?>
            </div>
        </form>
</div>