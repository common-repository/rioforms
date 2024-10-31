<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Rioforms\Helpers\Utils;

$id     = $attributes['id'] ?? '';
$postId = $attributes['postId'] ?? '';
if ( ! $postId ) {
	echo '<h2>Please select a form</h2>';

	return;
}
$post = get_post( $postId, OBJECT, 'edit' );

if ( ! $post || 'publish' !== $post->post_status ) {
	echo '<h2>No form found.</h2>';

	return;
}
//$style         = $attributes['styles'] ?? '';
//$form_style    = get_post_meta( $postId, 'form_block_style', true );
$parsed_blocks = parse_blocks( $post->post_content );
?>
<div <?php
echo wp_kses_data( get_block_wrapper_attributes( [
	'class' => sprintf( 'rioform-container %1$s', esc_attr( $id ) )
] ) ); ?>>
	<div>
		<?php
		echo wp_kses( render_block( $parsed_blocks[0] ), Utils::form_allowed_html() ); ?>
	</div>
</div>
