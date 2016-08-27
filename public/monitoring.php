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

$sql = "SELECT * FROM settings";
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


$list = "
Starting Nmap 7.01 ( https://nmap.org ) at 2016-08-10 15:26 EDT
Nmap scan report for 192.168.43.1
Host is up (0.024s latency).
MAC Address: 44:74:6C:B3:DE:C4 (Sony Mobile Communications AB)
Nmap scan report for EyvindTC (192.168.43.85)
Host is up (0.00012s latency).
MAC Address: 7C:DD:90:69:02:6F (Shenzhen - Ogemray Technology)
Nmap scan report for 192.168.43.102
Host is up (0.12s latency).
MAC Address: 68:5D:43:D9:27:77 (Intel Corporate)
Nmap scan report for Adriana (192.168.43.213)
Host is up (0.071s latency).
MAC Address: 68:5D:43:D9:27:77 (Intel Corporate)
Nmap scan report for kali (192.168.43.131)
Host is up.
Nmap done: 256 IP addresses (5 hosts up) scanned in 16.08 seconds
";
$list = shell_exec('nmap -sP ' . $ip_scan);
$list = explode(PHP_EOL, $list);

mysqli_query($CONN, 'DELETE FROM nmap_all_scan');
mysqli_query($CONN, 'TRUNCATE nmap_all_scan');

$pc = array();

for ($index = 2; $index < sizeof($list) - 4; $index += 3) {
    preg_match_all('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $list[$index], $ip_matches);
    preg_match_all('/\d{1,7}\.\d{1,7}/', $list[$index + 1], $latency_matches);
    preg_match_all('/\([0-9a-zA-Z -]{1,100}\)/', $list[$index + 2], $manufacturer_matches);
    preg_match_all('/[0-9a-zA-Z]{1,2}\:[0-9a-zA-Z]{1,2}\:[0-9a-zA-Z]{1,2}\:[0-9a-zA-Z]{1,2}\:[0-9a-zA-Z]{1,2}\:[0-9a-zA-Z]{1,2}/', $list[$index + 2], $mac_matches);

    $mac = $mac_matches[0][0];
    $manufacturer = $manufacturer_matches[0][0];
    $latency = $latency_matches[0][0];
    $ip = $ip_matches[0][0];

    array_push($pc, ['mac' => $mac, 'ip' => $ip, 'manufacturer' => $manufacturer, 'latency' => $latency]);
    $sql = 'INSERT INTO nmap_all_scan (ip, mac, latency, manufacturer) VALUES ("' . $ip . '","' . $mac . '","' . $latency . '","' . $manufacturer . '");';
    mysqli_query($CONN, $sql);
}

echo "<h5>SERVICIO EJECUTADO</h5>";
mysqli_close($CONN);