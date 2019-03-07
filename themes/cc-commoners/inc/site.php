<?php

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
                $single_chapter = [];
                $single_chapter['name'] = $post->post_title;
                $single_chapter['date'] = $post->cc_chapters_date;
                $single_chapter['email'] = $post->cc_chapters_email;
                $single_chapter['chapter_lead'] = get_user_by('id', $post->cc_chapters_chapter_lead)->display_name;
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
    public static function get_chapters($status='active') {
        $params = array(
            'post_type' => 'cc_chapters',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'cc_chapters_chapter_status',
            'meta_value' => $status
        );
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
}
//add_action("wp_ajax_event-chapters__get_countries", Commoners::get_chapters_by_status());
add_action('wp_ajax_event-get-chapters',array('Commoners','get_chapters_by_status'));
add_action('wp_ajax_nopriv_event-get-chapters', array('Commoners', 'get_chapters_by_status'));