<?php
	$img = sbrpw_get_post_thumbnail( get_the_ID() , $atts['thumb_size'], 'wide');
	$link = get_permalink();
?>

<li>
	<a href="<?php echo $link ?>" title="<?php the_title() ?>">
		<img src="<?php echo $img ?>" alt="<?php the_title() ?>">
	</a>
</li>