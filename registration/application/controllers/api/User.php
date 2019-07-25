<?php

require_once APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller {

    public function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model('user_model', 'user');
    }

    public function login_post() {
        $username = $this->post('username');
        $password = $this->post('password');
        $where = array(
            'username' => $username,
            'password' => $password
        );
        $result = $this->user->get_where($where);

        if (count($result) > 0) {
            $token = md5(rand(0, 10000));
            $user = $result[0];
            $userid = $user->userid;
            $this->user->update(array('token' => $token),$userid);
            $data = array(
                'success' => TRUE,
                'message' => 'Login Berhasil',
                'token' => $token
            );
        } else {
            $data = array(
                'success' => FALSE,
                'message' => 'Login gagal',
				'token' => ''
            );
        }
        $this->response($data);
    }
	public function logout_post() {
        $token = $this->post('token');
        $where = array(
            'token' => $token,
        );
        $result = $this->user->get_where($where);

        if (count($result) > 0) {
            $user = $result[0];
            $userid = $user->userid;
            $this->user->update(array('token' => ''),$userid);
            $data = array(
                'success' => TRUE,
                'message' => 'Logout Berhasil',
                'token' => $token
            );
        } else {
            $data = array(
                'success' => FALSE,
                'message' => 'Logout gagal',
                'token' => ''
            );
        }
        $this->response($data);
    }


}
