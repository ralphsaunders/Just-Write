<?php

class Membership_model extends CI_Model
{
    /**
     * Old hashing algo which has been depreciated
     * in favour of hash_password().
     *
     * Still used to validate users who have not
     * yet migrated over to the new algo.
     *
     * Returns a sha512 hash that has been salted
     * using the encyption key set in:
     * /application/config/config.php
     */
    public function old_hash_password($password)
    {
        return hash_hmac( "sha512", $password, $this->config->item('encryption_key') );
    }

    /**
     * New hashing algo which replaces old_hash_password().
     *
     * Generates hashes using blowfish encryption via the
     * crypt() function in PHP.
     *
     * Returns an array containing the salt used to generate
     * the hash as well as the actual hash.
     *
     * Given a password and a salt hash_password() will return
     * a hash that can be used for validation purposes.
     */
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


    /**
     * Returns a random string that is used as a salt
     */
    public function generate_random_string()
    {
        return substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(), mt_rand()))), 0, 22);
    }

    /**
     * Looks up a user's row in table "users" and
     * checks whether said user is an admin.
     *
     * Returns false unless user's column "admin"
     * is true.
     */
    public function user_is_admin()
    {
        $this->db->where( 'username', $this->input->post( 'username' ));
        $this->db->where( 'admin', true );

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

    /**
     * Checks a user's details against records, returns
     * true if they match.
     *
     * validate() will call migrate_user() in the case that
     * the user's details match those recorded using the old
     * hashing algo.
     *
     * In the case that user_is_admin() returns true, validate()
     * will return an array reflecting this.
     */
    function validate()
    {
        $this->db->where('username', $this->input->post('username'));
        $query = $this->db->get('users');

        if($query->num_rows() == 1)
        {
            $user = $query->row();

            if( isset( $user->migrated ) != NULL )
            {
                $hash = $this->hash_password( $this->input->post( 'password' ), $user->salt );
                if( $hash['hash'] == $user->password )
                    // Validate using new hash algo
                {
                    if( $this->user_is_admin() )
                        // Check if user is admin
                    {
                        return array( 'admin' => true );
                    }
                    else
                        // They still validated but aren't an admin
                    {
                        return true;
                    }
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
                    // If their details were valid...

                    // Migrate user
                    $this->migrate_user();

                    if( $this->validate() )
                        // Validate again
                    {
                        if( $this->user_is_admin() )
                            // Check if user is admin
                        {
                            return array( 'admin' => true );
                        }
                        else
                            // They still validated but aren't an admin
                        {
                            return true;
                        }
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

    /**
     * Hashes a user's password using the new algo
     * and updates that user's row in the database.
     */
    public function migrate_user()
    {
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

    /**
     * Creates new user record if none exists with that
     * username or email.
     *
     * Returns false if a user is found with the details
     * given.
     */
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

    /**
     * Generates a sha512 hash salted based on the date and user's
     * email address and stores this hash in the database if the details
     * provided are correct.
     */
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
                // If we got a match for username + email in the same row
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

    /**
     * Checks whether $key has been asigned to $username
     * in the database.
     */
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

    /**
     * Updates user's password provided the key has been
     * assigned to the username in the databse.
     */
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
