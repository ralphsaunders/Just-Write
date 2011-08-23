<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

    /**
     * Constructor for Settings controller.
     *
     * Requires is_logged_in() to return true.
     */
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
    }

    /**
     * Checks user's cookie for "is_logged_in" key.
     *
     * Redirects and exits unless "is_logged_in" is set and true
     */
    function is_logged_in()
    {
        $is_logged_in = $this->session->userdata('is_logged_in');

        if(!isset($is_logged_in) || $is_logged_in != true){
            redirect('session');
            exit();
        }
    }

    /**
     * Called via javascript.
     *
     * Returns json encoded bool
     */
    function change_theme()
    {
        $this->load->model( 'settings_model' );
        if( $query = $this->settings_model->change_theme() )
        {
            echo json_encode( true );
        }
        else
        {
            echo json_encode( false );
        }

    }

}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */
