<?php
/**
 * 折扣模型
 */

class DiscountModel extends CI_Model {
	
	// 返回数据
	public $returnRes;

	public function __construct(){
		$this->returnRes = array(
							'error' => true, // true=有错误, false=正确
							'msg'   => false, 
							'data'  => array()
						);

		$this->isAdmin = $this->isAdminUser($this->userInfo->role_id);
	}

	/**
	 * 获取折扣列表
	 * @param int $page 页码
	 * @param string $where 查询条件
	 */
	public function getDiscountList($page = 1, $where = NULL){

		$totalField = " COUNT(*) AS total ";
		$field = " 	id,
					brand_pic_url,
					name_zh,
					type,
					LEFT(begin_date, 10) AS begin_date, 
					LEFT(end_date, 10) AS end_date ,
					CONCAT(brand_name_en, '<br>', brand_name_zh) AS brand,
					category_name,
					tb_brand_id,
					LEFT(discount_desc, 30) AS discount_desc ";

		$page = ($page - 1) * 25;

		$limit = ' LIMIT '.$page.',25';

		$sql = "SELECT %s FROM ".tname('discount')." %s ORDER BY create_time DESC %s ";

		$queryTotal = $this->db->query(sprintf($sql, $totalField, $where, ''))->first_row();

		$queryRes = $this->db->query(sprintf($sql, $field, $where, $limit))->result_array();

		$returnData = array(
				'list' => $queryRes,
				'page' => $this->setPagination(site_url('Discount/'.$this->router->method), $queryTotal->total, 25),
			);

		return $returnData;
	}

	/**
	 * 获取品牌分类
	 *
	 */
	public function getBrandCate(){
		
		$where = array(
				'level' => 1,
				'type'  => 1,
			);

		return $this->db->select('id, name')->get_where(tname('category'), $where)->result_array();
	}

	/**
	 * 删除折扣信息
	 * @param string $discountId 折扣id
	 * @param string $brandId 品牌id
	 * @return mixed
	 */
	public function delDiscountById($discountId = false, $brandId = false){

		$where = array(
				'id'          => $discountId,
				'tb_brand_id' => $brandId,
			);

		if ($this->isAdmin === true) {
 			unset($where['tb_brand_id']);
		}else{
			if ($brandId != $this->userInfo->brand_id) return false; 
		}

		$queryRes = $this->db->where($where)->update(tname('discount'), array('is_delete' => 1));		

		return $queryRes ? true : false;
	}

	/**
	 * 获取品牌名
	 * @param string $brandId 品牌id
	 */
	public function getBrandName($brandId = false){
		$sql = "SELECT CONCAT(name_en, ' ', name_zh) AS name FROM ".tname('brand')." WHERE id = '".$brandId."' ";
		$queryRes = $this->db->query($sql)->first_row();

		return $queryRes->name;
	}

	/**
	 * 获取品牌宣传图
	 * @param string $brandId 品牌id
	 */
	public function getBrandImg($brandId = false){
		
		$where = array(
				'id' => $brandId,
			);

		if (!$this->isAdmin) {
			$where['id'] = $this->userInfo->brand_id;
		}

		$queryRes = $this->db->select('pic_url')->get_where(tname('brand'), $where)->first_row();

		$brandImg = $this->_formatBrandImg($queryRes->pic_url);

		return $brandImg;
	}

	/**
	 * 格式化
	 * @param string $picUrl 多张图片路径
	 */
	private function _formatBrandImg($picUrl = false){
		 $img = explode(',', $picUrl);

		 $imgArr = array();

		 foreach ($img as $k => $v) {

		 	if (empty($v) || !is_string($v)) continue;

		 	$tmpImg = preg_replace('/\|\d+\|\d+/', '', $v);

		 	if (empty($tmpImg)) continue;

		 	array_push($imgArr, $tmpImg);
		 }

		return $imgArr;
	}











}
