<?php

abstract class AbstractController {

    protected $view;
    protected $messages;
    protected $request;
    protected $fp = null;
    protected $auth_user = null;
    protected $jsv = null;
    protected $mail;

    public function __construct() {
        session_start();
        $this->request = new Request();
        $this->messages = new Messages(Config::MESSAGES_FILE);
        $this->view = new View();
        $this->fp = new FormProcessor($this->request,$this->messages);
        $this->auth_user = $this->authUser();
        $this->jsv = new JSValidator($this->messages);
        $this->mail = new AbstractMail($this->view, Config::ADMIN_NAME);
        if (!$this->access()) {
            $this->accessDenied();
            throw new Exception("ACCESS_DENIED");
        }
    }

    protected function authUser(){
        return null;
    }

    protected function access() {
        return true;
    }

    abstract protected function accessDenied();

    abstract protected function render($str);

    final protected function notFound() {
        $this->action404();
    }

    abstract protected function action404();

    final protected function redirect($url) {
        header("Location: $url");
        exit;
    }

    final protected function renderData($modules, $layout, $params = array()) {
        if (!is_array($modules)) return false;
        foreach ($modules as $key => $value) {
            $params[$key] = $value;
        }
        return $this->view->render($layout, $params, true);
    }

}

?>