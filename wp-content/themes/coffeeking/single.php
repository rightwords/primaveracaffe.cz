<?php
/**
 * The Template for displaying all single posts
 */

get_header(); ?>
<div class="inner-page margin-default">
    <div class="row">
        <div class="col-lg-9 col-md-8">
            <section class="blog-post">
				<?php
					// Start the Loop.
				while ( have_posts() ) : the_post();

					get_template_part( 'tmpl/content-blog-full', get_post_format() );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					endwhile;
				?>                    
            </section>
        </div>

		<?php
			get_sidebar();
		?>
    </div>
</div>
<?php

get_footer();
