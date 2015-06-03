<?php
/**
 * web基类模型
 */

class WebBaseModel extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

	/**
	 * 检测用户登录权限
	 * @param array $sessionInfo 会话信息
	 */
	public function checkAuth($sessionInfo = array()){
		// 验证用户登录状态
		$this->checkLogin($sessionInfo);
	}

	/**
	 * 验证用户登录状态
	 * @param array $sessionInfo 会话信息
	 */
	public function checkLogin($sessionInfo = array()){
		$user_id = strDecrypt($sessionInfo['sessionUser']);
		$userInfo = $this->cache->get(config_item('USER_CACHE.LOGIN').$user_id);

		if ($userInfo) {
			
		}
	}

	/**
	 * 获取权限数组列表
	 * @param string $userID 用户id
	 * @return array
	 */
	public function getSideBar($userID = false){
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

		return $sideBarArr;
	}
}
