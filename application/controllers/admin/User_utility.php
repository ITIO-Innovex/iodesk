<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_utility extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_utility_model');
    }

    /**
     * List all user utility forms
     */
    public function index()
    {
        if (!is_admin() && !has_permission('user_utility', '', 'view')) {
            //access_denied('User Utility');
        }

        $data['forms'] = $this->user_utility_model->get_all_forms();
        $data['title'] = 'User Utility Forms';
        $this->load->view('admin/user_utility/manage', $data);
    }

    /**
     * Create new form
     */
    public function create()
    {
        if (!is_admin() && !has_permission('user_utility', '', 'create')) {
           // access_denied('User Utility');
        }

        if ($this->input->post()) {
            $form_name = $this->input->post('form_name');
            $form_fields = $this->input->post('form_fields');

            if (empty($form_name) || empty($form_fields)) {
                set_alert('danger', 'Form name and fields are required');
                redirect(admin_url('user_utility/create'));
            }

            $data = [
                'form_name' => $form_name,
                'form_fields' => json_encode($form_fields),
                'created_by' => get_staff_user_id(),
                'company_id' => get_staff_company_id()
            ];

            $insert_id = $this->user_utility_model->add($data);

            if ($insert_id) {
                set_alert('success', 'Form created successfully');
                redirect(admin_url('user_utility'));
            } else {
                set_alert('danger', 'Failed to create form');
            }
        }

        $data['title'] = 'Create New Form';
        $this->load->view('admin/user_utility/form', $data);
    }

    /**
     * Edit existing form
     */
    public function edit($id)
    {
        if (!is_admin() && !has_permission('user_utility', '', 'edit')) {
            //access_denied('User Utility');
        }

        $form = $this->user_utility_model->get($id);
        if (!$form) {
            show_404();
        }

        if ($this->input->post()) {
            $form_name = $this->input->post('form_name');
            $form_fields = $this->input->post('form_fields');

            if (empty($form_name) || empty($form_fields)) {
                set_alert('danger', 'Form name and fields are required');
                redirect(admin_url('user_utility/edit/' . $id));
            }

            $data = [
                'form_name' => $form_name,
                'form_fields' => json_encode($form_fields)
            ];

            $success = $this->user_utility_model->update($id, $data);

            if ($success) {
                set_alert('success', 'Form updated successfully');
                redirect(admin_url('user_utility'));
            } else {
                set_alert('danger', 'Failed to update form');
            }
        }

        $data['form'] = $form;
        $data['form']->form_fields = json_decode($form->form_fields, true);
        $data['title'] = 'Edit Form: ' . $form->form_name;
        $this->load->view('admin/user_utility/form', $data);
    }

    /**
     * Delete form
     */
    public function delete($id)
    {
        if (!is_admin() && !has_permission('user_utility', '', 'delete')) {
            //access_denied('User Utility');
        }

        $form = $this->user_utility_model->get($id);
        if (!$form) {
            show_404();
        }

        $success = $this->user_utility_model->delete($id);

        if ($success) {
            set_alert('success', 'Form deleted successfully');
        } else {
            set_alert('danger', 'Failed to delete form');
        }

        redirect(admin_url('user_utility'));
    }

    /**
     * View and submit dynamic form
     */
    public function view($id)
    {
        if (!is_admin() && !has_permission('user_utility', '', 'view')) {
           // access_denied('User Utility');
        }

        $form = $this->user_utility_model->get($id);
        if (!$form) {
            show_404();
        }

        if ($this->input->post()) {
            $form_data = [];
            $form_fields = json_decode($form->form_fields, true);

            foreach ($form_fields as $field) {
                $field_name = $field['name'];
                $field_type = $field['type'];

                if ($field_type === 'file') {
                    // Handle file upload
                    if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] === 0) {
                        $upload_path = './uploads/user_utility/';
                        if (!is_dir($upload_path)) {
                            mkdir($upload_path, 0755, true);
                        }

                        $file_name = time() . '_' . $_FILES[$field_name]['name'];
                        if (move_uploaded_file($_FILES[$field_name]['tmp_name'], $upload_path . $file_name)) {
                            $form_data[$field_name] = $file_name;
                        }
                    }
                } elseif ($field_type === 'checkbox') {
                    $form_data[$field_name] = $this->input->post($field_name) ? $this->input->post($field_name) : [];
                } else {
                    $form_data[$field_name] = $this->input->post($field_name);
                }
            }

            $update_data = [
                'form_data' => json_encode($form_data)
            ];

            $success = $this->user_utility_model->update($id, $update_data);

            if ($success) {
                set_alert('success', 'Form data saved successfully');
                redirect(admin_url('user_utility/view/' . $id));
            } else {
                set_alert('danger', 'Failed to save form data');
            }
        }

        $data['form'] = $form;
        $data['form']->form_fields = json_decode($form->form_fields, true);
        $data['form']->form_data = $form->form_data ? json_decode($form->form_data, true) : [];
        $data['title'] = 'Form: ' . $form->form_name;
        $this->load->view('admin/user_utility/view_form', $data);
    }

    /**
     * AJAX endpoint to get field types
     */
    public function get_field_types()
    {
        $field_types = [
            'text' => 'Text',
            'textarea' => 'Textarea',
            'listbox' => 'Listbox',
            'radio' => 'Radio',
            'checkbox' => 'Checkbox',
            'datetime' => 'Date/Time',
            'file' => 'File'
        ];

        echo json_encode($field_types);
    }
}
