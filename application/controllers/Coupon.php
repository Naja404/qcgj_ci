<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 优惠券管理
 */

class Coupon extends WebBase {
	//  视图输出内容
	public $outData;

	public function __construct(){
		parent::__construct();

		$this->load->model('CouponModel');
		$this->outData['currentModule'] = __CLASS__;
	}

	/**
	 * 优惠券审核
	 *
	 */
	public function verifyCoupon(){
		$where = ' a.on_sale = 0 ';

		$order = ' ORDER BY a.create_time DESC ';


		$this->outData['pageTitle'] = $this->lang->line('TEXT_COUPON_VERIFY');
		$couponList = $this->CouponModel->getCouponList($where, $order, $this->p);

		$this->outData['couponList'] = $couponList['data']['list'];
		$this->outData['couponListPage'] = $couponList['data']['page'];

		$this->load->view('Coupon/verifyCoupon', $this->outData);
	}

	/**
	 * 上架优惠券
	 *
	 */
	public function saleCoupon(){
		if (!$this->input->is_ajax_request()) {
			jsonReturn($this->ajaxRes);
		}

		$couponId = strDecrypt($this->input->post('couponId'));
		$couponStatus = $this->input->post('status');

		$updateRes = $this->CouponModel->saleCoupon($couponId, $couponStatus);

		if (!is_array($updateRes)) {
			$this->ajaxRes['msg'] = $updateRes;
		}else{
			$this->ajaxRes = array(
						'status'       => 0,
						'html'         => $updateRes['html'],
						'couponStatus' => $couponStatus == 1 ? 2 : 1,
						'class'        => $updateRes['class'],
						);
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 优惠券列表
	 *
	 */
	public function couponList(){

		$where = $order = '';

		$order = ' ORDER BY a.create_time DESC ';


		$this->outData['pageTitle'] = $this->lang->line('TEXT_COUPON_LIST');
		$couponList = $this->CouponModel->getCouponList($where, $order, $this->p);

		$this->outData['couponList'] = $couponList['data']['list'];
		$this->outData['couponListPage'] = $couponList['data']['page'];

		$this->load->view('Coupon/couponList', $this->outData);
	}

	/**
	 * 新建优惠券
	 *
	 */
	public function addCoupon(){

		if ($this->input->is_ajax_request()) {

			$verlidationRes = $this->_verlidationAddCoupon();

			if ($verlidationRes !== true) {
				$this->ajaxRes['msg'] = $verlidationRes;
				jsonReturn($this->ajaxRes);
			}

			$addCouponRes = $this->CouponModel->addCoupon($this->input->post());

			if ($addCouponRes['error']) {
				$this->ajaxRes['msg'] = $addCouponRes['msg'];
			}else{
				$this->ajaxRes = array(
						'status' => 0,
					);
			}

			jsonReturn($this->ajaxRes);
		}

		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_ADDCOUPON');
		$shopList = $this->CouponModel->getShopList();

		$this->outData['shopList'] = $shopList['data']['list'];
		$this->outData['areaList'] = $shopList['data']['areaList'];
		$this->outData['cityList'] = $shopList['data']['cityList'];
		$this->outData['bjAreaList'] = $shopList['data']['bjAreaList'];
		$this->outData['shAreaList'] = $shopList['data']['shAreaList'];
		$this->outData['gzAreaList'] = $shopList['data']['gzAreaList'];

		$this->load->view('Coupon/addCoupon', $this->outData);
	}

	/**
	 * 编辑优惠券
	 *
	 */
	public function editCoupon(){
		$couponId = strDecrypt($this->input->get('couponId'));

		if (!$this->CouponModel->checkAuthCoupon($couponId)) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_COUPON'),
					'url'     => site_url('Coupon/couponList'),
				);
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) {

			$verlidationRes = $this->_verlidationAddCoupon();

			if ($verlidationRes !== true) {
				$this->ajaxRes['msg'] = $verlidationRes;
				jsonReturn($this->ajaxRes);
			}

			$couponData = $this->input->post();
			$couponData['couponId'] = $couponId;

			$editCouponRes = $this->CouponModel->editCoupon($couponData);

			if ($editCouponRes['error']) {
				$this->ajaxRes['msg'] = $editCouponRes['msg'];
			}else{
				$this->ajaxRes = array(
						'status' => 0,
					);
			}

			jsonReturn($this->ajaxRes);
		}

		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_EDITCOUPON');

		$this->outData['couponData'] = $this->CouponModel->getCouponById($couponId);

		$shopList = $this->CouponModel->getShopList();

		$this->outData['shopList'] = $this->_fetchCheckMall($shopList['data']['list'], $this->outData['couponData']->mallID);

		$this->outData['areaList'] = $shopList['data']['areaList'];
		$this->outData['cityList'] = $shopList['data']['cityList'];
		$this->outData['bjAreaList'] = $shopList['data']['bjAreaList'];
		$this->outData['shAreaList'] = $shopList['data']['shAreaList'];
		$this->outData['gzAreaList'] = $shopList['data']['gzAreaList'];

		$this->load->view('Coupon/editCoupon', $this->outData);
	}

	/**
	 * 删除优惠券
	 *
	 */
	public function delCoupon(){
		if (!$this->input->is_ajax_request()) {
			jsonReturn($this->ajaxRes);
		}

		$updateRes = $this->CouponModel->delCouponById(strDecrypt($this->input->post('couponId')));

		if (!$updateRes) {
			$this->ajaxRes['msg'] = $this->lang->line('TEXT_COUPON_DELETE_FAIL');
		}else{
			$this->ajaxRes = array('status' => 0);
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 置顶=1,推荐=2,取消置顶=101,取消推荐=102
	 *
	 */
	public function setCouponStatus(){

		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);
		
		$statusArr = array(1, 2, 101, 102);
		$reqStatus = $this->input->post('reqStatus');
		$couponId  = strDecrypt($this->input->post('couponId'));

		if (!in_array($reqStatus, $statusArr)) jsonReturn($this->ajaxRes);

		$reqStatus = $reqStatus > 2 ? 0 : $reqStatus;

		$returnRes = $this->CouponModel->setCouponStatus($couponId, $reqStatus);

		if ($returnRes === true) {
			$this->ajaxRes = array('status' => 0);
		}

		jsonReturn($this->ajaxRes);

	}

	/**
	 * 优惠券报表
	 *
	 */
	public function analysis(){

		$arr = array(
"uploadtemp/canting/2015-07/mall_0017d39febfc2944f4fd8ac466c5b48d.jpg",
"uploadtemp/canting/2015-07/mall_0131e16bc276e1906ddfff29beac38b8.jpg",
"uploadtemp/canting/2015-07/mall_0135c6510b21ee80b775305825a0eae1.jpg",
"uploadtemp/canting/2015-07/mall_013fe317acdbb66591b8864c171a79f6.jpg",
"uploadtemp/canting/2015-07/mall_0170e45dfa33e772817aafb5ae673427.jpg",
"uploadtemp/canting/2015-07/mall_0260f86e9516532194a166be3ffd3fa7.jpg",
"uploadtemp/canting/2015-07/mall_02b2f3432ba90cbeb71ca2cdebea93fe.jpg",
"uploadtemp/canting/2015-07/mall_039f02e66ac8d4fcb09f3ae42b718cd7.jpg",
"uploadtemp/canting/2015-07/mall_03bfbb7c9d56f263e0824aaf5c95f96d.jpg",
"uploadtemp/canting/2015-07/mall_049b99565156763a960cc752b7259c6b.jpg",
"uploadtemp/canting/2015-07/mall_04c9d14e33d0308a83627b52e5b00575.jpg",
"uploadtemp/canting/2015-07/mall_0501f078dc155ff7678d01518d1ecee8.jpg",
"uploadtemp/canting/2015-07/mall_052cdeea0c7702cea8dff3ede232ab8b.jpg",
"uploadtemp/canting/2015-07/mall_053c1c97fbb91207931398ea35aaacd4.jpg",
"uploadtemp/canting/2015-07/mall_05c5ed756daf82477f0ade6606c64d8c.jpg",
"uploadtemp/canting/2015-07/mall_05d39f1b46d9643891bed56d10928768.jpg",
"uploadtemp/canting/2015-07/mall_0600a85b598ebcc872f69470bb78a93a.jpg",
"uploadtemp/canting/2015-07/mall_061050dd28801087820d6b5181dc982b.jpg",
"uploadtemp/canting/2015-07/mall_061779d14349d746cdeabeb4a468aa70.jpg",
"uploadtemp/canting/2015-07/mall_0619818b51f04a7938c07edaab830093.jpg",
"uploadtemp/canting/2015-07/mall_0643ddfb9b6efba18ac7fae0d331c601.jpg",
"uploadtemp/canting/2015-07/mall_075f4b97658c3b5a130859f1557d0601.jpg",
"uploadtemp/canting/2015-07/mall_076155d009073a81d19693c9ae999d13.jpg",
"uploadtemp/canting/2015-07/mall_07b51097ac330556c4c9d364aa6fbf3d.jpg",
"uploadtemp/canting/2015-07/mall_094f5da0d61b5019c396e2efc1314346.jpg",
"uploadtemp/canting/2015-07/mall_0982746b4db6eb2eb4c71e6e63bb408c.jpg",
"uploadtemp/canting/2015-07/mall_0a366c57c54f37158c5a946760a67110.jpg",
"uploadtemp/canting/2015-07/mall_0a38cad8c733f82de508eb7a0d68d42b.jpg",
"uploadtemp/canting/2015-07/mall_0a7fb2d9876d3f5d2850041a198c5129.jpg",
"uploadtemp/canting/2015-07/mall_0b0d3f1726c28c7d150dc2e9b8e51e7c.jpg",
"uploadtemp/canting/2015-07/mall_0b810390ea920f0fef1acb5b32c4d4c7.jpg",
"uploadtemp/canting/2015-07/mall_0c08b40e231edd0926f48b483ad3bd26.jpg",
"uploadtemp/canting/2015-07/mall_0c65cf98e0dacf551973196e9e98fd10.jpg",
"uploadtemp/canting/2015-07/mall_0cefbf0ce88ba15cf16f9ed5f546d0ed.jpg",
"uploadtemp/canting/2015-07/mall_0d389c5e486a9a49ebd1e925b6dd31bb.jpg",
"uploadtemp/canting/2015-07/mall_0d80cdd89534333722d5afe944095543.jpg",
"uploadtemp/canting/2015-07/mall_0dae21892fecc9f7e2c70c07de8246e9.jpg",
"uploadtemp/canting/2015-07/mall_0e58023b88a9ffc63c13f45ce818d1fb.jpg",
"uploadtemp/canting/2015-07/mall_0e84245ba2f42508431e11a5c5b8aa96.jpg",
"uploadtemp/canting/2015-07/mall_0ec30472539438fd95f1fa48b0163677.jpg",
"uploadtemp/canting/2015-07/mall_0f915993f7ed79266c4bb229f429430b.jpg",
"uploadtemp/canting/2015-07/mall_0f9f31f8e79bf316395a94dfb1f2ae34.jpg",
"uploadtemp/canting/2015-07/mall_0ff9354d0e5bb57ed0709c173a5d9383.jpg",
"uploadtemp/canting/2015-07/mall_1064cc6e5fbe8d46e65d527251866b51.jpg",
"uploadtemp/canting/2015-07/mall_1080f7d50820c036afd0530dc2a608af.jpg",
"uploadtemp/canting/2015-07/mall_10b66c937900e42da413bd43a42277a3.jpg",
"uploadtemp/canting/2015-07/mall_10fee4d5dd13b8d67478a4fa9986cd74.jpg",
"uploadtemp/canting/2015-07/mall_11111b3331e37fd7e48a62e6ae9cb3eb.jpg",
"uploadtemp/canting/2015-07/mall_1122745ebadb53fab523361c82e443d8.jpg",
"uploadtemp/canting/2015-07/mall_112ffa75bb3d2c7230d3fc991694e1cc.jpg",
"uploadtemp/canting/2015-07/mall_11643e4e2716cf19fc930f0e57969abb.jpg",
"uploadtemp/canting/2015-07/mall_119734085eaa2927cb69b2e24dd49fb5.jpg",
"uploadtemp/canting/2015-07/mall_11b3bf8e673e3bdda8008eacec639fce.jpg",
"uploadtemp/canting/2015-07/mall_11de326b69cb52a3ddf2f98eb0ad65f2.jpg",
"uploadtemp/canting/2015-07/mall_12174ba81943fb431540748f86436359.jpg",
"uploadtemp/canting/2015-07/mall_128fcd2adb8d255055d064b16b758cd0.jpg",
"uploadtemp/canting/2015-07/mall_140c4eb283726e9b58f233ed763ec144.jpg",
"uploadtemp/canting/2015-07/mall_14bd4c2855db09c547fae557300262b7.jpg",
"uploadtemp/canting/2015-07/mall_15cff81e81737a0dbdd36a38c581214d.jpg",
"uploadtemp/canting/2015-07/mall_1691f90501da340a61a7abf8caac11ca.jpg",
"uploadtemp/canting/2015-07/mall_175075c212ef69d5e5e9947e381e8ab1.jpg",
"uploadtemp/canting/2015-07/mall_17af09ff3c72e0e100e98e9080eb8d00.jpg",
"uploadtemp/canting/2015-07/mall_18167f81c4ca9f7e33e11ac7fecbdcdb.jpg",
"uploadtemp/canting/2015-07/mall_182cf228ec89bb96225a2ba5058a4298.jpg",
"uploadtemp/canting/2015-07/mall_18af08884ba898909fed20a526b50c92.jpg",
"uploadtemp/canting/2015-07/mall_195dcb40827574f668406a31c6ee4f60.jpg",
"uploadtemp/canting/2015-07/mall_1988d7b7fd5993eb0aa60ffbcaba3b1a.jpg",
"uploadtemp/canting/2015-07/mall_19e83ec35226d9172c2cdb44bf6c623c.jpg",
"uploadtemp/canting/2015-07/mall_1a8b7896dc1df16310a1aba6ea7a0c8b.jpg",
"uploadtemp/canting/2015-07/mall_1abbb011d8e1703d2688cf9af5128658.jpg",
"uploadtemp/canting/2015-07/mall_1b0091e3b5c25737c105b49320ba13ae.jpg",
"uploadtemp/canting/2015-07/mall_1b6fe5bd63c0416ea6287459547fc49e.jpg",
"uploadtemp/canting/2015-07/mall_1c7735b6e8b9fb011a27fbbf2160f746.jpg",
"uploadtemp/canting/2015-07/mall_1f2f7ec19384c0b360f2d5bf4d269c67.jpg",
"uploadtemp/canting/2015-07/mall_1fa91c4df3337c51ff22593df2b42f81.jpg",
"uploadtemp/canting/2015-07/mall_1fd66fdb33e77c725bec84fcc09481bf.jpg",
"uploadtemp/canting/2015-07/mall_20505f357038381b3040f3d6f3956b2e.jpg",
"uploadtemp/canting/2015-07/mall_20536de688dbcc950c8136ec02702318.jpg",
"uploadtemp/canting/2015-07/mall_2123f78bf4486d3dfaff5c256cf15ab9.jpg",
"uploadtemp/canting/2015-07/mall_214a031e453ac03eb8cbdafcc6d77cb4.jpg",
"uploadtemp/canting/2015-07/mall_214b5e4fe6689a29b605d5fc79e10008.jpg",
"uploadtemp/canting/2015-07/mall_21b9c1f916c6fcd566ad82b92292a5eb.jpg",
"uploadtemp/canting/2015-07/mall_21d9ab935f88f57fbe4ec97741940835.jpg",
"uploadtemp/canting/2015-07/mall_226fd0f67792cd65f65ddd04fcfcbbe9.jpg",
"uploadtemp/canting/2015-07/mall_229fab23bdf8ae78a7b345bba1b5696a.jpg",
"uploadtemp/canting/2015-07/mall_22f02918d90a089092699d627b1af317.jpg",
"uploadtemp/canting/2015-07/mall_239b99a42f1ffc7fa9a8dc322a7cd0fe.jpg",
"uploadtemp/canting/2015-07/mall_24cd8fcd33fcf4ef78044333b238f04f.jpg",
"uploadtemp/canting/2015-07/mall_24edaf522bceaef060474b0e29467fc9.jpg",
"uploadtemp/canting/2015-07/mall_277db201c6fe26e75f19f9230c34ec3f.jpg",
"uploadtemp/canting/2015-07/mall_27b635ae1989ea9ea28f2e908f6c109d.jpg",
"uploadtemp/canting/2015-07/mall_27f0c6f5d9c229c8a1085af52dd45351.jpg",
"uploadtemp/canting/2015-07/mall_28007d4a1803014f9e2b326684a9cca7.jpg",
"uploadtemp/canting/2015-07/mall_285b797d71ddacbbe5b741ecce658220.jpg",
"uploadtemp/canting/2015-07/mall_287c67c68c0f6eb24868ee31d24e52db.jpg",
"uploadtemp/canting/2015-07/mall_2907f736fd94e913a9231cf95a01bbb9.jpg",
"uploadtemp/canting/2015-07/mall_2948ed634510afd3bd6bffa53dbb2aac.jpg",
"uploadtemp/canting/2015-07/mall_294906e80511d0e4b3949cd2e4158270.jpg",
"uploadtemp/canting/2015-07/mall_29b4739becc051574360a1e181bf8419.jpg",
"uploadtemp/canting/2015-07/mall_2a9491c06a92319c7199d98c6fbd5d5c.jpg",
"uploadtemp/canting/2015-07/mall_2aa5a8e6d15c285884676f2fc6e2d283.jpg",
"uploadtemp/canting/2015-07/mall_2b4ea0d2f71932c3a2b0a36020773b8e.jpg",
"uploadtemp/canting/2015-07/mall_2be613a448a5a5cc681cbd0b33ec2b3b.jpg",
"uploadtemp/canting/2015-07/mall_2c7f4e9e1a2ea131412b861a1d6c3359.jpg",
"uploadtemp/canting/2015-07/mall_2d649ce98bb7450c50ed99b963803b51.jpg",
"uploadtemp/canting/2015-07/mall_2e3fe674a974ca9124f88f097c9e50c3.jpg",
"uploadtemp/canting/2015-07/mall_2e6900f77c83bdec7c2de0a39ed9120a.jpg",
"uploadtemp/canting/2015-07/mall_2e6be64e0295fc2c95e553d565acfeb6.jpg",
"uploadtemp/canting/2015-07/mall_2ea5468d54c34b76ae8e93872fe57025.jpg",
"uploadtemp/canting/2015-07/mall_2f79a806096666987846ea9e816f2575.jpg",
"uploadtemp/canting/2015-07/mall_2faf25599a4f6eada319d33b0780b089.jpg",
"uploadtemp/canting/2015-07/mall_312a570cb72ebcb8c6321c013a1402f0.jpg",
"uploadtemp/canting/2015-07/mall_31affcb10cc61d83519333bf6d4d906d.jpg",
"uploadtemp/canting/2015-07/mall_31dda2794a02dc4f3c3afbd9a4b17356.jpg",
"uploadtemp/canting/2015-07/mall_32638261a13f2662bb730e2916a806de.jpg",
"uploadtemp/canting/2015-07/mall_32e81ff37b2849e0a9c414e5eb938b13.jpg",
"uploadtemp/canting/2015-07/mall_331193463797acad30664d66bc0b53cc.jpg",
"uploadtemp/canting/2015-07/mall_33c206eabcfd1a050fc6898c87243487.jpg",
"uploadtemp/canting/2015-07/mall_34f3db609d83407dcb76ea9226c85e7d.jpg",
"uploadtemp/canting/2015-07/mall_3671402e71099fdcb7e9cad9e77c1e5f.jpg",
"uploadtemp/canting/2015-07/mall_384205bf9a9d220b0d9e50ac7bcad1b1.jpg",
"uploadtemp/canting/2015-07/mall_388a864576643418eb1f603bd6bc56af.jpg",
"uploadtemp/canting/2015-07/mall_389ce298867d02ecb06a79bded25d54d.jpg",
"uploadtemp/canting/2015-07/mall_394d4b91888f22ee3bf466e3fae0a8d5.jpg",
"uploadtemp/canting/2015-07/mall_3963a96a2f8667f369bb51bc3786bc6d.jpg",
"uploadtemp/canting/2015-07/mall_3a2287f3873511b183069cb4f0cadb6a.jpg",
"uploadtemp/canting/2015-07/mall_3a4e98fffdca712680c7a2a085e75c0a.jpg",
"uploadtemp/canting/2015-07/mall_3a632bf9aeaff9324732a7830d768f38.jpg",
"uploadtemp/canting/2015-07/mall_3ac1f965ef9b7db1f3f6c0778920e25f.jpg",
"uploadtemp/canting/2015-07/mall_3afb13951856a008f999d08e85599044.jpg",
"uploadtemp/canting/2015-07/mall_3b9eee7398d3aa962cbe313043f60b11.jpg",
"uploadtemp/canting/2015-07/mall_3bad2c1248cd794ef8bd9eb851ee2849.jpg",
"uploadtemp/canting/2015-07/mall_3c32012214e5ba613d49a35a1a6ee4d8.jpg",
"uploadtemp/canting/2015-07/mall_3c9be0e6d0239e0875fa3be6999d44fd.jpg",
"uploadtemp/canting/2015-07/mall_3ca32924bee783d4e5318bc9a6cb3258.jpg",
"uploadtemp/canting/2015-07/mall_3cc45cff694e57244e7874a8feba9f17.jpg",
"uploadtemp/canting/2015-07/mall_3f1758d53a9bbd6bea979e78a9a98e86.jpg",
"uploadtemp/canting/2015-07/mall_3f2d144ad355cd838d894e705e8535e0.jpg",
"uploadtemp/canting/2015-07/mall_3f6576fab1c2d2606fed942c74baf3d2.jpg",
"uploadtemp/canting/2015-07/mall_3f7a3b9ef3f41a51226ffc1779c97558.jpg",
"uploadtemp/canting/2015-07/mall_3fcfd2adae6a8ef88190b6b9f7cff490.jpg",
"uploadtemp/canting/2015-07/mall_3fe5739d356ca351e93fe182cfef9b63.jpg",
"uploadtemp/canting/2015-07/mall_402945211ea9f5484ca54fbd49a96343.jpg",
"uploadtemp/canting/2015-07/mall_40508fb965bd60741d5360a2295b73e8.jpg",
"uploadtemp/canting/2015-07/mall_417b8033b770552dc7ac457d73a2b8ed.jpg",
"uploadtemp/canting/2015-07/mall_42378694ca76ead925539506512a747d.jpg",
"uploadtemp/canting/2015-07/mall_425d8dd7f617351d248302db238cb233.jpg",
"uploadtemp/canting/2015-07/mall_432f2a2e108ed0e0e750cdcbb2df7646.jpg",
"uploadtemp/canting/2015-07/mall_439eb929435c16ece3d4e80297c21f4c.jpg",
"uploadtemp/canting/2015-07/mall_43d0b2ad770e5701908821f009518a2e.jpg",
"uploadtemp/canting/2015-07/mall_45a2fde6b2f78cf45655b610133678e5.jpg",
"uploadtemp/canting/2015-07/mall_464290571a586f15123812a2e1523d14.jpg",
"uploadtemp/canting/2015-07/mall_46ea446e83dc8ca81c47731722abd09d.jpg",
"uploadtemp/canting/2015-07/mall_478d35d3368fe1b0738ccb68b6a20a32.jpg",
"uploadtemp/canting/2015-07/mall_47da3ec2733366525ba545b6eb6b4fa1.jpg",
"uploadtemp/canting/2015-07/mall_48c5f3151d0c6581adef6856ec36fad3.jpg",
"uploadtemp/canting/2015-07/mall_4925072f5a140f8ed84cd5a6287c8dee.jpg",
"uploadtemp/canting/2015-07/mall_497bd22eb47d2d5833e43d7caf228d49.jpg",
"uploadtemp/canting/2015-07/mall_49b890c98ffd3f04bc3ded01a27b9aec.jpg",
"uploadtemp/canting/2015-07/mall_49ca3e30f7f474986effee2c697fd64d.jpg",
"uploadtemp/canting/2015-07/mall_4a105fdbce05760e962f8b5404804352.jpg",
"uploadtemp/canting/2015-07/mall_4a57633c7f0fe874b92751d5aef30c09.jpg",
"uploadtemp/canting/2015-07/mall_4a7498bcec87fdae4308e47babf393e7.jpg",
"uploadtemp/canting/2015-07/mall_4be4e3a835f707879921110a2422deec.jpg",
"uploadtemp/canting/2015-07/mall_4c3857807844460a2e6228e96d718a8e.jpg",
"uploadtemp/canting/2015-07/mall_4cdb9dbc7ecd39693bd2ff300c9acfe3.jpg",
"uploadtemp/canting/2015-07/mall_4cf969a086714f0492b3e8f7901444de.jpg",
"uploadtemp/canting/2015-07/mall_4d9edf1d90057c115355b82d9dd30b9c.jpg",
"uploadtemp/canting/2015-07/mall_4dfa5989fc9484d1c26b8bab1c8bf519.jpg",
"uploadtemp/canting/2015-07/mall_4eb4528670c99794923e729a9939478e.jpg",
"uploadtemp/canting/2015-07/mall_4ec5270faeda3830c986566b102d8650.jpg",
"uploadtemp/canting/2015-07/mall_4f13b943caae49320492ccfd3559039e.jpg",
"uploadtemp/canting/2015-07/mall_50f43b767440f083380715741f13f2ef.jpg",
"uploadtemp/canting/2015-07/mall_51bb7d804201eb94555f8ef881f76d6b.jpg",
"uploadtemp/canting/2015-07/mall_51bf4410104424be1c27de6c02b1454b.jpg",
"uploadtemp/canting/2015-07/mall_51ddd5649ae0d07821d3eef3905997be.jpg",
"uploadtemp/canting/2015-07/mall_526cda98b2e423d8eaea124eb72ebd92.jpg",
"uploadtemp/canting/2015-07/mall_5272c9e0691ea507b07e116c848ae280.jpg",
"uploadtemp/canting/2015-07/mall_5290e0ba3e9986abe5a72dfd141b9b5b.jpg",
"uploadtemp/canting/2015-07/mall_52bebcf27a3a47fee5503dc21fc0003b.jpg",
"uploadtemp/canting/2015-07/mall_531a28ad6a7fa5d964c7a69014949250.jpg",
"uploadtemp/canting/2015-07/mall_532c25780928aeaf3e24f7f5b5e1a99c.jpg",
"uploadtemp/canting/2015-07/mall_54704706ab64e7a44753c9395046fb3a.jpg",
"uploadtemp/canting/2015-07/mall_5494e721e01e880f88c2a285a97b05ef.jpg",
"uploadtemp/canting/2015-07/mall_54aa1c27d797298bcb9b252c8b50fa82.jpg",
"uploadtemp/canting/2015-07/mall_5531192899b6572c5317814256fc9db1.jpg",
"uploadtemp/canting/2015-07/mall_55c2a62f60ceda745fa7dfab618f5451.jpg",
"uploadtemp/canting/2015-07/mall_55c4db84cfe25bb1c30af2f13886fbcb.jpg",
"uploadtemp/canting/2015-07/mall_55cca7637c81d765f812a3f2a4b552de.jpg",
"uploadtemp/canting/2015-07/mall_56b34bd2622d4922890ec42fee38a3f3.jpg",
"uploadtemp/canting/2015-07/mall_57e67bd947916b1913655ffd70914f71.jpg",
"uploadtemp/canting/2015-07/mall_582c02310dd98fd59ccbb3a765f30080.jpg",
"uploadtemp/canting/2015-07/mall_586195e2afd5fc7639173789834f05d5.jpg",
"uploadtemp/canting/2015-07/mall_58c1bdc18792773165de65eed26fe973.jpg",
"uploadtemp/canting/2015-07/mall_58d13211b1599fe19711aea9d8a71cd3.jpg",
"uploadtemp/canting/2015-07/mall_58fad64f5ddc819a1ebcce3db2deef8f.jpg",
"uploadtemp/canting/2015-07/mall_595204df1560cdb6b073ba581faeefba.jpg",
"uploadtemp/canting/2015-07/mall_595ee61ecaf2110d8c99e8b62ca57710.jpg",
"uploadtemp/canting/2015-07/mall_59a50e5df5cc6733eb9a54614262fb0f.jpg",
"uploadtemp/canting/2015-07/mall_59c445f48bd34091e192f17de15765de.jpg",
"uploadtemp/canting/2015-07/mall_59e580104323e3021bcb2e34b597636c.jpg",
"uploadtemp/canting/2015-07/mall_5a32d1f112224e5dcdf3acee594b4b2c.jpg",
"uploadtemp/canting/2015-07/mall_5a44fdb3c8cbcca40d0ad62856f57d9c.jpg",
"uploadtemp/canting/2015-07/mall_5b14bbec35dc0e0e5eeff8fef95e6802.jpg",
"uploadtemp/canting/2015-07/mall_5b39d10c977a1cb7dde155395cd3878d.jpg",
"uploadtemp/canting/2015-07/mall_5b3be49fb2258763f53ae50deadeb1f8.jpg",
"uploadtemp/canting/2015-07/mall_5b751c2ce9d5fb4c998d83021703cabf.jpg",
"uploadtemp/canting/2015-07/mall_5ba559159fcb153d0bbfa7734c48dbde.jpg",
"uploadtemp/canting/2015-07/mall_5bec4d70e314c69dfac52cf5f26e0e58.jpg",
"uploadtemp/canting/2015-07/mall_5c0ecc318d3061c062861f106bb4fc9a.jpg",
"uploadtemp/canting/2015-07/mall_5c7eccf30a2872d2cb9151d91738606f.jpg",
"uploadtemp/canting/2015-07/mall_5c964d4116dee970d39054489b5ce688.jpg",
"uploadtemp/canting/2015-07/mall_5ca61f5dc0b2876ea23df4f367c570df.jpg",
"uploadtemp/canting/2015-07/mall_5cf29c6e930c806878c5f342aae9cb8e.jpg",
"uploadtemp/canting/2015-07/mall_5d5006e0d948cb7d6df5d8afee8f3eb6.jpg",
"uploadtemp/canting/2015-07/mall_5d89dacf0e6e28a3d0c7e2ad31cdf10f.jpg",
"uploadtemp/canting/2015-07/mall_5dfe1369af83a23e0a92a2512e0b92dc.jpg",
"uploadtemp/canting/2015-07/mall_5e5a62de8760c323f53aa20f3049f3f8.jpg",
"uploadtemp/canting/2015-07/mall_5e74226dbe5a955832b3d205c152ea1b.jpg",
"uploadtemp/canting/2015-07/mall_5f424ec6a0cce23b0a0cb0dec40e8f78.jpg",
"uploadtemp/canting/2015-07/mall_5faf08b93b2d60f806aa66a8bee2a272.jpg",
"uploadtemp/canting/2015-07/mall_60184b24c37a2eb9c662e61af7370e34.jpg",
"uploadtemp/canting/2015-07/mall_607375bec7573f52f9b3775af2088a7d.jpg",
"uploadtemp/canting/2015-07/mall_608b1789bc19a6352cb66fc9eb099953.jpg",
"uploadtemp/canting/2015-07/mall_60ef97926c284b17042b05736243c3a8.jpg",
"uploadtemp/canting/2015-07/mall_610ceefca6ef59d60e76a16dc1c75690.jpg",
"uploadtemp/canting/2015-07/mall_61fedda76e1ad06cb8d908cb48c998bf.jpg",
"uploadtemp/canting/2015-07/mall_62116f3538286aab6d00173763b887c8.jpg",
"uploadtemp/canting/2015-07/mall_625ba5d9d29e9025c899b462d51076dd.jpg",
"uploadtemp/canting/2015-07/mall_626873d0d97cc4c5f1b061fc248b40b5.jpg",
"uploadtemp/canting/2015-07/mall_62af7329b4a1f1581e118cc6c427c1cb.jpg",
"uploadtemp/canting/2015-07/mall_63290b8a025629444c65ee826d5c863f.jpg",
"uploadtemp/canting/2015-07/mall_649197ee3af4b8df21be2279578a4e1f.jpg",
"uploadtemp/canting/2015-07/mall_652dad1797cca6b32f2d3805c1018bea.jpg",
"uploadtemp/canting/2015-07/mall_653ed0f7f50e5b448ff52c80f328d59a.jpg",
"uploadtemp/canting/2015-07/mall_656d27c06cd2527d11a4166981e7ad29.jpg",
"uploadtemp/canting/2015-07/mall_65b69447be4c5132527a62788e8b72fb.jpg",
"uploadtemp/canting/2015-07/mall_65c3cc6a288b62b9d3e18fce08155e0c.jpg",
"uploadtemp/canting/2015-07/mall_65dfbc6f958e734690e1250e29d6eaf2.jpg",
"uploadtemp/canting/2015-07/mall_664983c3d0696ccdba80876bc80aed7e.jpg",
"uploadtemp/canting/2015-07/mall_6744dbd51cf19a30e546dca64e97fc43.jpg",
"uploadtemp/canting/2015-07/mall_6855070f3df0dca1c9148796e5870e60.jpg",
"uploadtemp/canting/2015-07/mall_6899cbc4e4c682fe5b33c11501b7e1a8.jpg",
"uploadtemp/canting/2015-07/mall_68bf60b23483cce1d20b5789f57f7aeb.jpg",
"uploadtemp/canting/2015-07/mall_68dbb16fc26addc2d64e3d517c8b3e6f.jpg",
"uploadtemp/canting/2015-07/mall_6918e141bf586a8b70a3bb8445ed86b3.jpg",
"uploadtemp/canting/2015-07/mall_69cce56511c79dcc795a0f0e27dc9e8e.jpg",
"uploadtemp/canting/2015-07/mall_69f9c92de5c2862ee9da9d986d4c6856.jpg",
"uploadtemp/canting/2015-07/mall_6a2e1644d7af5c296967f0b7a3f3ff59.jpg",
"uploadtemp/canting/2015-07/mall_6a733d4adf9f0b9ae7ff1dde719207ed.jpg",
"uploadtemp/canting/2015-07/mall_6a8509eb9c09973bfb4ec552fcf4aa55.jpg",
"uploadtemp/canting/2015-07/mall_6b556d143c6beed042a3e53cbee74413.jpg",
"uploadtemp/canting/2015-07/mall_6b55b8846a9e85321cccef7a7dbb33fb.jpg",
"uploadtemp/canting/2015-07/mall_6c2bb0e4b20c25b14fc857518065e43f.jpg",
"uploadtemp/canting/2015-07/mall_6c688af6672e863211aa8381507c1bc5.jpg",
"uploadtemp/canting/2015-07/mall_6c8ede4a0878345566516821cebb9f96.jpg",
"uploadtemp/canting/2015-07/mall_6c9eb6023f9ef2e06fafc3059c05fa02.jpg",
"uploadtemp/canting/2015-07/mall_6cf91aaaee4579694a8c286214029433.jpg",
"uploadtemp/canting/2015-07/mall_6d66282393c06e72c2356c3e2ac85a4f.jpg",
"uploadtemp/canting/2015-07/mall_6db525afe80eb07b79006964721802b5.jpg",
"uploadtemp/canting/2015-07/mall_6deff8a1b12f26dd336b154f062341cd.jpg",
"uploadtemp/canting/2015-07/mall_6df9e137a8ff976d340dbff7bb0587c5.jpg",
"uploadtemp/canting/2015-07/mall_6f043fd0d535e48834a5d0e24f205cd8.jpg",
"uploadtemp/canting/2015-07/mall_6fa188a35ad6530da7fd4d55d3418d82.jpg",
"uploadtemp/canting/2015-07/mall_701f2aec0f7cbf60a7f6693843b86c7f.jpg",
"uploadtemp/canting/2015-07/mall_70d9d4c2cec91eb47efc963b6445aa44.jpg",
"uploadtemp/canting/2015-07/mall_71aab1ccbec35c278f92aa95b0a95522.jpg",
"uploadtemp/canting/2015-07/mall_71b0930c58dde83d94700bdf4eb6c780.jpg",
"uploadtemp/canting/2015-07/mall_71c29b3f6cae82ee7be63a1d68d48975.jpg",
"uploadtemp/canting/2015-07/mall_71e1c3862c5c407ed38db7adb12bdc5e.jpg",
"uploadtemp/canting/2015-07/mall_71e8e576b6a76da5b58593373d818001.jpg",
"uploadtemp/canting/2015-07/mall_720eb4e61f84840fd0dcebc8f011fc9e.jpg",
"uploadtemp/canting/2015-07/mall_723b2a762f3d310093fb029b913af626.jpg",
"uploadtemp/canting/2015-07/mall_731a98e1bc706b5d74a52ebe68847148.jpg",
"uploadtemp/canting/2015-07/mall_7379e284a9f867613acb1ef87ba65d23.jpg",
"uploadtemp/canting/2015-07/mall_73fed0ac602093f01941db1ac46b431e.jpg",
"uploadtemp/canting/2015-07/mall_7430f05878e19697384c578beba490a9.jpg",
"uploadtemp/canting/2015-07/mall_748921e09323823a587d0414d0ec87d9.jpg",
"uploadtemp/canting/2015-07/mall_75fd86b77008e37137cf32f18d880dfd.jpg",
"uploadtemp/canting/2015-07/mall_7736098db6f6381e0dd78258c860f714.jpg",
"uploadtemp/canting/2015-07/mall_77e728e0f94ecb2ef7110bd90c38ae97.jpg",
"uploadtemp/canting/2015-07/mall_78a0fb17e8a7661c04d7112922ce56bc.jpg",
"uploadtemp/canting/2015-07/mall_7913419ec5c16bf09ed5f0be9cfd736b.jpg",
"uploadtemp/canting/2015-07/mall_79358308a150fd38252ccc26a1258dd8.jpg",
"uploadtemp/canting/2015-07/mall_79dcee9573f88f4237c0586cb9972d54.jpg",
"uploadtemp/canting/2015-07/mall_7a598cce4d5120445a398ab687f0d2d4.jpg",
"uploadtemp/canting/2015-07/mall_7aa2b08b800bad8e7b2f6da6f828a6e4.jpg",
"uploadtemp/canting/2015-07/mall_7ad7e12dc350c2938f95e204f4525838.jpg",
"uploadtemp/canting/2015-07/mall_7b29e8d7090af904b2a758ea3309af0f.jpg",
"uploadtemp/canting/2015-07/mall_7b446d7499ed2bb50a5e7efcbd22ac85.jpg",
"uploadtemp/canting/2015-07/mall_7b890154adadbc13944ffed160b1452c.jpg",
"uploadtemp/canting/2015-07/mall_7c467b6f635418a52629f545c6d74354.jpg",
"uploadtemp/canting/2015-07/mall_7d3571148cc07f85522ed1138a1e2dfe.jpg",
"uploadtemp/canting/2015-07/mall_7d4a6811457eb33e5e4f7bd3223bddbf.jpg",
"uploadtemp/canting/2015-07/mall_7d676117ba72d5acacc3cd647eeb0dc4.jpg",
"uploadtemp/canting/2015-07/mall_7e5e6508706d7c5b83185a74e5535158.jpg",
"uploadtemp/canting/2015-07/mall_7f7f96db5c3ca64f510a4d84f39f6f00.jpg",
"uploadtemp/canting/2015-07/mall_7f8e9bf583ec9d97787132a43e13282f.jpg",
"uploadtemp/canting/2015-07/mall_7fcce25c81f0502b02d3cea94d93100e.jpg",
"uploadtemp/canting/2015-07/mall_80213b4ec6d742f250db3c780a656832.jpg",
"uploadtemp/canting/2015-07/mall_805af3e90c0b892820b60d5a26d71bb7.jpg",
"uploadtemp/canting/2015-07/mall_807e5bda14733a826d6eca7c073648e4.jpg",
"uploadtemp/canting/2015-07/mall_80b121f2f5262465f0cd70dc69d565f4.jpg",
"uploadtemp/canting/2015-07/mall_8260fcb2226598ae8aa9da2afe134faf.jpg",
"uploadtemp/canting/2015-07/mall_82a3f3b7f114e99c4a74063a81750351.jpg",
"uploadtemp/canting/2015-07/mall_82a8c047e058e977bff5f01f97dd988d.jpg",
"uploadtemp/canting/2015-07/mall_83701ea5335a6188e2750b0aab6a77d0.jpg",
"uploadtemp/canting/2015-07/mall_83977c97c043904fa003716c4ffbbac4.jpg",
"uploadtemp/canting/2015-07/mall_844d8d5364b1f81bc350292b00817781.jpg",
"uploadtemp/canting/2015-07/mall_845ec41ec539255460c9aaae5e2db1e7.jpg",
"uploadtemp/canting/2015-07/mall_855e01ae8506474b455538ee1cabdb21.jpg",
"uploadtemp/canting/2015-07/mall_85a9e9e17d5af82c2dc4ecb8231c797c.jpg",
"uploadtemp/canting/2015-07/mall_866cd9a09e05cfa0b877a760d922b4ab.jpg",
"uploadtemp/canting/2015-07/mall_86c1fdf278ee328a7153bd0eba0b5042.jpg",
"uploadtemp/canting/2015-07/mall_86e82565172f8ffe33df4c3c6ca85fa1.jpg",
"uploadtemp/canting/2015-07/mall_872b89fd43404aca46a2d17e22684c72.jpg",
"uploadtemp/canting/2015-07/mall_873b6a973a9ecc7890109d63b93ae921.jpg",
"uploadtemp/canting/2015-07/mall_879f556066a9e99b035337ec5688e21c.jpg",
"uploadtemp/canting/2015-07/mall_87b867802320a57c475aeb4569958213.jpg",
"uploadtemp/canting/2015-07/mall_8856cbcf4270c731170fbbeb8618e3af.jpg",
"uploadtemp/canting/2015-07/mall_88c68a207cc548ed6ac948dcef38494b.jpg",
"uploadtemp/canting/2015-07/mall_88ca856b856349e9ed1d6b6563976dd8.jpg",
"uploadtemp/canting/2015-07/mall_88d2fe3a251549ce2adb2d4a477bb3aa.jpg",
"uploadtemp/canting/2015-07/mall_8913d50e7026192fe79d3053af479677.jpg",
"uploadtemp/canting/2015-07/mall_89ef0dc0fc92df4e83e1211ac499cf4f.jpg",
"uploadtemp/canting/2015-07/mall_8a2966e543c804a2a2a228ffdb4e24c4.jpg",
"uploadtemp/canting/2015-07/mall_8b0da4d4f5bf370330731e385903fc52.jpg",
"uploadtemp/canting/2015-07/mall_8b109ec3aa871a3614b2a49573998236.jpg",
"uploadtemp/canting/2015-07/mall_8b1b615ac05f8e336ba36945b821232d.jpg",
"uploadtemp/canting/2015-07/mall_8b52d21e3ab16fbf3578b2ded1017497.jpg",
"uploadtemp/canting/2015-07/mall_8b850436cc4fb1e4d6d1f5afc01006c3.jpg",
"uploadtemp/canting/2015-07/mall_8dd80cdd76afb1de534959896947d6de.jpg",
"uploadtemp/canting/2015-07/mall_8dee89caad804bea33d49172e84abd7b.jpg",
"uploadtemp/canting/2015-07/mall_8e1ef7e9581f8b3694a9357c3cf17698.jpg",
"uploadtemp/canting/2015-07/mall_8e5fbaca86f6b52b6fc28ee1e115d043.jpg",
"uploadtemp/canting/2015-07/mall_8e8b065dd167e920fa17e667b1567c86.jpg",
"uploadtemp/canting/2015-07/mall_8eb12f11e5ba2a8e38173bb1caca265d.jpg",
"uploadtemp/canting/2015-07/mall_8efb175678289f5d74bcaa520ccf98a7.jpg",
"uploadtemp/canting/2015-07/mall_8eff10c0fe8f164c069739b949a6bf63.jpg",
"uploadtemp/canting/2015-07/mall_8f723a33ea6fd49565d0b7286b584bdb.jpg",
"uploadtemp/canting/2015-07/mall_8f978c84862b06894d49bc70ddd20629.jpg",
"uploadtemp/canting/2015-07/mall_8faa711786e0dfd8489dd71095d0332a.jpg",
"uploadtemp/canting/2015-07/mall_8fd4d45f022c45d1d01cf1141b9bd95f.jpg",
"uploadtemp/canting/2015-07/mall_8fe030995f0d4f45532df61b206f5cac.jpg",
"uploadtemp/canting/2015-07/mall_90007463d37224bec9e2542a4e544e4a.jpg",
"uploadtemp/canting/2015-07/mall_920f540914845af92905248757170f23.jpg",
"uploadtemp/canting/2015-07/mall_92bf118d597fd780f822e2c9d890092d.jpg",
"uploadtemp/canting/2015-07/mall_92d986dcb820c264c10a65cfd796a4a5.jpg",
"uploadtemp/canting/2015-07/mall_92d99380fe18cd501fbd2b16e965f54a.jpg",
"uploadtemp/canting/2015-07/mall_933c821978e16c5cdc53a49128e0f380.jpg",
"uploadtemp/canting/2015-07/mall_934c25a270de1afbbec5cd4c7e678623.jpg",
"uploadtemp/canting/2015-07/mall_934c9673afd17b8258b535bae312ae16.jpg",
"uploadtemp/canting/2015-07/mall_93a940881db0168a708b9144feb1c4fb.jpg",
"uploadtemp/canting/2015-07/mall_943f85118f9ded86bb1527767c9fbf55.jpg",
"uploadtemp/canting/2015-07/mall_94b6c58685439519b8ef1506fb7353c9.jpg",
"uploadtemp/canting/2015-07/mall_98170de73fd0a420bdeaa73f36744db9.jpg",
"uploadtemp/canting/2015-07/mall_985db7ed6b5a6defb1ad4e59e1475345.jpg",
"uploadtemp/canting/2015-07/mall_9866a6a0918514a3a8a13ee49886f26c.jpg",
"uploadtemp/canting/2015-07/mall_98bc8d8e368709835e6992a0e84faa1e.jpg",
"uploadtemp/canting/2015-07/mall_98f51af21b27d79f056a18157b8b5f13.jpg",
"uploadtemp/canting/2015-07/mall_990016f90e5dfbf04849e7fd2b85c08c.jpg",
"uploadtemp/canting/2015-07/mall_99c569a39988c96503a8fa89574ec4a7.jpg",
"uploadtemp/canting/2015-07/mall_99c5daf02efff6173cdbf402dd4dd734.jpg",
"uploadtemp/canting/2015-07/mall_9a6035b5104680ec38af36dc92697bf8.jpg",
"uploadtemp/canting/2015-07/mall_9a822d76399c41528ef6ce7649165cc3.jpg",
"uploadtemp/canting/2015-07/mall_9aa4f9e5c1dbafbd6e57a80e8fb70b02.jpg",
"uploadtemp/canting/2015-07/mall_9ab63c39233840cdc2525efe8441fc30.jpg",
"uploadtemp/canting/2015-07/mall_9b158a072c3a17f4c893aa72fb08e08a.jpg",
"uploadtemp/canting/2015-07/mall_9b184c3b519f5ced974950ee6ebceb64.jpg",
"uploadtemp/canting/2015-07/mall_9b5e8864390e0f21cf64317e691ee2a6.jpg",
"uploadtemp/canting/2015-07/mall_9b78e7bc233e0443e4a5beb832343244.jpg",
"uploadtemp/canting/2015-07/mall_9bfae47ef0c9b62d2893fe481eb5a161.jpg",
"uploadtemp/canting/2015-07/mall_9c062ebc28b746eb35347fb3ae9548e6.jpg",
"uploadtemp/canting/2015-07/mall_9cd0f15f876ab317b0ac91c8be599dec.jpg",
"uploadtemp/canting/2015-07/mall_9d4fcd9da4e780c4290d07a513c67f40.jpg",
"uploadtemp/canting/2015-07/mall_a0313aeee3c19dc1e72333475d978b33.jpg",
"uploadtemp/canting/2015-07/mall_a04776c2d883bb112823f1ca8f32c3c6.jpg",
"uploadtemp/canting/2015-07/mall_a0665772d79ad83f4bd602f26db92e39.jpg",
"uploadtemp/canting/2015-07/mall_a0adcb2e76e0dbeb44a2d0501f15eed9.jpg",
"uploadtemp/canting/2015-07/mall_a0ceae882460786e9995b30341e4187d.jpg",
"uploadtemp/canting/2015-07/mall_a0d3c4949b53b3e55e8a3c7e21163369.jpg",
"uploadtemp/canting/2015-07/mall_a101980c38f0d11d75cedb0afdf21302.jpg",
"uploadtemp/canting/2015-07/mall_a13c04fddbf062f36c3b7735d4270f8c.jpg",
"uploadtemp/canting/2015-07/mall_a1454db2c9ea4feec9e517ceb05e0de6.jpg",
"uploadtemp/canting/2015-07/mall_a15012b25a80a17f561c58117a94b86d.jpg",
"uploadtemp/canting/2015-07/mall_a15989a456b9d703d02ef89e9e111e82.jpg",
"uploadtemp/canting/2015-07/mall_a28fea933ca48fd576581c8c0ff410eb.jpg",
"uploadtemp/canting/2015-07/mall_a2b111c245fd3300a1b98934357ac1a1.jpg",
"uploadtemp/canting/2015-07/mall_a2e6059547ae4ce9e55e13a45504d6ff.jpg",
"uploadtemp/canting/2015-07/mall_a39f911dc0b945c3531b1edcafcb5bee.jpg",
"uploadtemp/canting/2015-07/mall_a3d47bd2118aa64487bc0e515956f4e3.jpg",
"uploadtemp/canting/2015-07/mall_a45d34eaf89c26524e88ae7a2408e753.jpg",
"uploadtemp/canting/2015-07/mall_a469416287b5063cb5e0061e0f6590e8.jpg",
"uploadtemp/canting/2015-07/mall_a48ffec132cf5f666fdc8bd13968d312.jpg",
"uploadtemp/canting/2015-07/mall_a58f0e3c14119fc7c569fdf717ea8528.jpg",
"uploadtemp/canting/2015-07/mall_a5926645da4b83138e6771c4bbe16176.jpg",
"uploadtemp/canting/2015-07/mall_a6125ceba395542c3d90eeed6217a4e9.jpg",
"uploadtemp/canting/2015-07/mall_a63717595b41c8096f5951b54d1ac5be.jpg",
"uploadtemp/canting/2015-07/mall_a66e6ea55081ffc5235e005e6e9c4790.jpg",
"uploadtemp/canting/2015-07/mall_a690500a7e43c67207c8f35f5d49767d.jpg",
"uploadtemp/canting/2015-07/mall_a69150dc5b90f9c1dbf6a8cc8f5ce412.jpg",
"uploadtemp/canting/2015-07/mall_a72bee97bf4de9b1c5997f7e17a3eeef.jpg",
"uploadtemp/canting/2015-07/mall_a769688cd0fe89c00b52f50ada73c422.jpg",
"uploadtemp/canting/2015-07/mall_a76a22c500608a3f652d1805a11ecfda.jpg",
"uploadtemp/canting/2015-07/mall_a7849f8653b97b653206a78573643035.jpg",
"uploadtemp/canting/2015-07/mall_a7bcdb828ec17cc1b1db872bad8b2854.jpg",
"uploadtemp/canting/2015-07/mall_a875634d08c9bb509f8dd2d9a7dcc1a3.jpg",
"uploadtemp/canting/2015-07/mall_a8adab7c054cb86723cc8838f732faa6.jpg",
"uploadtemp/canting/2015-07/mall_a8cbebdb05a30566b99071631b4cebe4.jpg",
"uploadtemp/canting/2015-07/mall_a9050be3faccc38e21a1b405541cf970.jpg",
"uploadtemp/canting/2015-07/mall_aa3ffee790bf8b7cc1d335e1c39fc506.jpg",
"uploadtemp/canting/2015-07/mall_aa481261ef18e85120bc8436dcd557cc.jpg",
"uploadtemp/canting/2015-07/mall_ab3fca710d0e5b472114d888261fb388.jpg",
"uploadtemp/canting/2015-07/mall_ac2dcb7913ef008a88f251bf3745a266.jpg",
"uploadtemp/canting/2015-07/mall_ac6b70b150de0b85001ac62ebf90290a.jpg",
"uploadtemp/canting/2015-07/mall_acb6c36a847d927add49025e6aa08b54.jpg",
"uploadtemp/canting/2015-07/mall_acdce3c9de0d6a3461045d34f3263f98.jpg",
"uploadtemp/canting/2015-07/mall_acf48e2bbc980ff034a3224de4e8810a.jpg",
"uploadtemp/canting/2015-07/mall_ad2bc85f0f2faa980b266fee6edce834.jpg",
"uploadtemp/canting/2015-07/mall_ad940d4a449cbe0d0e3c0145e08f288c.jpg",
"uploadtemp/canting/2015-07/mall_add14dbedf6406b98ad0a3dcf9d1b2b9.jpg",
"uploadtemp/canting/2015-07/mall_aec0c0b83fe1e586f750bcffffee198c.jpg",
"uploadtemp/canting/2015-07/mall_aedb9cd8452a316865dd7912c12c4f89.jpg",
"uploadtemp/canting/2015-07/mall_af3b0f1c0ed21a969faa992d461cbab1.jpg",
"uploadtemp/canting/2015-07/mall_afb861dc07554488ddef26e5b30cf982.jpg",
"uploadtemp/canting/2015-07/mall_b0270530b2d8b2e30576f50fab9de5e3.jpg",
"uploadtemp/canting/2015-07/mall_b0503adede213bb8b82984509f0c4055.jpg",
"uploadtemp/canting/2015-07/mall_b0b314d8edf30bd22def10f08cd5d0e9.jpg",
"uploadtemp/canting/2015-07/mall_b0cb658390a4f7bb59a283cc3ed0bcdc.jpg",
"uploadtemp/canting/2015-07/mall_b1d1b4bc5ca0419a4c59f2f96bf51e40.jpg",
"uploadtemp/canting/2015-07/mall_b1fe2afeb5f587b65c49d2ea6940f82d.jpg",
"uploadtemp/canting/2015-07/mall_b204cda674b954a285e075e5132230c4.jpg",
"uploadtemp/canting/2015-07/mall_b301415ea3cfbed8ec9448d89fd52cd7.jpg",
"uploadtemp/canting/2015-07/mall_b321ba4e0f56302fdbadca80245b6c7e.jpg",
"uploadtemp/canting/2015-07/mall_b391ccb8683e286d711047b3eb2e179f.jpg",
"uploadtemp/canting/2015-07/mall_b429b5bf2ea040ee0fb3f310161137ec.jpg",
"uploadtemp/canting/2015-07/mall_b4a63d02f820e40eefa7e8836517159b.jpg",
"uploadtemp/canting/2015-07/mall_b4abad642e9d55bda9bb3cb64c0b1ef7.jpg",
"uploadtemp/canting/2015-07/mall_b4b2b8b6e5197bf50351f75d0aea6e24.jpg",
"uploadtemp/canting/2015-07/mall_b4fc7aea9a5157582a71e8e598f5721c.jpg",
"uploadtemp/canting/2015-07/mall_b52a1f4cecbf3894ea8fa80ebb816585.jpg",
"uploadtemp/canting/2015-07/mall_b558c1aa58e50bc30ed2cc0ba123c3f4.jpg",
"uploadtemp/canting/2015-07/mall_b64939528d4dd165c1e27e40d38574a2.jpg",
"uploadtemp/canting/2015-07/mall_b6d3fe46ae780299fbceedcfd6020b1c.jpg",
"uploadtemp/canting/2015-07/mall_b817533c47aead3314e59923e0f3b71e.jpg",
"uploadtemp/canting/2015-07/mall_b93dacefe151b35cd2ad0616e87e6369.jpg",
"uploadtemp/canting/2015-07/mall_b99c640537fda1b5cfe260cd6080492e.jpg",
"uploadtemp/canting/2015-07/mall_b9b8c2cff07788e621d05dddcf5f5506.jpg",
"uploadtemp/canting/2015-07/mall_ba50090ac8458b0b804a296a2e22bae8.jpg",
"uploadtemp/canting/2015-07/mall_ba59f2081ffcd7e07ea79f5306e08675.jpg",
"uploadtemp/canting/2015-07/mall_babe0129dec8c2ca6e3c8b8d30db0362.jpg",
"uploadtemp/canting/2015-07/mall_bb2806e2636eca8feae1431207dcd386.jpg",
"uploadtemp/canting/2015-07/mall_bbee9373de166016cf6adc31c72377da.jpg",
"uploadtemp/canting/2015-07/mall_bc077f09a5a3a75dc08cc76626b849ac.jpg",
"uploadtemp/canting/2015-07/mall_bc613c9160c8dfbb7cb2a11bf457acaf.jpg",
"uploadtemp/canting/2015-07/mall_bdf72edb79f1daa2526a0245289f767d.jpg",
"uploadtemp/canting/2015-07/mall_be030643be776e2e4162e4fe2727a901.jpg",
"uploadtemp/canting/2015-07/mall_be23e6167dbe4376abf763f6fb0664cc.jpg",
"uploadtemp/canting/2015-07/mall_be42fad477abbb2d7ea09041b7c44904.jpg",
"uploadtemp/canting/2015-07/mall_be4ab79150feac7c384a0f3d09a1d58c.jpg",
"uploadtemp/canting/2015-07/mall_becd15d509d1d46428dac57e9cc567f7.jpg",
"uploadtemp/canting/2015-07/mall_bf41b2e4c1a3fb83bde5123b869e44ce.jpg",
"uploadtemp/canting/2015-07/mall_bf4dbf3d3298bdf36060527f141ba8cf.jpg",
"uploadtemp/canting/2015-07/mall_bfd70cb076b239b67dddd18f2db207fa.jpg",
"uploadtemp/canting/2015-07/mall_c1970d9869cf1b4ac30eb09f456eea76.jpg",
"uploadtemp/canting/2015-07/mall_c1cd45ea1ae720e2cb7d18cfda85b869.jpg",
"uploadtemp/canting/2015-07/mall_c21817dcb967430c2b135b8fa32bfa2f.jpg",
"uploadtemp/canting/2015-07/mall_c21fa7920ccaa5b31b9d7a504d6cf754.jpg",
"uploadtemp/canting/2015-07/mall_c301f550555e71526e9257bfcafe8da8.jpg",
"uploadtemp/canting/2015-07/mall_c3496d91021270726bb70ade48394bb8.jpg",
"uploadtemp/canting/2015-07/mall_c381c12863973b1395cb6964bdfdfb23.jpg",
"uploadtemp/canting/2015-07/mall_c3a7dc5f1cecee27e95eb00a218b36ce.jpg",
"uploadtemp/canting/2015-07/mall_c3b3e03a1af66e45eff9ff8695f1e128.jpg",
"uploadtemp/canting/2015-07/mall_c3e603d07c731df01bfbec53bfc0d803.jpg",
"uploadtemp/canting/2015-07/mall_c4b25bc8b94902b45c126d2c91102637.jpg",
"uploadtemp/canting/2015-07/mall_c516ea5fbf2d2059e13935328bce7105.jpg",
"uploadtemp/canting/2015-07/mall_c6479d78fb7bb44e68c6de9c977a4b99.jpg",
"uploadtemp/canting/2015-07/mall_c6ee20df6d9b5d3bb189739ab2faf95b.jpg",
"uploadtemp/canting/2015-07/mall_c710479e278e8b48f227e648865ff3c9.jpg",
"uploadtemp/canting/2015-07/mall_c7cbff463a19e60365be5bb97a845531.jpg",
"uploadtemp/canting/2015-07/mall_c81cf3aada6ff25ad22fa16c6ec2e194.jpg",
"uploadtemp/canting/2015-07/mall_c83a373d6668bdf3b4950184bf96d44d.jpg",
"uploadtemp/canting/2015-07/mall_c8664a512be85b5eef8e2845fa31a819.jpg",
"uploadtemp/canting/2015-07/mall_c8b3252c6cdcbedc83cb5a1874ca1243.jpg",
"uploadtemp/canting/2015-07/mall_c8f605e1864ae2c2f15fa2b6159bccd1.jpg",
"uploadtemp/canting/2015-07/mall_c90a8b415b1cea68c0d3b48e4e63b087.jpg",
"uploadtemp/canting/2015-07/mall_c99f72433a9852f4cc208d0dcd45b9b5.jpg",
"uploadtemp/canting/2015-07/mall_c9ebb84d5176c8270c89f10ad9ade500.jpg",
"uploadtemp/canting/2015-07/mall_cbf8965f798a08f75e5fc2a9b34a0d31.jpg",
"uploadtemp/canting/2015-07/mall_cc4d86b6214d56c4ec150655a09bd7d6.jpg",
"uploadtemp/canting/2015-07/mall_cc8a854d48f8ef5a5ac6baeb0c291b9f.jpg",
"uploadtemp/canting/2015-07/mall_cce8b31a18776a7dfad3909eb9eed80c.jpg",
"uploadtemp/canting/2015-07/mall_cd389dc9b7f1534c7af5eeba9710ec41.jpg",
"uploadtemp/canting/2015-07/mall_cd6bc77cbe22e25dd18ed33445a50977.jpg",
"uploadtemp/canting/2015-07/mall_ce058c9aebea243bd0f3587460e299c9.jpg",
"uploadtemp/canting/2015-07/mall_ce08116bb6d7a62b5afdb0b41608d2ef.jpg",
"uploadtemp/canting/2015-07/mall_cf0885fe3baf13b954f1048d19c98992.jpg",
"uploadtemp/canting/2015-07/mall_cf6bf2981c8bf27682d04335f7728bfb.jpg",
"uploadtemp/canting/2015-07/mall_cf940c9df3771e0aa8dce063cadfb38d.jpg",
"uploadtemp/canting/2015-07/mall_cfab51dbc8fb3cfa61ce87e5741986f9.jpg",
"uploadtemp/canting/2015-07/mall_d15af19378f4d2cc9e931e37b4fa0c43.jpg",
"uploadtemp/canting/2015-07/mall_d297205666e827318bfbefb8571671ef.jpg",
"uploadtemp/canting/2015-07/mall_d311a2657faa8f59e0f09e9d8f331cfc.jpg",
"uploadtemp/canting/2015-07/mall_d336f04ea0b52198c6d5f41558324071.jpg",
"uploadtemp/canting/2015-07/mall_d3d72e8e7010f7a9f34b75d475b1c3be.jpg",
"uploadtemp/canting/2015-07/mall_d510f32a6979995c3344859a7211eaab.jpg",
"uploadtemp/canting/2015-07/mall_d51851527dac0c27e05e36b123588c35.jpg",
"uploadtemp/canting/2015-07/mall_d5a4cd2cbc10c4d40066028eea4dc34d.jpg",
"uploadtemp/canting/2015-07/mall_d5b19244756ffb117a37be2d4fa8bd55.jpg",
"uploadtemp/canting/2015-07/mall_d5e5e157d6a7cfaa2a14d0eaafefc806.jpg",
"uploadtemp/canting/2015-07/mall_d67003c3aff74b511dcc01022039ea93.jpg",
"uploadtemp/canting/2015-07/mall_d6dfbbdef6513f5bcc0fb74aab5c88fd.jpg",
"uploadtemp/canting/2015-07/mall_d720da1f02e90816a2282bb0ebd66e4e.jpg",
"uploadtemp/canting/2015-07/mall_d73bd792e18d48a50743b725ee41f57c.jpg",
"uploadtemp/canting/2015-07/mall_d743c8944aa61ec4ae6c140d47abec1b.jpg",
"uploadtemp/canting/2015-07/mall_d7e287a404bf947dd45d4d9dbb683561.jpg",
"uploadtemp/canting/2015-07/mall_d7e2a2b10bc6bb7879e53434e7df279d.jpg",
"uploadtemp/canting/2015-07/mall_d86c95222f3f83d426919087df4b7566.jpg",
"uploadtemp/canting/2015-07/mall_d8c2ce1455ea76730fc2ffd25ff3994e.jpg",
"uploadtemp/canting/2015-07/mall_da3d6ab36d69926baca0093790a7d056.jpg",
"uploadtemp/canting/2015-07/mall_da7597fd2a6cc4c6a13d5c7045f6c0ed.jpg",
"uploadtemp/canting/2015-07/mall_da87735c3efe6600a5b6c37f0883a331.jpg",
"uploadtemp/canting/2015-07/mall_da87af0f33964e6de9b13d165dbad58d.jpg",
"uploadtemp/canting/2015-07/mall_dacbe15b2c4904e24c8792e5c3fd8abe.jpg",
"uploadtemp/canting/2015-07/mall_db7c100875257e928c4dcf2e08bd5889.jpg",
"uploadtemp/canting/2015-07/mall_dc789be9869a1e13e985be04faaad6f3.jpg",
"uploadtemp/canting/2015-07/mall_dc857f5c516830599753c04ddea0c291.jpg",
"uploadtemp/canting/2015-07/mall_dcd7119218e9744a47ebf96fa12b1533.jpg",
"uploadtemp/canting/2015-07/mall_dd5e4f4077c10ce5daeb87457bada3c8.jpg",
"uploadtemp/canting/2015-07/mall_dd6a1568b712658aa8ec2b8957006c5d.jpg",
"uploadtemp/canting/2015-07/mall_dd98574a762b0bb5652d9922a41a6301.jpg",
"uploadtemp/canting/2015-07/mall_dde671ad3b7f0262e6f0294dac0aa158.jpg",
"uploadtemp/canting/2015-07/mall_ddf141806243c44304f5c5850f14e8bd.jpg",
"uploadtemp/canting/2015-07/mall_ddfddf7d927cde6203beae1509ab61f5.jpg",
"uploadtemp/canting/2015-07/mall_de3097264d1902f1d335979d33809ad8.jpg",
"uploadtemp/canting/2015-07/mall_de4b1249d7aadb64d20ae9e1adf69eb1.jpg",
"uploadtemp/canting/2015-07/mall_dea3b183aa9c62336d828e6aa4780e08.jpg",
"uploadtemp/canting/2015-07/mall_dee2df21d533dd40c44d0c12ca40e26b.jpg",
"uploadtemp/canting/2015-07/mall_df079d5b81a944839c32d6e35599391a.jpg",
"uploadtemp/canting/2015-07/mall_dfc20ad23df9eef77789b88bde61f5fe.jpg",
"uploadtemp/canting/2015-07/mall_dffaacbb45cdff617ee21afce701c44d.jpg",
"uploadtemp/canting/2015-07/mall_e0a619232f8ba0b86575afe42eee9d71.jpg",
"uploadtemp/canting/2015-07/mall_e0c705db686ce14bf5b31f5cfd1d3ca4.jpg",
"uploadtemp/canting/2015-07/mall_e128dac0bf4556cec568258054cc964d.jpg",
"uploadtemp/canting/2015-07/mall_e1346da926bb246257da30350d2b8e83.jpg",
"uploadtemp/canting/2015-07/mall_e1b5d9e85a5ae298f6c24f6786f2fe44.jpg",
"uploadtemp/canting/2015-07/mall_e265487a854e98e5386fd68261a3b568.jpg",
"uploadtemp/canting/2015-07/mall_e2fec0884a3b43de475f5a1f41d542ba.jpg",
"uploadtemp/canting/2015-07/mall_e3912fe6da39d7109bdfdd97560c2020.jpg",
"uploadtemp/canting/2015-07/mall_e3e4f0aa5d3bbc979a690c60356f4402.jpg",
"uploadtemp/canting/2015-07/mall_e3e9bee6a4cc5c5baa9196620eff3bc0.jpg",
"uploadtemp/canting/2015-07/mall_e4eb49fbf8d0a4e2f536f8e6f6b7674d.jpg",
"uploadtemp/canting/2015-07/mall_e50fdb25ea0c1b8437440c00d2891553.jpg",
"uploadtemp/canting/2015-07/mall_e5281e72e2d10318468a41f499c9d37d.jpg",
"uploadtemp/canting/2015-07/mall_e53e83345f5efd24a9ea6e7d6edbc3e7.jpg",
"uploadtemp/canting/2015-07/mall_e64d17d8b250cb8a9e0a9e642720ee70.jpg",
"uploadtemp/canting/2015-07/mall_e6d0321ad83ef266757c44e58ad9b0d9.jpg",
"uploadtemp/canting/2015-07/mall_e6d831606348ae8fcaf4caa608a67409.jpg",
"uploadtemp/canting/2015-07/mall_e6ef074079e290b6c197f8f34b5aeebf.jpg",
"uploadtemp/canting/2015-07/mall_e6f291d759d2c4b313132059d92d4eb2.jpg",
"uploadtemp/canting/2015-07/mall_e75703dca320eb4d5c6efb7d79d4bc1b.jpg",
"uploadtemp/canting/2015-07/mall_e8280038ead6469a26e547adba67ff5d.jpg",
"uploadtemp/canting/2015-07/mall_e846d890f5121a294375a155b50c4d6a.jpg",
"uploadtemp/canting/2015-07/mall_e86333c6a669ace7241e84106087fd91.jpg",
"uploadtemp/canting/2015-07/mall_e895329f356f68a6133c2445c86581c1.jpg",
"uploadtemp/canting/2015-07/mall_e91c8b3369f2d34943c2de99c2084738.jpg",
"uploadtemp/canting/2015-07/mall_e96659f97eb11ab8cb0d287fa92e1e01.jpg",
"uploadtemp/canting/2015-07/mall_e9cddec8d04142f943b023819bbc5b4e.jpg",
"uploadtemp/canting/2015-07/mall_e9e951381c579d0925923a9913f553d0.jpg",
"uploadtemp/canting/2015-07/mall_ea4952159378727c76c9c2737ee41d9f.jpg",
"uploadtemp/canting/2015-07/mall_ea6d8dbd3c59574ea6ebb8fb3c0c7f15.jpg",
"uploadtemp/canting/2015-07/mall_ea7ae111920c33c75f8c92949b58d07f.jpg",
"uploadtemp/canting/2015-07/mall_eacec50bb6578e58f078b1b89537e3f6.jpg",
"uploadtemp/canting/2015-07/mall_eb3201a8adf20f897ab8c29d91ed3f1b.jpg",
"uploadtemp/canting/2015-07/mall_ebcce80f15149bff9652a7f07a0c22f3.jpg",
"uploadtemp/canting/2015-07/mall_ec0fa89b85b81e18d575939a685b7cdb.jpg",
"uploadtemp/canting/2015-07/mall_ec126262999f93a88f13ddb5735eafc5.jpg",
"uploadtemp/canting/2015-07/mall_ec8a58ce353d4f04f163ab567eeb4630.jpg",
"uploadtemp/canting/2015-07/mall_ecc3a662b0821c51d3c35a4dccae5c89.jpg",
"uploadtemp/canting/2015-07/mall_edd2ecdb1f24977317d9679bbf883a75.jpg",
"uploadtemp/canting/2015-07/mall_ee09bd49f3ea965b37be59e17f3c4394.jpg",
"uploadtemp/canting/2015-07/mall_ef11ae32ec84ef31cae40e46f82869b6.jpg",
"uploadtemp/canting/2015-07/mall_ef2c3b324c4cf667800dd3d64833145c.jpg",
"uploadtemp/canting/2015-07/mall_ef6df7654336c7cdbc2149145f825352.jpg",
"uploadtemp/canting/2015-07/mall_f08fcda7c6dd2f23cea32f5f98aebb06.jpg",
"uploadtemp/canting/2015-07/mall_f0d94d63de6e259ca3b484ab82c69d72.jpg",
"uploadtemp/canting/2015-07/mall_f0ffe2260af9a2bac2fb7b7201357d6b.jpg",
"uploadtemp/canting/2015-07/mall_f1a9c5674614e8bdb84c10c2d6119413.jpg",
"uploadtemp/canting/2015-07/mall_f33447bac7e2f1dbc30e73ba08dec53e.jpg",
"uploadtemp/canting/2015-07/mall_f3568193e631b85f883f3ddecf731bfd.jpg",
"uploadtemp/canting/2015-07/mall_f42ad3ade3aa4ca9bc7b3517ad88eaf2.jpg",
"uploadtemp/canting/2015-07/mall_f54452a92a9c3c1bb74e66779f601617.jpg",
"uploadtemp/canting/2015-07/mall_f586a4cd48c5b46feb6f4cac783e501f.jpg",
"uploadtemp/canting/2015-07/mall_f5e344f47d8043985a42ba934f4eec01.jpg",
"uploadtemp/canting/2015-07/mall_f65241ec7d79fbfc7e5b9629059aaddf.jpg",
"uploadtemp/canting/2015-07/mall_f6b9395dc6ddf86b5a7b4a6227d9f362.jpg",
"uploadtemp/canting/2015-07/mall_f754a34fff424a382d625ad7f6415c5c.jpg",
"uploadtemp/canting/2015-07/mall_f76c43f590ab620a8e968fc432be9590.jpg",
"uploadtemp/canting/2015-07/mall_f873f56cf68376ba5e7bbd97ad381600.jpg",
"uploadtemp/canting/2015-07/mall_f8c7a37da705ab53995d8eaab699f190.jpg",
"uploadtemp/canting/2015-07/mall_f9cc9d5389b40a5b633243b930146cb5.jpg",
"uploadtemp/canting/2015-07/mall_f9cd43eb550a6f5cc7e357f9562ec6d3.jpg",
"uploadtemp/canting/2015-07/mall_fa2288b454741b407cac682d295e025a.jpg",
"uploadtemp/canting/2015-07/mall_fa258c1d0dccdaef33b0521b2088eecb.jpg",
"uploadtemp/canting/2015-07/mall_fabdc3c69dd38aabedfcb2064bbb5640.jpg",
"uploadtemp/canting/2015-07/mall_fae65f0deba2c5ff070346e64455de3f.jpg",
"uploadtemp/canting/2015-07/mall_faf35b629552014ab891e206fb2bb537.jpg",
"uploadtemp/canting/2015-07/mall_fb186d2b0d01bb5b58122396fa433753.jpg",
"uploadtemp/canting/2015-07/mall_fb3588a168e348c0621139cce4a9e7f7.jpg",
"uploadtemp/canting/2015-07/mall_fc150163f44e2a96ec9e3035c31dc642.jpg",
"uploadtemp/canting/2015-07/mall_fc2c537f54e4f3e85c1cc6c63564f7c5.jpg",
"uploadtemp/canting/2015-07/mall_fcb7e14d2c46fb6ebedec1130ac42366.jpg",
"uploadtemp/canting/2015-07/mall_fe16eda06f844bd89c68d27d5ed77ab5.jpg",
"uploadtemp/canting/2015-07/mall_fe28e1ed583d125822971f4113dfdc59.jpg",
"uploadtemp/canting/2015-07/mall_fe994df2dc795f4df0eb36a5b935095f.jpg",
"uploadtemp/canting/2015-07/mall_feda6ce1082177e2b0909899fc07744d.jpg",
"uploadtemp/canting/2015-07/mall_ff7e015eec28e2500467bac3a6c662d1.jpg",
"uploadtemp/canting/2015-07/mall_fffbd40340a9275bea7c39ae92c43560.jpg",

			);
			foreach ($arr as $k => $v) {
				$where = array(
					'picPath' => $v,
					);
				$queryRes = $this->db->get_where(tname('restaurant'), $where)->result();

				if (count($queryRes) <= 0) {
					continue;
				}
				echo $queryRes->name_s.'<br/>';
			}

		// $this->outData['pageTitle'] = $this->lang->line('TEXT_COUPON_TITLE_ANALYSIS');

		// $this->load->view('Coupon/analysis', $this->outData);
	}

	/**
	 * 优惠券使用状态
	 *
	 */
	public function statelist(){
		$this->outData['pageTitle'] = $this->lang->line('TEXT_STATELIST');

		$couponId = $this->input->post('couponId');

		$this->outData['couponData'] = $this->CouponModel->getStateList($couponId);

		$this->outData['selectCouponId'] = $couponId;

		$this->load->view('Coupon/statelist', $this->outData);
	}

	/**
	 * ajax上传优惠券图片
	 *
	 */
	public function uploadCouponPic(){

		if ($this->input->method() != 'post') {
			echo json_encode($this->ajaxRes);exit;
		}

		$uploadConf = config_item('FILE_UPLOAD');

		$uploadConf['upload_path']   = './uploadtemp/coupon/';
		$uploadConf['file_name']     = 'coupon_'.md5(currentTime('MICROTIME'));
		$uploadConf['relation_path'] = '/alidata1/apps/uploadtemp_app_admin_sj/coupon/';


		$this->load->library('upload');

		$this->upload->initialize($uploadConf);

		if (!$this->upload->do_upload('image')){
			$this->ajaxRes['msg'] = $this->upload->display_errors();
		}else{
			$this->ajaxRes = array(
					'status' => 0,
					'url'    => config_item('image_url').$this->upload->data('relative_path'),
					'path'   => $this->upload->data('relative_path'),
				);
		}

		echo json_encode($this->ajaxRes);exit;

	}

	/**
	 * 遍历选中的mall
	 * @param array $mallList
	 * @param array $checkedMall
	 */
	private function _fetchCheckMall($mallList = array(), $checkedMall = array()){

		$checkedMallArr = array();
		foreach ($checkedMall as $k => $v) {
			if (!in_array($v['id'], $checkedMallArr)) {
				array_push($checkedMallArr, $v['id']);
			}
		}

		foreach ($mallList as $k => $v) {
			if (in_array($v['mallID'], $checkedMallArr)) {
				$mallList[$k]['checked'] = true;
			}
		}

		return $mallList;
	}

	/**
	 * 验证优惠券添加
	 */
	private function _verlidationAddCoupon(){

		$this->form_validation->set_rules($this->lang->line('ADD_COUPON_VALIDATION'));

		if (!$this->form_validation->run()) {
			return validation_errors();
		}

		// 设置优惠券金额
		if ((int)$this->input->post('couponMoney') === 2) {
			if ((float)$this->input->post('couponMoneyNum') <= 0) {
				return $this->lang->line('ERR_COUPON_MONEY_NUM');
			}
		}

		if ($this->input->post('couponEveryoneSum') > $this->input->post('couponSum')) {
			return $this->lang->line('ERR_COUPON_EVERYONE_LIMIT');
		}

		// 验证有效期、领取期
		$couponDate = array(
				explode(' - ', $this->input->post('couponExpireDate')),
				explode(' - ', $this->input->post('couponReceiveDate')),
			);

		foreach ($couponDate as $k => $v) {

			if ($k == 0) $couponExpireDate = strtotime($v[1]);

			if (strtotime($v[1]) <= strtotime($v[0])) {
				$langLine = $k == 0 ? 'ERR_COUPON_EXPIRE_DATE_FORMAT' : 'ERR_COUPON_RECEIVEDATE_FORMAT';
				return $this->lang->line($langLine);
			}
		}

		// 验证使用时间
		if (strtotime($this->input->post('couponUseTimeStart')) >= strtotime($this->input->post('couponUseTimeEnd'))) {
			return $this->lang->line('ERR_USETIME');
		}

		// 验证审核
		if (in_array($this->input->post('reviewPass'), config_item('COUPON_REVIEWPASS'))) {
			if ($this->input->post('reviewPass') == 2) {
				$today = strtotime($this->input->post('reviewPassDate')) <= time() ? false : true;
				$expireDay = strtotime($this->input->post('reviewPassDate')) >= $couponExpireDate ? false : true;
				if (!$today || !$expireDay) return $this->lang->line('ERR_REVIEWPASS_DATE');
			}
		}else{
			return $this->lang->line('ERR_REVIEW_PASS');
		}

		return true;
	}
}
