<?php
/**
 * 新虹桥模型
 */

class HongQiaoModel extends CI_Model {
	
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
	 * 电影院列表
	 * @param int $p 页码
	 */
	public function getCinemaList($p = 1){
		
		$where = array(
				'area' => '长宁区',
			);

		$queryRes = $this->db->get_where(tname('cinema'), $where)->result();

		$pagination = $this->setPagination(site_url('HongQiao/cinemaList'), count($queryRes), 25);

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);

		return $returnRes;
	}

	/**
	 * 景点列表
	 * @param int $p 页码
	 */
	public function getTravelList($where = NULL, $p = 1){

		$limit = "LIMIT ".page($p, 25);
		
		$field = " * ";

		$sql = "SELECT %s FROM ".tname('travel')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('HongQiao/travelList'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);
		
		return $returnRes;
	}

	/**
	 * 餐厅列表
	 * @param int $p 页码
	 */
	public function getRestaurantList($where = NULL, $p = 1){

		$limit = "LIMIT ".page($p, 25);
		
		$field = " * ";

		$sql = "SELECT %s FROM ".tname('restaurant')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('HongQiao/restaurantList'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);
		
		return $returnRes;
	}

	/**
	 * 获取景点详细内容
	 * @param string $id
	 */
	public function getTravelDetail($id = false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('travel'), $where)->first_row();

		return $queryRes;
	}

	/**
	 * 获取餐厅详细信息
	 * @param string $id 餐厅id
	 */
	public function getRestaurantDetail($id = false){
		
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('restaurant'), $where)->first_row();

		return $queryRes;
	}

	/**
	 * 获取电影院详情
	 * @param string $id 电影院id
	 */
	public function getCinemaDetail($id = false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('cinema'), $where)->first_row();

		$path = json_decode($queryRes->text, true);

		foreach ($path['img'] as $j => $m) {
			$arr = explode('/', $m);
			$num = count($arr) - 1;
			$queryRes->pic[] = $arr[$num];
		}

		return $queryRes;
	}

	/**
	 * 检测餐厅id权限
	 * @param string $id 餐厅id
	 */
	public function checkEditRestaurant($id = false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('restaurant'), $where)->first_row();

		return count($queryRes) > 0 ? true : false;	
	}

	/**
	 * 检测景点id权限
	 * @param string $id 景点id
	 */
	public function checkEditTravel($id = false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('travel'), $where)->first_row();

		return count($queryRes) > 0 ? true : false;	
	}

	/**
	 * 检测电影院id权限
	 * @param string $id 电影院id
	 */
	public function checkEditCinema($id = false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('cinema'), $where)->first_row();

		return count($queryRes) > 0 ? true : false;	
	}

}
