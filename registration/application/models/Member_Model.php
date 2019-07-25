<?php

class Member_Model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->table = "member";
		$this->pk = "id";
	}
	
	public function insert($data){
		return $this->db->insert($this->table, $data);
	}
	
	public function update($data, $id){
		$where = array(
			$this->pk => $id
		);
		return $this->db->update($this->table, $data, $where);
	}
	
	public function delete($id){
		$where = array(
				$this->pk => $id	
		);
		return $this->db->delete($this->table, $where);				
	}
	
	public function get($id){
		$where = array (
		$this->pk => $id
		);
		return $this->db->get_where($this->table, $where)->result();
	}
	
	public function all(){
		return $this->db->get($this->table)->result();
	}
	
}