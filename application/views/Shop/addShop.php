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
								<a href="<?php echo site_url('Shop/shopList');?>"><?php echo $this->lang->line('TEXT_SHOP_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TEXT_ADD_SHOP');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="addShopForm">

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandZh"> 品牌中文 </label>

										<div class="col-sm-9">
											<input type="text" name="brandZh" id="brandZh" placeholder="可输入品牌英文">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandEn"> 品牌英文 </label>

										<div class="col-sm-9">
											<input type="text" value="" name="brandEn" id="brandEn" disabled>
										</div>
									</div>
									<input type="hidden" name="brandId" id="brandId" >
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopType"> 门店类型 </label>

										<div class="col-sm-9">
											<label class="blue">
												<input name="shopType" value="1" type="radio" class="ace" checked />
												<span class="lbl">商场</span>
											</label>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<label class="blue">
												<input name="shopType" value="2" type="radio" class="ace"  />
												<span class="lbl">街边店</span>
											</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="searchShop"> 搜索门店 </label>

										<div class="col-sm-9">
											<input type="text" name="searchShop" id="searchShop"  placeholder="商场名/地址"><a class="btn btn-sm" onclick="searchShop();">search</a>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopId"> </label>

										<div class="col-sm-9" id="shopListDiv">

										</div>
									</div>


									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="floor"><?php echo $this->lang->line('TEXT_ADDRESS');?></label>
										<div class="col-sm-9">
											<input type="text" name="floor" value="" placeholder="请填写楼层">
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subAddShop();">
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

		<script type="text/javascript">
			jQuery(function($) {

				$("#brandZh").keyup(function(){

					$("#brandId").val('');

					$.ajax({
			      		type:"POST",
			      		url:"<?php echo site_url('Brand/searchBrand');?>",
			      		data:{brand:$("#brandZh").val(), status:1},
			      		success:function(data){
			      			if (data.status != 0) { return false;}
			      			
			      			var sourceData = new Array();

			      			$.each(data.list, function(k, v){
			      				sourceData.push(v);
			      			});

						    $("#brandZh").autocomplete({
						      source: sourceData,
						      select:function(event, ui){
								var brandName = ui.item.label.split('_');

									$("#brandEn").val(brandName[0]);
									$("#brandZh").val(brandName[1]);

						      		$("#brandId").val(ui.item.id);
						      }
						  });
						
			      		}
					});
				});

				$('#cityName').on('change', function(){
					$.ajax({
						type:"POST",
						url:"<?php echo site_url('Shop/getAreaList');?>",
						data:{cityId:this.value},
						success:function(data){
							if (data.status == '0') {

							}
						}
					});
					$('#shopListHTML').html(shopListHTML);
				});
			})

			function searchShop(){
				var searchShop = $("#searchShop").val();
					searchType = $('input[name=shopType]:checked').val();
				if (!searchShop) {
					alert('请填写搜索内容');
					return false;
				}
				
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Shop/searchShop');?>",
					data:{name:searchShop, type:searchType},
					success:function(data){
						if (data.status == "0") {
							$("#shopListDiv").html(data.list);
						}
					}
				});

			}

			function subAddShop(){

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Shop/addShop');?>",
					data:$('#addShopForm').serialize(),
					success:function(data){
						if (data.status) {
							alert(data.msg);
							return false;
						}else{
							bootbox.dialog({
								message: "门店添加成功", 
								buttons: {
									"goList" : {
										"label" : "返回门店列表",
										"className" : "btn-sm btn-primary",
										callback: function(){
											window.location.href = "<?php echo site_url('Shop/shopList');?>";
										}
									},
									"continue" : {
										"label" : "继续添加门店",
										"className" : "btn-sm btn-primary",
										callback: function(){
											window.location.reload();
										}
									}
								}
							});
							return true;
						}
					}
				});
			}
		</script>

	</body>
</html>
