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
								<a href="<?php echo site_url('HongQiao/mall2w');?>"><?php echo $this->lang->line('TITLE_HONGQIAO');?></a>
							</li>
							<li class="active">编辑爬虫数据</li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="editMall-form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandName"> 爬虫品牌名 </label>

										<div class="col-sm-9">
											<label class="col-sm-3 "> <?php echo $detail->brandName;?> </label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandNameZh_s"> 品牌中文(匹配) </label>

										<div class="col-sm-9">
											<input type="text" name="brandNameZh_s" id="brandNameZh_s" placeholder="" value="<?php echo !empty($detail->brandInfo->name_zh) ? $detail->brandInfo->name_zh : '';?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandNameEn_s"> 品牌英文(匹配) </label>

										<div class="col-sm-9">
											<input type="text" name="brandNameEn_s" id="brandNameEn_s" placeholder="" value="<?php echo !empty($detail->brandInfo->name_en) ? $detail->brandInfo->name_en : '';?>" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for=""> 所属城市 </label>

										<div class="col-sm-9">
											<label class="col-sm-3 "> <?php echo $detail->cityName;?> </label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for=""> 爬虫商场数据 </label>

										<div class="col-sm-9">
											<label class="col-sm-3 "> <?php echo $detail->mallName.'('.$detail->address.')';?> </label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mallName"> 所属商场 </label>

										<div class="col-sm-9">
											<input type="text" name="mallName" id="mallName" placeholder="" value=""/><a class="btn btn-sm" onclick="getMallList()">search</a>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mallList_s"> </label>

										<div class="col-sm-9" id="mallListDiv">
											<?php echo $detail->mall;?>
										</div>
									</div>

									<input type="hidden" name="brandId_s" id="brandId_s" value="<?php echo !empty($detail->brandInfo->id) ? $detail->brandInfo->id : '';?>">

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subEditMall();">
												<i class="icon-ok bigger-110"></i>
												<?php echo $this->lang->line('BTN_SUBMIT');?>
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="button" onclick="window.location.href=document.referrer; ">
												<i class="icon-undo bigger-110"></i>
												返回
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn btn-danger" type="button" onclick="delMall('<?php echo $detail->id;?>')">
												<i class="icon-trash bigger-110"></i>
												删除
											</button>
										</div>
									</div>
									<input type="hidden" name="mallId" id="mallId" value="<?php echo $detail->id; ?>" >
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
			jQuery(function($) {

				$("#brandNameZh_s").keyup(function(){
					
					$("#brandId_s").val('');

					$.ajax({
			      		type:"POST",
			      		url:"<?php echo site_url('HongQiao/searchBrand');?>",
			      		data:{brand:$("#brandNameZh_s").val()},
			      		success:function(data){
			      			if (data.status != 0) { return false;}
			      			
			      			var sourceData = new Array();

			      			$.each(data.list, function(k, v){
			      				sourceData.push(v);
			      			});

						    $( "#brandNameZh_s" ).autocomplete({
						      source: sourceData,
						      select:function(event, ui){
									var brandName = ui.item.label.split('_');

									$("#brandNameEn_s").val(brandName[0]);
									$("#brandNameZh_s").val(brandName[1]);

						      		$("#brandId_s").val(ui.item.id);
						      }
						  });
						
			      		}
					});
				});
			});
	
		function getMallList(){
			if($('#mallName').val() == ''){
				return false;
			}

			$.ajax({
				type:"POST",
				url:"<?php echo site_url('HongQiao/getMallList');?>",
				data:{mall:$('#mallName').val(), city:'<?php echo $detail->cityName;?>'},
				success:function(data){
					if (data.status == '0') {
						$('#mallListDiv').html(data.list);
					}
				}
			});
		}

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

			if ($('input:radio[name="mallId_s"]:checked').val() == null) {
				alert('请选择商场');
				return false;
			}

			if ($('#brandId_s').val() == '') {
				if ($('#brandNameZh_s').val() == '') {
					alert('请填写品牌中文');
					return false;
				};
			}

			$.ajax({
				type:"POST",
				url:"<?php echo site_url('HongQiao/editMall2w').'?id='.$this->input->get('id');?>",
				data:$('#editMall-form').serialize(),
				success:function(data){
					if (data.status == '0') {
						// window.location.href = "<?php echo site_url('HongQiao/restaurantAddressList').'?p='.$this->input->get('p');?>";
						window.location.href=document.referrer;
						return true;
					}

					alert(data.msg);
					return false;
				}
			});
		}

		function delMall(mallId){
			if (!confirm("是否确认删除该数据")) {
				return false;
			}

			$.ajax({
				type:"POST",
				url:"<?php echo site_url('HongQiao/delMall2w');?>",
				data:{mallId:mallId},
				success:function(data){
						window.location.href=document.referrer;
				}
			});
		}
		</script>

	</body>
</html>
