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
								<form class="form-horizontal" role="form" id="editShop-form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopBrandName"> <?php echo $this->lang->line('TEXT_SHOP_BRANDNAME');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopBrandName" id="shopBrandName" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SHOP_BRANDNAME');?>" value="<?php echo $shop->brandName;?>"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopNameZH"> <?php echo $this->lang->line('TEXT_SHOP_NAMEZH');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopNameZH" id="shopNameZH" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SHOP_NAMEZH');?>" value="<?php echo $shop->shopNameZh;?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopNameEN"> <?php echo $this->lang->line('TEXT_SHOP_NAMEEN');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopNameEN" id="shopNameEN" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SHOP_NAMEEN');?>" value="<?php echo $shop->shopNameEn;?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopNamePY"> <?php echo $this->lang->line('TEXT_SHOP_NAME_PY');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopNamePY" id="shopNamePY" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SHOP_NAME_PY');?>" value="<?php echo $shop->shopNamePy;?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopNameShort"> <?php echo $this->lang->line('TEXT_SHOP_NAME_SHORT');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopNameShort" id="shopNameShort" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SHOP_NAME_SHORT');?>" value="<?php echo $shop->shopNameShort;?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopBranchName"> <?php echo $this->lang->line('TEXT_SHOP_BRANCH_NAME');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopBranchName" id="shopBranchName" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SHOP_BRANCH_NAME');?>"  value="<?php echo $shop->branchName;?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopImg"> <?php echo $this->lang->line('TEXT_SHOP_IMG');?> </label>

										<div class="col-sm-9">
											<?php if (isset($shop->shopPic) && !empty($shop->shopPic)) {?>
											<span id="fileImageSpan">
												<img src="<?php echo config_item('image_url').''.$shop->shopPic;?>" style="width: 200px;"><a onclick="removeFileImg();">重置</a>
											</span>
											<?php } ?>
											<input type="file" name="shopImg" id="fileImage" />
											<input type="hidden" name="shopImgPath" value="<?php echo $shop->shopPic;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopType"> <?php echo $this->lang->line('TEXT_SHOP_TYPE');?> </label>

										<div class="col-sm-9">
											<select name="shopType" id="shopType">
												<option value="1" <?php echo $shop->shopTyp == 1 ? 'selected' : '';?>><?php echo $this->lang->line('TEXT_SHOP_TYPE_MALL');?></option>
												<option value="2" <?php echo $shop->shopTyp == 2 ? 'selected' : '';?>><?php echo $this->lang->line('TEXT_SHOP_TYPE_STREET');?></option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopDistrict"> <?php echo $this->lang->line('TEXT_SHOP_DISTRICT');?> </label>

										<div class="col-sm-9">
											<select name="shopCity" id="shopCity">
												<?php foreach($cityList as $k => $v):?>
												<option value="<?php echo $v->id;?>" <?php echo $shop->cityId == $v->id ? 'selected' : '';?>><?php echo $v->name; ?></option>
												<?php endforeach;?>
												<input type="hidden" name="selectCity" id="selectCity" value="<?php echo $cityList[0]->name;?>">
											</select>
											<select name="shopDistrict" id="shopDistrict">
												<?php foreach($districtList as $k => $v):?>
												<option value="<?php echo $v->id;?>" <?php echo $shop->districtId == $v->id ? 'selected' : '';?>><?php echo $v->name; ?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>

<!-- 									<div class="form-group" id="shopMallDiv">
										<label class="col-sm-3 control-label no-padding-right" for="shopMall"> <?php echo $this->lang->line('TEXT_SHOP_MALL');?> </label>

										<div class="col-sm-9">
											<select name="mallId" id="mallId">
												<?php echo $mallList;?>
											</select>
										</div>
									</div> -->

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopOpenTime"> <?php echo $this->lang->line('TEXT_SHOP_OPENTIME');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopOpenTime" id="shopOpenTime" value="<?php echo $shop->openTime;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopCloseTime"> <?php echo $this->lang->line('TEXT_SHOP_CLOSETIME');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopCloseTime" value="<?php echo $shop->closeTime;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopTel"> <?php echo $this->lang->line('TEXT_SHOP_TEL');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopTel" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SHOP_TEL');?>" value="<?php echo $shop->tel;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopDescription"> <?php echo $this->lang->line('TEXT_DESCRIPTION');?> </label>

										<div class="col-sm-9">
											<textarea name="shopDescription"><?php echo $shop->description;?></textarea>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopAddress"> <?php echo $this->lang->line('TEXT_SHOP_ADDRESS');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopAddress" id="shopAddress" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SHOP_ADDRESS');?>" value="<?php echo $shop->address;?>"><a class="btn btn-sm" onclick="getLngLat()">search</a>
											<input type="hidden" name="shopAddressId" id="shopAddressId" value="<?php echo $shop->addressId;?>">
										</div>
									</div>

									<div class="form-group" id="shopFloorDiv">
										<label class="col-sm-3 control-label no-padding-right" for="shopFloor"> <?php echo $this->lang->line('TEXT_SHOP_FLOOR');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopFloor" id="shopFloor" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SHOP_FLOOR');?>" value="<?php echo $shop->shopFloor;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopLng"> <?php echo $this->lang->line('TEXT_SHOP_LNG');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopLng" id="shopLng" value="<?php echo $shop->shopLng;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopLat"> <?php echo $this->lang->line('TEXT_SHOP_LAT');?> </label>

										<div class="col-sm-9">
											<input type="text" name="shopLat" id="shopLat" value="<?php echo $shop->shopLat;?>">
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subEditShop();">
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
									<input type="hidden" name="shopBrandId" id="shopBrandId" value="<?php echo $shop->brandId;?>" >
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

		<script type="text/javascript" src="<?php echo config_item('html_url');?>js/jquery.ajaxfileupload.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			var interval;

			function applyAjaxFileUpload(element, filesName) {
				$(element).AjaxFileUpload({
					action: "<?php echo site_url('Brand/uploadPic');?>?filesName="+filesName,
					onChange: function(filename) {
						// Create a span element to notify the user of an upload in progress
						var $span = $("<span />")
							.attr("class", $(this).attr("id"))
							.text("Uploading")
							.insertAfter($(this));

						$(this).remove();

						interval = window.setInterval(function() {
							var text = $span.text();
							if (text.length < 13) {
								$span.text(text + ".");
							} else {
								$span.text("Uploading");
							}
						}, 200);
					},
					onSubmit: function(filename) {
						return true;
					},
					onComplete: function(filename, response) {
						window.clearInterval(interval);
						console.log(response);
						var $span = $("span." + $(this).attr("id")).text(filename + " "),
							$fileInput = $("<input />")
								.attr({
									type: "file",
									name: $(this).attr("name"),
									id: $(this).attr("id")
								});

						if (response.status) {
							$span.replaceWith($fileInput);

							applyAjaxFileUpload($fileInput, filesName);

							alert(response.msg);

							return;
						}else{
							$('#fileImageSpan').html('');
							$("<img />").attr("src", response.url).css("width", 200).appendTo($span);
							$("<a />").attr("href", "#").text("<?php echo $this->lang->line('BTN_RESET');?>").bind("click", function(e) {
									$span.replaceWith($fileInput);
									applyAjaxFileUpload($fileInput, filesName);
									$("input[name=shopImgPath]").val('');
								}).appendTo($span);
							$("input[name=shopImgPath]").val(response.path);
						}
					}
				});
			}

			applyAjaxFileUpload("#fileImage", "shopImg");
		});

			jQuery(function($) {
				$("#shopBrandName").keyup(function(){
					$.ajax({
			      		type:"POST",
			      		url:"<?php echo site_url('Brand/searchBrand');?>",
			      		data:{brand:$("#shopBrandName").val()},
			      		success:function(data){
			      			if (data.status != 0) { $("#shopBrandId").val(ui.item.id); return false;}
			      			
			      			var sourceData = new Array();
			      				mallHtml = '';

			      			$.each(data.list, function(k, v){
			      				sourceData.push(v);
			      			});

						    $( "#shopBrandName" ).autocomplete({
						      source: sourceData,
						      select:function(event, ui){
						      		$("#shopBrandId").val(ui.item.id);
						      }
						  });
						
			      		}
					});
				});

				$("#shopAddress").keyup(function(){
					$.ajax({
			      		type:"POST",
			      		url:"<?php echo site_url('Brand/searchAddress');?>",
			      		data:{address:$("#shopAddress").val()},
			      		success:function(data){
			      			if (data.status != 0 || data.list.length == 0) { $("#shopAddressId").val(''); return false;}
			      			
			      			var sourceData = new Array();
			      				mallHtml = '';

			      			$.each(data.list, function(k, v){
			      				sourceData.push(v);
			      			});

						    $( "#shopAddress" ).autocomplete({
						      source: sourceData,
						      select:function(event, ui){
						      		$("#shopAddress").val(ui.item.value);
						      		$("#shopAddressId").val(ui.item.id);
						      }
						  });
						
			      		}
					});
				});

				$('#editShop-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						shopBrandName: {
							required:true
						},
						shopNameZH: {
							required:true
						},
						shopLng: {
							required:true
						},
						shopLat:{
							required:true
						}
					},
			
					messages: {
						shopBrandName:{
							required:"<?php echo $this->lang->line('ERR_SHOP_BRANDNAME');?>"
						},
						shopNameZH: {
							required:"<?php echo $this->lang->line('ERR_SHOP_NAMEZH');?>"
						},
						shopLng: {
							required:"<?php echo $this->lang->line('ERR_SHOP_LNG');?>"
						},
						shopLat:{
							required:"<?php echo $this->lang->line('ERR_SHOP_LAT');?>"
						}
					},
			
					invalidHandler: function (event, validator) { //display error alert on form submit   
						$('.alert-danger', $('.login-form')).show();
					},
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error').addClass('has-info');
						$(e).remove();
					},
					errorPlacement: function (error, element) {
						console.log(error);
						error.insertAfter(element.parent().children());
					}
				});

				$('#shopCity').on('change', function(){
					$.ajax({
						type:"POST",
						url:"<?php echo site_url('Brand/getDistrictList');?>",
						data:{cityId:this.value},
						success:function(data){
							var html = '';
							if (data.status == 0) {
								$.each(data.list, function(k, v){
									html += '<option value="'+v.id+'">'+v.name+'</option>';
								});

								$('#shopDistrict').html(html);
								$('#selectCity').val(data.city);
								// $('#mallId').html(data.mall);
							}
						}
					});
				});

				// $('#shopType').on('change', function(){
				// 	if (this.value == '1') {
				// 		$('#shopMallDiv').show();
				// 	}else{
				// 		$('#shopMallDiv').hide();
				// 	}
				// });

			});
	
		function subEditShop(){
			if(!$('#editShop-form').valid()){
				return false;
			}

			$.ajax({
				type:"POST",
				url:"<?php echo site_url('Brand/editShop').'?shopId='.$this->input->get('shopId');?>",
				data:$('#editShop-form').serialize(),
				success:function(data){
					if (data.status == '0') {
						window.location.href = "<?php echo site_url('Brand/shopList').'?p='.$this->input->get('p');?>";
						return true;
					}

					alert(data.msg);
					return false;
				}
			});
		}

		function removeFileImg(){
			$('#fileImageSpan').html('');
		}

		function getLngLat(){
			var myGeo = new BMap.Geocoder();
			var	address = $("#shopAddress").val();
				city = $("#selectCity").val();

			// 将地址解析结果显示在地图上,并调整地图视野
			myGeo.getPoint(address, function(point){
				if (point) {
					$('#shopLng').val(point.lng);
					$('#shopLat').val(point.lat);
				}else{
					$('#shopLng').val('');
					$('#shopLat').val('');
					alert('<?php echo $this->lang->line("ERR_NO_ADDRESS_LNGLAT");?>');
				}
			}, city);
		}

		</script>

	</body>
</html>
