<?php 
use Queulat\Forms\Element\Div;
use Queulat\Forms\Element\Form;
use Queulat\Forms\Node_Factory;
use Queulat\Forms\Element\WP_Nonce;
use Queulat\Forms\Element\WP_Media;
use Queulat\Forms\Element\Input_Url;
use Queulat\Forms\Element\WP_Editor;
use Queulat\Forms\Element\Input_Text;
use Queulat\Forms\Element\Input;
use Queulat\Forms\Element\UI_Select2;

class ThemeSettings
{
    private $flash;
    public $settings;
    public function __construct()
    {
        $this->init();
        $this->flash = array('updated' => __('Settings saved', 'ccgn'), 'error' => __('There was a problem saving your settings', 'ccgn'));
        $this->settings = get_option('site_theme_settings');
    }
    public function init()
    {
        add_action('admin_menu', array($this, 'addAdminMenu'));
        add_action('admin_init', array($this, 'saveSettings'));
    }
    public function addAdminMenu()
    {
        add_submenu_page('index.php', _x('CCGN global settings', 'site settings title', 'ccgn'), _x('CCGN Global settings', 'site settings menu', 'ccgn'), 'edit_theme_options', 'ccgn-site-settings', array($this, 'adminMenuScreen'));
    }
    public function adminMenuScreen()
    {
        echo '<div class="wrap">';
        screen_icon('index');
        echo '<h2>' . _x('Site Settings', 'site settings title', 'ccgn') . '</h2>';
        if (!empty($_GET['msg']) && isset($this->flash[$_GET['msg']])) :
            echo '<div class="updated">';
                echo '<p>' . $this->flash[$_GET['msg']] . '</p>';
            echo '</div>';
        endif;
        $data = get_option('site_theme_settings');
        $form = Node_Factory::make(
                Form::class,
                [
                    'attributes' => [
                        'class' => 'form',
                        'id' => 'site-settings',
                        'method' => 'POST'
                    ],
                    'children' => [
                        Node_Factory::make(
                            Input_Text::class,
                            [
                                'name' => 'chapters_title',
                                'label' => 'Chapter section Title',
                                'value' => (!empty($data['chapters_title'])) ? $data['chapters_title'] : '',
                                'attributes' => [
                                    'class' => 'widefat'
                                ]
                            ]
                        ),
                        Node_Factory::make(
                            WP_Editor::class,
                            [
                                'name' => 'chapters_content',
                                'label' => 'Chapters section content',
                                'value' => (!empty($data['chapters_content'])) ? $data['chapters_content'] : '',
                                'attributes' => [
                                    'class' => 'widefat',
                                ],
                                'properties' => [
                                    'textarea_rows' => 8,
                                    'teeny' => true,
                                    'media_buttons' => false
                                ]
                            ]
                        ),
                         Node_Factory::make(
                             Div::class,
                             [
                                 'text_content' => '<h3>Excom Members</h3>'
                             ]
                         ),
                        Node_Factory::make(
                            UI_Select2::class,
                            [
                                'name' => 'excom_member1',
                                'label' => 'Excom Member 1',
                                'attributes' => [
                                    'class' => 'widefat'
                                ],
                                'properties' => [
                                    'instance' => [
                                        'multiple' => false,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => admin_url('admin-ajax.php?action=event-metabox__search-members&chapter_id=' . get_the_ID()),
                                        ]
                                    ],
                                    'description' => 'Choose the Member from the list'
                                ],
                                'options' => (function () {
                                    $data = get_option('site_theme_settings');
                                    $author_ids = $data['excom_member1'];
                                    if (!$author_ids) {
                                        return [];
                                    }
                                    $people = get_users([
                                        'meta_key' => 'ccgn-application-state',
                                        'meta_value' => 'accepted',
                                        'include' => $author_ids
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
                                'name' => 'excom_member2',
                                'label' => 'Excom Member 2',
                                'attributes' => [
                                    'class' => 'widefat'
                                ],
                                'properties' => [
                                    'instance' => [
                                        'multiple' => false,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => admin_url('admin-ajax.php?action=event-metabox__search-members&chapter_id=' . get_the_ID()),
                                        ]
                                    ],
                                    'description' => 'Choose the Member from the list'
                                ],
                                'options' => (function () {
                                    $data = get_option('site_theme_settings');
                                    $author_ids = $data['excom_member2'];
                                    if (!$author_ids) {
                                        return [];
                                    }
                                    $people = get_users([
                                        'meta_key' => 'ccgn-application-state',
                                        'meta_value' => 'accepted',
                                        'include' => $author_ids
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
                                'name' => 'excom_member3',
                                'label' => 'Excom Member 3',
                                'attributes' => [
                                    'class' => 'widefat'
                                ],
                                'properties' => [
                                    'instance' => [
                                        'multiple' => false,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => admin_url('admin-ajax.php?action=event-metabox__search-members&chapter_id=' . get_the_ID()),
                                        ]
                                    ],
                                    'description' => 'Choose the Member from the list'
                                ],
                                'options' => (function () {
                                    $data = get_option('site_theme_settings');
                                    $author_ids = $data['excom_member3'];
                                    if (!$author_ids) {
                                        return [];
                                    }
                                    $people = get_users([
                                        'meta_key' => 'ccgn-application-state',
                                        'meta_value' => 'accepted',
                                        'include' => $author_ids
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
                                'name' => 'excom_member4',
                                'label' => 'Excom Member 4',
                                'attributes' => [
                                    'class' => 'widefat'
                                ],
                                'properties' => [
                                    'instance' => [
                                        'multiple' => false,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => admin_url('admin-ajax.php?action=event-metabox__search-members&chapter_id=' . get_the_ID()),
                                        ]
                                    ],
                                    'description' => 'Choose the Member from the list'
                                ],
                                'options' => (function () {
                                    $data = get_option('site_theme_settings');
                                    $author_ids = $data['excom_member4'];
                                    if (!$author_ids) {
                                        return [];
                                    }
                                    $people = get_users([
                                        'meta_key' => 'ccgn-application-state',
                                        'meta_value' => 'accepted',
                                        'include' => $author_ids
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
                                'name' => 'excom_member5',
                                'label' => 'Excom Member 5',
                                'attributes' => [
                                    'class' => 'widefat'
                                ],
                                'properties' => [
                                    'instance' => [
                                        'multiple' => false,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => admin_url('admin-ajax.php?action=event-metabox__search-members&chapter_id=' . get_the_ID()),
                                        ]
                                    ],
                                    'description' => 'Choose the Member from the list'
                                ],
                                'options' => (function () {
                                    $data = get_option('site_theme_settings');
                                    $author_ids = $data['excom_member5'];
                                    if (!$author_ids) {
                                        return [];
                                    }
                                    $people = get_users([
                                        'meta_key' => 'ccgn-application-state',
                                        'meta_value' => 'accepted',
                                        'include' => $author_ids
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
                                'name' => 'excom_member6',
                                'label' => 'Excom Member 6',
                                'attributes' => [
                                    'class' => 'widefat'
                                ],
                                'properties' => [
                                    'instance' => [
                                        'multiple' => false,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => admin_url('admin-ajax.php?action=event-metabox__search-members&chapter_id=' . get_the_ID()),
                                        ]
                                    ],
                                    'description' => 'Choose the Member from the list'
                                ],
                                'options' => (function () {
                                    $data = get_option('site_theme_settings');
                                    $author_ids = $data['excom_member6'];
                                    if (!$author_ids) {
                                        return [];
                                    }
                                    $people = get_users([
                                        'meta_key' => 'ccgn-application-state',
                                        'meta_value' => 'accepted',
                                        'include' => $author_ids
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
                                'name' => 'excom_member7',
                                'label' => 'Excom Member 7',
                                'attributes' => [
                                    'class' => 'widefat'
                                ],
                                'properties' => [
                                    'instance' => [
                                        'multiple' => false,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => admin_url('admin-ajax.php?action=event-metabox__search-members&chapter_id=' . get_the_ID()),
                                        ]
                                    ],
                                    'description' => 'Choose the Member from the list'
                                ],
                                'options' => (function () {
                                    $data = get_option('site_theme_settings');
                                    $author_ids = $data['excom_member7'];
                                    if (!$author_ids) {
                                        return [];
                                    }
                                    $people = get_users([
                                        'meta_key' => 'ccgn-application-state',
                                        'meta_value' => 'accepted',
                                        'include' => $author_ids
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
                                'name' => 'excom_member8',
                                'label' => 'Excom Member 8',
                                'attributes' => [
                                    'class' => 'widefat'
                                ],
                                'properties' => [
                                    'instance' => [
                                        'multiple' => false,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => admin_url('admin-ajax.php?action=event-metabox__search-members&chapter_id=' . get_the_ID()),
                                        ]
                                    ],
                                    'description' => 'Choose the Member from the list'
                                ],
                                'options' => (function () {
                                    $data = get_option('site_theme_settings');
                                    $author_ids = $data['excom_member8'];
                                    if (!$author_ids) {
                                        return [];
                                    }
                                    $people = get_users([
                                        'meta_key' => 'ccgn-application-state',
                                        'meta_value' => 'accepted',
                                        'include' => $author_ids
                                    ]);
                                    if (!empty($people)) {
                                        return wp_list_pluck($people, 'display_name', 'ID');
                                    }
                                    return [];
                                })()
                            ]
                        ),
                        Node_Factory::make(
                            WP_Nonce::class,
                            [
                                'properties' => [
                                    'name' => '_site_settings_nonce',
                                    'action' => 'update_site_settings'
                                ]
                            ]
                        ),
                        Node_Factory::make(
                            Input::class,
                            [
                                'value' => 'Submit',
                                'attributes' => [
                                    'type' => 'submit',
                                    'class' => 'button button-primary button-large'
                                ],
                            ]
                        ),
                        Node_Factory::make(
                            Input::class,
                            [
                                'name' => 'action',
                                'value' => 'update_site_settings',
                                'attributes' => [
                                    'type' => 'hidden'
                                ],
                            ]
                        )
                    ]
                ]  
            );
            echo $form;
    }
    public function saveSettings()
    {
        // echo '<pre>'; print_r($_POST); echo '</pre>';
        // die();
        if (empty($_POST['action'])) return;
        if ($_POST['action'] !== 'update_site_settings') return;
        if (!wp_verify_nonce($_POST['_site_settings_nonce'], 'update_site_settings')) wp_die(_x("You are not supposed to do that", 'site settings error', 'ccgn'));
        if (!current_user_can('edit_theme_options')) wp_die(_x("You are not allowed to edit this options", 'site settings error', 'ccgn'));
        $fields = array(
            'chapters_title', 
            'chapters_content',
            'excom_member1',
            'excom_member2',
            'excom_member3',
            'excom_member4',
            'excom_member5',
            'excom_member6',
            'excom_member7',
            'excom_member8',
        );
        $raw_post = stripslashes_deep($_POST);
        $data = array_intersect_key($raw_post, array_combine($fields, $fields));
        update_option('site_theme_settings', $data);
        wp_redirect(admin_url('admin.php?page=ccgn-site-settings&msg=updated', 303));
        exit;
    }
}
$_set = new ThemeSettings;

