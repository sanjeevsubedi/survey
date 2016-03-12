<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('device/device_model');
    }

    /**
     * This function displays and manages the device add form
     */
    public function add() {

        if ($_POST) {
            $this->form_validation->set_rules('device', 'Device', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'new');
                $this->template->render();
            } else {
                $name = $this->input->post('device');
                $data = array('name' => $name, 'status' => 1);
                $this->device_model->save($data);
                $this->session->set_flashdata('success', 'Device added successfully');
                redirect(base_url() . 'device/all');
            }
        } else {
            $data = '';
            //$data['device'] = $this->survey_model->get_devices();
            $this->template->write_view('content', 'new', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays and manages the device edit form
     */
    public function edit() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'device/all');
        }
        $data['device'] = $this->device_model->get_device($id);
        $data['eid'] = $id;
        if ($_POST) {
            $this->form_validation->set_rules('device', 'Device', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'edit', $data);
                $this->template->render();
            } else {
                $name = $this->input->post('device');
                $data = array('name' => $name);
                $this->device_model->update($id, $data);
                $this->session->set_flashdata('success', 'Device updated successfully');
                redirect(base_url() . 'device/all');
            }
        } else {
            $this->template->write_view('content', 'edit', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays all the devices
     */
    public function all() {
        $data['devices'] = $this->device_model->get_devices(NULL, FALSE);
        $this->template->write_view('content', 'list', $data);
        $this->template->render();
    }

    /**
     * This function deletes the device
     */
    public function delete() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'device/all');
        }
        $this->device_model->delete($id);
        $this->session->set_flashdata('success', 'Device deleted successfully');
        redirect(base_url() . 'device/all');
    }

    /**
     * This function manage the device
     */
    public function manage() {

        $action = $this->input->post('action');
        $selected_values = $this->input->post('child');

        if (empty($selected_values)) {
            $this->session->set_flashdata('error', 'Please select a record from the list');
            redirect(base_url() . 'device/all');
        } else {
            switch ($action) {
                case 'delete':
                    foreach ($selected_values as $id) {
                        $this->device_model->delete($id);
                    }
                    break;
                case 'publish':
                    foreach ($selected_values as $id) {
                        $this->device_model->update($id, array('status' => 1));
                    }
                    break;
                case 'unpublish':
                    foreach ($selected_values as $id) {
                        $this->device_model->update($id, array('status' => 0));
                    }
                    break;
                default:
                    break;
            }

            $this->session->set_flashdata('success', 'Device updated successfully');
            redirect(base_url() . 'device/all');
        }
    }

}
