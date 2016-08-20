#!/usr/bin/env bash
echo "start"
tcpdump -i eth0 -nnq -l| tee -a out.cap