<?php
/**
 * 门店管理模型
 */

class ShopModel extends CI_Model {
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
	 * 获取门店列表
	 * @param string $where 查询条件
	 * @param string $order 排序条件
	 * @param int $pageNum 页码
	 * @param int $pageCount 条数
	 */
	public function getShopList($where = NULL, $order = NULL, $pageNum = 1, $pageCount = 25){

		$pageNum = ($pageNum - 1) * $pageCount;

		$limit = ' LIMIT '.$pageNum.','.$pageCount;

		$field = 'b.id AS brandId,
					b.name_zh AS brandName,
					e.name AS categoryName,
					c.name_zh AS mallName,
					c.address,
					c.trade_area_name AS areaName,
					c.city_name AS cityName,
					a.address AS floor ';

		$totalField = ' COUNT(*) AS total ';

		$sql = "SELECT %s
					FROM ".tname('qcgj_brand_mall')." AS a 
					LEFT JOIN ".tname('qcgj_brand')." AS b ON b.id = a.tb_brand_id
					LEFT JOIN ".tname('qcgj_mall')." AS c ON c.id  = a.tb_mall_id
					LEFT JOIN ".tname('qcgj_brand_category')." AS d ON d.tb_brand_id = a.tb_brand_id
					LEFT JOIN ".tname('qcgj_category')." AS e ON e.id = d.tb_category_id 
					%s 
					%s 
					%s ";

		$where = $this->_checkUserBrand($where);

		if (empty($where)) {
			return $this->_return(null, array('list' => array(), 'total' => 0), false);
		}

		$queryTotal = $this->db->query(sprintf($sql, $totalField, $where, $order, ''))->first_row();

		$queryRes = $this->db->query(sprintf($sql, $field, $where, $order, $limit))->result();
		// echo $this->db->last_query();exit;
		$this->returnRes = array(
				'error' => false,
				'msg'   => false,
				'data'  => array(
					'list'  => $queryRes,
					'total' => $queryTotal->total,
				),
			);

		return $this->returnRes;
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
	 * 获取店长列表
	 * @param int $pageNum 页数
	 * @param int $pageCount 条数
	 */
	public function getManagerList($pageNum = 1, $pageCount = 15){
		$pageNum = ($pageNum - 1) * $pageCount;

		$limit = " LIMIT ".$pageNum.",".$pageCount;
		$field = "	a.user_id,
					b.name AS userName,
					c.name_zh AS mallName,
					c.address,
					c.trade_area_name AS areaName,
					c.city_name AS cityName ";

		$sql = "SELECT %s
					 FROM ".tname('qcgj_role_brand_mall')." AS a 
					LEFT JOIN ".tname('qcgj_role_user')." AS b ON b.user_id = a.user_id 
					LEFT JOIN ".tname('qcgj_mall')." AS c ON c.id = a.mall_id 
					WHERE a.brand_id = '".$this->userInfo->brand_id."' 
					AND a.user_id != '".$this->userInfo->user_id."' 
					AND a.mall_id != '' ";
		$queryRes = $this->db->query(sprintf($sql, $field).$limit)->result();

		$queryTotal = $this->db->query(sprintf($sql, " COUNT(*) AS total "))->first_row();

		$this->returnRes = array(
				'error' => false,
				'msg'   => false,
				'data'  => array(
					'list'  => $queryRes,
					'page' => $this->setPagination(site_url('Shop/managerList'), $queryTotal->total),
				),
			);

		return $this->returnRes;

	}

	/**
	 * 设置分页
	 */
	public function setPagination($url = false, $total = 1, $prePage = 15){
		$pageConfig = array(
				'base_url'   => $url,
				'total_rows' => $total,
				'pre_page'   => $prePage,
			);

		$this->pagination->initialize($pageConfig);

		return $this->pagination->create_links();
	}

	/**
	 * 设置店铺查询条件
	 * @param string $where 查询条件
	 */
	private function _checkUserBrand($where = null){
		// 判断超级管理员
		$ruleArr = array(1);
		if (in_array($this->userInfo->role_id, $ruleArr)) {
			return $where;
		}

		if (isset($this->userInfo->brand_id) && !empty($this->userInfo->brand_id)) {
			$brandEmpty = true;
			$where .= " AND b.id = '".$this->userInfo->brand_id."' ";
		}

		if (isset($this->userInfo->mall_id) && !empty($this->userInfo->mall_id)) {
			$mallEmpty = true;
			$where .= " AND c.id = '".$this->userInfo->mall_id."' ";
		}

		if (!isset($mallEmpty) && !isset($brandEmpty)) {
			return null;
		}

		return $where;
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
