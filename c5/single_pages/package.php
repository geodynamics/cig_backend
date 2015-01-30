<html>
  <body>
    <?php
      function open_db($db_name) {
        $mysqli = new mysqli("localhost", "readonly", "", $db_name);
        if ($mysqli->connect_errno) {
          $fail_str = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
          die($fail_str);
        }
        return $mysqli;
      }
      
      function close_db($mysqli) {
        $mysqli->close();
      }

      $package = $_GET["pkg"];

      $mysqli = open_db("software_cig");

      if(!($stmt = $mysqli->prepare("SELECT software.short_name, software.package_title, software.license, software.mail_lists, software.repo_type, software.has_manual, software.user_map, CONCAT('http://github.com/geodynamics/', software.short_name), software.dev_branch FROM software WHERE software.short_name = ? LIMIT 1;"))) {
        die("Error in prepare(): " . $mysqli->error);
      }
      if(!$stmt->bind_param('s',$package)) {
        die("Error in bind_param(): " . $mysqli->error);
      }
      if(!$stmt->execute()) {
        die("Error in execute(): " . $mysqli->error);
      }
      if(!$stmt->bind_result($short_name, $package_title, $license, $mail_list, $repo_type, $has_manual, $user_map, $repo_url, $dev_branch)) {
        die("Error in bind_result(): " . $mysqli->error);
      }

      $stmt->fetch();

      if ($short_name) {

      $rows = array("short_name" => $short_name,
                    "package_title" => $package_title,
                    "license" => $license,
                    "mail_list" => $mail_list,
                    "repo_type" => $repo_type,
                    "has_manual" => $has_manual,
		    "user_map" => $user_map,
                    "repo_url" => $repo_url,
                    "dev_branch" => $dev_branch,
                    );
      $stmt->close();
      
      $tok = strtok($mail_list, ',');
      $mail_list_array = array();
      while ($tok !== false) {
        $mail_list_array[] = $tok;
        $tok = strtok(',');
      };

      if(!($stmt = $mysqli->prepare("SELECT software_desc.desc_revision_num, software_desc.revision_num, software_desc.desc_short, software_desc.desc_long, software_status.status_long as status, software_desc.cig_supported, software_desc.general_binary_desc, software_desc.general_source_desc FROM software, software_desc, software_status WHERE software_desc.software_id = software.id AND software_status.id = software_desc.status AND software.short_name = ?;"))) {
        die("Error in prepare(): " . $mysqli->error);
      }
      if(!$stmt->bind_param('s', $package)) {
        die("Error in bind_param(): " . $mysqli->error);
      }
      if(!$stmt->execute()) {
        die("Error in execute(): " . $mysqli->error);
      }
      if(!$stmt->bind_result($desc_revision_num, $revision_num, $desc_short, $desc_long, $status, $cig_supported, $general_binary_desc, $general_source_desc)) {
        die("Error in bind_result(): " . $mysqli->error);
      }

      $stmt->fetch();
      $rows = array_merge($rows,
                          array("desc_revision_num" => $desc_revision_num,
                                "revision_num" => $revision_num,
				"desc_short" => $desc_short,
                                "desc_long" => $desc_long,
                                "status" => $status,
                                "cig_supported" => $cig_supported,
                                "general_binary_desc" => $general_binary_desc,
                                "general_source_desc" => $general_source_desc));

      $stmt->close();

      # DEBUG: Shouldn't limit 1 for software binaries, should probably show all active=y instead, even for source
	# if(!($stmt = $mysqli->prepare("SELECT CONCAT('http://geodynamics.org/cig/software/', software.short_name, '/', software_tarball.filename), software_tarball.revision_num FROM software, software_tarball WHERE software_tarball.software_id = software.id AND software_tarball.active = 'y' AND software_tarball.source = 'y' AND software.short_name = ? ORDER BY software_tarball.release_date DESC LIMIT 1;"))) {
	if(!($stmt = $mysqli->prepare("SELECT CONCAT('/cig/software/', software.short_name, '/', software_tarball.filename), software_tarball.filename, software_tarball.release_date, software_tarball.revision_num, software_tarball.tarball_desc, software_tarball.repo FROM software, software_tarball WHERE software_tarball.software_id = software.id AND software_tarball.active = 'y' AND software_tarball.source = 'y' AND software.short_name = ? ORDER BY software_tarball.release_date DESC;"))) {
	  die("Error in prepare(): " . $mysqli->error);
	}
	if(!$stmt->bind_param('s',$package)) {
	  die("Error in bind_param(): " . $mysqli->error);
	}
	if(!$stmt->execute()) {
	  die("Error in execute(): " . $mysqli->error);
	}
	if (!$stmt->bind_result($release_src_url, $release_src_filename, $release_src_date, $release_src_version, $release_src_desc, $release_src_repo)) {
	  die("Error in bind_result(): " . $mysqli->error);
	}

	$sources = array();
	while($stmt->fetch()) $sources[] = array("release_src_url" => $release_src_url,
						 "release_src_filename" => $release_src_filename,
						 "release_src_date" => $release_src_date,
						 "release_src_version" => $release_src_version,
						 "release_src_desc" => $release_src_desc,
						 "release_src_repo" => $release_src_repo,
                                                 );
	$rows = array_merge($rows, array("sources" => $sources));
	$stmt->close();

	if(!($stmt = $mysqli->prepare("SELECT CONCAT('/cig/software/', software.short_name, '/', software_tarball.filename), software_tarball.filename, software_tarball.release_date, software_tarball.revision_num, software_tarball.tarball_desc FROM software, software_tarball WHERE software_tarball.software_id = software.id AND software_tarball.active = 'y' AND software_tarball.source = 'n' AND software.short_name = ? ORDER BY software_tarball.revision_num DESC;"))) {
	  die("Error in prepare(): " . $mysqli->error);
	}
	if(!$stmt->bind_param('s',$package)) {
	  die("Error in bind_param(): " . $mysqli->error);
	}
	if(!$stmt->execute()) {
	  die("Error in execute(): " . $mysqli->error);
	}
	if (!$stmt->bind_result($release_bin_url, $release_bin_filename, $release_bin_date, $release_bin_version, $release_bin_desc)) {
	  die("Error in bind_result(): " . $mysqli->error);
	}
	
	$binaries = array();
	while($stmt->fetch()) $binaries[] = array("release_bin_url" => $release_bin_url,
						  "release_bin_filename" => $release_bin_filename,
						  "release_bin_date" => $release_bin_date,
						  "release_bin_version" => $release_bin_version,
						  "release_bin_desc" => $release_bin_desc);
					
	$rows = array_merge($rows, array("binaries" => $binaries));
	$stmt->close();
	
	if(!($stmt = $mysqli->prepare("SELECT software.release_doxygen, software.dev_doxygen FROM software WHERE software.short_name = ? LIMIT 1;"))) {
	  die("Error in prepare(): " . $mysqli->error);
	$rows = array_merge($rows, array("sources" => $sources));;
	}
	if(!$stmt->bind_param('s', $package)) {
	  die("Error in bind_param(): " . $mysqli->error);
	}
	if(!$stmt->execute()) {
	  die("Error in execute(): " . $mysqli->error);
	}
	if(!$stmt->bind_result($release_dox, $dev_dox)) {
	  die("Error in bind_result(): " . $mysqli->error);
	}
	$stmt->fetch();
	$rows = array_merge($rows,
			    array("release_dox" => $release_dox,
				  "dev_dox" => $dev_dox));
	$stmt->close();
	close_db($mysqli);
      ?>
      <div class="sidebar_left">
	<ul class="nav">
	  <li><a href="/cig/software/software_list">Return to Software Page</a></li>
	  <li><h3><?php print $package_title;?></h3></li>
	  <li><a href="#release">Current Release</a></li>
	  <li><a href="#users">User Resources</a></li>
	  <li><a href="#developers">Developer Resources</a></li>
	  <?php if($user_map=="y") { ?>
          <li><a href="#map">User Map</a></li>
          <?php } ?>
	</ul>
      </div>
      <div class="content content_left">
	<div class="software_status">
	  <?php if($has_manual=='y'){ ?>
	    <a href="/cig/software/<?php print $short_name; ?>/<?php print $short_name; ?>-manual.pdf">
	      <img border="0" class="ccm-image-block" alt src="/cig/software/<?php print $short_name; ?>/cover-small" width="250px">
            </a>
          <?php } ?>
	  <p><strong>Status:</strong><br><?php print $status;?>
            <?php 
              $request = new HttpRequest ();
              
              $request->setUrl ("https://api.github.com/repos/geodynamics/".$short_name."/stats/commit_activity");
              $request->send ();

              if($request->getResponseCode () != 200) {
                if($request->getResponseCode () == 403) {
                  echo "<br><a href=\"https://github.com/geodynamics/".$short_name."/graphs/commit-activity\">Commit statistics available on GitHub</a>";
                }
              }else{
                $raw_stats = $request->getResponseBody ();
                $stats = json_decode ($raw_stats);

                $month_commits = 0;
                $year_commits = 0;

                foreach ($stats as $index => $week) {
                  if ($index < 4) {
                    $month_commits += $week->total;
                  }
                  $year_commits += $week->total;
                }
                echo "<br><a href=\"https://github.com/geodynamics/".$short_name."/graphs/commit-activity\">".$month_commits." commits this past month, ".$year_commits." commits this past year.</a>";
              }
            ?>
          </p>
	  <p><strong>Contact:</strong>
	     <?php foreach ($mail_list_array as $i => $value) { ?>
	       <?php if ($mail_list_array[$i] == "aspect") { $mail_list_array[$i] = "aspect-devel"; } else { $mail_list_array[$i] = "cig-".$mail_list_array[$i]; }; ?>
	       <br><a href = "//www.geodynamics.org/cgi-bin/mailman/listinfo/<?php print $mail_list_array[$i]; ?>"><?php print $mail_list_array[$i];?>@geodynamics.org</a><?php }; ?>
          </p>
	  <p><strong>Bug reports:</strong><br><a href = "//www.github.com/geodynamics/<?php print $short_name;?>/issues">Github Issue Tracker</a></p>
	  <p><strong>License:</strong><br>
	    <a href = "//raw.github.com/geodynamics/<?php print $short_name; ?>/master/COPYING">
	      <?php 
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
		    echo "Unlicensed";
		    break;
		}
	      ?>
	    </a>
	  </p>
	</div>
	<h1><?php print $package_title;?></h1>
	<p><strong id="parent-fieldname-description"><?php print $desc_short?></strong></p>
	<p><?php print $desc_long?></p>
	<a name="release"><h2>Current Release</h2></a>

	<?php if ( $general_binary_desc or $binaries ) { ?>
	<h3>Binaries</h3>
	<p><?php print $general_binary_desc;?></p>

	<?php
	  foreach ($binaries as $tarball) {
	    print '<p class="software-release">';
	    print '<a href="' . $tarball["release_bin_url"] . '">' . $tarball["release_bin_filename"] . '</a>';
	    print ' [' . $tarball["release_bin_date"] . ']';
	    print '</p>';
	    print '<p class="software-description">' . $tarball["release_bin_desc"] . '</p>';
	  };
	};?>

	<?php if ( $general_source_desc or $sources ) { ?>
	<h3>Source Packages</h3>
	<p><?php print $general_source_desc;?></p>
	<?php
	  foreach ($sources as $tarball) {
            if ($tarball["release_src_repo"] == 'y') {
	      print '<p class="software-release">';
              print 'Current Stable Release';
	      print '</p>';
              print 'To obtain the latest stable release of the code, use the following git command:<p>';
              print '<pre class="software-description">git clone --recursive https://github.com/geodynamics/'.$short_name.'.git</pre>';
            } else {
	      print '<p class="software-release">';
	      print '<a href="' . $tarball["release_src_url"] . '">' . $tarball["release_src_filename"] . '</a>';
	      print ' [' . $tarball["release_src_date"] . ']';
	      print '</p>';
	      print '<p class="software-description">' . $tarball["release_src_desc"] . '</p>';
            };
	  };
	}; ?>



	<a name="users"><h2>User Resources</h2></a>

        <?php if($has_manual=="y"){ ?>
	  <p class="software-release">User Manual</p>
	  <p class="software-description">The <?php print $package_title; ?> user manual is <a href="/cig/software/<?php print $short_name; ?>/<?php print $short_name; ?>-manual.pdf">available online</a>.</p>
        <?php } ?>
	<p class="software-release">Community Wiki</p>
	<p class="software-description">Visit the <a href="//wiki.geodynamics.org/software:<?php print $short_name; ?>:start"><?php print $package_title ?> Wiki page</a> for additional support with building, using, or modifying <?php print $package_title ?>.</p> 
	<p class="software-release"><?php print $package_title ?> Publications List</p>
	<p class="software-description"><a href="/cig/news/publications/#<?php print $short_name ?>">User submitted research publications</a> using <?php print $package_title ?>.</p>
	<p class="software-release">Mailing List</p>
	<?php foreach ($mail_list_array as $i => $list) { ?>
	  <?php switch ($mail_list_array[$i]) {
            case "cig-short":
	      $domain_name = "Short-Term Crustal Dynamics";
	      break;
	    case "cig-long":
	      $domain_name = "Long-Term Tectonics";
	      break;
	    case "cig-mc";
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
	  <p class="software-description">Browse the CIG <?php print $domain_name; ?> <a href="http://www.geodynamics.org/pipermail/<?php print $mail_list_array[$i];?>">Mailing List Archive</a> to find known issues or to troubleshoot common problems, or <a href="mailto:<?php print $mail_list_array[$i];?>@geodynamics.org">E-mail</a> the CIG <?php print $domain_name;?> Mailing List with details of your problem or suggestion.</p>
	</ul>
      <? }; ?>
      <a name="developers"><h2>Developer Resources</h2></a>
      <p class="software-release">Development Version</p>
      <p class="software-description">If you are interested in getting the development version of the code from the CIG repository, use the following git command:</p>
      <pre class="software-description">git clone --recursive <?php print ($dev_branch == "" ? "" : "--branch ". $dev_branch . " "); print $repo_url;?>.git</pre>
      <p class="software-description">You can also browse the <a href="<?php print $repo_url;?>">history of modifications</a> in the Git repository.</p>
      <p class="software-release">Issue/Bug Tracker in Github</p>
      <p class="software-description"> Browse and/or submit new issues at our <a href="<?php print $repo_url;?>/issues">Github Issues Tracker</a>.</p>
      <?php if ($dev_dox == 'y' or $release_dox == 'y') { ?>
	<p class="software-release">Doxygen Documentation</p>
	<p class="software-description">Auto-generated Doxygen documentation is available for the <?php if ($dev_dox == 'y') { ?><a href="//www.geodynamics.org/cig/doxygen/dev/<?php print $short_name; ?>/">Development</a><?php }; ?><?php if ($dev_dox == 'y' and $release_dox == 'y') { ?> and <?php }; ?><?php if ($release_dox == 'y') { ?><a href="//www.geodynamics.org/cig/doxygen/release/<?php print $short_name; ?>/">Release</a><?php }; ?> codebase<?php if ($dev_dox == 'y' and $release_dox == 'y') { ?>s<?php }; ?>.</p>
      <?php }; ?>
      <?php if($user_map=="y"){ ?>
      <a name="map"><h2><?php print $package_title; ?> Users Map</h2></a>
      <p>Shows location of all users who downloaded <?php print $package_title; ?> in the past year (image updated daily).</p>
      <p><img src="//geodynamics.org/cig/maps/<?php print $short_name; ?>.gif" alt="map showing location of all users who downloaded <?php print $package_title; ?> in the last year (image updated daily)" title="Location of all users who downloaded <?php print $package_title; ?> in the last year (image updated daily)"></p>
      <? } ?>
    </div>
  </div>
  <?php } else { ?>
    No such package!
  <?php }; ?>
  </body>
</html>

