<?php
use Queulat\Metabox;
use Queulat\Forms\Node_Factory;
use Queulat\Forms\Element\WP_Editor;
use Queulat\Forms\Element\Input;
use Queulat\Forms\Element\WP_Image;
use Queulat\Forms\Element\WP_Media;
use Queulat\Forms\Element\Select;


class Feature_Metabox extends Metabox
{
    public function __construct($id = '', $title = '', $post_type = '', array $args = array()) {
        parent::__construct($id, $title, $post_type, $args);
    }
    public function get_site_pages() {
        $return = array(
            '' => 'Select page'
            );
        $pages = get_pages();
        foreach ($pages as $page) {
            $return[$page->ID] = $page->post_title;
        }
        return $return;
    }
    public function get_site_categories() {
        $return = array(
            '' => 'Select category'
            );
        $categories = get_terms('sotc_year');
        foreach ($categories as $category) {
            $return[$category->term_id] = $category->name;
        }
        return $return;
    }
    public function get_fields() : array
    {
         wp_enqueue_media();
        return [
            
            Node_Factory::make(
                Input::class,
                [
                    'name' => 'subtitle',
                    'label' => 'Feature Subtitle',
                    'attributes' => [
                        'class' => 'widefat'
                    ],
                    'properties' => [
                        'description' => 'Text below the year'
                    ]
                ]
            ),
            Node_Factory::make(
                WP_Image::class,
                [
                    'name' => 'background',
                    'label' => 'Background image',
                    'properties' => [
                        'description' => 'Size recommended 2000x700 px'
                    ]
                ]
            ),
            Node_Factory::make(
                WP_Media::class,
                [
                    'name' => 'background_video',
                    'label' => 'Background Video',
                    'properties' => [
                        'description' => 'Extended recommended : .webm '
                    ]
                ]
            ),
            Node_Factory::make(
                Input::class,
                [
                    'name' => 'url',
                    'label' => 'button Url',
                    'attributes' => [
                        'class' => 'widefat'
                    ],
                    'properties' => [
                        'description' => 'If the url is not provided, the button will not be shown'
                    ]
                ]
            ),
            Node_Factory::make(
                Input::class,
                [
                    'name' => 'button_text',
                    'label' => 'Button Text',
                    'attributes' => [
                        'class' => 'widefat'
                    ],
                    'properties' => [
                        'description' => 'Button text (default: "Learn more")'
                    ]
                ]
            )
            
        ];
    }
    public function sanitize_data(array $data) : array
    {
        //Delete stats transient to update the global chapter stats
        $sanitized = [];
        foreach ($data as $key => $val) {
            switch ($key) {
                case 'subtitle':
                    $sanitized[$key] = $val;
                    break;
                case 'background':
                    $sanitized[$key] = $val;
                    break;
                case 'background_video':
                    $sanitized[$key] = $val;
                    break;
                case 'url':
                    $sanitized[$key] = $val;
                    break;
                case 'button_text':
                    $sanitized[$key] = $val;
                    break;
            }
        }
        return $sanitized;
    }
}

new Feature_Metabox('feature', 'Feature data', 'ccgnfeature', ['context' => 'normal']);