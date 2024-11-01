<?php
/**
 * Class containing functionality to import Instagram media automatically.
 *
 * Based on the technique outlined at the URL below:
 * https://gist.github.com/jdembowski/645eb2d5b0f9b326a2d98ef5d43c73ac
 *
 * @link              https://techxplorer.com
 * @since             1.3.0
 * @package           Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/includes
 */

/**
 * Automatically import Instagram media and replace the iframe element.
 *
 * @link       https://techxplorer.com
 * @since      1.3.0
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/includes
 */
class Txp_Instamedia {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.3.0
	 * @param    string $plugin_name  The name of this plugin.
	 * @param    string $version      The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		// Basic identifying information.
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Undertake actions when the post is published
	 *
	 * @since 1.3.0
	 * @param int     $post_id The id number of the post.
	 * @param WP_POST $post    The post object for the post.
	 *
	 * @return void
	 */
	public function post_published( $post_id, WP_POST $post ) {

		// Prevent a recursive loop.
		static $prevent_loop = false;

		if ( true === $prevent_loop ) {
			return;
		}

		// Define some helper variables.
		$insta_media_url = false;
		$insta_page_url = false;

		// Get the content of the post.
		$content = $post->post_content;

		// Extract the URLs from the post.
		$urls = wp_extract_urls( $content );

		// Look for the URL that points to the media on Instagram.
		foreach ( $urls as $url ) {
			// Get the img src url that start with https://scontent.cdninstagram.com.
			if ( preg_match( '/^https\:\/\/scontent\.cdninstagram\.com\//' , $url, $matches ) ) {
				$insta_media_url = $url;
			}

			// Don't worry about the IFTTT shortlink. Can add this in if required later.
		}

		// Was a link to media on Instagram found?
		if ( false === $insta_media_url ) {
			// No.
			return;
		}

		// Try to import the media file.
		$media_attachment = $this->import_media( $post_id, $insta_media_url );

		// Wass the media side loaded OK?
		if ( false === $media_attachment ) {
			// No.
			return;
		}

		// Set the featured imaged to the new image in the media library.
		set_post_thumbnail( $post_id , $media_attachment['id'] );

		// Build the substitute content.
		$content = $this->new_content( $media_attachment['url'], $insta_page_url );

		// Prep array to update the post.
		$updated_post = array(
			'ID'        => $post_id,
			'post_content'  => $content,
		);

		// Prevent an endless loop.
		$prevent_loop = true;

		// Update the post.
		wp_update_post( $updated_post );
	}

	/**
	 * Import the image into WordPress media library
	 *
	 * @since 1.3.0
	 * @param int    $post_id The id number of the post.
	 * @param string $url     The URL to the media on Instagrams servers.
	 *
	 * @return mixed
	 */
	private function import_media( $post_id, $url ) {

		// Include required functionality if it isn't already available.
		if ( ! function_exists( 'media_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
		}

		// Download the file.
		$tmp = download_url( $url );

		// Check to make sure everything is OK.
		if ( is_wp_error( $tmp ) ) {
			// Download failed.
			return false;
		}

		// Get the title of the image from the post title.
		$media_desc = get_the_title( $post_id );

		// Fix the file name.
		$file_array = array();
		preg_match( '/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches );

		$file_array['name'] = basename( $matches[0] );
		$file_array['tmp_name'] = $tmp;

		// Side load the media file.
		$media_id = media_handle_sideload( $file_array, $post_id, $media_desc );

		// If error storing permanently, abort.
		if ( is_wp_error( $id ) ) {
			return false;
		}

		$media_src = wp_get_attachment_url( $media_id );

		// Return the media ID and the url.
		$media_attachment = array(
			'id'    => $media_id,
			'url'   => $media_src,
		);

		return $media_attachment;
	}

	 /**
	  * Build the replacement content
	  *
	  * @since 1.3.0
	  * @param string $media_url The URL to the imported media file.
	  * @param string $insta_url The URL to the page on Instagram.
	  *
	  * @return mixed
	  */
	private function new_content( $media_url, $insta_url ) {

		// Define some helper variables.
		$allowed_html = array(
			'img' => array(
				'src' => array(),
			),
			'a' => array(
				'href' => array(),
			),
			'p' => array(
				'class' => array(),
			),
			'br' => array(),
		);

		// Build image tag.
		$img_tag = '<img src="' . $media_url . '" />';

		// Build the replacement content.
		$content = '<p class="' . esc_html( $this->plugin_name ) . '-instagram">';
		$content .= $img_tag;
		$content .= '</p>';

		return wp_kses( $content, $allowed_html );
	}
}
