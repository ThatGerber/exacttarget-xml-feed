<item>
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
		<?php echo get_the_content_feed('rss2'); ?>
		]]>
	</description>
	<?php rss_enclosure(); ?>
	<?php do_action('rss2_item'); ?>
</item>