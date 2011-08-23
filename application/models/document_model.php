<?php

class Document_model extends CI_Model
{

    /**
     * Returns a user's id from their record according
     * to the username given.
     */
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

    /**
     * Returns truncated string when given $title and
     * $limit (INT).
     */
    public function truncate( $title, $limit )
    {
        if (strlen($title) > $limit)
        {
            $title = substr($title, 0, $limit) . '...';
        }
        return $title;
    }

    /**
     * Saves document when given $data.
     *
     * Creates new record if a document can't be
     * found with given ID.
     *
     * Returns ID of last modified record.
     */
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
                'title' => $data['title'],
                'content' => $data['content']
            );

            // Insert new data
            $insert = $this->db->insert('documents', $store_document_insert_data);

        }
        elseif( $query->num_rows == 1 )
        {
            // New data
            $store_document_insert_data = array(
                'user_id' => $this->get_user_id(),
                'title' => $data['title'],
                'content' => $data['content']
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
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns an array containing a document's
     * information when given said document's ID.
     */
    function load_document( $data )
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
        }
        else
        {
            return false;
        }
    }

    /**
     * Removes record from database when given
     * a record's ID.
     *
     * Returns bool.
     */
    function delete_document( $data )
    {
        $this->db->where( 'id', $data['id'] );
        $query = $this->db->delete( 'documents' );

        if( $query )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns an array of documents belonging to
     * a user when given said user's ID.
     *
     * First document in array does not have a
     * truncated title.
     *
     * Returns false when a user has no
     * documents.
     */
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
                if( $i <= 5 )
                {
                    if( $i == 0 )
                        // Don't truncate the first document's title as it's
                        // the one that's going to be displayed by default.
                    {
                        $results[] = $row;
                    }
                    else
                    {
                        $row->title = $this->truncate( $row->title, 15 );
                        $results[] = $row;
                    }
                }
                $i++;
            }

            return $results;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns an array of documents belonging to
     * a user when given said user's ID.
     *
     * All document titles are truncated.
     *
     * Returns false if user has no documents.
     */
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
                }
                else
                {
                    return $docs;
                }
            }
            return $docs;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns array of user's documents including a
     * user's published documents, which are ordered
     * by the time the record was last modified where the
     * most recently modified document is first.
     *
     * Returns false if a user has no documents
     */
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
                $documents['docs'][] = $row;
            }

        }
        else
        {
            return false;
        }

        $this->db->where( 'user_id', $this->get_user_id() );
        $this->db->order_by( 'export_timestamp', 'desc' );
        $query = $this->db->get( 'published_documents' );

        if( $query->num_rows() > 0 )
        {
            // Take it out of the MySQL stuff
            foreach ( $query->result() as $row )
            {
                $documents['published_docs'][] = $row;
            }

        }

        return $documents;

    }

    /**
     * Creates new record when supplied with an ID
     * that can't be found in the database.
     *
     * Updates record when supplied with an ID that
     * is in the database.
     *
     * Returns an array containing the ID of the last
     * modified record.
     */
    function new_html_doc( $doc )
    {
        $this->db->where( 'user_id', $this->get_user_id() );
        $this->db->where( 'origin_id', $doc['id'] );
        $query = $this->db->get( 'exported_documents' );

        if( $query->num_rows() == 0 )
            // If there weren't any documents with this id
        {
            $store_document_insert_data = array(
                'user_id'   => $this->get_user_id(),
                'origin_id' => $doc['id'],
                'title'     => $doc['title'],
                'content'   => $doc['content']
            );

            // Insert new data
            $insert = $this->db->insert('exported_documents', $store_document_insert_data);

        }
        elseif( $query->num_rows() == 1 )
        {
            // New data
            $store_document_insert_data = array(
                'user_id'   => $this->get_user_id(),
                'origin_id' => $doc['id'],
                'title'     => $doc['title'],
                'content'   => $doc['content']
            );

            // Update the row
            $this->db->where( 'user_id', $this->get_user_id() );
            $this->db->where( 'origin_id', $doc['id'] );
            $update = $this->db->update('exported_documents', $store_document_insert_data);
        }

        // Give back ID of the last saved document
        $this->db->where( 'user_id', $this->get_user_id() );
        $this->db->order_by( 'last_edited', 'desc' );
        $docs = $this->db->get( 'exported_documents' );

        if( $docs->num_rows() > 0 )
        {
            foreach( $docs->result() as $doc )
            {
                $results[] = $doc;
            }

            return $results;
        }
        else
        {
            return false;
        }
    }

    /**
     * Creates new record when supplied with an ID
     * that can't be found in the database.
     *
     * Updates record when supplied with an ID that
     * is in the database.
     *
     * Returns an array containing the last modified
     * record.
     */
    function publish( $doc )
    {
        $this->db->where( 'user_id', $this->get_user_id() );
        $this->db->where( 'origin_id', $doc['id'] );
        $query = $this->db->get( 'published_documents' );

        if( $query->num_rows() == 1 )
        {
            // Set data
            $store_document_insert_data = array(
                'user_id'   => $this->get_user_id(),
                'username'  => $this->session->userdata('username'),
                'origin_id' => $doc['id'],
                'title'     => $doc['title'],
                'content'   => $doc['content']
            );

            // Update the row
            $this->db->where( 'user_id', $this->get_user_id() );
            $this->db->where( 'origin_id', $doc['id'] );
            $this->db->update('published_documents', $store_document_insert_data);
        }
        elseif( $query->num_rows() == 0 )
        {
            // Set data
            $store_document_insert_data = array(
                'user_id'   => $this->get_user_id(),
                'username'  => $this->session->userdata('username'),
                'origin_id' => $doc['id'],
                'title'     => $doc['title'],
                'content'   => $doc['content']
            );

            // Insert new data
            $this->db->insert('published_documents', $store_document_insert_data);
        }

        // Grab data from table
        $this->db->where( 'user_id', $this->get_user_id() );
        $this->db->where( 'origin_id', $doc['id'] );
        $docs = $this->db->get( 'published_documents' );

        if( $docs->num_rows()  == 1 )
        {
            foreach ( $docs->result() as $doc )
            {
                $results[] = $doc;
            }

            return $results;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns an array containing the document
     * whose record matches the $id supplied.
     *
     * If no such record was found, function
     * returns false.
     */
    function published_document_lookup( $id )
    {
        $this->db->where( 'id', $id );
        $query = $this->db->get( 'published_documents' );

        if( $query->num_rows() == 1 )
        {
            foreach( $query->result() as $row )
            {
                $results[] = $row;
            }

            return $results;
        }
        else
        {
            return false;
        }
    }
}
?>
