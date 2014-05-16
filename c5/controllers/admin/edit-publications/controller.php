<?php
# Don't allow this page to be accessed outside of C5.
defined ('C5_EXECUTE') or die("Access Denied.");

class AdminEditPublicationsController extends Controller {
  # Default - Load a list of publications.
  public function view() {

    # If someone added a publication, add that to the database first.
    if (isset ($_POST['submit'])) {$this->submitAdd();}

    # Load the publications database helper.
    $ih = Loader::helper ('cigPublicationsW');
    $db = $ih->cigPublicationsWDB();

    # Get a list of all publications from the database.
    $db->SetFetchMode (ADODB_FETCH_ASSOC);
    $result = $db->Query (
        "SELECT
           pub.id as publication_id,
           pub.title as title,
           GROUP_CONCAT(software.short_name SEPARATOR ', ') as short_names
         FROM
           pub,
           software
         WHERE
           INSTR(pub.cig_code, CONCAT(',', software.id, ',')) != 0
         GROUP BY
           1
         ORDER BY
           publication_id"
       );

    $this->set ('publicationList', $result);

    # Close the DB connection and load the C5 DB, or chaos ensues.
    $db = Loader::db (null, null, null, null, true);
  }
  
  # Insert or update a publication in the DB.
  public function submitAdd() {
    # Assume the data is junk, although we have some restrictions on the input.
    $vh = Loader::helper ('validation/form');
    $vh->setData ($_POST);

    # If the required data is there, prepare to add to the database.
    if ($response = $vh->test()) {
      $db = Loader::db();
      # Build array with Key => Value. This helps prevent injection attacks.
      $i = 0;
      $cig_code = ',';
      $temp_code = $this->post('cig_code-'.$i);
      while ($temp_code !== null) {
        $cig_code = $cig_code . $temp_code . ',';
        $i += 1;
        $temp_code = $this->post('cig_code-'.$i);
      }
      $data = array (
                'cig_code' =>     $cig_code,
                'type' =>         $this->post ('type'),
                'title' =>        $this->post ('title'),
                'author' =>       $this->post ('author'),
                'year' =>         $this->post ('year'),
                'booktitle' =>    $this->post ('booktitle'),
                'series' =>       $this->post ('series'),
                'edition' =>      $this->post ('edition'),
                'editor' =>       $this->post ('editor'),
                'eprint' =>       $this->post ('eprint'),
                'howpublished' => $this->post ('howpublished'),
                'institution' =>  $this->post ('institution'),
                'note' =>         $this->post ('note'),
                'chapter' =>      $this->post ('chapter'),
                'publisher' =>    $this->post ('publisher'),
                'address' =>      $this->post ('address'),
                'journal' =>      $this->post ('journal'),
                'volume' =>       $this->post ('volume'),
                'number' =>       $this->post ('number'),
                'school' =>       $this->post ('school'),
                'month' =>        $this->post ('month'),
                'pages' =>        $this->post ('pages'),
                'issn' =>         $this->post ('issn'),
                'isbn' =>         $this->post ('isbn'),
                'doi' =>          $this->post ('doi'),
                'url' =>          $this->post ('url')
              );
      foreach ($data as $key => $value) {
        if ($value == '') $data[$key] = NULL;
      }

      # Open CIG write DB.
      $ih = Loader::helper ('cigPublicationsW');
      $db = $ih->cigPublicationsWDB();

      if ($this->post ('publication_id')) {
        # Editing existing entry.
        $db->AutoExecute ('pub', $data, 'UPDATE', 
                  'id = ' . $this->post('publication_id'));
      } else {
        # Creating new entry
        $db->AutoExecute ('pub', $data, 'INSERT');
      }

      # Close the DB connection and load the C5 DB, or chaos ensues.
      $db = Loader::db (null, null, null, null, true);
    }
  }

  # We don't need to do anything here (yet.) In the future, we will 
  # need to load list entries.
  public function add() {
    $this->get_cig_codes(); 
  }


  public function edit ($publication_id = 0) {
    $this->get_cig_codes(); 
    # If someone edited a publication, update that first.
    if (isset ($_POST['submit'])) {    
      $this->submitAdd();
      $publication_id = $_POST['publication_id'];
      $this->set ('publication_id', $_POST['publication_id']);
    } else if (isset ($_GET['publication_id'])) {
      # If someone is choosing the publication from the main list.
      $publication_id = $_GET['publication_id'];
      $this->set ('publication_id', $_GET['publication_id']);
    }
 
    # Load the publications DB.
    $ih = Loader::helper ('cigPublicationsW');
    $db = $ih->cigPublicationsWDB();

    # Pass query and array in order of ?
    $publication_query = "SELECT 
                            *
                          FROM
                            pub 
                          WHERE 
                            id = ?;";
    $db->SetFetchMode (ADODB_FETCH_ASSOC);
    $result = $db->Query ($publication_query, 
        array ($publication_id)
    );
    # With only one ID, only have to pass along one row to the view.
    $publication_edit = $result->fetchRow();
    # Get the $cig_codes string to be tokenized for editing
    $cig_codes = $publication_edit['cig_code'];

    # Tokenize the list of codes at the commas, and separate into different pieces.
    $i = 0;
    $tok = strtok($cig_codes, ',');

    while ($tok !== false) {
      $publication_edit['cig_code-'.$i] = $tok;
      $tok = strtok(',');
      $i += 1;
    }
    
    # Set the publication_edit variable (again)
    $this->set ('publication_edit', $publication_edit);
    # Close the DB connection and load the C5 DB, or chaos ensues.
    $db = Loader::db (null, null, null, null, true);
  }

  public function parseBibTeX() {
    $bh = Loader::helper ('bibTeX');
    
    print json_encode($bh->parse_raw_BibTeX($_POST['bibtex'])->export_fields());
  }

  public function get_cig_codes() {
    $ih = Loader::helper ('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    $code_query = "SELECT id AS code_id, package_title AS title FROM software WHERE hidden='n'";
    $result = $db->Query ($code_query);

    foreach ($result as $row) {
      $software_list[$row[code_id]] = $row[title];
    }
    $this->set ('cig_codes', $software_list);

    $db = Loader::db (null, null, null, null, true);
  }
}
    
?> 
