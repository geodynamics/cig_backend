<?php 
  defined ('C5_EXECUTE') or die ("Access Denied.");

  $mh = Loader::helper('mathToolbox');
  $bh = Loader::helper('bibTeX'); 

  $codes = $this->controller->getCodes();
?>

  <div class="sidebar_left">
    <ul class="nav">
      <?php
        # Create a sidebar of anchor links to each code
        foreach ($codes as $code_name) {
          print "<li>";
          print "<a href='#" . $code_name["short_name"] . "'>" . $code_name["package_title"] . "</a>";
          print "</li>";
        }
      ?>
    </ul>
  </div>

  <div class="content content_left">
    <h1>Publications</h1>
    <p>An evolving, self-reporting reference list of published journal articles
     using CIG Software.</p>
    <p><a title="Submit" href="/cig/news/publications/submit/">Submit</a> your 
    publications using the CIG Software for inclusion below.</p>
    <?php
      # Get and print the total number of publications
      $num_total_pubs = $this->controller->getCodePublicationCount();
      print "Total of " . $num_total_pubs["num_pubs"] . " publications.";

      # Go through each code and print publications
      foreach ($codes as $code_name) {
        # Create the anchor and print the package name
        print "<h2><a name='" . $code_name["short_name"] . "'>" . $code_name["package_title"] . "</a></h2>";
 
        # print the number of publications just for this code
        $num_pubs = $this->controller->getCodePublicationCount($code_name["id"]);
        print $num_pubs["num_pubs"] . " publications";

        # Get all publications associated with this code
        $cur_year = 0;
        $pub_list = $this->controller->getCodePublications($code_name["id"]);
        foreach ($pub_list as $pub) {
          if ($pub["year"] != $cur_year) {
            if ($cur_year != 0) print "</ul>";
            $cur_year = $pub["year"];
            print "<h3>" . $cur_year . "</h3>";
            print "<ul>";
          }
          print "<li>";
          $bibtexEntry = new BibTeXEntry ($pub);
          $bibtexEntry->display_citation();
          print "</li>";
        }
        if ($cur_year != 0) print "</ul>";
      }
    ?>
  </div>




