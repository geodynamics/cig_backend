<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php  $mh = Loader::helper('mathToolbox'); ?>


<?php if ( $this->controller->getTask() == 'edit' ||
         $this->controller->getTask() == 'add') { ?>

<?php include 'common_nav_head.php'; # For top level, it only shows on edit/add links ?>


<H1>
<?php print $this->controller->getTask() . " "; ?>
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
print $form->label      ('package_title',   'Package Title');
print $form->text       ('package_title',   $software_edit['package_title'], array('maxlength' => '255', 'required' => ''));

print "<LI class=medium> ";
print $form->label      ('short_name',   'Short Name');
print $form->text       ('short_name',   $software_edit['short_name'], array('maxlength' => '255', 'required' => ''));

print "<LI class=medium> ";
print $form->label      ('domain_id',   'Domain');
print $form->select	('domain_id',   $softwareDomainList, $software_edit['domain_id']);

print "<LI class=short> ";
print $form->label      ('hidden',   'Hidden?');
print $form->select	('hidden',   array('n' => 'No', 'y' => 'Yes'), $software_edit['hidden']);

print "</UL>\n";
print "<UL class=form-section>";

# BUG dropdown box instead? 
print "<LI class=medium> ";
print $form->label      ('license',   'License');
print $form->text       ('license',   $software_edit['license'], array('maxlength' => '255'));

# BUG dropdown box instead? 
print "<LI class=medium> ";
print $form->label      ('mail_lists',   'Mail Lists');
print $form->text       ('mail_lists',   $software_edit['mail_lists'], array('maxlength' => '255'));

print "<LI class=medium> ";
print $form->label      ('has_manual',   'Has Manual?');
print $form->select	('has_manual',   array('y' => 'Yes', 'n' => 'No'), $software_edit['has_manual']);

print "<LI class=medium> ";
print $form->label      ('manual_url',   'Manual URL');
print $form->text	('manual_url',   $software_edit['manual_url'], array('maxlength' => '255'));

print "</UL>\n";
print "<UL class=form-section>";

print "<LI class=short> ";
print $form->label      ('wiki',   'Has Wiki?');
print $form->select	('wiki',   array('y' => 'Yes', 'n' => 'No'), $software_edit['wiki']);

print "<LI class=full> ";
print $form->label      ('wiki_desc',   'Alternate Wiki Description (Optional)');
print $form->textarea	('wiki_desc',   $software_edit['wiki_desc'], array('maxlength' => '5000'));

print "</UL>\n";
print "<UL class=form-section>";

/*
print "<LI class=short> ";
print $form->label      ('repo',   'Has Repository?');
print $form->select	('repo',   array('y' => 'Yes', 'n' => 'No'), $software_edit['repo']);
*/

print "<LI class=medium> ";
print $form->label      ('repo_type',   'Repo Type');
print $form->select	('repo_type',   array('git' => 'GitHub'), $software_edit['repo_type']);

print "<LI class=medium> ";
print $form->label      ('dev_branch',   'Dev Branch');
print $form->text       ('dev_branch',   $software_edit['dev_branch'], array('maxlength' => '255'));

print "<LI class=short> ";
print $form->label      ('dev_doxygen',   'Dev Doxygen?');
print $form->select	('dev_doxygen',   array('y' => 'Yes', 'n' => 'No'), $software_edit['dev_doxygen']);

print "<LI class=short> ";
print $form->label      ('release_doxygen',   'Release Doxygen?');
print $form->select	('release_doxygen',   array('y' => 'Yes', 'n' => 'No'), $software_edit['release_doxygen']);

print "<LI class=short> ";
print $form->label      ('jenkins_build',   'Checking builds with Jenkins?');
print $form->select	('jenkins_build',   array('y' => 'Yes', 'n' => 'No'), $software_edit['jenkins_build']);

print "<LI class=short> ";
print $form->label      ('jenkins_test',   'Testing with Jenkins?');
print $form->select	('jenkins_test',   array('y' => 'Yes', 'n' => 'No'), $software_edit['jenkins_test']);

print "</UL>\n";
print "<UL class=form-section>";


print "<LI class=short> ";
print $form->label      ('bug_reports',   'Git Bug Reports?');
print $form->select	('bug_reports',   array('y' => 'Yes', 'n' => 'No'), $software_edit['bug_reports']);

/*
print "<LI class=short> ";
print $form->label      ('doxygen',   'Has Doxygen?');
print $form->select	('doxygen',   array('y' => 'Yes', 'n' => 'No'), $software_edit['doxygen']);
*/

print "<LI class=short> ";
print $form->label      ('user_map',   'Has User Map?');
print $form->select	('user_map',   array('y' => 'Yes', 'n' => 'No'), $software_edit['user_map']);


print "</UL>\n";

if ( $this->controller->getTask() == 'edit' ) { 
  print $form->hidden	('software_id', $software_edit['id'] );
  print $form->submit     ('submit', 'Update Package', $tagAttributes, $additionalClasses);
} 
else {
  print $form->submit     ('submit', 'Add New Software Package', $tagAttributes, $additionalClasses);
}

?>




<? } else { ?>
<!-- Default, show list of news blurbs -->

<H1>Software Packages</H1>

<P>
<A HREF="add">Add Package</A>
</P>

<TABLE>
<TR>
  <TH>Name</TH>
  <TH>Domain</TH>
  <TH>Updated</TH>
  <TH colspan=2> </TH>
</TR>

<?php
  foreach( $softwareList as $row ) {
    $count = $count + 1;
    if ( $count % 2 === 0 )  print "<TR>";
    else print "<TR class=odd>";

    # Name
    print "<TD>" ;
    print "<A HREF='edit/?software_id=" . $mh->mTxt( $row[id] ) . "'>";
    #print "<A HREF='edit'>";
    print $mh->mTxt( $row[package_title] ) . "</A>";
    print "</TD>";

    print "<TD>" . $mh->mTxt( $row[domain_abbr] ) . "</TD>";
    print "<TD align=right>" . $mh->mTxt( $row[updated] ) . "&nbsp;</TD>";

    print "<TD>" ;
    print "<A HREF='release_desc/?software_id=" . $mh->mTxt( $row[id] ) . "'>";
    print "Release Desc</A>&nbsp;</TD>" ;

    print "<TD>" ;
    print "<A HREF='release/?software_id=" . $mh->mTxt( $row[id] ) . "'>";
    print "Files</A></TD>" ;


    print "</TR>";
  }
?>

</TABLE>


<? }?>

<?php include 'common_nav_foot.php'; ?>
