<?php
/**
 * 权限管理模型
 */

class RoleModel extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

	// 获取权限列表
	public function getRoleList($pageCount = 10, $pageNum = 1){

		$pageNum = ($pageNum - 1) * $pageCount;

		$queryRes = $this->db
						 ->select('qcgj_role_user.user_id, qcgj_role_user.name, qcgj_role.name AS role_name, qcgj_role_user.created_time, qcgj_role_user.status')
						 ->join('qcgj_role', 'qcgj_role.role_id = qcgj_role_user.role_id')
						 ->get_where('qcgj_role_user', array('qcgj_role_user.status' => 1))
						 ->result();
		// $totalCount = $this->db
		// 				 ->select('qcgj_role_user.user_id, qcgj_role_user.name, qcgj_role.name AS role_name, qcgj_role_user.created_time')
		// 				 ->join('qcgj_role', 'qcgj_role.role_id = qcgj_role_user.role_id')
		// 				 ->get_where('qcgj_role_user', array('qcgj_role_user.status' => 1))
		// 				 ->count_all_results();
		return $queryRes;
		echo '<pre>';
		print_r($queryRes);exit;
		foreach ($queryRes as $k => $v) {

		}
	}

	/**
	 * 设置分页
	 */
	public function setPagination($url = false, $total = 1){
		$pageConfig = array(
				'base_url'   => site_url('Role/rolelist'),
				'total_rows' => 2,
				'pre_page'   => 1,
			);

		$this->pagination->initialize($pageConfig);

		return $this->pagination->create_links();
	}
}
