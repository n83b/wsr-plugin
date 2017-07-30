<?php get_header(); ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<h1><?php the_title(); ?></h1>
		<?php the_post_thumbnail( 'medium', array('class' => 'img-responsive alignleft')); ?>
	  	<?php the_content(); ?>

	<?php endwhile; else: ?>
		<p><?php _e('Sorry, no promotion matched your criteria.'); ?></p>
	<?php endif; ?>
<?php get_footer(); ?>