<?php
/**
 * XML Feed
 */

header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<root>
<language><?php bloginfo_rss( 'language' ); ?></language>

	<?php while( have_posts()) : the_post(); ?>

	<item>
		<title><?php the_title_rss() ?></title>
		<link><?php the_permalink_rss() ?></link>

		<?php if ( has_post_thumbnail() ): ?>
		<image>
			<?php
			$tag_names = wp_get_post_tags( $post->ID, array( 'fields' => 'names' ) );

			if ( array_search( 'Featured', $tag_names) !== false ) {

				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'featured-email-thumb' );

			} else {

				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'email-thumb' );

			}

			echo $image[0]; ?>
		</image>
		<?php endif; ?>

		<description>
			<![CDATA[
			<?php $content = get_the_content_feed('rss2');
			echo $content ?>
			]]>
		</description>
		<?php rss_enclosure(); ?>
		<?php do_action('rss2_item'); ?>
	</item>

	<?php endwhile; ?>
</root>
