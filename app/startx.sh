
## Set up the lcd screen
modprobe fbtft_device name=tinylcd35 rotate=270 speed=36000000

## Start x windows
#FRAMEBUFFER=/dev/fb1 startx
startx