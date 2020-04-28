<?php
use Queulat\Metabox;
use Queulat\Forms\Node_Factory;
use Queulat\Forms\Element\WP_Media;
use Queulat\Forms\Element\Input_Url;
use Queulat\Forms\Element\WP_Editor;
use Queulat\Forms\Element\Input_Text;
use Queulat\Forms\Element\Input;
use Queulat\Forms\Element\Select;
use Queulat\Forms\Element\UI_Select2;
use Queulat\Forms\Element\Input_Number;
use Queulat\Forms\Element\Input_Email;


class Chapters_Metabox extends Metabox
{
    public function __construct($id = '', $title = '', $post_type = '', array $args = array()) {
        parent::__construct($id, $title, $post_type, $args);
        add_action("wp_ajax_event-metabox__search-members", [$this, 'search_members']);
        add_action("wp_ajax_event-metabox__get_countries", [$this, 'get_countries']);
    }
    public function get_countries() {
        $search = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
        $country_id = (array)get_post_meta(get_the_ID(), 'cc_chapter_chapter_country', false);
        $countries = $countries = GF_Field_Address::get_countries();
        $results = [];
        foreach ($countries as $key=>$country) {
            if (!empty($search)) {
                if (stripos($country, $search) !== false) {
                    $results[] = [
                        'id' => $key,
                        'text' => $country,
                        'selected' => in_array($key, $country_id, true)
                    ];
                }
            } else {
                $results[] = [
                    'id' => $key,
                    'text' => $country,
                    'selected' => in_array($key, $country_id, true)
                ];
            }
        }
        return wp_send_json([
            'results' => $results
        ]);
    }
    public function search_members()
    {
        $search = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
        $existing = (array)get_post_meta(filter_input(INPUT_GET, 'chapter_id', FILTER_SANITIZE_NUMBER_INT), 'cc_chapters_chapter_lead', false);
        //$existing = array_map('absint', $existing);
        $members_query = new WP_User_Query(array(
            'search' => $search.'*',
            'meta_key' => 'ccgn-application-state',
            'meta_value' => 'accepted'
        ));
        $people = $members_query->get_results();
        $results = [];
        if (!empty($people)) {
            foreach ($people as $person) {
                $results[] = [
                    'id' => $person->ID,
                    'text' => $person->data->display_name,
                    'selected' => in_array($person->ID, $existing, true)
                ];
            }
        }
        return wp_send_json([
            'results' => $results
        ]);
    }
    public function get_fields() : array
    {
        return [
            Node_Factory::make(
                Select::class,
                [
                    'name' => 'chapter_status',
                    'label' => 'Chapter Status',
                    'attributes' => [
                        'class' => 'widefat'
                    ],
                    'properties' => [
                        'description' => 'Choose the Status of the current chapter',
                    ],
                    'options' => (function () {
                        $status = array(
                            '' => 'Choose',
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'in-progress' => 'In Progress'
                        );
                            return $status;
                    })()
                ]
            ),
            Node_Factory::make(
                Input::class,
                [
                    'name' => 'date',
                    'label' => 'Date founded',
                    'attributes' => [
                        'type' => 'date'
                    ],
                    'properties' => [
                        'description' => 'Date when chapter was founded'
                    ]
                ]
            ),
            Node_Factory::make(
                Input_Email::class,
                [
                    'name' => 'email',
                    'label' => 'Email',
                    'properties' => [
                        'description' => 'Chapter contact email'
                    ]
                ]
            ),
            Node_Factory::make(
                UI_Select2::class,
                [
                    'name' => 'chapter_country',
                    'label' => 'Chapter Country',
                    'attributes' => [
                        'class' => 'widefat'
                    ],
                    'properties' => [
                        'instance' => [
                            'width' => '100%',
                            'multiple' => false,
                            'minimumInputLength' => 3
                            // 'ajax' => [
                            //     'url' => admin_url('admin-ajax.php?action=event-metabox__get_countries'),
                            // ]
                        ],
                        'description' => 'Choose the Chapter Country',
                    ],
                    'options' => (function () {
                        // $country_id = (array)get_post_meta(get_the_ID(), 'cc_chapter_chapter_country', false);
                        // if (!$country_id) {
                        //     return [];
                        // }
                        $countries = GF_Field_Address::get_countries();
                        if (!empty($countries)) {
                            return $countries;
                        }
                        return [];
                    })()
                ]
            ),
            Node_Factory::make(
                UI_Select2::class,
                [
                    'name' => 'chapter_lead',
                    'label' => 'Chapter Lead',
                    'attributes' => [
                        'class' => 'widefat'
                    ],
                    'properties' => [
                        'instance' => [
                            'width' => '100%',
                            'multiple' => true,
                            'minimumInputLength' => 3,
                            'allowClear' => true,
                            'placeholder' => 'Select',
                            'ajax' => [
                                'url' => admin_url('admin-ajax.php?action=event-metabox__search-members&chapter_id=' . get_the_ID()),
                            ]
                        ],
                        'description' => 'Choose the Chapter Lead from the list'
                    ],
                    'options' => (function () {
                        $author_ids = get_post_meta(get_the_ID(), 'cc_chapters_chapter_lead', false);
                        if (!$author_ids) {
                            return [];
                        }
                        $people = get_users([
                            'meta_key' => 'ccgn-application-state',
                            'meta_value' => 'accepted',
                            'include' => $author_ids,
                            'search' => $search
                        ]);
                        if (!empty($people)) {
                            return wp_list_pluck($people, 'display_name', 'ID');
                        }
                        return [];
                    })()
                ]
            ),
            Node_Factory::make(
                UI_Select2::class,
                [
                    'name' => 'member_gnc',
                    'label' => 'Global Network Council representative',
                    'attributes' => [
                        'class' => 'widefat'
                    ],
                    'properties' => [
                        'instance' => [
                            'width' => '100%',
                            'multiple' => false,
                            'minimumInputLength' => 3,
                            'allowClear' => true,
                            'placeholder' => 'Select',
                            'ajax' => [
                                'url' => admin_url('admin-ajax.php?action=event-metabox__search-members&chapter_id=' . get_the_ID()),
                            ]
                        ],
                        'description' => 'Choose the Member of the representative to the network council'
                    ],
                    'options' => (function () {
                        $author_ids = (array)get_post_meta(get_the_ID(), 'cc_chapters_member_gnc', false);
                        if (!$author_ids) {
                            return [];
                        }
                        $people = get_users([
                            'meta_key' => 'ccgn-application-state',
                            'meta_value' => 'accepted',
                            'include' => $author_ids,
                            'search' => $search
                        ]);
                        if (!empty($people)) {
                            return wp_list_pluck($people, 'display_name', 'ID');
                        }
                        return [];
                    })()
                ]
            ),
            Node_Factory::make(
                Input_Url::class,
                [
                    'name' => 'url',
                    'label' => 'External URL',
                    'attributes' => [
                        'class' => 'widefat',
                        'placeholder' => 'Chapter external URL (eg. twitter, instagram)',
                        'type' => 'url'
                    ]
                ]
            ),
            Node_Factory::make(
                Input_Url::class,
                [
                    'name' => 'chapter_url',
                    'label' => 'Chapter website URL',
                    'attributes' => [
                        'class' => 'widefat',
                        'placeholder' => 'Official Chapter website URL',
                        'type' => 'url'
                    ]
                ]
            ),
            Node_Factory::make(
                Input_Url::class,
                [
                    'name' => 'meeting_url',
                    'label' => 'Meeting URL',
                    'attributes' => [
                        'class' => 'widefat',
                        'placeholder' => 'Chapter first meeting URL',
                        'type' => 'url'
                    ],
                    'properties' => [
                        'description' => 'Chapter first meeting URL'
                    ]
                ]
            ),
            Node_Factory::make(
                Input_Text::class,
                [
                    'name' => 'mailing_list',
                    'label' => 'Mailing list',
                    'attributes' => [
                        'class' => 'widefat',
                        'placeholder' => 'Chapter mailing list'
                    ]
                ]
            )
        ];
    }
    public function sanitize_data(array $data) : array
    {
        //Delete stats transient to update the global chapter stats
        delete_transient('ccgn_global_stats');
        $sanitized = [];
        foreach ($data as $key => $val) {
            switch ($key) {
                case 'date':
                    $sanitized[$key] = $val;
                    break;
                case 'email':
                    $sanitized[$key] = $val;
                    break;
                case 'chapter_country':
                    $sanitized[$key] = $val;
                case 'chapter_lead':
                    $sanitized[$key] = $val;
                case 'member_gnc':
                    $sanitized[$key] = $val;
                    break;
                case 'url':
                    $sanitized[$key] = esc_url_raw($val);
                    break;
                case 'chapter_url':
                    $sanitized[$key] = esc_url_raw($val);
                    break;
                case 'meeting_url':
                    $sanitized[$key] = esc_url_raw($val);
                    break;
                case 'chapter_status':
                    $sanitized[$key] = $val;
                break;
                case 'mailing_list':
                    $sanitized[$key] = $val;
                break;
            }
        }
        return $sanitized;
    }
}

new Chapters_Metabox('cc_chapters', 'Chapter information', 'cc_chapters', ['context' => 'normal']);