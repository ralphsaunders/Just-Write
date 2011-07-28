<?php

class Membership_model extends CI_Model
{
  public function old_hash_password($password)
  {
    return hash_hmac( "sha512", $password, $this->config->item('encryption_key') );
  }
  
  public function hash_password( $password, $salt = null )
  {
    // Salt
    if( !isset( $salt ) )
    {
      $type = '$2a';
      $num_rounds = '$10';
      $random_string = '$' . $this->generate_random_string() . '$';
      $salt = $type . $num_rounds . $random_string;
    }
    
    // Actual hash
    return array('salt' => $salt, 'hash' => crypt( $password, $salt )) ;   
  }


  public function generate_random_string() 
  {
    return substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(), mt_rand()))), 0, 22);  
  }

  function validate()
  {
    $this->db->where('username', $this->input->post('username'));
    $query = $this->db->get('users');

    if($query->num_rows() == 1){
      $user = $query->row();

      if( isset( $user->migrated ) != NULL )
      {
        $hash = $this->hash_password( $this->input->post( 'password' ), $user->salt );
        if( $hash['hash'] == $user->password )
        // Validate using new hash algo
        {
          return true;
        }
        else
        {
          return false;
        }
      }
      else
      {
        if( $this->old_hash_password( $this->input->post( 'password' ) ) == $user->password )
        // Validate using old algo
        {
          // Migrate user
          $this->migrate_user();
          
          if( $this->validate() )
          // Validate again
          {
            return true;
          } else {
            return false;
          }
        
        }
        else
        {
          return false;
        }
      }
    } 
    else 
    { 
      return false;
    }
  }
  
  public function migrate_user()
  {
    // Encode password using new hash
    $migration = $this->hash_password( $this->input->post('password') );
    $hash = $migration['hash'];
    $salt = $migration['salt'];
    
    $data = array(
      'password' => $hash,
      'salt'     => $salt,
      'migrated' => true
    );
    
    $this->db->where( 'username', $this->input->post( 'username' ) );
    $this->db->update( 'users', $data );
  }
  
  function create_user()
  {
    $this->db->where('username', $this->input->post('username'));
    $this->db->where('email', $this->input->post('email'));
    $query = $this->db->get('users');

    if($query->num_rows() == 0)
    {
      $password = $this->hash_password( $this->input->post( 'password' ) );
      
      $new_member_insert_data = array(
        'username' => $this->input->post('username'),
        'email'    => $this->input->post('email'),
        'password' => $password['hash'],
        'salt'     => $password['salt'],
        'migrated' => true
      );

      $this->db->insert('users', $new_member_insert_data);
      return true;
    } 
    else
    {
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
      $password = $this->hash_password( $this->input->post( 'password' ) );
      $new_password = array(
        'password'  => $password['hash'],
        'salt'      => $password['salt'],
        'migrated'  => true,
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

}
