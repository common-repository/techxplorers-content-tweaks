<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://techxplorer.com
 * @since      1.0.0
 *
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/admin/partials
 */

// Include the ThickBox library.
add_thickbox();

$allowed_html = array(
	'a' => array(
		'href' => array(),
		'title' => array(),
		'name' => array(),
		'class' => array(),
	),
	'code' => array(),
);
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- Main Content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<form method="post" name="txp-content-tweaks" action="options.php">
							<?php settings_fields( 'txp-content-tweaks' ); ?>
							<?php $options = $this->validate( get_option( $this->plugin_name ) ); ?>
							<h2><span class="dashicons dashicons-tag"></span> <?php esc_html_e( 'Tag Tweaks', 'txp-content-tweaks' ); ?></h2>
							<div class="inside">
								<ul class="striped">
									<li>
										<!-- Add tags by finding and matching hash tags. -->
										<fieldset>
											<legend class="screen-reader-text"><?php esc_html_e( 'Automatic matching of hash tags to WordPress tags.' , 'txp-content-tweaks' ); ?></legend>
											<input type="checkbox"
												 id="<?php echo esc_html( $this->plugin_name ); ?>-maptags"
												 name="<?php echo esc_html( $this->plugin_name ); ?>[maptags]"
												 value="1" <?php checked( $options['maptags'], 1 ); ?>/>
											 <label for="<?php echo esc_html( $this->plugin_name ); ?>-maptags"><?php esc_html_e( 'Enable matching of hash tags to WordPress tags?', 'txp-content-tweaks' ); ?></label>
										</fieldset>
									</li>
									<li>
										<!-- Add tags by finding and matching hash tags. -->
										<fieldset class="<?php echo esc_html( $this->plugin_name ); ?>-admin-indent">
											<legend class="screen-reader-text"><?php esc_html_e( 'Automatic creation of WordPress tags to match hash tags.', 'txp-content-tweaks' ); ?></legend>
											<input type="checkbox"
												 id="<?php echo esc_html( $this->plugin_name ); ?>-createtags"
												 name="<?php echo esc_html( $this->plugin_name ); ?>[createtags]"
												 value="1" <?php checked( $options['createtags'], 1 ); ?>
												 class="<?php echo esc_html( $this->plugin_name ); ?>-tagoption"/>
											<label for="<?php echo esc_html( $this->plugin_name ); ?>-createtags"><?php esc_html_e( 'Enable automatic creation of missing WordPress tags?', 'txp-content-tweaks' ); ?></label>
										</fieldset>
									</li>
								</ul>
							</div>
							<h2><span class="dashicons dashicons-admin-media"></span> <?php esc_html_e( 'Media Tweaks' , 'txp-content-tweaks' ); ?></h2>
							<div class="inside">
								<ul class="striped">
									<li>
										<!-- Add custom media fields -->
										<fieldset>
											<legend class="screen-reader-text"><span><?php esc_html_e( 'Addition of custom media fields.', 'txp-content-tweaks' ); ?></span></legend>
											<input type="checkbox"
												 id="<?php echo esc_html( $this->plugin_name ); ?>-media"
												 name="<?php echo esc_html( $this->plugin_name ); ?>[media]"
												 value="1" <?php checked( $options['media'], 1 ); ?>/>
											<label for="<?php echo esc_html( $this->plugin_name ); ?>-media"><?php esc_html_e( 'Enable the three custom media fields?', 'txp-content-tweaks' ); ?></label>
										</fieldset>
										<p class="description" id="<?php echo esc_html( $this->plugin_name ); ?>-media-description">
											<?php
												echo wp_kses(
													sprintf(
														// translators: the first placeholder is the anchor tag for the more help modal the second is the name of the modal.
														__( 'More information on the three custom media fields is <a href="%1$s" name="%2$s" class="thickbox">available here</a>.', 'txp-content-tweaks' ),
														'#TB_inline?width=600&amp;height=200&amp;inlineId=txp-content-tweaks-media-modal',
														__( 'Media Fields', 'txp-content-tweaks' )
													), $allowed_html
												);
											?>
										</p>
									</li>
									<li>
										<!-- Import Instagram photos into WordPress -->
										<fieldset>
											<legend class="screen-reader-text"><span><?php esc_html_e( 'Import Media from Instagram.', 'txp-content-tweaks' ); ?></span></legend>
											<input type="checkbox"
												 id="<?php echo esc_html( $this->plugin_name ); ?>-instamedia"
												 name="<?php echo esc_html( $this->plugin_name ); ?>[instamedia]"
												 value="1" <?php checked( $options['instamedia'], 1 ); ?>/>
											<label for="<?php echo esc_html( $this->plugin_name ); ?>-instamedia"><?php esc_html_e( 'Import media from Instagram?', 'txp-content-tweaks' ); ?></label>
										</fieldset>
										<p class="description" id="<?php echo esc_html( $this->plugin_name ); ?>-instamedia-description">
											<?php
												echo wp_kses(
													sprintf(
														// translators: the first placeholder is the anchor tag for the more help modal the second is the name of the modal.
														__( 'More information on importing Instagram media is <a href="%1$s" name="%2$s" class="thickbox">available here</a>.', 'txp-content-tweaks' ),
														'#TB_inline?width=600&amp;height=200&amp;inlineId=txp-content-tweaks-instamedia-modal',
														__( 'Import Instagram Media', 'txp-content-tweaks' )
													), $allowed_html
												);
											?>
										</p>
									</li>
								</ul>
							</div>
							<h2><span class="dashicons dashicons-admin-post"></span> <?php esc_html_e( 'Post Tweaks', 'txp-content-tweaks' ); ?></h2>
							<div class="inside">
								<ul class="striped">
									<li>
										<!-- Enable Post Statistics shortcode -->
										<fieldset>
											<legend class="screen-reader-text"><span><?php esc_html_e( 'Enabling shortcode to display post statics.', 'txp-content-tweaks' ); ?></span></legend>
											<input type="checkbox"
												 id="<?php echo esc_html( $this->plugin_name ); ?>-posts"
												 name="<?php echo esc_html( $this->plugin_name ); ?>[posts]"
												 value="1" <?php checked( $options['posts'], 1 ); ?>/>
											<label for="<?php echo esc_html( $this->plugin_name ); ?>-posts"><?php esc_html_e( 'Enable the post statistics shortcode?', 'txp-content-tweaks' ); ?></label>
										</fieldset>
										<p class="description" id="<?php echo esc_html( $this->plugin_name ); ?>-posts-description">
											<?php
												echo wp_kses(
													sprintf(
														// translators: the first placeholder is the anchor tag for the more help modal the second is the name of the modal.
														__( 'More information on the post statistics is <a href="%1$s" name="%2$s" class="thickbox">available here</a>.', 'txp-content-tweaks' ),
														'#TB_inline?width=600&amp;height=300&amp;inlineId=txp-content-tweaks-posts-modal',
														__( 'Post Statistics', 'txp-content-tweaks' )
													), $allowed_html
												);
											?>
										</p>
									</li>
									<li>
										<!-- Synchronise published and last modified dates. -->
										<fieldset>
											<legend class="screen-reader-text"><span><?php esc_html_e( 'Synchronise published and last modified dates.', 'txp-content-tweaks' ); ?></span></legend>
											<input type="checkbox"
												 id="<?php echo esc_html( $this->plugin_name ); ?>-synchdt"
												 name="<?php echo esc_html( $this->plugin_name ); ?>[synchdt]"
												 value="1" <?php checked( $options['synchdt'], 1 ); ?>/>
											<label for="<?php echo esc_html( $this->plugin_name ); ?>-synchdt"><?php esc_html_e( 'Synchronise published and last modified date?', 'txp-content-tweaks' ); ?></label>
											<p class="description" id="<?php echo esc_html( $this->plugin_name ); ?>-synchdt-description">
												<?php esc_html_e( 'When a post is published, set the last modified date to the published date.' ); ?>
											</p>
										</fieldset>
									</li>
								</ul>
							</div>
							<h2><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'General Options', 'txp-content-tweaks' ); ?></h2>
							<div class="inside">
								<ul class="striped">
									<li>
										<!-- Disable custom CSS -->
										<fieldset>
											<legend class="screen-reader-text"><span><?php esc_html_e( 'Disabled custom CSS.', 'txp-content-tweaks' ); ?></span></legend>
											<input type="checkbox"
												 id="<?php echo esc_html( $this->plugin_name ); ?>-nocss"
												 name="<?php echo esc_html( $this->plugin_name ); ?>[nocss]"
												 value="1" <?php checked( $options['nocss'], 1 ); ?>/>
											<label for="<?php echo esc_html( $this->plugin_name ); ?>-nocss"><?php esc_html_e( 'Disable the custom CSS for these features?', 'txp-content-tweaks' ); ?></label>
											<p class="description" id="<?php echo esc_html( $this->plugin_name ); ?>-nocss-description">
												<?php esc_html_e( 'Disabling the CSS make it easier to add your own using the functionality of your theme. ' ); ?>
											</p>
										</fieldset>
									</li>
								</ul>
							</div>
							<div class="inside">
								<?php submit_button( 'Save all changes', 'primary','submit', true ); ?>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Sidebar -->
		   <div id="postbox-container-1" class="postbox-container">
			   <div class="metabox-sortables">
				   <div class="postbox">
					   <h2><span class="dashicons dashicons-info"></span> <?php esc_html_e( 'More information' ); ?></h2>
					   <div class="inside">
						   <p><?php esc_html_e( 'The purpose of this plugin is to implement a variety of small tweaks to content that I find useful.', 'txp-content-tweaks' ); ?></p>
						   <p><?php esc_html_e( 'More information on this plugin is available from the links below.', 'txp-content-tweaks' ); ?></p>
						   <ul class="striped">
							   <li><span class="dashicons dashicons-admin-plugins"></span> <a href="https://techxplorer.com/projects/techxplorers-content-tweaks/"><?php esc_html_e( 'Plugin homepage.', 'txp-content-tweaks' ); ?></a></li>
							   <li><span class="dashicons dashicons-twitter"></span> <a href="https://twitter.com/techxplorer"><?php esc_html_e( 'My Twitter profile.', 'txp-content-tweaks' ); ?></a></li>
							   <li><span class="dashicons dashicons-admin-home"></span> <a href="https://techxplorer.com/"><?php esc_html_e( 'My website.', 'txp-content-tweaks' ); ?></a></li>
						   </ul>
				   </div>
			   </div>
		   </div>
		   <br class="clear">
	   </div>
	</div>
</div>
<!-- Modal contents -->
<div id="<?php echo esc_html( $this->plugin_name ); ?>-posts-modal" style="display:none;">
	<h2><span class="dashicons dashicons-chart-line"></span> <?php esc_html_e( 'Post Statistics', 'txp-content-tweaks' ); ?></h2>
	<p>
		<?php
			// translators: the placeholder is the name of the shortcode.
			echo wp_kses( sprintf( __( 'With this option enabled the %s shortcode will be replaced with content detailing some statistics about the post.', 'txp-content-tweaks' ), '<code>[txp-post-stats]</code>' ), $allowed_html );
		?>
	</p>
	<p><?php esc_html_e( 'The following statistics are only generated automatically and cached to improve performance.', 'txp-content-tweaks' ); ?></p>
	<ol>
		<li><?php esc_html_e( 'Number of words in the post.', 'txp-content-tweaks' ); ?></li>
		<li><?php esc_html_e( 'The estimated reading time, (number of words / 180 words per minute).', 'txp-content-tweaks' ); ?></li>
		<li><?php esc_html_e( 'The last modified date and time.', 'txp-content-tweaks' ); ?></li>
	</ol>
</div>
<div id="<?php echo esc_html( $this->plugin_name ); ?>-media-modal" style="display:none;">
	<h2><span class="dashicons dashicons-admin-media"></span> <?php esc_html_e( 'Media Fields', 'txp-content-tweaks' ); ?></h2>
	<p><?php esc_html_e( 'The following custom media fields are added:', 'txp-content-tweaks' ); ?></p>
	<ol>
		<li><?php esc_html_e( 'Author name. The person who created the media.', 'txp-content-tweaks' ); ?></li>
		<li><?php esc_html_e( 'Author URL. A URL to more information about the author.', 'txp-content-tweaks' ); ?></li>
		<li><?php esc_html_e( 'Source URL. A URL to the original source of the media.', 'txp-content-tweaks' ); ?></li>
	</ol>
</div>
<div id="<?php echo esc_html( $this->plugin_name ); ?>-instamedia-modal" style="display:none;">
	<h2><span class="dashicons dashicons-admin-media"></span> <?php esc_html_e( 'Import Instagram Media', 'txp-content-tweaks' ); ?></h2>
	<p><?php esc_html_e( 'The IFTTT applet that adds Instagram posts to your blog does not import the photo. Instead it uses an iframe element.', 'txp-content-tweaks' ); ?></p>
	<p><?php esc_html_e( 'When this option is enabled, the photo is automatically imported and the iframe element is replaced with a native media embed.', 'txp-content-tweaks' ); ?></p>
</div>
