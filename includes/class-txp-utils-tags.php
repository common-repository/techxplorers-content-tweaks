<?php
/**
 * A utlity class for working with tags.
 *
 * @link       https://techxplorer.com
 * @since      1.0.0
 *
 * @package Txp_Utils
 * @author  techxplorer <corey@techxplorer.com>
 */

/**
 * A class containing tag related utility functions.
 *
 * @since   1.0.0
 * @package Txp_Utils
 * @author  techxplorer <corey@techxplorer.com>
 */
class Txp_Utils_Tags {

	/**
	 * Retrieve a list of hash tags from a string
	 *
	 * @param string  $content     The content to search for hash tags.
	 * @param boolean $strip_hash  Flag to indicate to strip the hashes from the tags.
	 *
	 * @return array An array of identified hash tags.
	 */
	public static function find_hashtags( $content, $strip_hash = false ) {

		// Trim the content and check for an empty string.
		$content = trim( $content );

		// Find all of the hash tags.
		$hash_tags = array();

		if ( '' === $content ) {
			return $hash_tags;
		}

		// Based on an idea sourced from: http://stackoverflow.com/a/16609221.
		preg_match_all( '/(#\w+)/u', $content, $matches );

		if ( false === empty( $matches ) && false === empty( $matches[0] ) ) {
			$hash_tags = array_unique( $matches[0] );
		}

		// Should the hash '#' be removed from the tag?
		if ( true === $strip_hash ) {
			array_walk(
				$hash_tags, function( &$value, $key ) {
					$value = ltrim( $value, '#' );
				}
			);
		}

		return $hash_tags;
	}
}
