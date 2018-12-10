#!/bin/bash
#
# BETLOG
# 2015-07-02--02-01-56
# Create an image with text reading same as any datetimeoriginal from jpgs in the folder - for some simple oh-hi.info indexing
#
declare -a g_dateArray

for d in "$@";do
    if [[ -d $d ]];then
        kdialog --title "Generating Index Image..." --passivepopup "$d" 5
        for f in ${d}/*.[Jj][Pp][Gg]; do
#             date=$(exiftool -s -s -s -DateTimeOriginal ${f})
            date=$(exiv2 -Pv -g Exif.Photo.DateTimeOriginal ${f})
            date=${date%% *}
            date=${date//=/}
            date=${date//:/-}
echo "XX${date}XX"
            if [[ ! "${g_dateArray[*]}" =~ "$date" ]]; then
                g_dateArray+=(${date})
   echo "added ${#g_dateArray[@]} - $date"
            fi
        done
echo "ARRAY: ${g_dateArray[*]}"
        for j in ${g_dateArray[@]}; do
#             convert -background black -size 400x600 -quality 90% -draw "fill '#111111' polygon 360,30  360,570  385,300" \        
            convert -background black -size 480x720 -quality 100% -draw "fill '#111111' polygon 440,30  440,690  465,360" \
                -pointsize 33 -font Monospace -gravity center -fill '#FF8800' label:"${j}" "${d}/${j}.jpg"
    #
            exiftool -overwrite_original_in_place -ExposureTime=1 -FNumber=1.0 -ISO=100 \
                -ExposureCompensation=0 -FocalLength='1.0 mm' \
                -DateTimeOriginal="${j} 23:59:59+10:00" "${d}/${j}.jpg"
    #        
            kdialog --title "Created Index Image:" --passivepopup "$j" 2
        done
    else
        kdialog --title "WARNING:" --passivepopup "${d}\nNot a directory" 2
    fi
    
done
kdialog --title "COMPLETED:" --passivepopup "Index Image Generation" 5