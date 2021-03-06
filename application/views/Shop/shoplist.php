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
							<li class="active"><?php echo $this->lang->line('TEXT_SHOP_LIST');?></li>
						</ul>
						<div class="nav-search">
							<a href="<?php echo base_url('Shop/addShop');?>"><button class="btn btn-xs btn-primary">添加门店</button></a>
						</div>
					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<form method="get" action="<?php echo site_url('Shop/shopList');?>">
									<?php echo $this->lang->line('TEXT_SELECT_CITY');?>:
									<select name="city">
										<option value="">全部</option>
										<?php foreach($cityList as $city){?>
										<option value="<?php echo $city->id?>" <?php echo $this->input->get('city', true) == $city->id ? 'selected' : '';?>><?php echo $city->name_zh;?></option>
										<?php }?>
									</select>
									<?php if($this->ShopModel->isAdmin){?>
									&nbsp;
									&nbsp;
									品牌名<input type="text" name="brand" value="<?php echo $this->input->get('brand');?>">
									<?php }?>
									&nbsp;
									&nbsp;
									<?php echo $this->lang->line('TEXT_SHOP_NAME');?>:<input type="text" name="shop" value="<?php echo $this->input->get('shop');?>"/>
									&nbsp;
									&nbsp;
									<?php echo $this->lang->line('TEXT_SHOP_ADDRESS');?>:<input type="text" name="address" value="<?php echo $this->input->get('address');?>"/>
									&nbsp;
									&nbsp;
									<button type="submit"><?php echo $this->lang->line('BTN_SEARCH');?></button><?php echo $shopListTotalLang;?>
									<br>
									<br>
								</form>
							</div>

							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="rolelist-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															LOGO
														</th>
														<th><?php echo $this->lang->line('TEXT_BRAND_NAME');?></th>
														<th><?php echo $this->lang->line('TEXT_MALL_NAME');?></th>
														<th><?php echo $this->lang->line('TEXT_ADDRESS');?></th>
														<th>区域</th>
														<th><?php echo $this->lang->line('TEXT_CITY_NAME');?></th>
														<th><?php echo $this->lang->line('TEXT_FLOOR');?></th>
														<th><?php echo $this->lang->line('TEXT_OPERATION');?></th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($shopList as $v):?>
													<tr>
														<td class="center">
															<img src="<?php echo config_item('image_url').$v->logoUrl;?>" width="100px;">
														</td>

														<td>
															<a href="#"><?php echo $v->brandName;?></a>
														</td>
														<td><?php echo $v->mallName;?></td>
														<td><?php echo $v->address;?></td>
														<td><?php echo $v->areaName;?></td>
														<td><?php echo $v->cityName;?></td>
														<td><?php echo $v->floor;?></td>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																&nbsp;
																<a href="<?php echo site_url('Shop/editShop').'?id='.$v->id;?>">
																	<i class="icon-edit bigger-120">编辑</i>
																</a>
																&nbsp;
																<a style="color:red;" onclick="delShop('<?php  echo $v->id;?>')">
																	<i class="icon-trash bigger-120">删除</i>
																</a>
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
			function delShop(shopId){
				if (!confirm('是否确认删除该条数据?')) {
					return false;
				};

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Shop/delShop');?>",
					data:{shopId:shopId},
					success:function(data){
						window.location.reload();							
					}
				});
			}
		</script>

	</body>
</html>
