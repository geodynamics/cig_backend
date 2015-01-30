<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php  $mh = Loader::helper('mathToolbox'); ?>


<?php include 'common_nav_head.php'; ?> 

<?php if ( $this->controller->getTask() == 'edit' ||
         $this->controller->getTask() == 'add') { ?>

<H1>
<?php print ucwords( $this->controller->getTask() ) . " Downloadable File "; ?>
<?php print $software_edit['package_title']; ?>
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
print $form->label      ('filename',   'Filename');
print $form->text       ('filename',   $software_edit['filename'], array('maxlength' => '255', 'required' => ''));

print "<LI class=medium> ";
print $form->label      ('revision_num',   'Revision Number');
print $form->text       ('revision_num',   $software_edit['revision_num'], array('maxlength' => '255', 'required' => ''));

#DEBUG make calendar widget instead?
print "<LI class=short> ";
print $form->label      ('release_date',   'Release Date');
print $form->text       ('release_date',   $software_edit['release_date'], array('maxlength' => '10'));

print "</UL>\n";
print "<UL class=form-section>";

print "<LI class=short> ";
print $form->label      ('source',   'Source/Binary?');
print $form->select	('source',   array('y' => 'Source', 'n' => 'Binary'), $software_edit['source']);

print "<LI class=short> ";
print $form->label      ('active',   'Show As Recent?');
print $form->select	('active',   array('y' => 'Yes', 'n' => 'No'), $software_edit['active']);

print "<LI class=short> ";
print $form->label      ('hidden',   'Hidden?');
print $form->select	('hidden',   array('y' => 'Yes', 'n' => 'No'), $software_edit['hidden']);

print "<LI class=short> ";
print $form->label      ('list_first',   'List First?');
print $form->select	('list_first',   array('n' => 'No', 'y' => 'Yes'), $software_edit['list_first']);

print "<LI class=full> ";
print $form->label      ('tarball_desc',   'Short information specific to this particular file release');
print $form->textarea	('tarball_desc',   $software_edit['tarball_desc'], array('maxlength' => '5000'));

print "</UL>\n";


print $form->hidden	('software_id', $software_edit['software_id'] );

if ( $this->controller->getTask() == 'edit' ) { 
  print $form->hidden	('release_id', $software_edit['id'] );
  print $form->submit	('submit', 'Update Release', $tagAttributes, $additionalClasses);
} 
else {
  print $form->submit	('submit', 'Add New Release', $tagAttributes, $additionalClasses);
}

?>




<? } else { ?>
<!-- Default, show list of news blurbs -->

<H1>List of Downloadable Files</H1>

<P>
<A HREF="add/?software_id=<?php print $software_id ?>">Add Package</A>
</P>

<TABLE>
<TR>
  <TH>Name</TH>
  <TH>File</TH>
  <TH>Ver</TH>
  <TH>Date</TH>
  <TH>Active</TH>
  <TH>Hidden</TH>
  <TH>Source</TH>
  <TH>List First</TH>
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
    	"&release_id=" . $mh->mTxt( $row[id] ) . "'>";
    #print "<A HREF='edit'>";
    print $mh->mTxt( $row[filename] ) . "</A>";
    print "</TD>";

    print "<TD>" . $mh->mTxt( $row[revision_num] ) . "</TD>";
    print "<TD>" . $mh->mTxt( $row[release_date] ) . "</TD>";
    print "<TD align=center>" . $mh->mTxt( $row[active] ) . "</TD>";
    print "<TD align=center>" . $mh->mTxt( $row[hidden] ) . "</TD>";
    print "<TD align=center>" . $mh->mTxt( $row[source] ) . "</TD>";
    print "<TD align=center>" . $mh->mTxt( $row[list_first] ) . "</TD>";

    print "</TR>";
  }
?>

</TABLE>


<? }?>

<?php include 'common_nav_foot.php'; ?> 
