<?php

class Document_model extends CI_Model
{
  
  public function get_user_id()
  // Gets a user's unique ID
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
  
  public function truncate( $title, $limit )
  {
    if (strlen($title) > $limit) 
    {
      $title = substr($title, 0, $limit) . '...';
    }
    return $title;
  }

  function save_document( $data )
  {
    // Look for documents with the same id that belong to this user
    $this->db->where( 'user_id', $this->get_user_id() );
    $this->db->where( 'id', $data['id'] );
    $query = $this->db->get( 'documents' ); 

    if( $query->num_rows == 0 )
    // If there weren't any documents with this id
    {
      $store_document_insert_data = array(
        'user_id' => $this->get_user_id(),
        'title' => htmlentities( $data['title'], ENT_QUOTES, "UTF-8" ),
        'content' => htmlentities( $data['content'], ENT_QUOTES, "UTF-8" )
      );

      // Insert new data
      $insert = $this->db->insert('documents', $store_document_insert_data);

    } elseif( $query->num_rows == 1 )
    {
      // New data
      $store_document_insert_data = array(
        'user_id' => $this->get_user_id(),
        'title' => htmlentities( $data['title'], ENT_QUOTES, "UTF-8" ),
        'content' => htmlentities( $data['content'], ENT_QUOTES, "UTF-8" )
      );

      // Update the row
      $this->db->where( 'user_id', $this->get_user_id() );
      $this->db->where( 'id', $data['id'] );
      $update = $this->db->update('documents', $store_document_insert_data);
    } 

    // Give back ID of the last saved document
    $this->db->where( 'user_id', $this->get_user_id() );
    $this->db->order_by( 'last_edited', 'desc' );
    $docs = $this->db->get( 'documents' );

    if( $docs->num_rows() > 0 )
    {
      foreach( $docs->result() as $id )
      {
        $results[] = $id->id;
      }

      return $results;
    } else {
      return false;
    }
  }

  function load_document( $data )
  // Loads a document when given an id 
  {
    $this->db->where( 'user_id', $this->get_user_id() );
    $this->db->where( 'id', $data['id'] );
    $query = $this->db->get( 'documents' ); 

    if( $query->num_rows() == 1 )
    {
      $results = array();

      foreach( $query->result() as $row )
      {
        $results[] = $row;
      }

      return $results;
    } else {
      return false;
    }
  }

  function delete_document( $data )
  {
    $this->db->where( 'id', $data['id'] );
    $query = $this->db->delete( 'documents' );
 
    if( $query )
    {
      return true;
    } else {
      return false; 
    }
  }

  function load_last_open_document()
  {
    $this->db->where( 'user_id', $this->get_user_id() );
    $this->db->order_by( 'last_edited', 'desc' );
    $query = $this->db->get( 'documents' );

    if( $query->num_rows() > 0 )
    {
      $results = array();
      $i = 0;
      foreach( $query->result() as $row )
      {
        if( $i < 5 )
        {
          if( $i == 0 )
          {
            $results[] = $row;
          } else {
            $row->title = $this->truncate( $row->title, 15 );
            $results[] = $row;
          }
        } 
        $i++;
      }

      return $results;
    } else {
      return false;
    } 
  }

  function refresh_doc_list()
  {
    $this->db->where( 'user_id', $this->get_user_id() );
    $this->db->order_by( 'last_edited', 'desc' );
    $query = $this->db->get( 'documents' );

    if( $query->num_rows() > 0 )
    {

      $docs = array();
      $i = 0;
      foreach( $query->result() as $doc )
      {
        if( $i < 5 )
        {
          $doc->title = $this->truncate( $doc->title, 15 );
          $docs[] = $doc;
          $i++;
        } else {
          return $docs;
        }
      }

      return $docs;

    } else {
      
      return false;

    }
  }

  function load_all_documents()
  {
    $this->db->where( 'user_id', $this->get_user_id() );
    $this->db->order_by( 'last_edited', 'desc' );
    $query = $this->db->get( 'documents' );

    if( $query->num_rows() > 0 )
    {
      // Take it out of the MySQL stuff
      foreach ( $query->result() as $row )
      {
        $documents[] = $row;
      }

      return $documents;
    } else {
      return false;
    }
  }

  function new_html_doc( $doc )
  {
    $this->db->where( 'user_id', $this->get_user_id() );
    $this->db->where( 'id', $doc['id'] );
    $query = $this->db->get( 'exported_documents' ); 

    if( $query->num_rows() == 0 )
    // If there weren't any documents with this id
    {
      $store_document_insert_data = array(
        'user_id' => $this->get_user_id(),
        'title'   => $doc['title'],
        'content' => $doc['content']
      );

      // Insert new data
      $insert = $this->db->insert('exported_documents', $store_document_insert_data);

    } 
    elseif( $query->num_rows() == 1 )
    {
      // New data
      $store_document_insert_data = array(
        'user_id' => $this->get_user_id(),
        'title'   => $doc['title'],
        'content' => $doc['content']
      );

      // Update the row
      $this->db->where( 'user_id', $this->get_user_id() );
      $this->db->where( 'id', $doc['id'] );
      $update = $this->db->update('exported_documents', $store_document_insert_data);
    } 

    // Give back ID of the last saved document
    $this->db->where( 'user_id', $this->get_user_id() );
    $this->db->order_by( 'last_edited', 'desc' );
    $docs = $this->db->get( 'exported_documents' );

    if( $docs->num_rows() > 0 )
    {
      foreach( $docs->result() as $id )
      {
        $results[] = $id->id;
      }

      return $results;
    } else {
      return false;
    }
  } 
}
?>
