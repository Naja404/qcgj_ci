<?php $this->load->view('Public/header');?>

		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/ace-skins.min.css" />
		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/datepicker.css" />
		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/daterangepicker.css" />

		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="<?php echo config_item('html_url');?>css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->

		<script src="<?php echo config_item('html_url');?>js/ace-extra.min.js"></script>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="<?php echo config_item('html_url');?>js/html5shiv.js"></script>
		<script src="<?php echo config_item('html_url');?>js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>

		<?php $this->load->view('Public/navbar');?>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>

				<?php $this->load->view('Public/sidebar');?>

				<div class="main-content">
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="icon-home home-icon"></i>
								<a href="<?php echo site_url();?>"><?php echo $this->lang->line('TEXT_HOME_PAGE');?></a>
							</li>

							<li>
								<a href="{:U('Coupon/rolelist')}"><?php echo $this->lang->line('TEXT_COUPON_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TEXT_COUPON_LIST');?></li>
						</ul>
						<div class="nav-search">
							<a href="<?php echo base_url('Coupon/addCoupon');?>"><button class="btn btn-xs btn-primary">添加优惠券</button></a>
						</div>
						
					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">

								<form method="get" action="<?php echo site_url('Coupon/couponList');?>">

									时间范围:
									<input type="text" name="dateRange" id="dateRange" value="<?php echo $this->input->get('dateRange');?>" style="width:180px;">
									&nbsp;
									&nbsp;
									&nbsp;
									优惠券标题:<input type="text" name="title" value="<?php echo $this->input->get('title');?>">
									&nbsp;
									&nbsp;
									&nbsp;
									状态:
									<select name="status">
										<option value="">全部</option>
										<?php foreach (config_item('COUPON_STATUS') as $k => $v) {?>
										<option value="<?php echo $v['id'];?>" <?php echo (string)$this->input->get('status') == $v['id'] ? 'selected' : '';?>><?php echo $v['name'];?></option>
										<?php } ?>
									</select>
									<button type="submit"><?php echo $this->lang->line('BTN_SEARCH');?></button>
								</form>
								<br>

								<div class="row">
									<div class="col-xs-12">

								<div class="row-fluid">
									<div class="span12">
										<div class="table-responsive">
											<table id="rolelist-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															<label>
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</th>
														<th><?php echo $this->lang->line('TEXT_COUPON_TITLE');?></th>
														<th><?php echo $this->lang->line('TEXT_COUPON_EXPIRE');?></th>
														<th><?php echo $this->lang->line('TEXT_COUPON_STATUS');?></th>
														<th><?php echo $this->lang->line('TEXT_COUPON_RECEIVECOUNT');?></th>
														<th><?php echo $this->lang->line('TEXT_COUPON_USECOUNT');?></th>
														<th><?php echo $this->lang->line('TEXT_COUPON_SHOPCOUNT');?></th>
														<th><?php echo $this->lang->line('TEXT_OPERATION');?></th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($couponList as $v):?>
													<tr>
														<td class="center">
															<label>
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</td>

														<td>
															<a href="#modal-review-coupon" role="button" data-toggle="modal" onclick="getCouponDetail('<?php echo $v->id;?>')"><?php echo $v->title;?></a>
														</td>
														<td><?php echo $v->expire;?></td>
														<td id="userSpan_<?php echo strEncrypt($v->id);?>"><?php echo $this->lang->line('TEXT_STATUS_'.$v->saleStatus);?></td>
														<td><?php echo $v->received;?></td>
														<td><?php echo $v->used;?></td>
														<td><?php echo $v->mallCount;?></td>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																<a style="color:red;" onclick="delCoupon('<?php echo strEncrypt($v->id);?>');">
																	<i class="icon-trash bigger-120">删除</i>
																</a>
																&nbsp;
																<?php if($v->saleStatus == 0 && $this->userInfo->role_id != 1){?>
																<a onclick="editCoupon('<?php echo strEncrypt($v->id);?>');">
																	<i class="icon-edit bigger-120">编辑</i>
																</a>
																<?php } ?>
																<?php if($this->userInfo->role_id == 1){?>
																<a onclick="editCoupon('<?php echo strEncrypt($v->id);?>', '<?php echo $v->tb_brand_id;?>');">
																	<i class="icon-edit bigger-120">编辑</i>
																</a>
																<?php } ?>
																&nbsp;
																<a href="#modal-recommend-coupon" role="button" data-toggle="modal" onclick="getRecommend('<?php echo $v->id;?>')">
																	<i class="icon-external-link bigger-120">推荐</i>
																</a>
																&nbsp;
																<?php if($v->saleStatus == 2){?>
																<a onclick="saleCoupon('<?php echo strEncrypt($v->id);?>');" >
																	<i id="userIcon_<?php echo strEncrypt($v->id);?>" class="icon-ok bigger-120 green">上架</i>
																</a>
																<input type="hidden" name="userHide_<?php echo strEncrypt($v->id);?>" value="1" />
																<?php }else{ ?>
																<a onclick="saleCoupon('<?php echo strEncrypt($v->id);?>');" >
																	<i id="userIcon_<?php echo strEncrypt($v->id);?>" class="icon-remove bigger-120 red">下架</i>
																</a>
																<input type="hidden" name="userHide_<?php echo strEncrypt($v->id);?>" value="2" />
																<?php }?>
															</div>

														</td>
													</tr>
													<?php endforeach;?>
												</tbody>
											</table>
										</div><!-- /.table-responsive -->
									</div>
								</div>

									</div><!-- /span -->
								</div>
								<?php echo $couponListPage;?>
							</div><!-- /.col -->

						</div><!-- /.row -->

						<div id="modal-review-coupon" class="modal fade" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header no-padding">
										<div class="table-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												<span class="white">&times;</span>
											</button>
											优惠券预览
										</div>
									</div>

									<div class="modal-body no-padding">
										<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
											<tbody>
												<tr>
													<td style="width:100px;">所属品牌
													</td>
													<td id="modal_brand_name">
													</td>
												</tr>
												<tr>
													<td style="width:100px;">
														优惠券标题
													</td>
													<td id="modal_name">

													</td>
												</tr>

												<tr>
													<td>
														优惠券类型
													</td>
													<td id="modal_type">

													</td>
												</tr>
												<tr>
													<td>
														优惠券金额
													</td>
													<td id="modal_price">

													</td>
												</tr>
												<tr>
													<td>
														优惠券总数
													</td>
													<td id="modal_count">

													</td>
												</tr>
												<tr>
													<td>
														每人领取上限
													</td>
													<td id="modal_limit_received">

													</td>
												</tr>
												<tr>
													<td>
														有效期
													</td>
													<td id="modal_expire_date">

													</td>
												</tr>
												<tr>
													<td>
														领取期
													</td>
													<td id="modal_received_date">

													</td>
												</tr>
												<tr>
													<td>
														使用说明
													</td>
													<td id="modal_use_note">

													</td>
												</tr>
												<tr>
													<td>
														验券说明
													</td>
													<td id="modal_check_note">

													</td>
												</tr>
												<tr>
													<td>
														优惠券码类型
													</td>
													<td id="modal_code_type">

													</td>
												</tr>
												<tr>
													<td>
														验券合作商
													</td>
													<td id="modal_partner">

													</td>
												</tr>
												<tr>
													<td>
														所在商场
													</td>
													<td id="modal_mall">

													</td>
												</tr>
											</tbody>
										</table>
									</div>

									<div class="modal-footer no-margin-top">
										<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal" id="">
											<i class="icon-remove"></i>
											<?php echo $this->lang->line('BTN_CLOSE');?>
										</button>
									</div>
								</div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div>

						<div id="modal-recommend-coupon" class="modal fade" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header no-padding">
										<div class="table-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												<span class="white">&times;</span>
											</button>
											优惠券推荐
										</div>
									</div>

									<div class="modal-body no-padding">
										<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
											<tbody>
												<tr>
													<td style="width:100px;">
														状态
													</td>
													<td>
														<label class="blue">
															<input type="radio" id="recStatus" name="recStatus" value="1">
															<span class="lbl">推荐</span>
														</label>
														&nbsp;
														&nbsp;
														<label class="red">
															<input type="radio" id="recStatus" name="recStatus" value="0">
															<span class="lbl">不推荐</span>
														</label>
													</td>
												</tr>
												<tr>
													<td style="width:100px;">
														排序
													</td>
													<td>
														<div class="clearfix">
															<input  type="text" id="recSort" placeholder="数字越小排序越前" value="" >
														</div>
													</td>
												</tr>
											</tbody>
										</table>
										<input type="hidden" id="recCouponId" value="">
									</div>

									<div class="modal-footer no-margin-top">
										<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal" id="">
											<i class="icon-remove"></i>
											<?php echo $this->lang->line('BTN_CLOSE');?>
										</button>
										<button class="btn btn-sm pull-left" onclick="setRecommend();">
											<i class="icon-info"></i>
											保存
										</button>
									</div>
								</div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div>

					</div><!-- /.page-content -->
				</div><!-- /.main-content -->
			</div><!-- /.main-container-inner -->

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->

		<script src="<?php echo config_item('html_url');?>js/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo config_item('html_url');?>js/jquery-ui-1.10.3.full.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="<?php echo config_item('html_url');?>js/jquery-1.10.2.min.js"></script>
<![endif]-->

		<!--[if !IE]> -->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo config_item('html_url');?>js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
		<script type="text/javascript">
		 window.jQuery || document.write("<script src='<?php echo config_item('html_url');?>js/jquery-1.10.2.min.js'>"+"<"+"/script>");
		</script>
		<![endif]-->
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo config_item('html_url');?>js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo config_item('html_url');?>js/bootstrap.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/typeahead-bs2.min.js"></script>

		<!-- page specific plugin scripts -->
		
		<!-- ace scripts -->
		<script src="<?php echo config_item('html_url');?>js/date-time/moment.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/daterangepicker.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			function getCouponDetail(couponId){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/getCouponDetail');?>",
					data:{couponId:couponId},
					success:function(data){
						if (data.status == '0') {
							$('#modal_brand_name').html(data.data.brandName.name_zh+' '+data.data.brandName.name_en);
							$('#modal_name').html(data.data.name);
							$('#modal_type').html(data.data.typeHTML);
							$('#modal_price').html(data.data.price);
							$('#modal_count').html(data.data.limit_count_used);
							$('#modal_limit_received').html(data.data.limit_count_per_person);
							$('#modal_expire_date').html(data.data.couponExpireDate);
							$('#modal_received_date').html(data.data.couponReceiveDate);
							$('#modal_use_note').html(data.data.coupon_desc);
							$('#modal_check_note').html(data.data.recommend_desc);
							$('#modal_code_type').html(data.data.geneHTML);
							$('#modal_partner').html(data.data.partnerHTML);
							$('#modal_mall').html(data.data.mallHTML);
							
							return true;
						}
						alert(data.msg);
						return false;
					}
				});	
			}

			function saleCoupon(couponId){
				var couponStatus = $('input[name=userHide_'+couponId+']').val();

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/saleCoupon');?>",
					data:{couponId:couponId, status:couponStatus},
					success:function(data){
						if (data.status == '0') {
							$('#userSpan_' + couponId).html(data.html);
							$('#userIcon_'+couponId).attr("class", data.class);
							$('#userIcon_'+couponId).text(data.showText);
							$('input[name=userHide_'+couponId+']').val(data.couponStatus);
							return true;
						}
						alert(data.msg);
						return false;
					}
				});
			}

			function getRecommend(couponId){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/getCouponDetail');?>",
					data:{couponId:couponId},
					success:function(data){
						if (data.status == '0') {
							// $('#recStatus').html(data.data.status);
							$("input[type=radio][value="+data.data.status+"]").attr("checked",'checked');
							$('#recSort').val(data.data.sort_num);
							$('#recCouponId').val(data.data.id);
							return true;
						}
						alert(data.msg);
						return false;
					}
				});	
			}

			function setRecommend(){
				var recStatus = $("#recStatus").val();
					recSort = $("#recSort").val();
					recCouponId = $("#recCouponId").val();
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/setRecommend');?>",
					data:{couponId:recCouponId, status:recStatus, sort:recSort},
					success:function(data){
						if (data.status == '0') {
							$("#modal-recommend-coupon").modal('hide');
							return true;
						}
						alert(data.msg);
						return false;
					}
				});	
			}

			jQuery(function($) {
						// 日期选择
				$('input[name=dateRange]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});
			})

			function editCoupon(couponId, brandId){
				window.location.href = "<?php echo site_url('Coupon/editCoupon');?>"+"?couponId="+couponId+"&brand="+brandId;
			}

			function delCoupon(couponId){
				if (!confirm("<?php echo $this->lang->line('TEXT_CONFIRM_DEL_COUPON');?>")) {
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/delCoupon');?>",
					data:{couponId:couponId},
					success:function(data){
						if (data.status) {
							alert(data.msg);
							return false;
						}else{
							window.location.reload();
						}
					}
				});
			}

			function setCouponStatus(obj, couponId){

				var reqStatus = obj.value;

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/setCouponStatus');?>",
					data:{couponId:couponId, reqStatus:reqStatus},
					success:function(data){
						if (data.status == '0') {

							return true;
						}

						alert(data.msg);
						return false;
					}
				});
			}
		</script>
	</body>
</html>

