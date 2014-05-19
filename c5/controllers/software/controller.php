<?php
defined ('C5_EXECUTE') or die("Access Denied.");

class SoftwareController extends Controller {
  public function view ($package=null) {
    if (isset ($package)) {
      $this->set ('task', "detail");
      $this->set ('package', $package);     
    } else {
      $this->set ('task', "list");
    }
  }

  public function getDomains () {
    $ih = Loader::helper ('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();   

    $db->SetFetchMode (ADODB_FETCH_ASSOC);
    $domains = $db->Query (
        "select
           domain_abbr, domain_name
         from
           software_domain;"
        );

    $this->set ('domains', $domains);
    
    $db = Loader::db(null, null, null, null, true);

    return $domains;
  }

  public function getCodesByDomain ($domain) {
    $ih = Loader::helper ('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();
   
    $db->SetFetchMode (ADODB_FETCH_ASSOC);
    $packages = $db->Query (
        "select distinct
           software.package_title,
           software.short_name,
           software_desc.desc_short,
           software_status.status_short
         from
           software, 
           software_domain, 
           software_desc, 
           software_status
         where 
           software_domain.domain_abbr = '".$domain."' and
           software_domain.id = software.domain_id and
           software.id = software_desc.software_id and
           software_desc.status = software_status.id and
           software.hidden = 'n'
         order by
           software_desc.status, 
           software.updated;"
        );

    $db = Loader::db(null, null, null, null, true);

   return $packages;
  }

  public function getCodeDetails ($package) {
    $ih = Loader::helper ('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();
     
    $db->SetFetchMode (ADODB_FETCH_ASSOC);
    $details = $db->Query (
        "select distinct
           software.short_name, 
           software.package_title, 
           software.license, 
           software.mail_lists, 
           software.repo_type, 
           software.has_manual, 
           software.user_map,
           software.dev_branch,
           software_desc.desc_revision_num,
           software_desc.revision_num,
           software_desc.desc_short,
           software_desc.desc_long,
           software_status.status_long,
           software_desc.cig_supported,
           software_desc.general_binary_desc,
           software_desc.general_source_desc,
           software.release_doxygen,
           software.dev_doxygen
         from
           software,
           software_desc,
           software_status
         where
           software_desc.software_id = software.id and
           software_status.id = software_desc.status and
           software.short_name = '".$package."'
         order by
           software_desc.desc_revision_num desc
         limit 1;"         
        );

    $db = Loader::db(null, null, null, null, true);

    return $details->fetchRow();  
  }

  public function getGithubStats ($package) {
    $request = new HttpRequest ();
    $request->setUrl ("https://api.github.com/repos/geodynamics/".$package."/stats/commit_activity");
    $request->send();

    if ($request->getResponseCode() != 200) {
      if ($request->getResponseCode() == 403) {
        print "<br><a href='https://github.com/geodynamics/".$package."/graphs/commit-activity'>Commit statistics available on GitHub</a>";
      }
    } else {
      $raw_stats = $request->getResponseBody();
      $stats = json_decode ($raw_stats);

      $month_commits = 0;
      $year_commits = 0;

      foreach ($stats as $index => $week) {
        if ($index < 4) {
          $month_commits += $week->total;
        }
        $year_commits += $week->total;
      }
      
      print "<br><a href='https://github.com/geodynamics/".$package."/graphs/commit-activity'>".$month_commits." commits this past month, ".$year_commits." commits this past year.</a>";
    } 
  }


  // Function to reduce existing duplicated code - mw 2014/04
  public function getTarballs ($package, $tarball_type='binaries', $is_active='y') {
    $ih = Loader::helper ('cigSoftwareW');
    $db = $ih->cigSoftwareWDB();

    if ($tarball_type == 'binaries') { $source='n'; }
    else { $source='y'; }
   
    $db->SetFetchMode (ADODB_FETCH_ASSOC);
    $tarballs = $db->Query (
        "select
           (case
                when locate('github', software_tarball.filename) = 1 then
                concat('/cig/software/', software_tarball.filename) else
                concat('/cig/software/',software.short_name,'/',software_tarball.filename)
           end) as url,
           (case
                when locate('/',software_tarball.filename) <> 0 then
                substring_index(software_tarball.filename, '/', -1) else
                software_tarball.filename
           end) as filename,
           software_tarball.release_date,
           software_tarball.revision_num,
           software_tarball.tarball_desc,
           software_tarball.repo
         from
           software,
           software_tarball
         where 
           software_tarball.software_id = software.id and
           software_tarball.active = '".$is_active."' and
           software_tarball.source = '".$source."' and
           software.short_name = '".$package."'
         order by
           software_tarball.release_date desc;"
        );

    $db = Loader::db(null, null, null, null, true);

    $this->set($tarball_type, $tarballs);
    return $tarballs;
  }

  public function getBinaryTarballs ($package) {
    return $this->getTarballs($package, 'binaries');
  }
  public function getSourceTarballs ($package) {
    return $this->getTarballs($package, 'sources');
  }


  public function getLicense ($license) {
    switch ($license) {
      case "gnu2":
      case "gnu3":
        echo "GNU Public License";
        break;
      case "cecill2":
        echo "CeCILL License (version 2)";
        break;
      case "mit":
        echo "MIT License";
        break;
      case "bsd3":
        echo "BSD License";
        break;
      case "none":
        echo "unlicensed";
        break;
    }
  }
}

