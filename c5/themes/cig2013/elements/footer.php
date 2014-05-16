<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
</DIV> <!-- End content -->

<div class="clear"></div>

<DIV class=footer>
<DIV class=support>
&copy; <?php  echo date('Y')?> UC Regents, Davis campus. All rights reserved.
CIG is supported by the US National Science Foundation.
</DIV>
</DIV>

<DIV class=login>

Contact Us | 
Site Map |

<?php
  $u = new User();
  if ($u->isRegistered()) {
    $userName = $u->getUserName(); ?>

        <span class="sign_in">Signed In: <B><?php echo $userName ?></B>
        |
        <A HREF="<?php echo $this->url('/login', 'logout')?>">Sign Out</A>
        </span>
  <? } else { ?>
        <span class="sign_in"><A HREF="<?php echo $this->url('/login')?>">Sign In</A></span>
<?  } ?>
</DIV> <!-- end login -->




</DIV> <!-- End main container -->

<?php Loader::element('footer_required'); ?>

</body>
</html>
