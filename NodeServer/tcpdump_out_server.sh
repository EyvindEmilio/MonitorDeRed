#!/usr/bin/env bash
tcpdump -i eth0 -nnq -l| tee -a out.cap