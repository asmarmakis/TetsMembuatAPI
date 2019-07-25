<?php
require_once APPPATH . 'libraries/REST_Controller.php';

class Member extends REST_Controller {
	
	public function __construct($config = 'rest'){
		parent::__construct($config);
		$token = $this->post('token');

        if (!$token) {
            $token = $this->get('token');
            if (!$token) {
                $response = array(
                    'status' => 'failed',
                    'error' => 'akses ditolak',
                    'data' => ''
                );
                $this->response($response, REST_Controller::HTTP_UNAUTHORIZED);
            }
        }
        $where = array(
            'token' => $token,
        );
        $this->load->model('user_model', 'user');
        $result = $this->user->get_where($where);
        if (count($result) < 0) {
            $response = array(
                'status' => 'failed',
                'error' => 'token invalid',
                'data' => ''
            );
            $this->response($response, REST_Controller::HTTP_UNAUTHORIZED);
        }

		$this->load->model('member_model', 'member');
	}
	
	function index_get(){
        $info = array(
            'version' => 0.1
        );

        $this->response($info);
    }	
	
	
	private function data (){
		$data = array (
			'member_name' => $this->input->post('member_name', TRUE),
			'member_email' => $this->input->post('member_email', TRUE),
			'member_birthdate' => $this->input->post('member_birthdate', TRUE),
			'member_sex' => $this->input->post('member_sex', TRUE),
			'member_religion' => $this->input->post('member_religion', TRUE),
			'member_address' => $this->input->post('member_address', TRUE),
		);
		return $data;
	}
	
	private function status($result) {
		if ($result) {
			$response = array (
				'status' => 'success',
				'error' => '',
				'data' => $result
			);
			$this->response($response, 200);
			
		} else {
			$response = array(
				'status' => 'failed',
				'error' => 'terjadi error',
				'data' => ''
			);
			$this->response($response, 500);
		}
		return $response;
	}
	
	public function insert_post() {
		$data = $this->data();
		$result = $this->member->insert($data);
		$this->status($result);
	}
	
	public function update_post(){
		$data = $this->data();
		$id = $this->input->post('id', TRUE);
		$result = $this->member->update($data, $id);
		$this->status($result);
	}
	
	public function delete_post() {
		$id = $this->input->post('id', TRUE);
		$result = $this->member->delete($id);
		$this->status($result);
	}
	
	public function find_get() {
		$id = $this->input->get('id', TRUE);
		$result = $this->member->get($id);
		$this->status($result);
	}
	
	public function all_get() {
		$result = $this->member->all();
		$this->status($result);
	}
	
	public function upload_post() {
		$config['upload_path']=realpath(APPPATH.'../assets/upload/');
		$config['allowed_types']='*';
		$config['max size']=2048;//2MB
		$this->load->library('upload',$config);
			$result=$this->upload->do_upload('gambar');
			$this->status($result);
	}
	
}