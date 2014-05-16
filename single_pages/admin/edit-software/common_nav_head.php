<!-- Adds left side navigation -->
<!-- Hard-coded base URLs to avoid weirdness with subdirectories 
     of single pages -->
<!-- might need to pull software id from URL/GET -->

<?php $sw_base_url = View::url('/') . 'admin/edit-software/'; ?>

<?php # Get the package title
if ( $software_id ) {
    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $result = $db->Query(
        "select package_title from software where id=?",
	array( $software_id )
    );
    $temp = $result->fetchRow();
    $package_title = $temp['package_title'];

    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);
}
?>


<!-- Software Navigation block -->
<DIV class=sidebar_left>
<UL class=nav>
  <LI> <A HREF="<?php echo $sw_base_url; ?>">Back to list</A>

<P>
  <?php if ( $software_id ) { # Hide this if we're adding new software ?>
    <LI> <A HREF="<?php echo $sw_base_url . 'edit/?software_id=' . $software_id ?>">[<?php echo $package_title ?>]</A>
    <LI> <A HREF="<?php echo $sw_base_url . 'edit/?software_id=' . $software_id ?>">General Info</A>
    <LI> <A HREF="<?php echo $sw_base_url . 'release_desc/?software_id=' . $software_id ?>">Release Description</A>
    <LI> <A HREF="<?php echo $sw_base_url . 'release/?software_id=' . $software_id ?>">Downloadable Files</A>
</P>
  <?php } ?>

</UL>
</DIV>

<DIV class=content_left>
