<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://techxplorer.com
 * @since      1.0.0
 *
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/admin
 * @author     techxplorer <corey@techxplorer.com>
 */
class Txp_Content_Tweaks_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/txp-content-tweaks-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Include the admin related JavaScript.
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/txp-content-tweaks-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Add a settings page for the plugin.
	 *
	 * @since   1.0.0
	 */
	public function add_plugin_admin_menu() {
		add_options_page(
			__( "Techxplorer's Content Tweaks" ),
			__( 'Content Tweaks' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_settings_page' )
		);
	}

	/**
	 * Add a settings action link to the plugins page.
	 *
	 * @param array $links The list of existing links.
	 *
	 * @since 1.0.0
	 */
	public function add_action_links( $links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', 'txp-content-tweaks' ) . '</a>',
		);
		return array_merge( $settings_link, $links );
	}

	/**
	 * Render the settings page
	 *
	 * @since 1.0.0
	 */
	public function display_plugin_settings_page() {
		include_once( 'partials/txp-content-tweaks-admin-display.php' );
	}

	/**
	 * Register the setting options
	 *
	 * @since 1.0.0
	 */
	public function options_update() {
		register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );
	}

	/**
	 * Validate the admin settings
	 *
	 * @param array $input       The list of input from the settings form.
	 *
	 * @since 1.0.0
	 */
	public function validate( $input ) {
		// All checkbox options.
		$valid = array();

		// Tag management.
		$valid['maptags'] = ( isset( $input['maptags'] ) && ! empty( $input['maptags'] ) ) ? 1 : 0;
		$valid['createtags'] = ( isset( $input['createtags'] ) && ! empty( $input['createtags'] ) ) ? 1 : 0;

		// Media fields.
		$valid['media'] = ( isset( $input['media'] ) && ! empty( $input['media'] ) ) ? 1 : 0;

		$valid['instamedia'] = ( isset( $input['instamedia'] ) && ! empty( $input['instamedia'] ) ) ? 1 : 0;

		// Posts.
		$valid['posts'] = ( isset( $input['posts'] ) && ! empty( $input['posts'] ) ) ? 1 : 0;

		$valid['synchdt'] = ( isset( $input['synchdt'] ) && ! empty( $input['synchdt'] ) ) ? 1 : 0;

		$valid['nocss'] = ( isset( $input['nocss'] ) && ! empty( $input['nocss'] ) ) ? 1 : 0;

		return $valid;
	}
}
