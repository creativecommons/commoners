<?php
class WP_Widget_Subscribe extends WP_Widget {
    /** constructor */
    function __construct() {
        $widget_ops = array('classname' => 'subscribe', 'description' => 'Subscribe to CC newsletter form');
        $control_ops = array();
        parent::__construct('subscribe', 'CCGN Newsletter Subscribe', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        echo '<div class="cell auto widget subscribe">';
            echo '<h3 class="widget-title">'.$instance['title'].'</h3>';
            echo '<p>'.$instance['description'].'</p>';
            if ( !empty( $instance['gforms_shortcode'] ) ) {
                echo do_shortcode( $instance['gforms_shortcode'] );
            }
        echo '</div>';
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form( $instance ) {
        extract( $instance );
        echo '<p><label for="'.$this->get_field_id('title').'">Title: <input type="text" name="'. $this->get_field_name('title') .'" id="'.$this->get_field_id('title').'" value="'.$instance['title'].'" class="widefat" /></label></p>';
        echo '<p><label for="'.$this->get_field_id('description').'">Description: <textarea name="'. $this->get_field_name('description') .'" id="'.$this->get_field_id('description').'" class="widefat">'.$instance['description'].'</textarea></label></p>';
        echo '<p><label for="'.$this->get_field_id('gforms_shortcode').'">Gforms Shortcode: <input type="text" name="'. $this->get_field_name('gforms_shortcode') .'" id="'.$this->get_field_id('gforms_shortcode').'" value="'.$instance['gforms_shortcode'].'" class="widefat" /></label></p>';
       } 
}

function cc_subscribe_widget_init()
{
    register_widget( 'WP_Widget_Subscribe');
}

add_action('widgets_init', 'cc_subscribe_widget_init');