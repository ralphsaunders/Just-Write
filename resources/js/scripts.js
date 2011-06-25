$(document).ready(function(){

  // Set global variables
  var siteUrl = 'http://localhost/24hr-writing-webapp/index.php/';
  var baseUrl = 'http://localhost/24hr-writing-webapp/';


  // Hides input value on focus
  $('input:text, input:password').each(function() {
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

  // Sets widths + heights for the navigation and document 
  $( '#control-bar' ).width( $( window ).width() - 73 );
  $( '#document' ).height( $( window ).height() - 110 );

  // When window is resized update heights + widths
  $( window ).resize( function() {
    $( '#control-bar' ).width( $( window ).width() - 73 );
    $( '#document' ).height( $( window ).height() - 110 );
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
  
  // $( 'input[name$=current-doc-title]:focus' )


  function hideControlBar() {
    $( '#control-bar' ).stop( true, true ).fadeOut( 500 );
  }

  function showOnMouseMove() {
    var i = null;

    $("#index").mousemove(function() {
      
      $( '#control-bar' ).fadeIn( 200 );
      
      clearTimeout( i );
      i = setTimeout( hideControlBar, 1000);

    }).mouseleave(function() {

      clearTimeout(i);
      i = setTimeout( hideControlBar, 1000);

    })
  }
  
  $( 'input[name$=current-doc-title]' ).focusout( function(){
    //i = setTimeout( showOnMouseMove, 10);
  }).focusin( function(){
    //clearTimeout( i );
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
    if( $( '#document-controls' ).is( ':hidden' ) && $( '#all-documents' ).is( ':hidden' ) ){
      saveDocument();
    }
  }, 60000 )
  
  function saveDocument() {
    var title = $( 'input[name$=current-doc-title]' ).val();
    var content = $( '#document' ).val();
    var id = $( '#document' ).attr( 'class' );

    $( '#saving-icon' ).stop( true, true ).fadeIn( 50 );

    $.ajax({
      url: siteUrl + 'document/save',
      type: "POST",
      data: ({ id:id, content:content, title:title }),
      async: false,
      success: function (data) { 
        var result = $.parseJSON(data);

        $( '#document' ).attr( 'class', result );

        $( '#saving-icon' ).stop( true, true ).fadeOut( 300 );
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
    var content = $( '#document' ).val();
    
    $.ajax({
      url: siteUrl + 'document/markdown_to_html',
      type: "POST",
      data: ({ content:content }),
      async: false,
      success: function (data) { 
        var html = $.parseJSON(data);
        
        // Populate textarea with html and select said html,
        // ready for copy/paste.
        $( '#document' ).val( html ).focus().select(); 
        
      }
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

        // For every item in docs
        for( var i in docs ){
          list.push( '<li class="' + docs[i].id + ' none"><span class="delete"><a class="' + docs[i].id + '" href="#" title="Delete ' + docs[i].title + '" >' );
          list.push( '<img src="' + baseUrl + 'resources/imgs/delete.png" alt="Delete" /></a></span>' );
          list.push( '<a class="load" id="' + docs[i].id + '" href="#" title="' + docs[i].title + '"><span class="doc-title">' + docs[i].title + '</span>' );
          list.push( ' <span class="timestamp">' + docs[i].last_edited + '</span></a></li>' );
        }

        $( '#index' ).append( '<div id="all-documents"><a href="#" title="Close Window" class="close"><img src="' + baseUrl + 'resources/imgs/close.png" alt="close window" /></a><ul id="all-documents-list"></ul><div class="edit"><a href="#" title="Edit Documents">Edit</a></div></div>' );
        
        $( '#all-documents' ).hide();
        $( '#all-documents-list' ).append( list.join('') );
        $( '#all-documents' ).fadeToggle( 300 );
        $( '.delete a' ).hide();
      }
    })
  }

  $( '.close' ).live( 'click', function() {
    $( '#all-documents' ).fadeToggle( function() {
      $( this ).remove();
    })
    return false;
  })

  $( '#about-just-write' ).hide();

  $( '#no-account' ).live( 'click', function() {
    $( '#about-just-write' ).stop( true, true ).fadeIn( 200 );
    return false;
  })
}); 
