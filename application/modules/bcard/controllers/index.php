<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('bcard/bcard_model');
        $this->load->model('contact/contact_model');
        $this->load->model('survey/survey_model');
        $this->load->model('surveyreport/survey_response_model');
        $this->load->helper('xml');
    }

    /**
     * This function processes all the xml and updates the database 
     */
    public function recursive_copy($src, $dst, $replace = TRUE) {
        //$str = "";
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    $this->recursive_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    if (!$replace) {
                        if (!file_exists($dst . '/' . $file)) {
                            //$str .= "1";
                            copy($src . '/' . $file, $dst . '/' . $file);
                        }
                    } else {
                        copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
        }
        //echo $str;
        closedir($dir);
    }

    function copyToDir($pattern, $dir) {
        foreach (glob($pattern) as $file) {
            if (!is_dir($file) && is_readable($file)) {
                $dest = realpath($dir . DIRECTORY_SEPARATOR) . DS . basename($file);
                
                if(!file_exists($dest)) {
                  copy($file, $dest);
                }
            }
        }
    }

    /**
     * This function processes all the xml and updates the database 
     * Skips those which has been already processed
     * If $this->uri->segment(3) is provided, then it only processes those xmls which has that survey id
     */
    public function process_all() {
        $this->load->library('encrypt');
        //$this->copyToDir(ROOTPATH . DS . 'tmp' . DS . '*.txt', ROOTPATH . DS . 'uploads' . DS . 'bcards_xml/');
        $this->copyToDir('C:\etrservice\output\SingleBusinessCard' . DS . '*.xml', ROOTPATH . DS . 'uploads' . DS . 'bcards_xml/');
      
        //Export-folder is C:\etrservice\output C:\inetpub\wwwroot\meplan\uploads\bcards_xml
        //$this->recursive_copy(ROOTPATH . DS . "tmp", "/var/www/meplan/uploads/bcards_xml", FALSE);
        /*
         * ﻿<?xml version="1.0" encoding="utf-8"?>
          <registration id="62C89887A1">
          <exhibition>41</exhibition>
          <aitem suid="ext.meplan.a.ExpoRMServer.2014072206-33-49-4">
          <fields>
          <city>Chatillon cedex zip place</city>
          <company>Siemens SAS</company>
          <company2>Firm2 field</company2>
          <country>NP</country>
          <email>herve.deIacotte@siemens.com</email>
          <first_name>Hervé</first_name>
          <gender>1</gender>
          <mobile_extension>9841652848</mobile_extension>
          <modifier_system>visit:meplan</modifier_system>
          <name>Lacotte</name>
          <phone>01 49 75 20 65</phone>
          <street>150 avenue de la Republique</street>
          <street2>Address 2</street2>
          <position>Directeur de la Communication</position>
          <title>Title field</title>
          <zip_street>92323</zip_street>
          </fields>
          <categories />
          <freefields>
          <ff label="privacy_provisions">1</ff>
          </freefields>
          </aitem>
          <notices>
          <notice>sonstiges field</notice>
          </notices>
          </registration>
         * 
         */
        /*
          mobile 		mobile_extension
          phone_1		phone
          address		street
          address_2	street2
          zip		zip_street

          gender ???
         */

        //get all the files from the directory
        $path = UPLOADPATH . DS . 'bcards_xml';
        $files = scandir($path);

        //if specific survey is provided in the url
        $url_survey_id = $this->uri->segment(3);
        if ($url_survey_id && !$this->survey_model->is_valid_survey_reqest($url_survey_id)) {
            redirect(base_url() . '/');
        }

        //get list of already processed xml files from the stats table
        $processed_xmls = $this->bcard_model->get_processed_xmls();

        foreach ($files as $k => $file) {

            //only look for xml files
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if ($ext != 'xml')
                continue;

            //if this file is already in the processed list, don't do it again
            $is_processed = FALSE;

            foreach ($processed_xmls as $xml_file) {
                if ($xml_file->name == $file) {
                    $is_processed = TRUE;
                    break;
                }
            }

            if ($is_processed) {
                //echo $file . ' ALREADY PROCESSED<br />';
                continue;
            }

            if ($file == "." || $file == "..") {
                continue;
            }

            $file_path = $path . DS . $file;

            $xml = xml_parser($file_path);

            $survey_id = (string) $xml->exhibition;

            //if specific survey is specified, and 
            //this is not the specific survey as wanted then skip it
            if (!empty($url_survey_id) && ($url_survey_id != $survey_id))
                continue;

            //attribute of registration node
            $survey_user_id = (string) $xml->attributes()->id;

            //rest of the fields obtained from the xml file
            $fields = $xml->aitem->fields;

            //get survey details from the database
            $survey_details = $this->survey_model->get_survey($survey_id);
            $language_id = $survey_details->language_id;

            //get all the associated contact fields of that survey id from the database
            $contact_fields_in_survey = $this->contact_model->get_contact_field_questionnaire_clone($survey_id);

            foreach ($fields->children() as $node => $value) {
                $node = (string) $node;
                $value = (string) $value;

                switch ($node) {
                    case "mobile_extension":
                        $node = "mobile";
                        break;
                    case "phone":
                        $node = "phone_1";
                        break;
                    case "street":
                        $node = "address";
                        break;
                    case "street2":
                        $node = "address_2";
                        break;
                    case "zip_street":
                        $node = "zip";
                        break;
                }


                //if the node is either zip or street, then merge it and make it address node
                /*  if ($node == 'zip_street' || $node == 'street') {
                  if (!isset($address)) {
                  //if address is not set, then first node is found
                  $address = $value . ', ';
                  } else {
                  //second time another node is also found, the address is already set
                  //just append value of second node to address
                  $address .= $value;
                  $node = 'address'; //assume this node is address
                  $value = $address; //and its value is the combination
                  }
                  } */

                //now get the fields_id of the nodes that is present in the xml
                $original_field_id = $this->contact_model->get_original_field_id($node);

                //if the xml node is not in our required field list, we will get empty array
                //hence ignore this node as not required and cant be saved
                if (!is_object($original_field_id)) {
                    continue;
                }

                //get their translated version depeneding on the language
                $translated_field_id = $this->contact_model->get_translated_field_id($original_field_id->id, $language_id);

                //if the field has no translation in the desired language
                //empty array will be returned hence it should be ignored
                if (!is_object($translated_field_id)) {
                    continue;
                }

                //assuming the field does not exists in required fields list
                $field_exists = FALSE;

                foreach ($contact_fields_in_survey as $contact_field) {
                    if ($contact_field->contact_form_field_id == $translated_field_id->id) {
                        $field_exists = TRUE;
                        break;
                    }
                }

                //check if this translated version id is present in the required fields, if not, then leave it
                if (!$field_exists)
                    continue;

                $contact_form_field_id = $translated_field_id->id;
                $val = $this->encrypt->encode($value);
                $insert_data = array(
                    'contact_form_field_id' => $contact_form_field_id,
                    'questionnaire_id' => $survey_id,
                    'survey_user_id' => $survey_user_id,
                    'value' => $val
                );
                //$strs = $this->encrypt->encode($value);
                //var_dump($this->encrypt->decode($strs));
                //die;
                $this->bcard_model->save_xml_data($insert_data, 'survey_contact_detail');
            }

            //also update the stats table
            $stats['name'] = $file;
            $stats['survey_id'] = $survey_id;
            $stats['survey_user_id'] = $survey_user_id;
            $this->bcard_model->update_xml_stats($stats);
            //echo $file . ' NOW PROCESSED<br />';
        }
        $this->session->set_flashdata('success', 'Business cards have been processed successfully');
        redirect(base_url() . 'surveyreport/' . $url_survey_id);
    }

}
