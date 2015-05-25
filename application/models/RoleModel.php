<?php
/**
 * 权限管理模型
 */

class RoleModel extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

	// 获取权限列表
	public function getRoleList(){

		// $query = $this->db->select('*')->from('qcgj_role_rule')->where('type', 1);

		$query = $this->db->get('qcgj_role_rule')->result_array();
		echo '<pre>';
		print_r($query);exit;
	}
}
