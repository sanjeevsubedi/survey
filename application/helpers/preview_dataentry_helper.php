<?php

/**
 * Helper to return preview for data entry type 
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date May 27, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 * 
 */
if (!function_exists('preview_data_entry_type')) {

    function preview_data_entry_type($dat_entry_type, $question_type) {
        $preview = "";
        switch ($question_type) {
            //single choice question and multi choice question
            case 1:
            case 3:
                switch ($dat_entry_type) {
                    case 1:
                        $preview = '<h2>Question 5:</h2>
	        <h3>What products do you like?</h3>
		        <div class="option-control-group clearfix">
			        <fieldset>
			            <label style="cursor: pointer;">Option One</label>
                        <input type="text" name="opt1" class="jqtranformdone jqTransformInput">
			        </fieldset>
			        <fieldset>
			            <label style="cursor: pointer;">Option Two</label>
                        <input type="text" name="opt2" class="jqtranformdone jqTransformInput">
			        </fieldset>
			        <fieldset>
			            <label style="cursor: pointer;">Option Three</label>
                        <input type="text" name="opt3" class="jqtranformdone jqTransformInput">
			        </fieldset>
		        </div><!-- control group -->
		        <a id="close" title="Close" href="#">Close</a>';
                        break;
                    case 2:
                        $preview = '<h2>Question 2:</h2>
	        <h3>What products do you like?</h3>
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
		        </div><!-- control group -->
		        <a id="close" title="Close" href="#">Close</a>';
                        break;
                    case 3 :
                        $preview = '<h2>Question 4:</h2>
	        <h3>What products do you like?</h3>
		        <div class="option-control-group clearfix">
			        <fieldset><a class="jqTransformCheckbox" href="#"></a>
                    	<input type="checkbox" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option One</label>
			        </fieldset>
			        <fieldset><a class="jqTransformCheckbox jqTransformChecked" href="#"></a>
                    	<input type="checkbox" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option Two</label>
			        </fieldset>
			        <fieldset><a class="jqTransformCheckbox jqTransformChecked" href="#"></a>
                    	<input type="checkbox" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option Three</label>
			        </fieldset>
			        <fieldset><a class="jqTransformCheckbox" href="#"></a>
                    	<input type="checkbox" name="option" class="jqTransformHidden">
			            <label style="cursor: pointer;">Option Four</label>
			        </fieldset>
		        </div><!-- control group -->
		        <a id="close" title="Close" href="#">Close</a>';

                        break;
                    case 4:
                        $preview = '<h2>Question 3:</h2>
	        <h3>What products do you like?</h3>
		        <div class="option-control-group clearfix">
                	<div class="jqTransformSelectWrapper" style="z-index: 10; width: 158px;"><div><span style="width: 116px;">Option One</span><a class="jqTransformSelectOpen" href="#"></a></div><ul style="width: 156px; display: none; visibility: visible;"><li><a index="0" href="#" class="selected">Option One</a></li><li><a index="1" href="#">Option Two</a></li><li><a index="2" href="#">Option Three</a></li><li><a index="3" href="#">Option Four</a></li></ul><select class="jqTransformHidden" style="">
                    	<option>Option One</option>
                    	<option>Option Two</option>
                    	<option>Option Three</option>
                    	<option>Option Four</option>
                    </select></div>
		        </div><!-- control group -->
		        <a id="close" title="Close" href="#">Close</a>';
                        break;
                }
                break;
            //scale question type
            case 4:
                switch ($dat_entry_type) {
                    case 1:
                        $preview = '<div class="singleCollection"><h2>Question 1:</h2>
	        <h3>What products do you like?</h3>
		        <div class="option-control-group">
                <table id="ansOption">
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
                  	<td><strong>Information 3</strong></td>
                    <td><input type="text" name="txt1" class="jqtranformdone jqTransformInput"></td>
                    <td><input type="text" name="txt2" class="jqtranformdone jqTransformInput"></td>
                  </tr>
                </tbody></table>
	         </div><!-- control group -->
	        <a id="close" title="Close" href="#">Close</a></div>';
                        break;
                    case 2:
                        $preview = '<div class="singleCollection"><h2>Question 2:</h2>
	        <h3>What products do you like?</h3>
		        <div class="option-control-group">
                <table id="ansOption">
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
                  	<td><strong>Information 3</strong></td>
                    <td><a rel="option_1" class="jqTransformRadio" href="#"></a><input type="radio" name="option_1" class="jqTransformHidden"></td>
                    <td><a rel="option_2" class="jqTransformRadio jqTransformChecked" href="#"></a><input type="radio" name="option_2" class="jqTransformHidden"></td>
                  </tr>
                </tbody></table>
	         </div><!-- control group -->
	        <a id="close" title="Close" href="#">Close</a></div>';
                        break;
                    case 3 :
                        $preview = '<div class="singleCollection"><h2>Question 2:</h2>
	        <h3>What products do you like?</h3>
		        <div class="option-control-group">
                <table id="ansOption">
                	<tbody><tr>
                    	<th>&nbsp;</th>
                    	<th><strong>Information 1</strong></th>
                    	<th><strong>Information 2</strong></th>
                    </tr>
                  <tr>
                  	<td><strong>Information 3</strong></td>
                    <td><a class="jqTransformCheckbox" href="#"></a><input type="checkbox" name="chkBox" class="jqTransformHidden"></td>
                    <td><a class="jqTransformCheckbox" href="#"></a><input type="checkbox" name="chkBox" class="jqTransformHidden"></td>
                  </tr>
                  <tr>
                  	<td><strong>Information 3</strong></td>
                    <td><a class="jqTransformCheckbox" href="#"></a><input type="checkbox" name="chkBox" class="jqTransformHidden"></td>
                    <td><a class="jqTransformCheckbox" href="#"></a><input type="checkbox" name="chkBox" class="jqTransformHidden"></td>
                  </tr>
                </tbody></table>
	         </div><!-- control group -->
	        <a id="close" title="Close" href="#">Close</a></div>';

                        break;
                    case 4:
                        $preview = '<div class="singleCollection jqtransformdone">
	    	<h2>Question 1:</h2>
	        <h3>What products do you like?</h3>
		        <div class="option-control-group">
                <table id="ansOption">
                	<tbody><tr>
                    	<th>&nbsp;</th>
                    	<th><strong>Information 1</strong></th>
                    	<th><strong>Information 2</strong></th>
                    </tr>
                  <tr>
                  	<td><strong>Information 3</strong></td>
                    <td>
                    <div class="jqTransformSelectWrapper" style="z-index: 10; width: 142px;"><div><span style="width: 100px;">Answer 1</span><a class="jqTransformSelectOpen" href="#"></a></div><ul style="width: 140px; display: none; visibility: visible;"><li><a index="0" href="#" class="selected">Answer 1</a></li><li><a index="1" href="#">Answer opt</a></li><li><a index="2" href="#">Answer opt</a></li></ul><select class="jqTransformHidden" style="">
                      <option>Answer 1</option>
                      <option>Answer opt</option>
                      <option>Answer opt</option>
                    </select></div>
                    </td>
                    <td>
                    <div class="jqTransformSelectWrapper" style="z-index: 9; width: 142px;"><div><span style="width: 100px;">Answer 2</span><a class="jqTransformSelectOpen" href="#"></a></div><ul style="width: 140px; display: none; visibility: visible;"><li><a index="0" href="#" class="selected">Answer 2</a></li><li><a index="1" href="#">Answer opt</a></li><li><a index="2" href="#">Answer opt</a></li></ul><select class="jqTransformHidden" style="">
                      <option>Answer 2</option>
                      <option>Answer opt</option>
                      <option>Answer opt</option>
                    </select></div>
                    </td>
                  </tr>
                  <tr>
                  	<td><strong>Information 4</strong></td>
                    <td>
                    <div class="jqTransformSelectWrapper" style="z-index: 8; width: 142px;"><div><span style="width: 100px;">Answer 3</span><a class="jqTransformSelectOpen" href="#"></a></div><ul style="width: 140px; display: none; visibility: visible;"><li><a index="0" href="#" class="selected">Answer 3</a></li><li><a index="1" href="#">Answer opt</a></li><li><a index="2" href="#">Answer opt</a></li></ul><select class="jqTransformHidden" style="">
                      <option>Answer 3</option>
                      <option>Answer opt</option>
                      <option>Answer opt</option>
                    </select></div>
                    </td>
                    <td>
                    <div class="jqTransformSelectWrapper" style="z-index: 7; width: 142px;"><div><span style="width: 100px;">Answer 4</span><a class="jqTransformSelectOpen" href="#"></a></div><ul style="width: 140px; display: none; visibility: visible;"><li><a index="0" href="#" class="selected">Answer 4</a></li><li><a index="1" href="#">Answer opt</a></li><li><a index="2" href="#">Answer opt</a></li></ul><select class="jqTransformHidden" style="">
                      <option>Answer 4</option>
                      <option>Answer opt</option>
                      <option>Answer opt</option>
                    </select></div>
                    </td>
                  </tr>
                </tbody></table>
	         </div><!-- control group -->
	        <a id="close" title="Close" href="#">Close</a>
           </div>';
                        break;
                }

                break;
        }

        return $preview;
    }

}
?>
