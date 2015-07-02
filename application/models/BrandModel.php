<?php
/**
 * 品牌管理模型
 */

class BrandModel extends CI_Model {
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
	 * 获取品牌列表
	 * @param string $where 查询条件
	 * @param int $p 页数
	 */
	public function getBrandList($where = NULL, $p = 1){

		$limit = "LIMIT ".page($p, 25);
		
		$field = " 	id, 
					name_en, 
					name_zh, 
					logo_url, 
					create_time, 
					update_time, 
					LEFT(description, 50) AS summary, 
					oper ";

		$sql = "SELECT %s FROM ".tname('brand')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('Brand/listView'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);

		return $returnRes;
	}

	/**
	 * 删除品牌
	 * @param string $brandId 品牌id
	 */
	public function delBrand($brandId = false){
		
		$where = array('id' => $brandId);

		return $this->db->delete(tname('brand'), $where);
	}
}
