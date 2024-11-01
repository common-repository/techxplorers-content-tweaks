<?php
/**
 * Class containing functionality to add custom fields to media.
 *
 * @link              https://techxplorer.com
 * @since             1.0.0
 * @package           Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/includes
 */

/**
 * Add author name, author url and source url media fields.
 *
 * @link       https://techxplorer.com
 * @since      1.0.0
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/includes
 */
class Txp_Media_Fields {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name  The name of this plugin.
	 * @param    string $version      The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		// Basic identifying information.
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Add our custom fields to the array of fields.
	 *
	 * @since 1.0.0
	 *
	 * @param array   $form_fields An array of existing form fields.
	 * @param WP_Post $post        The post object for this attachment.
	 *
	 * @return array The modified array of form fields.
	 */
	public function add_fields( array $form_fields, WP_Post $post ) {

		// Add the author name field.
		$field_value = get_post_meta( $post->ID, 'txp-author-name', true );
		$form_fields['txp-author-name'] = array(
			'value' => $field_value ? $field_value : '',
			'input' => 'text',
			'application' => 'image',
			'label' => __( 'Author Name', 'txp-content-tweaks' ),
			'helps' => __( 'The author of the image', 'txp-content-tweaks' ),
		);

		$field_value = get_post_meta( $post->ID, 'txp-author-url', true );
		$form_fields['txp-author-url'] = array(
			'value' => $field_value ? $field_value : '',
			'input' => 'text',
			'application' => 'image',
			'label' => __( 'Author URL', 'txp-content-tweaks' ),
			'helps' => __( 'The URL to the author credit page', 'txp-content-tweaks' ),
		);

		$field_value = get_post_meta( $post->ID, 'txp-source-url', true );
		$form_fields['txp-source-url'] = array(
			'value' => $field_value ? $field_value : '',
			'input' => 'url',
			'application' => 'image',
			'label' => __( 'Source URL', 'txp-content-tweaks' ),
			'helps' => __( 'The URL to the source of the image', 'txp-content-tweaks' ),
		);

		// Return the modified list of form fields.
		return $form_fields;
	}

	/**
	 * Save our custom fields to the database.
	 *
	 * @since 1.0.0
	 *
	 * @param array $post       An array of post data.
	 * @param array $attachment An array of attachment metadata.
	 *
	 * @return array The array of post data.
	 */
	public function save_fields( array $post, array $attachment ) {

		if ( true === isset( $attachment['txp-author-name'] ) ) {
			$this->save_field_value(
				$post['ID'],
				'txp-author-name',
				$attachment['txp-author-name']
			);
		} else {
			delete_post_meta( $post['ID'], 'txp-author-name' );
		}

		if ( true === isset( $attachment['txp-author-url'] ) ) {
			$this->save_field_value(
				$post['ID'],
				'txp-author-url',
				$attachment['txp-author-url'],
				true
			);
		} else {
			delete_post_meta( $post['ID'], 'txp-author-url' );
		}

		if ( true === isset( $attachment['txp-source-url'] ) ) {
			$this->save_field_value(
				$post['ID'],
				'txp-source-url',
				$attachment['txp-source-url'],
				true
			);
		} else {
			delete_post_meta( $post['ID'], 'txp-source-url' );
		}

		return $post;
	}

	/**
	 * Private utility function to save additional metadata
	 *
	 * @param int    $id     The id of the attachement record.
	 * @param string $name   The name of the field.
	 * @param string $value  The value of the field.
	 * @param bool   $is_url A flag to indicate the value is a URL.
	 *
	 * @return void
	 */
	private function save_field_value( $id, $name, $value, $is_url = false ) {

		if ( true === $is_url ) {
			$value = esc_url( $value );
		} else {
			$value = sanitize_text_field( $value );
		}

		update_post_meta( $id, $name, $value );
	}
}
