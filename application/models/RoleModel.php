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

		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.SEARCH_BRAND_LIST').md5($brandName));
		
		if ($cacheRes) return $cacheRes; 

		$list = $this->db->select('name_zh, name_en')
						 ->like('name_zh', $brandName)
						 ->or_like('name_en', $brandName)
						 ->order_by('name_en, name_zh ASC')
						 ->limit(20)
						 ->get(tname('brand'))
						 ->result();

		$returnList = array();

		foreach ($list as $k => $v) {
			array_push($returnList, $v->name_en.'_'.$v->name_zh);
		}

		if (count($returnList)) $this->cache->save(config_item('NORMAL_CACHE.SEARCH_BRAND_LIST').md5($brandName), $returnList, 3600); 

		return $returnList;
	}

	/**
	 * 根据品牌名搜索店铺列表
	 * @param string $brandEn 品牌英文名
	 * @param string $brandZh 品牌中文名
	 */
	public function searchMallByBrand($brandEn = false, $brandZh = false){

		$sql = "SELECT 
					CONCAT(c.city_name, '-',c.name_zh, b.address) AS name,
					c.id AS mallId
					FROM ".tname('brand')." AS a 
					INNER JOIN ".tname('brand_mall')." AS b ON b.tb_brand_id = a.id
					INNER JOIN ".tname('mall')." AS c ON c.id = b.tb_mall_id
					WHERE a.name_zh = '".$brandZh."' AND a.name_en = '".$brandEn."' 
					ORDER BY c.city_name";

		$queryRes = $this->db->query($sql)->result();

		$html = '';

		foreach ($queryRes as $k => $v) {
			$html .= '<option value="'.$v->mallId.'">'.$v->name.'</option>';
		}

		return $html;
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
				'name'         => $addRoleUserData['roleUsername'],
				'passwd'	   => md5($addRoleUserData['passwd']),
				'status'       => 1,
				'created_time' => currentTime(),
			);

		$queryRes = $this->db->insert(tname('qcgj_role_user'), $insertData);
		
		if (!$queryRes) {
			$this->returnRes['msg'] = $this->lang->line('ERR_ADD_FAILURE');
		}else{
			$this->returnRes['error'] = false;
			
			$brandId = $this->getBrandIdWithName($addRoleUserData['brandName']);
			
			$insertBrand = array(
						'user_id'  => $insertData['user_id'],
						'brand_id' => $brandId,
				);

			if ($addRoleUserData['role_id'] == 3) {
				$insertBrand['mall_id'] = $addRoleUserData['mallId'];
			}

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
	 * 删除用户
	 * @param string $userId 用户id
	 */
	public function delUser($userId = false){

		$delRes = $this->db->delete(tname('qcgj_role_user'), array('user_id' => $userId));

		if (!$delRes) $this->returnRes['msg'] = $this->lang->line('ERR_DELETE_FAILURE'); else $this->returnRes['error'] = false;

		return $this->returnRes;
	}

	/**
	 *  获取用户详情
	 * @param string $userId 用户id
	 */
	public function getUserDetail($userId = false){
		$sql = "SELECT 
					a.name AS userName, 
					a.role_id, 
					IF(c.brand_id is null, '', (SELECT CONCAT(name_zh, '_', name_en) FROM ".tname('brand')." WHERE id = c.brand_id)) AS brandName,
					c.brand_id
					FROM ".tname('qcgj_role_user')." AS a 
					LEFT JOIN ".tname('qcgj_role')." AS b ON b.role_id = a.role_id
					LEFT JOIN ".tname('qcgj_role_brand_mall')." AS c ON c.user_id = a.user_id
					WHERE a.user_id = '%s'";

		$queryRes = $this->db->query(sprintf($sql, $userId))->first_row();

		return $queryRes;
	}

	/**
	 * 获取用户列表
	 *
	 */
	public function getRoleUserList($pageNum = 1, $where, $pageCount = 10){

		$pageNum = abs(($pageNum - 1) * $pageCount);
		$limit = ' LIMIT '.$pageNum.','.$pageCount;

		$field = " a.user_id, 
						a.name, 
						b.name AS role_name, 
						a.created_time, 
						a.status ";
		$countField = " COUNT(*) AS total ";

		$sql = "SELECT %s
					FROM ".tname('qcgj_role_user')." AS a
		 			LEFT JOIN ".tname('qcgj_role')." AS b ON b.role_id = a.role_id 
		 			 %s
		 			 ORDER BY a.created_time DESC 
		 			 %s ";


		$queryTotal = $this->db->query(sprintf($sql, $countField, $where, ''))->first_row();
		$queryRes = $this->db->query(sprintf($sql, $field, $where, $limit))->result();

		$this->returnRes = array(
				'error' => false,
				'data'  => array(
						'total'  => $queryTotal->total,
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

		$queryRes = $this->db->get_where(tname('qcgj_role_user'), array('name' => $reqData['roleUsername']))->result();

		if (count($queryRes) > 0) {
			return $this->lang->line('ERR_ADDROLE_USERNAME_EXISTS');
		}

		$queryRes = $this->db->select('role_id')
							 ->get_where(tname('qcgj_role'), array('role_id' => $reqData['role_id'], 'status' => 1))
							 ->first_row();

		if ($queryRes->role_id != $reqData['role_id']) {
			return $this->lang->line('ERR_ADD_FAILURE');
		}

		$brandId = $this->getBrandIdWithName($reqData['brandName']);

		// 验证品牌和店铺
		if (in_array($reqData['role_id'], array(2, 3))) {
			if (!$this->existsBrand($brandId)) return $this->lang->line('ERR_ROLE_NO_BRAND');

			if ($reqData['role_id'] == 3) {
				if (!$this->existsMall($brandId, $reqData['mallId'])) return $this->lang->line('ERR_ROLE_NO_MALL');
			}
		}

		return true;
	}

	/**
	 * 根据品牌名获取品牌id
	 * @param string $brandName 拼接品牌名 NIKE_耐克
	 */
	public function getBrandIdWithName($brandName = false){
		if (empty($brandName)) return '';

		$brandName = explode('_', $brandName);

		if (count($brandName) != 2) return ''; 

		$where = array(
				'name_zh' => $brandName[1],
				'name_en' => $brandName[0],
			);

		$queryRes = $this->db->select('id')->get_where(tname('brand'), $where)->first_row();

		return is_string($queryRes->id) ? $queryRes->id : '';
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
