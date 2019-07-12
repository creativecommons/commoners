<?php

use Queulat\Metabox;
use Queulat\Forms\Node_Factory;
use Queulat\Forms\Element\Input_Url;
use Queulat\Forms\Element\WP_Editor;
use Queulat\Forms\Element\Input_Text;


class Event_Metabox extends Metabox
{
    public function __construct($id = '', $title = '', $post_type = '', array $args = array())
    {
        parent::__construct($id, $title, $post_type, $args);
        add_action("{$this->get_id()}_metabox_data_updated", [$this, 'data_updated'], 10, 2);
    }
    public function data_updated($data, $post_id)
    {
        $dtstart = DateTime::createFromFormat('Y-m-d H:i', $data['dtstart_date'] . ' ' . $data['dtstart_time']);
        $dtend = DateTime::createFromFormat('Y-m-d H:i', $data['dtstart_date'] . ' ' . $data['dtend_time']);
        if ($dtstart) {
            update_post_meta($post_id, 'event_dtstart', $dtstart->format('Y-m-d H:i:s'));
        }
        if ($dtend) {
            update_post_meta($post_id, 'event_dtend', $dtend->format('Y-m-d H:i:s'));
        }
        return true;
    }
    public function get_fields(): array
    {
        return [
            Node_Factory::make(
                Input_Text::class,
                [
                    'name' => 'location',
                    'label' => 'Location',
                    'attributes' => [
                        'class'    => 'regular-text',
                        'required' => 'required'
                    ]
                ]
            ),
            Node_Factory::make(
                Input_Text::class,
                [
                    'name' => 'dtstart_date',
                    'label' => 'Date',
                    'attributes' => [
                        'class'    => 'regular-text',
                        'required' => 'required',
                        'type'     => 'date'
                    ]
                ]
            ),
            Node_Factory::make(
                Input_Text::class,
                [
                    'name' => 'dtstart_time',
                    'label' => 'Start time',
                    'attributes' => [
                        'class'    => 'regular-text',
                        'required' => 'required',
                        'type'     => 'time'
                    ]
                ]
            ),
            Node_Factory::make(
                Input_Text::class,
                [
                    'name' => 'dtend_time',
                    'label' => 'End time',
                    'attributes' => [
                        'class'    => 'regular-text',
                        'required' => 'required',
                        'type'     => 'time'
                    ]
                ]
            ),
            Node_Factory::make(
                Input_Text::class,
                [
                    'name' => 'signups',
                    'label' => 'Signup Url',
                    'attributes' => [
                        'class' => 'regular-text'
                    ]
                ]
            ),
            Node_Factory::make(
                Input_Url::class,
                [
                    'name' => 'url',
                    'label' => 'Event Url',
                    'attributes' => [
                        'class' => 'regular-text'
                    ]
                ]
            ),
        ];
    }
    public function sanitize_data(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $val) {
            switch ($key) {
                case 'location':
                case 'signups':
                    $sanitized[$key] = sanitize_text_field($val);
                    break;
                case 'dtstart_date':
                    $dtstart = DateTime::createFromFormat('Y-m-d', $val);
                    if ($dtstart instanceof \DateTime) {
                        $sanitized[$key] = $dtstart->format('Y-m-d');
                    }
                    break;
                case 'dtstart_time':
                case 'dtend_time':
                    $time = DateTime::createFromFormat('Y-m-d H:i', date_i18n('Y-m-d') . ' ' . $val);
                    if ($time instanceof \DateTime) {
                        $sanitized[$key] = $time->format('H:i');
                    }
                    break;
                case 'url':
                case 'featured_url':
                    $sanitized[$key] = esc_url_raw($val);
                    break;
                case 'description':
                    $sanitized[$key] = wp_kses_post($val);
                    break;
            }
        }
        return $sanitized;
    }
}

new Event_Metabox('event', 'Event related data', 'ccgnevents', ['context' => 'normal']);

