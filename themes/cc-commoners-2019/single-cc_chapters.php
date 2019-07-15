<?php 
    get_header();
 ?>
 <section class="main-content">
    <div class="grid-container hentry">
        <div class="grid-x align-center">
            <div class="cell large-8">
                <header class="entry-header header-chapter">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <a href="<?php echo site_url('chapter') ?>" class="back-link"><i class="ion-arrow-left-c"></i> Back to chapter list</a>
                </header>
                <section class="entry-content chapter-content inner-space">
                    <?php the_content(); ?>
                </section>
                <section class="entry-meta chapter-metadata">
                    <?php 
                        global $post;
                        $date_founded = $post->cc_chapters_date;
                        $chapter_mail = $post->cc_chapters_email;
                        $chapter_lead = $post->cc_chapters_chapter_lead;
                        $member_gnc = $post->cc_chapters_member_gnc;
                        $external_url = $post->cc_chapters_url;
                        $chapter_url = $post->cc_chapters_chapter_url;
                        $meeting_url = $post->cc_chapters_meeting_url;
                        $mailing_list = $post->cc_chapters_mailing_list;
                     ?>
                    <div class="grid-x grid-padding-x align-justify align-middle inner-space tight-padding enclosed">
                        <?php 
                            if ( !empty( $date_founded ) ) {
                                echo '<div class="cell auto">';
                                    echo '<span class="light-text">Founded: '.render::date_format( $date_founded ).'</span>';
                                echo '</div>';
                            }
                            if ( !empty( $chapter_mail ) ) {
                                echo '<div class="cell large-3 medium-3 align-self-middle">';
                                    $antispam_mail = antispambot( $chapter_mail );
                                    echo '<a href="mailto:'.$antispam_mail.'?cc=network@creativecommons.org" class="button secondary">Contact chapter</a>';
                                echo '</div>';
                            } 
                        ?>
                    </div>
                    <div class="grid-x grid-margin-x large-up-4 align-center inner-space">
                        <?php 
                            if ( !empty( $external_url ) ) {
                                echo '<div class="cell text-center">';
                                    echo '<a href="'.esc_url($external_url).'" target="_blank" class="button gray">Social url</a>';
                                echo '</div>';
                            }
                            if ( !empty( $chapter_url ) ) {
                                echo '<div class="cell text-center">';
                                    echo '<a href="'.esc_url($chapter_url).'" target="_blank" class="button gray">Chapter website</a>';
                                echo '</div>';
                            }
                            if ( !empty( $mailing_list ) ) {
                                echo '<div class="cell text-center">';
                                    echo '<a href="'.esc_url($mailing_list).'" target="_blank" class="button gray">Mailing list</a>';
                                echo '</div>';
                            }
                            if ( !empty( $meeting_url ) ) {
                                echo '<div class="cell text-center">';
                                    echo '<a href="'.esc_url($meeting_url).'" target="_blank" class="button gray">Meeting</a>';
                                echo '</div>';
                            }
                         ?>
                    </div>
                    <?php 
                        echo '<div class="grid-x grid-margin-x large-up-2 medium-up-2">';
                            if ( !empty( $chapter_lead ) ) {
                                echo render::chapter_member($chapter_lead, 'Chapter Lead');
                            }
                            if ( !empty( $member_gnc ) ) {
                                echo render::chapter_member($member_gnc, 'Representative to the Global Network Council');
                            }
                        echo '</div>';
                        
                     ?>
                </section>
            </div>
        </div>
    </div>
 </section>

 <?php get_footer(); ?>