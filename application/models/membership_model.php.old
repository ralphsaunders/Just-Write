<?php

class Membership_model extends CI_Model
{
  public function hash_password($password)
  {
    return hash_hmac( "sha512", $password, $this->config->item('encryption_key') );
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

  function generate_password_reset_key()
  {
    if ( $this->input->post( 'username' ) && $this->input->post( 'email' ) )
    {
      /**
       * Salt uses current date based on day to generate a key.
       * This means the key expires at the end of the current day. 
       */

      // Todays Date
      $date = date( 'dmY' );

      // Generate Salt
      $salt = hash_hmac( "sha512", $date, $this->input->post( 'email' ) );

      // Generate Key
      $key = hash_hmac( "sha512", $this->input->post( 'username' ), $salt );

      // Lookup relevant table 
      $this->db->where( 'username', $this->input->post('username') );
      $this->db->where( 'email', $this->input->post('email') );
      $query = $this->db->get( 'users' );

      if( $query->num_rows() == 1 )
      // If we got a match for username + password in the same row
      {
        $reset_key = array(
          'reset_key' => $key
        );

        $this->db->where( 'email', $this->input->post( 'email' ) );
        $this->db->update( 'users', $reset_key );

        return $key;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }

  function key_match( $username, $key )
  {
    $this->db->where( 'username', $username );
    $this->db->where( 'reset_key', $key );
    $query = $this->db->get( 'users' );

    if( $query->num_rows() == 1 )
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  function update_password()
  {
    $this->db->where( 'username', $this->input->post( 'username' ) );
    $this->db->where( 'reset_key', $this->input->post( 'key' ) );
    $query = $this->db->get( 'users' );

    if( $query->num_rows() == 1 )
    {
      $new_password = array(
        'password'  => $this->hash_password( $this->input->post('password') ),
        'reset_key' => null
      );

      $this->db->where( 'username', $this->input->post( 'username' ) );
      $this->db->update( 'users', $new_password );

      return true;
    }
    else
    {
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
