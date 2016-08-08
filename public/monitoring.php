<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/8/16
 * Time: 12:36 AM
 */

$DB_HOST = "127.0.0.1";
$DB_DATABASE = "monitor_red";
$DB_USERNAME = "root";
$DB_PASSWORD = "emilio";

$CONN = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

if (!$CONN) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT network_address, gateway, mask, time_check_network, active_system FROM settings";
$result = mysqli_query($CONN, $sql);

$network_address = "192.168.1.1";
$mask = 24;

while ($row = mysqli_fetch_assoc($result)) { // priority must be at least 1 record
    $network_address = $row["network_address"];
    $mask = (int)$row["mask"];
}

$OCT = explode('.', $network_address);
$ip_scan = '';
if ($mask == 8) {
    $ip_scan = $OCT[0] . '.*.*.*';
} elseif ($mask == 16) {
    $ip_scan = $OCT[0] . '.' . $OCT[1] . '.*.*';
} elseif ($mask == 24) {
    $ip_scan = $OCT[0] . '.' . $OCT[1] . '.' . $OCT[2] . '.*';
} else {
    $ip_scan = $network_address;
}

$list = shell_exec('nmap -sP ' . $ip_scan);
$list = explode(PHP_EOL, $list);

mysqli_query($CONN, 'DELETE FROM nmap_all_scan');
mysqli_query($CONN, 'TRUNCATE nmap_all_scan');

$pc = array();

for ($index = 2; $index < sizeof($list) - 4; $index += 3) {
    $first_line = explode(' ', $list[$index + 2], 4);
    $mac = $first_line[2];
    $manufacturer = $first_line[3];

    $latency_line = explode(' ', $list[$index + 1]);
    $latency = $latency_line[3];
    $latency = str_replace("(", "", $latency);
    $latency = str_replace("s", "", $latency);

    $second_line = explode(' ', $list[$index]);
    $ip = $second_line[4];

    array_push($pc, ['mac' => $mac, 'ip' => $ip, 'manufacturer' => $manufacturer, 'latency' => $latency]);

    $sql = 'INSERT INTO nmap_all_scan (ip, mac, latency, manufacturer) VALUES ("' . $ip . '","' . $mac . '","' . $latency . '","' . $manufacturer . '");';
    mysqli_query($CONN, $sql);
}

print_r($pc);
mysqli_close($CONN);