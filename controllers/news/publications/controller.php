<?php
  defined ("C5_EXECUTE") or die ("Access Denied.");

  class NewsPublicationsController extends Controller {
    public function view() {}
    
    # Get an array of all valid CIG codes from the software table
    public function getCodes() {
      $ih = Loader::helper ('cigSoftwareW');
      $db = $ih->cigSoftwareWDB();

      $db->SetFetchMode (ADODB_FETCH_ASSOC);
      $packages = $db->Query (
          "SELECT
             software.id,
             software.short_name,
             software.package_title
           FROM
             software
           WHERE
             software.hidden = 'n'
           ORDER BY
             software.package_title;
          ");

      $db = Loader::db (null, null, null, null, true);

      return $packages;
    }

    # Get the number of publications associated with the specified
    # package (short_name format. If no package is specified, return
    # the total number of publications for all packages.
    # The value is returned in an array indexed by "num_pubs"
    public function getCodePublicationCount ($package="") {
      $ih = Loader::helper ('cigSoftwareW');
      $db = $ih->cigSoftwareWDB();

      $db->SetFetchMode (ADODB_FETCH_ASSOC);

      if ($package !== "") {
        $pub_count_query = "SELECT COUNT(*) AS num_pubs FROM pub WHERE pub.cig_code LIKE '%," . $package . ",%';";
      } else {
        $pub_count_query = "SELECT COUNT(*) AS num_pubs FROM pub;";
      }
      $pub_count = $db->Query ($pub_count_query);

      $db = Loader::db (null, null, null, null, true);

      return $pub_count->fetchRow();
    }
    
    # Get the publications associated with the specified code,
    # in reverse chronological order
    public function getCodePublications ($package) {
      $ih = Loader::helper ('cigSoftwareW');
      $db = $ih->cigSoftwareWDB();
      
      $db->SetFetchMode (ADODB_FETCH_ASSOC);
      $pubs = $db->Query (
          "SELECT
             pub.type,
             pub.title,
             pub.booktitle,
             pub.series,
             pub.edition,
             pub.editor,
             pub.eprint,
             pub.howpublished,
             pub.institution,
             pub.note,
             pub.chapter,
             pub.author,
             pub.publisher,
             pub.address,
             pub.journal,
             pub.volume,
             pub.number,
             pub.school,
             pub.year,
             pub.month,
             pub.pages,
             pub.issn,
             pub.isbn,
             pub.doi,
             pub.url
           FROM
             pub
           WHERE
             pub.cig_code LIKE '%," . $package . ",%'
           ORDER BY
             year DESC;
          ");

      $db = Loader::db (null, null, null, null, true);

      return $pubs;
    }
  }
?>

