<?php
if (isset ($_POST['from_doi'])) {
  # Request the DOI from doi.org (IP used because of strange throttling
  # issues with the URL, possibly something wonky with the DNS.)
  $url = "http://38.100.138.162/" . $_POST['doi_curl'];

#  $ch = curl_init($url);


  $opts = array(
    'http'=>array(
      'method' => "GET",
      'header' => "Accept: text/bibliography; style=bibtex",
      'timeout' => 600
    )
  );

  $context = stream_context_create($opts);
  $data = file_get_contents($url, false, $context);
 
  $_POST['bibtex'] = $data;
  $_POST['from_bibtex'] = 'yes';
}
if (isset ($_POST['from_bibtex']) or isset ($_POST['from_doi'])) {
  if (isset ($_POST['bibtex'])) {
    $bibtex = $_POST['bibtex'];

    $fields = array ("type"         => null,
                     "title"        => null,
                     "booktitle"    => null,
                     "series"       => null,
                     "edition"      => null,
                     "editor"       => null,
                     "eprint"       => null,
                     "howpublished" => null,
                     "institution"  => null,
                     "note"         => null,
                     "chapter"      => null,
                     "author"       => null,
                     "publisher"    => null,
                     "address"      => null,
                     "journal"      => null,
                     "volume"       => null,
                     "number"       => null,
                     "school"       => null,
                     "year"         => null,
                     "month"        => null,
                     "pages"        => null,
                     "issn"         => null,
                     "isbn"         => null,
                     "doi"          => null,
                     "url"          => null
                   );

    # Throw away any whitspace or the '@' character starting the entry.
    $position = 0;
    while (strpos (" \t\n\@", $bibtex[$position]) == false &&
           $position < strlen ($bibtex)) {
      $position++;
    }
    

    // scrape the type of entry.
    $position = 1;
    $type = "";
    // We will use strpos to check whether a character is in a set of
    // characters (e.g. whitespace or '{'). We also need to always check if
    // we're at the end of the string, to prevent an infinite loop.
    while (strpos (" \t\n{", $bibtex[$position]) == false && 
           $position < strlen ($bibtex)) {
      $type = $type . $bibtex[$position++];   
    }
    $fields["type"] = strtolower ($type);

    // ignore the identifier, the open brace, and any whitespace.
    while ($position < strlen ($bibtex) && 
           strpos (" \t\n{", $bibtex[$position]) !== false) {
      $position++;
    }
    while ($position < strlen ($bibtex) &&
           $bibtex[$position] !== ",") {
      $position++;
    }
    while ($position < strlen ($bibtex) &&
           strpos (" \t\n,", $bibtex[$position]) !== false) {
      $position++;
    }
        
    // Loop over the rest of the BibTeX entries, loading each into 
    // the $fields structure.
    while ($position < strlen ($bibtex) &&
           $bibtex[$position] != '}') {
    // Strip any whitespace around the items. I use strpos to determine whether 
    while ($bibtex[$position] == ' ' && $position++ < strlen ($bibtex)) {}
    // First read the name of the field
    $name = "";
    while ($position < strlen ($bibtex) &&
      strpos (" \t\n=", $bibtex[$position]) == false) {
        $name = $name . $bibtex[$position++];
      }
      $name = strtolower ($name);
        
      // ignore the '=' field and any surrounding whitespace
      while ($position < strlen ($bibtex) &&
             strpos (" \t\n=", $bibtex[$position]) !== false) {$position++;}
      // Ignore the braces surrounding the value for the field, and capture
      // the value.
      $value = "";
      $balance = 0;
      do {
        if ($bibtex[$position] == '{') {
          // Ignore the first opening brace
          if ($balance > 0) $value = $value . $bibtex[$position];
          $balance++;
        } elseif ($bibtex[$position] == '}') {
          // And the last closing brace
          if ($balance !== 1) $value = $value . $bibtex[$position];
          $balance--;
        } else {
          $value = $value . $bibtex[$position];
        }         
      } while ($position++ < strlen ($bibtex) &&
               $balance !== 0);
      // Ignore any whitespace or comma after between the value and the 
      // next entry
      while ($position < strlen ($bibtex) &&
             strpos (" \t\n,", $bibtex[$position]) !== false) {$position++;}
          
      // Load the entry into the fields of our class.
      $fields[$name] = $value;
    }

    print json_encode($fields);
  }
}
?>

