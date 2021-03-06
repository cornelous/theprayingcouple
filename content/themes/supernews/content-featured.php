<?php 
global $cat;

// Get the category ID.
$category = get_category( get_query_var( 'cat' ), false );
$style = get_tax_meta( $category->term_id, 'supernews_featured_layout', false );

// Set the default value for the style.
if ( empty( $style ) ) {
	$style = 'classic';
}

// Get the tag ID.
$tag = get_tax_meta( $category->term_id, 'supernews_featured_tag', false );

if ( $tag !== '' ) : ?>

	<?php
		// Posts query arguments.
		$query = array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
			'tag_id'         => $tag,
			'cat'            => $cat
		);

		// Allow dev to filter the query.
		$args = apply_filters( 'supernews_featured_posts_args', $query );

		// The post query
		$featured = new WP_Query( $args );
	?>

	<?php if ( $featured->have_posts() ) : ?>

		<?php if ( 'classic' === $style ) : ?>

			<div id="featured-content-2" class="category-box clearfix">
				<h3 class="section-title"><strong><?php _e( 'Featured News', 'supernews' ); ?></strong></h3>

				<?php $i = 0; ?>

				<?php while ( $featured->have_posts() ) : $featured->the_post(); ?>

					<?php if ( ++$i == 1 ) :  ?>

						<div class="featured-big">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'supernews-featured-big', array( 'class' => 'entry-thumbnail', 'alt' => esc_attr( get_the_title() ) ) ); ?></a>
							<?php endif; ?>
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<?php if ( 'post' == get_post_type() ) : ?>
								<div class="entry-meta">
									<span class="entry-date"><time class="entry-date published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() )?></time></span>
									<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
										<span class="entry-comment"><?php comments_popup_link( __( '0 Comment', 'supernews' ), __( '1 Comment', 'supernews' ), __( '% Comments', 'supernews' ) ); ?></span>
									<?php endif; ?>
								</div><!-- .entry-meta -->
							<?php endif; ?>

							<div class="entry-summary">
								<?php echo wp_trim_words( get_the_excerpt(), 24 ); ?>
							</div><!-- .entry-content -->

							<div class="more-link">
							    <a href="<?php the_permalink(); ?>"><?php _e( 'Read more >>', 'supernews' ); ?></a>
							</div><!-- .more-link -->  
						</div>

					<?php else : ?>

						<?php
							$class = '';
							if ( $i == 3 ) { $class = 'last'; }
						?>

						<div class="featured-small <?php echo $class; ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'supernews-featured-small', array( 'class' => 'entry-thumbnail', 'alt' => esc_attr( get_the_title() ) ) ); ?></a>
							<?php endif; ?>
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<?php if ( 'post' == get_post_type() ) : ?>
								<div class="entry-meta">
									<span class="entry-date"><time class="entry-date published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() )?></time></span>
									<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
										<span class="entry-comment"><?php comments_popup_link( __( '0 Comment', 'supernews' ), __( '1 Comment', 'supernews' ), __( '% Comments', 'supernews' ) ); ?></span>
									<?php endif; ?>
								</div><!-- .entry-meta -->
							<?php endif; ?>
						</div>

					<?php endif; ?>

				<?php endwhile; ?>

			</div>

		<?php elseif ( 'slider' === $style ) : ?>

			<div id="featured-content" class="category-box clearfix">
				<div id="carousel-0" class="jcarousel">
					<ul>
						<?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
							<li>
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<?php if ( has_post_thumbnail() ) : ?>
										<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'supernews-blog', array( 'class' => 'entry-thumbnail', 'alt' => esc_attr( get_the_title() ) ) ); ?></a>
									<?php endif; ?>
									<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									<?php if ( 'post' == get_post_type() ) : ?>
										<div class="entry-meta">
											<span class="entry-date"><time class="entry-date published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() )?></time></span>
											<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
												<span class="entry-comment"><?php comments_popup_link( __( '0 Comment', 'supernews' ), __( '1 Comment', 'supernews' ), __( '% Comments', 'supernews' ) ); ?></span>
											<?php endif; ?>
										</div><!-- .entry-meta -->
									<?php endif; ?>

									<div class="entry-summary">
										<?php echo wp_trim_words( get_the_excerpt(), 24 ); ?>
									</div><!-- .entry-content -->

									<div class="more-link">
									    <a href="<?php the_permalink(); ?>"><?php _e( 'Read more >>', 'supernews' ); ?></a>
									</div><!-- .more-link -->  
								</article>
							</li>
						<?php endwhile; ?>
					</ul>
					<p class="jcarousel-pagination-0"></p>
					<a href="#" class="jcarousel-control-prev"><i class="fa fa-chevron-left"></i></a>
					<a href="#" class="jcarousel-control-next"><i class="fa fa-chevron-right"></i></a>
				</div>
			</div>

		<?php endif; ?>

	<?php endif; wp_reset_postdata(); ?>

<?php endif; ?>