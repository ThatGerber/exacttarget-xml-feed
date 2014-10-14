<?php global $post; ?>
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
			XT_XML_Feed::feed_image( $post, XT_XML_Feed::$cat->slug );
		} ?>
	</image>
	<description>
		<![CDATA[
			<?php XT_XML_Feed::the_description( $post->post_content ); ?>
		]]>
	</description>
	<?php do_action('rss2_item'); ?>
</item>