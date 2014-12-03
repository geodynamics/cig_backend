<?php
defined('C5_EXECUTE') or die("Access Denied.");

class AdminEditSoftwareController extends Controller {
  public function view()  { # Default - Load a list of software

    # If someone added a package, run that first
    if ( isset( $_POST['submit'] )) { $this->submitAdd(); }


    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $result = $db->Query(
	"select 
	  software.id,
	  package_title, short_name, domain_id, license,
	  mail_lists, wiki, repo_type, dev_branch, bug_reports,
          has_manual, manual_url,
	  dev_doxygen, release_doxygen,
	  jenkins_build, jenkins_test,
	  user_map, hidden, date_format( updated, '%b %e, %Y' ) as updated,
	  domain_abbr, domain_name
	from software 
	left join software_domain on software.domain_id=software_domain.id
	order by package_title"
    );

    $this->set('softwareList', $result );

    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);
  }
  
  
  public function submitAdd() {
    # Assume data is junk, even though we have some restrictions on the input
    $vh = Loader::helper('validation/form');
    $vh->setData($_POST);
    $vh->addRequired('package_title', 'Please specify a package title.');

    if ($response = $vh->test()) { # If the required data is there
      $db = Loader::db();
      # Build array with Key => Value. This helps prevent injection attacks
      		#'doxygen' =>		$this->post('doxygen'),
      		#'repo' =>		$this->post('repo'),
      $data = array(
      		'package_title' =>	$this->post('package_title'),
      		'short_name' =>		$this->post('short_name'),
      		'domain_id' =>		$this->post('domain_id'),
      		'license' =>		$this->post('license'),
      		'hidden' =>		$this->post('hidden'),
      		'mail_lists' =>		$this->post('mail_lists'),
      		'has_manual' =>		$this->post('has_manual'),
      		'manual_url' =>		$this->post('manual_url'),
      		'wiki' =>		$this->post('wiki'),
      		'wiki_desc' =>		$this->post('wiki_desc'),
      		'repo_type' =>		$this->post('repo_type'),
      		'dev_branch' =>		$this->post('dev_branch'),
      		'dev_doxygen' =>	$this->post('dev_doxygen'),
      		'release_doxygen' =>	$this->post('release_doxygen'),
      		'jenkins_build' =>	$this->post('jenkins_build'),
      		'jenkins_test' =>	$this->post('jenkins_test'),
      		'bug_reports' =>	$this->post('bug_reports'),
      		'user_map' =>		$this->post('user_map')
      );
    }

    # Open CIG write DB
    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    # ADoDB function: AutoExecute($table, $arrFields, $mode, $where=false, $forceUpdate=true,$magicq=false)
    if ( $this->post('software_id') ) { # editing existing entry
      $db->AutoExecute('software', $data, 'UPDATE', 
		'id = ' . $this->post('software_id'));
    }
    else {
      $db->AutoExecute('software', $data, 'INSERT');
    }

    #print_r ($data);


    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);

  }

  public function buildSoftwareDomainList() {
    # Get the list of types of software support, for the form
    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $result = $db->Query(
        "select
                id as sw_domain_id, domain_abbr, domain_name
        from software_domain"
    );

    foreach ( $result as $row ) { # Make the array the right format
      $softwareDomainList[ $row[sw_domain_id] ] = $row[domain_name];
    }
    $this->set('softwareDomainList', $softwareDomainList );

    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);
  }


  public function add() { #nothing needed here since there's nothing to load
    $this->buildSoftwareDomainList(); # List of options for form
  }
  public function edit( $software_id = 0 ) { 
    $this->buildSoftwareDomainList(); # List of options for form

    # If someone edited a package, update that first
    if ( isset( $_POST['submit'] )) { 
      $this->submitAdd(); 
      $software_id = $_POST['software_id'];
      $this->set('software_id', $_POST['software_id'] );
    } # If someone is choosing the package from the main list
    else if ( isset( $_GET['software_id'] )) {
      $software_id = $_GET['software_id'];
      $this->set('software_id', $_GET['software_id'] );
    }

    $ih = Loader::helper('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    # Pass query and array in order of ?
    $software_query = "select * from software where id=?";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $result = $db->Query( $software_query, 
	array( $software_id )
    );
    # with only one ID, only have to pass along one row to the view
    $this->set('software_edit', $result->fetchRow() );

    # close the db connection and load the c5 db, or chaos ensues
    $db = Loader::db(null, null, null, null, true);
  }
}
