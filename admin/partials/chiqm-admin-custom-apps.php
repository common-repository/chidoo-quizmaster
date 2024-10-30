<?php

?>

<div class="wrap">
	<h1><?php esc_html_e( get_admin_page_title() ); ?></h1>

	<pre><?php echo esc_html( sanitize_textarea_field(file_get_contents( CHIQM_APPDIR . '/README' ))); ?></pre>

</div>


