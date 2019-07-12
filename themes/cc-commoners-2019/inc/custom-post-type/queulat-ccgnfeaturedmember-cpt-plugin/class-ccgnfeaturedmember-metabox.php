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

class FeaturedMembers_Metabox extends Metabox {
    public function get_fields() : array
    {
        return [
            Node_Factory::make(
                UI_Select2::class,
                [
                    'name' => 'featured_member',
                    'label' => 'Featured Member',
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
                        'description' => 'Choose the Featured member from the list'
                    ],
                    'options' => (function () {
                        $author_ids = get_post_meta(get_the_ID(), 'featuredmember_featured_member', false);
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
				WP_Editor::class,
				[
					'name' => 'abstract',
					'label' => 'Abstract',
					'attributes' => [
						'class' => 'widefat',
					],
					'properties' => [
						'textarea_rows'  => 8,
						'teeny' => true,
						'media_buttons' => false,
						'description' => 'Member statement',
					]
				]
			),

         ];
    }
    public function sanitize_data(array $data) : array
    {
        $sanitized = [];
        foreach ($data as $key => $val) {
            switch ($key) {
                case 'featured_member':
                    $sanitized[$key] = $val;
                    break;
                case 'abstract':
                    $sanitized[$key] = $val;
                    break;
            }
        }
        return $sanitized;
    }
}

new FeaturedMembers_Metabox('featuredmember', 'Featured Member information', 'ccgnfeaturedmember', ['context' => 'normal']);