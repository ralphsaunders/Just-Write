<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Session extends CI_Controller {

    /**
     * Index of Session controller.
     *
     * Loads landing page view.
     */
    function index()
    {
        $data['title'] = 'Just Write';
        $data['main_content'] = 'landing';
        $this->load->view('includes/template', $data);
    }

    /**
     * Sets session cookies if validate() returns
     * true.
     *
     * If validate() returns array where "admin" key is
     * set and is true, validate_credentials() will redirect
     * to the admin controller.
     */
    function validate_credentials()
        // Logs the user in if they entered correct information
    {
        $this->load->model('membership_model');
        $query = $this->membership_model->validate();

        if($query){

            $data = array(
                'username' => $this->input->post('username'),
                'is_logged_in' => true
            );

            if( isset( $query['admin'] ) && $query['admin'] == true )
            {
                $data[ 'is_admin' ] = true;
            }

            $this->session->set_userdata( $data );

            if( !isset( $query['admin'] ) || $query['admin'] != true )
            {
                redirect('document/load_last_open_document', 'refresh');
            }
            else
            {
                redirect( 'admin', 'refresh' );
            }
        }
        else
        {
            $data['title'] = "Login Error | Just Write";
            $data['error'] = "You entered an incorrect username or password.";
            $data['main_content'] = 'landing';
            $this->load->view('includes/template', $data);
        }
    }

    /**
     * Destroys session data and loads the index
     * of this controller.
     */
    function destroy()
    {
        // Logs the user out and redirects to landing page
        $this->session->sess_destroy();
        $this->index();
    }
}

/* End of file session.php */
/* Location: ./application/controllers/session.php */
