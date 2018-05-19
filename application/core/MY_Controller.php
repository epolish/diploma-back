<?php

/**
 * Class MY_Controller
 * Advanced general controller, which can operate with logs.
 *
 * @param $file
 * @property CI_URI $uri
 * @property Aauth $aauth
 * @property CI_Input $input
 * @property CI_Router $router
 * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
 * @copyright Copyright (c) 2017 Eleanorsoft (https://www.eleanorsoft.com/)
 */
class MY_Controller extends CI_Controller {

    /**
     * MY_Controller constructor.
     *
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    public function __construct() {
        parent::__construct();

        $method = $this->router->fetch_method();
        $controller = $this->router->fetch_class();
        $action = "/$controller/$method";

        $this->process_guest_request($action);
        $this->process_logged_user_request($action);
    }

    /**
     * Process guest request.
     *
     * @param $action
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    protected function process_guest_request($action) {
        if (
            !$this->aauth->is_loggedin() &&
            $action != $this->get_login_action() &&
            !$this->input->is_cli_request()
        ) {
            $this->redirect_login_page();
        }
    }

    /**
     * Process logged user request.
     *
     * @param $action
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    protected function process_logged_user_request($action) {
        if (
            $this->aauth->is_loggedin() &&
            $action == $this->get_login_action()
        ) {
            $this->redirect_home_page();
        }
    }

    /**
     * Get login action.
     *
     * @return string
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    protected function get_login_action() {
        return '/auth/login';
    }

    /**
     * Redirect to home page.
     *
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    protected function redirect_home_page() {
        redirect('/statement', 'refresh');
    }

    /**
     * Redirect to login page.
     *
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    protected function redirect_login_page() {
        redirect($this->get_login_action(), 'refresh');
    }

    /**
     * Reload current page.
     *
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    protected function reload_page() {
        redirect($this->uri->uri_string(), 'refresh');
    }

}
