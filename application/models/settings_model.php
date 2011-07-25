<?php

class Settings_model extends CI_Model
{

  public function get_user_id()
  // Returns a user's unique ID
  {
    $this->db->where('username', $this->session->userdata('username'));
    $this->db->select('id');
    $this->db->from('users');
    $query = $this->db->get();

    if ($query->num_rows == 1) {
      foreach($query->result() as $row){
        return $row->id;
      }
    } 
  }

  public function create_users_settings()
  // Creates a user's settings
  {
    $this->db->where( 'user_id', $this->get_user_id() );
    $query = $this->db->get( 'settings' );

    if( $query->num_rows() == 0 )
    {
      $user = array(
        'user_id' => $this->get_user_id()
      );

      $this->db->insert( 'settings', $user );
    }
  }

  function fetch_users_settings()
  // fetches a user's settings
  {
    $this->db->where( 'user_id', $this->get_user_id() );
    $query = $this->db->get( 'settings' );

    if( $query->num_rows() == 1 )
    {
      $user = $query->row();
      return $user;
    }
    else
    {
      return false;
    }
  }

  function change_theme()
  // Toggles theme
  {
    $this->db->where( 'user_id', $this->get_user_id() );
    $query = $this->db->get( 'settings' );

    if( $query->num_rows() == 1 )
    {
      $user = $query->row(); 

      if( $user->theme == null || $user->theme == 0 )
      // If the user's theme is set to the default
      {
        $theme = array(
          'theme' => 1
        );
      }
      else
      {
        $theme = array(
          'theme' => 0
        );
      }

      $this->db->where( 'user_id', $this->get_user_id() );
      $this->db->update( 'settings', $theme );

      return true;
    }
    else
    {
      $this->create_users_settings();
      $this->change_theme();
    }
  }

}
?>
