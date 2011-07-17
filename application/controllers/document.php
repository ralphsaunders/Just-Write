<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document extends CI_Controller {
  
  public function __construct()
  // This controller requires user to be logged in.
  {
    parent::__construct();
    $this->is_logged_in();
  }

  function is_logged_in()
  // Denies user entry if they aren't logged in.
  {
    $is_logged_in = $this->session->userdata('is_logged_in');

    if(!isset($is_logged_in) || $is_logged_in != true){
      redirect('session');
      exit();
    }
  }
  
  public function index()
  {
      $data['title'] = 'Just Write';
      $data['main_content'] = 'writer';
      $this->load->view('includes/template', $data);
  }

  function save()
  // Saves document. Is given a document's content + title via javascript.
  {
    if( $_POST && $_POST['content'] && $_POST['title'] )
    // ID isn't required, as if there isn't one we'll create a new row in the database
    {
      $data['id'] = $this->security->xss_clean( $_POST['id'] );
      $data['content'] = $this->security->xss_clean( $_POST['content'] );
      $data['title'] = $this->security->xss_clean( $_POST['title'] );

      $this->load->model('document_model');
      if( $query = $this->document_model->save_document( $data ) )
      {

        $result = $query[0];
        echo json_encode( $result );

      } else {
        return false;
      }
    }
  }

  function load()
  // Loads document. Is given a document's id via javascript.
  {
    if( $_POST && $_POST['id'] )
    {
      $data['id'] = $this->security->xss_clean( $_POST['id'] );

      $this->load->model('document_model');
      if( $query = $this->document_model->load_document( $data ) )
      {
        $result = array(
          'id' => $query[0]->id,
          'title' => html_entity_decode( $query[0]->title, ENT_QUOTES, "UTF-8" ),
          'content' => html_entity_decode( $query[0]->content, ENT_QUOTES, "UTF-8" )
        );

        echo json_encode( $result ); 
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  function delete_document()
  // Deletes document. Is given a document's id via javascript.
  {
    if( $_POST && $_POST['id'] )
    {
      $data['id'] = $this->security->xss_clean( $_POST['id'] );

      $this->load->model('document_model');
      if( $query = $this->document_model->delete_document( $data ) )
      {
        return true;
      } else {
        return false;
      }
    }
  }

  function load_last_open_document()
  // Loads the document the user has last edited.
  {
    $this->load->model( 'document_model' );
    if( $query = $this->document_model->load_last_open_document() )
    {
      $data['title'] = 'Just Write';
      $data['last_open_document'] = $query[0];
      $data['users_documents'] = $query;
      $data['main_content'] = 'writer';
      $this->load->view('includes/template', $data);
    } else {
      redirect('document', 'refresh'); 
    }
  }

  function refresh_doc_list()
  // Gets a user's latest documents. Passes data to javascript with a json response.
  {
    if( $_POST && $_POST['refresh'] )
    {
      $this->load->model('document_model');
      if( $query = $this->document_model->refresh_doc_list() )
      {
        echo json_encode( $query ); 
      } else {
        return false;
      }
    }
  }
  
  function markdown_to_html()
  {
    if( $_POST && $_POST['content'] && $_POST['id'] && $_POST['title'] )
    {
      $content = $this->security->xss_clean( $_POST['content'] );
      $title   = $this->security->xss_clean( $_POST['title'] );

      $this->load->helper( 'markdown' );
      $doc = array(
        'id'      => $_POST['id'],
        'title'   => '<h1>' . $title . '</h1>',
        'content' => markdown( $content )
      );

      $this->load->model( 'document_model' );
      if( $query = $this->document_model->new_html_doc( $doc ) )
      {
        $exported_doc = $doc; 
        $exported_doc['title']   = htmlspecialchars( $exported_doc['title'] );
        $exported_doc['content'] = htmlspecialchars( $exported_doc['content'] );
        echo json_encode( $exported_doc );
      }

    }
    else
    {
      return false;
    }
  }

  function load_all_documents()
  {
    if( $_POST && $_POST['loadAll'] )
    {
      $this->load->model('document_model');
      if( $query = $this->document_model->load_all_documents() )
      {
        echo json_encode( $query ); 
      } else {
        return false;
      }
    }
  }

  function publish()
  {
    if( $_POST && $_POST['content'] && $_POST['id'] && $_POST['title'] )
    {
      $content = $this->security->xss_clean( $_POST['content'] );
      $title   = $this->security->xss_clean( $_POST['title'] );

      $this->load->helper( 'markdown' );
      $doc = array(
        'id'      => $_POST['id'],
        'title'   => $title,
        'content' => markdown( $content )
      );

      $this->load->model( 'document_model' );
      if( $query = $this->document_model->publish( $doc ) )
      {
        echo json_encode( $query[0] );
      }
    
    }
    else
    {
      return false;
    }
  }

}

/* End of file session.php */
/* Location: ./application/controllers/document.php */
