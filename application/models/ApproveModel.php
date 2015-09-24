<?php
/**
 * 评论模型
 */

class ApproveModel extends CI_Model {
	
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
					audit,
					LEFT(discount_desc, 30) AS discount_desc ";

		$page = ($page - 1) * 25;

		$limit = ' LIMIT '.$page.',25';

		$sql = "SELECT %s FROM ".tname('discount')." %s ORDER BY create_time DESC %s ";

		$queryTotal = $this->db->query(sprintf($sql, $totalField, $where, ''))->first_row();

		$queryRes = $this->db->query(sprintf($sql, $field, $where, $limit))->result();

		$returnData = array(
				'list' => $queryRes,
				'page' => $this->setPagination(site_url('Approve/'.$this->router->method), $queryTotal->total, 25),
			);

		return $returnData;
	}

	/**
	 * 获取评论列表
	 * @param int $page 页码
	 * @param string $where 查询条件
	 */
	public function getCommentList($page = 1, $where = NULL){
		$totalField = " COUNT(*) AS total ";
		$field = " 
					a.id AS commentId,
					a.type,
					b.mobile,
					(
						CASE a.type
						WHEN '1' THEN d.name_zh
						ELSE 
							CONCAT(f.name_zh, f.name_en, '-', (SELECT name_zh FROM tb_mall WHERE id = e.tb_mall_id), '店')
						END
					) AS shopName,
					a.status,
					a.content AS comment,
					a.grade,
					a.pic_url AS picUrl,
					a.create_time AS pubTime,
					a.approve_time AS approveTime,
					c.name AS operName ";

		$page = ($page - 1) * 25;

		$limit = ' LIMIT '.$page.',25';

		$sql = "SELECT 
					 %s
					 FROM tb_comment AS a 
					LEFT JOIN tb_user AS b ON b.id = a.tb_user_id
					LEFT JOIN tb_qcgj_role_user AS c ON c.user_id = a.oper
					LEFT JOIN tb_mall AS d ON d.id = a.tb_obj_id
					LEFT JOIN tb_brand_mall AS e ON e.id = a.tb_obj_id
					LEFT JOIN tb_brand AS f ON f.id = e.tb_brand_id 
					 %s 
					ORDER BY a.create_time DESC 
					 %s";

		$queryTotal = $this->db->query(sprintf($sql, $totalField, $where, ''))->first_row();

		$queryRes = $this->db->query(sprintf($sql, $field, $where, $limit))->result();

		$returnData = array(
				'list' => $queryRes,
				'page' => $this->setPagination(site_url('Approve/'.$this->router->method), $queryTotal->total, 25),
			);

		return $returnData;
	}

	/**
	 * 获取评论图片内容
	 * @param string $commentId 评论id
	 */
	public function getCommentImage($commentId = false){
		
		$where = array(
				'id' => $commentId,
			);

		$queryRes = $this->db->get_where(tname('comment'), $where)->first_row();

		$image = explode(',', $queryRes->pic_url);

		return count($image) > 0 ? $image : array();
	}

	/**
	 * 更新评论审核状态
	 * @param array $reqData 更新内容
	 */
	public function upCommentStatus($reqData = array()){
		$where = array(
				'id' => $reqData['commentId'],
			);

		$update = array(
				'status'       => (int)$reqData['status'],
				'approve_time' => currentTime(),
				'oper'         => $this->userInfo->user_id,
			);

		$status = $this->db->where($where)->update(tname('comment'), $update);

		$status = array(
				'error'  => $status ? true : false,
				'status' => $update['status'] == 1 ? 2 : 1,
			);

		return $status;
	}

	/**
	 * 更新折扣审核状态
	 * @param array $reqData 更新内容
	 */
	public function upDiscountStatus($reqData = array()){
		$where = array(
				'id' => $reqData['discountId'],
			);

		$update = array(
				'audit'       => (int)$reqData['status'],
				'oper'        => $this->userInfo->user_id,
				'update_time' => currentTime(),
			);

		$status = $this->db->where($where)->update(tname('discount'), $update);

		$status = array(
				'error'  => $status ? true : false,
				'status' => $update['audit'] == 1 ? 2 : 1,
			);

		return $status;
	}

	/**
	 * 删除折扣信息
	 * @param array $reqData 删除内容
	 */
	public function delDiscount($reqData = array()){
		$where = array(
				'id' => $reqData['discountId'],
			);
		$update = array(
				'is_delete'   => 1,
				'oper'        => $this->userInfo->user_id,
				'update_time' => currentTime(),
			);

		$queryRes = $this->db->where($where)->update(tname('discount'), $update);		

		return $queryRes ? true : false;
	}

	/**
	 * 获取洋码头图片列表
	 * @param int $page 页码
	 * @param string $where 查询条件
	 */
	public function getYmtList($page = 1, $where = NULL){
		$totalField = " COUNT(*) AS total ";
		$field = " a.id AS ymtId,
				   a.name, 
				   a.orig_pic_url AS picUrl, 
				   a.thumb_pic_url AS thumbUrl, 
				   a.province_name AS provinceName, 
				   a.no, 
				   a.slogan, 
				   a.status, 
				   a.create_time, 
				   a.update_time ";

		$page = ($page - 1) * 25;

		$limit = ' LIMIT '.$page.',25';

		$sql = "SELECT 
					%s
					 FROM  tb_ymt_participant AS a 
					%s
					ORDER BY a.create_time DESC 
					%s ";

		$queryTotal = $this->db->query(sprintf($sql, $totalField, $where, ''))->first_row();

		$queryRes = $this->db->query(sprintf($sql, $field, $where, $limit))->result();

		$returnData = array(
				'list' => $queryRes,
				'page' => $this->setPagination(site_url('Approve/'.$this->router->method), $queryTotal->total, 25),
			);

		return $returnData;
	}

	/**
	 * 获取洋码头详细内容
	 * @param string $ymtId 
	 */
	public function getYmtDetail($ymtId = false){
		$where = array(
				'id' => $ymtId,
			);

		$queryRes = $this->db->get_where(tname('ymt_participant'), $where)->first_row();

		return $queryRes;
	}

	/**
	 * 更新洋码头图片状态
	 * @param array $reqData 更新内容
	 */
	public function upYmtPicStatus($reqData = array()){
		$where = array(
				'id' => $reqData['ymtId'],
			);

		$update = array(
				'status'      => (int)$reqData['status'],
				'update_time' => currentTime(),
			);

		$status = $this->db->where($where)->update(tname('ymt_participant'), $update);

		$status = array(
				'error'  => $status ? true : false,
				'status' => $update['status'] == 1 ? 2 : 1,
			);

		return $status;
	}

	/**
	 * 编辑洋码头图片
	 * @param array $update 更新内容
	 * @param string $ymtId 
	 */
	public function editYmt($update = array(), $ymtId = false){
		
		$where = array(
				'id' => $ymtId,
			);

		return $this->db->where($where)->update(tname('ymt_participant'), $update);
	}

	/**
	 * 获取省份列表
	 *
	 */
	public function getProvinceList(){

		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.PROVINCE_LIST'));

		if (!empty($cacheRes) && count($cacheRes)) return $cacheRes;

		$queryRes = $this->db->select('id, code, name')->get(tname('province'))->result();

		if (count($queryRes)) $this->cache->save(config_item('NORMAL_CACHE.PROVINCE_LIST'), $queryRes);

		return $queryRes;
	}




















}
