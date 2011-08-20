<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

  /**
  * Index Page for this controller.
  *
  * Maps to the following URL
  * 		http://example.com/index.php/admin
  *	- or -  
  * 		http://example.com/index.php/admin/index
  *	- or -
  * 
  * So any other public methods not prefixed with an underscore will
  * map to /index.php/welcome/<method_name>
  * @see http://codeigniter.com/user_guide/general/urls.html
  */

  function _is_admin()
  {
    $is_admin = $this->session->userdata('is_admin');

    if(!isset( $is_admin ) || $is_admin != true)
    {
      return false;
    } else {
      return true;
    }
  }

  public function index()
  {
    if( !$this->_is_admin() ){
      redirect( 'site', 'refresh' ); 
    } else {
      $data['title'] = 'Admin panel | Just Write';
      $data['main_content'] = 'admin';
      $this->load->view('includes/template', $data);
    }
  }

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */
