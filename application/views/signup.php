<section class="container">

  <a href="<?php echo base_url(); ?>" title="Just Write Homepage" ><img src="<?php echo base_url(); ?>resources/imgs/just-write-logo.png" alt="Just Write" /></a>

  <div id="signup-form" class="generic-form">

    <?php echo validation_errors('<p class="error">'); ?>

    <?php
      echo form_open('member/create_member');
      echo form_label('Username:', 'username');
      echo form_input('username', set_value('A Username is required', 'Username'));
      echo form_label('Email:', 'email');
      echo form_input('email', set_value('Your e-mail is required', 'Email'));
    ?>
      <p class="message">Don't worry, we wont share your email with <em><strong>anyone</strong></em> &mdash; we hate spam too.</p>
    <?php
      echo form_label('Password:', 'password');
      echo form_password('password', set_value('Your password is required', 'Password'));

      echo form_label('Confirm Password:', 'password');
      echo form_password('confirm_password', set_value('Your password is required', 'Password') );

      echo form_submit('submit', 'Sign me up!');
    ?>

    <?php if(isset($error)): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    </form>

    <p>Got an account? <a id="login" href="" title="Login to write">Login</a></p>
  </div>

</section>
