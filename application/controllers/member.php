<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

  

  function index()
  {
    redirect( 'document' ); 
  }

  function create_member()
  {
    // Checks data for nasties and sents on to membership model 
    $this->load->library('form_validation');
    // Field name, error message, validation rules
    $this->form_validation->set_rules('username', 'Username', 'trim|required');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');

    if($this->form_validation->run() == FALSE) {
      $data['title'] = 'Signup';
      $data['main_content'] = 'signup';
      $this->load->view('includes/template', $data);
    } else {
      $this->load->model('membership_model');
      if($query = $this->membership_model->create_member()){
        $data['title'] = 'Write Web App';
        $data['main_content'] = 'landing';
        $data['confirmation'] = "Your account was created, you can now login";
        $this->load->view('includes/template', $data);
      } else {
        $error = 'Username or Email is already taken';
        $data['error'] = $error;
        $data['title'] = 'Signup';
        $data['main_content'] = 'signup';
        $this->load->view('includes/template', $data);
      }
    }
  }

  function apply_for_invitation()
    // Not hooked up to anything, but I'll leave it here incase I run into scaling issues
  {
    $email = $this->input->post('address');
    
    $email->load->library('form_validation');
    $email->form_validation->set_rules('address', 'Email is required', 'trim|required|valid_email');

    if($email->form_validation->run() == FALSE) {
      echo json_encode("failed");
    } else {
      echo json_encode($email);
    }
  }
}

/* End of file member.php */
/* Location: ./application/controllers/member.php */
