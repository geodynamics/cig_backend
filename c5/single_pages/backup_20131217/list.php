<html>
  <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
  <body>
    <div class="sidebar_left">
      <ul class="nav" id="domain-nav">
      </ul>
    </div>
    <div class="content content_left"> 
      <h2>List of Software</h2>
      <table style="width: 750px;" border="0">
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
        <tbody id="list-box">
        </tbody>
      </table>
      <address>&nbsp;</address>
      <address>Status: DC = Developed CIG, D = Developed, SC = Supported CIG, S = Supported, T = Tracked, and A = Archived</address>
      <address>For descriptions of software support status levels see Software Support Policies.</address>
      <h2>&nbsp</h2>
    </div>
    <script>
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

      $mysqli = open_db("software_cig");

      if (!($stmt = $mysqli->prepare("SELECT software_domain.domain_abbr, software_domain.domain_name FROM software_domain;"))) {
        die("Error in prepare(): " . $mysqli->error);
      }
      if (!$stmt->execute()) {
        die("Error in execute(): " . $mysqli->error);
      }
      if (!$stmt->bind_result($domain_abbr,
                              $domain_name)) {
        die("Error in bind_result(): " . $mysqli->error);
      }

      $rows = array();
      while($stmt->fetch()) $rows[] = array("abbr" => $domain_abbr, "name" => $domain_name);
      $stmt->close();

      print "var domains = " . json_encode($rows) . ";";

      if (!($stmt = $mysqli->prepare("SELECT DISTINCT software.package_title, software_domain.domain_abbr, software_desc.desc_short, software_status.status_short FROM software, software_domain, software_desc, software_status WHERE software.id = software_desc.software_id AND software_desc.status = software_status.id AND software_domain.id = software.domain_id AND software.hidden = 'n' ORDER BY software_desc.status, software.updated;"))) {
        die("Error in prepare(): " . $mysqli->error);
      }
      if (!$stmt->execute()) {
        die("Error in execute(): " . $mysqli->error);
      }
      if (!$stmt->bind_result($software_name,
   	                      $software_domain,
 			      $software_desc,
			      $software_status)) {
        die("Error in bind_result(): " . $mysqli->error);
      }

      $rows = array();
      while($stmt->fetch()) $rows[] = array("name" => $software_name,
  					    "domain" => $software_domain,
					    "desc" => $software_desc,
					    "stat" => $software_status);
      $stmt->close();
 
      close_db($mysqli);

     print 'var code_details = ' . json_encode($rows) . ';';
   ?>            
    $(document).ready(function () {
      for (domain in domains) {
        var domainRow = $('<tr></tr>');
          domainRow.append('<td colspan="5" align="left" valign="top"><a name="' + domains[domain].abbr + '"><h3 style="color: #DAA520;"><strong>' + domains[domain].name + '</strong></h3></a></td>');
          domainRow.append('<td>&nbsp;</td><td>&nbsp;</td>');
	  $('#domain-nav').append('<li><a href="#' + domains[domain].abbr + '" target="_self">' + domains[domain].name + '</a></li>');
          $('#list-box').append(domainRow);
          for (code in code_details) {
            if (code_details[code].domain == domains[domain].abbr) {
	      var softRow = $('<tr></tr>');
	      softRow.append('<td align="left" valign="top">&nbsp;</td><td>&nbsp;</td>');
   	      softRow.append('<td align="left" valign="top">&nbsp;' + code_details[code].name + '</td><td>&nbsp;</td>');
              softRow.append('<td><span>' + code_details[code].desc + '</span>&nbsp;</td><td>&nbsp;</td>');
              softRow.append('<td style="text-align: center;">' + code_details[code].stat + '&nbsp;</td>'); 
	      $("#list-box").append(softRow);
            }
          }
        }
      });
    </script>
  </body>
</html>
