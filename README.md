# rpi-edgemap (initramfs, meshtastic)

External tree for [buildroot](https://buildroot.org) to build RaspberryPi4 based [Edgemap](https://resilience-theatre.com/edgemap/) firmware image. 

![meshtastic](https://github.com/resiliencetheatre/rpiedgemap/blob/meshtastic/doc/meshtastic-kit.png?raw=true)

## Features

This branch contains edgeUI for browser based mapping with [Protomaps](https://protomaps.com/) and message delivery 
over [Meshtastic](https://meshtastic.org/) radios. Setup is tested with [LILYGO LoRa32 V2.1_1.6 radios](https://www.lilygo.cc/products/lora3).
 
Branch is stripped down version of [Edgemap](https://resilience-theatre.com/edgemap/) firmware image for
[Raspberry Pi4](https://en.wikipedia.org/wiki/Raspberry_Pi) and does not need tileserver-gl to serve 
mbtiles. All maps are pmtiles handled by [maplibre-gl-js](https://github.com/maplibre/maplibre-gl-js) library.

Image contains [taky](https://github.com/tkuester/taky) a simple [COT](https://www.mitre.org/sites/default/files/pdf/09_4937.pdf) server 
for [ATAK](https://tak.gov/products). CoT server is mainly targeted to read CoT targets in network and illustrate them on Web user interface.
This functionality was tested with [AN/PRC-169](https://silvustechnologies.com/) radios.

Buildroot is used to build [initramfs](https://en.wikipedia.org/wiki/Initial_ramdisk) image and it contains kernel and rootfs on single file
on MicroSD card boot partition. This makes system total ephemeral and updating OS (including rootfs) does not require full MicroSD copying. Instead
you can drop new 'Image' file to boot partition and update is done. 

For [operations security](https://en.wikipedia.org/wiki/Operations_security) we use Linux Unified Key Setup (LUKS) to encrypt data partition on same MicroSD card (or external USB drive).
Since we aim to use RPi4 in headless configuration we use [systemd-cryptsetup](https://www.freedesktop.org/software/systemd/man/latest/systemd-cryptsetup@.service.html)
with [FIDO2](https://shop.nitrokey.com/shop/product/nkfi2-nitrokey-fido2-55) key. With this approach we can start unit to fully operational state with FIDO2
key plugged in to USB port and after unit is booted (and LUKS partitions are opened) - we can remove FIDO2 key. 

Browser usable user interface allows wide range of end user devices (EUD's) without any additional software installs. Check out [edgemap-ui](https://github.com/resiliencetheatre/edgemap-ui) repository
for more details how Edgemap can be integrated to various systems.

This version does NOT have high rate target, simulations, CoT reading and [AN/PRC-169](https://silvustechnologies.com/) support. 

If you choose to use browser based geolocation, configure installation to use TLS connection. 

## Meshtastic

Meshtastic implementation is still highly experimental and contains only message delivery over meshtastic radios. Following
picture gives overview how FIFO pipes are used to deliver payload to/from radios.

![meshtastic](https://github.com/resiliencetheatre/rpiedgemap/blob/meshtastic/doc/meshtastic.png?raw=true)

This meshtasic branch is configured to use second partition for maps, elevation model and imagery without encryption. And some of messaging 
channel functions have been commented out on UI code. We don't deliver 'drag marker' or 'geolocation' over meshtastic and we have increased
presence indication sending interval to 2 minute.


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

Checkout meshtastic branch on from rpiedgemap:

```
cd ~/build-directory/rpiedgemap
git checkout meshtastic
```

Current build uses master branch of buildroot. Build is tested with 87943b75310190db05342232046790db0f8e4232.

Modify `rpi-firmware` package file and change firmware version tag to
match kernel version (6.6.26) we're using. 

```
# package/rpi-firmware/rpi-firmware.mk
RPI_FIRMWARE_VERSION = 45319db29eb5e4f67feab5c4194bc1f28c574ed0
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

By default meshtastic branch works without FIDO2 enabled encryption, use `create-partition-noenc.sh` script to partition remaining space on your MicroSD card.

Place pmtiles to your MicroSD second partition and link them under /opt/edgemap/edgeui/ on running instance. Modify also styles/style.json to 
match amount of sources available.

## Map data

You need to have full [planet OSM](https://maps.protomaps.com/builds/) pmtiles and A global terrain RGB dataset from [Mapzen Joerd](https://github.com/tilezen/joerd) project.

## Local GPS

Meshtastic branch has [gpsd ](https://gpsd.io/) package which can read your locally attached GPS receiver(s) and expose
location information to localhost socket. There is also [gpsreader](https://github.com/resiliencetheatre/gpsreader) as
buildroot package and it's used to read gpsd socket and output location string to fifo file. This fifo is delivered to 
map UI via websocket. 

Locally connected GPS is good companion for Edgemap with Meshtastic radios. Typically browser based geolocation (also present 
in edgemap UI) requires TLS connection between EUD (client) and edgemap (server). Since TLS connection requires complex and
meta data enriched certificates to be present and real time (difficult to obtain in battle space) we offer edgemap UI without
TLS to local network connected EUD's. Browser geolocation requires also Cell, Wifi and BT to be active (stupidity in battle space) for optimum results.

You can configure GPS serial port:

```
# /etc/default/gpsd
DEVICES="/dev/ttyUSB1"
```

You can modify this file before build at external directory:

```
board/raspberrypi/fs_edgemap_initramfs/etc/default/gpsd
```

You could also use 'bootstrap.sh' to replace that file and restart service, 'bootstrap.sh' is run on boot from boot partition.

Daemon (gpsd) and 'gpsreader' is started automatically after you plug GPS in USB port.
