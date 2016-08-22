#!/usr/bin/env bash
#sudo iftop -nN -t -o 2s -L1 |tee -a iftop.out
sudo iftop -nN -t -o 2s -F $1/$2 > iftop.out