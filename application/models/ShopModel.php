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
	 */
	public function getShopList($where = NULL, $order = NULL, $pageCount = 25, $pageNum = 1){

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
					echo sprintf($sql, $totalField, $where, $order, '');exit;
		$queryTotal = $this->db->query(sprintf($sql, $totalField, $where, $order, ''))->result();
		echo '<pre>';
		print_r($queryTotal);exit;
		$queryRes = $this->db->query(sprintf($sql, $field, $where, $order, $limit))->result();

		$this->returnRes = array(
				'error' => false,
				'msg'   => false,
				'data'  => array(
					'list'  => $queryRes,
					'total' => $queryTotal,
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
