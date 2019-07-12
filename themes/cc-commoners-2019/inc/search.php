<?php 

define('XPROFILE_LANGUAGES',19);
define('XPROFILE_LOCATION',20);
define('BP_MEMBER_TYPE_INDIVIDUAL_TERM', 20);
define('BP_MEMBER_TYPE_INSTITUTIONAL_TERM', 22);

/**
*	Members filter class 
 *   @author @hugosolar
*/
class members_search {
    public $query;
    private $default_role = 'subscriber';
    private $search_text;
    private $page;
    private $languages;
    private $country;
    private $total_per_page = 12;
    private $application_type;
    public function set_default_role( $role ) {
        $this->default_role = $role;
    }
    private function get_default_role() {
        return $this->default_role;
    }
    public function set_application_type( $application_type ) {
        $this->application_type = $application_type;
    }
    public function set_search_text( $search ) {
        $this->search_text = $search;
    }
    public function set_country( $country ) {
        $this->country = $country;
    }
    public function set_languages( $languages ) {
        $this->languages = $languages;
    }
    function set_page( $page ) {
		$this->page = $page;
	}
    public function get_total_per_page() {
        return $this->total_per_page;
    }

    static function translate_member_type( $member_type ) {
        switch ($member_type) {
            case 'individual':
                return BP_MEMBER_TYPE_INDIVIDUAL_TERM;
                break;
            case 'institutional':
                return BP_MEMBER_TYPE_INSTITUTIONAL_TERM;
                break;
        }
    }
    function filter_user_query( $user_query ) {
        if ( is_page('members') ) {
            global $wpdb;
            if ( !empty( $_GET['application_type'] ) ) {
                $application_type = esc_attr( $_GET[ 'application_type' ] );
                $user_query->query_from .= " INNER JOIN $wpdb->term_relationships ON ( $wpdb->users.ID = $wpdb->term_relationships.object_id )";
                $user_query->query_where .= ' AND '.$wpdb->term_relationships.'.term_taxonomy_id='.self::translate_member_type( $application_type );
            }
            if ( !empty( $_GET['country'] ) || !empty( $_GET['language'] ) ) {
                $user_query->query_from .= " INNER JOIN {$wpdb->prefix}bp_xprofile_data ON ( $wpdb->users.ID = {$wpdb->prefix}bp_xprofile_data.user_id )";
                if ( !empty( $_GET['country'] ) ) {
                    $user_query->query_where .= ' AND '.$wpdb->prefix.'bp_xprofile_data.field_id='.XPROFILE_LOCATION.' AND '.$wpdb->prefix.'bp_xprofile_data.value="'.esc_attr( $_GET['country'] ).'" ';
                }
                if ( !empty( $_GET['language'] ) ) {
                    $user_query->query_where .= ' AND '.$wpdb->prefix.'bp_xprofile_data.field_id='.XPROFILE_LANGUAGES.' AND '.$wpdb->prefix.'bp_xprofile_data.value LIKE "%'.esc_attr($_GET['language']).'%" ';
                }
            }
        }
    }
    function get_default_args() {
		$meta = get_queried_object();
        $default =  array(
			'subscriber' => $this->get_default_role(),
            'orderby' => 'display_name',
            'order' => 'ASC',
            'number' => $this->total_per_page,
            'meta_query' => array(
                array(
                    'key' => 'ccgn-application-state',
                    'value' => 'accepted'
                )
            )
		);
        if ( !empty( $this->search_text ) ) {
            $words = str_word_count( $this->search_text );
            if ($words > 2) {
			    $default['search'] = $this->search_text;
            } else {
                $default['search'] = $this->search_text.'*';
            }
		}
		if ( !empty( $this->page ) ) {
			$default['paged'] = $this->page;
		}
        return $default;
    }
    function search($args = null) {
		$default = wp_parse_args($this->get_default_args(),$args=false);
		$this->query = new WP_User_Query($default);
		return $this->query;
	}
}
add_action( 'pre_user_query', array( 'members_search', 'filter_user_query' ), 10, 1);
/**
*	Search filter class
 *   @author @hugosolar
*/

class search_filter {
	public $query;
	private $post_type;
	private $taxonomies;
	private $meta;
	private $date;
	private $event_date;
	private $posts_per_page;
	private $page;
	private $search_text;
	private $taxonomy_name;
	private $tax_array;

	function __construct() {
		//$this->search($args);
	}

	function get_current_taxonomy() {
		$meta = get_queried_object();
		if ( empty($this->taxonomy_name) ) {
			$tax = $meta->taxonomy;
		} else {
			$tax = $this->taxonomy_name;
		}
		return $tax;
	}
	function set_taxonomy_name($tax) {
		$this->taxonomy_name = $tax;
	}
	function default_post_type() {
		return 'post';
	}
	function set_search_text($text) {
		$this->search_text = $text;
	}
	function get_search_text() {
		return $this->search_text;
	}
	function set_page($page) {
		$this->page = $page;
	}
	function get_page() {
		if ( !empty( $this->page ) ) {
			return $this->page;
		} 
	}
	function get_query() {
		return $this->query;
	}
	function set_taxonomies($taxonomies) {
		$this->taxonomies = $taxonomies;
	}
	function set_array_taxonomies($taxonomies) {
		$this->tax_array = $taxonomies;
	}
	function get_taxonomies() {
		return $this->taxonomies;
	}
	function set_post_type($post_type) {
		$this->post_type = $post_type;
	}
	function get_date() {
		return $this->date;
	}
	function set_date($date_array) {
		$this->date = $date_array;
	}
	function set_event_date($date_array)
	{
		$this->event_date = $date_array;
	}
	function get_post_type() {
		if ( !empty( $this->post_type ) ) {
			return $this->post_type;
		} else {
			return $this->default_post_type();
		}
	}
	function get_default_args() {
		$meta = get_queried_object();

		$default =  array(
			'post_type' => $this->get_post_type(),
			'posts_per_page' => get_option('posts_per_page'),
			'posts_status' => 'publish',
			);
		if (!empty($this->date)) {
			 $default['date_query'] = array($this->date);
		}
		if (!empty($this->search_text)) {
			$default['s'] = $this->search_text;
		}
		if (!empty($this->page)) {
			$default['paged'] = $this->page;
		}
		if ( ( get_class($meta) == 'WP_Term') && ( empty( $this->taxonomies ) ) ) {
			$default['tax_query'] = array(
				array(
					'taxonomy' => $meta->taxonomy,
					'field' => 'slug',
					'terms' => $meta->slug
					)
				);
		} else if ( !empty($this->taxonomies[0]) ) {
			$taxonomies = array(); 
			foreach ($this->taxonomies as $tax) {
				$taxonomies[] = array(
					'taxonomy' => $this->get_current_taxonomy(),
					'field' => 'slug',
					'terms' => $tax
					);
			}
			$default['tax_query'] = $taxonomies; 
		}
		if (!empty($this->tax_array) && is_array($this->tax_array)) {
			foreach ($this->tax_array as $tax => $term) {
				$taxonomies[] = array(
					'taxonomy' => $tax,
					'field' => 'slug',
					'terms' => $term
				);
			}
			$default['tax_query'] = $taxonomies;
		}
		if ( !empty( $this->meta ) ) {
			$default['meta_query'] = $this->meta;
		}
		if ( !empty( $this->event_date ) ) {
			$default['meta_query'] = array();
			$date = $this->event_date;
			if (!empty($date['year']) || !empty($date['month'])) {
				$month = (!empty($date['month'])) ? $date['month'] : '01';
				$year = (!empty($date['year'])) ? $date['year'] : date('Y');
				$default['meta_query'][] = array(
					'key' => 'event_date',
					'value' => $year.'-'.$month.'-01',
					'compare' => '>=',
					'type' => 'DATE'
				);
				$month = (!empty($date['month'])) ? $date['month'] : '12';
				$default['meta_query'][] = array(
					'key' => 'event_date',
					'value' => $year . '-'.$month.'-31',
					'compare' => '<=',
					'type' => 'DATE'
				);
			}
			$default['meta_key'] = 'event_date';
			$default['orderby'] = 'meta_value';
			$default['order'] = 'DESC';
		}
		//echo '<pre>'; print_r($default); echo '</pre>';
		return $default;
	}
	function search($args = null) {
		$default = wp_parse_args($this->get_default_args(),$args=false);
		$this->query = new WP_Query($default);
		return $this->query;
	}
	function get_years($from='2012') {
		$years = array();
		for ( $x = $from; $x <= date(Y); $x++) {
			$years[] = $x;
		}
		return $years;
	}
	function get_years_select($attributes=null, $selected=null) {
		if ( !empty($attributes ) && is_array( $attributes ) ) {
			$print_attr = '';
			foreach ($attributes as $key => $val) {
				$print_attr .= ' '.$key.'="'.$val.'" ';
			}
		}
		$years = $this->get_years();
		$out = '<select '.$print_attr.' >';
			$out .= '<option value="">Year</option>';
		foreach ( $years as $year ) {
			$select = ($year == $selected ) ? ' selected="selected"': '';
			$out .= '<option value="'.$year.'"'.$select.'>'.$year.'</option>';
		}
		$out .='</select>';

		return $out;
	}
	function get_months() {
		return array(
			1 => 'Jan',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Apr',
			5 => 'May',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Aug',
			9 => 'Sept',
			10 => 'Oct',
			11 => 'Nov',
			12 => 'Dec'
			);
	}
	function get_months_select($attributes=null, $selected=null) {
		if ( !empty($attributes ) && is_array( $attributes ) ) {
			$print_attr = '';
			foreach ($attributes as $key => $val) {
				$print_attr .= ' '.$key.'="'.$val.'" ';
			}
		}
		$months = $this->get_months();
		$out = '<select '.$print_attr.' >';
			$out .= '<option value="">Month</option>';
		foreach ( $months as $key => $month ) {
			$select = ($key == $selected ) ? ' selected="selected"': '';
			$out .= '<option value="'.$key.'"'.$select.'>'.$month.'</option>';
		}
		$out .='</select>';

		return $out;
	}
}