#!/usr/bin/env bash
tcpdump -i eth0 -nnq -t not arp -l
