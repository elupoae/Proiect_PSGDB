<?php

// Create connection to Oracle
$conn = oci_connect("EDUARD", "EDUARD", "localhost/orcl12");

$query = 'select * from station';
$stid = oci_parse($conn, $query);
$r = oci_execute($stid);

// Fetch each row in an associative array
print '<table border="1">';
while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
    print '<tr>';
    foreach ($row as $item) {
        print '<td>'.($item !== null ? htmlentities($item, ENT_QUOTES) : '&nbsp').'</td>';
    }
    print '<td class="column-delete">DELETE</td>';
    print '</tr>';
}
print '</table>';

