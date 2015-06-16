<?php $this->load->view('public/header');?>

		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/ace-skins.min.css" />

		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/jquery-ui-1.10.3.full.min.css" />


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
								<a href="<?php echo config_item('base_url');?>"><?php echo $this->lang->line('TEXT_HOME_PAGE');?></a>
							</li>

							<li>
								<a href="<?php echo site_url('Coupon/couponList');?>"><?php echo $this->lang->line('TEXT_COUPON_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TEXT_STATELIST');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>
						<div class="row">
							<div class="col-sm-8">
								<form class="form-horizontal" action="<?php echo site_url('Coupon/statelist');?>" method="post" id="couponForm">
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo $this->lang->line('TEXT_COUPON_TITLE');?>: </label>
										<div class="col-sm-3">
											<select name="couponId" id="couponIdSelect">
												<?php foreach ($couponData['couponList'] as $k => $v) {?>
												<option value="<?php echo $v['couponId'];?>" <?php echo $v['couponId'] == $selectCouponId ? 'selected' : '';?>><?php echo $v['title'];?></option>
												<?php }?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo $this->lang->line('TEXT_COUPON_RECEIVECOUNT');?>: </label>
										<div class="col-sm-3">
											<?php echo $couponData['receivedCoupon']['total'];?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo $this->lang->line('TEXT_COUPON_USECOUNT');?>: </label>
										<div class="col-sm-3">
											<?php echo $couponData['usedCoupon']['total'];?>
										</div>
									</div>
									<div class="form-group">
										<div class="radio">
													<label>
														<input name="useState" type="radio" class="ace" onclick="" checked>
														<span class="lbl"><?php echo $this->lang->line('TEXT_RECEIVED');?></span>
													</label>
													&nbsp;&nbsp;&nbsp;&nbsp;
													<label>
														<input name="useState" type="radio" class="ace" onclick="">
														<span class="lbl"><?php echo $this->lang->line('TEXT_USED');?></span>
													</label>
										</div>
									</div>
								</form>
							</div>
							<div class="col-sm-8">
							<canvas id="canvas"></canvas>
							</div>
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

		<!-- ace scripts -->

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/Chart.min.js"></script>
		<script type="text/javascript">
			var lineChartData = {
				labels : <?php echo json_encode($couponData['receivedCoupon']['labels']);?>,
				datasets : [
					{
						label: "My First dataset",
						fillColor : "rgba(220,220,220,0.2)",
						strokeColor : "rgba(220,220,220,1)",
						pointColor : "rgba(220,220,220,1)",
						pointStrokeColor : "#fff",
						pointHighlightFill : "#fff",
						pointHighlightStroke : "rgba(220,220,220,1)",
						data : <?php echo json_encode($couponData['receivedCoupon']['data']);?>
					}
				]

			}
			jQuery(function($) {
				var ctx = document.getElementById("canvas").getContext("2d");
				window.myLine = new Chart(ctx).Line(lineChartData, {
					responsive: true
				});

				$("#couponIdSelect").on('change', function(){
					$("#couponForm").submit();
				})
			})

			function changeChart(){
				// console.log(window.myLine);
			}
		</script>

	</body>
</html>
