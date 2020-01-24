<?php 
    get_header();
    get_post();
    global $_set;
    $settings = $_set->settings;
    $section_title = (!empty($settings['chapters_title'])) ? $settings['chapters_title'] : 'Chapters';
?>
<section class="main-content space-top">
    <div class="grid-container">
        <div class="grid-x grid-padding-x align-center">
            <div class="cell large-12">
                <header class="grid-x align-center section-header header-chapters">
                    <div class="large-10 cell entry-content" id="entry-chapters-content">
                        <div class="entry-header">
                            <h1 class="entry-title"><?php echo $section_title ?></h1>
                        </div>
                        <section class="entry-content">
                            <div class="post-content">
                                <?php echo apply_filters('the_content', $settings['chapters_content']); ?>
                            </div>
                        </section>
                    </div>
                </header>

                <div class="view-switch show-for-large">
                    <div class="grid-x align-justify">
                        <div class="cell large-10 title">
                            <div class="map-header-content" id="chapters-map-header">
                                <div class="inline-container">
                                    <h3 class="chapter-title"></h3>
                                    <div class="chapter-metadata">
                                        <p>Chapter lead: <span class="chapter-lead-name"></span></p>
                                        <p><small>founded on <em class="chapter-date"></em> </small></p>
                                    </div>
                                    <a href="#" class="button warning more">More information</a>
                                </div>
                            </div>
                        </div>
                        <div class="cell large-2 buttons">
                            <a href="#view-map" class="button gray active"><span class="dashicons dashicons-location-alt"></span> Map</a>
                            <a href="#view-list" class="button gray"><span class="dashicons dashicons-list-view"></span> List</a>
                        </div>
                    </div>
                </div>
                <div class="world-map view-content active show-for-large" id="view-map">
                    <?php get_template_part('inc/partials/maps/world','map'); ?>
                    <div class="text-center"><em>Click in a highlighted country to get more information</em></div>
                    <div id="country-name">
                        <span class="chapter-title"></span>
                    </div>
                </div>
                <div class="map-list view-content" id="view-list">
                    <table class="chapters-table" id="chapters-table">
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Date founded</td>
                                <td>Email</td>
                                <td>Chapter Lead</td>
                                <td>GNC Representative</td>
                                <td>Url</td>
                                <td>Website</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $chapters = Commoners::get_chapters();
                                if (!empty($chapters)) {
                                    foreach ($chapters as $chapter) {
                                        $chapter_url = (!empty($chapter->cc_chapters_url)) ? '<a href="'. filter_var($chapter->cc_chapters_url, FILTER_VALIDATE_URL) .'" target="_blank" class="button secondary tiny">View</a>' : 'No url';
                                        $website_url = (!empty($chapter->cc_chapters_chapter_url)) ? '<a href="'. filter_var($chapter->cc_chapters_chapter_url, FILTER_VALIDATE_URL) .'" target="_blank" class="button secondary tiny">View</a>' : 'No website';
                                        echo '<tr>';
                                            echo '<td><a href="'.get_permalink( $chapter->ID ).'">'.$chapter->post_title.'</a></td>';
                                            echo '<td>' . render::date_format( $chapter->cc_chapters_date ) . '</td>';
                                            echo '<td>' . antispambot($chapter->cc_chapters_email) . '</td>';
                                            echo '<td>' . get_user_by('id', $chapter->cc_chapters_chapter_lead)->display_name . '</td>';
                                            echo '<td>' . get_user_by('id', $chapter->cc_chapters_member_gnc)->display_name . '</td>';
                                            echo '<td>' . $chapter_url. '</td>';
                                            echo '<td>' . $website_url. '</td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>