<?php
/**
 * web基类模型
 */

class WebBaseModel extends CI_Model {

	// 返回数据
	public $returnRes;

	public $userID;

	public $sessionSSID;

	public $userInfo;

	public function __construct(){
		
		$this->returnRes = array(
							'error' => true, // true=有错误, false=正确
							'msg'   => false, 
							'data'  => array()
							);

		$this->load->database();
	}

	/**
	 * 检测用户登录权限
	 * @param array $sessionInfo 会话信息
	 */
	public function checkAuth($sessionInfo = array()){

		if (empty($sessionInfo['sessionSSID']) || empty($sessionInfo['sessionUser'])) {
			return $this->_return($this->lang->line('ERR_PLEASE_LOGIN'));
		}

		$this->userID = $sessionInfo['sessionUser'];
		$this->sessionSSID = $sessionInfo['sessionSSID'];

		$this->userInfo = $this->cache->get(config_item('USER_CACHE.LOGIN').$this->userID);
		// 验证用户登录状态
		$checkLoginRes = $this->checkLogin();

		if (is_string($checkLoginRes) || $checkLoginRes !== true) {
			return $this->_return($checkLoginRes);
		}

		// 验证用户权限
		$checkUserAuthRes = $this->checkUserAuth();

		if (is_string($checkUserAuthRes) || $checkUserAuthRes !== true) {
			return $this->_return($checkUserAuthRes, array('url' => site_url()));
		}

		return $this->_return(NULL, $this->userInfo, FALSE);
	}

	/**
	 * 验证用户登录状态
	 */
	public function checkLogin(){

		if (isset($this->userInfo->user_id) && $this->userInfo->user_id != $this->userID) {
			return $this->lang->line('ERR_TIMEOUT_LOGIN');
		}

		if (isset($this->userInfo->sessionSSID) && $this->userInfo->sessionSSID != $this->sessionSSID) {
			return $this->lang->line('ERR_TIMEOUT_LOGIN');
		}

		return true;
	}

	/**
	 * 验证用户权限
	 */
	public function checkUserAuth(){
		
		$sql = "SELECT 
					c.action_url AS rule 
					FROM ".tname('qcgj_role_user')." AS a 
					LEFT JOIN ".tname('qcgj_role')." AS b ON b.role_id = a.role_id
					LEFT JOIN ".tname('qcgj_role_rule')." AS c ON FIND_IN_SET(c.id, b.rule)
					WHERE a.user_id = '".$this->userID."' 
						AND c.status = 1 
					ORDER BY c.sort DESC";
		$queryRes = $this->db->query($sql)->result_array();

		$ruleArr = array();

		foreach ($queryRes as $k => $v) {
			array_push($ruleArr, $v['rule']);
		}

		$ruleURI = $this->router->class.'/'.$this->router->method;

		if (!in_array($ruleURI, $ruleArr)) {
			return $this->lang->line('ERR_NOT_ALLOW');
		}

		$this->cache->save(config_item('USER_CACHE.RULE').$this->userID, $ruleArr, config_item('USER_CACHE.DEFAULT_EXPIRETIME'));

		return true;

	}

	/**
	 * 获取权限数组列表
	 * @param string $userID 用户id
	 * @return array
	 */
	public function getSideBar($userID = false){

		$cacheSideBar = $this->cache->get(config_item('USER_CACHE.MENU').$userID);

		if (is_array($cacheSideBar) && count($cacheSideBar)) {
			return $cacheSideBar;
		}

		$sql = "SELECT 
					c.module, 
					c.module_title, 
					c.action_title, 
					c.action_url 
					FROM ".tname('qcgj_role_user')." AS a 
					LEFT JOIN ".tname('qcgj_role')." AS b ON b.role_id = a.role_id
					LEFT JOIN ".tname('qcgj_role_rule')." AS c ON FIND_IN_SET(c.id, b.rule)
					WHERE a.user_id = '".$userID."' 
						AND c.type = 1 
						AND c.status = 1 
					ORDER BY c.sort DESC";
		$queryRes = $this->db->query($sql)->result();

		$sideBarArr = array();
		
		foreach ($queryRes as $k => $v) {
			if (!in_array($v->module, array_keys($sideBarArr))) {
				$sideBarArr[$v->module] = array(
						'title' => $v->module_title,
						'module' => $v->module,
					);
			}

			$sideBarArr[$v->module]['list'][] = array(
						'url'   => $v->action_url,
						'title' => $v->action_title,
				);
		}

		$this->cache->save(config_item('USER_CACHE.MENU').$userID, $sideBarArr, config_item('USER_CACHE.DEFAULT_EXPIRETIME'));

		return $sideBarArr;
	}

	/**
	 * model 返回内容
	 * @param string $msg 返回信息内容
	 * @param array $data 数据内容
	 * @param bool $error 返回状态
	 */
	private function _return($msg = false, $data = array(), $error = true){

		$this->returnRes = array(
					'error' => $error,
					'msg'   => $msg,
					'data'  => $data,
			);

		return $this->returnRes;
	}
}
