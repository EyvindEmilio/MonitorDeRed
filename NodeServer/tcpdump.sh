#!/usr/bin/env bash
tcpdump -i $i -nnq -t not arp -l
