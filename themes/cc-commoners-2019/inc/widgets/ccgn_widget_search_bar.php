<?php
class WP_Widget_Search_User extends WP_Widget {
    /** constructor */
    function __construct() {
        $widget_ops = array('classname' => 'search_user', 'description' => 'Show a big search user input box');
        $control_ops = array();
        parent::__construct('search_user', 'CCGN Search users', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        $grid = ( !empty( $instance['grid'] ) ) ? $instance['grid'] : 8;
        $title = ( !empty( $instance['title'] ) ) ? $instance['title'] : 'Search members';
        echo '<div class="cell large-'.$grid.'">';
            echo '<div class="widget big-search">';
                echo '<form action="'.site_url( 'members' ).'" method="GET" class="search-users">';
                    echo '<input type="text" name="search" class="input-search" placeholder="'.$title.'">';
                    echo '<input type="hidden" name="action" value="search">';
                    echo '<i class="ion-arrow-right-c"></i>';
                echo '</form>';
            echo '</div>';
        echo '</div>';
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form( $instance ) {
        extract( $instance );
        echo '<p><label for="'.$this->get_field_id('title').'">Title: <input type="text" name="'. $this->get_field_name('title') .'" id="'.$this->get_field_id('title').'" value="'.$instance['title'].'" class="widefat" /></label></p>';
        echo '<h3>Display</h3>';
        echo '<p><label>Columns: </label>';
            echo '<i>How many columns is going to use this widget. "auto" will take all the space available in the row</i>';
            echo '<select class="widefat" id="'.$this->get_field_id('grid').'" name="'.$this->get_field_name('grid').'">';
                echo '<option value="auto" '.(($grid == 'auto')  ?  'selected="selected"' : '') .'>Auto fit</option>';
                for ( $i = 6; $i <= 12; $i++) {
                    echo '<option value="'.$i.'" '.(($grid == $i) ? 'selected="selected"' : '') .'>'.$i.'</option>';
                }
            echo '</select>';
        echo '</p>';
       } 
}

function cc_search_user_widget_init()
{
    register_widget( 'WP_Widget_Search_User');
}

add_action('widgets_init', 'cc_search_user_widget_init');