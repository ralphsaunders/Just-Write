<section id="container">

  <nav class="generic-nav">
    <?php if( isset( $error ) ): ?>
      <span class="error"><?php echo $error; ?></span>
    <?php endif; ?>
    <?php if( isset( $confirmation ) ): ?>
      <span class="confirmation"><?php echo $confirmation; ?></span>
    <?php endif; ?>
    <a id="login" class="right" href="" title="Login to write">
      <img src="<?php echo base_url(); ?>resources/imgs/log-in.png" alt="->" />Login
    </a>
  </nav>
  
  <section class="generic-section clear">
    <article id="introduction">
      <aside id="ui-screenshot" class="left">
        <a href="<?php echo base_url(); ?>" title="Homebound">
          <img src="<?php echo base_url() . 'resources/images/ui-screenshot.png' ; ?>" alt="The Just Write Interface" />
        </a>
      </aside>
      <div class="right">        
        <!-- <h1>Just Write, <em>the</em> comfortable way to write online</h1> -->
        <textarea id="write-your-own">Just Write, the comfortable way to write onli</textarea>
        <?php echo anchor( '/member/create_member', "Try it out, it's free", 'class="small call-to-action" title="Yes, free!"' ); ?>
        <a class="small call-to-action twitter" href="http://twitter.com/justwriteapp" title="Yes, free!">Get updates via twitter</a>
      </div> 
    </article>
  </section>
  
  <nav id="quick-navigation">
    <div class="quick-nav-arrow left"></div>
    <ul class="left">
      <?php
        
        $what = array(
          'title'     => 'What Does it Do?',
          'microcopy' => 'The things we did that make Just Write better than the rest',
          'url'       => 'what'
        );

        $why = array(
          'title'     => 'Why Does it Exist?',
          'microcopy' => 'The reasons and princicples for Just Write\'s creation',
          'url'       => 'why'
        );

        $source = array(
          'title'     => 'Licensing & Source',
          'microcopy' => 'You can use our source code for free, even in commercial projects',
          'url'       => 'source'
        );

        $advertise = array(
          'title'     => 'Advertise With Us',
          'microcopy' => 'Does your product fit with our service?',
          'url'       => 'advertise'
        );

        $menu = array( $what, $why, $source );

        $i = 0;
        foreach( $menu as $item ):
          if( $i == 3 )
          {
            $last = true;
          }
          else
          {
            $last = false;
          }
      ?>

      <li<?php if( $last ){ echo ' class="last"'; } ?>>
        <a href="#<?php echo $item['url']; ?>" title="<?php echo $item['microcopy']; ?>">
          <span class="generic-menu-heading">
            <?php echo $item['title']; ?>
          </span>
          <span class="generic-menu-copy">
            <?php echo $item['microcopy']; ?>
          </span>
        </a> 
      </li>
      <?php $i++; endforeach; ?>
    
    </ul>
    <div class="quick-nav-arrow right"></div>
  </nav>
  
  <br class="clear">  

  <section class="generic-section">
    <article id="what">
      <h2>What Does it Do?</h2>
      <ul>
        <?php

          $docs = array(
            'title' => 'Just Write lets you write from anywhere provided you have an internet connection',
            'img'   => 'access'
          );
          
          $markdown = array(
            'title' => 'Just Write supports <a href="http://daringfireball.net/projects/markdown/" title="Markdown is a text-to-HTML converstion tool for web writers">markdown</a>, allowing you to write for the web without having to remember HTML\'s fiddly syntax',
            'img'   => 'markdown',
          );

          $export = array(
            'title' => 'Just Write lets you export your documents as HTML. We don\'t lock you in',
            'img'   => 'export',
          );

          $publish = array(
            'title' => 'Just Write allows you to publish &amp; share your writing with the public with two clicks of your mouse',
            'img'   => 'publish',
            'thumb' => true
          );

          $current = array( $docs, $markdown, $export, $publish );

          
          
          $i = -1;
          foreach( $current as $item ):
            if( $i % 2 )
            {
              $class = true;
            }
            else
            {
              $class = false;
            }
        ?>
          
          <li<?php if( $class ) { echo ' class="odd"'; } ?>>
              <?php if( isset( $item['thumb'] ) ): ?>
                <a class="gallery-link" href="<?php echo base_url() . 'resources/images/' . $item['img']; ?>.jpg" ><span class="generic-expand"><img src="<?php echo base_url() . 'resources/imgs/expand.png'; ?>" alt="expand" /></span><img class="generic-thumb" src="<?php echo base_url() . 'resources/images/' . $item['img'] . '-thumb.jpg'; ?>" alt="thumbnail" /></a>
              <?php else: ?>
                <img class="generic-thumb" src="<?php echo base_url() . 'resources/images/' . $item['img'] . '-thumb.jpg'; ?>" alt="thumbnail" />
              <?php endif; ?>

              <h3><?php echo $item['title']; ?></h3>
          </li>
        <?php $i++; endforeach; ?>
      </ul>
      <br class="clear">
    </article>
    <aside id="what-planned" class="clear">
      <h2>Planned Features:</h2>
      <ul>
        <?php
          $oauth = array(
            'title'   => 'oAuth &amp; OpenID Support',
            'content' => 'oAuth &amp; OpenID will let you securely sign into Just Write with services like Twitter, Wordpress, and Facebook, making signup and document sharing less of a hassle.',
            'img'     => 'openid'
          );

          $ios = array(
            'title'   => 'Official iOS Support',
            'content' => 'In theory, Just Write should work fine on iOS devices right now, however we\'re yet to fully test on these devices. If you feel adventurous, give it ago and <a href="http://twitter.com/justwrite" title="Just Write on Twitter">let us know how it goes on twitter</a>.',
            'img'     => 'ios'
          );
          
          $planned = array( $oauth, $ios );

          $i = -1;
          foreach( $planned as $item ):
            if( $i % 2 )
            {
              $class = true;
            }
            else
            {
              $class = false;
            }
          ?>
          <li<?php if( $class ){ echo ' class="odd"'; } ?>>
              <?php if( isset( $item['thumb'] ) ): ?>
                <a class="gallery-link" href="<?php echo base_url() . 'resources/images/' . $item['img']; ?>.jpg" ><img class="generic-thumb" src="<?php echo $item['img'] . '-thumb.jpg'; ?>" alt="thumbnail" /></a>
              <?php else: ?>
                <img class="generic-thumb" src="<?php echo base_url() . 'resources/images/' . $item['img'] . '-thumb.jpg'; ?>" alt="thumbnail" />
              <?php endif; ?>

              <h3><?php echo $item['title']; ?></h3>
              <p><?php echo $item['content']; ?></p>
          </li>
        <?php $i++; endforeach; ?>
      </ul>
      <nav class="call-to-action-nav">
        <?php echo anchor( '/member/create_member', "Try it out, it's free", 'class="clear call-to-action"' ); ?>
      </nav>
      <br class="clear"> 
    </aside>
  </section>

  <section class="generic-section">
    <article id="why">
      <h2>Why Does it Exist?</h2>
      <img class="left" src="<?php echo base_url() . 'resources/images/why-thumb.png'; ?>" alt="Just Write Logo" />
     <p>Just Write was created because we couldn't find a simplistic and intuitive online writing tool. We set about crafting a tool that would let you do just that; it should be as easy as logging in and starting to type.</p>
    </article>
    <article id="source">
      <h2>Licensing &amp; Source</h2>
      <aside class="left thumb-button">
        <img class="left" src="<?php echo base_url() . 'resources/images/code-thumb.png'; ?>" alt="Just Write Logo" />
        <a class="call-to-action small" href="https://github.com/ralphsaunders/Just-Write" title="Available on Github">Get it on Github</a>
      </aside>
      <p>We've made Just Write's source code freely available for people wanting to integrate a proper writing environment into their projects and for the curious. The source is licensed under the <a href="http://www.opensource.org/licenses/bsd-license.php" title="For your reference">BSD 2-clause license</a> making it completely free, even for commercial projects.</p> 
    </article>
    <br class="clear">
  </section>

</section>

<footer id="landing-footer">
  <img src="<?php echo base_url() . 'resources/images/just-write-logo.png'; ?>" alt="The Just Write Logo" />
  <p>Created and maintained by Ralph Saunders, <a href="http://twitter.com/ralphsaunders" title="@ralphsaunders (Ralph Saunders) on Twitter">follow him on twitter</a>.</p>
  <small><a href="http://write.ralphsaunders.co.uk/index.php/site/published/36" >Privacy Policy</a></small>
</footer>

<!-- Page Specific Scripts -->
<script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript">
$( document ).ready( function() {
  // Setup fancybox
  $( ".gallery-link" ).fancybox({
    'titlePosition'		: 'outside',
    'easingIn'        : 'easeOutBack',
		'easingOut'       : 'easeInBack',
    'overlayColor'		: '#000',
    'overlayOpacity'	: 0.9
  })
  
  // Focus on the title
  function setSelectionRange(input, selectionStart, selectionEnd) {
    if (input.setSelectionRange) {
      input.focus();
      input.setSelectionRange(selectionStart, selectionEnd);
    }
    else if (input.createTextRange) {
      var range = input.createTextRange();
      range.collapse(true);
      range.moveEnd('character', selectionEnd);
      range.moveStart('character', selectionStart);
      range.select();
    }
  }

  function setCaretToPos (input, pos) {
    setSelectionRange(input, pos, pos);
  }
  $( '#write-your-own' ).focus();
  setCaretToPos( document.getElementById( 'write-your-own' ), 47 );
});
</script>
