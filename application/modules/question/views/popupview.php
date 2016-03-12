<!-- popup container-->
<!--<a href="#popup-box" id="popup-trigger"></a>-->
<div id="popup-box" style="display:none">
    <div class="popup-title">Questions</div>
    <div class="popup-body">
        <div>What would you like to do next?</div>
        <input type="submit" name="more" value="Add another question" class="save another" />
        <input type="submit" name="done" value="Done adding question" class="save done" />
    </div>
</div>

<script>
    $(document).ready(function(){
        //popup section
        //$("#final-next").on('click',function(){
        $("#popup-trigger").fancybox({  wrapCSS:'quesitonPopup'});

        //})
        
        $(".save").on('click',function(){
            var done = $(this).hasClass("done");
            if(done) {
                $("input[name=track_btn]").val('done');
                $('form').submit();
            } else {
                $("input[name=track_btn]").val('another');
                $('form').submit();
            }
        })
    })
</script>