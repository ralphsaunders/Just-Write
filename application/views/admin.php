<h1>Admin.php loaded</h1>

<p>Page rendered in {elapsed_time} seconds</p>

<p>
  <?php
    if( !isset( $members_registered ) )
    {
      echo "0";
    }
    else
    {
      echo $members_registered;
    }
  ?>
 members have registered so far today.
</p>

<p>
  <?php
    if( !isset( $documents_edited ) )
    {
      echo "0";
    }
    else
    {
      echo $documents_edited;
    }
  ?>
 documents have been edited so far today.
</p>


<?php echo anchor( 'document/load_last_open_document', 'Write' ); ?>
<br>
<a href="<?php echo site_url( 'session/destroy' ); ?>" title="Logout" id="logout">logout</a>
