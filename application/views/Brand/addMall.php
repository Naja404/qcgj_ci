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
								<a >品牌管理</a>
							</li>
							<li class="active"><?php echo $this->lang->line('TITLE_ADD_MALL')?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="addMall-form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mallName"> 商场名 </label>

										<div class="col-sm-9">
											<input type="text" name="mallName" id="mallName" placeholder="" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="address"> 城市 </label>

										<div class="col-sm-9">
											<select name="cityId" id="citySelect">
												<?php foreach($city as $v):?>
												<option value="<?php echo $v->id;?>" >
													<?php echo $v->name;?>市
												</option>
												<?php endforeach;?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="address"> 所属区 </label>

										<div class="col-sm-9">
											<select name="districtId" id="districtSelect">
												<?php foreach($district as $v):?>
												<option value="<?php echo $v->id;?>" >
													<?php echo $v->name;?>
												</option>
												<?php endforeach;?>
											</select>
										</div>
									</div>						

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="address"> 地址 </label>

										<div class="col-sm-9">
											<input type="text" name="address" id="address" placeholder="" /><a class="btn btn-sm" onclick="getLngLat()">search</a>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="lng"> 经度 </label>

										<div class="col-sm-9">
											<input type="text" name="lng" id="lng" placeholder="" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="lat"> 纬度 </label>

										<div class="col-sm-9">
											<input type="text" name="lat" id="lat" placeholder="" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="tel"> 电话 </label>

										<div class="col-sm-9">
											<input type="text" name="tel" id="tel" placeholder="" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopImg"> <?php echo $this->lang->line('TEXT_SHOP_IMG');?> </label>

										<div class="col-sm-9">
											<input type="file" name="shopImg" id="fileImage" />
											<input type="hidden" name="shopImgPath" value="">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shopImg"> 缩略图 </label>

										<div class="col-sm-9">
											<input type="file" name="shopThumbImg" id="ThumbImg" />
											<input type="hidden" name="shopThumbImgPath" value="">
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subAddMall();">
												<i class="icon-ok bigger-110"></i>
												<?php echo $this->lang->line('BTN_SUBMIT');?>
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="button" onclick="window.location.href=document.referrer; ">
												<i class="icon-undo bigger-110"></i>
												返回
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

		<script src="http://api.map.baidu.com/api?v=1.5&ak=C06d8528dd571c548c6f862391f97d9f" type="text/javascript"></script>
		<script src="http://api.gjla.com:80/app_admin_v330/res/baidumap/scripts/bmap.js" type="text/javascript"></script>

		<script type="text/javascript" src="<?php echo config_item('html_url');?>js/jquery.ajaxfileupload.js"></script>
		<script type="text/javascript">

		$(document).ready(function() {
			var interval;

			function applyAjaxFileUpload(element, filesName, filePath, fileSpan) {
				$(element).AjaxFileUpload({
					action: "<?php echo site_url('Brand/uploadPic');?>?filesName="+filesName,
					onChange: function(filename) {
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
							if (fileSpan) {
								$('#'+fileSpan).html('');
							}
							$("<img />").attr("src", response.url).css("width", 200).appendTo($span);
							$("<a />").attr("href", "#").text("<?php echo $this->lang->line('BTN_RESET');?>").bind("click", function(e) {
									$span.replaceWith($fileInput);
									applyAjaxFileUpload($fileInput, filesName);
									$("input[name="+filePath+"]").val('');
								}).appendTo($span);
							$("input[name="+filePath+"]").val(response.path);
						}
					}
				});
			}

			applyAjaxFileUpload("#fileImage", "shopImg", "shopImgPath", "shopImgPathSpan");
			applyAjaxFileUpload("#ThumbImg", "shopThumbImg", "shopThumbImgPath", "shopThumbImgPathSpan");
		});

			jQuery(function($) {

				$('#citySelect').on('change', function(){
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

								$('#districtSelect').html(html);
							}
						}
					});
				});


				$('#addMall-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						mallName: {
							required:true
						},
						lng: {
							required:true
						},
						lat:{
							required:true
						}
					},
			
					messages: {
						mallName: {
							required:"<?php echo $this->lang->line('ERR_MALL_NAME');?>"
						},
						lng: {
							required:"<?php echo $this->lang->line('ERR_MALL_LNG');?>"
						},
						lat:{
							required:"<?php echo $this->lang->line('ERR_MALL_LAT');?>"
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
						error.insertAfter(element.parent().children());
					}
				});
			});

		function getLngLat(){
			var myGeo = new BMap.Geocoder();
			var	address = $("#address").val();
			var cityName = $.trim($("#citySelect").find("option:selected").text());

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
			}, cityName);
		}

			function removeFileImg(element,filePath){
				$('#'+element).html('');
				$("input[name="+filePath+"]").val('');
			}
	
		function subAddMall(){
			if(!$('#addMall-form').valid()){
				return false;
			}

			$.ajax({
				type:"POST",
				url:"<?php echo site_url('Brand/addMall');?>",
				data:$('#addMall-form').serialize(),
				success:function(data){
					if (data.status == '0') {
						window.location.href=document.referrer;
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
