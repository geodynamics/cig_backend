<?php
  defined ('C5_EXECUTE') or die ("Access Denied.");

  class BibTeXHelper {
 
    public function parse_raw_BibTeX ($rawBibTeX) {
      
      $bibTeX = new BibTeXEntry();

      $rawBibTeX = trim ($rawBibTeX);
    
      $bibTeX->import_BibTeX ($rawBibTeX);

      return $bibTeX;
    }

  };

  class BibTeXEntry {
    // the $fields array holds the actual BibTeX fields
    private $fields = array ("type"         => null,
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

    // Constructor with the option to construct from an array, (i.e. from 
    // the DB) or as a blank entry.
    public function __construct() {
      $args = func_get_args();
      if (gettype ($args[0]) == 'array') {
        $this->__construct_array ($args[0]);
      } elseif (count ($args) !== 0)
        throw new Exception ('NO CONSTRUCTOR', NULL, NULL);
    }

    // Construct a BibTeX citation from an array. This will be useful for
    // interacting with the database.
    private function __construct_array ($fields) {
      if (!array_key_exists ("type", $fields))
        throw new Exception ("Cannot create a BibTeX Entry without a type!", NULL, NULL);
      foreach ($fields as $key => $value) {
        if (array_key_exists ($key, $this->fields)) {
          $this->fields[$key] = $value;
        } else
          throw new Exception ("BibTeX format has no such entry: \"" . $key . "\".", NULL, NULL);
      }
    }
    
    // Display the BibTeX entry as a formatted citation 
    public function display_citation() {
      // TODO: Figure out which fields are required to be a valid citation.
      //   Should we hide the 'year' field if there is no 'year' in the bibTeX,
      //   or should we require it to be there in order to display the citation.
      print $this->fields["author"]." (".$this->fields["year"].") \"".$this->fields["title"]."\"";
      if (!is_null ($this->fields["journal"])) {
        print ", <i>".ucwords($this->fields["journal"])."</i>"; 
        if (!is_null ($this->fields["volume"])) {
          print " Volume " . $this->fields["volume"];
          if (!is_null ($this->fields["number"])) {
            print " (".$this->fields["number"].")";
          }
        }
      }

      if (!is_null ($this->fields["pages"]) && preg_match("/\d+\-\d+/",$this->fields["pages"])) {
        print " ".$this->fields["pages"];
      }
      if (!is_null ($this->fields["doi"])) {
        print " DOI: <a href=\"http://www.dx.doi.org/".$this->fields["doi"]."\">".$this->fields["doi"]."</a>";
      }
      print "<br>";
    }

    public function import_BibTeX ($rawBibTeX) {
      // TODO: Make sure this doesn't break on invalid input!

      // Check for the '@' delimiter. I'm not certain whether we need to
      // require this, but it is in the BibTeX standard, so why not.
      if ($rawBibTeX[0] != '@') {
        throw new Exception ('Invalid BibTeX: Missing \'@\' delimiter.');
      }

      // scrape the type of entry.
      $position = 1;
      $type = "";
      // We will use strpos to check whether a character is in a set of
      // characters (e.g. whitespace or '{'). We also need to always check if
      // we're at the end of the string, to prevent an infinite loop.
      while (strpos (" \t\n{", $rawBibTeX[$position]) == false && 
             $position < strlen ($rawBibTeX)) {
        $type = $type . $rawBibTeX[$position++];
       
      }
      $this->fields["type"] = strtolower ($type);

      // ignore the identifier, the open brace, and any whitespace.
      while ($position < strlen ($rawBibTeX) && 
             strpos (" \t\n{", $rawBibTeX[$position]) !== false) {
        $position++;
      }
      while ($position < strlen ($rawBibTeX) &&
             $rawBibTeX[$position] !== ",") {
        $position++;
      }
      while ($position < strlen ($rawBibTeX) &&
             strpos (" \t\n,", $rawBibTeX[$position]) !== false) {
        $position++;
      }
      
      // Loop over the rest of the BibTeX entries, loading each into 
      // the $fields structure.
      while ($position < strlen ($rawBibTeX) &&
             $rawBibTeX[$position] != '}') {
        // Strip any whitespace around the items. I use strpos to determine whether 
        while ($rawBibTeX[$position] == ' ' && $position++ < strlen ($rawBibTeX)) {
        }
        // First read the name of the field
        $name = "";
        while ($position < strlen ($rawBibTeX) &&
               strpos (" \t\n=", $rawBibTeX[$position]) == false) {
          $name = $name . $rawBibTeX[$position++];
        }
        $name = strtolower ($name);
        
	// ignore the '=' field and any surrounding whitespace
        while ($position < strlen ($rawBibTeX) &&
               strpos (" \t\n=", $rawBibTeX[$position]) !== false) {$position++;}
        // Ignore the braces surrounding the value for the field, and capture
        // the value.
        $value = "";
        $balance = 0;
        do {
          if ($rawBibTeX[$position] == '{') {
            // Ignore the first opening brace
            if ($balance > 0) $value = $value . $rawBibTeX[$position];
            $balance++;
          } elseif ($rawBibTeX[$position] == '}') {
            // And the last closing brace
            if ($balance !== 1) $value = $value . $rawBibTeX[$position];
            $balance--;
          } else {
            $value = $value . $rawBibTeX[$position];
          }         
        } while ($position++ < strlen ($rawBibTeX) &&
                 $balance !== 0);
        // Ignore any whitespace or comma after between the value and the 
        // next entry
        while ($position < strlen ($rawBibTeX) &&
               strpos (" \t\n,", $rawBibTeX[$position]) !== false) {$position++;}
        
	// Load the entry into the fields of our class.
        $this->fields[$name] = $value;
      }
    }

    public function export_BibTeX() {
      $rawBibTeX = "@";
      $rawBibTeX = $rawBibTeX . $this->fields["type"] . "{entry";

      foreach ($this->fields as $key => $value) {
        if ($key !== "type" && $value)
          $rawBibTeX = $rawBibTeX . ", " . $key . "={" . $value . "}";
      }

      $rawBibTeX = $rawBibTeX . "}";
    
      return $rawBibTeX;
    }

    // Export the BibTeX fields as an array (useful for interacting with the
    // database.)
    public function export_fields() {
      return $this->fields; // I think this is a copy... php is strange.
    }

    // Declaration of getters/setters
    public function set_type ($type) {
      $this->fields["type"] = $type;
    }
    public function get_type() {
      return $this->fields["type"];
    }

    public function set_title ($title) {
      $this->fields["title"] = $title;
    }
    public function get_title() {
      return $this->fields["title"];
    }

    public function set_booktitle ($booktitle) {
      $this->fields["booktitle"] = $booktitle;
    }
    public function get_booktitle() {
      return $this->fields["booktitle"];
    }

    public function set_series ($series) {
      $this->fields["series"] = $series;
    }
    public function get_series() {
      return $this->fields["series"];
    }

    public function set_edition ($edition) {
      $this->fields["edition"] = $edition;
    }
    public function get_edition() {
      return $this->fields["edition"];
    }

    public function set_editor ($editor) {
      $this->fields["editor"] = $editor;
    }
    public function get_editor() {
      return $this->fields["editor"];
    }

    public function set_eprint ($eprint) {
      $this->fields["eprint"] = $eprint;
    }
    public function get_eprint() {
      return $this->fields["eprint"];
    }

    public function set_howpublished ($howpublished) {
      $this->fields["howpublished"] = $howpublished;
    }
    public function get_howpublished() {
      return $this->fields["howpublished"];
    }

    public function set_institution ($institution) {
      $this->fields["institution"] = $instution;
    }
    public function get_institution() {
      return $this->fields["institution"];
    }

    public function set_note ($note) {
      $this->fields["note"] = $note;
    }
    public function get_note() {
      return $this->fields["note"];
    }

    public function set_chapter ($chapter) {
      $this->fields["chapter"] = $chapter;
    }
    public function get_chapter() {
      return $this->fields["chapter"];
    }
    
    public function set_author ($author) {
      $this->fields["author"] = $author;
    }
    public function get_author() {
      return $this->fields["author"];
    }

    public function set_publisher ($publisher) {
      $this->fields["publisher"] = $publisher;
    }
    public function get_publisher() {
      return $this->fields["publisher"];
    }

    public function set_address ($address) {
      $this->fields["address"] = $address;
    }
    public function get_address() {
      return $this->fields["address"];
    }

    public function set_journal ($journal) {
      $this->fields["journal"] = $journal;
    }
    public function get_journal() {
      return $this->fields["journal"];
    }

    public function set_volume ($volume) {
      $this->fields["volume"] = $volume;
    }
    public function get_volume() {
      return $this->fields["volume"];
    }

    public function set_number ($number) {
      $this->fields["number"] = $number;
    }
    public function get_number() {
      return $this->fields["number"];
    }

    public function set_school ($school) {
      $this->fields["school"] = $school;
    }
    public function get_school() {
      return $this->fields["school"];
    }
    
    public function set_year ($year) {
      $this->fields["year"] = $year;
    }
    public function get_year() {
      return $this->fields["year"];
    }

    public function set_month ($month) {
      $this->fields["month"] = $month;
    }
    public function get_month() {
      return $this->fields["month"];
    }

    public function set_pages ($pages) {
      $this->fields["pages"] = $pages;
    }
    public function get_pages() {
      return $this->fields["pages"];
    }

    public function set_issn ($issn) {
      $this->fields["issn"] = $issn;
    }
    public function get_issn() {
      return $this->fields["issn"];
    }

    public function set_isbn ($isbn) {
      $this->fields["isbn"] = $isbn;
    }
    public function get_isbn() {
      return $this->fields["isbn"];
    }
    
    public function set_doi ($doi) {
      $this->fields["doi"] = $doi;
    }
    public function get_doi() {
      return $this->fields["doi"];
    }

    public function set_url ($url) {
      $this->fields["url"] = $url;
    }
    public function get_url() {
      return $this->fields["url"];
    }
  }; 

?>
