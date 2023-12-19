<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    User_Profiles_Dots
 * @subpackage User_Profiles_Dots/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    User_Profiles_Dots
 * @subpackage User_Profiles_Dots/public
 * @author     Sonali Prajapati <sonaliprajapati2019@gmail.com>
 */
class User_Profiles_Dots_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_shortcode( 'profile_listings', array( $this, 'profile_search_shortcode' ) );
		add_action( 'wp_ajax_profile_search_datatable', array( $this, 'profile_search_datatable' ) );
		add_action( 'wp_ajax_nopriv_profile_search_datatable', array( $this, 'profile_search_datatable' ) );
		add_action( 'wp_footer', array( $this, 'prefix_footer_code' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in User_Profiles_Dots_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The User_Profiles_Dots_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/user-profiles-dots-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-dtable', plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all' );
		wp_register_style( 'Font_Awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
		wp_enqueue_style( 'Font_Awesome' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in User_Profiles_Dots_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The User_Profiles_Dots_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-table2excell', plugin_dir_url( __FILE__ ) . 'js/table2excell.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/user-profiles-dots-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-dTableJS', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-dTablebtnJS', plugin_dir_url( __FILE__ ) . 'js/dataTables.buttons.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-html5btn', plugin_dir_url( __FILE__ ) . 'js/buttons.html5.min.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	 * Generates a shortcode for displaying a profile search form.
	 * This function generates the HTML code for a profile search form and returns it as a shortcode.
	 * The shortcode can be used on any page or post to display the form.
	 */
	public function profile_search_shortcode() {
		ob_start(); // Start output buffering.

		// Include Search Form.
		include_once 'partials/user-profiles-dots-public-display.php';

		return ob_get_clean(); // End output buffering and return the shortcode content.
	}

	/**
	 * Handles the AJAX request for profile search.
	 * Retrieves the search parameters sent by the user and uses WP_Query to query the profiles that match
	 * the search criteria. The results are returned as an HTML string and displayed on the page using jQuery.
	 * The search parameters include skills, education, keyword, age range, ratings, number of jobs completed,
	 * years of experience, and sorting order. The search is performed using the "AND" operator, meaning that all
	 * the search criteria must match for a profile to be included in the results.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function profile_search_datatable() {
		// Verify nonce.
		if ( ! isset( $_POST['pl_multidots_search_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pl_multidots_search_nonce'] ) ), 'pl_multidots_search_nonce' ) ) {
			return;
		}

		// Get the search filters.
		$skills             = isset( $_POST['skills'] ) ?  wp_unslash( $_POST['skills'] ) : '';
		$keyword            = isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';
		$education          = isset( $_POST['education'] ) ? wp_unslash( $_POST['education'] ) : '';
		$age                = isset( $_POST['age'] ) ? sanitize_text_field( wp_unslash( intval( $_POST['age'] ) ) ) : '';
		$ratings            = isset( $_POST['ratings'] ) ? sanitize_text_field( wp_unslash( intval( $_POST['ratings'] ) ) ) : '';
		$no_jobs_completed  = isset( $_POST['jobs_completed'] ) ? sanitize_text_field( wp_unslash( intval( $_POST['jobs_completed'] ) ) ) : '';
		$year_of_experience = isset( $_POST['years_experience'] ) ? sanitize_text_field( wp_unslash( intval( $_POST['years_experience'] ) ) ) : '';
		$sort_order         = isset( $_POST['sorting'] ) ? sanitize_text_field( wp_unslash( $_POST['sorting'] ) ) : 'ASC';

		// Setup the query args.
		$args = array(
			'post_type'      => 'user_profiles_dots',
			'order'          => $sort_order,
			'orderby'        => 'title',
		);

		// Add meta query for age.
		if ( ! empty( $age ) ) {
			$args['meta_query'][] = array(
				'key'     => '_user_profiles_dots_dob_age_in_years',
				'value'   => $age,
				'type'    => 'NUMERIC',
				'compare' => '=',
			);
		}

		// Add meta query for ratings.
		if ( ! empty( $ratings ) ) {
			$args['meta_query'][] = array(
				'key'     => '_user_profiles_dots_ratings',
				'value'   => $ratings,
				'compare' => '=',
				'type'    => 'NUMERIC',
			);
		}

		// Add meta query for jobs completed.
		if ( ! empty( $no_jobs_completed ) ) {
			$args['meta_query'][] = array(
				'key'     => '_user_profiles_dots_no_jobs_completed',
				'value'   => $no_jobs_completed,
				'compare' => '=',
				'type'    => 'NUMERIC',
			);
		}

		// Add meta query for years of experience.
		if ( ! empty( $year_of_experience ) ) {
			$args['meta_query'][] = array(
				'key'     => '_user_profiles_dots_year_of_exp',
				'value'   => $year_of_experience,
				'compare' => '=',
				'type'    => 'NUMERIC',
			);
		}
		
		if ( ! empty( $skills ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'user_profiles_dots_skills',
				'field'    => 'term_id',
				'terms'    => $skills,
				'operator' => 'IN',
			);
		}

		if ( ! empty( $education ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'user_profiles_dots_educations',
				'field'    => 'term_id',
				'terms'    => $education,
				'operator' => 'IN',
			);
		}

		if ( ! empty( $keyword ) ) {
			$args['s'] = $keyword;
		}

		$args['posts_per_page'] = "-1";

		$query = new WP_Query( $args );
		
		$data = array();
		if ( $query->have_posts() ) :
			$i = 1;
			while ( $query->have_posts() ) :
				$query->the_post();
				$dob                = get_post_meta( get_the_ID(), '_user_profiles_dots_dob', true );
				$age                = get_post_meta( get_the_ID(), '_user_profiles_dots_dob_age_in_years', true );
				$hobbies            = get_post_meta( get_the_ID(), '_user_profiles_dots_hobbies', true );
				$interests          = get_post_meta( get_the_ID(), '_user_profiles_dots_interests', true );
				$year_of_experience = get_post_meta( get_the_ID(), '_user_profiles_dots_year_of_exp', true );
				$ratings            = get_post_meta( get_the_ID(), '_user_profiles_dots_ratings', true );
				$no_jobs_completed  = get_post_meta( get_the_ID(), '_user_profiles_dots_no_jobs_completed', true );

				$profile_data   = array(
					$i,
					'<a href="' . get_the_permalink() . '" target="_blank">' . get_the_title() . '</a>',
					$age,
					$year_of_experience,
					$no_jobs_completed,
					$this->generate_star_rating( $ratings ),
					$ratings
				);
				$data['data'][] = $profile_data;
				$i++;
			endwhile;

			header( 'Content-Type: application/json' );
			echo wp_json_encode( $data );
		else :
			echo 'false';
		endif;

		exit();
	}

	/**
	 * Add loader html div to before body closing tag.
	 */
	public function prefix_footer_code() {
		// Add your code here.
		echo '<div id="loader" class="lds-hourglass display-none overlay"></div>';
	}

	/**
	 * Generates HTML for star ratings based on the given rating value.
	 *
	 * @param float $rating The rating value.
	 * @return string The HTML representation of the star ratings.
	 */
	public function generate_star_rating( $rating ) {
		$stars          = '';
		$rounded_rating = round( $rating * 2 ) / 2;

		for ( $i = 1; $i <= 5; $i++ ) {
			if ( $i <= $rounded_rating ) {
				$stars .= '<i class="fa fa-star"></i>';
			} elseif ( $i - $rounded_rating <= 0.5 ) {
				$stars .= '<i class="fa fa-star-half-o"></i>';
			} else {
				$stars .= '<i class="fa fa-star-o"></i>';
			}
		}

		return $stars . "<br/><p style='display: none'>".$rounded_rating."</p>";
	}

}
