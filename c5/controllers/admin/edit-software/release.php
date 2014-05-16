<?php
defined('C5_EXECUTE') or die("Access Denied.");

class AdminEditSoftwareReleaseController extends Controller {
  public function view()  { # Default - Load a list of software

    # If someone added a package, run that first
    if ( isset( $_POST['submit'] )) { 
      $this->submitAdd(); 
      $software_id = $_POST['software_id'];
      $this->set('software_id', $_POST['software_id'] );
    }

    # Otherwise, check what package the release is for
    if ( isset( $_GET['software_id'] )) { 
      $software_id = $_GET['software_id']; 
      $this->set('software_id', $software_id );
    }


    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $result = $db->Query(
	#"select * from software_tarball where software_id = ?",
	"select 
		software_tarball.id, software_id, filename, revision_num, 
		release_date, active, source, list_first, 
		software_tarball.hidden,
		software.package_title
	from software_tarball 
	left join software on software.id=software_id
	where software_id = ?",
	array( $software_id )
    );

    $this->set('softwareList', $result );

    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);
  }
  
  
  public function submitAdd() {
    # Assume data is junk, even though we have some restrictions on the input
    $vh = Loader::helper('validation/form');
    $vh->setData($_POST);
    $vh->addRequired('software_id', 'Unknown software.');
    $vh->addRequired('filename', 'Please specify a filename.');

    if ($response = $vh->test()) { # If the required data is there
      $db = Loader::db();
      # Build array with Key => Value. This helps prevent injection attacks
      $data = array(
      		'software_id' =>	$this->post('software_id'),
      		'filename' =>		$this->post('filename'),
      		'revision_num' =>	$this->post('revision_num'),
      		'release_date' =>	$this->post('release_date'),
      		'source' =>		$this->post('source'),
      		'active' =>		$this->post('active'),
      		'hidden' =>		$this->post('hidden'),
      		'list_first' =>		$this->post('list_first'),
      		'tarball_desc' =>	$this->post('tarball_desc')
      );
    }

    # Open CIG write DB
    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    # ADoDB function: AutoExecute($table, $arrFields, $mode, $where=false, $forceUpdate=true,$magicq=false)
    if ( $this->post('release_id') ) { # editing existing entry
      $db->AutoExecute('software_tarball', $data, 'UPDATE', 
		'id = ' . $this->post('release_id'));
    }
    else {
      $db->AutoExecute('software_tarball', $data, 'INSERT');
    }


    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);

  }

  public function add() { # Just need to grab the current software ID for nav
    if ( isset( $_GET['software_id'] )) {
      $software_id = $_GET['software_id'];
      $this->set('software_id', $software_id );
    }
  }
  public function edit( $software_id = 0 ) { 
    # If someone edited a package, update that first
    if ( isset( $_POST['submit'] )) { 
      $this->submitAdd(); 
      $software_id = $_POST['software_id'];
      $this->set('software_id', $software_id );
      $release_id = $_POST['release_id'];
    } # If someone is choosing the package from the main list
    else if ( isset( $_GET['software_id'] )) {
      $software_id = $_GET['software_id'];
      $this->set('software_id', $software_id );
      $release_id = $_GET['release_id'];
    }

    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    # Pass query and array in order of ?
    $release_query = "select * from software_tarball where id=?";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $result = $db->Query( $release_query, 
	array( $release_id )
    );
    # with only one ID, only have to pass along one row to the view
    $this->set('software_edit', $result->fetchRow() );

    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);
  }
}
