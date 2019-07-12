<?php
class WP_Widget_User_Status extends WP_Widget {
    /** constructor */
    function __construct() {
        $widget_ops = array('classname' => 'user-status', 'description' => 'Show the current user application status.');
        $control_ops = array();
        parent::__construct('user-status', 'CCGN Application status', $widget_ops, $control_ops);
    }
    private function get_progress_bar($step) {
        switch ($step) {
            case 1: return 0; break;
            case 2: return 33; break;
            case 3: return 63; break;
            case 4: return 100; break;
        }
    }
    private function get_point_status($current,$step) {
        if ( $current == $step ) {
            return ' checked ';
        } else if ( $current > $step ) {
            return ' empty not-yet ';
        } else if ( $current < $step ) {
            return ' past-event ';
        }
    }
    private function is_active($current,$step) {
        if ($current == $step)  {
            return 'data-toggle="user-status-panel"';
        } else {
            return '';
        }
    }
    function widget($args, $instance) {
        $title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
        $grid = ( !empty( $instance['grid'] ) ) ? $instance['grid'] : 8;
        $user_id = get_current_user_id();
        $application_status = ccgn_show_current_application_status($user_id);
        $current_step = $application_status['step']['step'];
        $progress = $this->get_progress_bar($current_step);
        echo '<div class="cell large-8">';
            echo '<div class="widget user-status">';
                echo '<h3 class="widget-title">'.$title.'</h3>';
                echo '<div class="widget-content">';
                    if ( !empty( $description ) ) {
                        echo '<p>'.$instance['description'].'</p>';
                    }
                    echo '<div class="user-progress">';
                        echo '<progress class="progress-bar primary" max="100" value="'.$progress.'"></progress>';
                        echo '<a href="#" class="status-point first '.$this->get_point_status(1,$current_step).'" '.$this->is_active(1,$current_step).' id="status-first-point"></a>';
                        echo '<a href="#" class="status-point second '.$this->get_point_status(2,$current_step).'" '.$this->is_active(2,$current_step).' id="status-second-point"></a>';
                        echo '<a href="#" class="status-point third '.$this->get_point_status(3,$current_step).'" '.$this->is_active(3,$current_step).' id="status-third-point"></a>';
                        echo '<a href="#" class="status-point fourth '.$this->get_point_status(4,$current_step).'" '.$this->is_active(4,$current_step).' id="status-third-point"></a>';
                        echo '<div class="dropdown-pane" id="user-status-panel" data-alignment="center" data-v-offset="30" data-dropdown data-hover-pane="true">';
                            echo '<div class="triangle"></div>';
                            echo '<h4 class="status-title">'.$application_status['step']['title'].'</h4>';
                            echo '<div class="status-content">';
                                echo '<p>'.$application_status['step']['msg'].'</p>';
                                echo '<div class="status-action">';
                                    echo '<a href="'.$application_status['step']['link'].'" class="button primary">'.$application_status['step']['link_text'].'</a>';
                                echo '</div>';
                            echo '</div>';
                            
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form( $instance ) {
        $color = $instance['color'];
        $grid = $instance['grid'];
        echo '<p><label for="'.$this->get_field_id('title').'">Title: <input type="text" name="'. $this->get_field_name('title') .'" id="'.$this->get_field_id('title').'" value="'.$instance['title'].'" class="widefat" /></label></p>';
        echo '<p><label for="'.$this->get_field_id('description').'">Description: <textarea name="'. $this->get_field_name('description') .'" id="'.$this->get_field_id('description').'" class="widefat">'.$instance['description'].'</textarea></label></p>';
        echo '<h3>Display</h3>';
        echo '<p><label>Columns: </label>';
            echo '<i>How many columns is going to use this widget. "auto" will take all the space available in the row</i>';
            echo '<select class="widefat" id="'.$this->get_field_id('grid').'" name="'.$this->get_field_name('grid').'">';
                echo '<option value="auto" '.(($grid == 'auto')  ?  'selected="selected"' : '') .'>Auto fit</option>';
                for ( $i = 8; $i <= 12; $i++) {
                    echo '<option value="'.$i.'" '.(($grid == $i) ? 'selected="selected"' : '') .'>'.$i.'</option>';
                }
            echo '</select>';
        echo '</p>';
       } 
}

function cc_user_status_widget_init()
{
    register_widget( 'WP_Widget_User_Status');
}

add_action('widgets_init', 'cc_user_status_widget_init');