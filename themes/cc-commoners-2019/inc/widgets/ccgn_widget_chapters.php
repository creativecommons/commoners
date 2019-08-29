<?php
class WP_Widget_Chapter_List extends WP_Widget {
    /** constructor */
    function __construct() {
        $widget_ops = array('classname' => 'chapters-list', 'description' => 'Show a random list of chapters with some statistics.');
        $control_ops = array();
        parent::__construct('chapters-list', 'CCGN Chapters list', $widget_ops, $control_ops);
    }
    
    function widget($args, $instance) {
        $title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
        $stats = Commoners::stats();
        $user_id = get_current_user_id();
        $application_status = ccgn_show_current_application_status($user_id);
        $current_step = $application_status['step']['step'];
        echo '<div class="cell widget chapters">';
            echo '<div class="widget-title">'.$title.'</div>';
            echo '<div class="widget-content">';
                echo '<div class="stats">';
                    echo '<article class="stats-item">';
                        echo '<span class="stats-number">'.$stats['active_chapters'].'</span>';
                        echo '<span class="stats-name">Chapters</span>';
                    echo '</article>';
                    echo '<article class="stats-item">';
                        echo '<span class="stats-number">'.$stats['total_members'].'</span>';
                        echo '<span class="stats-name">Members</span>';
                    echo '</article>';
                echo '</div>';
                echo '<div class="chapter-list">';
                    echo '<ul class="arrow-list">';
                        $chapters = Commoners::get_chapters('active',true, 4);
                        $chapters_link = get_post_type_archive_link( 'cc_chapters' );
                        foreach ( $chapters as $chapter ) {
                            echo '<li><a href="'.get_permalink( $chapter ).'">'.get_the_title( $chapter ).' <i class="ion-arrow-right-c"></i></a></li>';
                        }
                        echo '<li><a href="'.$chapters_link.'">View all Chapters <i class="ion-arrow-right-c"></i></a></li>';
                    echo '</ul>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form( $instance ) {
        echo '<p><label for="'.$this->get_field_id('title').'">Title: <input type="text" name="'. $this->get_field_name('title') .'" id="'.$this->get_field_id('title').'" value="'.$instance['title'].'" class="widefat" /></label></p>';
       } 
}

function cc_chapter_list_widget_init()
{
    register_widget( 'WP_Widget_Chapter_List');
}

add_action('widgets_init', 'cc_chapter_list_widget_init');