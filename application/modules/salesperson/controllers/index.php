<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('salesperson/salesperson_model');
    }

    /**
     * This function displays and manages the salespersons add form
     */
    public function add() {

        if ($_POST) {
            $this->form_validation->set_rules('salesperson', 'Salesperson', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'new');
                $this->template->render();
            } else {

                $user_id = $this->session->userdata(SESS_UID);
                $company_id = $this->user_model->get_company_id_by_user_id($user_id);


                $name = $this->input->post('salesperson');
                $data = array('name' => $name, 'company_id' => $company_id, 'status' => 1);
                $this->salesperson_model->save($data);
                $this->session->set_flashdata('success', 'Sale Person added successfully');
                redirect(base_url() . 'salesperson/all');
            }
        } else {
            $user_id = $this->session->userdata(SESS_UID);
            $company_id = $this->user_model->get_company_id_by_user_id($user_id);
            $data['surveys'] = $this->survey_model->get_surveys(1, TRUE, $company_id);
            $this->template->write_view('content', 'new', $data);
            $this->template->render();
        }
    }

    function excel_import() {
        if (isset($_FILES['salesperson_excel'])) {
            $this->load->helper(array('phpexcel'));

            $config['upload_path'] = UPLOADPATH . DS . 'excel';
            /* $config['allowed_types'] = 'xls|xlsx';
              $config['max_size'] = '100000';
              $field_name = "salesperson_excel";

              $this->load->library('upload', $config);

              //if the file is not successfully uploaded.. redirect to where it came from
              if (!$this->upload->do_upload($field_name)) {
              $error = array('error' => $this->upload->display_errors());
              $this->session->set_flashdata('error', $error['error']);
              redirect(base_url() . 'salesperson/excel_import');
              } else {
              if(is_file($config['upload_path'])) {
              chmod($config['upload_path'], 777); //this will change the permission of the uploaded file
              }
              }

              //uploaded data
              $data = array('upload_data' => $this->upload->data());

              //take the uploaded file name, raw_name makes sure that we get accurate name
              //this name may or may not be same as the initial file name before uploading
              $file_name = $data['upload_data']['raw_name'];
              $file_ext = $data['upload_data']['file_ext'];

              $inputFileName = UPLOADPATH . DS . 'excel' . DS . $file_name . $file_ext;
             */

            $file_name = $_FILES['salesperson_excel']['name'];
            $file_ext = substr($file_name, strrpos($file_name, '.') + 1);
            $temp_file = $_FILES['salesperson_excel']['tmp_name'];
            $allowed_types = array('xls', 'xlsx');

            if (in_array($file_ext, $allowed_types)) {
                if (@is_uploaded_file($temp_file)) {
                    $upload_dir = $config['upload_path'] . DS;
                    //$rand_file_name = get_random_string(16) . "." . $file_ext;
                    $rand_file_name = $file_name;
                    @move_uploaded_file($temp_file, $upload_dir . $rand_file_name);
                } else {
                    $this->session->set_flashdata('error', 'File could not be uploaded');
                    redirect(base_url() . 'salesperson/excel_import');
                }
            } else {
                $this->session->set_flashdata('error', 'Please upload a valid excel file');
                redirect(base_url() . 'salesperson/excel_import');
            }
            $inputFileName = UPLOADPATH . DS . 'excel' . DS . $rand_file_name;
            //using custom phpexcel helper, based on phpexcel library
            //this will return each rows of the excel file in an array
            //all rows, column upto A, skip first row
            $salespersons = read_excel($inputFileName, TRUE, 'A');

            //to assign users to respective company
            $user_id = $this->session->userdata(SESS_UID);
            $company_id = $this->user_model->get_company_id_by_user_id($user_id);

            $number_of_salesperson_added = 1; //preset, to count how many added

            $not_saved = array(); //blank array for those rows which does not have any valid email address or is already registered

            foreach ($salespersons as $salesperson) {
                $salesperson = $salesperson[0]; //shifting the array one step up otherwise it is as below

                $data = array(
                    'name' => $salesperson[0],
                    'company_id' => $company_id,
                    'status' => '1',
                );

                $this->salesperson_model->save($data);
                $number_of_salesperson_added++;
            }

            $this->session->set_flashdata('success', $number_of_salesperson_added . ' salespersons has been imported successfully.');
            redirect(base_url() . 'salesperson/all');
        } else {
            $data = '';
            $this->template->write_view('content', 'excel_import', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays and manages the salespersons edit form
     */
    public function edit() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'salesperson/all');
        }
        $data['salesperson'] = $this->salesperson_model->get_salperson($id);
        $data['eid'] = $id;

        $salesperson_objs = $this->salesperson_model->get_salesperson_questionnaire($id);
        $survey_ids = array();
        foreach ($salesperson_objs as $salesperson_obj) {
            $survey_ids [] = $salesperson_obj->sqid;
        }
        $data['survey_salesperson'] = $survey_ids;


        if ($_POST) {
            $this->form_validation->set_rules('salesperson', 'Salesperson', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'edit', $data);
                $this->template->render();
            } else {
                //delete the past records
                $this->salesperson_model->delete_salesperson_questionnaire($id);

                //update the new entries
                $surveys = $this->input->post('surveys');

                foreach ($surveys as $survey) {
                    $this->salesperson_model->save_salesperson_questionnaire(array(
                        'salesperson_id' => $id, 'questionnaire_id' => $survey)
                    );
                }

                $name = $this->input->post('salesperson');
                $data = array('name' => $name);
                $this->salesperson_model->update($id, $data);
                $this->session->set_flashdata('success', 'Sales Person updated successfully');
                redirect(base_url() . 'salesperson/all');
            }
        } else {
            $user_id = $this->session->userdata(SESS_UID);
            $company_id = $this->user_model->get_company_id_by_user_id($user_id);
            $data['surveys'] = $this->survey_model->get_surveys(1, TRUE, $company_id);
            $this->template->write_view('content', 'edit', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays all the salespersons
     */
    public function all() {
        $data['salespersons'] = $this->salesperson_model->get_salesperson(NULL, FALSE);
        $this->template->write_view('content', 'list', $data);
        $this->template->render();
    }

    /**
     * This function deletes the salespersons
     */
    public function delete() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'salesperson/all');
        }
        $this->salesperson_model->delete($id);
        $this->session->set_flashdata('success', 'Salesperson deleted successfully');
        redirect(base_url() . 'salesperson/all');
    }

    /**
     * This function manage the salespersons
     */
    public function manage() {

        $action = $this->input->post('action');
        $selected_values = $this->input->post('child');

        if (empty($selected_values)) {
            $this->session->set_flashdata('error', 'Please select a record from the list');
            redirect(base_url() . 'salesperson/all');
        } else {
            switch ($action) {
                case 'delete':
                    foreach ($selected_values as $id) {
                        $this->salesperson_model->delete($id);
                    }
                    break;
                case 'publish':
                    foreach ($selected_values as $id) {
                        $this->salesperson_model->update($id, array('status' => 1));
                    }
                    break;
                case 'unpublish':
                    foreach ($selected_values as $id) {
                        $this->salesperson_model->update($id, array('status' => 0));
                    }
                    break;
                default:
                    break;
            }

            $this->session->set_flashdata('success', 'Sales Person updated successfully');
            redirect(base_url() . 'salesperson/all');
        }
    }

}
