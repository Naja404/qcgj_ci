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
	 * 添加折扣信息
	 * @param array $reqData 折扣数据
	 */
	public function addDis($reqData = array()){

		if ($this->isAdmin) {
			$brandId = $reqData['brandId'];
		}else{
			$brandId = $this->userInfo->brand_id;
		}

		$date = $this->_formatDiscountDate($reqData['discountDate']);
		
		$addDis = array(
				'id'             => makeUUID(),
				'name_zh'        => addslashes($reqData['discountTitle']),
				'brand_pic_url'  => $reqData['discountImg'],
				'status'         => 0,
				'create_time'    => currentTime(),
				'update_time'    => currentTime(),
				'tb_category_id' => $reqData['discountCate'],
				'category_name'  => $this->getCateNameById($reqData['discountCate']),
				'tb_brand_id'    => $brandId,
				'brand_name_en'  => $this->getBrandNameById($brandId, 'EN'),
				'brand_name_zh'  => $this->getBrandNameById($brandId, 'ZH'),
				'discount_desc'  => $reqData['discountDescription'],
				'begin_date'     => currentTime('', $date[0]),
				'end_date'       => currentTime('', $date[1]),
				'type'			 => (int)$reqData['discountType'],
				'is_delete'		 => 0,
				'oper'			 => $this->userInfo->user_id,
				'is_top'		 => 0,
			);

		// 添加折扣
		$addDisRes = $this->db->insert(tname('discount'), $addDis);

		if (!$addDisRes) {
			$this->returnRes['msg'] = $this->lang->line('ERR_DISCOUNT_ADD_FAILURE');
			return $this->returnRes;
		}

		// 添加商场
		if (is_array($reqData['mallID']) && count($reqData['mallID'])) {
			$disInsertMall = $tmpMallArr = array();

			foreach ($reqData['mallID'] as $k => $v) {

				if (in_array($v, $tmpMallArr)) {
					continue;
				}

				$disInsertMall[] = array(
						'id'             => makeUUID(),
						'tb_discount_id' => $addDis['id'],
						'tb_mall_id'     => $v,
						'address'        => $this->getMallFloorById($v, $brandId),
						'create_time'    => currentTime(),
						'update_time'    => currentTime(),
					);
			}

			$this->db->insert_batch(tname('discount_mall'), $disInsertMall);
		}

		$this->returnRes['error'] = false;
	}

	/**
	 * 编辑折扣信息
	 * @param array $reqData 折扣数据
	 */
	public function editDis($reqData = array()){

		$date = $this->_formatDiscountDate($reqData['discountDate']);
		
		$editDis = array(
				'name_zh'        => addslashes($reqData['discountTitle']),
				'brand_pic_url'  => $reqData['discountImg'],
				'update_time'    => currentTime(),
				'tb_category_id' => $reqData['discountCate'],
				'category_name'  => $this->getCateNameById($reqData['discountCate']),
				'discount_desc'  => $reqData['discountDescription'],
				'begin_date'     => currentTime('', $date[0]),
				'end_date'       => currentTime('', $date[1]),
				'type'			 => (int)$reqData['discountType'],
				'update_time'    => currentTime(),
			);

		if (!$this->isAdmin) $editDis['oper'] = $this->userInfo->user_id;

		$where = array(
				'id'          => $reqData['discountId'],
				'tb_brand_id' => $reqData['brandId'],
			);

		// 更新折扣
		$editDisRes = $this->db->where($where)->update(tname('discount'), $editDis);

		if (!$editDisRes) {
			$this->returnRes['msg'] = $this->lang->line('ERR_DISCOUNT_UPDATE_FAILURE');
			return $this->returnRes;
		}

		// 更新商场
		if (is_array($reqData['mallID']) && count($reqData['mallID'])) {

			$this->db->where(array('tb_discount_id' => $reqData['discountId']))->delete(tname('discount_mall'));

			$disInsertMall = $tmpMallArr = array();

			foreach ($reqData['mallID'] as $k => $v) {

				if (in_array($v, $tmpMallArr)) {
					continue;
				}

				$disInsertMall[] = array(
						'id'             => makeUUID(),
						'tb_discount_id' => $reqData['discountId'],
						'tb_mall_id'     => $v,
						'address'        => $this->getMallFloorById($v, $reqData['brandId']),
						'create_time'    => currentTime(),
						'update_time'    => currentTime(),
					);
			}

			$this->db->insert_batch(tname('discount_mall'), $disInsertMall);
		}

		$this->returnRes['error'] = false;
	}

	/**
	 * 获取折扣详情
	 * @param string $discountId 折扣id
	 */
	public function getDiscountDetail($discountId = false){
		
		$where = array(
				'id' => $discountId,
			);

		$queryRes = $this->db->get_where(tname('discount'), $where)->first_row();

		$mallQueryRes = $this->db->select('tb_mall_id')->get_where(tname('discount_mall'), array('tb_discount_id' => $discountId))->result();

		$queryRes->mallID = array();

		foreach ($mallQueryRes as $k => $v) {
			array_push($queryRes->mallID, $v->tb_mall_id);
		}

		$queryRes->date = date('Y/m/d', strtotime($queryRes->begin_date)).' - '.date('Y/m/d', strtotime($queryRes->end_date));

		return $queryRes;
	}

	/**
	 * 获取折扣列表
	 * @param int $page 页码
	 * @param string $where 查询条件
	 */
	public function getDiscountList($page = 1, $where = NULL, $order = NULL){

		$totalField = " COUNT(*) AS total ";
		$field = " 	id,
					brand_pic_url,
					name_zh,
					type,
					LEFT(begin_date, 10) AS begin_date, 
					LEFT(end_date, 10) AS end_date ,
					CONCAT(brand_name_en, '<br>', brand_name_zh) AS brand,
					brand_name_zh,
					brand_name_en,
					category_name,
					tb_brand_id,
					LEFT(discount_desc, 30) AS discount_desc,
					update_time,
					IF(CHAR_LENGTH(oper) = 32, (SELECT name FROM tb_qcgj_role_user WHERE user_id = oper LIMIT 1), oper) AS oper ";

		$page = ($page - 1) * 25;

		$limit = ' LIMIT '.$page.',25';

		$sql = "SELECT %s FROM ".tname('discount')." %s %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, $totalField, $where, '', ''))->first_row();

		$queryRes = $this->db->query(sprintf($sql, $field, $where, $order, $limit))->result_array();

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
	 * 是否存在折扣
	 * @param string $discountId 折扣id
	 */
	public function hasDiscountById($discountId = false, $brandId = false){
		$where = array(
				'id' => $discountId,
				'tb_brand_id' => $brandId,
			);
		if ($this->isAdmin) unset($where['tb_brand_id']);

		$queryRes = $this->db->get_where(tname('discount'), $where)->result();

		return count($queryRes) == 1 ? true : false;
	}

	/**
	 * 验证折扣添加
	 * @param array $reqData 折扣数据
	 */
	public function verlidationAddDis($reqData = array()){

		$this->form_validation->set_rules($this->lang->line('ADD_DISCOUNT_VALIDATION'));

		if (!$this->form_validation->run()) {
			return validation_errors();
		}

		$discountDate = $this->_formatDiscountDate($reqData['discountDate'], true);

		if (!$discountDate) return $this->lang->line('EMPTY_DISCOUNT_DATE');

		return true;
	}

	/**
	 * 格式化折扣有效期
	 * @param string $date
	 * @param bool $returnBool 是否判断
	 */
	private function _formatDiscountDate($date = false, $returnBool = false){

		$date = explode(' - ', $date);

		$date[0] = strtotime($date[0]);
		$date[1] = strtotime($date[1]);

		if ($returnBool) {
			
			if ($date[1] <= $date[0]) return false;

			return true;
		}

		return $date;
	}


	/**
	 * 格式化品牌宣传图
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
