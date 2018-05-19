<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Console
 * Console controller.
 *
 * @property Aauth $aauth
 * @property CI_Input $input
 *
 * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
 * @copyright Copyright (c) 2017 Eleanorsoft (https://www.eleanorsoft.com/)
 * @version 1.0
 */
class Console extends MY_Controller {

    /**
     * Console constructor.
     *
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    public function __construct() {
        parent::__construct();

        if (!$this->input->is_cli_request()) {
            show_404();
        }
    }

    /**
     * Run command if defined and print results to console.
     *
     * @param string $command
     * @throws Exception
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    public function command($command = '', ...$params) {
        switch ($command) {
            case 'user:list':
                $this->print_all_users();
                break;
            case 'user:create':
                $this->create_user($params);
                break;
            case 'user:update':
                $this->update_user($params);
                break;
            case 'user:delete':
                $this->delete_user($params);
                break;
            default:
                print 'command not found';

                print $this->help();
        }
    }

    private function update_user($params) {
        $email = isset($params[0]) ? $params[0] : false;
        $password = isset($params[1]) ? $params[1] : false;
        $username = isset($params[2]) ? $params[2] : false;

        if (!$email || $email == '_') {
            print 'email is required';

            return;
        }

        $user_id = $this->aauth->get_user_id($email);

        if (!$user_id) {
            print 'user with specified email does not exist';

            return;
        }

        if ($this->aauth->update_user(
            $user_id,
            $email != '_' ? $email : false,
            $password != '_' ? $password : false,
            $username
        )) {
            print 'user updated successfully';
        } else {
            print 'some errors occurred';
        }
    }

    private function delete_user($params) {
        $email = isset($params[0]) ? $params[0] : false;

        if (!$email) {
            print 'email is required';

            return;
        }

        if ($email == 'all') {
            $errors = '';

            foreach ($this->aauth->list_users() as $user) {
                if(!$this->aauth->delete_user($user->id)) {
                    $errors .= "{$user->email} not deleted\n";
                }
            }

            print $errors ? "some errors was occurred: $errors" : 'all users deleted successfully';

            return;
        }

        $user_id = $this->aauth->get_user_id($email);

        if (!$user_id) {
            print 'user with specified email does not exist';

            return;
        }

        if ($this->aauth->delete_user($user_id)) {
            print 'user deleted successfully';
        } else {
            print 'some errors occurred';
        }
    }

    private function create_user($params) {
        $email = isset($params[0]) ? $params[0] : false;
        $password = isset($params[1]) ? $params[1] : false;
        $username = isset($params[2]) ? $params[2] : explode('@', $email)[0];

        if (!$email || !$password) {
            print 'email or password is empty';

            return;
        }

        if ($this->aauth->get_user_id($email)) {
            print 'already exists';

            return;
        }

        if ($this->aauth->create_user($email, $password, $username)) {
            print 'user created successfully';
        } else {
            print 'some errors occurred';
        }
    }

    private function print_all_users() {
        foreach ($this->aauth->list_users() as $user) {
            print "{$user->email}\n";
        }
    }

    /**
     * Return stubbed help info
     *
     * @return string
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    private function help() {
        return <<<HELP
\n
available commands\n

user:list:\tget user list
-----------------------------

user:create:\tcreate new user
format: email password [username]
example: user:update test@mail.com 12345Qq test_name
example: user:update test@mail.com 12345Qq
notice: if username is not specified, takes email part before @
-----------------------------

user:update:\tupdate user
format: email password [username]
example: user:update test@mail.com 12345Qq test_name
example: user:update test@mail.com _ test_name
example: user:update test@mail.com 12345Qq
notice: use _ if you want to skip the password
-----------------------------

user:delete:\tdelete user
format: [email] [all - delete all users]
example: user:delete test@mail.com
example: user:delete all
-----------------------------

\n
HELP;
    }

}
