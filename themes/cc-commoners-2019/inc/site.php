<?php
class front {
    static function page_title(){
		$get= get_queried_object();
		if (is_post_type_archive())
			return 'List '.$get->labels->name;
		if (is_category())
			return 'Category: '.$get->name;
		if (is_tag())
			return '#'.$get->name;
		if (is_tax())
			return 'List '.$get->name;
		if (is_search())
			return 'Search for por: &#8220;'. get_search_query() .'&#8221;';
		if (is_404())
			return 'Sorry, page not found';
	}
    public static function get_post_type($post_type,$size, $return = 'ENTRIES',$order = 'menu_order', $orderby='ASC') {
        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => $size,
            'orderby' => $order,
            'order' => $orderby
        );
        $query = new WP_Query($args);
        if ($query->have_posts() && ($return == 'ENTRIES')) {
            return $query->posts;
        } else if ( $query->have_posts() && ($return == 'OBJECT') ) {
            return $query;
        } else {
            return false;
        }
    }
    public static function get_last_featured() {
        $featured_items = self::get_post_type('ccgnfeature', 1);
        return $featured_items;
    }
    public static function get_featured_members($size) {
        $featured_members = self::get_post_type('ccgnfeaturedmember', $size,'ENTRIES','rand');
        return $featured_members;
    } 
}

class Commoners {
    public static function get_chapters_by_status() {
        $status='active';
        $params = array(
            'post_type' => 'cc_chapters',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'cc_chapters_chapter_status',
            'meta_value' => $status
        );
        $query = new WP_Query($params);
        
        $results = [];
        if ($query->have_posts()) {
            foreach ($query->posts as $post) {
                $chapter_lead = get_post_meta($post->ID, 'cc_chapters_chapter_lead',false);
                $chapter_lead_name = '';
                if (count($chapter_lead) > 1) {
                    foreach ($chapter_lead as $member_id) {
                        $chapter_lead_name[] = get_user_by('id', $member_id)->display_name;
                    }
                } else {
                    $chapter_lead_name = get_user_by('id', $chapter_lead[0])->display_name;
                }
                $single_chapter = [];
                $single_chapter['name'] = $post->post_title;
                $single_chapter['link'] = get_permalink( $post->ID );
                $single_chapter['date'] = $post->cc_chapters_date;
                $single_chapter['email'] = $post->cc_chapters_email;
                $single_chapter['chapter_lead'] = $chapter_lead_name;
                $single_chapter['member_gnc'] = get_user_by('id', $post->cc_chapters_member_gnc)->display_name;
                $single_chapter['country_code'] = $post->cc_chapters_chapter_country;
                $single_chapter['url'] = $post->cc_chapters_url;
                $single_chapter['meeting_url'] = $post->cc_chapters_meeting_url;

                $results[] = $single_chapter;
            }
        }
        return wp_send_json($results);
        wp_die();
    }
    public static function get_chapters($status='active', $rand = false, $size = -1) {
        $params = array(
            'post_type' => 'cc_chapters',
            'post_status' => 'publish',
            'posts_per_page' => $size,
            'meta_key' => 'cc_chapters_chapter_status',
            'meta_value' => $status
        );
        if ( $rand ) {
            $params['orderby'] = 'rand';
        }
        $query = new WP_Query($params);
        if ($query->have_posts()) {
            return $query->posts;
        } else {
            return null;
        }

    }
    public static function stats() {
        //delete_transient('ccgn_global_stats');
        if (false === ($stats = get_transient('ccgn_global_stats'))) {
            $chapters = new WP_Query(array(
                'post_type' => 'cc_chapters',
                'post_status' => 'publish',
                'posts_per_page' => -1
            ));
            if ($chapters->have_posts()) {
                foreach ($chapters->posts as $chapter) {
                    switch ($chapter->cc_chapters_chapter_status) {
                        case 'active':
                            $stats['active_chapters']++;
                        break;
                        case 'in-progress':
                            $stats['in-progress_chapters']++;
                        break;
                        case 'inactive':
                            $stats['inactive_chapters']++;
                        break;
                    }
                }
            }
            $members_query = new WP_User_Query(array(
                'meta_key' => 'ccgn-application-state',
                'meta_value' => 'accepted'
            ));
            $members_result = $members_query->get_results();
            $stats['total_members'] = count($members_result);

            // SET THE TRANSIENT FOR 12 HRS
            set_transient('ccgn_global_stats', $stats, 12 * 60 * 60);
        }
        return $stats;
    }
    public static function reps() {
        delete_transient('ccgn_reps_members');
        if (false === ($members = get_transient('ccgn_reps_members'))) {
            $chapters = new WP_Query(array(
                'post_type' => 'cc_chapters',
                'post_status' => 'publish',
                'posts_per_page' => -1
            ));
            if ($chapters->have_posts()) {
                $members = [];
                foreach ($chapters->posts as $chapter) {
                    $chapter_lead = $chapter->cc_chapters_chapter_lead;
                    $gnc_rep = $chapter->cc_chapters_member_gnc;
                    $chapter_country = $chapter->cc_chapters_chapter_country;
                    $members['chapter_leads'][$chapter_lead] = $chapter_country;
                    $members['gnc_members'][$gnc_rep] = $chapter_country;
                }
            // SET THE TRANSIENT FOR 12 HRS
            set_transient('ccgn_reps_members', $members, 12 * 60 * 60);
            }
        }
        return $members;
    }
    static function is_excom_member( $user_id ) {
        global $_set;
		$settings = $_set->settings;
        for ($i = 1; $i <= 8; $i++) {
            if ($settings['excom_member'.$i] == $user_id) {
                return true;
            }
        }
        return false;
    }
}
//add_action("wp_ajax_event-chapters__get_countries", Commoners::get_chapters_by_status());
add_action('wp_ajax_event-get-chapters',array('Commoners','get_chapters_by_status'));
add_action('wp_ajax_nopriv_event-get-chapters', array('Commoners', 'get_chapters_by_status'));