################################################################################
#
# LIBFIDO2
#
# See: https://github.com/Yubico/libfido2/pull/708/commits/0bd31f72c9f5eeef49de1079917656b2fc063f7f
#
################################################################################

LIBFIDO2_VERSION = 92e3d895c30a4a1a92ecaa698ee19e08aa06c4a1
LIBFIDO2_SITE = $(call github,Yubico,libfido2,$(LIBFIDO2_VERSION))
LIBFIDO2_DEPENDENCIES = libcbor
LIBFIDO2_INSTALL_STAGING = YES
LIBFIDO2_AUTORECONF = YES
LIBFIDO2_CONF_OPTS = -DBUILD_MANPAGES=OFF -DBUILD_EXAMPLES=OFF -DNFC_LINUX=OFF -DUSE_WINHELLO=OFF

# LIBFIDO2_LICENSE = GPL-2.0
LIBFIDO2_LICENSE_FILES = LICENSE

$(eval $(cmake-package))
