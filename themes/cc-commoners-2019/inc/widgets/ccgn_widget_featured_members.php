<?php
class WP_Widget_Featured_Members extends WP_Widget {
    /** constructor */
    function __construct() {
        $widget_ops = array('classname' => 'featured-members', 'description' => 'Show a defined list of featured members of the network');
        $control_ops = array();
        parent::__construct('featured-members', 'CCGN Featured members', $widget_ops, $control_ops);
    }
    function widget($args, $instance) {
        $grid = ( !empty($instance['grid']) ) ? $instance['grid'] : 9;
        $size = ( !empty( $instance['size'] ) ) ? $instance['size'] : 3;
        $users_per_row = ( !empty($instance['users_per_row']) ) ? $instance['users_per_row'] : 3;
        $featured_members = front::get_featured_members($size);
        if ( !empty( $featured_members ) ) {
            echo '<div class="cell large-'.$grid.' medium-'.$grid.'">';
                echo '<section class="widget members-list">';
                    echo '<div class="widget-header">';
                        echo '<h3 class="widget-title">'.$instance['title'].'</h3>';
                    echo '</div>';
                    echo '<div class="widget-content">';
                        echo '<div class="grid-x grid-padding-x large-up-'.$users_per_row.' medium-up-'.$users_per_row.'">';
                            foreach ( $featured_members as $person ) {
                                echo '<div class="cell">';
                                    echo render::person($person);
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                echo '</section>';
            echo '</div>';
        }
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form( $instance ) {
        $is_link = $instance['is_link'];
        $grid = $instance['grid'];
        $users_per_row = $instance['users_per_row'];
        echo '<p><label for="'.$this->get_field_id('title').'">Titulo: <input type="text" name="'. $this->get_field_name('title') .'" id="'.$this->get_field_id('title').'" value="'.$instance['title'].'" class="widefat" /></label></p>';
        echo '<p><label for="'.$this->get_field_id('size').'">Number of members: <input type="text" name="'. $this->get_field_name('size') .'" id="'.$this->get_field_id('size').'" value="'.$instance['size'].'" class="widefat" /></label></p>';
        echo '<h3>Apariencia</h3>';
        echo '<p><label>Columns: </label>';
            echo '<i>How many columns is going to use this widget. "auto" will take all the space available in the row</i>';
            echo '<select class="widefat" id="'.$this->get_field_id('grid').'" name="'.$this->get_field_name('grid').'">';
                echo '<option value="auto" '.(($grid == 'auto')  ?  'selected="selected"' : '') .'>Auto fit</option>';
                for ( $i = 6; $i <= 12; $i++) {
                    echo '<option value="'.$i.'" '.(($grid == $i) ? 'selected="selected"' : '') .'>'.$i.'</option>';
                }
            echo '</select>';
        echo '</p>';
        echo '<p><label>Users per Row: </label>';
            echo '<i>How many users per row this widget is going to show</i>';
            echo '<select class="widefat" id="'.$this->get_field_id('users_per_row').'" name="'.$this->get_field_name('users_per_row').'">';
                for ( $i = 1; $i <= 5; $i++) {
                    echo '<option value="'.$i.'" '.(($users_per_row == $i) ? 'selected="selected"' : '') .'>'.$i.'</option>';
                }
            echo '</select>';
        echo '</p>';
       } 
}

function cc_featured_members_widget_init()
{
    register_widget( 'WP_Widget_Featured_Members');
}

add_action('widgets_init', 'cc_featured_members_widget_init');