<?php
  defined('C5_EXECUTE') or die( "Access Denied." );
  $this->inc( 'elements/header.php' ); 
?>

<DIV class=sidebar_left>
  <?php
  $a = new GlobalArea('Sidebar Nav');
  $a->setBlockLimit(1);
  $a->display($c);
  ?>

  <?php $a = new Area('Sidebar'); $a->display($c); ?>
</DIV>

<DIV class="content content_left">
  <?php $a = new Area('Main'); $a->display($c); ?>
</DIV>

<?php
//  defined('C5_EXECUTE') or die( "Access Denied." );
  $this->inc( 'elements/footer.php' ); 
?>
