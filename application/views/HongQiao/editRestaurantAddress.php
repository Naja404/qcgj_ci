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
								<a href="<?php echo site_url('Brand/listView');?>"><?php echo $this->lang->line('TITLE_BRAND_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TITLE_ADD_SHOP');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="editMall-form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mallName"> 店铺名 </label>

										<div class="col-sm-9">
											<input type="text" name="mallName" id="mallName" placeholder="" value="<?php echo !empty($detail->name_zh) ? $detail->name_zh : '';?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="floor"> 铺位/号 </label>

										<div class="col-sm-9">
											<input type="text" name="floor" id="floor" placeholder="" value="<?php echo !empty($detail->floor) ? $detail->floor : '';?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="branchName"> 分店名 </label>

										<div class="col-sm-9">
											<input type="text" name="branchName" id="branchName" placeholder="" value="<?php echo !empty($detail->branch_name) ? $detail->branch_name : '';?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="category"> 分类 </label>

										<div class="col-sm-9">
												<?php foreach ($category as $k => $v) :?>
												<label>
													<input type="radio" name="category[]" class="ace" value="<?php echo $v->id;?>" <?php echo $v->id == $detail->tb_category_id ? 'checked' : '';?>>
													<span class="lbl"><?php echo $v->name;?></span>
												</label>&nbsp;&nbsp;
												<br>
												<?php endforeach; ?>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="address"> 地址 </label>

										<div class="col-sm-9">
											<input type="text" name="address" id="address" placeholder="" value="<?php echo !empty($detail->address) ? $detail->address : '';?>"/><a class="btn btn-sm" onclick="getLngLat()">search</a>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="lng"> 经度 </label>

										<div class="col-sm-9">
											<input type="text" name="lng" id="lng" placeholder="" value="<?php echo !empty($detail->longitude) ? $detail->longitude : '';?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="lat"> 纬度 </label>

										<div class="col-sm-9">
											<input type="text" name="lat" id="lat" placeholder="" value="<?php echo !empty($detail->latitude) ? $detail->latitude : '';?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="tel"> 电话 </label>

										<div class="col-sm-9">
											<input type="text" name="tel" id="tel" placeholder="" value="<?php echo !empty($detail->tel) ? $detail->tel : '';?>"/>
										</div>
									</div>
									

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subEditMall();">
												<i class="icon-ok bigger-110"></i>
												<?php echo $this->lang->line('BTN_SUBMIT');?>
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="icon-undo bigger-110"></i>
												<?php echo $this->lang->line('BTN_RESET');?>
											</button>
										</div>
									</div>
									<input type="hidden" name="shopBrandId" id="shopBrandId" value="" >
								</form>
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
		<script src="<?php echo config_item('html_url');?>js/jquery.validate.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/bootbox.min.js"></script>

		<script src="http://api.map.baidu.com/api?v=1.5&ak=C06d8528dd571c548c6f862391f97d9f" type="text/javascript"></script>
		<script src="http://api.gjla.com:80/app_admin_v330/res/baidumap/scripts/bmap.js" type="text/javascript"></script>


		<script type="text/javascript">

		function getLngLat(){
			var myGeo = new BMap.Geocoder();
			var	address = $("#address").val();

			// 将地址解析结果显示在地图上,并调整地图视野
			myGeo.getPoint(address, function(point){
				if (point) {
					$('#lng').val(point.lng);
					$('#lat').val(point.lat);
				}else{
					$('#lng').val('');
					$('#lat').val('');
					alert('请正确输入地址内容');
				}
			}, "上海市");
		}
	
		function subEditMall(){
			if(!$('#editMall-form').valid()){
				return false;
			}

			$.ajax({
				type:"POST",
				url:"<?php echo site_url('HongQiao/editRestaurantAddress').'?id='.$this->input->get('id');?>",
				data:$('#editMall-form').serialize(),
				success:function(data){
					if (data.status == '0') {
						window.location.href = "<?php echo site_url('HongQiao/restaurantAddressList').'?p='.$this->input->get('p');?>";
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
