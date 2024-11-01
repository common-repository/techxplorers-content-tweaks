<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://techxplorer.com
 * @since      1.0.0
 *
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/includes
 * @author     techxplorer <corey@techxplorer.com>
 */
class Txp_Content_Tweaks {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Txp_Content_Tweaks_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

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
	 * Store a reference to the admin class for later use.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     Txp_Content_Tweaks_Admin $plugin_admin An instance of the plugin admin class
	 */
	protected $plugin_admin;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'txp-content-tweaks';
		$this->version = '1.2.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Txp_Content_Tweaks_Loader. Orchestrates the hooks of the plugin.
	 * - Txp_Content_Tweaks_I18n. Defines internationalization functionality.
	 * - Txp_Content_Tweaks_Admin. Defines all hooks for the admin area.
	 * - Txp_Content_Tweaks_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-txp-content-tweaks-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-txp-content-tweaks-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-txp-content-tweaks-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-txp-content-tweaks-public.php';

		$this->loader = new Txp_Content_Tweaks_Loader();

		// Make sure an instance of this class is available for later.
		$this->plugin_admin = new Txp_Content_Tweaks_Admin( $this->plugin_name, $this->version );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Txp_Content_Tweaks_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Txp_Content_Tweaks_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts' );

		// Add menu item.
		$this->loader->add_action( 'admin_menu', $this->plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->get_plugin_name() . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $this->plugin_admin, 'add_action_links' );

		// Register the options.
		$this->loader->add_action( 'admin_init', $this->plugin_admin, 'options_update' );

		// Get the plugin options.
		$options = $this->plugin_admin->validate( get_option( $this->get_plugin_name() ) );

		// Should the tag map functionality be enabled?
		if ( 1 === $options['maptags'] ) {
			// Yes.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-txp-hashtag-map.php';
			$hashtag_map = new Txp_Hashtag_Map( $this->get_plugin_name(), $this->get_version(), $options );

			// Add our functionality to the appropriate hook.
			$this->loader->add_action( 'save_post', $hashtag_map, 'map_tags', 10, 2 );
		}

		// Should the media fields functionality be enabled?
		if ( 1 === $options['media'] ) {
			// Yes.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-txp-media-fields.php';
			$media_fields = new Txp_Media_Fields( $this->get_plugin_name(), $this->get_version() );

			// Add our functionality to the appropriate hooks.
			$this->loader->add_filter( 'attachment_fields_to_edit', $media_fields, 'add_fields', 10, 2 );
			$this->loader->add_filter( 'attachment_fields_to_save', $media_fields, 'save_fields', 10, 2 );
		}

		// Should the Instagram media import functionality be enabled?
		if ( 1 === $options['instamedia'] ) {
			// Yes.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-txp-instamedia.php';
			$insta_media = new Txp_Instamedia( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'publish_post', $insta_media, 'post_published', 10, 2 );
		}

		// Should we enable the post tweaks?
		if ( 1 === $options['posts'] || 1 === $options['synchdt'] ) {
			// Yes.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-txp-post-stats.php';
			$post_stats = new Txp_Post_Stats( $this->get_plugin_name(), $this->get_version() );

			// Add our functionality to the appropriate hooks.
			if ( 1 === $options['posts'] ) {
				// Post statistics generation.
				$this->loader->add_action( 'save_post', $post_stats, 'save_stats', 10, 2 );
			}

			if ( 1 === $options['synchdt'] ) {
				// Sync post published date and last modified date.
				$this->loader->add_action( 'publish_post', $post_stats, 'post_published', 10, 2 );
			}
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// Get the plugin options.
		$options = $this->plugin_admin->validate( get_option( $this->get_plugin_name() ) );

		// Initialise the public plugin component.
		$plugin_public = new Txp_Content_Tweaks_Public( $this->get_plugin_name(), $this->get_version() );

		// Do we need to load the common public facing CSS?
		if ( 1 === $options['posts'] && 0 === $options['nocss'] ) {
			// Yes.
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		}

		// Do we need to load the common public facing JavaScript?
		if ( 1 === $options['posts'] ) {
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		}

		// Should the post statistics shortcode be enabled?
		if ( 1 === $options['posts'] ) {
			// Yes.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-txp-post-stats.php';
			$post_stats = new Txp_Post_Stats( $this->get_plugin_name(), $this->get_version() );

			// Add our functionality to the appropriate hooks.
			$this->loader->add_shortcode( 'txp-post-stats', $post_stats, 'do_shortcode' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();

		// Do any tasks that may be needed on upgrade.
		if ( $this->get_version() !== get_option( $this->plugin_name . '_version' ) ) {
			update_option( $this->plugin_name . '_version', $this->get_version() );

			// Update the options with appropriate defaults, or removing unused options.
			$options = $this->plugin_admin->validate( get_option( $this->get_plugin_name() ) );
			update_option( $this->get_plugin_name(), $options );
		}
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Txp_Content_Tweaks_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
