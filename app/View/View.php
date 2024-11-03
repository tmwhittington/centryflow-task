<?php

namespace app\View;

class View {
    private $view;
    private $data;

    public function __construct($view, $data) {
        $this->view = $view;
        $this->data = $data;
    }

    public function render() {
        if(file_exists( $this->view)) {

            extract($this->data);
            include $this->view;

        }

    }
}