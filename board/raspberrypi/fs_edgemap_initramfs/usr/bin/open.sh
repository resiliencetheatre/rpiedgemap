#/bin/sh
#
# Test script to mount encrypted partition with HSM encrypted key file
#
# Obtain LUKS key from HSM
pkcs15-crypt --decipher --key 10 --input /opt/hsm/lukskey.pkcs1 \
--pkcs1 --raw --pin 162412 > /tmp/lukskey.plain;
# Open
cryptsetup luksOpen /dev/mmcblk0p2 encpart --key-file=/tmp/lukskey.plain;
mkdir /tmp/crypto;
# TODO: mount as read only
mount /dev/mapper/encpart /tmp/crypto;
systemctl daemon-reload;
rm /tmp/lukskey.plain;
# Done
echo "/tmp/crypto mounted!"
