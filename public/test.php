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