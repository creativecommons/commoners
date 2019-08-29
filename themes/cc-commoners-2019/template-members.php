<?php
    /*Template name: CCGN Members*/
    get_header(); 
    $search = new members_search();
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    if ( get_query_var( 'paged' ) ) {
        $search->set_page( get_query_var( 'paged' ) );
    }
    if ( isset( $_GET['action']) ) {
        if ( isset( $_GET['search'] ) ) {
            $search->set_search_text( esc_attr( $_GET['search'] ) );
        }
        if ( isset( $_GET['country'] ) ) {
            $search->set_country( esc_attr( $_GET['country'] ) );
        }
        if ( isset( $_GET['languages'] ) ) {
            $search->set_country( esc_attr( $_GET['languages'] ) );
        }
    }

    $query = $search->search();
    $member_list = $query->get_results();
?>
<section class="main-content">
    <?php   
        global $post;
        
        if (has_post_thumbnail( MEMBERS_PAGE_ID )) {
            echo '<figure class="page-featured-image extended thin">';
                echo get_the_post_thumbnail(  MEMBERS_PAGE_ID , 'landscape-featured' );
                echo '<div class="content-wrap"></div>';
            echo '</figure>';
        }
     ?>
    <div class="grid-container">
        <div class="grid-x align-center search-members inner-space sidebar sidebar-move-up tiny-space">
            <div class="cell large-8 medium-8">
                <div class="widget big-search">
                    <form action="" method="GET" class="search-users-form">
                        <input type="text" name="search" class="input-search" <?php echo ( isset( $_GET['search'] ) ) ? 'value="'.esc_attr( $_GET['search'] ).'"' : ''; ?> placeholder="Search Members">
                        <input type="hidden" name="action" value="search">
                        <i class="ion-arrow-right-c"></i>

                        <div class="entry-display">
                            <div class="entry-dropdown">
                                <a href="#search-members-advanced" class="entry-title">Advanced search <i class="ion-arrow-down-b"></i></a>
                            </div>
                            <div class="entry-content closed" id="search-members-advanced">
                                <div class="grid-x grid-padding-x">
                                    <div class="cell auto">
                                        <?php 
                                            $countries = GF_Field_Address::get_countries();
                                            echo '<select name="country" id="country">';
                                                echo '<option value="">Select country</option>';
                                                foreach ( $countries as $country ) {
                                                    $selected = ( isset( $_GET['country'] ) && ( $_GET['country'] == $country ) ) ? ' selected="selected"' : '';
                                                    echo '<option value="'.$country.'"'.$selected.'>'.$country.'</option>';
                                                }
                                            echo '</select>';
                                        ?>
                                    </div>
                                    <div class="cell auto">
                                        <select name="application_type" id="application-type">
                                            <option value="">All Members</option>
                                            <option value="individual" <?php echo ( isset($_GET['application_type']) && ($_GET['application_type'] == 'individual') ) ? ' selected="selected" ' : '' ?> >Individual Members</option>
                                            <option value="institutional" <?php echo ( isset($_GET['application_type']) && ($_GET['application_type'] == 'institutional') ) ? ' selected="selected" ' : '' ?>>Institutional Members</option>
                                        </select>
                                    </div>
                                    <div class="cell auto">
                                        <input type="text" name="language" <?php echo ( isset( $_GET['language'] ) ) ? 'value="'.esc_attr( $_GET['language'] ).'"' : ''; ?> id="language" placeholder="Languages">
                                    </div>
                                    <div class="cell auto">
                                        <input type="submit" class="submit-form button" value="Search">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
        <div class="entry-content">
                <?php 
                    if ( !empty( $member_list) ) {
                        echo '<div class="grid-x grid-margin-x large-up-4 medium-up-4 small-up-2">';
                        foreach ( $member_list as $member ) {
                            echo render::member_single( $member );
                        }

                        $total_user = $query->total_users;
                        $total_pages = ceil( $total_user / $search->get_total_per_page() );
                        echo '<div class="navigation pagination custom-pagination">';
                            echo '<div class="nav-links">';
                                echo paginate_links(array(  
                                    'base' => add_query_arg('paged','%#%'),  
                                    'format' => '',
                                    'current' => $paged,
                                    'total' => $total_pages,  
                                    'prev_text' => 'Previous',  
                                    'next_text' => 'Next',
                                    'type'     => 'list',
                                ));
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                } else {
                    echo '<div class="callout warning">';
                        echo '<h5>No results</h5>';
                        echo '<p>Sorry, no results for your search criteria </p>';
                    echo '</div>';
                }
                ?>
            
        </div>
    </div>
</section>
<?php get_footer(); ?>