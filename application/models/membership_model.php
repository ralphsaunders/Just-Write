<?php

class Membership_model extends CI_Model
{
  public function hash_password($password)
  {
      $config['encryption_key'] = "pPjL3CadR404692mR8wJKdXCXKe6ECPL";
      return hash_hmac("sha512", $password, $config['encryption_key']);
  }

  function validate()
  {
    $this->db->where('username', $this->input->post('username'));
    $this->db->where('password', $this->hash_password($this->input->post('password')));
    $query = $this->db->get('users');

    if($query->num_rows == 1){
      return true;
    } else { 
      return false;
    }
  }

  function create_member()
  {
    $this->db->where('username', $this->input->post('username'));
    $query = $this->db->get('users');

    if($query->num_rows == 0){
      $this->db->where('email', $this->input->post('email'));
      $query = $this->db->get('users');
      
      if($query->num_rows == 0){

        $new_member_insert_data = array(
          'username' => $this->input->post('username'),
          'email' => $this->input->post('email'),
          'password' => $this->hash_password($this->input->post('password')),
        );

        $insert = $this->db->insert('users', $new_member_insert_data);
        return $insert;
      } else {
        return false; 
      }
    } else {
      return false; 
    }
  }

  function store_email()
  // For when we go alpha 
  {
    $this->db->where('email', $this->input->post('email'));
    $query = $this->db->get('invitees');


    if($query->num_rows == 0){
      $email_to_invite = array(
        'email' => $this->input->post('email')
      );

      $insert = $this->db->insert('invitees', $email_to_invite);
      return $insert;
    } else {
      return false;
    }
  }
}
?>
