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
      <?php echo $document->content; ?>
    <?php endif; ?>
  </article>
  <footer class="generic-meta">

  <?php $is_logged_in = $this->session->userdata('is_logged_in'); if( $is_logged_in ): ?>
  <nav class="generic-menu back-to-editor">
    <?php echo anchor( 'document/load_last_open_document', '&larr; Back to editor' ); ?>
  </nav>
  <?php endif; ?>

  <p>This was published using Just Write, <em>the</em> comfortable, distraction-free writing environment. <a href="<?php echo base_url(); ?>" title="Yes, really">Try it out, it's free</a></p>
  </footer>
</section>
