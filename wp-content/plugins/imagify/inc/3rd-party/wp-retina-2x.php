<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

if ( function_exists( 'wr2x_delete_attachment' ) && function_exists( 'wr2x_generate_images' ) ) :

	/**
	 * Process to restore all retina versions for an attachment.
	 *
	 * @since 1.0
	 */

	add_action( 'before_imagify_restore_attachment', '_imagify_wr2x_delete_attachment_on_restore' );
	/**
	 * Remove all retina versions if exists.
	 *
	 * @since 1.0
	 *
	 * @param int $attachment_id An attachment ID.
	 */
	function _imagify_wr2x_delete_attachment_on_restore( $attachment_id ) {
		wr2x_delete_attachment( $attachment_id );
	}

	add_action( 'after_imagify_restore_attachment', '_imagify_wr2x_generate_images_on_restore' );
	/**
	 * Regenerate all retina versions.
	 *
	 * @since 1.0
	 *
	 * @param int $attachment_id An attachment ID.
	 */
	function _imagify_wr2x_generate_images_on_restore( $attachment_id ) {
		$metadata = wp_get_attachment_metadata( $attachment_id );
		wr2x_generate_images( $metadata );
	}

endif;

if ( function_exists( 'wr2x_get_retina' ) ) :

	/**
	 * Process to generate the retina version of a thumbnail.
	 *
	 * @since 1.0
	 */

	add_filter( 'imagify_fill_thumbnail_data', '_imagify_optimize_wr2x', 10, 7 );
	/**
	 * Filter the optimization data of each thumbnail.
	 *
	 * @since 1.0
	 *
	 * @param  array  $data               The statistics data.
	 * @param  object $response           The API response.
	 * @param  int    $id                 The attachment ID.
	 * @param  string $path               The attachment path.
	 * @param  string $url                The attachment URL.
	 * @param  string $size_key           The attachment size key.
	 * @param  bool   $optimization_level The optimization level.
	 * @return array  $data               The new optimization data.
	 */
	function _imagify_optimize_wr2x( $data, $response, $id, $path, $url, $size_key, $optimization_level ) {
		/**
		 * Allow to optimize the retina version generated by WP Retina x2.
		 *
		 * @since 1.0
		 *
		 * @param bool $do_retina True will force the optimization.
		 */
		$do_retina   = apply_filters( 'do_imagify_optimize_retina', true );
		$retina_path = wr2x_get_retina( $path );

		if ( empty( $retina_path ) || ! $do_retina ) {
			return $data;
		}

		$response = do_imagify( $retina_path, array(
			'backup'             => false,
			'optimization_level' => $optimization_level,
			'context'            => 'wp-retina',
		) );
		$class_name = get_imagify_attachment_class_name( 'wp', $id, 'imagify_fill_thumbnail_data' );
		$attachment = new $class_name( $id );

		return $attachment->fill_data( $data, $response, $url, $size_key . '@2x' );
	}

endif;