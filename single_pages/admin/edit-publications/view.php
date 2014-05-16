<?php defined ('C5_EXECUTE') or die ("Access Denied."); ?>
<?php $mh = Loader::helper('mathToolbox'); ?>
<?php $bh = Loader::helper('bibTeX'); ?>

<?php if ($this->controller->getTask() == 'edit' ||
          $this->controller->getTask() == 'add') { ?>

<?php include 'common_nav_head.php'; ?>

<h1>
  <?php print $this->controller->getTask() . ' '; ?>
  <?php print ' ' . $publication_edit['title'] . ' '; ?>
</h1>

<?php 
  $form = Loader::helper('form');
?>

<?php if ($this->controller->getTask() == 'add') { ?>
  <h3>Import BibTeX</h3>
<?php
  # Include the option to parse BibTeX or get a citation from a DOI
  # if we're adding a new publication
  print "<form id='bibtex-parse' method='POST' action='/cig/ajax/bibTeX.php'>";
  print "<ul class='form-section'>";
  print "  <li class='long'>";
  print $form->label ('bibtex', 'BibTeX Import');
  print $form->textarea ('bibtex', '', array ('rows' => '5', 'cols' => '80'));
  print "  </li>";
  print "</ul>";
  print $form->hidden ('from_bibtex', 'yes');
  print $form->submit ('submit', 'Parse BibTeX', $tagAttributes, $additionalClasses);
  print "</form>";
  # Use Javascript/AJAX to handle the parsing and populate forms afterwards.
?>
  <h3>Populate by DOI</h3>
<?php 
  print "<form id='doi-curl' method='POST' action='/cig/ajax/bibTeX.php'>";
  print "<ul class='form-section'>";
  print "  <li class='long'>";
  print $form->label ('doi_curl', 'DOI');
  print $form->text ('doi_curl', '', array ());
  print "  </li>";
  print "</ul>";
  print $form->hidden ('from_doi', 'yes');
  print $form->submit ('submit', 'Populate from DOI', $tagAttributes, $additionalClasses);
  print "<div id='loading-div' style='display: none;'><em><b>Loading from DOI</b></em></div>";
  print "</form>";
} ?>
  <script type="text/javascript">
    var frm = $('#bibtex-parse,#doi-curl');
    $(document).ajaxSend(function(e, jqXHR) {
      $('#loading-div').show()
    });
    $(document).ajaxComplete (function (e, jqXHR) {
      $('#loading-div').hide()
    });
    frm.submit(function (ev) {
      var f = this;
      console.log(f);

      $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize().replace(/(%0D|%0A)/gm,'+'),
        success: function (data) {
          data = jQuery.parseJSON(data);
          for (var k in data) {
            $('input#' + k).val(data[k]);
          }
         }
      });

      ev.preventDefault();
    });
  </script>

  <h3>Edit individual fields</h3>

<?php if ($this->controller->getTask() == 'edit') { ?>
  <form method="POST" id='fields' action="<?php echo $this->action('edit'); ?>">
<?php } else { ?>
  <form method="POST" id='fields' action="<?php echo $this->action(''); ?>">
<?php } ?>

<?php

  print "<ul class=form-section>";
  print "  <li class='long'>";
  print $form->label ('title', 'Title');
  print $form->text ('title', $publication_edit ['title'], array ('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('author', 'Author');
  print $form->text ('author', $publication_edit ['author'], array ('maxlength' => '1024'));

  print "  <li class='long'>";
  print $form->label ('journal', 'Journal');
  print $form->text ('journal', $publication_edit ['journal'], array ('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('year', 'Publication Year');
  print $form->text ('year', $publication_edit ['year'], array ('maxlength' => '4', 'size' => '4', 'required' => ''));

  print "  <br><br>";

  print "  <li class='long' id='cig_code-0'>";
  print $form->label ('cig_code-0', 'CIG Code 0');
  print $form->select ('cig_code-0', $cig_codes, $publication_edit['cig_code-0'], array ( 'required' => ''));
  print "  </li>";
  $i = 1;
  $temp_code = $publication_edit['cig_code-'.$i];
  while ($temp_code !== null) {
    print "<li class='long' id='cig_code-$i'>";
    print $form->label ("cig_code-$i", "CIG Code $i");
    print $form->select ("cig_code-$i", $cig_codes, $publication_edit['cig_code-'.$i]);
    print "<small id='remove-code-$i' style='cursor: pointer'><b>(Remove Code)</b></small>";
    print "</li>";
    $i += 1;
    $temp_code = $publication_edit['cig_code-'.$i];
  }
  print "<small id='add-code' style='text-indent: 50px; cursor: pointer;'><b>(Add another code)</b></small>"; 
?>
  <script type="text/javascript">
  <?php print "var n_codes = $i"; ?>

  var new_code_dropdown = function (n) {
    newcode = $('<?php
    print '<li class="long" id="cig_code-\'+n+\'">';
    print $form->label ('cig_code-\'+n+\'', 'CIG Code \'+n+\'');
    print $form->select ('cig_code-\'+n+\'', $cig_codes, "1");
    print '<small id="remove-code-\'+n+\'" style="cursor: pointer"><b>(Remove Code)</b></small>';
    print "</li>";?>');
    return newcode;
  }
  
  $('ul.form-section').on('click', 'small[id^=remove-code-]', function () { 
    var code_num = parseInt($(this).attr('id').replace (/[^\d.]/g, ''));
    $('li[id=cig_code-'+code_num+']').remove();
    if (code_num < n_codes - 1) {
      for (var i = code_num + 1; i < n_codes; ++i) {
        var new_code = 'cig_code-'+(i-1);
        $('li[id=cig_code-'+i+']').attr('id', new_code);
        $('small[id=remove-code-'+i+']').attr('id', 'remove-code-'+(i-1));
        $('label[for=cig_code-'+i+']').attr('for', new_code).html('CIG Code '+(i-1));
        $('select[name=cig_code-'+i+']').attr('id', new_code);
        $('select[id=cig_code-'+i+']').attr('id', new_code);
      }
    }
    n_codes = n_codes - 1;
  });

  $('small#add-code').click(function () {
    var old_code = n_codes - 1;
    var code_num = n_codes;
    $('li#cig_code-'+old_code).after(new_code_dropdown(code_num));
    n_codes = n_codes + 1;
  });
  </script>

<?php  
  print "<br><br>";

  print "  <li class='long'>";
  print $form->label ('type', 'Publication Type');
  print $form->select ('type', 
      array('article' => 'Article', 
            'book' => 'Book',
            'booklet' => 'Booklet',
            'conference' => 'Conference',
            'inbook' => 'In Book',
            'incollection' => 'In Collection',
            'inproceedings' => 'In Proceedings',
            'manual' => 'Manual',
            'mastersthesis' => 'Masters Thesis',
            'misc' => 'Miscellaneous',
            'phdthesis' => 'PhD Thesis',
            'procdedings' => 'Proceedings',
            'techreport' => 'Tech Report',
            'unpublished' => 'Unpublished'
           ), $publication_edit ['type']);
  print "<br><br>";

  print "  <li class='long'>";
  print $form->label ('booktitle', 'Book Title');
  print $form->text ('booktitle', $publication_edit ['booktitle'], array ('maxlength' => '255'));
  
  print "  <li class='long'>";
  print $form->label ('series', 'Series');
  print $form->text ('series', $publication_edit ['series'], array ('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('edition', 'Edition');
  print $form->text ('edition', $publication_edit ['edition'], array ('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('editor', 'Editor');
  print $form->text ('editor', $publication_edit ['editor'], array ('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('eprint', 'E-Print');
  print $form->text ('eprint', $publication_edit ['eprint'], array ('maxlength' => '255'));
  
  print "  <li class='long'>";
  print $form->label ('howpublished', 'Method of Publishing');
  print $form->text ('howpublished', $publication_edit ['howpublished'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('institution', 'Institution');
  print $form->text ('institution', $publication_edit ['institution'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('note', 'Note');
  print $form->text ('note', $publication_edit ['note'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('chapter', 'Chapter');
  print $form->text ('chapter', $publication_edit ['chapter'], array('maxlength' => '255'));
  
  print "  <li class='long'>";
  print $form->label ('publisher', 'Publisher');
  print $form->text ('publisher', $publication_edit ['publisher'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('address', 'Address');
  print $form->text ('address', $publication_edit ['address'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('volume', 'Volume');
  print $form->text ('volume', $publication_edit ['volume'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('number', 'Number');
  print $form->text ('number', $publication_edit ['number'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('school', 'School');
  print $form->text ('school', $publication_edit ['school'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('month', 'Month');
  print $form->text ('month', $publication_edit ['month'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('pages', 'Pages');
  print $form->text ('pages', $publication_edit ['pages'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('issn', 'ISSN');
  print $form->text ('issn', $publication_edit ['issn'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('isbn', 'ISBN');
  print $form->text ('isbn', $publication_edit ['isbn'], array('maxlength' => '255'));

  print "  <li class='long'>";
  print $form->label ('doi', 'DOI');
  print $form->text ('doi', $publication_edit ['doi'], array('maxlength' => '255'));
  
  print "  <li class='long'>";
  print $form->label ('url', 'URL');
  print $form->text ('url', $publication_edit ['url'], array('maxlength' => '255'));


  print "</ul>\n";

  if ($this->controller->getTask() == 'edit') {
    print $form->hidden ('publication_id', $publication_edit ['publication_id']);
    print $form->submit ('submit', 'Update Publication', $tagAttributes, $additionalClasses);
  } else {
    print $form->submit ('submit', 'Add New Publication', $tagAttributes, $additionalClasses);
  }
print "</form>";

} elseif ($this->controller->getTask() == "parseBibTeX") {
} else { ?>

  <h1>Publication</h1>
  
  <p><a href="add">Add Publication</a></p>

  <table>
    <tr>
      <th>Title</th>
      <th>CIG Code(s)</th>
    </tr>
  <?php
    foreach ($publicationList as $row) {
      print "<tr>";
      
      # Title
      print "<td>";
      print "<a href='edit/?publication_id=" . $mh->mTxt ($row[publication_id]) . "'>";
      print $mh->mTxt ($row [title]) . "</a>";
      print "</td>";

      #CIG Code
      print "<td>" . $mh->mTxt ($row [short_names]) . "</td>";

      print "</tr>";
    }
  ?>

  </table>

<?php  
} 
?>

<?php include 'common_nav_foot.php'; ?>
