<?php
class Session {
    private $logged_in, $username;
    public function __construct($model) {
        $this->model = $model;
        $this->logged_in = $_SESSION['logged_in'];
        $this->username = $_SESSION['username'];
    }

    public static function init() {
        if(!isset($_SESSION['logged_in'])) {
            $_SESSION['logged_in'] = false;
        }
        if(!isset($_SESSION['username'])) {
            $_SESSION['username'] = "";
        }
    }

    public function login($data) {
        $this->logout();
        if(!isset($data['username']) || !isset($data['password'])) {
            return false;
        }
        if($data['username'] == 'ijsjesop1stok' && $data['password'] == 'rakettenop2') {
            $this->setLoggedIn(true);
            $this->setUsername($data['username']);
            return true;
        }
        return false;
    }

    private function setLoggedIn($logged_in) {
        $this->logged_in = $logged_in;
        $_SESSION['logged_in'] = $this->logged_in;
    }

    public function getLoggedIn() {
        return $this->logged_in;
    }

    private function setUsername($username) {
        $this->username = $username;
        $_SESSION['username'] = $this->username;
    }

    public function logout() {
        $this->setLoggedIn(false);
        return true;
    }

}

Session::init();
?>