<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Designations extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('designations_model');
        $this->load->model('departments_model');
        $this->load->dbforge();

        if (!is_admin()) {
            access_denied('Designations');
        }

        // Ensure table exists
        $this->_maybe_create_table();
    }

    private function _maybe_create_table()
    {
        $table = db_prefix() . 'designations';
        if (!$this->db->table_exists($table)) {
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'department_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => false,
                    'default' => 0,
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 191,
                    'null' => false,
                ],
                'created_by' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,
                ],
                'date_created' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_by' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,
                ],
                'date_updated' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'is_active' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => false,
                    'default' => 1,
                ],
                'company_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,
                ],
            ];

            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table($table, true);

            // Add index for department_id
            $this->db->query('ALTER TABLE `'.$table.'` ADD INDEX (`department_id`);');
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('designations');
        }
        $data['title'] = 'Designations';
        $this->load->view('admin/designations/manage', $data);
    }

    public function manage()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('department_id', 'Department', 'required|integer');
            $this->form_validation->set_rules('title', 'Designation', 'required|min_length[2]|max_length[191]');

            $is_ajax = $this->input->is_ajax_request();
            $id = $this->input->post('id');

            if ($this->form_validation->run() === false) {
                $errors = validation_errors();
                if ($is_ajax) {
                    echo json_encode(['success' => false, 'message' => $errors]);
                    die;
                }
                set_alert('warning', $errors);
                redirect(admin_url('designation'));
            }

            $data = $this->input->post();
            if (empty($id)) {
                $insert_id = $this->designations_model->add($data);
                $success = (bool)$insert_id;
                $message = $success ? _l('added_successfully', 'Designation') : _l('problem_adding', 'Designation');
            } else {
                $success = $this->designations_model->update((int)$id, $data);
                $message = $success ? _l('updated_successfully', 'Designation') : _l('problem_updating', 'Designation');
            }

            if ($is_ajax) {
                echo json_encode(['success' => $success, 'message' => $message]);
                die;
            }

            if ($success) {
                set_alert('success', $message);
            } else {
                set_alert('warning', $message);
            }
            redirect(admin_url('designation'));
        }
    }

    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('designation'));
        }
        $success = $this->designations_model->delete($id);
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => $success, 'message' => $success ? _l('deleted', 'Designation') : _l('problem_deleting', 'Designation')]);
            die;
        }
        if ($success) {
            set_alert('success', _l('deleted', 'Designation'));
        } else {
            set_alert('warning', _l('problem_deleting', 'Designation'));
        }
        redirect(admin_url('designation'));
    }

    public function departments()
    {
        // Return active departments list for select (AJAX helper)
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $deps = $this->departments_model->get();
        $out = [];
        foreach ($deps as $d) {
            $out[] = ['id' => $d['departmentid'], 'name' => $d['name']];
        }
        echo json_encode($out);
    }
}
