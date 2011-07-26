<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Session extends CI_Controller {

  function index()
  {
    $data['title'] = 'Just Write';
    $data['main_content'] = 'landing';
    $this->load->view('includes/template', $data);
  }
  
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
      
      $this->session->set_userdata($data);
      
      redirect('document/load_last_open_document', 'refresh'); 
    } else {
      $data['title'] = "Login Error | Just Write";
      $data['error'] = "You entered an incorrect username or password.";
      $data['main_content'] = 'landing';
      $this->load->view('includes/template', $data);
    }
  }

  function destroy()
  {
    // Logs the user out and redirects to landing page
    $this->session->sess_destroy();
    $this->index();
  }
}

/* End of file session.php */
/* Location: ./application/controllers/session.php */
