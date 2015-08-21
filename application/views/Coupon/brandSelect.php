<?php $this->load->view('Public/header');?>

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
							<li class="active"><?php echo $this->lang->line('TEXT_TITLE_BRAND_SELECT');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="addManagerForm" novalidate="novalidate">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandZH"> 优惠券品牌（中文） </label>

										<div class="col-sm-9">
											<div class="clearfix">
											<input type="text" id="brandZH" name="brandZH" placeholder="" class="col-xs-10 col-sm-5" >
											</div>
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandEN"> 优惠券品牌（英文） </label>

										<div class="col-sm-9">
											<div class="clearfix">
											<input type="text" id="brandEN" name="brandEN" placeholder="" class="col-xs-10 col-sm-5">
											</div>
										</div>
									</div>
									<input type="hidden" name="brandId" id="brandId" value="" >
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subBrandSelect();">
												<i class="icon-ok bigger-110"></i>
												下一步											
											</button>
										</div>
									</div>
								</form>
							</div><!-- /.col-xs-12 -->
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

		<!-- ace scripts -->

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/bootbox.min.js"></script>

		<script type="text/javascript">
			jQuery(function($) {

				$("#brandZH").keyup(function(){
					
					$("#brandId").val('');

					$.ajax({
			      		type:"POST",
			      		url:"<?php echo site_url('Coupon/searchBrand');?>",
			      		data:{brand:$("#brandZH").val()},
			      		success:function(data){
			      			if (data.status != 0) { return false;}
			      			
			      			var sourceData = new Array();

			      			$.each(data.list, function(k, v){
			      				sourceData.push(v);
			      			});

						    $( "#brandZH" ).autocomplete({
						      source: sourceData,
						      select:function(event, ui){
									var brandName = ui.item.label.split('_');

									$("#brandEN").val(brandName[0]);
									$("#brandZH").val(brandName[1]);

						      		$("#brandId").val(ui.item.id);
						      }
						  });
						
			      		}
					});
				});
			});

			function subBrandSelect(){
				var brandId = $("#brandId").val();

				window.location.href = '<?php echo base_url("Coupon/addCoupon");?>'+'?brandId='+brandId;
			}
		</script>

	</body>
</html>
