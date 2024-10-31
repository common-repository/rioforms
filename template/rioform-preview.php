<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! wp_verify_nonce( $_GET['nonce'], 'rioform-preview-nonce' ) ) {
	wp_die();
}
?>
<!DOCTYPE html>
<html <?php
language_attributes(); ?>>
<head>
	<meta charset="<?php
	bloginfo( 'charset' ); ?>"/>
	<?php
	wp_head(); ?>
</head>

<body <?php
body_class( 'rio-form-preview-template' ); ?>>
<?php
wp_body_open(); ?>

<div class="rio-form-preview-container">
	<?php
	$form_id       = sanitize_text_field( $_GET['form_id'] ) ?? '';
	$post          = get_post( $form_id, OBJECT, 'edit' );
	$parsed_blocks = parse_blocks( $post->post_content );
	$block         = $parsed_blocks[0];
	$styles        = $block['attrs']['styles'] ?? '';
	$styles        .= $block['attrs']['innerBlocksStyles'] ?? '';
	$fonts         = $block['attrs']['fontFamily'] ?? '';

	if ( $post ) {
		rioforms()->styleGenerator->generate_inline_assetes( $fonts, $styles );
		echo wp_kses( render_block( $block ), \Rioforms\Helpers\Utils::form_allowed_html() );
	}
	?>
</div>

<?php
wp_footer(); ?>
</body>
</html>
