<?php 


class PhoneNumberSettings {
	
	/**
	* Holds the values to be used in the fields callbacks
	*/
	private $options;		

	/**
	* Start up
	*/
	public function __construct()
	{
		add_action('admin_menu', array( $this, 'add_plugin_page' ) );
		add_action('admin_init', array( $this, 'page_init' ) );
	}
	

	/**
	* Add options page
	*/
	public function add_plugin_page()
	{

	 // This page will be an Admin menu
	 add_menu_page(
		   'Settings Admin', 
		   'Phone Number Settings', 
		   'manage_options', 
		   'phone-setting-admin', 
			array( $this, 'create_admin_page' ),
		   'dashicons-phone',
		   '30.2'
		 );      
    }


    /**
     * Options page callback
     */
    public function create_admin_page() 
	{

        // add option
		add_option( 'phone_option' );
		
		// Set class property
        $this->options = get_option( 'phone_option' );

        ?>

        <div class="wrap">
            <h2><span class="dashicons dashicons-phone"></span>Phone Number Settings</h2>
            <form method="post" action="options.php">
            <?php 
                // This prints out all hidden setting fields
                settings_fields( 'phone_option_group' );
                do_settings_sections( 'phone-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php 
    } 

    /**
     * Register and add settings
     */
    public function page_init() {        

        register_setting(
            'phone_option_group', // Option group
            'phone_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Phone Number Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'phone-setting-admin' // Page
        );		

		add_settings_field(
            'title', // ID
            'Title', // Title 
            array( $this, 'title_callback' ), // Callback
            'phone-setting-admin', // Page
            'setting_section_id' // Section 
        );		

        add_settings_field(
            'id_number', // ID
            'Phone Number', // Title 
            array( $this, 'id_number_callback' ), // Callback
            'phone-setting-admin', // Page
            'setting_section_id' // Section  
        );              

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {

        $new_input = array();

        if( isset( $input['id_number'] ) )

            $new_input['id_number'] = sanitize_text_field(  $input['id_number'] );



        if( isset( $input['title'] ) )

            $new_input['title'] = sanitize_text_field( $input['title'] );


        return $new_input;

    }


    /** 
     * Print the Section text
     */
    public function print_section_info() {

        print 'These settings work with WooCommerce content-product.php custom template page. <br />Enter your settings below:';

    }



    /** 
     * Get the settings option array and print one of its values
     */
    public function id_number_callback() {

        printf(

            '<input type="text" id="id_number" name="phone_option[id_number]" value="%s" />',

            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''

        );

    }



    /**
     * Get the settings option array and print one of its values
     */
    public function title_callback() {

        printf(

            '<input type="text" id="title" name="phone_option[title]" value="%s" />',

            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''

        );

    }
}

if( is_admin() ){ 
	$phone_settings = new PhoneNumberSettings();
}
