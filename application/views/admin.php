<h1>Admin.php loaded</h1>

<p>Page rendered in {elapsed_time} seconds</p>

<p>
<?php if( !isset( $members_registered ) ) : ?>
0
<?php else: ?>
<?php echo $members_registered; ?>
<?php endif; ?>
 members have registered today.
</p>

<?php echo anchor( 'document/load_last_open_document', 'Write' ); ?>
<br>
<a href="<?php echo site_url( 'session/destroy' ); ?>" title="Logout" id="logout">logout</a>
