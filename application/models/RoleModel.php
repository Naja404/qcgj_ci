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
	 * 搜索品牌
	 * @param string $brandName 品牌名
	 */
	public function searchBrand($brandName = false){
		if (!$brandName) return false;

		$list = $this->db->select('name_zh, name_en')
						 ->like('name_zh', $brandName)
						 ->or_like('name_en', $brandName)
						 ->order_by('name_en, name_zh ASC')
						 ->get(tname('brand'))
						 ->result();

		$returnList = array();

		foreach ($list as $k => $v) {
			array_push($returnList, $v->name_en.$v->name_zh);
		}

		return $returnList;
	}

	/**
	 * 获取品牌列表
	 *
	 */
	public function getBrandList(){
		
		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.BRAND_LIST'));

		if ($cacheRes) return $cacheRes;

		$queryRes = $this->db->select("id,name_en,name_zh")->get(tname('brand'))->result();

		if (count($queryRes)) $this->cache->save(config_item('NORMAL_CACHE.BRAND_LIST'), $queryRes);

		return $queryRes;
	}

	/**
	 * 获取店铺地址列表
	 * @param string $brandId 品牌列表
	 */
	public function getMallList($brandId = false){
		
		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.MALLLIST_BY_BRAND').$brandId);
		
		if ($cacheRes) return $cacheRes;
		
		$sql = "SELECT b.id, 
					   b.name_zh, 
					   b.address 
					   FROM ".tname('brand_mall')." AS a 
					LEFT JOIN ".tname('mall')." AS b ON b.id = a.tb_mall_id 
					WHERE a.tb_brand_id = '".$brandId."' ";
		
		$queryRes = $this->db->query($sql)->result();

		if (count($queryRes)) $this->cache->save(config_item('NORMAL_CACHE.MALLLIST_BY_BRAND').$brandId, $queryRes);

		return $queryRes;
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
	 * 添加角色
	 * @param array $addRoleData 角色数组
	 */
	public function addRole($addRoleData = array()){
		$insertData = array(
				'name'   => $addRoleData['role_name'],
				'rule'   => implode(',', $addRoleData['role_rule']),
				'status' => 1,
			);

		$queryRes = $this->db->insert(tname('qcgj_role'), $insertData);

		if (!$queryRes) {
			$this->returnRes['msg'] = $this->lang->line('ERR_ADD_FAILURE');
		}else{
			$this->returnRes['error'] = false;
		}

		return $this->returnRes;
	}

	/**
	 * 添加角色用户
	 * @param array $addRoleUserData 角色用户数组
	 */
	public function addRoleUser($addRoleUserData = array()){
		$insertData = array(
				'user_id'      => makeUUID(),
				'role_id'      => $addRoleUserData['role_id'],
				'name'         => $addRoleUserData['role_username'],
				'passwd'	   => md5($addRoleUserData['passwd']),
				'status'       => 1,
				'created_time' => currentTime(),
			);

		$queryRes = $this->db->insert(tname('qcgj_role_user'), $insertData);
		
		if (!$queryRes) {
			$this->returnRes['msg'] = $this->lang->line('ERR_ADD_FAILURE');
		}else{
			$this->returnRes['error'] = false;
			
			$insertBrand = array(
						'user_id'  => $insertData['user_id'],
						'brand_id' => $addRoleUserData['brandId'] ? strDecrypt($addRoleUserData['brandId']) : '',
						'mall_id'   => $addRoleUserData['mallId'] ? strDecrypt($addRoleUserData['mallId']) : '',
				);

			$this->db->insert(tname('qcgj_role_brand_mall'), $insertBrand);
		}

		return $this->returnRes;
	}

	/**
	 * 获取角色列表
	 */
	public function getRoleList(){
		$queryRes = $this->db->select('role_id, name')->get_where(tname('qcgj_role', array('status' => 1)))->result();

		return $queryRes;
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
			$this->returnRes['html'] = $this->lang->line('TEXT_UPDATE_USER_STATUS_'.$updateArr['status']);
			$this->returnRes['class'] = $this->lang->line('TEXT_UPDATE_USERSTATUS_CLASS_'.$updateArr['status']);
		}

		return $this->returnRes;
	}

	/**
	 * 获取用户列表
	 *
	 */
	public function getRoleUserList($pageNum = 1, $pageCount = 10){

		$pageNum = abs(($pageNum - 1) * $pageCount);
		$limit = ' LIMIT '.$pageNum.','.$pageCount;

		$sql = "SELECT  a.user_id, 
						a.name, 
						b.name AS role_name, 
						a.created_time, 
						a.status 
					FROM ".tname('qcgj_role_user')." AS a
		 			LEFT JOIN ".tname('qcgj_role')." AS b ON b.role_id = a.role_id ORDER BY a.created_time DESC";

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
	 * @param array $verlidationConf 表单验证配置内容
	 */
	public function verlidationAddRule($verlidationConf = array()){
		$this->form_validation->set_rules($verlidationConf);

		if (!$this->form_validation->run()) {
			return validation_errors();
		}

		return true;
	}

	/**
	 * 验证角色添加
	 * @param array $verlidationConf 表单验证配置内容
	 * @param array $reqData ajax数据内容
	 */
	public function verlidationAddRole($verlidationConf = array(), $reqData = array()){
		$this->form_validation->set_rules($verlidationConf);

		if (!$this->form_validation->run()) {
			return validation_errors();
		}

		$queryRes = $this->db->get_where(tname('qcgj_role'), array('name' => $reqData['role_name']))->result();

		if (count($queryRes) > 0) {
			return $this->lang->line('ERR_ADD_ROLENAME_EXISTS');
		}

		if (!is_array($reqData['role_rule'])) {
			return $this->lang->line('ERR_ROLE_RULE');
		}

		$roleRule = array();
		foreach ($reqData['role_rule'] as $k => $v) {
			if (!is_numeric($v) || empty($v)) {
				return $this->lang->line('ERR_ROLE_RULE');
			}
			array_push($roleRule, $v);
		}

		$queryRes = $this->db->select('SUM(status) AS count')
							 ->where(array('status' => 1))
							 ->where_in('id', $roleRule)
							 ->get(tname('qcgj_role_rule'))
							 ->first_row();
		if ($queryRes->count < count($roleRule)) {
			return $this->lang->line('ERR_ROLE_RULE');
		}

		return true;
	}

	/**
	 * 验证角色用户
	 * @param array $verlidationConf 表单验证配置内容
	 * @param array $reqData ajax数据内容
	 */
	public function verlidationAddRoleUser($verlidationConf = array(), $reqData = array()){

		$this->form_validation->set_rules($verlidationConf);

		if (!$this->form_validation->run()) {
			return validation_errors();
		}

		$queryRes = $this->db->get_where(tname('qcgj_role_user'), array('name' => $reqData['role_username']))->result();

		if (count($queryRes) > 0) {
			return $this->lang->line('ERR_ADDROLE_USERNAME_EXISTS');
		}

		$queryRes = $this->db->select('role_id')
							 ->get_where(tname('qcgj_role'), array('role_id' => $reqData['role_id'], 'status' => 1))
							 ->first_row();

		if ($queryRes->role_id != $reqData['role_id']) {
			return $this->lang->line('ERR_ADD_FAILURE');
		}

		// 验证品牌和店铺
		if (in_array($reqData['role_id'], array(2, 3))) {
			if (!$this->existsBrand(strDecrypt($reqData['brandId']))) return $this->lang->line('ERR_ROLE_NO_BRAND');

			if ($reqData['role_id'] == 3) {
				if (!$this->existsMall(strDecrypt($reqData['brandId']), strDecrypt($reqData['mallId']))) return $this->lang->line('ERR_ROLE_NO_MALL');
			}
		}

		return true;
	}

	/**
	 * 检测品牌是否存在
	 * @param string $brandId 品牌id
	 */
	public function existsBrand($brandId = false){
		$where = array(
				'id' => $brandId,
			);

		$queryRes = $this->db->get_where(tname('brand'), $where)->result();

		return count($queryRes) > 0 ? true : false;
	}

	/**
	 * 检测品牌下的店铺是否存在
	 * @param string $brandId 品牌id
	 * @param string $mallId 店铺id
	 */
	public function existsMall($brandId = false, $mallId = false){
		$where = array(
				'tb_brand_id' => $brandId,
				'tb_mall_id'  => $mallId,
			);

		$queryRes = $this->db->get_where(tname('brand_mall'), $where)->result();

		return count($queryRes) > 0 ? true : false;
	}
}
