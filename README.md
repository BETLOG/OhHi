OhHi
====

Originally written for [oh-hi.info](http://oh-hi.info), this software allows one to run a certain style of photo gallery by simply dropping the photos into a folder.  Photos are chronologically sorted based on EXIF tags.  Modern browsers can enjoy lovely histograms along with selected EXIF data when the user clicks an image.

Usage
-----

1. Drop the `oh-hi` folder into your web root.
2. Drop the supplied `index.php` script into any folder containing images.

Problem?
--------

The software creates a file named `cache` in your image folder -- if something breaks, try removing the cache file

Notes:
------
Script: generate_1x720px-dated-index-images.sh - The date picker ('pick' menu option) locates and displays the nearest photos to the date searched, and relies on specially named and intentionally invisible jpg index files being present within the usual image folders. Script user variables are edited to reflect current needs and executed locally every time I make a new YYYY_M-M folder and the results uploaded.
 
Script: betlogCreateIndexImage.sh - after editing the photos I shot that day they are exported to ./pub/, and this script is executed. It exampines the available (./pub/) images' exif datetimestamps and generates a black image with the date printed on it. Filename is also the datestamp. These are mostly for visual reference, but also serve to fuzzy-locate dates searched in the 'pick' menu. (as above)
