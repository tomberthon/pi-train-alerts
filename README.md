## Pi Train Alerts

A Pi based Train information board

Used to showcase different technologies all running together on a Raspberry Pi with LCD screen.

Technologies used
 - Docker and Resin  - for deployment
 - PHP - for retrival of data
 - Redis - for temporary data storage
 - Go lang - for API server
 - Node - web app to show status
 - X windows and Surf browser - display to lcd screen

This application has been created to be deployed using [resin.io](http://resin.io/)

Required Hardware
 - Raspberry Pi - tested on Pi 2
 - 3.5" Tiny LCD screen [Order here](http://www.neosecsolutions.com//products.php?28&cPath=17)
 - Wifi dongle / or wired network

Environment variables needed for train alert configuration - Set as env vars in resin:
 - APP_OPENLDBWS_KEY - Auth key from National rail enquiries web service - [Register Here](http://realtime.nationalrail.co.uk/OpenLDBWSRegistration)
 - APP_STATION_CODE - The CRS code for the departure station - [Look up](http://www.railwaycodes.org.uk/CRS/CRS0.shtm)
 - APP_STATION - The name of the station - this must match the name from the CRS code
 
### Enhancements still to do
 - Control brightness
 - Show only destination services
