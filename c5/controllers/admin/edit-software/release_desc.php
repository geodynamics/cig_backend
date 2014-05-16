<?php
defined('C5_EXECUTE') or die("Access Denied.");

class AdminEditSoftwareReleaseDescController extends Controller {
  public function view()  { # Default - Load a list of software

    # If someone added a package, run that first
    if ( isset( $_POST['submit'] )) { 
      $this->submitAdd(); 
      $software_id = $_POST['software_id'];
      $this->set('software_id', $_POST['software_id'] ); # Get var for nav
    }

    # Otherwise, check what package the release desc is for
    if ( isset( $_GET['software_id'] )) { 
      $software_id = $_GET['software_id']; 
      $this->set('software_id', $software_id ); # Get var for nav
    }


    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $result = $db->Query(
	"select 
		software_desc.id, software_id, desc_revision_num, revision_num, 
		desc_short, desc_long, 
		general_binary_desc, general_source_desc,
		status,
		software_status.status_long,
		software.package_title
	from software_desc
	left join software on software.id=software_id
	left join software_status on software_status.id=status
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
    $vh->addRequired('desc_revision_num', 'Missing description revision num.');

    if ($response = $vh->test()) { # If the required data is there
      $db = Loader::db();
      # Build array with Key => Value. This helps prevent injection attacks
      #		'cig_supported' =>	$this->post('cig_supported'),
      #		'revision_num' =>	$this->post('revision_num'),
      $data = array(
      		'software_id' =>	$this->post('software_id'),
      		'desc_revision_num' =>	$this->post('desc_revision_num'),
      		'status' =>		$this->post('status'),
      		'desc_short' =>		$this->post('desc_short'),
      		'desc_long' =>		$this->post('desc_long'),
      		'general_binary_desc' =>$this->post('general_binary_desc'),
      		'general_source_desc' =>$this->post('general_source_desc')
      );
    }

    # Open CIG write DB
    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    # ADoDB function: AutoExecute($table, $arrFields, $mode, $where=false, $forceUpdate=true,$magicq=false)
    if ( $this->post('release_desc_id') ) { # editing existing entry
      $db->AutoExecute('software_desc', $data, 'UPDATE', 
		'id = ' . $this->post('release_desc_id'));
    }
    else {
      $db->AutoExecute('software_desc', $data, 'INSERT');
    }


    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);

  }

  public function buildSoftwareStatusList() {
    # Get the list of types of software support, for the form
    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $result = $db->Query(
        "select
                id as sw_status_id, status_long, status_short
        from software_status"
    );

    foreach ( $result as $row ) { # Make the array the right format
      $softwareStatusList[ $row[sw_status_id] ] = $row[status_short];
    }
    $this->set('softwareStatusList', $softwareStatusList );

    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);
  }


  public function add() { # Just grab what software this is, for nav
    if ( isset( $_GET['software_id'] )) {
      $software_id = $_GET['software_id'];
      $this->set('software_id', $software_id );
    }

    $this->buildSoftwareStatusList(); # Make the software status list
  }
  public function edit( $software_id = 0 ) { 
    # If someone edited a package, update that first
    if ( isset( $_POST['submit'] )) { 
      $this->submitAdd(); 
      $software_id = $_POST['software_id'];
      $this->set('software_id', $software_id );
      $release_desc_id = $_POST['release_desc_id'];
    } # If someone is choosing the package from the main list
    else if ( isset( $_GET['software_id'] )) {
      $software_id = $_GET['software_id'];
      $this->set('software_id', $software_id );
      $release_desc_id = $_GET['release_desc_id'];
    }

    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    # Pass query and array in order of ?
    $release_query = "select * from software_desc where id=? order by software_desc.id desc";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $result = $db->Query( $release_query, 
	array( $release_desc_id )
    );
    # with only one ID, only have to pass along one row to the view
    $this->set('software_edit', $result->fetchRow() );

    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);


    $this->buildSoftwareStatusList(); # Make the software status list
  }
}
