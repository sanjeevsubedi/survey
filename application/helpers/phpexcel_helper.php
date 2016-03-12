<?php

/**
 * Helper to use PHPExcel
 * 
 * @author Sushil Kumar Gupta
 * @version 1.0
 * @date Oct 25, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 * 
 */

require_once(APPPATH . 'libraries/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php');

/**
 * arguments information
 * file_path => absolute file path to the excel file
 * rows true => default take all the rows
 * cols true => default take all the cols => ALPHABETS ONLY
 * skip_first => default skip the first row because it usually contains col-information
 * worksheet_num => number of worksheet to pull the data, by default it is 0
 * @return array of rows read from excel file
 */
function read_excel($file_path = '', $rows = TRUE, $cols = TRUE, $skip_first = TRUE, $worksheet_num = 0) {
    
    try { //read excel file
        $inputFileType = PHPExcel_IOFactory::identify($file_path); //identify type of the file
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); //associate proper reader according to filetype
        $objReader->setReadDataOnly(true); //set to read data only
        $objPHPExcel = $objReader->load($file_path); //load the file
    } catch(Exception $e) {
        die('Error loading file "'.pathinfo($file_path,PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    //read excel dimensions
    $sheet = $objPHPExcel->getSheet($worksheet_num); //get the work sheetn

    if($rows === TRUE)
        $rows = $sheet->getHighestRow(); //max rows

    if($cols === TRUE)
        $cols = $sheet->getHighestColumn(); //max cols

    //check whether first row needs to be skipped
    if($skip_first)
        $start = 2;
    else 
        $start = 1;

    //loop through each row of the worksheet in turn
    $result = array();
    for ($row = $start; $row <= $rows; $row++){ 
        $result[$row] = $sheet->rangeToArray('A' . $row . ':' . $cols. $row, NULL, TRUE, FALSE);
    }

    return $result;
}