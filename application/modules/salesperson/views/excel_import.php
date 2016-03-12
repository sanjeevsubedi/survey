<?php
$attributes = array('name' => 'new_salespersonExcel', 'id' => 'new_salespersonExcel', 'class' => 'new_salespersonExcel');
?>
<div class="formInner">
    <h3>Import salespersons from excel file</h3>
    <?php echo form_open_multipart(base_url() . 'salesperson/excel_import', $attributes); ?>  
    <fieldset>        
         <label for="salesperson_excel">Import salespersons</label>        
       <input type="file" name="salesperson_excel" >  
    </fieldset>

    <div class="nextBtn">
        <input type="submit" name="save" value="Next" />
    </div>
    <?php echo form_close(); ?>
</div>
