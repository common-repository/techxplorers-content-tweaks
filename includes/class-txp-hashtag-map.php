<?php
/**
 * Class containing functionality to map WordPress tags to hashtags.
 *
 * @link              https://techxplorer.com
 * @since             1.0.0
 * @package           Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/includes
 */

/**
 * The hashtag component of the plugin.
 *
 * @link       https://techxplorer.com
 * @since      1.0.0
 *
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/includes
 */
class Txp_Hashtag_Map {

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
	 * Flag to indicate that missing tags should be automatically created.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      boolean    $auto_create    Flag to indicate that missing tags should be created.
	 */

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name  The name of this plugin.
	 * @param    string $version      The version of this plugin.
	 * @param    array  $options      An array of options.
	 */
	public function __construct( $plugin_name, $version, array $options ) {

		// Basic identifying information.
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->options     = $options;
	}

	/**
	 * Map the tags to those found in the post content on post save
	 *
	 * @param int     $post_id The id number of the post.
	 * @param WP_POST $post    The post object for the post.
	 *
	 * @return void
	 */
	public function map_tags( $post_id, WP_POST $post ) {

		// Get the filtered title and content from the post.
		$title = apply_filters( 'the_title', $post->post_title );
		$content = apply_filters( 'the_content', $post->post_content );

		// Require the utility class.
		require_once __DIR__ . '/class-txp-utils-tags.php';

		// Get all of the hash tags, without the hash.
		$hash_tags = array_merge(
			Txp_Utils_Tags::find_hashtags( $title, true ),
			Txp_Utils_Tags::find_hashtags( $content, true )
		);

		// Limit to existing tags?
		if ( 0 === $this->options['createtags'] ) {
			// Yes.
			$new_tags = array();

			foreach ( $hash_tags as $tag ) {
				// Ignore the code style violoation as wpcom_vip_term_exists() isn't available everywhere.
				// @codingStandardsIgnoreStart.
				$term = term_exists( $tag, 'post_tag' );
				// @codingStandardsIgnoreEnd

				if ( 0 !== $term && null !== $term ) {
					$new_tags[] = $tag;
				}
			}

			$hash_tags = $new_tags;
		}

		// Add any missing tags.
		if ( false === empty( $hash_tags ) ) {
			wp_set_post_tags( $post_id, $hash_tags, true );
		}
	}
}
