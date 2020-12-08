<?php
final class LiveReview {

	protected static $instance = null;

	public static function instance() {
		if (self::$instance == null){
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init_hooks') );
	}

	public function init_hooks() {
		add_action ('init', array( $this, 'create_cpt') );
		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );

		add_filter('manage_live-review_posts_columns',  array( $this, 'show_columns' ) );
		add_action('manage_live-review_posts_custom_column' , array( $this, 'set_custom_columns_data'), 10, 2 );

		add_shortcode('live-review-form', array( $this, 'create_review_form' ) );
		add_shortcode('live-review-slideshow', array( $this, 'create_review_slideshow' ) );

		add_action( 'admin_menu', array( $this, 'setShortcodePage' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'my_enqueue_script' ) );
		add_action( 'wp_ajax_live_review_submit', array( $this, 'live_review_submit' ) );
		add_action( 'wp_ajax_nopriv_live_review_submit', array( $this, 'live_review_submit' ) );
	}

	public function live_review_submit() {
		
   		if( ! DOING_AJAX ){
			return $this->return_json( 'error' );
		}

		//sanitize data
		$name =  sanitize_text_field( $_POST['name']);
		$email  =  sanitize_text_field( $_POST['email']);
		$message  =  sanitize_text_field( $_POST['message']);

		$meta_value = array(
			'name' => $name,
			'email' => $email,
			'approved' => 0,
			'featured' => 0,
		);

		//store data into live_review CPT
		$client_review = array(
		  'post_title'    => 'Review from ' . $name,
		  'post_content'  => $message,
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'post_type' => 'live-review',
		  'meta_input' =>  array(
		  		'_live_review_key' => $meta_value
		  )
		);
		 
		//var_dump($client_review);
		$review_insert = wp_insert_post( $client_review );
		
		//send response
		if ( ! $review_insert ){
			return $this->return_json( 'error' );
		}

		return $this->return_json( 'success' );
	}

	public function return_json( $status )
	{
		$response = array(
			'status' => $status,
		);

		wp_send_json( $response );
		wp_die();
	}

	public function my_enqueue_script() {
		wp_enqueue_style( 'slideshow-css', LIVE_REVIEW_URL . '/assets/slideshow-css.css' );

   		// wp_register_script( 'form-handle', LIVE_REVIEW_URL . '/assets/form-handle.js', array('jquery'), '', true);
	   
	   	wp_localize_script( 'form-handle', 'jsData',[
	        'ajaxurl' => admin_url('admin-ajax.php')
	    ]);

	    wp_enqueue_script( 'form-handle' );

	    wp_deregister_script('jquery');

	    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);

	    wp_enqueue_script('slideshow-handle', LIVE_REVIEW_URL . '/assets/slideshow-handle.js', array('jquery'), null, true);



	}
	

	public function create_cpt() 
	{
		register_post_type('live-review',
	        array(
	            	'labels'      => array(
	                'name'          => 'Live Review',
	                'singular_name' => 'Live Review',
	                'menu_icon' => 'dashicons-testimonial',
	                'exclude_from_search' => true,
	                'publicly_queryable' => false,
	                'support' =>array( 'title', 'editor' )
	            ),
	                'public'      => true,
	                'has_archive' => false,
	        )
    	);
	}

	public function register_meta_boxes() 
	{
		 add_meta_box(
		    'author',      // Unique ID
		   	esc_html__( 'Author', 'lrplg' ),    // Title
		    array( $this, 'render_meta_box' ) ,   // Callback function
		    'live-review',         // Admin page (or post type)
		    'side',         // Context
		    'default'         // Priority
		 );
	}

	function render_meta_box( $post ) 
	{ 
		wp_nonce_field( basename( __FILE__ ), 'live_review_nonce' ); 
		$data = get_post_meta( $post->ID, '_live_review_key', true);
		$name = isset( $data['name'] ) ? $data['name'] : "";//var_dump ($name);
		$email = isset( $data['email'] ) ? $data['email'] : "";
		$approved = ( $data['approved'] ) ? "checked" : "";
		$featured = ( $data['featured'] ) ? "checked" : "";
		?>

		<div class="form-group">
		    <label for="name">Author:</label>
		    <input type="text" class="form-control" id="name" name="name" value="<?=esc_attr($name)?>" />
		</div>
		<br>
		<div class="form-group">
		    <label for="email">Email address:</label>
		    <input type="email" class="form-control" id="email" name="email" value="<?=esc_attr($email)?>" />
		</div>
		<br>
		<div class="form-group">
			<input type="checkbox" class="form-control" id="approved" name="approved" <?=esc_attr($approved)?> />
		    <label for="approved">Approved </label>
		</div>
		<br>
		<div class="form-group">
			<input type="checkbox" class="form-control" id="featured" name="featured" <?=esc_attr($featured)?> />
		    <label for="featured">Featured </label> 
		</div>

	<?php 
	}


	public function save_meta_box( $post_id ) 
	{	
		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['live_review_nonce'] ) || !wp_verify_nonce( $_POST['live_review_nonce'], basename( __FILE__ ) ) )
		{
		    return $post_id;
		}
		
		 /* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( 'edit_posts', $post_id ) ){
			
		    return $post_id;
		}

		$meta_value = array(
			'name' => sanitize_text_field( $_POST['name'] ),
			'email' => sanitize_text_field( $_POST['email'] ),
			'approved' => isset( $_POST['approved'] ) ? 1 : 0,
			'featured' => isset( $_POST['featured'] ) ? 1 : 0,
		);

		update_post_meta( $post_id, '_live_review_key', $meta_value );
	}

	public function show_columns($columns) 
	{

		unset( $columns['date']   );
	     
	    $columns['name']     = 'Author Name';//var_dump ($columns['name']);
	    $columns['approved']     = 'Approved';
	    $columns['featured']     = 'Featured';
	 
	    return $columns;
	}

	public function set_custom_columns_data( $column, $post_id )
	{
		$data = get_post_meta( $post_id, '_live_review_key', true);
		$name = isset( $data['name'] ) ? $data['name'] : "";//var_dump ($name);
		$email = isset( $data['email'] ) ? $data['email'] : "";
		$approved = ( $data['approved'] ) ? "Yes" : "No";
		$featured = ( $data['featured'] ) ? "Yes" : "No";

		switch ( $column ) {
 
	        case 'name' :

	            echo $name . "\n" . $email;
	            break;

	        case 'approved' :

	            echo $approved;
	     		break;

	        case 'featured' :
	            echo $featured;
	            break;
    	}

	}


	public function create_review_form()
	{
		ob_start();
		echo '<link rel="stylesheet" href="' . LIVE_REVIEW_URL . '/assets/form-css.css" >';
		require_once(LIVE_REVIEW_PATH . 'template/review-form.php');
		echo "<script src=\"  LIVE_REVIEW_URL  '/assets/form-handle.js\">  </script>";
		return ob_get_clean();
	}

	public function create_review_slideshow()
	{
		ob_start();
		echo '<link rel="stylesheet" href="' . LIVE_REVIEW_URL . '/assets/slideshow-css.css" >';
		require_once( LIVE_REVIEW_PATH . 'template/review-slideshow.php');
		echo '<script src="' .  LIVE_REVIEW_URL  . '/assets/slideshow-handle.js">  </script>';
		return ob_get_clean();
	}

	public function setShortcodePage()
	{
		add_submenu_page(
		    'edit.php?post_type=live-review',
		    __( 'Shortcodes', 'lrplg' ),
		    __( 'Shortcodes', 'lrplg' ),
		    'manage_options',
		    'live_review_shortcode',
		    'mt_settings_page'
		);

		function mt_settings_page() 
		{
	       	return require_once( __DIR__ .'/../template/live-review.php' );
	    }
	}

}