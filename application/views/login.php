<section class="container">
  <div id="login-form" class="generic-form">
    <h2>Login</h2>
    <?php
      echo form_open('session/validate_credentials');
      echo form_input('username', 'Username');
      echo form_password('password', 'Password');
      
      echo form_submit('submit', 'Login');

      echo validation_errors('<p class="error">'); 
    ?>
    </form>
    <?php if( isset( $error ) ): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php echo anchor('member/create_member', 'Don\'t have an account? Signup!'); ?>
    <a href="<?php echo base_url(); ?>" title="Return to the homepage">Return to the homepage</a>
  </div>
  <article id="about-just-write">

  </article>
</section>
