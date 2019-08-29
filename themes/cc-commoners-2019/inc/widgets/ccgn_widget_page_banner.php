<?php
class WP_Widget_banner_page_link extends WP_Widget {
    /** constructor */
    function __construct() {
        $widget_ops = array('classname' => 'banner-page-link', 'description' => 'A simple color banner with a link');
        $control_ops = array();
        parent::__construct('banner-page-link', 'CCGN Banner Page link', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        $color = ( !empty( $instance['color'] ) ) ? $instance['color'] : 'outline';
        $link_text = ( !empty( $instance['link_text'] ) ) ? $instance['link_text'] : 'View more';
        $url = ( !empty($instance['page'])) ?  get_permalink($instance['page']) : '#';
        if ( !empty($instance['page'] ) ) {
            $title = ( !empty($instance['title']) ) ? $instance['title'] : get_the_title($instance['page']);
            echo '<div class="cell large-'.$instance['grid'].'" medium-'.$instance['grid'].'>';
                echo '<section class="widget page '.$color.'">';
                    echo '<a href="'.$url.'">';
                        echo '<header class="widget-header">';
                            echo '<h3 class="widget-title">'.$title.'</h3>';
                        echo '</header>';
                        echo '<div class="widget-content">';
                            echo '<span class="arrow-link text-right"><i class="ion-arrow-right-c"></i></span>';
                        echo '</div>';
                    echo '</a>';
                echo '</section>';
            echo '</div>';
        }
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form( $instance ) {
        $color = $instance['color'];
        $grid = $instance['grid'];
        echo '<p><label for="'.$this->get_field_id('title').'">Title: <input type="text" name="'. $this->get_field_name('title') .'" id="'.$this->get_field_id('title').'" value="'.$instance['title'].'" class="widefat" /></label></p>';
        echo '<p><label for="'.$this->get_field_id('page').'">Destination page:';
        wp_dropdown_pages( array(
            'selected' => $instance['page'],
            'name' => $this->get_field_name('page'),
            'id' => $this->get_field_id('page'),
            'show_option_none' => 'Seleccionar página'
            ) );
        echo '</label></p>';
        echo '<h3>Display</h3>';
        echo '<p><label>Color: </label>';
        echo '<select class="widefat" id="'.$this->get_field_id('color').'" name="'.$this->get_field_name('color').'">';
            echo '<option value="">Select</option>';
                echo '<option value="highlight"'. (($color == 'highlight') ? 'selected="selected"' : '') .'>Highlight</option>';
                echo '<option value="dark"'. (($color == 'dark') ? 'selected="selected"' : '') .'>Dark</option>';
                echo '<option value="primary"'. (($color == 'primary') ? 'selected="selected"' : '') .'>Light Gray</option>';
                echo '<option value="secondary" '.(($color == 'secondary') ? 'selected="selected"' : '') .'>Outline</option>';
            echo '</select>';
        echo '</p>';
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

function cc_banner_page_widget_init()
{
    register_widget( 'WP_Widget_banner_page_link');
}

add_action('widgets_init', 'cc_banner_page_widget_init');