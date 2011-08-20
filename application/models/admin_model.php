<?php

class Admin_model extends CI_Model
{

  /**
   * Returns the number of members that have
   * registered in the past 24 hours
   */
  function members_registered_past_day()
  {
    $this->db->where( 'timestamp >', date('Y-m-d') );
    $query = $this->db->get( 'users' );

    if( $query->num_rows() > 0 )
    {
      return $query->num_rows(); 
    }
    else
    {
      return false;
    }
  }

  /**
   * Returns the number of documents that have
   * edited and created in the past 24 hours
   */
  function documents_edited_past_day()
  {
    
    $this->db->where( 'last_edited >', date('Y-m-d') );
    $query = $this->db->get( 'documents' );

    if( $query->num_rows() > 0 )
    {
      return $query->num_rows(); 
    }
    else
    {
      return false;
    }
    
  }

}
