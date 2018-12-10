/*
 *    
 *    This file is part of OhHi
 *    http://github.com/brandon-lockaby/OhHi
 *    
 *    (c) Brandon Lockaby http://about.me/brandonlockaby for http://oh-hi.info
 *    
 *    OhHi is free software licensed under Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0)
 *    http://creativecommons.org/licenses/by-nc-sa/3.0/
 *    
 */
/*
 *    Andrew Eglington - 2015-05-22--19-17-08
 *    
 *        ISSUES:
 *            Need to differentiate 1px wide index images otherwise large numbers of them together make load weird and slow
 *            eg: http://192.168.178.25/2010/?from=2010-08-23.jpg
 *            
 */

$(function(){
    
    // load below
    var first_load = true;
    var loadBelowInterval;
    var loadBelow = function() {
        if($(window).scrollTop() > $(".image:last").offset().top - ($(window).height() * 6)) {
            var from = $("#images .image:last");
            var images = $.get("?get=below&from="+from.attr("data-filename"))
            .success(function(data) {
// console.log("data" + data);                            
                if($(data).find(".image").length > 0) {
                    $("#images").append($(data).find(".image"));
                    if(first_load) {
                        first_load = false;
                        setHistoryFilename($(".image:first").attr("data-filename"));
                    }
                    loadBelowInterval = setInterval(loadBelow, 500);
                }
            });
            clearInterval(loadBelowInterval);
        }
    }
    loadBelowInterval = setInterval(loadBelow, 500);
    
    // load above
    var loadAboveInterval;
    var loadAbove = function() {
        if($(window).scrollTop() < ($(window).height() * 6)) {
            var from = $("#images .image:first");
            var images = $.get("?get=above&from="+from.attr("data-filename"))
            .success(function(data) {
                if($(data).find(".image").length > 0) {
                    var scrolltop = $(window).scrollTop();
                    $("#images").prepend('<div id="hidden-images" style="display:none"></div>');
                    $("#hidden-images").html($(data).find(".image"));
                    var height = $("#hidden-images").height();
                    var last_image_height = $("#hidden-images .image:last img").attr("height");
                    $("#hidden-images").remove();
                    $("#images").prepend('<div style="width:100%" class="load-below-fixup" data-height="' + last_image_height + '"/>');
                    $("#images").prepend($(data).find(".image"));
                    $(window).scrollTop(scrolltop + height);
                    loadAboveInterval = setInterval(loadAbove, 500);
                }
            });
            clearInterval(loadAboveInterval);
        }
    }
    loadAboveInterval = setInterval(loadAbove, 500);
    
    // layout fixup for load-above
    setInterval(function() {
        var window_bottom = $(window).scrollTop() + $(window).height();
        $(".load-below-fixup").each(function() {
            if(($(this).offset().top - $(this).attr("data-height")) > window_bottom) {
                $(this).remove();
            }
        });
    }, 1000);
    
    //update history
    var historyFilename = "";
    function setHistoryFilename(filename) {
        if(history && history.replaceState && filename !== historyFilename) {
            historyFilename = filename;
            var url = "?from=" + historyFilename;
            history.replaceState(null, null, url);
        }
    }
    if(history && history.replaceState) {
        var first_filename = $(".image:first").attr("data-filename");
        if(first_filename) {
            setHistoryFilename(first_filename);
        }
        $( window ).scroll(function() {
            clearTimeout( $.data( this, "scrollCheck" ) );
            $.data( this, "scrollCheck", setTimeout(function() {
            first_filename = $(".image:first").attr("data-filename");
            var scrolltop = $(window).scrollTop();
            var nearest_filename;
            var nearest_top;
            var flag = true;
            $(".image").each(function() {
                var top = $(this).offset().top - scrolltop - 100;
                if(top > 0) {
                    if(flag || top < nearest_top) {
                        flag = false;
                        nearest_filename = $(this).attr("data-filename");
                        nearest_top = top;
                    }
                }
            });
            setHistoryFilename(nearest_filename);
            }, 500) );
        });    
    }    
    
    //'more' menu
    var previousClick="undefined";
    $("#images").on("click", function(evt) {    //on general images div id=images
        $(document).find(".more:visible").css({"display":"none"});
        if(/.jpg/.test(evt.target.src) == false) {
            previousClick="notAnImage";
        } else {            
            if(evt.target.src == previousClick) {            
                previousClick="undefined";
            } else {
                previousClick=evt.target.src;                
                var more = $(evt.target.nextElementSibling);
                if(renderHistogram && more.find('.histogram').length == 0) {
                    more.find(".exif").append('<canvas class="histogram" width="256" height="64"></canvas>');
                    var canvas = more.find(".histogram").get(0);                                            
                    renderHistogram($(more).parents(".image").find("img").get(0), canvas);                        
                }
                if(more.find('.share').length == 0) {
                    var shareURL = evt.target.src;  
                     var pos = shareURL.lastIndexOf('/');
                     var shareFromURL = shareURL.substring(0,pos) + '/?from=' + shareURL.substring(pos+1);
//                     more.append('<div class="share">\
//                     <div class="shareContainer"><a href="'+shareFromURL+'">\
//                     <img src="/oh-hi/images/link-red.png"></a></div>\
//                     <div class="shareContainer"><a href="'+shareURL+'">\
//                     <img src="/oh-hi/images/link-blue.png"></a></div>\
//                     <div class="shareContainer"><a href="https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(shareURL)+'" target="_new">\
//                     <img src="/oh-hi/images/facebook.png"></a></div>\
//                     <div class="shareContainer"><a href="https://twitter.com/share?url='+encodeURIComponent(shareURL)+'" target="_new">\
//                     <img src="/oh-hi/images/twitter.png"></a></div>\
//                     <div class="shareContainer"><a href="https://plus.google.com/share?url='+encodeURIComponent(shareURL)+'" target="_new">\
//                     <img src="/oh-hi/images/googleplus.png"></a></div>\
//                     <div class="shareContainer"><a href="http://www.reddit.com/submit?url='+encodeURIComponent(shareURL)+'" target="_new">\
//                     <img src="/oh-hi/images/reddit.png"></a></div>\
//                     </div>');
//                   
// RDFa 1.1 extends HTML5 so that it’s valid to use meta and link elements in the body, as long as they contain a property attribute.
//
//https://developers.facebook.com/tools/debug/og/object/
//
//fffffffffffffffffffffffffffffffffffffffails anyway - should work?
                    more.append('<div class="share">\
                        <div class="shareContainer"><a href="'+shareFromURL+'" title="Link into stream starting at this image.">\
                            <img src="/oh-hi/images/link-red.png"></a></div>\
                            <div class="shareContainer"><a href="'+shareURL+'" target="_new" title="Open in a new tab.">\
                            <img src="/oh-hi/images/link-blue.png"></a></div>\
                        <div class="shareContainer">\
                            <meta property="og:image" content="'+shareURL+'">\
                            <meta property="og:url" content="'+shareURL+'">\
                            <a href="https://www.facebook.com/sharer/sharer.php?u='+shareURL+'" target="_new" title="Share this image on FaceBook">\
                            <img src="/oh-hi/images/facebook.png"></a></div>\
                        <div class="shareContainer"><a href="https://twitter.com/share?url='+shareURL+'" target="_new" title="Share this image on Twitter">\
                            <img src="/oh-hi/images/twitter.png"></a></div>\
                        <div class="shareContainer"><a href="https://plus.google.com/share?url='+shareURL+'" target="_new" title="Share this image on Google+">\
                            <img src="/oh-hi/images/googleplus.png"></a></div>\
                        <div class="shareContainer"><a href="http://www.reddit.com/submit?url='+shareURL+'" target="_new" title="Share this image on Reddit">\
                            <img src="/oh-hi/images/reddit.png"></a></div>\
                    </div>');
//
//                     more.append('<div class="share">\
//                     <div class="shareContainer"><a href="'+shareURL+'">\
//                     <img src="/oh-hi/images/link-red.png"></a></div>\
//                     <div class="shareContainer"><a href="'+shareURL+'">\
//                     <img src="/oh-hi/images/link-blue.png"></a></div>\
//                     <div class="shareContainer"><a href="https://www.facebook.com/sharer/sharer.php?u='+shareURL+'" target="_new">\
//                     <img src="/oh-hi/images/facebook.png"></a></div>\
//                     <div class="shareContainer"><a href="https://twitter.com/share?url='+shareURL+'" target="_new">\
//                     <img src="/oh-hi/images/twitter.png"></a></div>\
//                     <div class="shareContainer"><a href="https://plus.google.com/share?url='+shareURL+'" target="_new">\
//                     <img src="/oh-hi/images/googleplus.png"></a></div>\
//                     <div class="shareContainer"><a href="http://www.reddit.com/submit?url='+shareURL+'" target="_new">\
//                     <img src="/oh-hi/images/reddit.png"></a></div>\
//                     </div>');
                }
                evt.target.nextElementSibling.style.display = 'block';                 
            }
            evt.stopPropagation();
        }
    });
//     href="https://pinterest.com/pin/create/button/?url='+encodeURIComponent(shareURL)+'&media='+encodeURIComponent(shareURL)+'" target="_new">\
//     <img src="/oh-hi/images/pinterest.png"></a></div>\
    
    
    // histogram
    var scratchCanvas = document.createElement("canvas");
    if(scratchCanvas.getContext) {
        Array.prototype.init = function(x, n) {
            if(typeof(n)=='undefined') { n = this.length; }
            while (n--) { this[n] = x; }
            return this;
        };
        Array.prototype.max = function(){
            return Math.max.apply( Math, this );
        };
        Array.prototype.min = function(){
            return Math.min.apply( Math, this );
        };
        
        renderHistogram = function(img, dest) {
            // img to temporary canvas
            scratchCanvas.width = img.width;
            scratchCanvas.height = img.height;
            var ctx = scratchCanvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            
            // data
            var data = ctx.getImageData(0, 0, img.width, img.height);
            var len = data.width * data.height * 4; // aka data.data.length * 4 ?
            var reds = [].init(0, 256);
            var greens = [].init(0, 256);
            var blues = [].init(0, 256);
            for(var i = 0; i < len; i += 4) {
                ++reds[data.data[i]];
                ++greens[data.data[i + 1]];
                ++blues[data.data[i + 2]];
            }
            
            // scale and flip
            var max = reds.max();
            var n = greens.max(); if(n > max) max = n;
            n = blues.max(); if(n > max) max = n;
            var den = max / dest.height;
            for(var i = 0; i < 256; i++) {
                reds[i] = dest.height - (reds[i] / den);
                greens[i] = dest.height - (greens[i] / den);
                blues[i] = dest.height - (blues[i] / den);
            }
            
            // dest canvas context
            var ctx = dest.getContext("2d");
            ctx.globalCompositeOperation = "lighter";
            ctx.lineWidth = 2;
            ctx.lineCap = "round";
            ctx.lineJoin = "round";
            
            // curves
            var drawCurve = function(values, stroke, fill) {
                ctx.strokeStyle = stroke;
                ctx.fillStyle = fill;
                ctx.beginPath();
                ctx.moveTo(0.5, values[0] + 0.5);
                for(var i = 1; i < 256; i++) {
                    ctx.lineTo(i + 0.5, values[i] + 0.5);
                }
                ctx.stroke();
                ctx.lineTo(dest.width - 0.5, dest.height - 0.5);
                ctx.lineTo(0.5, dest.height - 0.5);
                ctx.closePath();
                ctx.fill();
            }
            drawCurve(reds, "#a00", "#f00");
            drawCurve(greens, "#0a0", "#0f0");
            drawCurve(blues, "#00a", "#00f");
        }
    }    
    
    
    
});
