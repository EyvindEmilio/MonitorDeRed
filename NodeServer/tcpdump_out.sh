#!/usr/bin/env bash
echo "start"
tcpdump -i eth0 -nnq -t icmp -l| tee -a out_icmp.cap