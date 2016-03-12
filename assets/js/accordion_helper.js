/**
 *
 * accordion helper function
 * Author: Sanjeev Subedi
 * 
 */
var accordionHandler = function (accordionName,panelNo) {
   
    var gCurrentIndex = 0; // global variable to keep the current index;
    var ACCORDION_PANEL_COUNT = 3; //global variable for panel count. Here 3 is for zero based accordion index
 
    var wizard = $("#"+accordionName).accordion({
        disabled: accordionVal, //referenced from head.php file as a global
        event: 'click',
        active: panelNo == "" ? 0 : panelNo,
        heightStyle: "content",
        change: function (event, ui) {
            gCurrentIndex = $(this).find("h3").index(ui.newHeader[0]);
        }
    });
    //Bind event for previous and next buttons
    $('.prev-accordion,.nxt-accordion').click(function () {
        var index = 0;
        if ($(this).hasClass('nxt-accordion')) {
            index = gCurrentIndex + 1;
            if (index > ACCORDION_PANEL_COUNT ) {
                index = ACCORDION_PANEL_COUNT;
            }
                
            var status = $("#ui-accordion-"+accordionName+"-header-"+gCurrentIndex).find('span:last');
            var inputContainer = $("#ui-accordion-"+accordionName+"-panel-"+gCurrentIndex+ " .input-container");
            var elem = "";
            var selectElem = inputContainer.find('select').length;
            elem = inputContainer.find('input').attr('type');
            if(selectElem > 0) {
                elem = "select";
            }
            
            //for contigency table
            var scrollContainer = $("#ui-accordion-"+accordionName+"-panel-"+gCurrentIndex+ " .scroll-container");
            
            if(scrollContainer.length > 0) {
                elem = "contingency";
            }
            
            //for scale container
            var scaleContainer = $("#ui-accordion-"+accordionName+"-panel-"+gCurrentIndex+ " .scale-container");
            
            if(scaleContainer.length > 0) {
                elem = "scale";
            }
            
            console.log(elem);
                
            var condition = false;
            switch(elem){
                
                case "scale":
                    var fixed = $('#fixed').attr('checked');
                    var interval = $('#interval').attr('checked');
                    
                    if(fixed) {
                        var from = $("#from");
                        var to = $("#to");
                        if(from.val()!= "" && to.val() !="") {
                            condition = true;
                        }
                    }
                    
                    if(interval) {
                        var intervalValue = $("#fixed_value");
                        if(intervalValue.val()!= "") {
                            condition = true;
                        }
                    }
                    break;
                
                case "contingency":
                    var horizontalAns = $('input.answers-h');
                    var verticalAns = $('input.answers-v');
                    
                    if(horizontalAns.val() != "" && verticalAns.val() != "") {
                        condition = true;
                    }
                    break;
                
                case "text":
                    if(inputContainer.find('input').val() != "") {
                        condition = true;
                    }
                    break;
                case "checkbox":
                    if(inputContainer.find("input:checked").length > 0){
                        condition = true
                    }
                    break;
                case "radio":
                    if(inputContainer.find("input:checked").length > 0){
                        condition = true
                    }
                    break;
                case "select":
                    if(inputContainer.find("select").val() != ""){
                        condition = true
                    }
                    break;
            }
            console.log(condition);
            if(condition) {
                $(status).removeClass("error-flag");
                $(status).addClass("valid-flag");
            }else {
                $(status).removeClass("valid-flag");
                $(status).addClass("error-flag");
            }
           
            //create the middle part while adding the options
            if(index == 2) {
                var dataEntry = getDataEntryType();
                var type = "";
                if(dataEntry == 3) {
                    type += "<input type = 'checkbox' />"
                } else if (dataEntry == 4) {
                    type += "<select id='add00'><option value=''>option</option></select><a href='#optionlist00' class = 'dropdown-options' id='00'>+</a>"+
                    "<div class='optioncontainer' id='optionlist00' style='display:none'>"+
                    "<div class='info-options'>"+information+"</div><textarea name='options[00]' rows='5' cols='5'></textarea>"+
                    "<a href='#' id='save_00' class = 'save-option'>save</a></div>";
                }else if (dataEntry == 1) {
                    type += "<input type='text'/>"
                } else {
                    type += "<input type = 'radio' />";
                }
                $(".c-doc_container .c-dataentry").html(type);
            }
           
            //add dynamic answer upload functionality
            if(index == 3) {
                var qindex = 0; //first panel
                var questionBox = $("#ui-accordion-"+accordionName+"-panel-"+qindex + " .input-container input");
                var question = questionBox.val();
                $(".que_container .qAppenddiv label").html(question);
                var nextContainerAnswers = $("#ui-accordion-"+accordionName+"-panel-"+index+ " #added-answers");
                // only for single and multiple choice questions
                var previousContainerAnswers = $("#ui-accordion-"+accordionName+"-panel-"+gCurrentIndex+" .add_answer_parent .answers");
                var docs= "";
                
                previousContainerAnswers.each(function(index,value){
                    //console.log($(this).val());
                    if(!$(this).attr('id')) {
                        docs  += '<div class="clearfix dynamic-ans"><label class="ans-doc">'+$(this).val() +
                        '</label><div class="filetype-wrapper"><input type="file" name="answer_documents[]" class="common_doc" /></div>'+
                        '<input type="hidden" value="'+index+'" name="answer_id[]"></div>';
                    }
                    docs  += $("#del-answer-"+index + ' label').html($(this).val());
                })
                nextContainerAnswers.html(docs);
                
                
                
                //contigency doucuments section
                var nextContainerAnswersCont = $("#ui-accordion-"+accordionName+"-panel-"+index+ " #added-answersc");
                
                var contingencyMiddle = '<div class="c-doc_container">';
                var horizontalContainer = '<div class="h-cont">';
                var verticalContainer = '<div class="v-cont">';
                var iteration = false;
                
                $(".answers-v").each(function(index,value){
                    if(!$(this).attr('id')) {  
                        $(".answers-h").each(function(hindex,hvalue){
                            if(!iteration) {
                                horizontalContainer += '<div class="">'+$(this).val()+'</div>';
                            }
                            contingencyMiddle += '<div class="doc-contingency"><div class="filetype-wrapper  multipleDocumentAns"><input type="file" class="common_doc" name="answer_documents['+index+'_'+hindex+']" /></div>'+
                            '<input type="hidden" name="answer_id[]" value="'+index+'_'+hindex+'"/></div>';
                        
                        })
                        contingencyMiddle += "<div class='clearfix'></div>"
                    
                        verticalContainer += '<div>'+$(this).val()+'</div>';
                        iteration = true;
                    }
                }) 
                horizontalContainer += '</div>';
                verticalContainer += '</div>';
    
                nextContainerAnswersCont.html('<div>'+horizontalContainer+
                    '<div class="clearfix"></div><div class="v-d-container">'+verticalContainer+
                    contingencyMiddle+'</div></div>');
                
                
                
            /*var newHAns = $('.new-hans');
                var newVAns = $('.new-vans');
                var hans = "";
                var vans = "";
                $(".answers-h").each(function(index,value){
                    if(!$(this).attr('id')) {  
                        hans += '<div>'+$(this).val()+'</div>';
                    }
                })
                newHAns.html(hans);
                
                $(".answers-v").each(function(index,value){
                    if(!$(this).attr('id')) {  
                        vans += '<div>'+$(this).val()+'</div>';
                    }
                }) 
                newVAns.html(vans);*/
             
            }        
    
        }
        else {
            index = gCurrentIndex - 1;
            if (index < 0) {
                index = 0;
            }
        }
        // alert(index);
        //Call accordion method to set the active panel
        wizard.accordion("activate", index);
    });
}