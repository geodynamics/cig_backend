<?php
  defined('C5_EXECUTE') or die( "Access Denied." );
  $this->inc( 'elements/header.php' ); 
?>

<DIV class="content content_full">
  <?php $a = new Area('Main'); $a->display($c); ?>
</DIV>

<?php
  $this->inc( 'elements/footer.php' ); 
?>

