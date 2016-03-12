/**
 *
 * check/uncheck all
 * Author: Sanjeev Subedi
 * 
 */
var information = "After adding an Answer, please press return to add next answer";
$(function(){
    var extList = ['pdf','doc','docx','ppt','pptx','mpeg','mp4','mp3','xls','xlsx']; //also change in multi js file
    var logoExtList = ['png','jpeg','jpg','gif'];
    
    //settings event
    $("#settings").click(function(){
        $(".navHolder").show();
        $(".new-survey-icon").hide();
        return false;
    })
    
    //settings event
    $(document).click(function(){
        $(".navHolder").hide();
        $(".new-survey-icon").show();
    })
    
    $(".navHolder").click(function(ev){
        ev.stopPropagation();
    })
    
    // add multiple select / deselect functionality
    $("#parent").click(function () {
        $('.child').attr('checked', this.checked);
    });

    // if all checkbox are selected, check the parent checkbox
    // and viceversa
    $(".child").click(function(){

        if($(".child").length == $(".child:checked").length) {
            $("#parent").attr("checked", "checked");
        } else {
            $("#parent").removeAttr("checked");
        }

    });


     $(".parent-for-hidden").click(function () {
        $('.child').attr('checked', this.checked);
        $('#owner-row').attr('checked', 'checked');
    });

    // if all checkbox are selected, check the parent checkbox
    // and viceversa
    $(".child").click(function(){

        if($(".child").length == $(".child:checked").length) {
            $(".parent-for-hidden").attr("checked", "checked");
        } else {
            $(".parent-for-hidden").removeAttr("checked");
        }

    });




    //single choice
    
    var lastOptionVal = $(".dynamic-answer").prev();
    var originalCountAns = parseInt($(".dynamic-answer").prev().attr('data-ans')) +1;
    var count_ans = originalCountAns;
    $(".add-answer").live('click',function(){
        var subAnswer = "<div class='subans-holder-container' data-id="+count_ans+"></div>"+
        "<div><a class='add-subanswer-trigger' href='#' rel='subanswer-holder"+count_ans +"'>Add sub answer</a></div>";
        var type = $(this).attr('type');
        var HasSubAnswer = type== "dd" ?  "": subAnswer;

        $(".add_answer_parent .dynamic-answer").append("<div class ='answer' id='answer"+count_ans+"'><input type ='text'"+
            "name='answers[]' class='answers' />"+
            "<a href='#' rel='answer"+count_ans+"' class='trigger_anchor'>Remove</a>"+
            HasSubAnswer
            );
        count_ans = count_ans+1;
        return false;
    })
    
    $('.trigger_anchor').live('click', function() {
        var key = $(this).attr('rel');
        $("#"+key).remove();
        count_ans =  originalCountAns;
        
        $('.dynamic-answer .answer').each(function(){
            $(this).attr('id', 'answer'+count_ans);
            $(this).find('a').attr('rel','answer'+count_ans);
            $(this).find('.subans-holder-container').attr('data-id',count_ans);
            count_ans++;
        })
        //count_ans--;
        return false;
    });
    
    
    //create dynamic subanswer
    var count_subans = 100000;
    $(".add-subanswer-trigger").live('click',function() {
        
        var optionId = $(this).parent().prev().attr('data-id');
        //alert(optionId);
        
        var subanshtml = "<div class='subanswer-holder' id='subanswer-holder"+count_subans+"'>"+
        "<p style='clear:both;'>Data entry type</p>"+
        "<select name='data_entry_type_sub"+optionId+"' id='data_entry_type_sub'>"+
        "<option value='2'>radiobutton</option>"+
        "<option value='3'>checkbox</option>"+
        "<option value='1'>textbox</option>"+
        "<option value='4'>select</option>"+
        "</select>"+
        "<div id='subanswers-hold'><div class='subanswers'>Leave empty or add one subanswer per line</div>"+
        "<textarea rows='4' cols='3' name='subanswers["+optionId+"]'></textarea>"+
        "</div></div>";
        //var subanswerHolderId = $(this).attr('rel');
        //$("#"+subanswerHolderId).show();
        
        //if(count_subans == 100001) {
        $(this).parent().prev().append(subanshtml);
        $(this).parent().html("<a class='delete-subanswer-trigger' href='#' rel='subanswer-holder"+count_subans +"'>Remove sub answer</a>");
        count_subans++;    
        //}
        
        return false;
    })
    
    //delete subanswer
    $('.delete-subanswer-trigger').live('click', function() {
        $(this).parent().prev().empty();
        $(this).parent().html("<a class='add-subanswer-trigger' href='#' rel='subanswer-holder"+count_ans +"'>Add sub answer</a>");
        return false;
    });
    
    //hide subanswer box while selecting textbox in sub answer data entry type
    
    /*$("#data_entry_type_sub").live('change',function() {
        if($(this).val() == 1) {
            $(this).parent().find("#subanswers-hold").hide();
        }else{
            $(this).parent().find("#subanswers-hold").show(); 
        }
    })*/
    
    //toggle
    $(".toogle-logic").live('click',function(){
        $(this).next().toggle();
    });
    
    //show/hide attaching documents to questions and answers
    /* $("#q_doc_container").hide();
    $("#a_doc_container").hide();
    
    $("#chk_q_doc").live('click',function(){
        if($(this).attr('checked')) {
            $("#q_doc_container").slideUp();
            
        }else {
            
            $("#q_doc_container").slideDown();
        }
    })
    
    $("#chk_a_doc").live('click',function(){
        if($(this).attr('checked')) {
            $("#a_doc_container").slideUp();
        }
        else {
            $("#a_doc_container").slideDown();
        }
    })*/
    
    var  width = 0;
    var hIndex = 0;
    var vIndex = 0;
    var showContingencyOptions = function () {
        var contingencyMiddle = "";
        var hoptions = ""
        var dataEntry = "";
        var type = "";
        
        $(".data-entry-lists li a").each(function(){
            if($(this).hasClass('jqTransformChecked')) {
                dataEntry = $(this).next().next().next().val();
                return false;
            }
        })
    
        $(".answers-h").each(function(){
            hIndex++;
        })
            
        $(".answers-v").each(function(){
            type += "<div class= 'entry-container' id='entry-"+vIndex+"'>"
            for(var hi = 0; hi<hIndex;hi++) {
                
                if(dataEntry == 3) {
                    type += "<div class='c-dataentry' id='"+vIndex+hi+"'><input type = 'checkbox' /></div>"
                } else if (dataEntry == 4) {
                    type += "<div class='c-dataentry000 c-dataentry-"+vIndex+"'><select id='add"+vIndex+hi+"'><option value=''>option</option></select><a href='#optionlist"+vIndex+hi+"' class = 'dropdown-options' id='"+vIndex+hi+"'>+</a>"+
                    "<div class='optioncontainer' id='optionlist"+vIndex+hi+"' style='display:none'>"+
                    "<div class='info-options'>"+information+"</div><textarea name='options["+vIndex+hi+"]' rows='5' cols='5'></textarea>"+
                    "<a href='#' id='save_"+vIndex+hi+"' class = 'save-option'>save</a></div></div>";
                }else if (dataEntry == 1) {
                    type += "<div class='c-dataentry' id='"+vIndex+hi+"' ><input type='text'/></div>"
                } else {
                    type += "<div class='c-dataentry' id='"+vIndex+hi+"'><input type = 'radio' /></div>";
                }
            }
            vIndex++;
            type += "</div>";
            type += "<div class='clearfix'></div>";
        })  
        
        return type;
    }
    var count = 100000;
    $(".add-answer-h").live('click',function(){
        $(".add_answer_parent_h").append("<div id='h_answers"+count+"'><input type ='text'"+
            "name='h_answers[]' class='answers answers-h' />"+
            "<a href='#' rel='h_answers"+count+"' class='trigger_anchor_h'></a></div>");
        count = count+1;
        
        //$("#c-input-type").append(showOptCol());
        showOptCol();
        hIndex = 0;
        vIndex = 0;
                
        //adjust the width for scroll
        width = $(".horizontal_container").width()
        width = width + 130;
        $(".horizontal_container").css('width',width);
        $(".vertical_container").css('width',width);
        
        return false;
    })
    
    $('.trigger_anchor_h').live('click', function() {
        //adjust the width for scroll
        width = $(".horizontal_container").width()
        width = width - 130;
        $(".horizontal_container").css('width',width);
        $(".vertical_container").css('width',width);
        var key = $(this).attr('rel');
        
        var parentContainer = $(this).parent().attr('id');
        //remove all the related middle part
        $(".answers-h").each(function(index,value){
            if($(this).parent().attr('id') == parentContainer){
                $(".c-dataentry"+index).remove();
                return false;
            }
            
            
        })
        $("#"+key).remove();
        
        //reset the indexes of middle part
        $(".entry-container").each(function(eindex,evalue){
            $(this).attr('id','entry-'+eindex);
            $(this).find(".c-dataentry").each(function(cindex,cvalue){
                
                $(this).attr('class','');
                $(this).addClass('c-dataentry');
                $(this).addClass('c-dataentry'+cindex);
                
                $(this).find("select").attr('id','add'+eindex+cindex);
                $(this).find("a.dropdown-options").attr('id',''+eindex+cindex);
                $(this).find("a.dropdown-options").attr('href','#optionlist'+eindex+cindex);
                $(this).find(".optioncontainer a").attr('id','save_'+eindex+cindex);
                $(this).find(".optioncontainer").attr('id','optionlist'+eindex+cindex);
                $(this).find("textarea").attr('name','options['+eindex+cindex+']');
            })
        })
        
        
        hIndex = 0;
        vIndex = 0;
        return false;
    });
    
    var hcount = 100000;
    $(".add-answer-v").live('click',function(){
        $(".add_answer_parent_v").append("<div id='v_answers"+hcount+"'><input type ='text'"+
            "name='v_answers[]' class='answers answers-v' />"+
            "<a href='#' rel='v_answers"+hcount+"' class='trigger_anchor_v'></a></div>");
        hcount = hcount+1;
        
        $("#c-input-type").append(showOptRow());
        
        hIndex = 0;
        vIndex = 0;
        return false;
    })
    
   
    var showOptRow = function () {
        var col = $(".answers-h").length;
        var row = $(".answers-v").length;
        console.log(col);
        var verIndex = row-1;
        var dataEntry = getDataEntryType();
        var selectContent = "<div class= 'entry-container' id='entry-"+verIndex+"'>";
        for(var cnt=0;cnt<col;cnt++){
            
            selectContent +="<div class='c-dataentry c-dataentry"+cnt+"'>";
            
            if(dataEntry == 4) {
                selectContent += "<select id='add"+verIndex+cnt+"'><option value=''>option</option></select><a href='#optionlist"+verIndex+cnt+"' class = 'dropdown-options' id='"+verIndex+cnt+"'>+</a>"+
                "<div class='optioncontainer' id='optionlist"+verIndex+cnt+"' style='display:none'>"+
                "<div class='info-options'><div class='answer-title'>Answer</div>"+information+"</div><textarea name='options["+verIndex+cnt+"]' rows='5' cols='5'></textarea>"+
                "<a href='#' id='save_"+verIndex+cnt+"' class = 'save-option'>save</a></div>";
            } else if(dataEntry == 3) {
                selectContent += "<input type='checkbox' />";
            }else if(dataEntry == 1) {
                selectContent += "<input type='text'/>";
            } else {
                selectContent += "<input type='radio'/>";
            }
            selectContent += "</div>";    
        }
        selectContent += "</div>";
        return selectContent;
    }
    
    var showOptCol = function () {
        var col = $(".answers-h").length;
        var row = $(".answers-v").length;
       
        var rowIndex = col-1;
        var dataEntry = getDataEntryType();
        
        for(var cnt=0;cnt<row;cnt++){
            var selectContent ="<div class='c-dataentry c-dataentry"+rowIndex+"'>";
        
            if(dataEntry == 4) {
                selectContent += "<select id='add"+cnt+rowIndex+"'><option value=''>option</option></select><a href='#optionlist"+cnt+rowIndex+"' class = 'dropdown-options' id='"+cnt+rowIndex+"'>+</a>"+
                "<div class='optioncontainer' id='optionlist"+cnt+rowIndex+"' style='display:none'>"+
                "<div class='info-options'><div class='answer-title'>Answer</div>"+information+"</div><textarea name='options["+cnt+rowIndex+"]' rows='5' cols='5'></textarea>"+
                "<a href='#' id='save_"+cnt+rowIndex+"' class = 'save-option'>save</a></div>";
            } else if(dataEntry == 3) {
                selectContent += "<input type='checkbox'";
            }else if(dataEntry == 1) {
                selectContent += "<input type='text'";
            } else {
                selectContent += "<input type='radio'";
            }
            selectContent += "</div>";
            
            $("#entry-"+cnt).append(selectContent);

        }
    }
    
    
    
    $('.trigger_anchor_v').live('click', function() {
        var key = $(this).attr('rel');
        var parentContainer = $(this).parent().attr('id');

        //remove all the related middle part
        $(".answers-v").each(function(index,value){
            //console.log(index);
            if($(this).parent().attr('id') == parentContainer){
                $("#entry-"+index).remove();
                //console.log($(".entry-container").length);
                
                //reset the indexes of middle part
                $(".entry-container").each(function(eindex,evalue){
                    $(this).attr('id','entry-'+eindex);
                    $(this).find(".c-dataentry").each(function(cindex,cvalue){
                        $(this).find("select").attr('id','add'+eindex+cindex);
                        $(this).find("a.dropdown-options").attr('id',''+eindex+cindex);
                        $(this).find("a.dropdown-options").attr('href','#optionlist'+eindex+cindex);
                        $(this).find(".optioncontainer a").attr('id','save_'+eindex+cindex);
                        $(this).find(".optioncontainer").attr('id','optionlist'+eindex+cindex);
                        $(this).find("textarea").attr('name','options['+eindex+cindex+']');
                    })
                })
                
                
                return false;
            }  
        })
        
        $("#"+key).remove();
       
        hIndex = 0;
        vIndex = 0;
        return false;
    });
    
    //removes all the leading and trailing whitespace of textarea
    $("#new_question textarea, #edit_contact textarea").each(function(){
        var value = $(this).val();
        value = $.trim(value);
        $(this).val(value);
    })
    
    //confirm on delete action
    //delete alert
    jQuery(".deleteOption").click(function (e)
    {
        var bool;
        bool = confirm("Confirm to delete!");
        if(!bool){
            e.preventDefault();
        }
    });
    
    //validates all the answer documents types only
    $('.common_doc').live('change',function(){
        var fileSize = this.files[0].size;
        var ext = $(this).val().split('.').pop().toLowerCase();
        checkExtension(ext,extList);
        checkSize(fileSize,$(this));
    }); 
    
    //validates the logo
    $('.common_logo').live('change',function(){
        var fileSize = this.files[0].size;
        var ext = $(this).val().split('.').pop().toLowerCase();
        checkExtension(ext,logoExtList);
        checkSize(fileSize,$(this));
    });
    
    function checkSize(fileSize,field) {
        var limit = 2000000; //2 MB
        if(fileSize > limit) {
            alert('Max 2 MB is allowed.');
            field.val('');
        } 
    }
    
    function checkExtension(ext,extList) {
        var msg = extList.join();
        if($.inArray(ext, extList) == -1) {
            alert('You cannot select a ' +ext +' file.\n Only '+ msg+' are allowed.');
            $(this).val('');
        }
    }
    
    /*function convertSize(size) {
        if (size < 1024) return size + ' Bytes';
        size /= 1024;
        if (size < 1024) return size.toFixed(2) + ' KB';
        size /= 1024;
        if (size < 1024) return size.toFixed(2) + ' MB';
        size /= 1024;
        return size.toFixed(2) + ' GB';
    }*/
    
    //add class
    $('.new_question .input-container').eq(1).addClass("selectWrapper");  
   
    //menu activation
    var url = window.location.pathname;
    
    var steps = url.split('/');
    var step = steps[3] != "" ? steps[3]:"";
    var module = steps[2] != "" ? steps[2]:"";
    
    var zIndex = 6;
    $("ul.tabs li").each(function(){
        var href = $(this).find('a').attr("href");
        if(href != "#") {
            var hrefsteps = href.split('/');
            var hrefstep = hrefsteps[5];
            $(this).addClass('active');
            $(this).css('z-index',zIndex);
            
            // used for question add/edit page
            if(module == "question") {
                if(hrefstep == "step_5") {
                    return false;
                }
            }
            //for language translate interface
            else if(module == "language") {
                if(hrefstep == "survey_lang") {
                    return false;
                }
            }
            else if(step == hrefstep) {
                return false;    
            }
            zIndex--;
        } else {
            $(this).addClass('active');
            return false;
        }
    })
    
    
    //show/hide existing/new template
    $("#existing-temp-check").live('click',function(){
        if(!$(this).attr('checked')) {
            $("#existing-templates-container").slideDown();
        }
    })
    
    
    $(".save-option").live('click',function(){
        var id_details = $(this).attr('id');
        var splitted_val = id_details.split('_');
        var id = splitted_val[1];
        var selectList = $("#add"+id);
        var txtarea = $("#optionlist"+id + " textarea").val();
        var allopts = "";
        var options = txtarea.split("\n");
        $(options).each(function(index,value){
            if(value != "") {
                allopts += "<option value= '"+value+"'>"+value+"</option>";
               
            }
        })
        selectList.html(allopts);
        $.fancybox.close();
        return false;
        
    })
    

})

function getDataEntryType() {
    var dataEntry = "";
    $(".data-entry-lists li a").each(function(){
        if($(this).hasClass('jqTransformChecked')) {
            dataEntry = $(this).next().next().next().val();
        }
    })
    return dataEntry;
       
}