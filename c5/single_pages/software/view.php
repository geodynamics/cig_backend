<?php defined ('C5_EXECUTE') or die ("Access Denied."); 

  $nh = Loader::helper('navigation');
  $currentURL = NavigationHelper::getLinkToCollection(Page::getCurrentPage(), true);

  if ($task == "list") {
    $domains = $this->controller->getDomains();
?>





<div class="sidebar_left">
  <ul class="nav" id="domain-nav">
    <?php foreach ($domains as $domain) { ?>
      <li>
        <a href="#<?php print $domain["domain_abbr"];?>"><?php print $domain["domain_name"];?></a>
      </li>
    <?php }; ?>
  </ul>
</div>

<div class="content content_left">
  <h1>List of Software</h1>
  <table border="0">
    <thead>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td>&nbsp;</td>
        <td><address>code</address></td>
        <td>&nbsp;</td>
        <td><address>description</address></td>
        <td>&nbsp;</td>
        <td><address>status</address></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($domains as $domain) { ?>
        <tr>
          <td colspan="5" align="left" valign="top">
            <a name="<?php print $domain["domain_abbr"]; ?>">
              <h3 style="color: #BF9900;"><strong>
                <?php print $domain["domain_name"]; ?>
              </strong></h3>
            </a>
          </td>
          <td>&nbsp;</td><td>&nbsp;</td>
        </tr>
        <?php
          $codes = $this->controller->getCodesByDomain ($domain["domain_abbr"]);
          
          foreach ($codes as $code) {
        ?>
          <tr>
            <td align="left" valign="top">&nbsp;</td><td>&nbsp;</td>
            <td align="left" valign="top">
              &nbsp;<a href="<?php print $currentURL.$code["short_name"]; ?>/"><?php print $code["package_title"]; ?></a>
            </td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">
              <?php print $code["desc_short"]; ?>
            </td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">
              <?php print $code["status_short"]; ?>
            </td>
          </tr>
        <?php }; ?>
      <?php }; ?>
    </tbody>
  </table>

  <address>&nbsp;</address>
  <address>Status:</address>
  <address>D_CIG = Developed by CIG.</address>
  <address>D_CONTRIB = Developed by community contributors.</address>
  <address>S_CIG = Supported by CIG.</address>
  <address>S_CONTRIB = Supported by community contributors.</address>
  <address>A = Archived. No development activity, not supported.</address>
  <address>For descriptions of software support status levels see <a href="/cig/software/support-policy/">Software Support Policies</a>.</address>

</div>
<?php
  } else if ($task == "detail") {
    $details = $this->controller->getCodeDetails ($package);

    if ($details[short_name] == "") die ('No such package!');   
 
    $tok = strtok ($details[mail_lists], ',');
    $mail_list_array = array();
    while ($tok !== false) {
      $mail_list_array[] = $tok;
      $tok = strtok(',');
    };
?>
    <div class="sidebar_left">
      <ul class="nav">
        <li><a href="/cig/software/">Software Package List</a></li>
        <li><h3><?php print $details[package_title]; ?></h3></li>
        <li><a href="#release">Current Release</a></li>
        <li><a href="#users">User Resources</a></li>
        <li><a href="#developers">Developer Resources</a></li>
        <?php if ($details[user_map] == "y") { ?>
          <li><a href="#usermap">User Map</a></li>
        <?php }; ?>
      </ul>
    </div>
  
    <div class="content content_left">
      <div class="software_status">
        <?php if ($details[has_manual] == 'y' && $details[manual_url] == NULL) { ?>
          <a href="/cig/software/<?php print $details[short_name]; ?>/<?php print $details[short_name]; ?>-manual.pdf">
            <img border="0" class="ccm-image-block" alt src="/cig/software/<?php print $details[short_name]; ?>/cover-small" width="250px">
          </a>
        <?php } else if ($details[has_manual] == 'y' && $details[manual_url] != NULL) { ?>
          <a href="<?php print $details[manual_url]; ?>">
            <img border="0" class="ccm-image-block" alt src="/cig/software/<?php print $details[short_name]; ?>/cover-small" width="250px">
          </a>
        <?php }; ?>
        <p>
          <strong>Status:</strong><br>
          <?php print $details[status_long];?>

        </p>
        <p>
          <strong>Code changes:</strong>
          <?php $this->controller->getGithubStats ($package); ?>
        </p>
        <p>
          <strong>Contact:</strong>
          <?php 
            foreach ($mail_list_array as $i => $value) {
              if ($value == "aspect") {
                $mail_list_array[$i] = "aspect-devel";
              } else {
                $mail_list_array[$i] = "cig-".$value;
              };
          ?>
              <br><a href="//lists.geodynamics.org/cgi-bin/mailman/listinfo/<?php print $mail_list_array[$i]; ?>"><?php print $mail_list_array[$i]; ?>@geodynamics.org</a>
          <?php }; ?>  
        </p>
        <p>
          <strong>Bug reports:</strong><br>
          <a href="//www.github.com/geodynamics/<?php print $package; ?>/issues">Github Issue Tracker</a>
        </p>
        <p>
          <strong>License:</strong><br>
          <?php $this->controller->getLicense ($details[license]); ?>
        </p>
      </div>

      <h1><?php print $details[package_title]; ?></h1>
      <p><strong id="parent-fieldname-description">
        <?php print $details[desc_short]; ?>
      </strong></p>
      <p>
        <?php print $details[desc_long]; ?>
      </p>

      <a name="release"><h2>Current Release</h2></a>
      <?php
        $binaries = $this->controller->getBinaryTarballs ($package);
        $sources = $this->controller->getSourceTarballs ($package);
	$old_binaries = $this->controller->getTarballs ($package, 'binaries', 'n');
	$old_sources = $this->controller->getTarballs ($package, 'sources', 'n');

        if ($details[general_binary_desc] != "" or (!$binaries->EOF)) { 
      ?>
        <h3>Binaries</h3>
        <p><?php print $details[general_binary_desc]; ?></p>
        
        <?php
          foreach ($binaries as $tarball) {
        ?>  
            <p class="software-release">
              <a href="<?php print $tarball[url]; ?>"><?php print $tarball[filename]; ?></a>[<?php print $tarball[release_date]; ?>]
            </p>
            <p class="software-description">
              <?php print $tarball[tarball_desc]; ?>
            </p>        
      <?php 
          };
        };
      ?>

      <?php
        if (!$old_binaries->EOF) { 
      ?>
        <h3>View Prior Binary Releases</h3>
         <span onclick="show('list1')" class="show">[show]</span>
         <span onclick="hide('list1')" class="hide">[hide]</span>
         <div id="list1">
        
        <?php
          foreach ($old_binaries as $tarball) {
        ?>  
            <p class="software-release">
              <a href="<?php print $tarball[url]; ?>"><?php print $tarball[filename]; ?></a>[<?php print $tarball[release_date]; ?>]
            </p>
            <p class="software-description">
              <?php print $tarball[tarball_desc]; ?>
            </p>        
      <?php 
          };
?>
</DIV>
<?php
        };
      ?>





      <?php
        if ($details[general_source_desc] or (!$sources->EOF)) {
      ?>
        <h3>Source Packages</h3>
        <p>
          <?php print $details[general_source_desc]; ?>
        </p>
        <?php
          foreach ($sources as $tarball) {
            if ($tarball[repo] == 'y') {
        ?>
          <p class="software-release">Current Stable Release</p>
          To obtain the latest stable release of the code, use the following git command:
          <pre class="software-description">git clone --recursive https://github.com/geodynamics/<?php print $package; ?>.git</pre>
        <?php
          } else {
        ?>
          <p class="software-release">
             <a href="<?php print $tarball[url]; ?>"><?php print $tarball[filename]; ?></a>[<?php print $tarball[release_date]; ?>]
          </p>
          <p class="software-description"><?php print $tarball[tarball_desc]; ?></p>
    <?php
          };
        };
      };
    ?>



      <?php
        if (!$old_sources->EOF) { 
      ?>
        <h3>View Prior Source Releases</h3>
         <span onclick="show('list2')" class="show">[show]</span>
         <span onclick="hide('list2')" class="hide">[hide]</span>
         <div id="list2">
        
        <?php
          foreach ($old_sources as $tarball) {
        ?>  
            <p class="software-release">
              <a href="<?php print $tarball[url]; ?>"><?php print $tarball[filename]; ?></a>[<?php print $tarball[release_date]; ?>]
            </p>
            <p class="software-description">
              <?php print $tarball[tarball_desc]; ?>
            </p>        
      <?php 
          };
?>
</DIV>
<?php
        };
      ?>





    <a name="users"><h2>User Resources</h2></a>

    <?php if ($details[has_manual] == 'y' && $details[manual_url] == NULL) { ?>
      <p class="software-release">User Manual</p>
      <p class="software-description">The <?php print $details[package_title]; ?> user manual is <a href="/cig/software/<?php print $details[short_name]; ?>/<?php print $details[short_name]; ?>-manual.pdf">available online</a>.</p>
    <?php } else if ($details[has_manual] == 'y' && $details[manual_url] != NULL) { ?>
      <p class="software-release">User Manual</p>
      <p class="software-description">The <?php print $details[package_title]; ?> user manual is <a href="<?php print $details[manual_url];?>">available online</a>.</p>
    <?php }; ?>
            
      <p class="software-release">Community Wiki</p>
      <p class="software-description">Visit the <a href="//wiki.geodynamics.org/software:<?php print $details[short_name]; ?>:start"><?php print $details[package_title]; ?> Wiki page</a> for additional support with building, using, or modifying <?php print $details[package_title]; ?>.</p>

      <p class="software-release"><?php print $details[package_title]; ?> Publications List</p>
      <p class="software-description"><a href="/cig/news/publications/#<?php print $details[short_name]; ?>">User-submitted research publications</a> using <?php print $details[package_title]; ?>.</p>

      <p class="software-release">Mailing List</p>
      <?php 
        foreach ($mail_list_array as $i => $domain) {
          switch ($domain) {
	    case "cig-short":
              $domain_name = "Short-Term Crustal Dynamics";
              break;
            case "cig-long":
              $domain_name = "Long-Term Tectonics";
              break;
            case "cig-mc":
              $domain_name = "Mantle Convection";
              break;
            case "cig-seismo":
              $domain_name = "Seismology";
              break;
            case "cig-geodyn":
              $domain_name = "Geodynamo";
              break;
            case "cig-cs":
              $domain_name = "Computational Science";
              break;
            case "aspect-devel":
              $domain_name = "Aspect Development";
              break;
          }; ?>
          <p class="software-descriptoin">Browse the CIG <?php print $domain_name; ?> <a href="//www.geodynamics.org/pipermail/<?php print $domain; ?>">Mailing List Archive</a> to find known issues or to troubleshoot common problems, or <a href="mailto:<?php print $domain; ?>@geodynamics.org">E-mail</a> the CIG <?php print $domain_name; ?> Mailing List with details of your problem or suggestion.</p>
        <?php }; ?>

        <a name="developers"><h2>Developer Resources</h2></a>
        <p class="software-release">Development Version</p>
            <p class="software-description">If you are interested in getting the development version of this code from the CIG repository, use the following git command:</p>
            <pre class="software-description">git clone --recursive <?php print ($details[dev_branch] == "" ? "" : "--branch " . $details[dev_branch] . " "); print "https://github.com/geodynamics/" . $details[short_name]; ?>.git</pre>
            <p class="software-description">You can also browse the <a href="<?php print "http://github.com/geodynamics/" . $details[short_name]; ?>">history of modifications</a> in the Git repository.</p>
            <p class="software-release">Issue/Bug Tracker on Github</p>
            <p class="software-description">Browse and/or submit new issues at our <a href="<?php print "http://github.com/geodynamics/" . $details[short_name]; ?>/issues">Github Issues Tracker</a>.</p>
        <?php if ($details[dev_doxygen] == 'y' or $details[release_doxygen] == 'y') { ?>
          <p class="software-release">Doxygen Documentation</p>
          <p class="software-description">Auto-generated Doxygen documentation is available for the <?php if ($details[dev_doxygen] == 'y') {?><a href="//www.geodynamics.org/cig/doxygen/dev/<?php print $details[short_name]; ?>/">Development</a><?php }; if ($details[dev_doxygen] == 'y' and $details[release_doxygen] == 'y') { ?> and <?php }; if ($details[release_doxygen] == 'y') { ?><a href="//www.geodynamics.org/cig/doxygen/release/<?php print $details[short_name]; ?>/">Release</a><?php }; ?> codebase<?php if ($details[dev_doxygen] =='y' and $details[release_doxygen] == 'y') { ?>s<?php }; ?>.</p>
     <?php }; ?> 

     <?php if ($details[jenkins_build] == 'y') { ?>
         <p class="software-release"><a href="http://blofeld.geodynamics.org:8080/job/<?php print $details[short_name] ?>">Jenkins Testing</a></p>
         <table border=3 cellpadding=3>
           <tr>
             <th>Ubuntu14 64 bit</th><th>Ubuntu14 32 bit</th><th>CentOS7 64 bit</th><th>CentOS7 32 bit</th>
           </tr>
           <tr>
             <td>
               <a href="http://blofeld.geodynamics.org:8080/job/<?php print $details[short_name] ?>/label=goldfinger.geodynamics.org">
               <img src="http://blofeld.geodynamics.org:8080/buildStatus/icon?job=<?php print $details[short_name] ?>/label=goldfinger.geodynamics.org">
               </a>
               </td>
             <td>
               <a href="http://blofeld.geodynamics.org:8080/job/<?php print $details[short_name] ?>/label=largo.geodynamics.org">
               <img src="http://blofeld.geodynamics.org:8080/buildStatus/icon?job=<?php print $details[short_name] ?>/label=largo.geodynamics.org">
               </a>
               </td>
             <td>
               <a href="http://blofeld.geodynamics.org:8080/job/<?php print $details[short_name] ?>/label=kananga.geodynamics.org">
               <img src="http://blofeld.geodynamics.org:8080/buildStatus/icon?job=<?php print $details[short_name] ?>/label=kananga.geodynamics.org">
               </a>
               </td>
             <td>
               <a href="http://blofeld.geodynamics.org:8080/job/<?php print $details[short_name] ?>/label=big.geodynamics.org">
               <img src="http://blofeld.geodynamics.org:8080/buildStatus/icon?job=<?php print $details[short_name] ?>/label=big.geodynamics.org">
               </a>
               </td>
           </tr>
         </table>
     <?php } ?>

       <?php if ($details[user_map] == 'y') { ?>
         <a name="usermap"><h2><?php print $details[package_title]; ?> Users Map</h2></a>
         <p>Shows location of all users who downloaded <?php print $details[package_title]; ?> in the past year (image updated daily.)</p>
         <img src="//geodynamics.org/cig/maps/<?php print $details[short_name]; ?>.gif" alt="map showing location of all users who downloaded <?php print $details[package_title]; ?> in the last year (image updated daily)"></p>
       <?php }; ?>
     </div>
<?php
  } else {
    die ("No such package!");
  }

?>
