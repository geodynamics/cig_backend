<html>
  <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
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

      if(!($stmt = $mysqli->prepare("SELECT software.short_name, software.package_title, software.license, software.mail_lists, software.repo_type, CONCAT('http://github.com/geodynamics/', software.short_name) FROM software WHERE software.short_name = ? LIMIT 1;"))) {
        die("Error in prepare(): " . $mysqli->error);
      }
      if(!$stmt->bind_param('s',$package)) {
        die("Error in bind_param(): " . $mysqli->error);
      }
      if(!$stmt->execute()) {
        die("Error in execute(): " . $mysqli->error);
      }
      if(!$stmt->bind_result($short_name, $package_title, $license, $mail_list, $repo_type, $repo_url)) {
        die("Error in bind_result(): " . $mysqli->error);
      }

      $stmt->fetch();
      $rows = array("short_name" => $short_name,
                    "package_title" => $package_title,
                    "license" => $license,
                    "mail_list" => $mail_list,
                    "repo_type" => $repo_type,
                    "repo_url" => $repo_url);
      $stmt->close();

      if(!($stmt = $mysqli->prepare("SELECT software_desc.desc_revision_num, software_desc.revision_num, software_desc.desc_short, software_desc.desc_long, software_desc.status, software_desc.cig_supported, software_desc.general_binary_desc, software_desc.general_source_desc FROM software, software_desc WHERE software_desc.software_id = software.id AND software.short_name = ?;"))) {
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

      if(!($stmt = $mysqli->prepare("SELECT CONCAT('http://geodynamics.org/cig/software/', software.short_name, '/', software_tarball.filename), software_tarball.revision_num FROM software, software_tarball WHERE software_tarball.software_id = software.id AND software_tarball.active = 'y' AND software_tarball.source = 'y' AND software.short_name = ? ORDER BY software_tarball.release_date DESC LIMIT 1;"))) {
        die("Error in prepare(): " . $mysqli->error);
      }
      if(!$stmt->bind_param('s',$package)) {
        die("Error in bind_param(): " . $mysqli->error);
      }
      if(!$stmt->execute()) {
        die("Error in execute(): " . $mysqli->error);
      }
      if (!$stmt->bind_result($release_src_url, $release_src_version)) {
        die("Error in bind_result(): " . $mysqli->error);
      }

      $stmt->fetch();
      $rows = array_merge($rows,
                          array("release_src_url" => $release_src_url,
                                "release_src_version" => $release_src_version));
      $stmt->close();

      if(!($stmt = $mysqli->prepare("SELECT CONCAT('http://geodynamics.org/cig/software/', software.short_name, '/', software_tarball.filename), software_tarball.filename, software_tarball.release_date, software_tarball.revision_num, software_tarball.tarball_desc FROM software, software_tarball WHERE software_tarball.software_id = software.id AND software_tarball.active = 'y' AND software_tarball.source = 'n' AND software.short_name = ? ORDER BY software_tarball.revision_num DESC;"))) {
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
      
      $tarballs = array();
      while($stmt->fetch()) $tarballs[] = array("release_bin_url" => $release_bin_url,
                                                "release_bin_filename" => $release_bin_filename,
                                                "release_bin_date" => $release_bin_date,
                                                "release_bin_version" => $release_bin_version,
                                                "release_bin_desc" => $release_bin_desc);
                                      
      $rows = array_merge($rows, array("tarballs" => $tarballs));
      $stmt->close();
      
      if(!($stmt = $mysqli->prepare("SELECT software_doxygen.release_doxygen, software_doxygen.dev_doxygen FROM software, software_doxygen WHERE software_doxygen.software_id = software.id AND software.short_name = ? LIMIT 1;"))) {
        die("Error in prepare(): " . $mysqli->error);
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
    <div class="sidebar_left software_sidebar_left">
      <div class="generic_sidebar_padding">
        <p><a href="/cig/software_list">Return to Software Page</a></p>
        <h3><?php print $package_title;?></h3>
        <p><a href="#download">Download</a></p>
        <p><a href="#support">Docs and Support</a></p>
        <p><a href="#developers">For Developers</a></p>
        <p><a href="#publications">Publications</a></p>
        <div id ="blockStyle189Main7" class="software_add_on ccm-block-styles">
          <ul class="nav"></ul>
        </div>
      </div>
    </div>
    <div class="content content_left">
      <div class="software_status">
        <img border="0" class="ccm-image-block" alt src="/cig/files/cache/41876576ec3b37b07bdeeddefc692d75_f14.jpg" width="250" height="187">
        <p><strong>Status:</strong>  <?php print $status;?></p>
        <p><strong>Contact:</strong> <a href = "//www.geodynamics.org/cig/community/lists/cig-<?php print $mail_list;?>">cig-<?php print $mail_list;?>@geodynamics.org</a></p>
        <p><strong>Bug reports:</strong> <a href = "//www.github.com/geodynamics/<?php print $short_name;?>/issues">Github Issue Tracker</a></p>
        <p><strong>License:</strong>
          <a href = "LICENSE">
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
      <h2><a name="download">Current Release</a></h2>
      <h3>Binaries</h3>
      <p><?php print $general_binary_desc;?></p>
      <?php
        foreach ($tarballs as $tarball) {
          print '<p class =- "software-release">';
          print '<a href="' . $tarball["release_bin_url"] . '">' . $tarball["release_bin_filename"] . '</a>';
          print '[' . $tarball["release_bin_date"] . ']';
          print '</p>';
          print '<p class="software-description">' . $tarball["release_bin_desc"] . '</p>';
        };
      ?>
      <h3>Source Packages</h3>
      <p><?php print $general_source_desc;?></p>
      <h2><?php print $package_title;?> Users Map</h2>
      <p>Shows location of all users who downloaded <?php print $package_title;?> in the past year (image updated daily).</p>
      <p><img src="//geodynamics.org/cig/maps/<?php print $short_name;?>.gif" alt="map showing location of all users who downloaded <?php print $package_title;?> in the last year (image updated daily)" title="Location of all users who downloaded <?php print $package_title;?> in the last year (image updated daily)"></p>
      <h2>Development Version</h2>
      <p class="software-description">If you are interested in checking out the software from the CIG repository, use the following git command:</p>
      <pre class="software-description"> git clone <?php print $repo_url;?>.git</pre>
      <p class="software-description">If git refuses to abide, claiming an SSL error, try</p>
      <pre class="software-description"> GIT_SSL_NO_VERIFY=true git clone <?php print $repo_url;?>.git</pre>
      <p class="software-description">You can also browse the <a href="<?php print $repo_url;?>">history of modifications in the Git repository.</a></p>
      <h2>Documentation</h2>
      Stuff goes here
      <h2>Help</h2>
      <p>To identify known issues, report bugs, request help, or provide feedback or suggestions:</p>
      <ul>
        <li>Browse and/or submit new issues at our <a href="<?php print $repo_url;?>/issues">Github Issues Tracker</a>.</li>
        <?php
          switch ($mail_list) {
            case "short":
              $domain_name = "Short-Term Crustal Dynamics";
              break;
            case "long":
              $domain_name = "Long-Term Tectonics";
              break;
            case "mc";
              $domain_name = "Mantle Convection";
              break;
            case "seismo":
              $domain_name = "Seismology";
              break;
            case "geodyn":
              $domain_name = "Geodynamo";
              break;
            case "cs":
              $domain_name = "Computational Science";
              break;
          }
        ?>
        <li>Browse the <a href="www.geodynamics.org/pipermail/cig-<?php print $mail_list;?>">CIG <?php print $domain_name;?> Mailing List Archive.</a>.</li>
        <li>E-mail the <a href="mailto:cig-<?php print $mail_list;?>@geodynamics.org">CIG <?php print $domain_name;?> Mailing List</a> with details of your problem or suggestion</li>
      </ul>
    </div>
  </body>
</html>
