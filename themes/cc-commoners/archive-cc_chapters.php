<?php 
    get_header();
    get_post();
?>
<section class="main-content space-top">
    <div class="grid-container">
        <div class="grid-x grid-padding-x">
            <div class="cell large-12">
                <div class="entry-header">
                    <h1 class="entry-title">Chapters</h1>
                </div>
                <div class="view-switch">
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
                <div class="world-map view-content active" id="view-map">
                    <?php get_template_part('template-parts/maps/world','map'); ?>
                    <div id="world-map-info">
                        <h2 class="chapter-title">CC titirilquen</h2>
                        <p class="chapter-line">Founded on <span class="chapter-date">29 May, 2018</span> </p>
                        <p class="chapter-lead">Chapter Lead: <span class="chapter-lead-name">Juan Carlos bodoque</span></p>
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
                                        echo '<tr>';
                                            echo '<td>'.$chapter->post_title.'</td>';
                                            echo '<td>' . $chapter->cc_chapters_date . '</td>';
                                            echo '<td>' . $chapter->cc_chapters_email . '</td>';
                                            echo '<td>' . get_user_by('id', $chapter->cc_chapters_chapter_lead)->display_name . '</td>';
                                            echo '<td>' . get_user_by('id', $chapter->cc_chapters_member_gnc)->display_name . '</td>';
                                            echo '<td>' . $chapter->cc_chapters_url . '</td>';
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