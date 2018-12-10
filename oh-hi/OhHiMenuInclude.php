<!-- INCLUDE  - for MENU -->
        <link rel="stylesheet" href="/oh-hi/css/pickadate/default.css">
        <link rel="stylesheet" href="/oh-hi/css/pickadate/default.date.css">
        <script src="/oh-hi/js/jquery.min.js"></script>
        <script src="/oh-hi/js/ohHiBehavior.js"></script>       
        <script type="text/javascript">
          function displaymenu(id,x,y){
              node=document.getElementById(id);
              node.style.position='absolute'
              node.style.left=x;
              node.style.top=y;
              node.style.visibility='visible';
              }
          function hidemenu(id){
              node=document.getElementById(id);
              node.style.visibility='hidden';
          } 
        </script>       
    </head>
    <body>
        <script src="/oh-hi/js/pickadate/picker.js"></script>
        <script src="/oh-hi/js/pickadate/picker.date.js"></script>
        <script>
        $(function() {
            $('#from').pickadate({
                format: 'yyyy-mm-dd',
                selectMonths: true,
                selectYears: true,
                min: [2010,0,01],
                max: true,
                closeOnSelect: false,
                container: undefined,
                clear: '',
                close: 'Cancel',
                onSet: function(){
                    var text = document.getElementById('from').value;
                    var comp = text.split('-');
                    var y = parseInt(comp[0], 10);
                    var m = parseInt(comp[1], 10);
                    var d = parseInt(comp[2], 10);
                    var date = new Date(y,m-1,d);
                    if (date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d) {
                        location.href='http://' + document.location.hostname + '/?from=' + text + '.jpg';
                        picker.close();
                        event.stopPropagation();
                    }
                },
                onClose: function() {
                    document.getElementById('from').value = 'DatePicker';
                }
            });
        }); 
        </script>       

<!--googleoff: anchor--> <!--googleoff: snippet-->
        <nav id="menu_wrapper"> 
            <ul>
            <li><a href="#">oh-hi</a>
                <ul id="menu">
                <li><a href="#" title="Calendar Selector" onclick="document.getElementById('from').trigger('select');return false;" id="from" >Pick</a></li>
                <li><a href="#">2018</a>
                    <ul>
<!--                     <li><a href="/2018_9-12/"><pre> Sep-Dec</pre></a></li>  -->
                     <li><a href="/2018_5-8/"><pre> May-Aug</pre></a></li> 
                    <li><a href="/2018_1-4/"><pre> Jan-Apr</pre></a></li>
                    </ul>       
                </li>
                <li><a href="#">2017</a>
                    <ul>
                    <li><a href="/2017_9-12/"><pre> Sep-Dec</pre></a></li> 
                    <li><a href="/2017_5-8/"><pre> May-Aug</pre></a></li>
                    <li><a href="/2017_1-4/"><pre> Jan-Apr</pre></a></li>
                    </ul>       
                </li>
                <li><a href="#">2016</a>
                    <ul>
                    <li><a href="/2016_9-12/"><pre> Sep-Dec</pre></a></li>
                    <li><a href="/2016_5-8/"><pre> May-Aug</pre></a></li>
                    <li><a href="/2016_1-4/"><pre> Jan-Apr</pre></a></li>
                    </ul>       
                </li>
                <li><a href="#">2015</a>
                    <ul>
                    <li><a href="/2015_9-12/"><pre> Sep-Dec</pre></a></li>
                    <li><a href="/2015_5-8/"><pre> May-Aug</pre></a></li>
                    <li><a href="/2015_1-4/"><pre> Jan-Apr</pre></a></li>
                    </ul>       
                </li>
                <li><a href="#">2014</a>
                    <ul>
                    <li><a href="/2014_9-12/"><pre> Sep-Dec</pre></a></li>
                    <li><a href="/2014_5-8/"><pre> May-Aug</pre></a></li>      
                    <li><a href="/2014_1-4/"><pre> Jan-Apr</pre></a></li>
                    </ul>
                </li>
                <li><a href="#">2013</a>
                    <ul>
                    <li><a href="/2013_8-12/"><pre> Aug-Dec</pre></a></li>
                    <li><a href="/2013_5-7/"><pre> May-Jul</pre></a></li>
                    <li><a href="/2013_1-4/"><pre> Jan-Apr</pre></a></li>
                    </ul>
                </li>
                <li><a href="/2012/">2012</a></li>
                <li><a href="/2011/">2011</a></li>
                <li><a href="/2010/">2010</a></li>
<!--            <li><a href="/19XX/">19XX</a></li> -->
                <li><a href="#">tech</a>
                    <ul>
                    <li><a href="/tech/">General</a></li>
                    <li><a href="/tech/50mm_compare/">50mm</a></li>
                    <li><a href="/tech/ISO_compare/">ISO</a></li>
                    <li><a href="/tech/telephoto_compare/">Telephoto</a></li>
                    </ul>
                </li>
                <li><a href="/info/">info</a></li>
                </ul>
            </li>
            </ul>
        </nav> 
        <form class="calendarform" id="calendarform">
            <input id="from" type="hidden" class="form-control" />
        </form>
        
 <!--googleon: anchor--> <!--googleon: snippet-->
<!-- END include  - for MENU -->
