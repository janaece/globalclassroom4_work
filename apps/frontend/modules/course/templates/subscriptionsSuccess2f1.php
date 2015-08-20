<?php
global $CFG;
$current_user = $CFG->current_app->getCurrentUser();
$enrol_count = count($current_user->getEnrolments());
//print_r($current_user->getEnrolments());
$course_creator = $CFG->current_app->hasPrivilege('EclassroomUser');

$CFG->current_app->requireMahara();
$CFG->current_app->requireLogin();
$current_user = $CFG->current_app->getCurrentUser();
$role_manager = $current_user->getRoleManager();
$gc_admin = $role_manager->hasRole('GCUser');
$owner = $role_manager->hasRole('EschoolAdmin');
$user_id = $current_user->getObject()->id;
$is_user = ($user_id == $current_user->getObject()->id);
?>
<style type="text/css">.fancybox-margin{margin-right:17px;}</style>
 
<link rel="stylesheet" href="/js/masonry/css/style.css">
<link rel="stylesheet" href="/css/course_view.css">
<link rel="stylesheet" href="/css/course_detail.css">
<script src="/js/masonry/jquery.masonry.min.js"></script>
<script src="/js/course_view.js"></script>

<link href='https://fonts.googleapis.com/css?family=Rambla|Domine|Droid+Sans|BenchNine|Economica|Great+Vibes|Amaranth|Playfair+Display+SC|Cookie|Cinzel+Decorative|Righteous|Roboto|Roboto+Condensed|Squada+One|Fjalla+One|Satisfy|Oswald|Damion|Marvel' rel='stylesheet' type='text/css'>
<style type="text/css">
.fontlist{font-family:'Rambla',sans-serif;font-family:Domine,serif;font-family:'Droid Sans',sans-serif;font-family:BenchNine,sans-serif;font-family:Economica,sans-serif;font-family:'Great Vibes',cursive;font-family:Amaranth,sans-serif;font-family:'Playfair Display SC',serif;font-family:Cookie,cursive;font-family:'Cinzel Decorative',cursive;font-family:Righteous,cursive;font-family:Roboto,sans-serif;font-family:'Roboto Condensed',sans-serif;font-family:'Squada One',cursive;font-family:'Fjalla One',sans-serif;font-family:Satisfy,cursive;font-family:Oswald,sans-serif;font-family:Damion,cursive;font-family:Marvel,sans-serif}
html,body{height:100%;margin:0}
.mybox{width:96%;height:auto;margin:auto;-webkit-border-radius:20px;-moz-border-radius:20px;border-radius:20px;border:2px solid #A9B6B8;background-color:#FFF;-webkit-box-shadow:#A6A6A6 1px 1px 1px;-moz-box-shadow:#A6A6A6 1px 1px 1px;box-shadow:#A6A6A6 1px 1px 1px}
.mycss{margin:20px;font-weight:400;color:#3B3B3B;letter-spacing:1pt;word-spacing:2pt;font-size:25px;text-align:left;font-family:arial,helvetica,sans-serif;line-height:1}
.page_header_title{margin:10px;font-weight:700;color:#00f;letter-spacing:1pt;word-spacing:3pt;font-size:30px;text-align:left;font-family:'Roboto',sans-serif;line-height:1}
.page_header_title_subline{margin-left:100px;font-weight:400;color:#333;letter-spacing:1pt;word-spacing:3pt;font-size:16px;text-align:left;font-family:'Roboto Condensed',sans-serif;line-height:1.5}
.myfont{margin:20px;font-weight:400;color:#3B3B3B;letter-spacing:3pt;word-spacing:2pt;font-size:25px;text-align:left;font-family:arial,helvetica,sans-serif;line-height:1}
.myfont1{margin:10px;font-weight:700;color:#415B84;letter-spacing:1pt;word-spacing:3pt;font-size:21px;text-align:left;font-family:'Roboto',sans-serif;line-height:1}
.myfont2{font-weight:400;color:#333;letter-spacing:1pt;word-spacing:3pt;font-size:16px;text-align:left;font-family:'Roboto Condensed',sans-serif;line-height:1.5}
.myfont3{margin:10px;font-weight:400;color:#00f;letter-spacing:3pt;word-spacing:2pt;font-size:16px;text-align:left;font-family:arial,helvetica,sans-serif;line-height:1}
.myfont4{margin:0px;font-weight:700;color:#000;letter-spacing:1pt;word-spacing:2pt;font-size:16px;text-align:left;font-family:arial,helvetica,sans-serif;line-height:1}
.myfont5{margin:0px;font-weight:400;color:#666;letter-spacing:1pt;word-spacing:2pt;font-size:12px;text-align:left;font-family:'Roboto Condensed',sans-serif;line-height:1}
#wrap0{width:100%;margin:20px;background-color:#fff}
#wrap1{width:100%;margin:20px;background-color:#fff}
#wrap1_left_col2{float:left;width:45%;background-color:#fff}
#wrap1_right_col2{float:right;width:45%;background-color:#fff}
#wrap2{width:100%;margin:20px;background-color:#ddd}
#wrap2_left_col2{float:left;width:45%;background-color:#ddd}
#wrap2_right_col2{float:right;width:45%;background-color:#ddd}
.col5header{position:relative;float:left;left:auto;width:auto;background-color:#fff}
.col5wrapper{position:relative;float:left;left:auto;width:auto;background-color:#fff}
.col5left1{position:relative;float:left;left:auto;width:auto;background-color:#fff}
.col5left2{position:relative;float:left;left:auto;width:auto;background-color:#fff}
.col5left3{position:relative;float:left;left:auto;width:auto;background-color:#fff}
.col5left4{position:relative;float:left;left:auto;width:auto;background-color:#fff}
.col5right{position:relative;float:right;right:auto;width:auto;background-color:#fff}
.col5footer{position:relative;float:left;left:auto;width:auto;background-color:#fff}
body{border-width:0;padding:0;margin:0;font-size:90%;background-color:#fff}
a.tooltip{outline:none}
a.tooltip strong{line-height:30px}
a.tooltip:hover{text-decoration:none}
a.tooltip span{z-index:10;display:none;padding:14px 20px;margin-top:-30px;margin-left:28px;width:300px;line-height:16px}
a.tooltip:hover span{display:inline;position:absolute;color:#111;border:1px solid #DCA;background:#fffAF0}
.callout{z-index:20;position:absolute;top:30px;border:0;left:-12px}
a.tooltip span{border-radius:4px;box-shadow:5px 5px 8px #CCC}
.subscribe_btn{background:#3498db;background-image:-webkit-linear-gradient(top,#3498db,#2980b9);background-image:-moz-linear-gradient(top,#3498db,#2980b9);background-image:-ms-linear-gradient(top,#3498db,#2980b9);background-image:-o-linear-gradient(top,#3498db,#2980b9);background-image:linear-gradient(to bottom,#3498db,#2980b9);-webkit-border-radius:28;-moz-border-radius:28;border-radius:28px;text-shadow:3px 3px 4px #666;font-family:Arial;color:#fff;font-size:18px;padding:10px 20px;text-decoration:none}
.subscribe_btn:hover{background:#3cb0fd;background-image:-webkit-linear-gradient(top,#3cb0fd,#3498db);background-image:-moz-linear-gradient(top,#3cb0fd,#3498db);background-image:-ms-linear-gradient(top,#3cb0fd,#3498db);background-image:-o-linear-gradient(top,#3cb0fd,#3498db);background-image:linear-gradient(to bottom,#3cb0fd,#3498db);text-decoration:none}
.free_btn{background:#34d97b;background-image:-webkit-linear-gradient(top,#34d97b,#137d09);background-image:-moz-linear-gradient(top,#34d97b,#137d09);background-image:-ms-linear-gradient(top,#34d97b,#137d09);background-image:-o-linear-gradient(top,#34d97b,#137d09);background-image:linear-gradient(to bottom,#34d97b,#137d09);-webkit-border-radius:28;-moz-border-radius:28;border-radius:28px;text-shadow:3px 3px 4px #666;font-family:Arial;color:#fff;font-size:18px;padding:10px 20px;text-decoration:none}
.free_btn:hover{background:#0b8f49;background-image:-webkit-linear-gradient(top,#0b8f49,#2ed9a6);background-image:-moz-linear-gradient(top,#0b8f49,#2ed9a6);background-image:-ms-linear-gradient(top,#0b8f49,#2ed9a6);background-image:-o-linear-gradient(top,#0b8f49,#2ed9a6);background-image:linear-gradient(to bottom,#0b8f49,#2ed9a6);text-decoration:none}
.available_btn{background:#b5a017;background-image:-webkit-linear-gradient(top,#b5a017,#e8e84c);background-image:-moz-linear-gradient(top,#b5a017,#e8e84c);background-image:-ms-linear-gradient(top,#b5a017,#e8e84c);background-image:-o-linear-gradient(top,#b5a017,#e8e84c);background-image:linear-gradient(to bottom,#b5a017,#e8e84c);-webkit-border-radius:28;-moz-border-radius:28;border-radius:28px;text-shadow:3px 3px 4px #666;font-family:Arial;color:#fff;font-size:18px;padding:10px 20px;text-decoration:none}
.available_btn:hover{background:#ebeb52;background-image:-webkit-linear-gradient(top,#ebeb52,#bda718);background-image:-moz-linear-gradient(top,#ebeb52,#bda718);background-image:-ms-linear-gradient(top,#ebeb52,#bda718);background-image:-o-linear-gradient(top,#ebeb52,#bda718);background-image:linear-gradient(to bottom,#ebeb52,#bda718);text-decoration:none}
.colorbar1{background-color:#9561FE;width:100%;height:12px}
.myfont1 span.count { font-size: 21px; color: #3838CB;}
.myfont2 a { text-decoration:underline; font-weight:200; font-size: 14px; }
h3.sub-title{font-size: 22px;}
button.library-button{  background-image: none !important; background-color: #415B84 !important; padding: 12px 26px; font-size: 26px; color: #fff; font-weight: 300;}
.library-div .myfont2 a {margin-left: 40px;}
button.register-button{  background-image: none !important; background-color: #00f !important; padding: 12px 26px; font-size: 26px; color: #fff; font-weight: 300;}
h1 { color: #5F6169; font-size: 26px; }
.gc_course_list_item_container { height: 0px; }
.gc_course_list_item_title { background: none; height: auto; padding: 0px; }
.col2 { width: 120px;}
#gc_course_list { float: left; width: 100%; }

<!-- ***************************** TMG Added for CDN  *****************************  -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js?ver=3.3.4-0'></script>

<!-- Add jQuery library -->
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
<!-- Add fancyBox main JS and CSS files -->
<link rel="stylesheet"  type="text/css" href="/css/jquery.fancybox.css?v=2.1.5" media="screen" />
<script type="text/javascript" src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.6/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js"></script>
<style>
.dataTables_wrapper .dataTables_filter { float: left; text-align: right; display: block;  margin: -25px 0 0 500px; position: relative; width: 100%; }
.dataTables_wrapper .dataTables_filter label{ float: left;}
table.dataTable.no-footer { border-bottom: none; }
table.dataTable thead th,
table.dataTable thead td {
  padding: 10px 18px;
  border-bottom: 1px solid #ddd;
}
table.dataTable tfoot th,
table.dataTable tfoot td {
  padding: 10px 18px 6px 18px;
  border-bottom: 0px solid #fff;
}
table.dataTable.row-border tbody th, table.dataTable.row-border tbody td, table.dataTable.display tbody th, table.dataTable.display tbody td {
  border-top: 0px solid #fff;
}
table.dataTable.row-border tbody tr:first-child th,
table.dataTable.row-border tbody tr:first-child td, table.dataTable.display tbody tr:first-child th,
table.dataTable.display tbody tr:first-child td {
  border-top: none;
}
table.dataTable.cell-border tbody th, table.dataTable.cell-border tbody td {
  border-top: 0px solid #fff;
  border-right: 0px solid #fff;
}
table.dataTable.cell-border tbody tr th:first-child,
table.dataTable.cell-border tbody tr td:first-child {
  border-left: 0px solid #fff;
}
table.dataTable.cell-border tbody tr:first-child th,
table.dataTable.cell-border tbody tr:first-child td {
  border-top: none;
}
table.dataTable.cell-border tbody th, table.dataTable.cell-border tbody td { border-bottom: 1px solid #ddd; }
.gc_course_list_item_container { height: auto; margin-bottom: 0px; background: none; }
.gc_course_list_item_title { background: none; }
</style>

<script type="text/javascript">
$(document).ready(function() {
	$(".various").fancybox({
		scrollOutside	: true,
		width		: '90%',
		height		: '75%',
		scrollOutside  : true,
		title		: 'Library',
		closeClick	: true,
	});
	$(".video").fancybox({
		maxWidth	: 960,
		maxHeight	: 600,
		fitToView	: true,
		width		: '90%',
		height		: '80%',
		autoSize	: true,
	});
	
</script>

<!-- ***************************** TMG Added for CDN  *****************************  -->

<?php
$lib_ctlg_crse_lists = array();
$lib_ctlg_crse_count = array();
$all_catalogs_list = array();
$reg_paid_status = array();
$all_catalogs_count = 0;

foreach($libraries_list as $library_list_key=>$library_list_val)
{
	$institution_name = $products_list_institution[$library_list_key];
 	$mhr_institution_obj = $CFG->current_app->selectFromMhrTable('institution', 'name', $institution_name, true);
	if ($mhr_institution_obj) {
		$mhr_institution = new GcrMhrInstitution($mhr_institution_obj, $CFG->current_app);
		$potential_eschools = array();
		$current_eschools = array();
		// Check if users do not exist on the eschool, and get potential users in properly formatted form
		$eschools = $mhr_institution->getEschools();
		if ($eschools) {
			foreach ($eschools as $eschool) {
				$current_eschools[$eschool->getShortName()] = $eschool->getFullName();
			}
		}
		$eschools = $CFG->current_app->getMnetEschools();
		if ($eschools) {
			foreach ($eschools as $eschool) {
				if (!array_key_exists($eschool->getShortName(), $current_eschools)) {
					$potential_eschools[$eschool->getShortName()] = $eschool->getFullName();
				}
			}
		}
		asort($potential_eschools);
		asort($current_eschools);

		//echo "<br>" . $institution_name . " = " . $library_list_key . " = " . $user_id . "<br>";
		$lib_ctlg_crse_count[$library_list_key] = 0;
		$ins_prod_orders = array();
		$ins_prod_orders = GcrInstitutionProductOrdersTable::get_orders($institution_name, $library_list_key, $user_id);
		// $mhr_usr_institution = $current_user->getMhrUsrInstitutionRecords1($institution_name);
		// if($mhr_usr_institution) {
		// 	$reg_paid_status[$library_list_key]["paid_flag"] = 1;
		// } else {
		// 	$reg_paid_status[$library_list_key]["paid_flag"] = 0;
		// }
		
  		foreach($ins_prod_orders as $ins_prod_order) {
			$reg_paid_status[$library_list_key]["id"] = $ins_prod_order->getId();
			$reg_paid_status[$library_list_key]["user_id"] = $ins_prod_order->getUserId();
			$reg_paid_status[$library_list_key]["institution_short_name"] = $ins_prod_order->getInstitutionShortName();
			$reg_paid_status[$library_list_key]["product_short_name"] = $ins_prod_order->getProductShortName();
			$reg_paid_status[$library_list_key]["register_flag"] = $ins_prod_order->getRegisterFlag();
			$reg_paid_status[$library_list_key]["paid_flag"] = $ins_prod_order->getPaidFlag();
			$reg_paid_status[$library_list_key]["paid_amt"] = $ins_prod_order->getPaidAmt();
			$reg_paid_status[$library_list_key]["start_date"] = $ins_prod_order->getStartDate();
			$reg_paid_status[$library_list_key]["orig_start_date"] = $ins_prod_order->getOrigStartDate();
			$reg_paid_status[$library_list_key]["expiry_date"] = $ins_prod_order->getExpiryDate();
			$reg_paid_status[$library_list_key]["renewal_attempts"] = $ins_prod_order->getRenewalAttempts();
		} 

		foreach($current_eschools as $current_eschool_key=>$current_eschool_val) {
			//if(isset($catalog_courses_count[$current_eschool_key]) && $catalog_courses_count[$current_eschool_key] > 0) {
				//if(stripos(strtolower($current_eschool_val), "$") !== false) {
 					/* if(stripos(strtolower($current_eschool_val), "certification$") !== false) {
						$lib_ctlg_crse_lists[$library_list_key][$current_eschool_key] = substr($current_eschool_val, 14, strlen($current_eschool_val));
						$all_catalogs_list[] = substr($current_eschool_val, 14, strlen($current_eschool_val));
					} else {
						$lib_ctlg_crse_lists[$library_list_key][$current_eschool_key] = substr($current_eschool_val, 1, strlen($current_eschool_val));
						$all_catalogs_list[] = substr($current_eschool_val, 1, strlen($current_eschool_val));
					} */
				//} else {
					$lib_ctlg_crse_count[$library_list_key] = $lib_ctlg_crse_count[$library_list_key] + $catalog_courses_count[$current_eschool_key];
					$all_catalogs_count = $all_catalogs_count + $catalog_courses_count[$current_eschool_key];					
					$lib_ctlg_crse_lists[$library_list_key][$current_eschool_key] = $current_eschool_val;
					$all_catalogs_list[] = $current_eschool_val;
				//}
			//}
		}				
 	} else {
		$lib_ctlg_crse_count[$library_list_key] = 0;
	}
}
/*print "<pre>";
print_r($reg_paid_status);
/*print_r($lib_ctlg_crse_count);
print_r($lib_ctlg_crse_lists);
echo $all_catalogs_count;
print_r($all_catalogs_list);
print_r($products_details); */
//print "</pre>";
?>

<section id="content">
<div id="gc_course_list">
	<h1>Subscriptions</h1>
	<ul>
	<li>All courses include video-rich tutorials and lectures.</li>
	<li>Unlimited access to all courses in your purchased library</li>
	<li>All courses include a Certificate of Completion and become part of your Global Classroom transcript</li>
	<li>No contract to sign</li>
	</ul>
	<?php
		$loop_i = 1;
		$t1_cost = array();
		foreach($products_details as $products_detail_key=>$products_detail_val) {
			$t1_cost[] = $products_detail_val["cost"];	
			$t1_uniquecost = array_unique ($t1_cost);
			asort( $t1_uniquecost, SORT_NUMERIC );
		}
		//print_r($t1_cost);
		//print_r($t1_uniquecost);
		// $mhr_usr_institution = $current_user->getMhrUsrInstitutionRecords1($institution_name);
		// if($mhr_usr_institution) {
		//	 $registered_user = 1;
		// } else {
		//	 $registered_user = 0;
		// }
		foreach ($t1_uniquecost as $t1_costitem) {
			echo $t1_costitem.", ";
		}
	?>
	<div>
		<hr size="10" width="80%" align="center">
		
<!-- blockcheck 1 -->		
		<div>
			<h2>My Free & Paid Subscriptions</h2>
			<div class="row">
				<?php
				$loop_i = 1;
				foreach($products_details as $products_detail_key=>$products_detail_val){
					//if(isset($lib_ctlg_crse_count[$products_detail_val["short_name"]]) && $lib_ctlg_crse_count[$products_detail_val["short_name"]] > 0) {
					if (($products_detail_val["cost"] == 0) ) {	?>
					
					
						<div class="col-md-4">
							<!-- <a class="various fancybox.iframe" href="http://globalclassroomportal.com/educationlibrary/library_template.php?PassedEducationLibrariesKEY=<?php echo $products_detail_val["id"]; ?>"> -->
							<a href="#<?php echo $products_detail_val["short_name"] ?>">
								<img src="<?php print $CFG->current_app->getAppUrl(); ?>theme/globalclassroom/static/images/<?php echo $products_detail_val["icon"]; ?>" alt="<?php echo $products_detail_val["full_name"]; ?>" height="30" width="30"><?php echo $products_detail_val["full_name"]; ?>
							</a>: 
							<?php echo isset($lib_ctlg_crse_count[$products_detail_val["short_name"]]) ? $lib_ctlg_crse_count[$products_detail_val["short_name"]] : 0; ?> courses
						</div>
						<?php
						if($loop_i%3 == 0) {
							?></div><div class="row"><?php
						}
						$loop_i++;
						//}
					}
				}
				?>
			</div>
		</div>
<!-- /blockcheck 1 -->		
<!-- blockcheck 2 -->		
		<div>
		    <?php foreach ($t1_uniquecost as $t1_costitem) { ?>				
				<?php if ($t1_costitem > 0) { ?>
					<h2>$ <?php echo $t1_costitem ?> Subscriptions</h2>
					<div class="row">
						<?php
						$loop_i = 1;
						foreach($products_details as $products_detail_key=>$products_detail_val){
							//if(isset($lib_ctlg_crse_count[$products_detail_val["short_name"]]) && $lib_ctlg_crse_count[$products_detail_val["short_name"]] > 0) {
							if (($products_detail_val["cost"] == $t1_costitem)  ) {	?>
							
							
								<div class="col-md-4">
									<!-- <a class="various fancybox.iframe" href="http://globalclassroomportal.com/educationlibrary/library_template.php?PassedEducationLibrariesKEY=<?php echo $products_detail_val["id"]; ?>"> -->
									<a href="#<?php echo $products_detail_val["short_name"] ?>">
										<img src="<?php print $CFG->current_app->getAppUrl(); ?>theme/globalclassroom/static/images/<?php echo $products_detail_val["icon"]; ?>" alt="<?php echo $products_detail_val["full_name"]; ?>" height="30" width="30"><?php echo $products_detail_val["full_name"]; ?>
									</a>: 
									<?php echo isset($lib_ctlg_crse_count[$products_detail_val["short_name"]]) ? $lib_ctlg_crse_count[$products_detail_val["short_name"]] : 0; ?> courses
								</div>
								<?php
								if($loop_i%3 == 0) {
									?></div><div class="row"><?php
								}
								$loop_i++;
								//}
							}
						}
						?>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
<!-- /blockcheck 2 -->		
		
		
	<p>&nbsp;</p>
	<div>
		<h2>Details</h2>
	<?php
	foreach($products_details as $products_detail_key=>$products_detail_val)
	{
		//if(isset($lib_ctlg_crse_count[$products_detail_val["short_name"]]) && $lib_ctlg_crse_count[$products_detail_val["short_name"]] > 0) {
	?>	
		<div class="panel panel-info">
			<div class="panel-heading">
				<a name="<?php echo $products_detail_val["short_name"]; ?>"></a>
				<div class="row">
					<div class="col-md-6">
						<p>
							<a class="various fancybox.iframe" href="http://globalclassroomportal.com/educationlibrary/library_template.php?PassedEducationLibrariesKEY=<?php echo $products_detail_val["id"]; ?>">
								<img src="<?php print $CFG->current_app->getAppUrl(); ?>theme/globalclassroom/static/images/<?php echo $products_detail_val["icon"]; ?>" alt="<?php echo $products_detail_val["full_name"]; ?>">
							</a> 
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOneCS<?php echo $products_detail_val["id"]; ?>" aria-expanded="false" class="collapsed">
								<span style="font-size: 16px; font-weight: bold; color:"><?php echo $products_detail_val["full_name"]; ?></span>
							</a> : <?php echo "$ " . $products_detail_val["cost"] . " per month"; ?>
							</p>
					</div>
					<?php
					$button_flag = 0;
					if(isset($reg_paid_status[$products_detail_key])) {
						if($reg_paid_status[$products_detail_key]["paid_flag"] == 1) {

						} else {
							if($products_detail_val["cost"] > 0) {
								$button_flag = 1;
							} else {
								$button_flag = 2;
							}
						}
					} else {
						if($products_detail_val["cost"] > 0) {
							$button_flag = 1;
						} else {
							$button_flag = 2;
						}
					}
					if($button_flag > 0) {
					?>
					<div class="col-md-3 col-md-offset-3" style="padding-top:20px;">
						<?php if($button_flag == 1) { ?>
							<a class="btn btn-info" href="https://<?php echo $CFG->current_app->getShortName(); ?>.globalclassroom.us/purchase/subscriptionPurchase?institution=<?php echo $products_list_institution[$products_detail_key]; ?>&type=1&product=<?php echo $products_detail_key; ?>">
							<span class="glyphicon glyphicon-usd" aria-hidden="true"></span>&nbsp;&nbsp;<strong>
							Buy Now
							</strong>
							</a>
						<?php } ?>
						<?php if($button_flag == 2) { ?>
							 <!-- <a class="btn btn-info" href="javascript:;">
							<strong>
							 Register 
							</strong>
							</a> -->
						<?php } ?>						
					</div>
					<?php } ?>
				</div>
			</div>
			<div id="collapseOneCS<?php echo $products_detail_val["id"]; ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
				<div class="panel-body">
					<h4><?php echo $products_detail_val["full_name"]; ?></h4>
					<div>
						<p>
						<?php echo $products_detail_val["description"]; ?>
						</p>
					</div>
					<!--
					<div class="content_spacer_20px">
						<a class="various fancybox.iframe" href="http://globalclassroomportal.com/educationlibrary/library_template.php?PassedEducationLibrariesKEY=<?php echo $products_detail_val["id"]; ?>"><span class="btn btn-info">View courses</span>
						</a>
					</div>
					-->
					<p><strong>Number of online courses:</strong> <?php echo isset($lib_ctlg_crse_count[$products_detail_val["short_name"]]) ? $lib_ctlg_crse_count[$products_detail_val["short_name"]] : 0; ?> </p>
					<div><span class="myfont4">Courses Catalog:&nbsp;&nbsp;</span><span class="myfont5">(To view the course list, click on the catalog name.)</span></div> 
					<?php
					$ctlg_courses_list = array();
					foreach($lib_ctlg_crse_lists[$products_detail_val["short_name"]] as $ctlg_crse_list_key=>$ctlg_crse_list_val) {
					$catalog_key_courses_count = isset($catalog_courses_count[$ctlg_crse_list_key]) ? (int)$catalog_courses_count[$ctlg_crse_list_key] : 0;
					if(isset($catalog_courses_count[$ctlg_crse_list_key])) {
						$params = array();
						$params["mode"] = "Eschool";
						$params["mode_id"] = $ctlg_crse_list_key;
						$CFG->current_app->requireMahara();
						$courses_list = new GcrCourseList($params, $CFG->current_app);
						$ctlg_courses_list = $courses_list->getCourseList();
					//}
					?>
					<h4> <a data-toggle="collapse" data-parent="#accordion" href="#cert_collapseCourse_<?php echo $ctlg_crse_list_key; ?>"><span style="color:red;"><?php echo $ctlg_crse_list_val; ?> ($)</span>&nbsp;<i class="fa fa-folder-open-o"></i></a></h4>
					<div id="cert_collapseCourse_<?php echo $ctlg_crse_list_key; ?>" class="panel-collapse collapse">
						<table cellpadding="0" cellspacing="0" border="0" class="display" id="sub_courses_list_<?php echo $ctlg_crse_list_key; ?>" width="100%" align="left">
						<thead>
						<tr>
							<th width="20%">Course Title</th>
							
							<th>Description</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
						</tr>
						</thead>
						<tbody>
						<?php
						foreach($ctlg_courses_list as $course_list) {
							$mdl_course = $course_list->getObject();
							$course_list_item = new GcrCourseListItem($course_list);
							$eschool = $course_list->getApp();
							$id = 'gcr_course_' . $eschool->getShortName() . '_' . $mdl_course->id;
							$img_src = $course_list_item->getCourseIconUrl();
							$mdl_user = $course_list_item->getInstructor();
							$summary = $course_list_item->getSummary();
							$enrol_count = $course_list_item->getActiveUserCount();
							$shortsummary = GcrInstitutionTable::formatStringSize($summary, 250, 21);
							if ($mdl_user) {
								$teacher_text = GcrEschoolTable::getInstructorProfileHtml($mdl_user);
							} else {
								$teacher_text = 'None';
							}
							$fullname = $mdl_course->fullname;
							$cost = $course_list->getCost();
							$cost_text = '';
							//if ($cost) {
								//$cost_text = 'Price: ' . GcrPurchaseTable::gc_format_money($cost);
								$cost_text = GcrPurchaseTable::gc_format_money($cost);
							//}
							$enrollment_status = false;
							$current_user = $CFG->current_app->getCurrentUser();
							if ($current_user->getRoleManager()->hasPrivilege('Student'))
							{
								$mdl_roles = $course_list->getRoleAssignments($current_user);
								$enrollment_status = ($mdl_roles && count($mdl_roles > 0));     
							}
							
							$course_button_text = "";
							if(empty($button_text)) {
								if($cost > 0) {
									$course_button_text = "Buy Now";
								} else {
									$course_button_text = "Enroll Now";
								}
							}
							?>
							<tr>
								<td width="20%"><?php print $fullname; ?></td>
								
								<td><?php print $shortsummary ?></td>
								<td nowrap style="width: auto;">
									<div class="gc_course_list_item_title gc_course_list_item_container_element ">
										<a title="<?php print $fullname; ?>" href="">Learn&nbsp;More</a> 
									</div>
								</td>
								<td style="width: auto;">
									<?php /* <div id="<?php print $id ?>" class="gc_course_list_item_container">
										<a class="various fancybox.iframe" href=""><span class="btn btn-success"> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Learn More</span></a>
									</div> */ ?>
									
									<div id="gc_course_list">
									<div id="gc_course_list_settings" style="margin: 0px">
									<div id="gc_course_list_container_<?php echo $ctlg_crse_list_key; ?>" class="transitions-enabled infinite-scroll clearfix">
									<div class="gc_course_list_item col2">
										<?php
										/*if (isset($request_params["mode_id"]) && $request_params["mode_id"]!="" && $meta_data)
										{
											print '<span class="coursebox" end_index="' . $meta_data . '"></span>';
										}*/   
										?>
										<div id="<?php print $id ?>" class="gc_course_list_item_container">
											<div class="gc_course_list_item_header">
												<!-- <div class="gc_course_list_item_title gc_course_list_item_container_element ">
													 <a title="<?php print $fullname; ?>" href="">
														<span class="btn btn-success"> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Learn More</span>
													    Learn More
													</a> 
												</div> -->
											</div>
											<div class="gc_course_list_item_body" style="display:none;">
												<div class="gc_course_list_item_top">
													<div class="gc_course_list_item_icon gc_course_list_item_container_element">
														<img src="<?php print $img_src ?>" />
													</div>
												</div>
												<div class="gc_course_list_item_instructor gc_course_list_item_container_element">
													<b>Instructor: <?php print $teacher_text ?></b>
												</div>
												<div class="gc_course_list_item_description gc_course_list_item_container_element">
													<?php print $shortsummary ?>
												</div>
											</div>
											<div class="gc_course_list_item_footer" style="display:none;">
												<span class="gc_course_list_item_enrol_count">
													Enrollments: <?php print $enrol_count; ?>
												</span>
												<span class="gc_course_list_item_cost">
													<?php print $cost_text; ?>
												</span> 
											</div>
										</div>
									</div>			
									</div>
									<script>
									jQuery(function()
									{
									var $container = jQuery('#gc_course_list_container_<?php echo $ctlg_crse_list_key; ?>');
									});
									</script>
									</div>
									</div>	 						
									
									<?php if(!empty($course_button_text)) { ?>
									&nbsp;&nbsp;&nbsp;
									<a href="check_out.php?name=HR Compliance Courses&cost=$39 per course"><span class="btn btn-success"> <span class="glyphicon glyphicon-usd" aria-hidden="true"></span> <?php echo $course_button_text; ?></span></a>
									<?php } ?>
								</td>
							</tr>			
							<?php
						}
						?>
						</tbody>
						</table>
						<script>
						jQuery(document).ready( function () {
							$('#sub_courses_list_<?php echo $ctlg_crse_list_key; ?>').dataTable( {
								"paging": true,
								"ordering": false,
								"info": false
							} );
						});	
						</script>
					</div>
					<?php
					}
					}
					?>
					
					<div></div>
				</div>
			</div>
		</div>
	<?php
		//}
	}
	?>	
	</div>
</div>
</div>
<!-- #content -->

</section>
<script>	
$gc_course_viewer.addNewCourseListItemsEventListeners(); 
/* jQuery("#course_detail_goto_button").click(function() 
{
	var html = '<br /><br /><h3 style="margin:10px">Loading course...</h3>';
	jQuery.colorbox({html: html, fixed: true});
	document.location.href = '/course/view.php?id=' + gc_course_detail.selected_course_id + '&transfer=' + gc_current_app_id;
}); */
</script>