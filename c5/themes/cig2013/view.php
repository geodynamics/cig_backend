<?php
  defined('C5_EXECUTE') or die( "Access Denied." );
  $this->inc( 'elements/header.php' ); 
?>

<!-- view.php is mostly used as a wrapper for c5 "single pages" -->

<!-- for form validation -->
<!-- http://www.concrete5.org/documentation/how-tos/designers/themimg-system-pages/ -->
<DIV class=error>
<?php Loader::element('system_errors', array('error' => $error)); ?>
</DIV>


<DIV class="content content_full">
<!-- for content of single pages -->
<?php print $innerContent; ?>
</DIV>

<?php
//  defined('C5_EXECUTE') or die( "Access Denied." );
  $this->inc( 'elements/footer.php' ); 
?>
