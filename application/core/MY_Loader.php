<?php

/**
 * Class MY_Loader
 * Advanced template loader.
 *
 * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
 * @copyright Copyright (c) 2017 Eleanorsoft (https://www.eleanorsoft.com/)
 */
class MY_Loader extends CI_Loader {

    /**
     * Add footer and header to standard template render.
     *
     * @param $template_name
     * @param array $vars
     * @param bool $return
     * @return object|string
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    public function template($template_name, $vars = array(), $return = FALSE) {
        $vars['assets_url'] =  base_url() . 'assets';

        if ($return) {
            $content = $this->view('templates/header', $vars, $return);
            $content .= $this->view($template_name, $vars, $return);
            $content .= $this->view('templates/footer', $vars, $return);

            return $content;
        } else {
            $this->view('templates/header', $vars);
            $this->view($template_name, $vars);
            $this->view('templates/footer', $vars);
        }
    }

}
