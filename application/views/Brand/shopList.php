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
								<a href="<?php echo site_url('Brand/listView');?>"><?php echo $this->lang->line('TITLE_BRAND_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TITLE_SHOP_MALL_LIST');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form method="get" action="<?php echo site_url('Brand/shopList');?>">
									<?php echo $this->lang->line('TEXT_SELECT_CITY');?>:
									<select name="city" id="citySelect">
											<option value="">城市</option>
										<?php foreach($cityList as $city){?>
											<option value="<?php echo $city->name;?>" <?php echo $city->name == $this->input->get('city') ? 'selected' : '' ;?>><?php echo $city->name;?></option>
										<?php }?>
									</select>

									<select name="district" id="districtSelect">
											<option value="">区域</option>
										<?php foreach($districtList as $v){?>
											<option value="<?php echo $v->name;?>" <?php echo $v->name == $this->input->get('district') ? 'selected' : '' ;?>><?php echo $v->name;?></option>
										<?php }?>
									</select>

									<?php echo $this->lang->line('TEXT_SHOP_BRANDNAME');?>:<input type="text" name="brand" value="<?php echo $this->input->get('brand');?>"/>

									<?php echo $this->lang->line('TEXT_SHOP_NAME');?>:<input type="text" name="shop" value="<?php echo $this->input->get('shop');?>"/>

									<?php echo $this->lang->line('TEXT_SHOP_ADDRESS');?>:<input type="text" name="address" value="<?php echo $this->input->get('address');?>"/>
									<button type="submit"><?php echo $this->lang->line('BTN_SEARCH');?></button>
								</form>
							</div>
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="rolelist-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th><?php echo $this->lang->line('TEXT_SHOP_IMG');?></th>
														<th><?php echo $this->lang->line('TEXT_SHOP_BRANDNAME');?></th>
														<th><?php echo $this->lang->line('TEXT_SHOP_NAMEZH');?></th>
														<th><?php echo $this->lang->line('TEXT_SHOP_DISTRICT');?></th>
														<th><?php echo $this->lang->line('TEXT_SHOP_ADDRESS');?></th>
														<th>状态</th>
														<th><?php echo $this->lang->line('TEXT_OPERATION');?></th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($shopList as $v):?>
													<tr>
														<td>
															<img src="<?php echo config_item('image_url').$v->shopPic;?>" width="100px;">
														</td>
														<td><?php echo $v->brandName.' '.$v->brandNameEn;?></td>
														<td><?php echo $v->shopName;?>(<?php echo $v->branchName;?>-<?php echo $v->level == 1 ? '商场' : '街边店';?>)</td>
														<td><?php echo $v->cityName.' '.$v->district;?></td>
														<td><?php echo $v->address;?></td>
														<td>
																<?php if (!empty($v->shopPic)) {?>
																<button class="btn">已编辑</button>
																<?php }?>
														</td>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																	<a href="<?php echo site_url('Brand/editShop').'?shopId='.strEncrypt($v->id).'&p='.$this->input->get('p');?>"><i class="icon-edit bigger-120"></i>编辑</a>
																<button class="btn btn-xs btn-danger" onclick="delShop('<?php echo $v->id;?>');">
																	<i class="icon-trash bigger-120"></i>
																</button>
															</div>

														</td>
													</tr>
													<?php endforeach;?>
												</tbody>
											</table>
										</div><!-- /.table-responsive -->

									</div><!-- /span -->
								</div>
							</div><!-- /.col -->
							<?php echo $pagination;?>
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

		<script type="text/javascript">
			jQuery(function($) {
				$('#citySelect').on('change', function(){
					$.ajax({
						type:"POST",
						url:"<?php echo site_url('Brand/getDistrictByCity');?>",
						data:{city:this.value},
						success:function(data){
							$('#districtSelect').html(data.html);
						}
					});
				});
			});

			function delShop(shopId){
				if (!confirm("<?php echo $this->lang->line('TEXT_CONFIRM_DELSHOP');?>")) {
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Brand/delShop');?>",
					data:{shopId:shopId},
					success:function(data){
						window.location.reload();
					}
				});
			}
		</script>

	</body>
</html>
