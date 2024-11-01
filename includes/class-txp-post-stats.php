<?php
/**
 * Class containing functionality to add statistics to posts.
 *
 * @link              https://techxplorer.com
 * @since             1.0.0
 * @package           Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/includes
 */

/**
 * Use a shortcode to display post statistics.
 *
 * @link       https://techxplorer.com
 * @since      1.0.0
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/includes
 */
class Txp_Post_Stats {

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
	 * The version number of the generated statistics
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      int       $stats_version   The current version of the stats.
	 */
	protected $stats_version = 1;

	/**
	 * The 'average' reading speed in word per minute.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      int       $avg_reading_speed   The 'average' reading speed
	 * @see      https://en.wikipedia.org/wiki/Words_per_minute
	 */
	protected $avg_reading_speed = 180;

	/**
	 * The default key prefix
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string      $key_prefix   The general prefix for all metadata keys.
	 */
	protected $key_prefix;

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

		// Generate the key prefix.
		$this->key_prefix = '_' . $this->plugin_name;
	}

	/**
	 * Undertake actions when the post is published
	 *
	 * @since 1.1.0
	 * @param int     $post_id The id number of the post.
	 * @param WP_POST $post    The post object for the post.
	 *
	 * @return void
	 */
	public function post_published( $post_id, WP_POST $post ) {

		// When a post is published, update the last modified date to the published date.
		// Especially useful when posts are published via schedule at some point in the future.
		//
		// Following coding standards are ignored.
		// WordPress.VIP.DirectDatabaseQuery.DirectQuery
		// WordPress.VIP.DirectDatabaseQuery.NoCaching
		//
		// @codingStandardsIgnoreStart
		global $wpdb;

		// Update the post modified fields to the published date.
		$data = array(
			'post_modified'     => $post->post_date,
			'post_modified_gmt' => $post->post_date_gmt,
		);

		$where = array(
			'ID' => $post->ID,
		);

		$wpdb->update( $wpdb->posts, $data, $where );
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Calculate the post statistics
	 *
	 * @since    1.0.0
	 * @param    int     $post_id The id number of the post.
	 * @param    WP_POST $post    The post object for the post.
	 * @param    boolean $return  Flag to indicate that the stats array needs to be returned.
	 *
	 * @return mixed void|array
	 */
	public function save_stats( $post_id, WP_POST $post, $return = false ) {

		$stats = $this->build_stats( strip_tags( strip_shortcodes( $post->post_content ) ) );

		// Save the metadata fields.
		$this->save_metadata(
			$post_id,
			$this->key_prefix . '-stats-version',
			$this->stats_version
		);

		$this->save_metadata(
			$post_id,
			$this->key_prefix . '-stats-created',
			$post->post_modified_gmt
		);

		$this->save_metadata(
			$post_id,
			$this->key_prefix . '-stats-data',
			$stats
		);

		if ( true === $return ) {
			return $stats;
		}
	}

	/**
	 * Build the post stats
	 *
	 * @since    1.0.0
	 * @param    string $content The post content.
	 *
	 * @return    array
	 */
	private function build_stats( $content ) {

		$stats = array();

		// Generate the word count.
		$stats['word_count'] = str_word_count( $content );

		// Generate the reading speed.
		$stats['reading_time'] = floor(
			$stats['word_count'] / $this->avg_reading_speed
		);

		return $stats;
	}

	/**
	 * Save the metadata field to the database
	 *
	 * @param  int    $post_id The unique post id.
	 * @param  string $key     The key of the metadata field.
	 * @param  mixed  $value   The value of the metadata field.
	 *
	 * @return void
	 */
	private function save_metadata( $post_id, $key, $value ) {
		if ( ! add_post_meta( $post_id, $key, $value, true ) ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

	/**
	 * Replace the shortcode with the statistics.
	 *
	 * @since 1.0.0
	 *
	 * @return string The HTML to replace the shortcode.
	 */
	public function do_shortcode() {

		// Get the details of te current post.
		$post = get_post();

		// Get the statistics.
		$stats   = get_post_meta( $post->ID, $this->key_prefix . '-stats-data', true );
		$version = get_post_meta( $post->ID, $this->key_prefix . '-stats-version', true );
		$created = get_post_meta( $post->ID, $this->key_prefix . '-stats-created', true );

		// See if the stats are available, the right version, and not outdated.
		if ( empty( $stats ) || empty( $version ) || empty( $created ) ||
			$version !== $this->stats_version ||
			$created !== $post->post_modified_gmt
		) {
			$stats = $this->save_stats( $post->ID, $post, true );
		}

		// Build the HTML for display.
		$html = '<div class="' . esc_html( $this->plugin_name ) . '-post-stats">';
		$html .= '<p><a class="' . esc_html( $this->plugin_name ) . '-post-stats-action" href="#">' . esc_html( 'Post statistics.', $this->plugin_name ) . '</a></p>';
		$html .= '<div class="' . esc_html( $this->plugin_name ) . '-post-stats-inner" style="display:none;">';
		$html .= '<ul>';

		$title = esc_html( 'Word count:', $this->plugin_name );
		// translators: placeholder is the number of words in the post.
		$data  = sprintf( _n( '%s word', '%s words', $stats['word_count'], 'txp-content-tweaks' ),  $stats['word_count'] );
		$html .= "<li> {$title} {$data}</li>";

		$title = esc_html( 'Estimated reading time:', $this->plugin_name );
		// translators: placeholder is the estimated number of minutes it takes to read the post.
		$data  = sprintf( _n( '%s minute', '%s minutes', $stats['reading_time'], 'txp-content-tweaks' ),  $stats['reading_time'] );
		$html .= "<li> {$title} {$data}</li>";

		$data = sprintf(
			esc_html( 'Last updated: %1$s', $this->plugin_name ),
			mysql2date( get_option( 'date_format' ), $post->post_modified )
		);

		$html .= "<li>{$data}</li>";

		$html .= '</ul></div></div>';

		return $html;
	}
}
