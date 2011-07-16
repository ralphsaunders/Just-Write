<section class="container">
  <a href="<?php echo base_url(); ?>" title="Just Write Homepage" ><img src="<?php echo base_url(); ?>resources/imgs/just-write-logo.png" alt="Just Write" /></a>
  <div id="forgot-password-form" class="generic-form">
    <?php if( isset( $error ) ): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if( isset( $mail_sent ) ): ?>
      <p class="confirmation">An email was sent to your address</p>
    <?php endif; ?>
    <?php if( isset( $debug ) ): ?>
      <pre>
        <code>
          <?php print_r( $debug ) ?>
        </code>
      </pre>
    <?php endif; ?>
    <?php
      if( validation_errors() ){
        echo validation_errors( '<p class="error">', '</p>' );
      }
    ?>
    <?php
      echo form_open('member/forgot_password');
      echo form_label('Username:', 'username');
      echo form_input('username', 'Username');
      echo form_label('Email you signed up with:', 'email');
      echo form_input('email', 'Email');
      
      echo form_submit('submit', 'Reset Password');
      
      echo anchor( 'member/forgot_password', 'Forgot Password', 'class="left"' );
    ?>
    </form>
  </div>
</section>
