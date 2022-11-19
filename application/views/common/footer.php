
<div class="footer">
    <div class="container" style="width:100%; background-color: black;color:white;">
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-4">
                <a href="<?php echo site_url("/"); ?>">
                <img src="<?php echo base_url("assets/images/logo_whiteback.png"); ?>" style="max-width:65px;height:86px; background-color: #ffffff; " class="img-responsive"/>
                </a>
            </div>
            <div class="col-md-7 col-sm-6">

            </div>
            <div class="col-md-3 col-sm-4 col-xs-8 text-middle">
                <div style="float:left;"><img src="<?php echo base_url("assets/images/logo_white.png?ee=1"); ?>" style="max-width:46px; margin:6px;" class="img-responsive"></div>
                <div style="margin-top:30px;margin-right:10px; margin-left:10px; font-size:12px"><strong>Copyright (c) 2022 <br/>schooldname.edu</strong></div>


            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/ekko-lightbox.min.js"); ?>"></script>
<script type="text/javascript">
    $(document).ready(function ($) {

        // delegate calls to data-toggle="lightbox"
        $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
            event.preventDefault();
            return $(this).ekkoLightbox({
                onShown: function() {
                    if (window.console) {
                        return console.log('onShown event fired');
                    }
                },
                onContentLoaded: function() {
                    if (window.console) {
                        return console.log('onContentLoaded event fired');
                    }
                },
                onNavigate: function(direction, itemIndex) {
                    if (window.console) {
                        return console.log('Navigating '+direction+'. Current item: '+itemIndex);
                    }
                }
            });
        });

        //Programatically call
        $('#open-image').click(function (e) {
            e.preventDefault();
            $(this).ekkoLightbox();
        });
        $('#open-youtube').click(function (e) {
            e.preventDefault();
            $(this).ekkoLightbox();
        });

        $(document).delegate('*[data-gallery="navigateTo"]', 'click', function(event) {
            event.preventDefault();
            return $(this).ekkoLightbox({
                onShown: function() {
                    var lb = this;
                    $(lb.modal_content).on('click', '.modal-footer a', function(e) {
                        e.preventDefault();
                        lb.navigateTo(2);
                    });
                }
            });
        });

    });
</script>


<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>
    var prevItem=0;
    var currentItem=0;
    var wid = $('body').css('width');
    var lef2 =0;
    if( parseInt(wid) > 950){
        lef2 = parseInt((parseInt(wid) - 950)/2);
    }
    $("#top_nav").css("left", lef2 +"px");
    $(function(){
        $(window).resize(function(){
            wid = $(this).width();
            lef2 =0;
            if( parseInt(wid) > 950){
                lef2 = parseInt((parseInt(wid) - 950)/2);
            }
            $("#top_nav").css("left", lef2 +"px");
        });
    });

</script>
</body>
</html>