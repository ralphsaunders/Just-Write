<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

  function change_theme()
  {
    $this->load->model( 'settings_model ');
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
