# rpi-edgemap

External tree for [buildroot](https://buildroot.org) to build RaspberryPi4 based edgemap firmware image. 

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

Checkout '2023.05.1' for buildroot

```
cd ~/build-directory/buildroot
git checkout 2023.05.1
```

Modify rpi-firmware package file and change firmware version tag to
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

## Usage

You need to have mbtiles and terrain data to make this image fully working. 

You can create planet.mbtiles from OSM dataset and obtain terrain data
from publicly available source. 


