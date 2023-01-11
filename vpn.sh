#!/bin/sh

# NOTE: sudo pip3 install "vpn-slice[dnspython,setproctitle]"

if [ "$(whoami)" != "root" ]; then
    sudo su -s "$0"
    exit
fi

if command -v vpn-slice; then
    openconnect --protocol=nc vpn.uva.nl -s "vpn-slice 83.96.0.0/16 85.10.0.0/16"
elif command -v ~/.local/bin/vpn-slice; then
    openconnect --protocol=nc vpn.uva.nl -s "~/.local/bin/vpn-slice 83.96.0.0/16 85.10.0.0/16"
else
    openconnect --protocol=nc vpn.uva.nl
fi
