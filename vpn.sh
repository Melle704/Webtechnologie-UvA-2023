#!/bin/sh

# NOTE: sudo pip3 install "vpn-slice[dnspython,setproctitle]"

if [ "$(whoami)" != "root" ]; then
    sudo su -s "$0"
    exit
fi

if command -v vpn-slice; then
    vpn_slice="vpn-slice"
elif command -v /root/.local/bin/vpn-slice; then
    vpn_slice="~/.local/bin/vpn-slice"
fi

openconnect --protocol=nc vpn.uva.nl -s "$vpn_slice 83.96.0.0/16 85.10.0.0/16"
