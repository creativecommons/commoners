<?php 
    /* Template name: Get involved */
    get_header(); 
    the_post();
    global $post;
    $in_child_page = ( $post->post_parent != 0 ) ? true : false;
    $post_id = ( $in_child_page ) ? $post->post_parent : $post->ID;
    $current_select_title = ( $in_child_page != 0 ) ? get_the_title( $post->ID ) : "I'm good at...";

?>
<section class="main-content">
    <?php   
        $get_involved = get_page_by_path( 'get-involved' );
        if ( has_post_thumbnail($get_involved->ID) ) {
            echo '<figure class="page-featured-image extended">';
                echo get_the_post_thumbnail( $get_involved->ID, 'landscape-featured' );
                echo '<div class="content-wrap">';
                    echo '<div class="feature-content">';
                        echo '<h1 class="entry-title">'.get_the_title( $get_involved->ID ).'</h1>';
                        echo apply_filters('the_content', $get_involved->post_excerpt);
                    echo '</div>';
                echo '</div>';
            echo '</figure>';
        }
     ?>
     <div class="grid-container">
         <div class="grid-x align-center sidebar sidebar-move-up tiny-space">
             <div class="cell large-8 auto">
                 <div class="module big-select">
                     <?php 
                        if ( $in_child_page ) {
                            echo '<span class="subtitle">I\'m good at</span>';
                        }
                      ?>
                     <a href="#content-selector" class="select-label selector"><?php echo $current_select_title; ?> <i class="ion-arrow-down-b"></i></a>
                     <div class="content-selector closed" id="content-selector">
                         
                         <ul class="menu vertical">
                             <?php
                                $params = array(
                                    'child_of' => $post_id,
                                    'show_date' => '',
                                    'title_li' => ''
                                );
                                if ( $in_child_page ) {
                                    $params['exclude'] = $post->ID;
                                }
                                wp_list_pages($params);

                                if ( $in_child_page ) {
                                    echo '<li class="back-link"><a href="'.get_permalink($post_id).'"> <i class="ion-arrow-left-c"></i> Back</a></li>';
                                }
                            ?>
                         </ul>
                     </div>
                 </div>
                 <section class="entry-content inner-space">
                    <div class="content-format">
                        <?php 
                            the_content();    
                        ?>
                    </div>
                </section>
             </div>
         </div>
     </div>
</section>
<?php get_footer(); ?>