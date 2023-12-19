<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    User_Profiles_Dots
 * @subpackage User_Profiles_Dots/public/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<form id="profile-search-form">
<?php wp_nonce_field( 'pl_multidots_search_nonce', 'pl_multidots_search_nonce' ); ?>
	<div class="form-group">
		<label for="keyword">Keyword:</label>
		<input type="text" name="keyword" id="keyword">
	</div>
	<div class="two_column">
		<div class="form-group">
			<label for="skills">Skills:</label>
			<?php
			$terms = get_terms(
				array(
					'taxonomy'   => 'user_profiles_dots_skills',
					'hide_empty' => false,
				)
			);

			if ( ! empty( $terms ) ) {
				echo '<select name="skills[]" id="skills" multiple>';
				echo '<option value="">Select Skills</option>';

				foreach ( $terms as $skill ) {
					echo '<option value="' . esc_attr( $skill->term_id ) . '">' .
						esc_html( $skill->name ) .
						'</option>';
				}

				echo '</select>';
			}
			?>
		</div>
		<div class="form-group">
			<label for="education">Education:</label>
			<?php
			$education_terms = get_terms(
				array(
					'taxonomy'   => 'user_profiles_dots_educations',
					'hide_empty' => false,
				)
			);

			if ( ! empty( $education_terms ) ) {
				echo '<select name="education[]" id="education" multiple>';
				echo '<option value="">Select Education</option>';

				foreach ( $education_terms as $education ) {
					echo '<option value="' . esc_attr( $education->term_id ) . '">' .
						esc_html( $education->name ) .
						'</option>';
				}

				echo '</select>';
			}
			?>
		</div>
	</div>
	<div class="two_column">
		<div class="form-group range_filter">
			<label for="age">Age: </label>
			<input type="range" name="age" id="age" min="0" max="150" value="0" onchange="updateTextInput(this.value);">
			[<span id="textInput">0</span>]
		</div>
		<div class="form-group">
			<label for="ratings">Ratings:</label>
			<select name="ratings" id="ratings">
			<option value="">Select Ratings</option>
			<option value="5">5 Stars</option>
			<option value="4">4 Stars</option>
			<option value="3">3 Stars</option>
			<option value="2">2 Stars</option>
			<option value="1">1 Star</option>
			</select>
		</div>
	</div>

	<div class="two_column">
		<div class="form-group">
			<label for="jobs_completed">No of Jobs Completed:</label>
			<input type="number" name="jobs_completed" id="jobs_completed" min="0">
		</div>
		<div class="form-group">
			<label for="years_experience">Years of Experience:</label>
			<input type="number" name="years_experience" id="years_experience" min="0">
		</div>
	</div>

	<div class="form-group">
		<button type="submit" class="btn btn-primary" id="profile_search">Search</button>
	</div>
</form>

<div class="profile-listing-wrap" style="display: none;">
	<!-- <div id="profile-listing"></div> -->
	<?php
		$sort_order = 'DESC';
		$args       = array(
			'post_type'   => 'user_profiles_dots',
			'order'       => $sort_order,
			'orderby'     => 'title',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		);
		$query      = new WP_Query( $args );
		if ( $query->have_posts() ) :
			$i = 1;
			// Loop through the posts and get the custom meta fields.
			echo '<table border="1" id="profile_datas">';
			echo '<thead><tr><th>No.</th><th class="profile_sorting" data-sort="desc">Profile Name</th><th>Age</th><th>Years of Experience</th><th>No of Jobs Completed</th><th style="width: 70px;">Ratings</th></tr></thead>';
			while ( $query->have_posts() ) :
				$query->the_post();
				$dob                = get_post_meta( get_the_ID(), '_user_profiles_dots_dob', true );
				$age                = get_post_meta( get_the_ID(), '_user_profiles_dots_dob_age_in_years', true );
				$hobbies            = get_post_meta( get_the_ID(), '_user_profiles_dots_hobbies', true );
				$interests          = get_post_meta( get_the_ID(), '_user_profiles_dots_interests', true );
				$year_of_experience = get_post_meta( get_the_ID(), '_user_profiles_dots_year_of_exp', true );
				$ratings            = get_post_meta( get_the_ID(), '_user_profiles_dots_ratings', true );
				$no_jobs_completed  = get_post_meta( get_the_ID(), '_user_profiles_dots_no_jobs_completed', true );
				?>
				<tr>
					<td><?php echo esc_html( $i ); ?></td>
					<td><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></td>
					<td><?php echo esc_html( $age ); ?></td>
					<td> <?php echo esc_html( $year_of_experience ); ?></td>
					<td> <?php echo esc_html( $no_jobs_completed ); ?></td>
					<td> 
					<?php
					$stars          = '';
					$rounded_rating = round( esc_html( $ratings ) * 2 ) / 2;

					for ( $i = 1; $i <= 5; $i++ ) {
						if ( $i <= $rounded_rating ) {
							$stars .= '<i class="fa fa-star"></i>';
						} elseif ( $i - $rounded_rating <= 0.5 ) {
							$stars .= '<i class="fa fa-star-half-o"></i>';
						} else {
							$stars .= '<i class="fa fa-star-o"></i>';
						}
					}
					echo $stars;
					?>
					</td>
				</tr>
				<?php
				$i++;
			endwhile;
			echo '</table> <button id="export-profiles">Export</button>';

			wp_reset_postdata();
		endif;
		?>
</div>

<script>
function updateTextInput(val) {
	document.getElementById('textInput').innerHTML=val; 
}
</script>
