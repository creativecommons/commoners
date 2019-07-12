<?php
class WP_Widget_Last_Event extends WP_Widget {
    /** constructor */
    function __construct() {
        $widget_ops = array('classname' => 'last-event', 'description' => 'Show the last event as a widget');
        $control_ops = array();
        parent::__construct('last-event', 'CCGN Last event', $widget_ops, $control_ops);
    }
    function get_last_events($size) {
    	$args = array(
                'post_type' => 'ccgnevents',
                'posts_per_page' => $size,
                'post_status' => 'publish'
            );
        $events = new WP_Query($args);
        if ( $events->have_posts() ) {
            return $events->posts;
        } else {
            return false;
        }
    }
    function widget($args, $instance) {
        $events = $this->get_last_events(1);
        $events_link = get_post_type_archive_link( 'ccgnevents' );
        if ( !empty( $events ) ) {
            $meta_chapter_id = get_post_meta($events[0],'event_chapter_id',true);
            if ( !empty( $meta_chapter_id ) ) {
                $meta_chapter_link = get_post_meta($meta_chapter_id, 'chapter_url' );
            }
            echo '<div class="cell large-'.$instance['grid'].' medium-'.$instance['grid'].' widget event">';
                echo '<div class="date-section">';
                    echo '<div class="date">';
                        echo '<span class="day-name">'.mysql2date('l', $events[0]->post_date).'</span>';
                        echo '<span class="day-number">'.mysql2date('d',$events[0]->post_date).'</span>';
                        echo '<span class="month">'.mysql2date('F', $events[0]->post_date).'</span>';
                    echo '</div>';
                    echo '<div class="widget-content">';
                        echo '<header class="event-header">';
                            echo '<h3 class="widget-title"><a href="'.get_permalink($events[0]->ID).'">'.get_the_title($events[0]->ID).'</a></h3>';
                            if ( !empty( $meta_chapter_id ) ) {
                                echo '<span class="subtitle">'.get_the_title( $meta_chapter_id ).'</span>';
                            }
                        echo '</header>';
                        echo '<section class="event-buttons">';
                            if ( !empty( $meta_chapter_id ) ) {
                                echo '<a href="'.$meta_chapter_link.'" class="button gray outline with-icon expanded">';
                                    echo '<i class="ion-calendar"></i> Events from '.get_the_title( $meta_chapter_id );
                                echo '</a>';
                            }
                            if ( $instance['is_link'] ) {
                                echo '<a href="'.$events_link.'" class="button gray outline with-icon expanded">';
                                    echo '<i class="ion-calendar"></i> View all upcoming events';
                                echo '</a>';
                            }
                        echo '</section>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="cell large-'.$instance['grid'].' medium-'.$instance['grid'].' widget event no-event">';
                echo '<h3 class="widget-title">Upcoming events</h3>';
                echo '<p>There is no upcoming events yet. You can check back later</p>';
            echo '</div>';
        }
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form( $instance ) {
        $is_link = $instance['is_link'];
        $grid = $instance['grid'];
        echo '<p><label for="'.$this->get_field_id('title').'">Titulo: <input type="text" name="'. $this->get_field_name('title') .'" id="'.$this->get_field_id('title').'" value="'.$instance['title'].'" class="widefat" /></label></p>';
        echo '<p><label for="'. $this->get_field_name('is_link').'">Link to all events? </label><input type="checkbox" id="'. $this->get_field_id('is_link').'"'.( ( !empty( $is_link ) ) ? ' checked="checked" ' : '' ).' name="'.$this->get_field_name('is_link').'" value="1"></p>';
        echo '<h3>Display</h3>';
        echo '<p><label>Columns: </label>';
            echo '<i>How many columns is going to use this widget. "auto" will take all the space available in the row</i>';
            echo '<select class="widefat" id="'.$this->get_field_id('grid').'" name="'.$this->get_field_name('grid').'">';
                echo '<option value="auto" '.(($grid == 'auto')  ?  'selected="selected"' : '') .'>Auto fit</option>';
                for ( $i = 1; $i <= 12; $i++) {
                    echo '<option value="'.$i.'" '.(($grid == $i) ? 'selected="selected"' : '') .'>'.$i.'</option>';
                }
            echo '</select>';
        echo '</p>';
       } 
}

function cc_last_event_widget_init()
{
    register_widget( 'WP_Widget_Last_Event');
}

add_action('widgets_init', 'cc_last_event_widget_init');