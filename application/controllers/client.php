<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('web_service');
        //ob_clean();
        $ob_get_status = ob_get_status();
        if (isset($ob_get_status['name']) && $ob_get_status['name'] != 'zlib output compression') {
            ob_clean();
        }
    }

    public function server() {
        $server_url = APPPATH . 'libraries/jsonRPCServer.php';
        include_once $server_url;
        jsonRPCServer::handle($this->web_service) or print 'no request';
    }

    public function index() {

        $client_url = APPPATH . 'libraries/jsonRPCClient.php';
        include_once($client_url);

        $server_url = base_url() . 'client/server';
        $obj = new jsonRPCClient($server_url);


        $method = $_GET['method'];
        //$method = 'get_devices';
        switch ($method) {
            case 'download_status':
                $survey_id = $_POST['survey_id'];
                $device_id = $_POST['device_id'];
                $params = array($survey_id, $device_id);
                $response = $this->web_service->download_status($survey_id, $device_id);
                break;

            case 'login':

                /*$postdata = file_get_contents("php://input");
                $request = json_decode($postdata);
                $username = $request->email;
                $password = $request->password;*/

                $username = trim($_POST['email']);
                $password = trim($_POST['password']);
                $params = array($username, $password);
                $response = $this->web_service->login($username, $password);
                break;

            case 'forgot':
                $username = trim($_POST['email']);
                $params = array($username);
                $response = $this->web_service->forgot($username);
                break;

            case 'pushtoken':
                $user_id= trim($_POST['userId']);
                $token = trim($_POST['token']);
                $device = isset($_POST['device']) ? trim($_POST['device']) : "";
                $response = $this->web_service->insert_pushtoken($user_id,$token,$device);
                break;

            case 'get_devices':
                $params = array();
                break;

            case 'get_xml':
                $survey_id = isset($_GET['sid']) && !empty($_GET['sid']) ? $_GET['sid'] : '';
                $type = isset($_GET['t']) && !empty($_GET['t']) ? $_GET['t'] : 'b'; //b for big and s for small mobile device

                $enc = isset($_GET['enc']) && !empty($_GET['enc']) ? $_GET['enc'] : 'n'; //n for no encryption. y for encryption->used in android devices
                $this->web_service->get_xml($survey_id, $type, $enc);

                //$this->web_service->get_xml($survey_id);
                break;

            case 'list_survey':
                $user_id = $_GET['uid'];
                $lang_id = isset($_GET['lang']) ? $_GET['lang'] : "";

                //$list = $this->survey_model->get_surveys_company_app($user_id);
                //var_dump($list);die;

                $params = array($user_id, $lang_id);
                $response = $this->web_service->list_survey($user_id, $lang_id);
                break;


            case 'upload_survey_data':
                $this->load->model('log/log_model');
                $this->log_model->delete_inconsistent_data();
                //die;
                //$filename = "20c9763ab7a8314c_1368526903481.txt";
                if (!empty($_FILES) && $_FILES["file"]["error"] == 0) {
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], "tmp/" . $_FILES["file"]["name"])) {
                        $filename = $_FILES["file"]["name"];
                    }
                }
                $params = array($filename);
                $response = $this->web_service->upload_survey_data($filename);
                break;

            case 'upload_card_image':
                $card_image_details = array();
                if ($_FILES["file"]["error"] == 0) {
                    //get the image name from app
                    $card_image = $_FILES["file"]["name"];
                    //var_dump($card_image);die;
                    $is_comment_image = substr($card_image, 0, 1);
                    if ($is_comment_image == 'c' || $is_comment_image == 'A') {
                        if (move_uploaded_file($_FILES["file"]["tmp_name"], "app_uploads/comment/" . $card_image)) {
                            $card_image_details = array(
                                'is_comment' => 'true',
                            );
                        }
                    } else {

                        //fetch the user details by card image name
                        $user_details = $this->survey_model->get_user_by_imagename($card_image);

                        if (!$user_details) {
                            if (move_uploaded_file($_FILES["file"]["tmp_name"], "app_uploads/waste/" . $card_image)) {

                                $card_image_details = array(
                                    'is_waste' => 'true',
                                );
                            }
                        }

                        //indicates that image in still not updated
                        if (!empty($user_details)) {
                            $survey_user_id = $user_details->survey_user_id; // person who completes the survey
                            //making new image name format
                            $survey_taken = $user_details->questionnaire_id;
                            $survey_info = $this->survey_model->get_survey($survey_taken);
                            $company = $survey_info->company_id;
                            //$this->load->helper('randomnumber');
                            //$new_card_name = get_random_string(10);
                            //$new_image_name = $new_card_name . '_' . $survey_user_id . '_' . $company . '_' . $survey_taken . '.jpeg';
                            //cardid is considered as surveyuserid. cardid_userid_company_surveyid

                            $new_image_name = $survey_user_id . '_' . $user_details->user_id . '_' . $company . '_' . $survey_taken . '.jpeg';

                            // Create a new image resource
                            /* $create_img = imagecreatefromjpeg($_FILES["file"]["tmp_name"]); */

                            // Apply grayscale filter
                            /* imagefilter($create_img, IMG_FILTER_GRAYSCALE); */

                            // Save changes
                            /* imagejpeg($create_img, $_FILES["file"]["tmp_name"]); */


                            //upload the card image in respective folder
                            if (move_uploaded_file($_FILES["file"]["tmp_name"], "app_uploads/" . $new_image_name)) {

                                //copy the orignal image
                                /* @copy("app_uploads/" . $new_image_name, "app_uploads/original/" . $card_image); */

                                //send necessary details to update the new card image
                                $card_image_details = array(
                                    'old_card_image' => $card_image,
                                    'new_card_image' => $new_image_name,
                                    'survey_user_id' => $survey_user_id,
                                );
                            }
                        }
                    }
                }
                $params = array($card_image_details);
                $response = $this->web_service->upload_card_image($card_image_details);
                break;

            default:
                $method = 'service_error_json';
                $params = array('No method found');
                break;
        }

        if ($method != "get_xml") {

            /* $response = $obj->__call($method, $params); */
            Header('Content-type: application/json');
            echo json_encode($response);
        }
    }

}

/* End of file client.php */
    /* Location: ./application/controllers/client.php */
