<?php

/**
 * Helper to return preview for sample question type 
 * 
 * @author Bidur B.K
 * @version 1.0
 * @date May 21, 2013
 * @copyright Copyright (c) 2012, neolinx.com.np
 * 
 */
if (!function_exists('preview_question_type')) {

    function preview_question_type($survey_id, $question_type) {
        $preview = "";
        switch ($question_type) {
            case 1:
                $preview = '<div id="popup-wrapper" class="jqtransformdone">
	<h2>PREWVIEW SINGLE CHOICE</h2>
    <div class="popup-grid-wrapper">
    	<div class="popup-grid-inner last">
	    	<h3>Data Entry RADIO BUTTON:</h3>
	        <h4>What products do you like?</h4>
		        <div class="option-control-group">
			        <fieldset><a rel="option" class="jqTransformRadio jqTransformChecked" href="#"></a>
			        	<input type="radio" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option One</label>
			        </fieldset>
			        <fieldset><a rel="option" class="jqTransformRadio" href="#"></a>
			        	<input type="radio" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option Two</label>
			        </fieldset>
			        <fieldset><a rel="option" class="jqTransformRadio" href="#"></a>
			        	<input type="radio" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option Three</label>
			        </fieldset>
			        <fieldset><a rel="option" class="jqTransformRadio" href="#"></a>
			        	<input type="radio" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option Four</label>
			        </fieldset>
					    <div class="backBtn">
                                <a href="' . base_url() . 'survey/step_5/' . $survey_id . '">Back to Overview</a>
                                </div>
                                <div class="nextBtn">
                                    <a href="' . base_url() . 'question/add/' . $survey_id . '/' . $question_type . '">Next</a>
                                </div>
	         </div><!-- control group -->
           </div><!-- popup grid ends here -->
    	
        <a id="close" title="Close" href="#">Close</a>
        
    </div><!-- popup grid wrapper ends here -->
</div>';
                break;
            case 2:
                $preview = '<div id="popup-wrapper" class="jqtransformdone">
	<h2>PREWVIEW SCALE</h2>
    <div class="popup-grid-wrapper">
    	<div class="popup-grid-inner range-box">
	    	<h3>Data Entry FLEXIBLE INTERVALS:</h3>
	        <h4>What products do you like?</h4>
		        <div class="option-control-group">
                        <div class="sliderRange">
	                	<div class="rangeBlock">
	                    	<div class="range">0</div>
	                        <div class="range">100</div>
	                    </div>
	                </div>
					    <div class="backBtn">
                                <a href="' . base_url() . 'survey/step_5/' . $survey_id . '">Back to Overview</a>
                                </div>
                                <div class="nextBtn">
                                    <a href="' . base_url() . 'question/add/' . $survey_id . '/' . $question_type . '">Next</a>
                                </div>
	         </div><!-- control group -->
           </div><!-- popup grid ends here -->
           <div class="popup-grid-inner range-box last">
	    	<h3>Data Entry FIXED INTERVALS:</h3>
	        <h4>What products do you like?</h4>
		        <div class="option-control-group">
			<div class="sliderRange">
                    	<div class="intervalBlock">
	                        <div class="interval">1</div>
	                        <div class="interval">5</div>
	                        <div class="interval">10</div>
	                        <div class="interval">15</div>
	                        <div class="interval">20</div>
	                        </div>
                        </div>

					    <div class="backBtn">
                                <a href="' . base_url() . 'survey/step_5/' . $survey_id . '">Back to Overview</a>
                                </div>
                                <div class="nextBtn">
                                    <a href="' . base_url() . 'question/add/' . $survey_id . '/' . $question_type . '">Next</a>
                                </div>
	         </div><!-- control group -->
           </div><!-- popup grid ends here -->
    	
        <a id="close" title="Close" href="#">Close</a>
        
    </div><!-- popup grid wrapper ends here -->
</div>';
                break;
            case 3:
                $preview = '<div id="popup-wrapper" class="jqtransformdone">
	<h2>PREWVIEW MULTICHOICE</h2>
    <div class="popup-grid-wrapper">
    	<div class="popup-grid-inner">
	    	<h3>Data Entry CHECKBOX:</h3>
	        <h4>What products do you like?</h4>
		        <div class="option-control-group">
			        <fieldset><a class="jqTransformCheckbox" href="#"></a>
                    	<input type="checkbox" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option One</label>
			        </fieldset>
			        <fieldset><a class="jqTransformCheckbox jqTransformChecked" href="#"></a>
                    	<input type="checkbox" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option Two</label>
			        </fieldset>
			        <fieldset><a class="jqTransformCheckbox" href="#"></a>
                    	<input type="checkbox" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option Three</label>
			        </fieldset>
			        <fieldset><a class="jqTransformCheckbox jqTransformChecked" href="#"></a>
                    	<input type="checkbox" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option Four</label>
			        </fieldset>
					    <div class="backBtn">
                                <a href="' . base_url() . 'survey/step_5/' . $survey_id . '">Back to Overview</a>
                                </div>
                                <div class="nextBtn">
                                    <a href="' . base_url() . 'question/add/' . $survey_id . '/' . $question_type . '">Next</a>
                                </div>
	         </div><!-- control group -->
           </div><!-- popup grid ends here -->
           <div class="popup-grid-inner last">
	    	<h3>Data Entry TEXTBOX:</h3>
	        <h4>What products do you like?</h4>
		        <div class="option-control-group">
					        <fieldset>
			            <label style="cursor: pointer;">Option One</label>
                        <input type="text" name="opt1" class="jqtranformdone jqTransformInput">
			        </fieldset>
			        <fieldset>
			            <label style="cursor: pointer;">Option Two</label>
                        <input type="text" name="opt2" class="jqtranformdone jqTransformInput">
			        </fieldset>
			        <fieldset>
			            <label style="cursor: pointer;">Option four</label>
                        <input type="text" name="opt3" class="jqtranformdone jqTransformInput">
			        </fieldset>

					    <div class="backBtn">
                                <a href="' . base_url() . 'survey/step_5/' . $survey_id . '">Back to Overview</a>
                                </div>
                                <div class="nextBtn">
                                    <a href="' . base_url() . 'question/add/' . $survey_id . '/' . $question_type . '">Next</a>
                                </div>
	         </div><!-- control group -->
           </div><!-- popup grid ends here -->
    	
        <a id="close" title="Close" href="#">Close</a>
        
    </div><!-- popup grid wrapper ends here -->
</div>';
                break;
            case 6:
                $preview = '<div id="popup-wrapper" class="jqtransformdone">
	<h2>PREWVIEW TEXT ANSWER</h2>
    <div class="popup-grid-wrapper">
    	<div class="popup-grid-inner data-entry last">
	    	<h3>Data Entry RADIO BUTTON:</h3>
	        <h4>What products do you like?</h4>
		        <div class="option-control-group clearfix">
			      <input type="text" name="txt3" class="jqtranformdone jqTransformInput">
					    <div class="backBtn">
                                <a href="' . base_url() . 'survey/step_5/' . $survey_id . '">Back to Overview</a>
                                </div>
                                <div class="nextBtn">
                                    <a href="' . base_url() . 'question/add/' . $survey_id . '/' . $question_type . '">Next</a>
                                </div>
	         </div><!-- control group -->
           </div><!-- popup grid ends here -->
    	
        <a id="close" title="Close" href="#">Close</a>
        
    </div><!-- popup grid wrapper ends here -->
</div>';
                break;
            case 8:
            case 9:
                break;
            case 4:
                $preview = '<div id="popup-wrapper" class="jqtransformdone">
                <h2>PREWVIEW CONTINGENCY</h2>
    <div class="popup-grid-wrapper">
    	<div class="popup-grid-inner">
	    	<h3>Data Entry TEXT BOX:</h3>
	        <h4>What products do you like?</h4>
		        <div class="option-control-group">
                <table>
                	<tbody class=""><tr>
                    	<th>&nbsp;</th>
                    	<th><strong>Information 1</strong></th>
                    	<th><strong>Information 2</strong></th>
                    </tr>
                  <tr>
                  	<td><strong>Information 3</strong></td>
                    <td><input type="text" name="txt1" class="jqtranformdone jqTransformInput"></td>
                    <td><input type="text" name="txt2" class="jqtranformdone jqTransformInput"></td>
                  </tr>
                  <tr>
                  	<td><strong>Information 4</strong></td>
                    <td><input type="text" name="txt3" class="jqtranformdone jqTransformInput"></td>
                    <td><input type="text" name="txt4" class="jqtranformdone jqTransformInput"></td>
                  </tr>
                </tbody></table>
		 <div class="backBtn">
                                <a href="' . base_url() . 'survey/step_5/' . $survey_id . '">Back to Overview</a>
                                </div>
                                <div class="nextBtn">
                                    <a href="' . base_url() . 'question/add/' . $survey_id . '/' . $question_type . '">Next</a>
                </div>
	         </div><!-- control group -->
           </div><!-- popup grid ends here -->
    	<div class="popup-grid-inner">
	    	<h3>Data Entry RADIO BUTTON:</h3>
	        <h4>What products do you like?</h4>
		        <div class="option-control-group">
                <table>
                	<tbody><tr>
                    	<th>&nbsp;</th>
                    	<th><strong>Information 1</strong></th>
                    	<th><strong>Information 2</strong></th>
                    </tr>
                  <tr>
                  	<td><strong>Information 3</strong></td>
                    <td><a rel="option_1" class="jqTransformRadio jqTransformChecked" href="#"></a><input type="radio" name="option_1" class="jqTransformHidden"></td>
                    <td><a rel="option_2" class="jqTransformRadio" href="#"></a><input type="radio" name="option_2" class="jqTransformHidden"></td>
                  </tr>
                  <tr>
                  	<td><strong>Information 4</strong></td>
                    <td><a rel="option_1" class="jqTransformRadio" href="#"></a><input type="radio" name="option_1" class="jqTransformHidden"></td>
                    <td><a rel="option_2" class="jqTransformRadio jqTransformChecked" href="#"></a><input type="radio" name="option_2" class="jqTransformHidden"></td>
                  </tr>
                </tbody></table>
		 <div class="backBtn">
                                <a href="' . base_url() . 'survey/step_5/' . $survey_id . '">Back to Overview</a>
                                </div>
                                <div class="nextBtn">
                     <a href="' . base_url() . 'question/add/' . $survey_id . '/' . $question_type . '">Next</a>
                </div>
	         </div><!-- control group -->
           </div><!-- popup grid ends here -->
    	<div class="popup-grid-inner">
	    	<h3>Data Entry CHECK BOX:</h3>
	        <h4>What products do you like?</h4>
		        <div class="option-control-group">
                <table>
                	<tbody><tr>
                    	<th>&nbsp;</th>
                    	<th><strong>Information 1</strong></th>
                    	<th><strong>Information 2</strong></th>
                    </tr>
                  <tr>
                  	<td><strong>Information 3</strong></td>
                    <td><a class="jqTransformCheckbox jqTransformChecked" href="#"></a><input type="checkbox" name="chkBox" class="jqTransformHidden"></td>
                    <td><a class="jqTransformCheckbox" href="#"></a><input type="checkbox" name="chkBox" class="jqTransformHidden"></td>
                  </tr>
                  <tr>
                  	<td><strong>Information 4</strong></td>
                    <td><a class="jqTransformCheckbox" href="#"></a><input type="checkbox" name="chkBox" class="jqTransformHidden"></td>
                    <td><a class="jqTransformCheckbox jqTransformChecked" href="#"></a><input type="checkbox" name="chkBox" class="jqTransformHidden"></td>
                  </tr>
                </tbody></table>
			 <div class="backBtn">
                                <a href="' . base_url() . 'survey/step_5/' . $survey_id . '">Back to Overview</a>
                                </div>
                                <div class="nextBtn">
                                    <a href="' . base_url() . 'question/add/' . $survey_id . '/' . $question_type . '">Next</a>
                </div>
	         </div><!-- control group -->
           </div><!-- popup grid ends here -->
        <a id="close" title="Close" href="#">Close</a>
    </div><!-- popup grid wrapper ends here -->
</div>';
                break;
            case 7:
                $preview = '<div id="popup-wrapper" class="jqtransformdone">
	<h2>PREWVIEW PULL DOWN</h2>
    <div class="popup-grid-wrapper" style="height:auto;min-height:auto">
    	<div class="popup-grid-inner last">
	    	<h3>Data Entry DROP DOWN:</h3>
	        <h4>What products do you like?</h4>
		       <div class="option-control-group">
                	<div class="jqTransformSelectWrapper" style="z-index: 10; width: 270px;"><div><span style="width: 228px;">Option One</span><a class="jqTransformSelectOpen" href="#"></a></div><ul style="width: 268px; display: none; visibility: visible;"><li><a index="0" href="#" class="selected">Option One</a></li><li><a index="1" href="#">Option Two</a></li><li><a index="2" href="#">Option Three</a></li><li><a index="3" href="#">Option Four</a></li></ul><select class="jqTransformHidden" style="">
                    	<option>Option One</option>
                    	<option>Option Two</option>
                    	<option>Option Three</option>
                    	<option>Option Four</option>
                    </select></div>
		 <div class="backBtn">
                                <a href="' . base_url() . 'survey/step_5/' . $survey_id . '">Back to Overview</a>
                                </div>
                                <div class="nextBtn">
                                    <a href="' . base_url() . 'question/add/' . $survey_id . '/' . $question_type . '">Next</a>
                </div>					 
   	           </div><!-- control group -->
     	</div><!-- popup grid ends here -->
        <a id="close" title="Close" href="#">Close</a>
        
    </div><!-- popup grid wrapper ends here -->
</div>';
                break;
        }
        return $preview;
    }

}
?>
