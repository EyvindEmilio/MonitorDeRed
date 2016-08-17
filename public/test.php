<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/10/16
 * Time: 3:44 PM
 */

$second_line = "Nmap scan report for Adriana (192.168.43.213)";
$second_line = "Nmap scan report for Adriana 192.168.43.213";
$second_line = "MAC Address: 68:5D:43:D9:27:77 (Intel Corporate)";
preg_match_all('/[0-9a-zA-Z]{1,2}\:[0-9a-zA-Z]{1,2}\:[0-9a-zA-Z]{1,2}\:[0-9a-zA-Z]{1,2}\:[0-9a-zA-Z]{1,2}\:[0-9a-zA-Z]{1,2}/', $second_line, $ip_matches);
//preg_match_all('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $second_line, $ip_matches);
print_r($ip_matches);
$cmd = "tcpdump -i eth0 src 192.168.1.18 and dst 192.168.1.7 > informe.txt";
//$cmd = "ifconfig";
exec($cmd, $thisq);
//exec();
echo "<pre>";
print_r($thisq);
echo "</pre>";
echo "<pre>$thisq</pre>";