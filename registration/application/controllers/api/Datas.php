<?php

require_once APPPATH . 'libraries/REST_Controller.php';
class Datas extends REST_Controller 
{
	public function __construct($config = 'rest') {
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

		$this->load->model('Datas_model', 'datas');
	}
	
	function index_get(){
        $info = array(
            'version' => 0.1
        );

        $this->response($info);
    }
    

	
	private function data() {
		$data = array(
			'keterangan' => $this->input->post('keterangan', TRUE),
			'tgllapor' => $this->input->post('tgllapor', TRUE),
			'lokasi' => $this->input->post('lokasi', TRUE),
			'photo' => $this->input->post('photo', TRUE),
			);
		
		return $data;
	}
	
	private function status($result) {
		if ($result) {
			$response = array(
				'status' => 'Berhasil',
				'error' => '',
				'data' => $result
			);
			$this->response($response, 200);
		} else {
			$response = array(
				'status' => 'Gagal,',
				'error' => 'Terjadi Kesalahan',
				'data' => ''
			);
			$this->response($response, 500);
		}
		return $response;
	}
	
	public function insert_post() {
		$data = $this->data();
		$result = $this->datas->insert($data);
		$this->status($result);
	}
	
	public function update_post() {
		$data = $this->data();
		$id = $this->input->post('id', TRUE);
		$result = $this->datas->update($data, $id);
		$this->status($result);
	}
	
	public function delete_post() {
		$id = $this->input->post('id', TRUE);
		$result = $this->datas->delete($id);
		$this->status($result);
	}
	
	public function find_get() {
		$id = $this->input->get('id', TRUE);
		$result = $this->datas->get($id);
		$this->status($result);
	}
	
	public function all_get() {
		$result = $this->datas->all();
		$this->status($result);
	}
	
	public function upload_post() {
        $config['upload_path'] = realpath(APPPATH . '../assets/upload/');
        $config['allowed_types'] = '*';
        $config['max_size'] = 10240 ;//10MB
        $this->load->library('upload', $config);
		//$id = $this->input->post('id');
        //unlink(realpath(APPPATH . '../assets/upload/'.$id.'.jpg'));//hapus gambar lama
		$result = $this->upload->do_upload('gambar');
		$this->status($result);
    }

	
}