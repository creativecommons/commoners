<?php
class render {
    static function date_format($date) {
        return mysql2date('F, d. \ Y',$date);
    }
    static function member_single( $member ) {
        $out = '';
        if ( !empty($member) ) {
            $country = xprofile_get_field_data('Location', $member->ID );
            $profile_link = bp_core_get_user_domain( $member->ID );
            $out .= '<article class="cell hentry entry-single-member">';
                $out .= '<figure class="entry-image profile-image">';
                    $out .= '<a href="'.$profile_link.'">';
                        $out .= bp_core_fetch_avatar ( array( 'item_id' => $member->ID, 'type' => 'full' ) );
                    $out .= '</a>';
                $out .= '</figure>';
                $out .= '<h3 class="entry-title"><a href="'.$profile_link.'">'.$member->display_name.'</a></h3>';
                $out .= '<span class="country">'.$country.'</span>';
            $out .= '</article>';
        }
        return $out;
    }
    static function voucher($voucher) {
        $out = '';
        if ( !empty( $voucher ) ) {
            $country = xprofile_get_field_data('Location', $voucher['id'] );
            $out .= '<article class="cell hentry entry-voucher">';
                $out .= '<header class="voucher">';
                    $out .= '<figure class="entry-image">';
                        $out .= bp_core_fetch_avatar ( array( 'item_id' => $voucher['id'], 'type' => 'thumb' ) );
                    $out .= '</figure>';
                    $out .= '<h3 class="entry-title">'.$voucher['name'].'</h3>';
                    $out .= '<span class="country">'.$country.'</span>';
                $out .= '</header>';
                $out .= '<div class="entry-summary">';
                    $out .= apply_filters( 'the_content', $voucher['reason'] );
                $out .= '</div>';
			$out .= '</article>';
        }
        return $out;
    }
    static function chapter_member($member_id,$position) {
        $out = '';
        if ( !empty( $member_id ) ) {
            $member = get_user_by('ID', $member_id);
            $profile_link = bp_core_get_user_domain( $member_id );
            $out .= '<article class="cell hentry entry-voucher entry-chapter-member">';
                $out .= '<header class="voucher">';
                    $out .= '<figure class="entry-image">';
                        $out .= bp_core_fetch_avatar ( array( 'item_id' => $member_id, 'type' => 'full' ) );
                    $out .= '</figure>';
                    $out .= '<h3 class="entry-title">'.$member->display_name.'</h3>';
                    $out .= '<span class="country">'.$position.'</span>';
                $out .= '</header>';
                $out .= '<div class="entry-summary">';
                    $out .= '<a href="'.$profile_link.'" class="button secondary">View profile</a>';
                $out .= '</div>';
			$out .= '</article>';
        }
        return $out;
    }
    static function post_gallery($gallery_ids) {
        $out = '';
        if ( !empty( $gallery_ids ) ) {
            $out .= '<section class="entry-gallery">';
                foreach ( $gallery_ids as $image ) {
                    $out .= '<figure class="image-item">';
                        $out .= wp_get_attachment_image( $image , 'landscape-medium' );
                    $out .= '</figure>';
                }
            $out .= '</section>';
        }
        return $out;
    }
    static function tiny_news($post) {
        $out = '';
        $out .= '<article class="hentry entry-mini-news">';
            $out .= '<h5 class="entry-title"><a href="'.get_permalink($post->ID).'">'.get_the_title($post->ID).'</a></h5>';
            $out .= '<div class="entry-meta">';
                $out .= '<span class="categories">'.get_the_category_list(', ','',$post->ID).'</span>';
                $out .= '<span class="date">'.self::date_format($post->post_date).'</span>';
            $out .= '</div>';
        $out .= '</article>';
        return $out;
    }
    static function archive_news($post) {
        $out = '';
        $out .= '<article class="hentry entry-archivenews">';
            $out .= '<div class="grid-x grid-padding-x">';
                $out .= '<div class="cell large-3 medium-3">';
                    $out .= '<figure class="entry-image">';
                        $out .= '<a href="'.get_permalink($post->ID).'">'.get_the_post_thumbnail($post->ID,'squared').'</a>';
                    $out .= '</figure>';
                $out .= '</div>';
                $out .= '<div class="cell large-9 medium-9">';
                    $out .= '<span class="subtitle">'.get_the_category_list( ', ', '', $post->ID ).'</span>';
                    $out .= '<h3 class="entry-title"><a href="'.get_permalink($post->ID).'">'.get_the_title($post->ID).'</a></h3>';
                    $out .= '<span class="date">'.self::date_format( $post->post_date ).'</span>';
                    $out .= '<div class="entry-summary">'.do_excerpt($post).'</div>';
                    $out .= '<div class="text-right">';
                        $out .= '<a href="'.get_permalink($post->ID).'" class="view-more">View more <i class="ion-arrow-right-c"></i></a>';
                    $out .= '</div>';
                $out .= '</div>';
            $out .= '</div>';
        $out .= '</article>';
        return $out;
    }
    static function big_news($post) {
        $out = '';
        $out .= '<article class="hentry entry-bignews">';
            $out .= '<div class="grid-x grid-padding-x">';
                $out .= '<div class="cell large-auto medium-auto small-12">';
                    $out .= '<figure class="entry-image">';
                        $out .= '<a href="'.get_permalink($post->ID).'">'.get_the_post_thumbnail($post->ID,'squared').'</a>';
                    $out .= '</figure>';
                $out .= '</div>';
                $out .= '<div class="cell large-auto medium-auto small-12">';
                    $out .= '<span class="subtitle">'.get_the_category_list( ', ', '', $post->ID ).'</span>';
                    $out .= '<h3 class="entry-title"><a href="'.get_permalink($post->ID).'">'.get_the_title($post->ID).'</a></h3>';
                    $out .= '<span class="date">'.self::date_format($post->post_date).'</span>';
                    $out .= '<div class="entry-summary">'.do_excerpt($post).'</div>';
                    $out .= '<div class="text-right">';
                        $out .= '<a href="'.get_permalink($post->ID).'" class="view-more">View more <i class="ion-arrow-right-c"></i></a>';
                    $out .= '</div>';
                $out .= '</div>';
            $out .= '</div>';
        $out .= '</article>';
        return $out;
    }
    static function person($post) {
        $out = '';
        $user = get_user_by('ID', $post->featuredmember_featured_member);
        $country = $country = xprofile_get_field_data('Location', $post->featuredmember_featured_member );
        $member_id = $post->featuredmember_featured_member;
        $bp_member_img = bp_core_fetch_avatar ( array( 'item_id' => $member_id, 'type' => 'full', 'width' => 300, 'height' => 300 ) );
        $member_image = ( !empty( $bp_member_img ) ) ? $bp_member_img : get_the_post_thumbnail($post->ID, 'squared');
        $out .= '<article class="entry-person">';
            $out .= '<a href="'.get_permalink($post->ID).'">';
                $out .= '<span class="entry-image">';
                    $out .= $member_image;
                $out .= '</span>';
                $out .= '<h4 class="entry-title">'.$user->display_name.'</h4>';
                $out .= '<span class="location">'.$country.'</span>';
            $out .= '</a>';
        $out .= '</article>';
        return $out;
    }
    static function home_feature($post) {
        $out = '';
        $button_text = ( !empty( $post->feature_button_text ) ) ? $post->feature_button_text : 'Learn more';
        $out .= '<section class="feature hentry entry-feature thin">';
            if ( !empty( $post->feature_background ) ) {
                $out .= wp_get_attachment_image($post->feature_background, 'landscape-feature');
            }
            $out .= '<div class="content-wrap">';
                $out .= '<div class="feature-content">';
                    if ( !empty( $post->feature_subtitle ) ) {
                        $out .= '<span class="subtitle">' .esc_attr($post->feature_subtitle). '</span>';
                    }
                    $out .= '<h2 class="entry-title">' .get_the_title($post->ID). '</h2>';
                    $out .= '<div class="action">';
                        if ( !empty( $post->feature_url ) ) {
                            $out .= '<a href="'.esc_url( $post->feature_url ).'" class="button primary">' .$button_text. '</a>';
                        }
                    $out .= '</div>';
                $out .= '</div>';
            $out .= '</div>';
        $out .= '</section>';
        return $out;
    }
}