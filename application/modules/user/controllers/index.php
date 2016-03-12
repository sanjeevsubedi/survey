<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user/user_model');
        $this->load->model('permission/permission_model');
        $this->load->model('user/company_model');
        $this->load->library('form_validation');
        $this->load->helper('permission');
        $this->load->helper(array('form', 'url'));
    }

    /*
      This function shows the listing of the user.
     */

    public function all() {
        $role_id = $this->session->userdata(SESS_ROLE_ID);
        $user_id = $this->session->userdata('sess_cms_uid');
        $user_role = $this->user_model->get_role($role_id);

        $company_id = $this->user_model->get_company_id_by_user_id($user_id);


        if ($user_role == 'super-admin') {
            $data['user_list'] = $this->user_model->get_all_user();
        } else if ($user_role == 'company-super-admin') {
            $data['user_list'] = $this->user_model->get_all_user($company_id);
        }

        //$data['company_name'] = $this->user_model->get_company_name_by_id($data['user_list']->company_id);

        $this->template->write_view('content', 'list', $data);
        $this->template->render();
    }

    /*
      This function shows the listing of the user.
     */

    public function auth_survey_user() {
        $survey_id = $this->uri->segment(3);

        //this step verifies the valid survey and prevents url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        $survey_detail = $this->survey_model->get_survey($survey_id);
        //check the necessary permission
        permission_helper($survey_id);

        load_js('fancybox');
        load_js('quicksearch');
        load_js('tablesorter');
        load_js('sticky');


        //questions are mandatory for survey
        $this->load->model("question/question_model");
        $survey_questions = $this->question_model->get_quiestions_questionnaire($survey_id);
        if (empty($survey_questions) && !$survey_detail->scan_only) {
            $this->session->set_flashdata('error', 'At least one question is required');
            redirect(base_url() . 'survey/step_5/' . $survey_id);
        }

        //check whether user has selected a language or not
        if ($survey_detail->language_id == 0) {
            $this->session->set_flashdata('error', 'Please select at least one language');
            redirect(base_url() . 'language/survey_lang/' . $survey_id);
        }

        $role_id = $this->session->userdata(SESS_ROLE_ID);
        $user_role = $this->user_model->get_role($role_id);

        $survey_details = $this->survey_model->get_survey($survey_id);
        $company_users = $this->user_model->get_all_user($survey_details->company_id);

        //show all the users for the super admin. Not bound to any company
        if ($user_role == 'super-admin') {
            $company_users = $this->user_model->get_all_user();
        }


        $user_priveleges = array();
        $final_company_users = array();
        foreach ($company_users as $company_user) {
            $has_permission = $this->permission_model->get_survey_privelege($survey_id, $company_user->id);

            if (!empty($has_permission)) {
                $new_user = new stdClass();
                $new_user->email = $company_user->email;
                $new_user->id = $company_user->id;
                $new_user->first_name = $company_user->first_name;
                $new_user->last_name = $company_user->last_name;
                $new_user->privelege = '';
                $new_user->privelege = $has_permission[0]->permission_id;
                $user_priveleges [] = $new_user;
            }
            $final_company_users [] = $company_user;
        }

        $data['eid'] = $survey_id;
        $data['survey'] = $this->survey_model->get_survey($survey_id);
        $data['auth_users'] = $this->permission_model->get_authorized_users($survey_id);
        $data['survey_owner'] = $survey_details->user_id;
        $data['user_lists'] = $user_priveleges;
        $data['cuser_lists'] = $final_company_users;
        $data['permissions'] = $this->permission_model->get_permissions(NULL, FALSE);
        $this->template->write_view('content', 'auth_users', $data);
        $this->template->render();
    }

    /**
     * This function manage the user
     */
    public function manage() {

        $action = $this->input->post('action');
        $selected_values = $this->input->post('child');

        if (empty($selected_values)) {
            $this->session->set_flashdata('error', 'Please select a record from the list');
            redirect(base_url() . 'member/lists');
        } else {
            switch ($action) {
                case 'delete':
                    foreach ($selected_values as $id) {
                        $this->user_model->delete_user($id);
                    }
                    break;
                case 'publish':
                    foreach ($selected_values as $id) {
                        $this->user_model->update_user($id, array('status' => 1));
                    }
                    break;
                case 'unpublish':
                    foreach ($selected_values as $id) {
                        $this->user_model->update_user($id, array('status' => 0));
                    }
                    break;
                default:
                    break;
            }

            $this->session->set_flashdata('success', 'User updated successfully');
            redirect(base_url() . 'member/lists');
        }
    }

    /**
     * This function authorizes a user to download a particular survey
     */
    /* public function authorize() {
      $survey_id = $this->uri->segment(3);

      //this step verifies the valid survey and prevents url tampering
      if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
      redirect(base_url() . 'survey/all');
      }
      $action = $this->input->post('action');
      $selected_values = $this->input->post('child');

      //if (empty($selected_values)) {
      //  $this->session->set_flashdata('error', 'Please select a record from the list');
      // redirect(base_url() . 'member/auth_survey_user/' . $survey_id);
      //} else {
      switch ($action) {
      case 'add':
      //remove all the past records
      $this->user_model->delete_authorized_user($survey_id);
      foreach ($selected_values as $id) {
      $this->user_model->save_authorized_survey_user(array('questionnaire_id' => $survey_id, 'user_id' => $id));
      }
      break;
      default:
      break;
      }
      $this->session->set_flashdata('success', 'User added successfully');
      redirect(base_url() . 'member/auth_survey_user/' . $survey_id);
      //}
      } */

    /**
     * This function manage the user
     */
    public function edit() {
        $data['companys'] = $this->company_model->list_companys();
        $user_id = $this->uri->segment(3);
        $user_info = $this->user_model->get($user_id);
        $data['user'] = $user_info;
        $data['company_info'] = $this->company_model->get($user_info->company_id);

        if ($_POST) {
            $password = $this->input->post('password');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');

            if ($password) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('country', 'Country', 'trim|required|xss_clean');

            if ($this->form_validation->run() == TRUE) {
                $updated_data['first_name'] = $this->input->post('first_name');
                $updated_data['last_name'] = $this->input->post('last_name');
                $updated_data['country'] = $this->input->post('country');
                $updated_data['email'] = $this->input->post('email');
                $updated_data['status'] = $this->input->post('status');

                //update password if it's not empty. If left empty then password is not updated.
                if ($password) {
                    $updated_data['password'] = md5($password);
                }
                if ($this->user_model->is_already_registered_edit($updated_data['email'], $user_id)) {
                    $this->session->set_flashdata('error', 'User is already registered with provided email. Please try another email!!');
                    redirect('member/edit/' . $user_id);
                } else {
                    //update the user
                    $this->user_model->update($user_id, $updated_data);
                    //send email to user after account activation or deactivation
                    if ($updated_data['status'] == 1 && $user_info->status == 0) {
                        $this->load->helper('sendmail');
                        $subject = "Account Activation Information";
                        $data['first_name'] = $user_info->first_name;
                        $data['msg'] = "You have been registered successfully.<br/>Now you can login to the system.";
                        $message = $this->load->view('layout/sendmail_activation', $data, true);
                        _sendmail($user_info->email, $subject, $message, "admin", SITE_EMAIL);
                    } else if ($updated_data['status'] == 0 && $user_info->status == 1) {
                        $this->load->helper('sendmail');
                        $subject = "Account Deactivation Information";
                        $data['first_name'] = $user_info->first_name;
                        $data['msg'] = "Your account has been deactivated.";
                        $message = $this->load->view('layout/sendmail_activation', $data, true);
                        _sendmail($user_info->email, $subject, $message, "admin", SITE_EMAIL);
                    }

                    $this->session->set_flashdata('success', 'User updated successfully');
                    redirect(base_url() . 'member/all/');
                }
            } else {
                $this->template->write_view('content', 'edit', $data);
                $this->template->render();
            }
        } else {
            $this->template->write_view('content', 'edit', $data);
            $this->template->render();
        }
    }

    /**
     * This function registers the new users
     */
    public function popup_register() {
        $survey_id = $this->uri->segment(3);
        $data['email'] = $this->input->post('email');
        $this->load->helper(array('randomnumber'));
        $token = get_random_string(30);
        $user_id = $this->session->userdata(SESS_UID);
        $company_id = $this->user_model->get_company_id_by_user_id($user_id);
        $data['status'] = 0; //user gets activated when he logins for the first time
        $data['first_name'] = $this->input->post('first_name');
        $data['last_name'] = $this->input->post('last_name');
        $data['salutation'] = $this->input->post('salutation');
        $data['role_id'] = 3; // company normal user
        $data['company_id'] = $company_id;
        $data['created'] = time();
        $data['token'] = $token;
        $user_id = $this->user_model->save($data);

        //send mail unsing php mail function
        $this->load->helper('sendmail');
        $this->load->helper('user_helper');
        $data['activation_link'] = register_url($user_id);
        $subject = "User Registration";
        //creating mail body
        $message = $this->load->view('layout/sendmail', $data, true);

        if (_sendmail($data['email'], $subject, $message, "admin", SITE_EMAIL)) {
            //send mail to user
            $this->session->set_flashdata('success', 'User added successfully');
            redirect('member/auth_survey_user/' . $survey_id);
        } else {
            $this->session->set_flashdata('error', 'Email could not be delivered');
            redirect('member/auth_survey_user/' . $survey_id);
        }
    }

    /**
     * This function assigns different priveledge to a particular survey
     */
    public function assign_priveledge() {
        $survey_id = $this->uri->segment(3);
        //this step verifies the valid survey and prevents url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }
        $user_rights = $this->input->post('right');

        if (!empty($user_rights)) {
            foreach ($user_rights as $user_id => $user_right) {
                //delete past records
                $this->permission_model->delete_priveledge($survey_id, $user_id);
                $data = array('permission_id' => $user_right, 'questionnaire_id' => $survey_id,
                    'user_id' => $user_id);
                $this->permission_model->save_priveledge($data);
            }
        }
        $this->session->set_flashdata('success', 'User rights updated successfully');
        redirect(base_url() . 'survey/contacts/' . $survey_id);
    }

    /**
     * This function uses excel_reader2_helper to read the excel (xls) file uploaded 
     * and then saves all the records in users database
     * User will upload .xls file and then he will be redirected here
     * Oct 9 2013, Sushil
     */
    public function import_excel() {
        //prevent direct access to this function without file
        if (!isset($_FILES['members_excel'])) {
            die("No file, die.");
        }

        $this->load->helper(array('randomnumber', 'email', 'phpexcel', 'sendmail', 'user_helper'));

        $config['upload_path'] = UPLOADPATH . DS . 'excel';
        /* $config['allowed_types'] = 'xls|xlsx';
          $config['max_size'] = '100000';
          $field_name = "members_excel";
          $this->load->library('upload', $config);

          //if the file is not successfully uploaded.. redirect to where it came from
          if (!$this->upload->do_upload($field_name)) {
          $error = array('error' => $this->upload->display_errors());
          $this->session->set_flashdata('error', $error['error']);

          $survey_id = $this->uri->segment(3);
          redirect('member/auth_survey_user/' . $survey_id);
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

          $inputFileName = UPLOADPATH.DS.'excel'.DS.$file_name.$file_ext; */

        $file_name = $_FILES['members_excel']['name'];
        $file_ext = substr($file_name, strrpos($file_name, '.') + 1);
        $temp_file = $_FILES['members_excel']['tmp_name'];
        $allowed_types = array('xls', 'xlsx');
        $survey_id = $this->uri->segment(3);

        if (in_array($file_ext, $allowed_types)) {
            if (@is_uploaded_file($temp_file)) {
                $upload_dir = $config['upload_path'] . DS;
                //$rand_file_name = get_random_string(16) . "." . $file_ext;
                $rand_file_name = $file_name;
                @move_uploaded_file($temp_file, $upload_dir . $rand_file_name);
            } else {
                $this->session->set_flashdata('error', 'File could not be uploaded');
                redirect('member/auth_survey_user/' . $survey_id);
            }
        } else {
            $this->session->set_flashdata('error', 'Please upload a valid excel file');
            redirect('member/auth_survey_user/' . $survey_id);
        }
        $inputFileName = UPLOADPATH . DS . 'excel' . DS . $rand_file_name;

        //using custom phpexcel helper, based on phpexcel library
        //this will return each rows of the excel file in an array
        //all rows, column upto C, skip first row
        $users = read_excel($inputFileName, TRUE, 'C');

        //to assign users to respective company
        $user_id = $this->session->userdata(SESS_UID);
        $company_id = $this->user_model->get_company_id_by_user_id($user_id);

        $number_of_users_added = 1; //preset, to count how many added

        $not_saved = array(); //blank array for those rows which does not have any valid email address or is already registered

        foreach ($users as $user) {

            $user = $user[0]; //shifting the array one step up otherwise it is as below
            // Array
            // (
            //  [0] => Array
            //      (
            //          [0] => name@domain.com
            //          [1] => John
            //          [2] => Doe
            //      )
            //  )
            //invalid email, continue
            if (!valid_email($user[0])) {
                $not_saved[] = $user[0];
                continue;
            }

            //already registered user, continue
            if ($this->user_model->is_already_registered($user[0])) {
                $not_saved[] = $user[0];
                continue;
            }

            $token = get_random_string(30);

            $time = time();
            $user_row = array(
                'email' => $user[0],
                'first_name' => $user[1],
                'last_name' => $user[2],
                'role_id' => '3',
                'status' => '0',
                'company_id' => $company_id,
                'token' => $token,
                'created' => $time
            );

            $user_id = $this->user_model->save($user_row);

            //send mail unsing php mail function
            $data['activation_link'] = register_url($user_id);
            $data['first_name'] = $user[1];
            $subject = "User Registration";
            $message = $this->load->view('layout/sendmail', $data, true); //creating mail body
            _sendmail($user[0], $subject, $message, "admin", SITE_EMAIL); //send the email
            //send mail end
            //count how many users are being added
            $number_of_users_added++;
        }

        if (!empty($not_saved)) {
            $names = '';
            foreach ($not_saved as $value) {
                $names .= $value . ' could not be saved.<br />';
            }

            $this->session->set_flashdata('error', $names);
        }

        $this->session->set_flashdata('message', $number_of_users_added . " users has been imported successfully.");

        $survey_id = $this->uri->segment(3);
        redirect('member/auth_survey_user/' . $survey_id);
    }

     public function import_excel_with_password() {
        //prevent direct access to this function without file
        if (!isset($_FILES['members_excel'])) {
            die("No file, die.");
        }

        $this->load->helper(array('randomnumber', 'email', 'phpexcel', 'sendmail', 'user_helper'));

        $config['upload_path'] = UPLOADPATH . DS . 'excel';

        $file_name = $_FILES['members_excel']['name'];
        $file_ext = substr($file_name, strrpos($file_name, '.') + 1);
        $temp_file = $_FILES['members_excel']['tmp_name'];
        $allowed_types = array('xls', 'xlsx');
        $survey_id = $this->uri->segment(3);

        if (in_array($file_ext, $allowed_types)) {
            if (@is_uploaded_file($temp_file)) {
                $upload_dir = $config['upload_path'] . DS;
                $rand_file_name = get_random_string(16) . "." . $file_ext;
                //$rand_file_name = $file_name;
                @move_uploaded_file($temp_file, $upload_dir . $rand_file_name);
            } else {
                $this->session->set_flashdata('error', 'File could not be uploaded');
                redirect('member/auth_survey_user/' . $survey_id);
            }
        } else {
            $this->session->set_flashdata('error', 'Please upload a valid excel file');
            redirect('member/auth_survey_user/' . $survey_id);
        }
        $inputFileName = UPLOADPATH . DS . 'excel' . DS . $rand_file_name;
        chmod($inputFileName, 0777); 
        //using custom phpexcel helper, based on phpexcel library
        //this will return each rows of the excel file in an array
        //all rows, column upto C, skip first row
        $users = read_excel($inputFileName, TRUE, 'D');
        
        //to assign users to respective company
        $user_id = $this->session->userdata(SESS_UID);
        $company_id = $this->user_model->get_company_id_by_user_id($user_id);

        $number_of_users_added = 1; //preset, to count how many added

        $not_saved = array(); //blank array for those rows which does not have any valid email address or is already registered

        foreach ($users as $user) {
            
            $user = $user[0]; //shifting the array one step up otherwise it is as below
            // Array
            // (
            //  [0] => Array
            //      (
            //          [0] => name@domain.com
            //          [1] => John
            //          [2] => Doe
            //      )
            //  )
            //invalid email, continue
            if (!valid_email($user[0])) {
                $not_saved[] = $user[0];
                continue;
            }

            //already registered user, continue
            if ($this->user_model->is_already_registered($user[0])) {
                $not_saved[] = $user[0];
                continue;
            }

            $token = get_random_string(30);

            $time = time();
            $user_row = array(
                'email' => $user[0],
                'first_name' => $user[1],
                'last_name' => $user[2],
                'password' => md5(trim($user[3])),
                'role_id' => '3',
                'status' => '0',
                'company_id' => $company_id,
                'token' => $token,
                'created' => $time
            );

            $user_id = $this->user_model->save($user_row);

            //send mail unsing php mail function
            /*$data['activation_link'] = register_url($user_id);
            $data['first_name'] = $user[1];
            $subject = "User Registration";
            $message = $this->load->view('layout/sendmail', $data, true); //creating mail body
            _sendmail($user[0], $subject, $message, "admin", SITE_EMAIL);*/ //send the email
            //send mail end
            //count how many users are being added
            $number_of_users_added++;
        }

        if (!empty($not_saved)) {
            $names = '';
            foreach ($not_saved as $value) {
                $names .= $value . ' could not be saved.<br />';
            }

            $this->session->set_flashdata('error', $names);
        }

        $this->session->set_flashdata('message', $number_of_users_added . " users has been imported successfully.");

        $survey_id = $this->uri->segment(3);
        redirect('member/auth_survey_user/' . $survey_id);
    }

        public function del_user_list() {

          $user_id = $this->uri->segment(4);
          $survey_id = $this->uri->segment(3);
          
          $this->user_model->delete_user($user_id);
          $this->session->set_flashdata('success', "User deleted successfully");
          
          redirect('member/auth_survey_user/' . $survey_id);
        }

    
}