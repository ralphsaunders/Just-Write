<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

  public function __construct()
  // This controller requires user to be logged in.
  {
    parent::__construct();
    $this->is_logged_in();
  }

  function is_logged_in()
  // Denies user entry if they aren't logged in.
  {
    $is_logged_in = $this->session->userdata('is_logged_in');

    if(!isset($is_logged_in) || $is_logged_in != true){
      redirect('session');
      exit();
    }
  }

  function change_theme()
  {
    $this->load->model( 'settings_model' );
    if( $query = $this->settings_model->change_theme() )
    {
      echo json_encode( 'true');
    }
    else
    {
      return false; 
    }

  }

}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */
