<?php

$cmd = filter_input(INPUT_GET, 'cmd', FILTER_SANITIZE_SPECIAL_CHARS);
if ($cmd == "list" || $cmd == "detail") {
  # Establish DB connection
  $db = new mysqli('localhost', 'readonly', '', 'software_cig');
  if($db->connect_errno > 0) {
    die('Unable to connect to database [' . $db->connect_error . ']');
  }

  # Create the query
  if ($cmd == "list") {
    $statement = $db->prepare("SELECT short_name FROM software ORDER BY short_name");
  } else {
    $short_name = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_SPECIAL_CHARS);
    $statement = $db->prepare("SELECT software.short_name, software.package_title, software.repo_type, ".
                              "CONCAT('https://github.com/geodynamics/', software.short_name, '.git'), ".
                              "software.dev_doxygen, software.release_doxygen, ".
                              "CONCAT('http://geodynamics.org/cig/software/', software.short_name, '/', software_tarball.filename), ".
                              "software_tarball.revision_num FROM software, software_tarball ".
                              "WHERE software.short_name = ? AND software_tarball.software_id = software.id ".
                              "AND software_tarball.source = 'y' ORDER BY release_date DESC LIMIT 1");
    $statement->bind_param('s', $short_name);
  }

  # Execute the query
  $statement->execute();

  # Get the results, organize and print as JSON
  if ($cmd == "list") {
    $statement->bind_result($code_name);
    $rows = array();
    while($statement->fetch()) {
      $rows[] = $code_name;
    }
    print json_encode($rows);
  } else if ($cmd == "detail") {
    $statement->bind_result($short_name, $package_title, $repo_type, $repo_url, $dev_doxygen, $release_doxygen, $src_url, $revision_num);
    $statement->fetch();
    $rows = array("short_name" => $short_name, "package_title" => $package_title,
                  "repo_type" => $repo_type, "repo_url" => $repo_url,
                  "dev_doxygen" => $dev_doxygen, "release_doxygen" => $release_doxygen,
                  "release_src_url" => $src_url, "release_src_version" => $revision_num);
    print json_encode($rows);
  }

  # Close the statement
  $statement->free_result();

  # Close the DB connection
  $db->close();
} else {
  //http_response_code(400);
}

?>

