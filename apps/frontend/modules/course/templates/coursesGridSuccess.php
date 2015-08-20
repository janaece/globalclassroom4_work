<?php
global $CFG;
$current_user = $CFG->current_app->getCurrentUser();
$enrol_count = count($current_user->getEnrolments());
$course_creator = $CFG->current_app->hasPrivilege('EclassroomUser');
?>
<link rel="stylesheet" href="/js/masonry/css/style.css" />
<link rel="stylesheet" href="/css/course_view.css" />
<link rel="stylesheet" href="/css/course_detail.css" />

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.6/css/jquery.dataTables.css" />
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script src="https://cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js"></script>

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
table tr th, table tr td { border: solid 1px #ebebeb; }
</style>

<section id="content">
<div id="gc_course_list">
    <div id="gc_course_list_settings" style="margin: 0 70px">
		<br>
		<div class="page_header_title">
			AVAILABLE COURSES
		</div>
		<div class="page_header_title_subline">
			<a href="">Please click here for platinum subscription</a>
			<a href="#" class="tooltip">
				<img src="<?php print $CFG->current_app->getAppUrl(); ?>theme/globalclassroom/static/images/questionmark3.png" width="25px" />
				<span>
					<strong>Platinum Subscription</strong><br />
					gives you access to all the courses in all the libraries.
				</span>
			</a>
		</div>	
		<div class="clearfix"></div>
		<br>
		<div class="colorbar1"></div>
		<br><br>
		<div style="float:right;">
			<a href="/course/view">[Back to Courses]</a>
		</div>		
		<div class="clearfix"></div>
		
		<div class="clearfix"></div>
		<table cellpadding="2" cellspacing="2" border="1" class="display" id="courses_list" width="100%">
        <thead>
            <tr>
                <th>Course Name</th>
				<th>Description</th>
				<?php /* <th>Sponsor</th> */ ?>
                <th>Enroll</th>
            </tr>
        </thead>
        <tbody>
		<?php
		foreach($courses_list as $course_list) {
			$mdl_course = $course_list->getObject();
			$course_list_item = new GcrCourseListItem($course_list);
			$eschool = $course_list->getApp();
			$id = 'gcr_course_' . $eschool->getShortName() . '_' . $mdl_course->id;
			$img_src = $course_list_item->getCourseIconUrl();
			$mdl_user = $course_list_item->getInstructor();
			$summary = $course_list_item->getSummary();
			$enrol_count = $course_list_item->getActiveUserCount();

			$shortsummary = GcrInstitutionTable::formatStringSize($summary, 250, 21);

			if ($mdl_user)
			{
				$teacher_text = GcrEschoolTable::getInstructorProfileHtml($mdl_user);
			}
			else
			{
				$teacher_text = 'None';
			}

			$fullname = GcrInstitutionTable::formatStringSize($mdl_course->fullname, 60, 30);
			$cost = $course_list->getCost();
			$cost_text = '';
			if ($cost)
			{
				$cost_text = 'Price: ' . GcrPurchaseTable::gc_format_money($cost);
			}
			?>
            <tr>
                <td><?php print $fullname; ?></td>
                <td><?php print $shortsummary ?></td>
				<?php /* <td><?php print $teacher_text ?></td> */ ?>
                <td><input type="submit" value="Enroll"/></td>
            </tr>			
			<?php
		}
		?>
		</tbody>
		</table>
    </div>
    


<script>
 
    $('#courses_list').dataTable( {} );
</script>
</div>
</section> <!-- #content -->