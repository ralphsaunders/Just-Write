<?php
if( isset( $document ) )
{
  $document = $document[0];
}
?>

<section id="published-document">

  <article class="generic-document">
    <?php if( isset( $document ) ) : ?>
      <h1><?php echo $document->title; ?></h1>
      <?php $is_logged_in = $this->session->userdata('is_logged_in'); if( $is_logged_in ): ?>
        <nav class="generic-menu back-to-editor">
          <?php echo anchor( 'document/load_last_open_document', '&larr; Back to editor' ); ?>
        </nav>
      <?php endif; ?>
      <?php echo $document->content; ?>
    <?php endif; ?>

    <footer class="generic-meta">
      <?php
        /**
         * Date Formatting.
         * Old: yyyy-mm-dd hh:mm:ss
         * New: dd-mm-yyyy hh:mm:ss
         */
        if( isset( $document->export_timestamp ) )
        {
          $year_month = explode( '-', $document->export_timestamp );
          $day_time   = explode( ' ', $year_month[2] );

          $timestamp = $day_time[0] . '-' . $year_month[1] . '-' . $year_month[0] . ' at ' . $day_time[1];
        }
      ?>
      <?php if( isset( $document->username ) && $document->username != '' ): ?>
        <p>This was published by <?php echo $document->username; ?> on <?php echo $timestamp; ?></p> 
      <?php endif; ?>
    </footer>

  </article>

  <aside class="ad">
    <a href="http://write.ralphsaunders.co.uk/" title="Try it now, for free">
      <img src="<?php echo base_url() . 'resources/imgs/just-write-ad.png'; ?>" alt="Write Logo" />
    </a>
    This was published with <a href="http://write.ralphsaunders.co.uk/" title="Try it now, for free">Just Write</a>, <em>the</em> comfortable way to write online.
  </aside>

</section>
