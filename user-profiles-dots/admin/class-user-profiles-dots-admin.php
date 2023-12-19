<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    User_Profiles_Dots
 * @subpackage User_Profiles_Dots/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    User_Profiles_Dots
 * @subpackage User_Profiles_Dots/admin
 * @author     Sonali Prajapati <sonaliprajapati2019@gmail.com>
 */
class User_Profiles_Dots_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_action( 'init', array( $this, 'user_profiles_dots_posttype' ) );
		flush_rewrite_rules();
		add_action( 'init', array( $this, 'create_skills_taxonomy' ) );
		add_action( 'init', array( $this, 'create_educations_taxonomy' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_user_profiles_dots_custom_meta_fields' ) );
		add_action( 'save_post_user_profiles_dots', array( $this, 'save_user_profiles_dots_custom_meta_fields' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/user-profiles-dots-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/user-profiles-dots-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Profile custom post type register
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function user_profiles_dots_posttype() {

		$labels = array(
			'name'               => __( 'Profiles' ),
			'singular_name'      => __( 'Profile' ),
			'all_items'          => __( 'All Profile' ),
			'add_new'            => _x( 'Add New Profile', 'Profile' ),
			'add_new_item'       => __( 'Add New Profile' ),
			'edit_item'          => __( 'Edit Profile' ),
			'new_item'           => __( 'New Profile' ),
			'view_item'          => __( 'View Profile' ),
			'search_items'       => __( 'Search in Profile' ),
			'not_found'          => __( 'No Profile found' ),
			'not_found_in_trash' => __( 'No Profile found in trash' ),
			'parent_item_colon'  => '',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'user_profiles_dots' ),
			'with_front'         => false,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-groups',
			'supports'           => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'author', 'page-attributes', 'comments' ),
		);
		register_post_type( 'user_profiles_dots', $args );
	}

	/**
	 * Registers the "Educations" custom taxonomy for the "Profile" post type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function create_skills_taxonomy() {
		$labels = array(
			'name'              => _x( 'Skills', 'taxonomy general name' ),
			'singular_name'     => _x( 'Skill', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Skills' ),
			'all_items'         => __( 'All Skills' ),
			'parent_item'       => __( 'Parent Skill' ),
			'parent_item_colon' => __( 'Parent Skill:' ),
			'edit_item'         => __( 'Edit Skill' ),
			'update_item'       => __( 'Update Skill' ),
			'add_new_item'      => __( 'Add New Skill' ),
			'new_item_name'     => __( 'New Skill Name' ),
			'menu_name'         => __( 'Skills' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => 'skills',
				'with_front' => false,
			),
		);

		register_taxonomy( 'user_profiles_dots_skills', 'user_profiles_dots', $args );
	}

	/**
	 * Registers the "Educations" custom taxonomy for the "Profile" post type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function create_educations_taxonomy() {
		$labels = array(
			'name'              => _x( 'Educations', 'taxonomy general name' ),
			'singular_name'     => _x( 'Education', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Educations' ),
			'all_items'         => __( 'All Educations' ),
			'parent_item'       => __( 'Parent Education' ),
			'parent_item_colon' => __( 'Parent Education:' ),
			'edit_item'         => __( 'Edit Education' ),
			'update_item'       => __( 'Update Education' ),
			'add_new_item'      => __( 'Add New Education' ),
			'new_item_name'     => __( 'New Education Name' ),
			'menu_name'         => __( 'Educations' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => 'educations',
				'with_front' => false,
			),
		);

		register_taxonomy( 'user_profiles_dots_educations', 'user_profiles_dots', $args );
	}

	/**
	 * Adds custom meta fields for the user_profiles_dots custom post type
	 *
	 * This function is hooked to the `add_meta_boxes` action and adds custom meta fields for the user_profiles_dots custom post type.
	 * The custom meta fields include the date of birth, hobbies, interests, year of experience, ratings, and number of jobs completed.
	 * These custom meta fields are added to the edit screen for the user_profiles_dots custom post type.
	 *
	 * @return void
	 */
	public function add_user_profiles_dots_custom_meta_fields() {
		add_meta_box(
			'user_profiles_dots_meta_box',
			'Custom Meta Fields',
			array( $this, 'display_user_profiles_dots_custom_meta_fields' ),
			'user_profiles_dots',
			'normal',
			'high'
		);
	}

	/**
	 * Displays custom meta fields for the user_profiles_dots custom post type
	 *
	 * This function is called within the user_profiles_dots custom post type loop to display the custom meta fields for the post being displayed.
	 * The custom meta fields include the date of birth, hobbies, interests, year of experience, ratings, and number of jobs completed.
	 * These custom meta fields are displayed in a table format.
	 *
	 * @param post $post WP_Post The post object for the current post being displayed.
	 * @return void
	 */
	public function display_user_profiles_dots_custom_meta_fields( $post ) {

		wp_nonce_field( 'user_profiles_dots_custom_meta_fields_nonce', 'user_profiles_dots_custom_meta_fields_nonce' );

		$dob               = get_post_meta( $post->ID, '_user_profiles_dots_dob', true );
		$hobbies           = get_post_meta( $post->ID, '_user_profiles_dots_hobbies', true );
		$interests         = get_post_meta( $post->ID, '_user_profiles_dots_interests', true );
		$year_of_exp       = get_post_meta( $post->ID, '_user_profiles_dots_year_of_exp', true );
		$ratings           = get_post_meta( $post->ID, '_user_profiles_dots_ratings', true );
		$no_jobs_completed = get_post_meta( $post->ID, '_user_profiles_dots_no_jobs_completed', true );
		?>
		<table>
			<tr>
				<td style="width: 25%;"><label for="user_profiles_dots_dob"><?php echo esc_html_e( 'Date of Birth', 'user_profiles_dots' ); ?></label></td>
				<td><input type="date" name="user_profiles_dots_dob" id="user_profiles_dots_dob" value="<?php echo esc_attr( $dob ); ?>" /></td>
			</tr>
			<tr>
				<td style="width: 25%;"><label for="user_profiles_dots_hobbies"><?php echo esc_html_e( 'Hobbies', 'user_profiles_dots' ); ?></label></td>
				<td><input type="text" name="user_profiles_dots_hobbies" id="user_profiles_dots_hobbies" value="<?php echo esc_attr( $hobbies ); ?>"></td>
			</tr>
			<tr>
				<td style="width: 25%;"><label for="user_profiles_dots_interests"><?php echo esc_html_e( 'Interests', 'user_profiles_dots' ); ?></label></td>
				<td><input type="text" name="user_profiles_dots_interests" id="user_profiles_dots_interests" value="<?php echo esc_attr( $interests ); ?>"></td>
			</tr>
			<tr>
				<td style="width: 25%;"><label for="user_profiles_dots_year_of_exp"><?php echo esc_html_e( 'Year of Experience', 'user_profiles_dots' ); ?></label></td>
				<td><input type="number" name="user_profiles_dots_year_of_exp" id="user_profiles_dots_year_of_exp" value="<?php echo esc_attr( $year_of_exp ); ?>"></td>
			</tr>
			<tr>
				<td style="width: 25%;"><label for="user_profiles_dots_ratings"><?php echo esc_html_e( 'Ratings', 'user_profiles_dots' ); ?></label></td>
				<td><input type="number" step="0.1" min="1" max="5" name="user_profiles_dots_ratings" id="user_profiles_dots_ratings" value="<?php echo esc_attr( $ratings ); ?>"></td>
			</tr>
			<tr>
				<td style="width: 25%;"><label for="user_profiles_dots_no_jobs_completed"><?php echo esc_html_e( 'No. of Jobs Completed', 'user_profiles_dots' ); ?></label></td>
				<td><input type="number" name="user_profiles_dots_no_jobs_completed" id="user_profiles_dots_no_jobs_completed" value="<?php echo esc_attr( $no_jobs_completed ); ?>"></td>
			</tr>
		</table>

		<?php
	}

	/**
	 * Saves custom meta fields for the user_profiles_dots custom post type
	 *
	 * This function is hooked to the `save_post` action and saves the custom meta fields for the user_profiles_dots custom post type.
	 * The custom meta fields include the date of birth, hobbies, interests, year of experience, ratings, and number of jobs completed.
	 *
	 * @param post_id $post_id int The ID of the post being saved.
	 * @return void
	 */
	public function save_user_profiles_dots_custom_meta_fields( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Verify nonce.
		if ( ! isset( $_POST['user_profiles_dots_custom_meta_fields_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['user_profiles_dots_custom_meta_fields_nonce'] ) ), 'user_profiles_dots_custom_meta_fields_nonce' ) ) {
			return;
		}

		if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['user_profiles_dots_custom_meta_fields_nonce'] ) ), 'user_profiles_dots_custom_meta_fields_nonce' ) ) {
			$action = ( isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '' );
		}

		if ( isset( $_REQUEST['user_profiles_dots_dob'] ) ) {
			$bday  = new DateTime( sanitize_text_field( wp_unslash( $_REQUEST['user_profiles_dots_dob'] ) ) ); // Your date of birth.
			$today = new Datetime( gmdate( 'm.d.y' ) );
			$diff  = $today->diff( $bday );

			update_post_meta( $post_id, '_user_profiles_dots_dob', sanitize_text_field( wp_unslash( $_REQUEST['user_profiles_dots_dob'] ) ) );
			update_post_meta( $post_id, '_user_profiles_dots_dob_age_in_years', sanitize_text_field( wp_unslash( esc_html( $diff->y ) ) ) );
		}

		if ( isset( $_REQUEST['user_profiles_dots_hobbies'] ) ) {
			update_post_meta( $post_id, '_user_profiles_dots_hobbies', sanitize_text_field( wp_unslash( $_REQUEST['user_profiles_dots_hobbies'] ) ) );
		}

		if ( isset( $_REQUEST['user_profiles_dots_interests'] ) ) {
			update_post_meta( $post_id, '_user_profiles_dots_interests', sanitize_text_field( wp_unslash( $_REQUEST['user_profiles_dots_interests'] ) ) );
		}

		if ( isset( $_REQUEST['user_profiles_dots_year_of_exp'] ) ) {
			update_post_meta( $post_id, '_user_profiles_dots_year_of_exp', sanitize_text_field( wp_unslash( $_REQUEST['user_profiles_dots_year_of_exp'] ) ) );
		}

		if ( isset( $_REQUEST['user_profiles_dots_ratings'] ) ) {
			update_post_meta( $post_id, '_user_profiles_dots_ratings', sanitize_text_field( wp_unslash( $_REQUEST['user_profiles_dots_ratings'] ) ) );
		}

		if ( isset( $_REQUEST['user_profiles_dots_no_jobs_completed'] ) ) {
			update_post_meta( $post_id, '_user_profiles_dots_no_jobs_completed', sanitize_text_field( wp_unslash( $_REQUEST['user_profiles_dots_no_jobs_completed'] ) ) );
		}
	}

}
