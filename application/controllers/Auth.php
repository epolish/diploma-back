<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * User authorization controller.
 *
 * @property Aauth $aauth
 * @property CI_Input $input
 * @property CI_Session $session
 *
 * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
 * @copyright Copyright (c) 2017 Eleanorsoft (https://www.eleanorsoft.com/)
 * @version 1.0
 */
class Auth extends MY_Controller {

    /**
     * User logout action
     *
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    public function logout() {
        $this->aauth->logout();

        $this->redirect_login_page();
    }

    /**
     * User sign in action
     *
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
	public function login() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        if ($email && $password) {
            if (!$this->aauth->login($email, $password)) {
                $this->session->set_flashdata('error_message', 'Incorrect username or password.');
                $this->reload_page();
            } else {
                $this->redirect_home_page();
            }
        }

        $this->load->template('forms/auth', [
            'error_message' => $this->session->flashdata('error_message')
        ]);
    }

}
