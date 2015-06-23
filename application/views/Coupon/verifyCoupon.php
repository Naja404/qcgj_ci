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
							<li class="active"><?php echo $this->lang->line('TEXT_COUPON_VERIFY');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
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
														<th><?php echo $this->lang->line('TEXT_CITY_NAME');?></th>
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
															<a href="#"><?php echo $v->title;?></a>
														</td>
														<td><?php echo $v->expire;?></td>
														<td id="userSpan_<?php echo strEncrypt($v->id);?>"><?php echo $this->lang->line('TEXT_STATUS_'.$v->status);?></td>
														<td><?php echo $v->cityName;?></td>
														<td><?php echo $v->received;?></td>
														<td><?php echo $v->used;?></td>
														<td><?php echo $v->mallCount;?></td>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																<button class="btn btn-xs btn-danger" onclick="delCoupon('<?php echo strEncrypt($v->id);?>');">
																	<i class="icon-trash bigger-120"></i>
																</button>
																<?php if($v->status == 0){?>
																<button class="btn btn-xs btn-info" onclick="saleCoupon('<?php echo strEncrypt($v->id);?>');">
																	<i id="userIcon_<?php echo strEncrypt($v->id);?>" class="icon-ok bigger-120"></i>
																</button>
																<input type="hidden" name="userHide_<?php echo strEncrypt($v->id);?>" value="1" />
																<?php } ?>
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

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
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
							$('input[name=userHide_'+couponId+']').val(data.couponStatus);
							return true;
						}
						alert(data.msg);
						return false;
					}
				});
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
		</script>
	</body>
</html>

