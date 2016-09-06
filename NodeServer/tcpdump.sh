#!/usr/bin/env bash
tcpdump -i wlan0 -nnq -t not arp -l
