<?php
  defined('C5_EXECUTE') or die( "Access Denied." );
  $this->inc( 'elements/header.php' ); 
?>

<DIV class="sidebar_left software_sidebar_left">
<DIV class="generic_sidebar_padding">
  <?php $a = new Area('Software Sidebar'); $a->display($c); ?>
</DIV>
</DIV>

<DIV class="content content_left">
  <DIV class="software_status">
    <?php $a = new Area('Software Status'); $a->display($c); ?>
  </DIV>

  <?php $a = new Area('Main'); $a->display($c); ?>

</DIV>

<?php
//  defined('C5_EXECUTE') or die( "Access Denied." );
  $this->inc( 'elements/footer.php' ); 
?>
