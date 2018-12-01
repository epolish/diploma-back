<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class StatementModel
 * StatementModel controller.
 *
 * @property Aauth $aauth
 * @property CI_Input $input
 * @property CI_Upload $upload
 * @property CI_Session $session
 * @property Expert_system $expert_system
 *
 * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
 * @copyright Copyright (c) 2017 Eleanorsoft (https://www.eleanorsoft.com/)
 * @version 1.0
 */
class Statement extends MY_Controller {

    /**
     * StatementModel constructor.
     *
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    public function __construct() {
        parent::__construct();

        $this->load->model('expert_system');
        $this->load->library('upload', [
            'overwrite' => true,
            'allowed_types' => 'csv',
            'upload_path' => './uploads/'
        ]);
    }

    public function import()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (array_key_exists('import_url', $_POST) && $_POST['import_url']) {
                try {
                    $this->expert_system->import(
                        $_POST['import_url'],
                        array_key_exists('append_mode', $_POST),
                        true
                    );
                    $this->session->set_flashdata('success_message', 'Imported successfully');
                } catch (\Exception $ex) {
                    $this->session->set_flashdata('error_message', $ex->getMessage());
                }
            } elseif (!$this->upload->do_upload('import_file')) {
                $this->session->set_flashdata('error_message', $this->upload->display_errors());
            } else {
                try {
                    $this->expert_system->import(
                        $this->upload->data()['full_path'],
                        array_key_exists('append_mode', $_POST)
                    );
                    $this->session->set_flashdata('success_message', 'Imported successfully');
                } catch (\Exception $ex) {
                    $this->session->set_flashdata('error_message', $ex->getMessage());
                }
            }

            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->load->template('statement/import', [
            'user_name' => $this->aauth->get_user()->username,
            'error_message' => $this->session->flashdata('error_message'),
            'success_message' => $this->session->flashdata('success_message'),
        ]);
    }

    /**
     * General action. Set service config data to template.
     *
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    public function index() {
        $this->load->template('statement/list', [
            'user_name' => $this->aauth->get_user()->username,
            'statements' => $this->expert_system->get_statements(),
            'error_message' => $this->session->flashdata('error_message'),
            'success_message' => $this->session->flashdata('success_message'),
        ]);
	}

	public function get($value = 'root') {
        try {
            $statement = $this->expert_system->get_statement($value);

            $this->load->template('statement/edit', [
                'user_name' => $this->aauth->get_user()->username,
                'statement' => $statement['statement_value'],
                'parent_statement' => $statement['parent_statement_value'],
                'parent_relationship' => $statement['parent_relationship_value'],
                'parent_relationship_support_level_value' => $statement['parent_relationship_support_level_value'],
                'child_statements' => $statement['child_statements'],
                'statements' => $this->expert_system->get_statements(),
                'error_message' => $this->session->flashdata('error_message'),
                'success_message' => $this->session->flashdata('success_message'),
            ]);
        } catch (\Exception $ex) {
            $this->session->set_flashdata('error_message', $ex->getMessage());

            redirect('statement/');
        }
    }

    public function tree() {
        $this->load->template('statement/tree', [
            'user_name' => $this->aauth->get_user()->username,
            'statements' => $this->expert_system->get_statement_tree_array(),
            'error_message' => $this->session->flashdata('error_message'),
            'success_message' => $this->session->flashdata('success_message'),
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$statement_value = isset($_POST['statement_value']) ? $_POST['statement_value'] : null;
			$parent_statement_value = isset($_POST['parent_statement_value']) ? $_POST['parent_statement_value'] : null;
			$parent_relationship_value = isset($_POST['parent_relationship_value']) ? $_POST['parent_relationship_value'] : null;
            $parent_relationship_support_level_value = isset($_POST['parent_relationship_support_level_value']) ? $_POST['parent_relationship_support_level_value'] : null;

            try {
                $this->expert_system->create_statement(
                    $statement_value,
                    $parent_statement_value,
                    $parent_relationship_value,
                    $parent_relationship_support_level_value
                );
                $this->session->set_flashdata('success_message', 'Statement created successfully');
            } catch (Exception $ex) {
                $this->session->set_flashdata('error_message', $ex->getMessage());
                $this->session->set_flashdata('statement_value', $statement_value);
                $this->session->set_flashdata('parent_statement_value', $parent_statement_value);
                $this->session->set_flashdata('parent_relationship_value', $parent_relationship_value);
                $this->session->set_flashdata('parent_relationship_support_level_value', $parent_relationship_support_level_value);

                redirect($_SERVER['HTTP_REFERER']);
            }

            redirect('statement/');
        }

        $this->load->template('statement/create', [
            'statement' => $this->session->flashdata('statement_value'),
            'parent_statement' => $this->session->flashdata('parent_statement_value'),
            'parent_relationship' => $this->session->flashdata('parent_relationship_value'),
            'parent_relationship_support_level_value' => $this->session->flashdata('parent_relationship_support_level_value'),
            'user_name' => $this->aauth->get_user()->username,
            'statements' => $this->expert_system->get_statements(),
            'error_message' => $this->session->flashdata('error_message'),
            'success_message' => $this->session->flashdata('success_message'),
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->expert_system->update_statement(
                    $_POST['statement_value'],
                    $_POST['new_statement_value'],
                    isset($_POST['new_parent_statement_value']) ? $_POST['new_parent_statement_value'] : null,
                    isset($_POST['new_parent_relationship_value']) ? $_POST['new_parent_relationship_value'] : null,
                    isset($_POST['new_parent_relationship_support_level_value']) ? $_POST['new_parent_relationship_support_level_value'] : null
                );

                $this->session->set_flashdata('success_message', 'Statement updated successfully');
            } catch (Exception $ex) {
                $this->session->set_flashdata('error_message', $ex->getMessage());

                redirect($_SERVER['HTTP_REFERER']);
            }

        }

        redirect('statement/');
    }

    public function remove($value) {
        try {
            $this->expert_system->remove_statement(urldecode($value), true);
            $this->session->set_flashdata('success_message', 'Statement deleted successfully');
        } catch (Exception $ex) {
            $this->session->set_flashdata('error_message', $ex->getMessage());

            redirect($_SERVER['HTTP_REFERER']);
        }

        redirect('statement/');
    }

}
