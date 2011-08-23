<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document extends CI_Controller {

    /**
     * Constructor for Document Controller.
     *
     * Requires is_logged_in() to return true.
     */
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
    }

    /**
     * Checks user's cookie for "is_logged_in" key.
     * Redirects and exits unless "is_logged_in" is set and true
     */
    function is_logged_in()
    {
        $is_logged_in = $this->session->userdata('is_logged_in');

        if(!isset($is_logged_in) || $is_logged_in != true){
            redirect('session');
            exit();
        }
    }

    /**
     * Index for document controller.
     *
     * Loads writer view.
     */
    public function index()
    {
        $data['title'] = 'Just Write';
        $data['main_content'] = 'writer';
        $this->load->view('includes/template', $data);
    }

    /**
     * Cleans document's data and passes onto the database.
     *
     * Called via javascript using $_POST method.
     *
     * If an ID is not received a new document will be created
     * the model will create a new document in the database.
     *
     * Returns a json encoded ID of the just saved document.
     */
    function save()
    {
        if( $_POST && $_POST['content'] && $_POST['title'] )
        {
            $data['id'] = $this->security->xss_clean( $_POST['id'] );
            $data['content'] = $this->security->xss_clean( $_POST['content'] );
            $data['title'] = $this->security->xss_clean( $_POST['title'] );

            $this->load->model('document_model');
            if( $query = $this->document_model->save_document( $data ) )
            {
                $result = $query[0];
                echo json_encode( $result );
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * Cleans document's ID and passes onto the database.
     *
     * Called via javascript using the $_POST method.
     *
     * An ID must be received otherwise the method will
     * return false.
     *
     * Returns a json encoded array of the document's
     * information.
     */
    function load()
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

    /**
     * Cleans a document's ID and passes into the model.
     *
     * Called via javascript using the $_POST method.
     *
     * An ID must be received or the method will return
     * false.
     *
     * Returns bool dependant on success.
     */
    function delete_document()
    {
        if( $_POST && $_POST['id'] )
        {
            $data['id'] = $this->security->xss_clean( $_POST['id'] );

            $this->load->model('document_model');
            if( $query = $this->document_model->delete_document( $data ) )
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * Passes to view:
     *      - A user's last edited document
     *      - A user's 5 most recently edited documents.
     *      - A user's settings.
     *
     * Redirects to index() if user has no documents.
     *
     * Called by standard http request.
     */
    function load_last_open_document()
    {
        $this->load->model( 'document_model' );
        if( $query = $this->document_model->load_last_open_document() )
        {
            $data['last_open_document'] = $query[0];
            $data['users_documents'] = $query;

            $this->load->model( 'settings_model' );
            if( $settings = $this->settings_model->fetch_users_settings() );
            {
                $data['settings'] = $settings;
            }

            $data['title'] = 'Just Write';
            $data['main_content'] = 'writer';
            $this->load->view('includes/template', $data);
        }
        else
        {
            redirect('document', 'refresh');
        }
    }

    /**
     * Called via javascript using the $_POST method.
     *
     * Returns a json encoded array containing a
     * user's most recently edited documents.
     */
    function refresh_doc_list()
    {
        if( $_POST && $_POST['refresh'] )
        {
            $this->load->model('document_model');
            if( $query = $this->document_model->refresh_doc_list() )
            {
                echo json_encode( $query );
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * - Cleans document's title and content.
     * - Utilises markdown helper to convert document's
     *   content to HTML.
     * - Passes converted document to model.
     *
     * Called via javascript using $_POST method.
     * Requires document's content, title, and ID otherwise
     * will return false.
     *
     * Returns a json encoded array of a document, the title
     * and content of which have been escaped.
     */
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

    /**
     * Called via javascript using $_POST method.
     *
     * Requires key "loadAll" to be set.
     *
     * Returns a json encoded array of a user's
     * documents.
     */
    function load_all_documents()
    {
        if( $_POST && $_POST['loadAll'] )
        {
            $this->load->model('document_model');
            if( $query = $this->document_model->load_all_documents() )
            {
                echo json_encode( $query );
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * - Cleans document's title and content.
     * - Utilises markdown helper to convert document's
     *   content to HTML.
     * - Passes converted document to model.
     *
     * Called via javascript using the $_POST method.
     *
     * Requires a document's content, title, and ID or
     * it returns false.
     *
     * Returns json encoded array of the just
     * published document.
     */
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

/* End of file document.php */
/* Location: ./application/controllers/document.php */
