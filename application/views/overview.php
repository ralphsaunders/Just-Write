<h1>Overview.php loaded</h1>
<?php if( isset($signed_up) ): ?>
  <h2>Signup successful</h2>
  <p>You can now <?php echo anchor('session', 'login'); ?>.</p>
<?php endif; ?>

<?php 
  $logged_in = $this->session->userdata('is_logged_in');
  if( isset($logged_in) ):
?>
  <h2>You're logged in!</h2>
<?php endif; ?>
