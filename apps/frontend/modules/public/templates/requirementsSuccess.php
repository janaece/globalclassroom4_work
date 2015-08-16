<?php slot("page_title") ?>
	<div class="page_title">
		<h3>System Requirements</h3>
	</div>
<?php end_slot() ?>
<div>
	<div>
		<h4>Hardware &amp; Software Requirements</h4>
		<br/>
		<h5>Hardware</h5>
		<ul>
			<li>Windows: Windows 98, ME, NT, 4.0, 2000, or XP and above, and a DSL Internet connection or better**</li>
			<li>Macintosh: Mac OS X and a DSL Internet connection or better**</li>
			<li>Linux*: Ubuntu or other modern Linux OS capable of running Firefox and a DSL Internet connection or better**</li>
		</ul>
		<p>
			<strong>**Dial-up connections are not capable of streaming video, in some courses there are streamed video feeds from 
			3rd party and Global Classroom hosted sources.  If you are having trouble viewing course resources, please contact your 
			teacher for an alternative way to view the information.</strong>
		</p>
		<p>
			<strong>*Individual courses may require the installation of proprietary software on Linux machines, based on needs of the 
			instructor/course.</strong>
		</p>
	</div>
	<br/>
	<div>
		<h5>Browsers</h5>
		<ul>
			<li>Firefox is recommended for all platforms. <?php print link_to("Free download", "http://mozilla.com/firefox"); ?></li>
			<li>Opera, Safari, and Google Chrome will now show the built-in HTML editor for Moodle.</li>
			<li>Internet Explorer can potentially cause running errors and neglects to show important icons in the editing view 
			for teachers and administrators.</li>
		</ul>
	</div>
	<br/>
	<div>
		<h5>Pop-up configuration</h5>
		<p>
			Some of the moodle courses use pop-up windows, therefore you will need to disable pop-up blockers in your browser.  Some 
			blockers may be turned off on Windows by right clicking an icon on the system tray, next to where the clock resides.
		</p>
	</div>
	<div>
		<h5>Firefox</h5>
		<ol>
			<li>Go to Tools > Options.</li>
			<li>Click Content tab.</li>
			<li>If Block pop-up windows is checked, then click Exceptions button near it.</li>
			<li>Type globalclassroom.us.</li>
			<li>Click Allow, click Close, click OK.</li>
		</ol>
	</div>
	<br/>
	<div>
		<h5>Internet Explorer's Built-In Popup Blocker</h5>
		<ol>
			<li>Go to Tools: Popup Blocker.  At this point you can either just turn the pop-up blocker off, or continue with the 
			settings below.</li>
			<li>Select Popup Blocker Settings.</li>
			<li>For Address of Web site to allow, type https://www.globalclassroomclassic.us</li>
			<li>Click the Add button.</li>
			<li>Click the Close button.</li>
			<li>Close and restart Internet Explorer.</li>
		</ol>
	</div>
	<br/>
	<div>
		<h5>Netscape 7.1 or 7.2</h5>
		<ol>
			<li>If you are using Netscape 7.1 or 7.2, select Popup Windows.</li>
			<li>Either uncheck Block unrequested sites and skip to step 5 or continue with the steps below.</li>
			<li>Click the Allowed Sites button.</li>
			<li>Enter globalclassroom.us in the text field.  Click Add.</li>
			<li>Click OK.</li>
		</ol>
	</div>
	<br/>
	<div>
		<h5>Yahoo! Toolbar</h5>
		<ol>
			<li>Go to your Moodle course.</li>
			<li>Click the Yahoo! Toolbar Pop-Up Blocker button.</li>
			<li>Click Always Allow Pop-Up From...</li>
			<li>Click globalclassroom.us.</li>
			<li>Click the Allow button.</li>
			<li>In response to the Always allow pop-ups from globalclassroom.us dialog box, click the OK button.</li>
			<li>Click the Close button.</li>
		</ol>
	</div>
	<br/>
	<div>
		<h5>Google Toolbar</h5>
		<ol>
			<li>Go to your Moodle course.</li>
			<li>Once you have entered the course, in the Google Toolbar click the button which shows the number of pop-ups that have 
			been blocked.  The button will now say "Popups okay."</li>
		</ol>
	</div>
	<br/>
	<div>
		<h4>Free downloads</h4>
	</div>
		<table id="downloads_table">
			<tr>
				<td>
				</td>
				<td>
					Win
				</td>
				<td>
					Mac
				</td>
			</tr>
			<tr>
				<td>
					Firefox
				</td>
				<td>
					<?php print link_to("Download", "http://mozilla.com/firefox"); ?>
				</td>
				<td>
					<?php print link_to("Download", "http://mozilla.com/firefox"); ?>
				</td>			
			</tr>
			<tr>
				<td>
					Internet Explorer 8
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/windows/downloads/ie/getitnow.mspx" ); ?>
				</td>
				<td>
				
				</td>				
			</tr>
			<tr>
				<td>
					Netscape
				</td>
				<td>
					<?php print link_to("Download", "http://browser.netscape.com/ns8/download/archive80x.jsp" ); ?>
				</td>
				<td>
					<?php print link_to("Download", "http://browser.netscape.com/ns8/download/archive80x.jsp" ); ?>
				</td>				
			</tr>
			<tr>
				<td>
					Opera
				</td>
				<td>
					<?php print link_to("Download", "http://www.opera.com/" ); ?>
				</td>
				<td>
					<?php print link_to("Download", "http://www.opera.com/" ); ?>
				</td>				
			</tr>
			<tr>
				<td>
					Adobe Reader
				</td>
				<td>
					 <?php print link_to("Download", "http://www.adobe.com/products/acrobat/readstep2.html" ); ?>
				</td>
				<td>
					 <?php print link_to("Download", "http://www.adobe.com/products/acrobat/readstep2.html" ); ?>
				</td>				
			</tr>
			<tr>
				<td>
					Flash Player
				</td>
				<td>
					<?php print link_to("Download", "http://www.macromedia.com/go/getflash" ); ?>
				</td>
				<td>
					<?php print link_to("Download", "http://www.macromedia.com/go/getflash" ); ?>
				</td>				
			</tr>
			<tr>
				<td>
					Shockwave Player
				</td>
				<td>
					<?php print link_to("Download", "http://www.adobe.com/shockwave/download/" ); ?>
				</td>
				<td>
					<?php print link_to("Download", "http://www.adobe.com/shockwave/download/" ); ?>
				</td>				
			</tr>
			<tr>
				<td>
					Windows Media Player
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/windows/windowsmedia/download/AllDownloads.aspx?displang=en&qstechnology=" ); ?>
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/windows/windowsmedia/download/AllDownloads.aspx?displang=en&qstechnology=" ); ?>
				</td>			
			</tr>
			<tr>
				<td>
					RealPlayer 10
				</td>
				<td>
					<?php print link_to("Download", "http://www.download.com/RealPlayer/3000-2139_4-10255189.html?tag=lst-0-2p" ); ?>
				</td>
				<td>
					<?php print link_to("Download", "http://www.download.com/RealPlayer/3000-2174_4-10382108.html?tag=lst-0-3" ); ?>
				</td>				
			</tr>
			<tr>
				<td>
					QuickTime Player
				</td>
				<td>
					<?php print link_to("Download", "http://www.apple.com/quicktime/download/win.html" ); ?>
				</td>
				<td>
					<?php print link_to("Download", "http://www.apple.com/quicktime/download/mac.html" ); ?>
				</td>				
			</tr>
			<tr>
				<td>
					PowerPoint Viewer
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/downloads/details.aspx?FamilyID=428d5727-43ab-4f24-90b7-a94784af71a4&displaylang=en" ); ?>
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/downloads/details.aspx?FamilyID=e25cb1e5-209c-4a58-b283-23e84b616477&DisplayLang=en" ); ?>
				</td>				
			</tr>
			<tr>
				<td>
					Excel Viewer
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/downloads/en/details.aspx?familyid=1cd6acf9-ce06-4e1c-8dcf-f33f669dbc3a&displaylang=en" ); ?>
				</td>
				<td>
				</td>				
			</tr>
			<tr>
				<td>
					Word Viewer
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/downloads/en/details.aspx?FamilyID=3657ce88-7cfa-457a-9aec-f4f827f20cac&displaylang=en" ); ?>
				</td>
				<td>
				</td>				
			</tr>
			<tr>
				<td>
					Visio Viewer
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/downloads/en/details.aspx?FamilyID=f9ed50b0-c7df-4fb8-89f8-db2932e624f7&displaylang=en" ); ?>
				</td>
				<td>
				</td>				
			</tr>
			<tr>
				<td>
					Microsoft Works 6-9 File Converter
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/downloads/details.aspx?FamilyID=BF41401E-70FA-465D-AE2E-CF44DBF05297&amp;displaylang=el&displaylang=en" ); ?>
				</td>
				<td>
				</td>				
			</tr>
			<tr>
				<td>
					PowerPoint '98 Viewer
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/downloads/info.aspx?na=0&p=9&SrcDisplayLang=en&SrcCategoryId=5&SrcFamilyId=&genscs=&u=/downloads/details.aspx?FamilyID=e25cb1e5-209c-4a58-b283-23e84b616477&displaylang=en" ); ?>
				</td>
				<td>
				</td>				
			</tr>
			<tr>
				<td>
					Microsoft Word 97, 98, and 2000 Converter
				</td>
				<td>
					<?php print link_to("Download", "http://www.microsoft.com/downloads/info.aspx?na=0&p=12&SrcDisplayLang=en&SrcCategoryId=5&SrcFamilyId=&genscs=&u=/downloads/details.aspx?FamilyID=18e45927-a094-4353-aa00-bd46f3709ff8&displaylang=en" ); ?>
				</td>
				<td>
				</td>				
			</tr>																																												
		</table>
</div>