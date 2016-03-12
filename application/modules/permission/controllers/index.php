<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('permission/permission_model', '', TRUE);
    }

    /**
     * This function displays and manages the permission add form
     */
    public function add() {

        if ($_POST) {
            $this->form_validation->set_rules('permission', 'Permission', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'new');
                $this->template->render();
            } else {
                $name = $this->input->post('permission');
                $slug = str_replace(' ', '-', $name);
                $data = array('name' => $name, 'slug' => $slug, 'status' => 1);
                $this->permission_model->save($data);
                $this->session->set_flashdata('success', 'Permission added successfully');
                redirect(base_url() . 'permission/all');
            }
        } else {
            $data = '';
            //$data['permission'] = $this->survey_model->get_permission();
            $this->template->write_view('content', 'new', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays and manages the permission edit form
     */
    public function edit() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'permission/all');
        }

        $permission_exists = $this->permission_model->check_permission_exists($id);
        if (empty($permission_exists)) {
            $this->session->set_flashdata('error', 'No such permission exists');
            redirect(base_url() . 'permission/all');
        }

        $data['permission'] = $this->permission_model->get_permission($id);
        $data['eid'] = $id;
        if ($_POST) {
            $this->form_validation->set_rules('permission', 'Permission', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'edit', $data);
                $this->template->render();
            } else {
                $name = $this->input->post('permission');
                $slug = str_replace(' ', '-', $name);
                $data = array('name' => $name, 'slug' => $slug);
                $this->permission_model->update($id, $data);
                $this->session->set_flashdata('success', 'Permission updated successfully');
                redirect(base_url() . 'permission/all');
            }
        } else {
            $this->template->write_view('content', 'edit', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays all the permissions, and also the roles associated with each of them
     */
    public function all() {

        $data['permissions'] = $this->permission_model->get_permissions(NULL, FALSE);

        $this->template->write_view('content', 'list', $data);
        $this->template->render();
    }

    /**
     * This function deletes the permission
     */
    public function delete() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'permission/all');
        }

        $permission_exists = $this->permission_model->check_permission_exists($id);
        if (empty($permission_exists)) {
            $this->session->set_flashdata('error', 'No such permission exists');
            redirect(base_url() . 'permission/all');
        }

        $this->permission_model->delete($id);
        $this->session->set_flashdata('success', 'Permission deleted successfully');
        redirect(base_url() . 'permission/all');
    }

    /**
     * This function manages the permission
     */
    public function manage() {

        $action = $this->input->post('action');
        $selected_values = $this->input->post('child');

        if (empty($selected_values)) {
            $this->session->set_flashdata('error', 'Please select a record from the list');
            redirect(base_url() . 'permission/all');
        } else {
            switch ($action) {
                case 'delete':
                    foreach ($selected_values as $id) {
                        $this->permission_model->delete($id);
                    }
                    break;
                case 'publish':
                    foreach ($selected_values as $id) {
                        $this->permission_model->update($id, array('status' => 1));
                    }
                    break;
                case 'unpublish':
                    foreach ($selected_values as $id) {
                        $this->permission_model->update($id, array('status' => 0));
                    }
                    break;
                default:
                    break;
            }

            $this->session->set_flashdata('success', 'Permission updated successfully');
            redirect(base_url() . 'permission/all');
        }
    }

}
