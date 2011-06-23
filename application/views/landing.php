<section class="container">

  <a href="<?php echo base_url(); ?>" title="Just Write Homepage" ><img src="<?php echo base_url(); ?>resources/imgs/just-write-logo.png" alt="Just Write" /></a>

  <div id="login-form" class="generic-form">
    <h2>&mdash; Login to write &mdash;</h2>
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
    <?php echo anchor('#about-just-write', 'Don\'t have an account?', 'id="no-account"' ); ?>
  </div>
  <article id="about-just-write">
    <h2>What is Just Write?</h2>
    <p>Just Write is a webapp that allows you to express your ideas and musings in a comfortable, distraction-free environment. Just Write specifically caters towards writing for the web, providing <a href="http://daringfireball.net/projects/markdown/" title="Information on Markdown">markdown</a> support and "export to HTML" functionality.</p>

    <p>Sound useful? <a id="sign-up" href="<?php echo site_url(); ?>/member/create_member" title="Yes, really">try it out, it's free</a>.</p>
     
  </article>
</section>