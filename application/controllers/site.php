<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */

    /**
     * Checks user's cookie for "is_logged_in" key.
     *
     * Redirects and exits unless "is_logged_in" is set and true
     */
    function is_logged_in()
    {
        $is_logged_in = $this->session->userdata('is_logged_in');

        if(!isset($is_logged_in) || $is_logged_in != true)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Index for Site controller.
     *
     * Redirects to document/load_last_open_document if user is
     * logged in.
     */
    public function index( $id = null )
    {
        if( $this->is_logged_in() ){
            redirect( 'document/load_last_open_document', 'refresh' );
        } else {
            $data['title'] = 'Just Write';
            $data['main_content'] = 'landing';
            $this->load->view('includes/template', $data);
        }
    }

    /**
     * Requires $id.
     *
     * Passes $id to model and loads "published" view
     * if $query returns true.
     */
    public function published( $id )
    {
        $this->load->model( 'document_model' );
        if( $query = $this->document_model->published_document_lookup( $id ) )
        {
            $data['title'] = $query[0]->title . " | published with Just Write";
            $data['document'] = $query;
            $data['main_content'] = 'published';
            $this->load->view('includes/template', $data);
        }
    }
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
