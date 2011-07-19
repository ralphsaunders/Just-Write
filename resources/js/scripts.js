$(document).ready(function(){

  // Set global variables
  var siteUrl = 'http://localhost/24hr-writing-webapp/';
  var baseUrl = 'http://localhost/24hr-writing-webapp/';

  function inputPrep() {
    // Hides input value on focus
    $( 'input:text, input:password' ).each(function() {
      var default_value = this.value;
      $(this).focus(function() {
        if(this.value == default_value) {
          this.value = '';
        }
      });
      $(this).blur(function() {
        if(this.value == '') {
          this.value = default_value;
        }
      });
    });
  }

  inputPrep();

  // Sets widths + heights for the navigation and document 
  var currentWidth = $( '#control-bar' ).width( $( window ).width() - 73 );
  $( '#document' ).height( $( window ).height() - 120 );
  $( '.generic-code' ).height( $( window ).height() - 120 );

  // When window is resized update heights + widths
  $( window ).resize( function() {
    currentWidth = $( '#control-bar' ).width( $( window ).width() - 73 );
    $( '#document' ).height( $( window ).height() - 120 );
    $( '.generic-code' ).height( $( window ).height() - 120 );
  })

  // Hides message on signup form
  $( '.generic-form .message' ).hide();

  // When you focus on the email field
  $( 'input[name$=email]' ).focus( function(){
    $( '.generic-form .message' ).slideDown( 200 );
  })

  // Hides the document controls dropdown
  $( '#document-controls' ).hide();
  
  // Hides the loader
  $( '#saving img' ).hide();

  // Hides the saved icon
  $( '#saved' ).hide();

  // Hides delete controls
  $( '.delete' ).hide();

  // Hides the control bar unless mouse is moving
  function hideControls() {
    $( '#main-menu, #logout' ).fadeOut( 500 );
  }

  // Damn dirty hack, but it works
  var i = null;
  var focused = false;

  $( 'input[name$=current-doc-title]' ).live( 'focus', function() {
    // Stops UI from hiding
    focused = true;
  })

  if( $( '#index' ).attr( 'class' ) == 'exporting' ) {
    focused = true;
  }

  $( 'input[name$=current-doc-title]' ).live( 'blur', function() {
    focused = false;
  })

  $( '#index' ).mousemove( function() {
    clearTimeout( i );

    if( $( '#index' ).attr( 'class' ) == null ) {
      $( '#main-menu, #logout' ).fadeIn( 300 );
    }

    if ( focused == false ) {
      i = setTimeout( hideControls, 6000);
    }
  })
 
  function refreshDocuments(fn) {
    // List documents from this user
    $.ajax({ 
      url: siteUrl + 'document/refresh_doc_list',
      type: "POST",
      data: ({ refresh: true }),
      success: function (data) {
        var docs = $.parseJSON(data);
        var list = new Array();

        list.push( '<li>Recent Documents:</li>' );

        // For every item in docs
        for( var i in docs ){
          list.push( '<li class="' + docs[i].id + '"><span class="delete"><a class="' + docs[i].id + '" href="#" title="Delete ' + docs[i].title + '" >' );
          list.push( '<img src="' + baseUrl + 'resources/imgs/delete.png" alt="Delete" /></a></span>' );
          list.push( '<a class="load" id="' + docs[i].id + '" href="#" title="' + docs[i].title + '">' + docs[i].title + '</a></li>' );
        }

        list.push ( '<li class="edit"><a href="#" title="Edit Documents">Edit</a></li>' );
        list.push( '<li class="more"><a href="#" title="load all documents">More</a></li>' );

        document.getElementById('document-controls').innerHTML = list.join( '' );

        // Reset class + height
        $( '#document-controls' ).attr( 'class', '' ).css( 'height', 'auto' ); 

        fn();
      }
    })
  }

  // Get an up to date list of documents for the current user
  $( 'a[href$=#document-controls]' ).live( 'click', function() {
    refreshDocuments( function() {
      $( '.delete a' ).hide();
    })
    $( '#document-controls' ).slideToggle( 100 );
    return false;
  }) 

  // Saves the current document
  $( 'a[href$=#save]' ).live( 'click', function() {
    saveDocument(); 
    return false;
  })

  // Shortcuts
  
  // Save
  $( document ).bind('keydown', 'Ctrl+s', function() {
    saveDocument();
    return false;
  })

  // Save re-bind
  $( '#document' ).bind( 'keydown', 'Ctrl+s', function() {
    saveDocument();
    return false;
  })

  $( '#title input' ).bind( 'keydown', 'Ctrl+s', function() {
    saveDocument();
    return false;
  })
  
  // Open
  $(document).bind( 'keydown', 'Ctrl+o', function() {
    refreshDocuments( function() {
      $( '.delete a' ).hide();
    })
    $( '#document-controls' ).slideToggle( 100 );
    return false;
  })

  // Open re-bind
  $( '#document' ).bind( 'keydown', 'Ctrl+o', function() {
    refreshDocuments( function() {
      $( '.delete a' ).hide();
    })
    $( '#document-controls' ).slideToggle( 100 );
    return false;
  })

  // New
  $( document ).bind( 'keydown', 'Ctrl+n', function() {
    // Reset document fields
    $( 'input[name$=current-doc-title]' ).val( 'Untitled Document' );
    $( '#document' ).val( '' ).focus();
    $( '#document' ).attr( 'class', '' );
    return false;
  })

  $( '#document' ).bind( 'keydown', 'Ctrl+n', function() {
    // Reset document fields
    $( 'input[name$=current-doc-title]' ).val( 'Untitled Document' );
    $( '#document' ).val( '' ).focus();
    $( '#document' ).attr( 'class', '' );
    return false;
  })

  // Every 60 seconds
  setInterval( function() {
    //If document controls is hidden, save document
    if( $( '#document-controls' ).is( ':hidden' ) && $( '#all-documents' ).is( ':hidden' ) && $( '#document-container' ).attr() == null ){
      saveDocument();
    }
  }, 60000 )
  
  function saveDocument() {
    var title = $( 'input[name$=current-doc-title]' ).val();
    var content = $( '#document' ).val();
    var id = $( '#document' ).attr( 'class' );

    $( '#saving-icon' ).fadeIn( 50 );

    $.ajax({
      url: siteUrl + 'document/save',
      type: "POST",
      data: ({ id:id, content:content, title:title }),
      async: false,
      success: function (data) { 
        var result = $.parseJSON(data);

        $( '#document' ).attr( 'class', result );

        $( '#saving-icon' ).fadeOut( 300 );
        $( '#saved' ).stop( true, true ).delay( 400 ).fadeIn( 100 ).delay( 1000 ).fadeOut( function() {
          refreshDocuments( function() {
            $( '.delete a' ).hide();
          })
        })
      }
    })
  }

  // Loads a selected document
  $( '.load' ).live( 'click', function() {

    // Save current document
    //saveDocument();

    $.ajax({ 
      url: siteUrl + 'document/load',
      type: "POST",
      data: ({ id: this.getAttribute( 'id' ) }),
      success: function (data) {
        var doc = $.parseJSON(data);
        
        // Hide document control panel
        if( $( '#document-controls' ).is( ':visible' ) ){
          $( '#document-controls' ).slideToggle( 300 );
        }
       
        if( $( '#all-documents' ).is( ':visible' ) ){
          $( '#all-documents' ).fadeToggle( function() {
            $( '#all-documents' ).remove();
          }) 
        }

        // Load in new data
        $( 'input[name$=current-doc-title]' ).val( doc.title );
        $( '#document' ).val( doc.content ).focus();
        $( '#document' ).attr( 'class', doc.id );

        // Update document list ( for height reasons )
        refreshDocuments( function() {
          $( '.delete a' ).hide();
        })
      }
    })

    return false;
  })

  // Resets input fields - database will then create new document when it's saved
  $( 'a[href$=new-document]' ).live( 'click', function() {
      
    saveDocument();
    
    // Reset document fields
    $( 'input[name$=current-doc-title]' ).val( 'Untitled Document' );
    $( '#document' ).val( '' ).focus();
    $( '#document' ).attr( 'class', '' );
    return false;
  })

  // Toggle edit controls
  $( '.edit' ).live( 'click', function() {
    $( '.delete a' ).toggle( 200 );
    if ( $( this ).html() == '<a href="#" title="Edit Documents">Edit</a>' ) {
      $( this ).html ( '<a href="#" title="Done Editing">Done</a>' );
    } else {
      $( this ).html ( '<a href="#" title="Edit Documents">Edit</a>' );
    }
    return false;
  })

  // Deletes a document when given an id
  $( '.delete a' ).live( 'click', function() {

    
    var id = $( this ).attr( 'class' );
    $( this ).parentsUntil('li.' + id ).parent().fadeOut( function() { 
      var id = $( this ).parent().attr( 'class' );

      $.ajax({ 
        url: siteUrl + 'document/delete_document',
        type: "POST",
        data: ({ id: this.getAttribute('class') }),
        success: function (data) {
          // No need to refresh the document list as the item has already been hidden from view
          // and removed from the database.
        }
      })
    })

    return false;
  })

  // Convert markdown to html
  $( '.markdown-to-html' ).live( 'click', function() {
    var title = $( 'input[name$=current-doc-title]' ).val();
    var content = $( '#document' ).val();
    var id = $( '#document' ).attr( 'class' );
    var normalNav = $( '#control-bar' ).children();

    $( '#index' ).attr( 'class', 'exporting' );
    $( '#control-bar, #document' ).animate({
      opacity: 0 
    }, 200, function(){
      $( '#document' ).hide();

      var markdownNav = new Array();

      markdownNav.push( '<li id="doc-controls"><span id="back" class="button"><a href="#" id="back-to-document" title="Back to Document">Back' );
      markdownNav.push( '</a></span></li>' );
      
      $( '#control-bar' ).html( markdownNav.join( '' ) );

      $( '#control-bar' ).animate({
        opacity: 1
      }, 200, function(){
        $( '#index' ).removeAttr( 'class' );
      })
    }) 
    
    $.ajax({
      url: siteUrl + 'document/markdown_to_html',
      type: "POST",
      data: ({ id:id, content:content, title:title }),
      async: false,
      success: function (data) { 
        var result = $.parseJSON(data);
        console.log( result );
        
        $( '#document-container' ).attr( 'class', 'exported' );
        
        var code = new Array();
        code.push( '<article><pre><code class="generic-code"></code></pre></article>' );
        
        $( '#document-container' ).append( code.join('') );
        $( '.generic-code' ).hide().html( result.title + '\n\n' );
        $( '.generic-code' ).append( result.content );
        
        // Set height and fade in
        $( '.generic-code' ).height( $( window ).height() - 120 );
        $( '.generic-code' ).fadeIn( 200 );
        
      }
    })

    $( '#back-to-document' ).live( 'click', function() {
      $( '#control-bar, .generic-code' ).animate({
        opacity: 0
      }, 200, function() {
        $( '#control-bar' ).html( normalNav ).animate({
          opacity: 1
        }, 200, function() {
          $( '#document' ).val( content ).show();
          $( '.generic-code' ).parent().parent().remove();
          $( '#logout, #document' ).animate({
            opacity:1
          }, 200, function(){
            // Animation Complete
          })
        })
      });

    })

    return false;
  })

  

  $( '.more' ).live( 'click', function() {
    loadAllDocuments();
    return false;
  })

  function loadAllDocuments () {
    $( '#document-controls' ).slideToggle( 200 );

    $.ajax({
      url: siteUrl + 'document/load_all_documents',
      type: "POST",
      data: ({ loadAll: true }),
      success: function (data) { 
        var docs = $.parseJSON(data);
        var list = new Array();
        
        list.push( '<li>All Documents:</li>' );

        // For every item in docs.docs ( non-exported documents )
        for( var i in docs.docs ){
          list.push( '<li class="' + docs.docs[i].id + ' none"><span class="delete"><a class="' + docs.docs[i].id + '" href="#" title="Delete ' + docs.docs[i].title + '" >' );
          list.push( '<img src="' + baseUrl + 'resources/imgs/delete.png" alt="Delete" /></a></span>' );
          list.push( '<a class="load" id="' + docs.docs[i].id + '" href="#" title="' + docs.docs[i].title + '"><span class="doc-title">' + docs.docs[i].title + '</span>' );
          list.push( ' <span class="timestamp">' + docs.docs[i].last_edited + '</span></a></li>' );
        }
        
        /*
         * Commented out until I tweak the delete & load functions
        list.push( '<li>Published Documents:</li>' );

        // For every item in docs.exported_docs ( exported documents )
        
        for( var i in docs.published_docs ){
          list.push( '<li class="' + docs.published_docs[i].id + ' none"><span class="delete"><a class="' + docs.published_docs[i].id + '" href="#" title="Delete ' + docs.published_docs[i].title + '" >' );
          list.push( '<img src="' + baseUrl + 'resources/imgs/delete.png" alt="Delete" /></a></span>' );
          list.push( '<a class="load" id="' + docs.published_docs[i].id + '" href="#" title="' + docs.published_docs[i].title + '"><span class="doc-title">' + docs.published_docs[i].title + '</span>' );
          list.push( ' <span class="timestamp">' + docs.published_docs[i].export_timestamp + '</span></a></li>' );
        }

        */

        $( '#index' ).append( '<div id="all-documents"><a href="#" title="Close Window" class="close"><img src="' + baseUrl + 'resources/imgs/close.png" alt="close window" /></a><ul id="all-documents-list"></ul><div class="edit"><a href="#" title="Edit Documents">Edit</a></div></div>' );
        
        $( '#all-documents' ).hide();
        $( '#all-documents-list' ).append( list.join('') );
        $( '#all-documents' ).fadeToggle( 300 );
        $( '.delete a' ).hide();
      }
    })
  }

  $( '.close' ).live( 'click', function() {
    $( this ).parent().fadeToggle( function() {
      if( $( this ).parent().attr( "id" ) == 'login-form-wrap' ) {
        $( this ).parent().remove()
      } else {
        $( this ).remove();
      }
    })
    return false;
  })

  $( '#about-just-write' ).hide();

  $( '#no-account' ).live( 'click', function() {
    $( '#about-just-write' ).stop( true, true ).fadeIn( 200 );
    return false;
  })

  $( '#document' ).focus( function() {
    if ( $( '#document-controls' ).is( ':visible' ) ) {
      $( '#document-controls' ).slideUp( 200 );
    }
  })

  // Publish Document
  $( '.publish' ).live( 'click', function() {
    var title = $( 'input[name$=current-doc-title]' ).val();
    var content = $( '#document' ).val();
    var id = $( '#document' ).attr( 'class' );

    $.ajax({
      url: siteUrl + 'document/publish',
      type: "POST",
      data: ({ id:id, content:content, title:title }),
      success: function (data) { 
        var result = $.parseJSON(data);
        window.location.replace( siteUrl + 'site/published/' + result.id );  
      }
    })
  })

  $( '#export-dropdown, #export a' ).live( 'click', function() {
    $( '#export, .arrow' ).toggle();
  })

  $( '#login' ).live( 'click', function() {
    if( $( '#login-form-wrap' ).length == 0 ){
      var form = new Array();

      // Setup wrappers
      form.push( '<div id="login-form-wrap"><div id="login-form" class="generic-form">' );
      // Start close link
      form.push( '<a href="" title="Close Window" class="close">' );
      // finish link + h2
      form.push( '<img src="' + baseUrl + 'resources/imgs/close.png" alt="close window" /></a>');
      // Just Write Logo
      form.push( '<img src="' + baseUrl + 'resources/images/just-write-logo.png" alt="The Just Write Logo" />' );
      // H2
      // form.push( '<h2>Login to Write</h2>' );
      // Start form
      form.push( '<form action="' + siteUrl + 'session/validate_credentials" method="post" accept-charset="utf-8">');
      // Fields
      form.push( '<label>Username:<br><input type="text" name="username" value="Username"></label>' );
      form.push( '<label>Password:<br><input type="password" name="password" value="Password"></label>' );
      form.push( '<input type="submit" name="submit" value="Login">' );
      // Forgot password link
      form.push( '<a href="' + siteUrl + 'member/forgot_password" title="Trouble remembering your password?">Forgot Password</a>' );
      // Close form and wrappers
      form.push( '</form></div></div>' );

      $( '#index' ).append( form.join('') );
      $( '#login-form-wrap' ).hide();
      $( '#login-form-wrap' ).fadeIn( 300 );
      
      inputPrep();

      $( 'input[name$=username]' ).focus();
    } 
    return false;
  }) 

}); 
