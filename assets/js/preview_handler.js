( function( $ ) {
    // The jQuery.aj namespace will automatically be created if it doesn't exist
    $.widget( "preview.dataentrypreview", {
        // These options will be used as defaults
        options: {
            className : "widget-tooltip"
        },
        _create: function() {
            this.element
            // add a class for theming
            .addClass(this.options.className )
            
        },
        hover: function(url,questionType) {
            var me = this.element;
            jQuery(me).hover(function() {
                var data_entry_type = me.attr("rel");
                jQuery(".pop-container").remove();
                var getURL = url;

                jQuery.ajax({
                    url: getURL,
                    data: "data_entry_type=" + data_entry_type+'&question_type='+questionType,

                    success: function(content) {
                        jQuery(".pop-container").remove();
                        jQuery("<div class='pop-container' style='z-index:99;position:relative;'><div class='popupWrapper' style='position:absolute;bottom:0;left:0'>"+content+"</div></div>").insertBefore(me);
                    },
                    error: function(err) {
                        return false;
                    }
                });
            },function(){
                });
        
            jQuery("#close").live('click',function(){
                $(".pop-container").remove();
                return false;
            })
            jQuery(document).live('click',function(){
                $(".pop-container").remove();
            })
            
        },
        _hover: function() {
        },

        _destroy: function() {
            // Use the destroy method to reverse everything your plugin has applied
            return this._super();
        }
    });
    
    
    $.widget( "preview.questiontypepreview", {
        // These options will be used as defaults
        options: {
            className : "widget-questiontype-tooltip"
        },
        _create: function() {
            this.element
            // add a class for theming
            .addClass(this.options.className )
            
        },
        hover: function(url) {
            var me = this.element;
            var tooltipHolder = $(".questionTypeTooltip div:first-child");
            jQuery(me).hover(function() {
                var survey_id = me.parent().parent().attr("data-survey");
                var questionType = me.parent().parent().attr("id");
                var getURL = url;

                jQuery.ajax({
                    url: getURL,
                    data: "survey_id=" + survey_id+'&question_type='+questionType,

                    success: function(content) {
                        $(".questionTypeTooltip").show();
                        tooltipHolder.show();
                        tooltipHolder.html(content);
                    //tooltipHolder.show();
                    },
                    error: function(err) {
                        return false;
                    }
                });
            },function(){
                });
                
            jQuery("#close").live('click',function(){
                tooltipHolder.hide();
                return false;
            })
        
            jQuery(document).live('click',function(){
                tooltipHolder.hide();
            })
        
            tooltipHolder.live('click',function(ev){
                ev.stopPropagation();
            })
        },
        _hover: function() {
        },

        _destroy: function() {
            // Use the destroy method to reverse everything your plugin has applied
            return this._super();
        }
    });
    
    
    
    
})(jQuery);