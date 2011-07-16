<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

  

  function index()
  {
    redirect( 'document' ); 
  }

  function create_member()
  {
    // Checks data is valid then passes to membership model 
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

  function forgot_password()
  {
    // Checks data is valid then passes to membership model 
    $this->load->library('form_validation');
    // Field name, error message, validation rules
    $this->form_validation->set_rules('username', 'username', 'trim|required');
    $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');

    $this->form_validation->set_message( 'required', 'Your %s must be the one you signed up with and is required' );
    $this->form_validation->set_message( 'valid_email', 'Your email must be the one you signed up with and is required' );
    
    if( $this->form_validation->run() == FALSE )
    // If the information entered was not valid 
    {
      $data['title'] = 'Forgot Password | Just Write';
      $data['main_content'] = 'forgot_password';
      $this->load->view('includes/template', $data);
    } 
    else
    {
      $this->load->model('membership_model');
      if( $query = $this->membership_model->generate_password_reset_key() )
      // If we were able to create and store a password reset key successfully
      {
        $this->load->library( 'email' );
        
        $this->email->from( 'write@ralphsaunders.co.uk', 'Ralph Saunders' );
        $this->email->to( $this->input->post( 'email' ) );

        $this->email->subject( 'Password reset for ' . $this->input->post( 'username' ) );
        $message = "Hey there " . $this->input->post( 'username' ) . ", \n\nWe've received a password reset request for {unwrap}" . base_url() . "{/unwrap}. \n\nTo reset your password, click this link: {unwrap}" . base_url() . "member/key_match/" . $this->input->post( 'username' ) . "/" . $query . "/{/unwrap}. \n\nIf the page asks for a security key yours is: {unwrap}$query{/unwrap} \n\nHope you're enjoying Just Write, \nRalph ";
        $this->email->message( $message );

        $sent = $this->email->send();

        if( $sent )
        // If the email got sent
        {
          $data['title'] = 'Password Reset Sent | Just Write';
          $data['main_content'] = 'forgot_password';
          $data['mail_sent'] = true;
          $this->load->view('includes/template', $data);
        }
        else
        {
          $data['title'] = 'Password Reset Failed to Send | Just Write';
          $data['main_content'] = 'forgot_password';
          $data['error'] = "We couldn't send an email to that address"; 
          $data['debug'] = $this->email->print_debugger();
          $this->load->view('includes/template', $data);
        }
      }
      else
      {
        $data['title'] = 'We couldn\'t find your information | Just Write';
        $data['main_content'] = 'forgot_password';
        $data['error'] = "We couldn't find that username and email combination. Are you sure they're correct?";
        $this->load->view('includes/template', $data);
      }
    }
  }

  function key_match( $username, $key = null )
  {
    // Check for nasties
    $this->security->xss_clean( $username ); 
    $this->security->xss_clean( $key ); 

    // Pass to model
    $this->load->model('membership_model');
    if( $query = $this->membership_model->key_match( $username, $key ) )
    // If they key matched
    {
      $data['title'] = 'Enter New Password | Just Write';
      $data['main_content'] = 'new_password';
      $data['key'] = $key;
      $data['username'] = $username;
      $this->load->view('includes/template', $data);
    }
    else
    {
      $data['title'] = 'Key expired | Just Write';
      $data['main_content'] = 'forgot_password';
      $data['error'] = "Looks like your key has expired, fill out your details to receive a new one";
      $this->load->view('includes/template', $data);
    } 
  }

  function new_password()
    // Underscore before function name stops it being served
    // via a url request 
  {
    // Checks data is valid then passes to membership model 
    $this->load->library('form_validation');
    // Field name, error message, validation rules
    $this->form_validation->set_rules('username', 'username', 'trim|required');
    $this->form_validation->set_rules('key', 'key', 'required');
    $this->form_validation->set_rules('password', 'password', 'trim|required');
    $this->form_validation->set_rules('confirm_password', 'password', 'trim|required|matches[password]');
    
    $this->form_validation->set_message( 'required', 'Your %s is required' );
    $this->form_validation->set_message( 'matches', 'The %s\'s you entered must match' );
    
    if( $this->form_validation->run() == FALSE )
    // If the information entered was not valid 
    {
      $data['title'] = 'Forgot Password | Just Write';
      $data['main_content'] = 'new_password';
      $data['key'] = $this->input->post( 'key' );
      $data['username'] = $this->input->post( 'username' );
      $this->load->view('includes/template', $data);
    }
    else
    {
      $this->load->model('membership_model');
      if( $query = $this->membership_model->update_password() )
      {
        $data['title'] = 'Password Updated Successfully | Just Write';
        $data['main_content'] = 'new_password';
        $data['key'] = $this->input->post( 'key' );
        $data['username'] = $this->input->post( 'username' );
        $data['confirmation'] = 'Your password has been updated successfully, you can <a href="' . base_url() . '" title="Login">login</a> now.';
        $this->load->view('includes/template', $data);
      }
      else
      {
        $data['title'] = 'We couldn\'t update your password | Just Write';
        $data['main_content'] = 'new_password';
        $data['error'] = 'We couldn\'t update your password with the information you provided, are you sure it was correct?';
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
