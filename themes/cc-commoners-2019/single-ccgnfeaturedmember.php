<?php 
    get_header();
    the_post();
?>
<section class="main-content">
    <div class="grid-container">
        <div class="grid-x align-center hentry">
            <div class="cell large-8">
                <header class="entry-header featured-member-header text-center inner-space">
                    <h1 class="entry-title"><?php the_title() ?></h1>
                    <?php 
                        global $post;
                        $member_id = $post->featuredmember_featured_member;
                        $member_statement = $post->featuredmember_abstract;
                        $profile_link = bp_core_get_user_domain( $member_id );
                        $user = get_user_by('ID', $member_id);
                        $country = $country = xprofile_get_field_data('Location', $member_id );
                        $bp_member_img = bp_core_fetch_avatar ( array( 'item_id' => $member_id, 'type' => 'full', 'width' => 300, 'height' => 300 ) );

                        echo '<figure class="entry-image rounded">';
                            echo $bp_member_img;
                        echo '</figure>';
                        echo '<span class="light-text">'.$country.'</span>';
                     ?>
                </header>
                <section class="entry-content text-center feature-member-statement">
                    <?php echo apply_filters( 'the_content', $member_statement ); ?>
                    <div class="inner-space">
                        <a href="<?php echo $profile_link ?>" class="button secondary"> View profile </a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>