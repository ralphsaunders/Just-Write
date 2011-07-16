<section class="container">
  <div class="generic-form">
    <a href="<?php echo base_url(); ?>" title="Just Write Homepage" ><img src="<?php echo base_url(); ?>resources/imgs/just-write-logo.png" alt="Just Write" /></a>
    <?php
      // Fill in fields if possible
      if( ! isset( $username ) && $username != '' )
      { 
        $username = 'Username';
      }

      echo form_open('member/new_password');
      echo form_label('Username:', 'username');
      echo form_input('username', $username);

      echo form_label('New Password:', 'password');
      echo form_password('password', '');
      echo form_label('Confirm New Password:', 'password');
      echo form_password('confirm_password', '');

      if( isset( $key ) && $key != '' )
      {
        echo form_hidden( 'key', $key );
      }
      else
      {
        echo form_label('Security Key:', 'key');
        echo '<p>Looks like you got sent here without a security key. The email you received when you reset your password had one in it (the key is a long line of digits and letters). You can <a href="' . base_url() . 'member/forgot_password" title="Reset Password">reset your password here</a>.</p>';
        echo form_input('key', 'key');
      } 

      echo form_submit('submit', 'Login');
    ?>
    </form>
    <?php
      if( validation_errors() ){
        echo validation_errors( '<p class="error">', '</p>' );
      }
    ?>
    <?php if( isset( $error ) ): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if( isset( $confirmation ) ): ?>
      <p class="confirmation"><?php echo $confirmation; ?></p>
    <?php endif; ?>
