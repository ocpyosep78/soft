<?php
	
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
	<?php $this->load->view( 'website/common/header' ); ?>
	
	<div class="home_wrapper">
		<div class="container-fluid home_content">
			<div class="row-fluid hero_bar"><!-- start hero -->
				<div class="span12">
					<div class="row-fluid">
						<br />
						<div class="span9 offset1 home-hero">
							
							<div class="row-fluid">
								<div class="span11 offset1">
									<div class="row-fluid">
										<div class="span5">
											<h3>I'm looking for...</h3>
										</div>				
										<div class="span5">
											<h3>Location</h3>
										</div>
									</div>
									
									<div class="row-fluid">
										
										<div class="span5">
											<input type="text" class="span12 search_input" placeholder="What type of job are you looking for?">
										</div>
										
										<div class="span4">
											<select class="home_select">
												<option value="0">London</option>
												<option value="0">Paris</option>
											</select>							
										</div>
										
										<div class="span2">
											<a class="btn btn-primary btn-success search_btn" href="browse.html">Search</a>
										</div>
										
									</div>
								</div>
							</div>
						</div>
						
						
						
						
					</div>
					
					
					
				</div>
				
				
			</div>
		</div><!-- end hero -->
		<div class="container-fluid home_main_content">
				<div class="row-fluid">
	
	<div class="span9">
		<div class="row-fluid">
			
			<div class="span12">
				<h2>latest jobs</h2>
				<table class="table table-striped">
					<tbody>
						<tr >
							<td><span class="label label-success">Part time</span></td>
							<td><strong><a href="view-job.html">Refrigeration Repair Technician</a></strong><br />
								<a href="#">Sears Corp</a> &ndash; Posted by <a href="#">appthemedemo</a>
							</td>
							<td>Austin<br />Texas, United States</td>
							<td> 23 Jan 2013</td>
						</tr>	                
						<tr   class="success">
							<td><span class="label label-warning">Full time</span></td>
							<td><strong><a href="view-job.html">UI Developer</a></strong><br />
								<a href="#">BioWare Intl</a> &ndash; Posted by <a href="#">Bio</a>
							</td>
							<td>New York<br />NY, United States</td>
							<td> 23 Jan 2013</td>
						</tr>	                
						<tr  class="">
							<td><span class="label label-success">Part time</span></td>
							<td><strong><a href="view-job.html">Refrigeration Repair Technician</a></strong><br />
								<a href="#">Sears Corp</a> &ndash; Posted by <a href="#">Casino</a>
							</td>
							<td>London<br />Tottenham, Great Britain</td>
							<td> 23 Jan 2013</td>
						</tr>	                
						<tr  class="">
							<td><span class="label label-success">Part time</span></td>
							<td><strong><a href="view-job.html">Sr. Information Security Analyst</a></strong><br />
								<a href="#">Pivotal Labs</a> &ndash; Posted by <a href="#">Paul Smith</a>
							</td>
							<td>Austin<br />Texas, United States</td>
							<td> 23 Jan 2013</td>
							</tr>	                <tr  class="success">
							<td><span class="label label-inverse">Freelance</span></td>
							<td><strong><a href="view-job.html">Linux System Administrator</a></strong><br />
								<a href="#">Poker Studios</a> &ndash; Posted by <a href="#">Moker man</a>
							</td>
							<td>London<br />Tottenham, Great Britain</td>
							<td> 23 Jan 2013</td>
							</tr><tr  class="">
							<td><span class="label label-inverse">Freelance</span></td>
							<td><strong><a href="view-job.html">Front-end Developer</a></strong><br />
								<a href="#">Scripts Snapper</a> &ndash; Posted by <a href="#">Snapper</a>
							</td>
							<td>Hong Kong, China</td>
							<td> 23 Jan 2013</td>
							</tr><tr  class="">
							<td><span class="label label-inverse">Freelance</span></td>
							<td><strong><a href="view-job.html">Software Developer PHP and/or C# and .NET</a></strong><br />
								<a href="#">Sears Corp</a> &ndash; Posted by <a href="#">appthemedemo</a>
							</td>
							<td>Austin<br />Texas, United States</td>
							<td> 23 Jan 2013</td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>
	
	<div class="span3">
		<h2>&nbsp;</h2>
		<div class="row-fluid">
			<div class="span12 center">
				<a class="btn btn-primary btn-large post_job" href="post-job.html">post a job<br /><small>(it's free!)</small></a>
			</div>
		</div>
		<br />
		<h2>Browse jobs</h2>
		<div class="row-fluid">
			<div class="span12">
				<select class="span12" id="job_term_cat" name="job_term_cat">
					<option value="">Job category...</option>
					<option value="38" class="level-0">Automotive</option>
					<option value="43" class="level-1">&nbsp;&nbsp;&nbsp;Electrical</option>
					<option value="40" class="level-1">&nbsp;&nbsp;&nbsp;Inspection</option>
					<option value="41" class="level-1">&nbsp;&nbsp;&nbsp;Painting</option>
					<option value="39" class="level-1">&nbsp;&nbsp;&nbsp;Service</option>
					<option value="42" class="level-1">&nbsp;&nbsp;&nbsp;Upholstry</option>
					<option value="20" class="level-0">Construction</option>
					<option value="29" class="level-1">&nbsp;&nbsp;&nbsp;Carpenter</option>
					<option value="44" class="level-1">&nbsp;&nbsp;&nbsp;Electrician</option>
					<option value="34" class="level-1">&nbsp;&nbsp;&nbsp;Flooring</option>
					<option value="36" class="level-1">&nbsp;&nbsp;&nbsp;Foundation Repair</option>
					<option value="33" class="level-1">&nbsp;&nbsp;&nbsp;General Maintence</option>
					<option value="37" class="level-1">&nbsp;&nbsp;&nbsp;Inspections</option>
					<option value="35" class="level-1">&nbsp;&nbsp;&nbsp;Insulation</option>
					<option value="31" class="level-1">&nbsp;&nbsp;&nbsp;Mason</option>
					<option value="32" class="level-1">&nbsp;&nbsp;&nbsp;Painter</option>
					<option value="30" class="level-1">&nbsp;&nbsp;&nbsp;Plumber</option>
					<option value="50" class="level-0">Fashion</option>
					<option value="23" class="level-0">Food Service</option>
					<option value="24" class="level-1">&nbsp;&nbsp;&nbsp;Bartender</option>
					<option value="28" class="level-1">&nbsp;&nbsp;&nbsp;Cook</option>
					<option value="25" class="level-1">&nbsp;&nbsp;&nbsp;Hosting</option>
					<option value="26" class="level-1">&nbsp;&nbsp;&nbsp;Waiter</option>
					<option value="21" class="level-0">Insurance</option>
					<option value="22" class="level-0">Realtors</option>
					<option value="19" class="level-0">Technology</option>
					<option value="45" class="level-1">&nbsp;&nbsp;&nbsp;Engineering</option>
					<option value="46" class="level-1">&nbsp;&nbsp;&nbsp;Programming</option>
					<option value="47" class="level-1">&nbsp;&nbsp;&nbsp;Sys Admin</option>
				</select>
				
				
				<select id="job_type" name="job_type" class="span12" >
					<option>Job type...</option>
					<option>&nbsp;&nbsp;&nbsp;Freelance</option>
					<option>&nbsp;&nbsp;&nbsp;Full-Time</option>
					<option>&nbsp;&nbsp;&nbsp;Internship</option>
					<option>&nbsp;&nbsp;&nbsp;Part-Time</option>
					<option>&nbsp;&nbsp;&nbsp;Temporary</option>
				</select>
				
				
				<select class="span12" id="job_term_salary" name="job_term_salary">
					<option value="">Job salary...</option>
					<option value="9" class="level-0">&nbsp;&nbsp;&nbsp;Less than 20,000</option>
					<option value="10" class="level-0">&nbsp;&nbsp;&nbsp;20,000 &ndash; 40,000</option>
					<option value="11" class="level-0">&nbsp;&nbsp;&nbsp;40,000 &ndash; 60,000</option>
					<option value="12" class="level-0">&nbsp;&nbsp;&nbsp;60,000 &ndash; 80,000</option>
					<option value="13" class="level-0">&nbsp;&nbsp;&nbsp;80,000 &ndash; 100,000</option>
					<option value="14" class="level-0">&nbsp;&nbsp;&nbsp;100,000 and above</option>
				</select>
				
				<select class="span12" id="job_term_salary2" name="job_term_salary2">
					<option value="">Date posted...</option>
					<option value="">Today</option>
					<option value="">This week</option>
					<option value="">Last week</option>
					<option value="">This month</option>
				</select>
				
				<a class="btn btn-large pull-right search_btn" href="browse.html">Search</a>
				
			</div>			
		</div>
		<h2>Stay connected</h2>
		<div class="row-fluid">
			<div class="span12">
				<ul class="social-icons">
					<li><a href="#"><i class="icon-facebook-sign icon-2x"></i></a></li>
					<li><a href="#"><i class="icon-twitter-sign icon-2x"></i></a></li>
					<li><a href="#"><i class="icon-google-plus-sign icon-2x"></i></a></li>
					<li><a href="#"><i class="icon-linkedin-sign icon-2x"></i></a></li>
					<li><a href="#"><i class="icon-pinterest-sign icon-2x"></i></a></li>
				</ul>
				<p>Stay connected to the latest jobs, events and career advice.</p>
			</div>			
		</div>
				
		
	</div>
</div>
</div></div> <!-- /container -->

<?php $this->load->view( 'website/common/footer' ); ?>
</body>
</html>