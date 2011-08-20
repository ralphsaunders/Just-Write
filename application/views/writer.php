<?php
  if( isset($last_open_document) )
  {
    $document = $last_open_document->content;
    $title = $last_open_document->title;
    $id = $last_open_document->id;
  } else {
    $document = '';
    $title = 'Untitled Document';
    $id = '';
  }

?>
<nav id="main-menu">
  <ul id="document-controls">
    <li>Recent Documents</li>
    <?php if( isset( $users_documents ) ): $i = 0; ?>
      <?php foreach( $users_documents as $doc ): ?>
        <li class="<?php echo $doc->id; ?>">
          <span class="delete">
            <a class="<?php echo $doc->id; ?>" href="#" title="Delete <?php echo $doc->title; ?>">
              <img src="<?php echo base_url(); ?>resources/imgs/delete.png" alt="Delete" />
            </a>
          </span>
          <a
            class="load"
            id="<?php echo $doc->id; ?>"
            href="#"
            title="<?php echo html_entity_decode( $doc->title, ENT_QUOTES, "UTF-8" ); ?>"
          >
            <?php echo html_entity_decode( $doc->title, ENT_QUOTES, "UTF-8" ); ?>
            <?php // var_dump($doc); ?> 
          </a>
        </li>
      <?php $i++; endforeach; ?>
    <?php endif; ?> 
    <li class="edit"><a href="#" title="Edit Documents">Edit</a></li>
    <li class="more"><a href="#" title="load all documents">More</a></li>
  </ul>
  <ul id="control-bar">
    <li id="doc-controls">
      <span id="doc-link">
        <a title="Recent Documents" href="#document-controls"><img src="<?php echo base_url(); ?>resources/imgs/open.png" alt="Open Document" /><span class="tool-tip">Recent Documents</span></a>
      </span>
      <span id="new-document"><a href="#new-document" title="New Document"><img src="<?php echo base_url(); ?>resources/imgs/new-doc.png" alt="New Document" /><span class="tool-tip">New Document</span></a></span>
    </li>

    <li id="title"><input name="current-doc-title" value="<?php echo html_entity_decode( $title, ENT_QUOTES, "UTF-8" ); ?>"  tabindex="1" /></li>

    <li id="current-doc-controls">
      <span id="save" class="button">
        <span id="saving">
          <span id="saving-icon"></span> 
          <img id="saved" src="<?php echo base_url(); ?>resources/imgs/tick.png" alt="saved" width="10" height="10" />
        </span>
        <a href="#save" title="save document">Save</a>
      </span>

      <ul id="export-options">
        <li><a id="export-dropdown" href="#" title="Export Options"><img src="<?php echo base_url(); ?>resources/imgs/export.png" alt="Export Options"></a></li>

        <li><div class="arrow"></div></li>

        <ul id="export">
          <li>
            <span id="export-to-html">
              <a class="markdown-to-html" href="#" title="Export to HTML">Export to HTML</a>
            </span>
          </li>
          <li>
            <span id="publish">
              <a class="publish" href="#" title="This will make the document viewable to the public">Publish</a>
            </span>
          </li>
        </ul>
      </ul>

    </li>
  </ul>
</nav>

<section id="document-container">
  <textarea class="<?php echo $id; ?>" id="document" name="document" tabindex="2"><?php echo html_entity_decode( $document, ENT_QUOTES, "UTF-8" ); ?></textarea>
</section>

<?php 
  $logged_in = $this->session->userdata('is_logged_in');
  if( isset($logged_in) ):
?>
<nav id="app-controls">
  <ul>
    <li class="left">
      <a href="#" title="Toggle UI" id="ui-toggle">
        <img src="<?php echo base_url(); ?>resources/imgs/ui-toggle.png" alt="Hide UI" />
      </a>
    </li> 
    <li class="left">
      <a href="#" title="Toggle Theme" id="theme-toggle"></a>
    </li> 
    <li class="right">
      <a href="<?php echo site_url( 'session/destroy' ); ?>" title="Logout" id="logout">
        <img src="<?php echo base_url(); ?>resources/imgs/log-out.png" alt="Logout" />Logout
      </a>
    </li>
  </ul>
</nav>
<?php endif; ?>
