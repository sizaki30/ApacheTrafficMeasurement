<?php
/**
 * This PHP script, from the Apache access log, you can aggregate the network transfer amount for each file type.
 *
 * CAUTION Format of the access log must be combinedio. 
 *
 * @author sizaki30
 * @license MIT
 */
$access_log_path = $argv[1];

@$access_log_data = file_get_contents($access_log_path);

$access_log = explode("\n", $access_log_data);

$traffics = array();
for ($i = 0; $i < count($access_log); $i++) {

    $row        = explode(' ', $access_log[$i]);
    $path       = parse_url($row[6], PHP_URL_PATH);
    $path_parts = pathinfo($path);
    $extension  = $path_parts['extension'];

    if (!$extension) {
        $extension = 'other';
    }

    $row_traffic = end($row);

    if (ctype_digit($row_traffic)) {
        $traffics[$extension] += $row_traffic;
        $traffics['total']    += $row_traffic;
    }
}

if (!$traffics) {
    exit;
}

arsort($traffics);
foreach ($traffics as $extension => $traffic_byte) {
    echo $extension . "\t" . $traffic_byte . " byte\n";
}