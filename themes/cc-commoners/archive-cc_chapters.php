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
                <div class="grid-x align-center">
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
                </div>

                <div class="view-switch show-for-large">
                    <div class="grid-x align-justify">
                        <div class="cell large-3 title">
                            Switch view
                        </div>
                        <div class="cell large-2 buttons">
                            <a href="#view-map" class="button gray active"><span class="dashicons dashicons-location-alt"></span> Map</a>
                            <a href="#view-list" class="button gray"><span class="dashicons dashicons-list-view"></span> List</a>
                        </div>
                    </div>
                </div>
                <div class="world-map view-content active show-for-large" id="view-map">
                    <?php get_template_part('template-parts/maps/world','map'); ?>
                    <div id="world-map-info">
                        <h2 class="chapter-title"></h2>
                        <p class="chapter-line">Founded on <span class="chapter-date"></span> </p>
                        <p class="chapter-lead">Chapter Lead: <span class="chapter-lead-name"></span></p>
                        <p class="chapter-buttons">
                            <a href="#" class="button warning contact">Contact</a>
                            <a href="#" class="button warning url">Web</a>
                            <a href="#" class="button warning meet">First Meet</a>
                        </p>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $chapters = Commoners::get_chapters(); 
                                if (!empty($chapters)) {
                                    foreach ($chapters as $chapter) {
                                        $chapter_url = (!empty($chapter->cc_chapters_url)) ? '<a href="'. filter_var($chapter->cc_chapters_url, FILTER_VALIDATE_URL) .'" target="_blank" class="button secondary tiny">View</a>' : 'No url';
                                        echo '<tr>';
                                            echo '<td>'.$chapter->post_title.'</td>';
                                            echo '<td>' . $chapter->cc_chapters_date . '</td>';
                                            echo '<td>' . $chapter->cc_chapters_email . '</td>';
                                            echo '<td>' . get_user_by('id', $chapter->cc_chapters_chapter_lead)->display_name . '</td>';
                                            echo '<td>' . get_user_by('id', $chapter->cc_chapters_member_gnc)->display_name . '</td>';
                                            echo '<td>' . $chapter_url. '</td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="grid-x grid-padding-x align-center">
            <div class="cell large-10">
                <div class="chapter-stats">
                    <?php $global_stats = Commoners::stats(); ?>
                    <div class="grid-x grid-margin-x large-up-3 medium-up-3 small-up-1">
                        <?php if (!empty($global_stats['total_members'])) : ?>
                            <div class="cell">
                                <article class="stat-box">
                                    <span class="stat-number"><?php echo $global_stats['total_members']; ?></span>
                                    <span class="title">Users</span>
                                </article>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($global_stats['active_chapters'])) : ?>
                            <div class="cell">
                                <article class="stat-box">
                                    <span class="stat-number"><?php echo $global_stats['active_chapters']; ?></span>
                                    <span class="title">Chapters</span>
                                </article>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($global_stats['in-progress_chapters'])) : ?>
                            <div class="cell">
                                <article class="stat-box with-subtitle">
                                    <span class="stat-number"><?php echo $global_stats['in-progress_chapters']; ?></span>
                                    <span class="subtitle">In-progress</span>
                                    <span class="title">Chapters</span>
                                </article>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>