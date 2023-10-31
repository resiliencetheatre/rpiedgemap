# rpi-edgemap (initramfs)

External tree for [buildroot](https://buildroot.org) to build RaspberryPi4 based Edgemap firmware image. 

## Features

This is second generation Edgemap image for [Raspberry Pi4](https://en.wikipedia.org/wiki/Raspberry_Pi). 
Implementation uses [maplibre-gl-js](https://github.com/maplibre/maplibre-gl-js) library to
render maps and [maptiler-gl-light](https://github.com/maptiler/tileserver-gl) to serve them.

Image contains [taky](https://github.com/tkuester/taky) a simple [COT](https://www.mitre.org/sites/default/files/pdf/09_4937.pdf) server 
for [ATAK](https://tak.gov/products). CoT server is mainly targeted to read CoT targets in network and illustrate them on Web user interface.
This functionality was tested with [AN/PRC-169](https://silvustechnologies.com/) radios.

Buildroot is used to build [initramfs](https://en.wikipedia.org/wiki/Initial_ramdisk) image and it contains kernel and rootfs on single file
on MicroSD card boot partition. This makes system total ephemeral and updating OS (including rootfs) does not require full MicroSD copying. Instead
you can drop new 'Image' file to boot partition and update is done. 

For [operations security](https://en.wikipedia.org/wiki/Operations_security) we use Linux Unified Key Setup (LUKS) to encrypt optional data partition
and maps partition on same MicroSD card (or external USB drive). Since we aim to use RPi4 in headless configuration we use [systemd-cryptsetup](https://www.freedesktop.org/software/systemd/man/latest/systemd-cryptsetup@.service.html)
with [FIDO2](https://shop.nitrokey.com/shop/product/nkfi2-nitrokey-fido2-55) key. With this approach we can start unit to fully operational state with FIDO2
key plugged in to USB port and after unit is booted (and LUKS partitions are opened) - we can remove FIDO2 key. 

Browser usable user interface allows wide range of end user devices (EUD's) without any additional software installs. 

This version does not have target simulations active, but they are present in system and UI component. 

## Building

To build edgemap firmware, you need to install Buildroot environment and clone this repository 
as 'external tree' to buildroot. Make sure you check buildroot manual for required packages 
for your host, before building.

```
mkdir ~/build-directory
cd ~/build-directory
git clone https://git.buildroot.net/buildroot
git clone https://github.com/resiliencetheatre/rpiedgemap
```

Checkout '2023.05.1' for buildroot:

```
cd ~/build-directory/buildroot
git checkout 2023.05.1
```

Modify `rpi-firmware` package file and change firmware version tag to
match kernel version we're using. 

```
# package/rpi-firmware/rpi-firmware.mk
RPI_FIRMWARE_VERSION = 9814645c19bd8621fdd382e13369b1efea816c1c
```

Disable hash check by deleting hash file:

```
cd ~/build-directory/buildroot
rm package/rpi-firmware/rpi-firmware.hash
```

After you're stable with kernel and firmware versions, re-create hash file.

Define _external tree_ location to **BR2_EXTERNAL** variable:

```
export BR2_EXTERNAL=~/build-directory/rpiedgemap
```

Make edgemap configuration (defconfig) and start building:

```
cd ~/build-directory/buildroot
make rpi4_edgemap_defconfig
make
```

After build is completed, you find image file for MicroSD card at:

```
~/build-directory/buildroot/output/images/sdcard.img
```

Use 'dd' to copy this image to your MicroSD card.

## Configuration

After you initial boot completes insert your FIDO2 key and run `create-partition.sh` script to partition remaining space on your MicroSD card. 

## Map data

You need to have mbtiles and terrain data to make this image fully working.

You can create planet.mbtiles from OSM dataset and obtain terrain data from publicly available sources. 
My planet.mbtiles is ~90 GB and if you like to have copy of it, feel free to send me at least 128 GB USB drive and return 
envelope. You can ping me at re_theatre@5222.de with your XMPP client or drop email to info(a)resilience-theatre.com.



