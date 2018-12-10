#!/bin/bash
# betlog 
# Generate 1x720px images named by datetimestamp. 
#   Allows the 'pick' menu to seek the date nearest to that being searched for. 
#   If there is a *large* number of index images packed together (with no display images between them) they will offset the display of images; by one pixel each. This can sometimes be noticeable as a vertical gap between the stream of photos.
# 
# years="2010 2011 2012 2013 2014 2015"
# months='9 12'
# 
years="2017"
months='1 12'

for year in $years; do
echo --- $year ---
  if [ -d ${year} ]; then
    echo /${year} exists
  else
    mkdir ${year}
  fi
  for month in `seq -w $months`; do
  echo -- $month -- 
    days=`echo $(cal -m$month $year) | tail -c 3`
    for day in `seq -w $days`; do
        convert -size 1x720 xc:black ${year}/"${year}-${month}-${day}.jpg"
        exiftool -overwrite_original_in_place -ExposureTime=1 -FNumber=1.0 -ISO=100 -ExposureCompensation=0 -FocalLength='1.0 mm' -DateTimeOriginal="${year}-${month}-${day} 23:59:59+10:00" "${year}/${year}-${month}-${day}.jpg"
    done
  done
done
kdialog --title "DONE" --passivepopup "Creation of passive index date images completed" 2    
