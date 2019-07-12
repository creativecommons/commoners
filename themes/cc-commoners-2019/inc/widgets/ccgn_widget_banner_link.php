<?php
class WP_Widget_banner_link extends WP_Widget {
    /** constructor */
    function __construct() {
        $widget_ops = array('classname' => 'banner-link', 'description' => 'Provide a simple way to create a banner with a title, description and link');
        $control_ops = array();
        parent::__construct('banner-link', 'CCGN Banner link', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        $color = ( !empty( $instance['color'] ) ) ? $instance['color'] : 'outline';
        $link_text = ( !empty( $instance['link_text'] ) ) ? $instance['link_text'] : 'View more';

        echo '<div class="cell auto widget banner-link '.$color.'">';
            if ( !empty( $instance['title'] ) ) {
                echo '<h3 class="widget-title">'.$instance['title'].'</h3>';
            }
            echo '<div class="widget-content">';
                if ( !empty( $instance['description'] ) ) {
                    echo apply_filters('the_content', $instance['description']);
                }
            echo '</div>';
            if ( !empty( $instance['url'] ) )
            echo '<div class="widget-link">';
                echo '<a href="'.esc_url($instance['url']).'" class="single-link with-icon">'.$link_text.' <i class="ion-chevron-right"></i></a>';
            echo '</div>';
        echo '</div>';
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form( $instance ) {
        extract( $instance );
        echo '<p><label for="'.$this->get_field_id('title').'">Title: <input type="text" name="'. $this->get_field_name('title') .'" id="'.$this->get_field_id('title').'" value="'.$instance['title'].'" class="widefat" /></label></p>';
        echo '<p><label for="'.$this->get_field_id('description').'">Description: <textarea name="'. $this->get_field_name('description') .'" id="'.$this->get_field_id('description').'" class="widefat">'.$instance['description'].'</textarea></label></p>';
        echo '<p><label for="'.$this->get_field_id('link_text').'">Link text (optional): <input type="text" name="'. $this->get_field_name('link_text') .'" id="'.$this->get_field_id('link_text').'" value="'.$instance['link_text'].'" class="widefat" /></label></p>';
        echo '<p><label for="'.$this->get_field_id('url').'">Url: <input type="text" name="'. $this->get_field_name('url') .'" id="'.$this->get_field_id('url').'" value="'.$instance['url'].'" class="widefat" /></label></p>';
        echo '<h3>Display</h3>';
        echo '<p><label>Color: </label>';
        echo '<select class="widefat" id="'.$this->get_field_id('color').'" name="'.$this->get_field_name('color').'">';
            echo '<option value="">Select</option>';
                echo '<option value="light-gray"'. (($color == 'light-gray') ? 'selected="selected"' : '') .'>Light Gray</option>';
                echo '<option value="outline" '.(($color == 'outline') ? 'selected="selected"' : '') .'>Outline</option>';
            echo '</select>';
        echo '</p>';
       } 
}

function cc_text_banner_widget_init()
{
    register_widget( 'WP_Widget_banner_link');
}

add_action('widgets_init', 'cc_text_banner_widget_init');