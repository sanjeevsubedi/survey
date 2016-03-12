<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user/user_model');
        $this->load->model('survey/survey_model');
        $this->load->model('contact/contact_model');
        $this->load->model('device/device_model');
        $this->load->model('question/question_model');
        $this->load->model('permission/permission_model');
        $this->load->model('file/file_model');
        $this->load->model('answer/answer_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->template->set_template('login');
    }

    public function index() {
        redirect('user/auth/login');
    }

    /**
     * This function displays the login form
     */
    public function login() {
        if ($_POST) {
            $this->check_user();
        } else {
            if (isset($_COOKIE["mp_rm"]) && !empty($_COOKIE["mp_rm"])) {
                $cookie_val = $_COOKIE["mp_rm"];
                $cookie_detail = explode('-', $cookie_val);
                $user_details = $this->user_model->get_user_token($cookie_detail[0]);
                if (is_object($user_details) && $user_details->id == $cookie_detail[1]) {
                    $user_obj = $this->user_model->get_user($user_details->id);
                    $this->user_model->set_session_variables($user_obj);
                    redirect(base_url() . 'survey/all');
                }
            }

            //check if the user is not authenticated        
            if ($this->user_model->check_session() == TRUE) {
                redirect(base_url() . 'survey/all');
            }

            $data['remember'] = isset($_COOKIE["mp_rem"]) && !empty($_COOKIE["mp_rem"]) ? TRUE : FALSE;
            $data['error'] = $this->session->flashdata('error');
            $this->session->unset_userdata('SESS_UNAME');
            $this->session->unset_userdata('SESS_UID');
            $this->session->unset_userdata('SESS_ROLE_ID');
            $this->session->sess_destroy();
            $this->template->write_view('content', 'login', $data);
            $this->template->render();
        }
    }

    /**
     * This function logout the user from the app
     */
    function logout() {
        $this->session->sess_destroy();
        $expire = time() - 60 * 60 * 24 * 30 * 6; // sets the past date for expiry
        if (isset($_COOKIE['mp_rm'])) {
            setcookie("mp_rm", "", $expire, "/");
        }
        redirect(base_url() . 'user/auth/login', 'refresh');
    }

    /**
     * This function checks for the valid user
     */
    function check_user() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_name', 'User Name', 'required');
        $this->form_validation->set_rules('user_password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->template->write_view('content', 'login');
            $this->template->render();
            //$this->login();
        } else {
            $usr_email = $this->input->post('user_name');
            $usr_password = $this->input->post('user_password');

            $validation = $this->user_model->validate_user($usr_email, $usr_password);
            if ($validation === TRUE) {
                $this->load->helper(array('randomnumber'));
                $remember = $this->input->post('signedIn');
                //set the cookie
                $expire = time() + 60 * 60 * 24 * 30 * 6; // expires in six month
                $token = get_random_string(30);
                if ($remember) {
                    setcookie("mp_rm", $token . '-' . $this->session->userdata(SESS_UID), $expire, "/");
                    setcookie("mp_rem", $token . '-' . $this->session->userdata(SESS_UID), $expire, "/");
                } else {
                    setcookie("mp_rem", "", $expire, "/");
                }

                $this->user_model->update($this->session->userdata(SESS_UID), array('access' => time(), 'token' => $token));
                redirect(base_url() . 'survey/all');
            } else {
                $this->session->set_flashdata('error', 'Sorry, either User name or Password did not match.');
                redirect(base_url() . 'user/auth/login');
            }
        }
    }

    /**
     * This function registers the new users
     */
    public function register() {
        $token = $this->uri->segment(4);
        //this step verifies the valid token and prevents from url tampering
        if ($token && !$this->user_model->is_valid_token($token)) {
            redirect(base_url() . 'user/auth/register');
        }

        $this->template->set_template('public');
        $this->load->model('company_model');

        $data = array();
        $data['token_id'] = "";
        if ($token) {
            $token_details = $this->user_model->get_user_token($token);
            $data['token_user'] = !empty($token_details) ? $token_details : "";
            $data['token_id'] = $token_details->token;
        } else {
            $this->form_validation->set_rules('salutation', 'Salutation', 'trim|required|xss_clean');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('company', 'Company', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        }

        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('repassword', 'Repeat password', 'trim||matches[password]|required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            unset($data['token_user']);
            unset($data['token_id']);
            $password = $this->input->post('password');

            //registered via url
            if ($this->input->post('start')) {
                $data['status'] = '1';
                $data['access'] = time();
                $data['password'] = md5($password);
                $this->user_model->update($token_details->id, $data);
                //set the sessin values
                $this->user_model->set_session_variables($token_details);
                //create a dummy questionnaire for each user
                //$this->survey_model->clone_whole_survey(11, $token_details->id);
                redirect(base_url() . 'survey/all');
            } else {
                $data['first_name'] = $this->input->post('first_name');
                $data['last_name'] = $this->input->post('last_name');
                $data['email'] = $this->input->post('email');
                $data['salutation'] = $this->input->post('salutation');
                $company = $this->input->post('company');
                $data['password'] = md5($password);

                if ($this->user_model->is_already_registered($data['email'])) {
                    $this->session->set_flashdata('error', 'User is already registered with provided email. Please try another email!!');
                    redirect('user/auth/register');
                } else if ($this->company_model->is_company_registered($company)) {
                    $this->session->set_flashdata('error', 'Company is already registered. Please try another company!!');
                    redirect('user/auth/register');
                } else {
                    //save company
                    $company_data = array('name' => $company, 'address_1' => '', 'address_2' => '', 'telephone' => '', 'status' => '1');
                    $company_id = $this->company_model->save($company_data);

                    //set company-super-admin role and save user
                    $role_id = '2';
                    $data['role_id'] = $role_id;
                    $data['company_id'] = $company_id;
                    $data['status'] = '1';
                    $data['created'] = time();
                    $data['access'] = time();
                    $user_id = $this->user_model->save($data);
                    //set the sessin values
                    $user_obj = $this->user_model->get_user($user_id);
                    $this->user_model->set_session_variables($user_obj);
                    //create a dummy questionnaire for each company user
                    $this->survey_model->clone_whole_survey(1, $user_id);
                    $this->session->set_flashdata('success', 'User Registered Sucessfully !!');
                    redirect(base_url() . 'survey/all');
                }
            }
        }
        $this->template->write_view('content', 'register', $data);
        $this->template->render();
    }

    /**
     * This function request for new password
     */public function forget_password() {
        $this->template->set_template('public');
        $data = array();
        $change_password = FALSE;
        $email_count = 0;
        if ($this->input->post('email')) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');

            if ($this->form_validation->run() == TRUE) {
                $input_email = $this->input->post('email');
                //checking either it is in system or not

                $email_count = $this->user_model->check_user_email($input_email);
                if (count($email_count) > 0) {
                    $data['first_name'] = $email_count->first_name;
                    $change_password = TRUE;
                }
                if ($change_password === TRUE) {
                    $this->load->helper('randomnumber');
                    $this->load->helper('sendmail');
                    /* Generating random Password */
                    $rand_password = get_random_string(6);
                    $data['password'] = $rand_password;
                    $data['email'] = $input_email;
                    $pass_data = array(
                        'password' => md5($rand_password)
                    );

                    $result = $this->user_model->update_password($pass_data, $input_email);

                    if ($result) {
                        /* Prepareation for sending mail. */
                        $subject = "Forget password";
                        //creating mail body
                        $message = $this->load->view('layout/sendmail_forget_password', $data, true);

                        if (_sendmail($input_email, $subject, $message, "admin", SITE_EMAIL)) {
                            //send mail to admin

                            $this->session->set_flashdata('success', 'Password is sent Sucessfully !!');
                            redirect('user/auth/forget_password');
                        } else {
                            $this->session->set_flashdata('error', 'Problem in sending new password. Please try again later !!');
                            redirect('user/auth/forget_password');
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Problem in updating new password. Please try again later !!');
                        redirect('user/auth/forget_password');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Provided email is not found in syste. Please try again later !!');
                    redirect('user/auth/forget_password');
                }
            }
        } else {
            $this->template->write_view('content', 'forget_password', $data);
            $this->template->render();
        }
    }

}
