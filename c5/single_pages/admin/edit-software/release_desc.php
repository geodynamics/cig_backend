<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php  $mh = Loader::helper('mathToolbox'); ?>


<?php include 'common_nav_head.php'; ?> 

<?php if ( $this->controller->getTask() == 'edit' ||
         $this->controller->getTask() == 'add') { ?>

<H1>
<?php print ucwords( $this->controller->getTask() ) . " Release Description "; ?>
<?php print $software_edit['package_title']; ?>
<!-- DEBUG -->
</H1>


<?php if ( $this->controller->getTask() == 'edit' ) { ?>
  <FORM method="POST" action="<?php echo $this->action('edit'); ?>">
<?php } else { # add an entry and return to list ?>
  <FORM method="POST" action="<?php echo $this->action(''); ?>">
<?php } ?>


<?php  
  $form = Loader::helper('form'); 


print "<UL class=form-section>";
print "<LI class=medium> ";
print $form->label      ('desc_revision_num',   'Description #');
print $form->text       ('desc_revision_num',   $software_edit['desc_revision_num'], array('maxlength' => '255', 'required' => ''));

/* Not in use
print "<LI class=medium> ";
print $form->label      ('revision_num',   'Software Rev #');
print $form->text       ('revision_num',   $software_edit['revision_num'], array('maxlength' => '255', 'required' => ''));
*/

print "</UL>\n";
print "<UL class=form-section>";

/* Not in use
print "<LI class=short> ";
print $form->label      ('cig_supported',   'CIG Supported?');
print $form->select	('cig_supported',   array('y' => 'Yes', 'n' => 'No'), $software_edit['cig_supported']);
*/

# DEBUG - add devel list
print "<LI class=short> ";
print $form->label      ('status',   'Devel Status');
#print $form->select	('status',   array('1' => 'Yes', '2' => 'No'), $software_edit['status']);
print $form->select	('status',   $softwareStatusList, $software_edit['status']);

print "</UL>\n";
print "<UL class=form-section>";

print "<LI class=full><H3>Overall Package Description</H3>";


print "<LI class=full> ";
print $form->label      ('desc_short',   'One or two sentences generally describing the software');
print $form->textarea	('desc_short',   $software_edit['desc_short'], array('maxlength' => '5000'));

print "</UL>\n";
print "<UL class=form-section>";

print "<LI class=full> ";
print $form->label      ('desc_long',   '(HTML) Paragraphs describing the software and, in general, the most important changes made for this revision');
print $form->textarea	('desc_long',   $software_edit['desc_long'], array('maxlength' => '5000'));

print "</UL>\n";
print "<UL class=form-section>";

print "<LI class=full><H3>General Downloadable File Descriptions (optional)</H3>";

print "<LI class=full> ";
print $form->label      ('general_binary_desc',   '(Optional) Description regarding <I>all</I> downloadable binaries');
print $form->textarea	('general_binary_desc',   $software_edit['general_binary_desc'], array('maxlength' => '5000'));

print "</UL>\n";
print "<UL class=form-section>";

print "<LI class=full> ";
print $form->label      ('general_source_desc',   '(Optional) Description regarding <I>all</I> downloadable source files');
print $form->textarea	('general_source_desc',   $software_edit['general_source_desc'], array('maxlength' => '5000'));

print "</UL>\n";


print $form->hidden	('software_id', $software_edit['software_id'] );

if ( $this->controller->getTask() == 'edit' ) { 
  print $form->hidden	('release_desc_id', $software_edit['id'] );
  print $form->submit	('submit', 'Update Release', $tagAttributes, $additionalClasses);
} 
else {
  print $form->submit	('submit', 'Add New Release', $tagAttributes, $additionalClasses);
}

?>




<? } else { ?>
<!-- Default, show list of news blurbs -->

<H1>Release Description</H1>

<P>
<A HREF="add/?software_id=<?php print $software_id ?>">Add New Release Description</A>
</P>

<TABLE>
<TR>
  <TH>Name</TH> <TH></TH>
  <TH>Software<BR>Rev #</TH>
  <TH>CIG<BR>Support</TH>
  <TH>Status</TH>
</TR>

<?php
  foreach( $softwareList as $row ) {
    $count = $count + 1;
    if ( $count % 2 === 0 )  print "<TR>";
    else print "<TR class=odd>";

    print "<TD>" . $mh->mTxt( $row[package_title] ) . "</TD>";

    # Release
    print "<TD>" ;
    print "<A HREF='edit/?software_id=" . $mh->mTxt( $row[software_id] ) . 
    	"&release_desc_id=" . $mh->mTxt( $row[id] ) . "'>";
    print "Desc " . $mh->mTxt( $row[desc_revision_num] ) . "</A>";
    print "</TD>";

    print "<TD>" . $mh->mTxt( $row[revision_num] ) . "</TD>";
    print "<TD align=center>" . $mh->mTxt( $row[cig_supported] ) . "</TD>";
    print "<TD>" . $mh->mTxt( $row[status_long] ) . "</TD>";

    print "</TR>";
  }
?>

</TABLE>


<? }?>

<?php include 'common_nav_foot.php'; ?> 
