<section class="container">
  <div id="login-form" class="generic-form">
    <?php
      echo form_open('session/validate_credentials');
      echo form_label('Username:', 'username');
      echo form_input('username', 'Username');
      echo form_label('Password:', 'password');
      echo form_password('password', 'Password');
      
      echo form_submit('submit', 'Login');
      
      echo anchor( 'member/forgot_password', 'Forgot Password', 'class="left"' );
    ?>
    </form>
    <?php if( isset( $error ) ): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <p>
      <?php echo anchor('member/create_member', 'Don\'t have an account? Signup!', 'class="left"' ); ?>
      <br>
      <a class="left" href="<?php echo base_url(); ?>" title="Return to the homepage">Return to the homepage</a>
    </p>
  
  </div>
</section>
