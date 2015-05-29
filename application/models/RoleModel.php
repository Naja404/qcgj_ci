<?php
/**
 * 权限管理模型
 */

class RoleModel extends CI_Model {
	// 返回数据
	public $returnRes;

	public function __construct(){
		$this->returnRes = array(
							'error' => true, // true=有错误, false=正确
							'msg'   => false, 
							'data'  => array()
							);
	}

	/**
	 * 添加规则
	 * @param array $addRuleData 规则数组
	 */
	public function addRule($addRuleData = array()){
		$otherArr = array(
				'status'       => 1,
				'created_time' => currentTime(),
			);
		$addRuleData = array_merge($addRuleData, $otherArr);

		$queryRes = $this->db->insert(tname('qcgj_role_rule'), $addRuleData);

		if (!$queryRes) {
			$this->returnRes['msg'] = $this->lang->line('ERR_ADD_FAILURE');
		}else{
			$this->returnRes['error'] = false;
		}

		return $this->returnRes;
	}

	/**
	 * 更新用户内容
	 * @param array $updateArr 需要更新的数组
	 */
	public function updateUser($updateArr = array()){

		$queryRes = $this->db->where(array('user_id' => $updateArr['user_id']))
				 ->update(tname('qcgj_role_user'), $updateArr);

		if (!$queryRes) {
			$this->returnRes['msg'] = $this->lang->line('ERR_UPDATE_FAILURE');
		}else{
			$this->returnRes['error'] = false;
		}

		return $this->returnRes;
	}

	/**
	 * 获取权限列表
	 *
	 */
	public function getRoleList($pageNum = 1, $pageCount = 10){

		$pageNum = abs(($pageNum - 1) * $pageCount);
		$limit = ' LIMIT '.$pageNum.','.$pageCount;

		$sql = "SELECT  a.user_id, 
						a.name, 
						b.name AS role_name, 
						a.created_time, 
						a.status 
					FROM ".tname('qcgj_role_user')." AS a
		 			LEFT JOIN ".tname('qcgj_role')." AS b ON b.role_id = a.role_id ";

		$queryTotal = $this->db->count_all(tname('qcgj_role_user'));
		$queryRes = $this->db->query($sql.$limit)->result();

		$this->returnRes = array(
				'error' => false,
				'data'  => array(
						'total'  => $queryTotal,
						'result' => $queryRes,
					),
			);

		return $this->returnRes;
	}

	/**
	 * 获取规则列表
	 *
	 */
	public function getRuleList(){
		
		$field = 'id, module,module_title, action_title';
		
		$queryRes = $this->db->select($field)
							 ->order_by('sort', 'DESC')
							 ->get_where(tname('qcgj_role_rule'), array('status' => 1))
							 ->result();
		$ruleList = array();

		foreach ($queryRes as $k => $v) {
			if (!in_array($v->module, array_keys($ruleList))) {
				$ruleList[$v->module] = array(
						'module' => $v->module,
						'title'  => $v->module_title,
					);
			}

			$ruleList[$v->module]['list'][] = array(
						'id'    => $v->id,
						'title' => $v->action_title,
				);
		}

		return $ruleList;
	}

	/**
	 * 设置分页
	 */
	public function setPagination($url = false, $total = 1, $pageNum = 10){
		$pageConf = array(
				'base_url'   => $url,
				'total_rows' => $total,
				'per_page'   => $pageNum,
			);

		$this->pagination->initialize($pageConf);

		return $this->pagination->create_links();
	}

	/**
	 * 验证权限添加
	 *
	 */
	public function verlidationAddRule($verlidationConf = array()){
		$this->form_validation->set_rules($verlidationConf);

		if (!$this->form_validation->run()) {
			return validation_errors();
		}

		return true;
	}
}
