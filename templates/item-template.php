<item>
	<?php rss_enclosure(); ?>
	<title>
		<?php the_title_rss() ?>
	</title>
	<link>
	<?php the_permalink_rss() ?>
	</link>
	<image>
		<?php if ( has_post_thumbnail() ) {
			XT_XML::feed_image( $post );
		} ?>
	</image>
	<description>
		<![CDATA[
		<?php XT_XML::the_description( $post->post_content, 200 ); ?>
		]]>
	</description>
	<?php do_action('rss2_item'); ?>
</item>