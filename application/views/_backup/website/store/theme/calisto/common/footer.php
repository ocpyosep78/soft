<?php
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
?>

<div class="main-footer-wrapper">
	<a href="#" class="back-to-the-top">Go back to the top</a>
	
	<div class="main-footer">
		<div class="newsletter">
			<div class="main-title"><p class="custom-font-1">Newsletter</p></div>
			<form id="form-newsletter">
				<input type="text" class="input-text-2 trans-1 required email" name="email" placeholder="your email address" />
				<input type="submit" value="Subscribe" class="submit custom-font-1" />
				<div class="clear"></div>
			</form>
			
			<p class="news-message hide" style="font-weight: bold;"></p>
			<p><?php echo $store['newsletter']['content']; ?></p>
		</div>
		<script type="text/javascript">
			$('#form-newsletter').submit(function() {
				$('.news-message').hide();
				$('#form-newsletter [name="email"]').css('background-color', '#FFFFFF');
				var validation = Site.Form.Validation('form-newsletter', {});
				if (validation.length > 0) {
					$('#form-newsletter [name="email"]').css('background-color', '#F2A683');
					return false;
				}
				
				var p = Site.Form.GetValue('form-newsletter');
				p.action = 'SubscribeNewsletter';
				Func.ajax({ url: Site.Host + '/ajax/newsletter', param: p, callback: function(result) {
					var message = (result.status) ? 'Terima kasih, email anda berhasil terdaftar.' : 'Email anda sudah terdaftar';
					$('.news-message').text(message);
					$('.news-message').slideDown(500);
				} });
				return false;
			});
		</script>

		<div class="menu">
			<div class="main-title">
				<p class="custom-font-1">Menu</p>
			</div>
			<ul>
				<li><a href="<?php echo site_url(); ?>">Homepage</a></li>
				<li><a href="<?php echo site_url('blog'); ?>">Blog</a></li>
				<li><a href="<?php echo site_url('contact'); ?>">Contact us</a></li>
			</ul>
		</div>

		<div class="about-us">
			<div class="main-title">
				<p class="custom-font-1">About us</p>
			</div>
			<p><?php echo $store['about_us']['content']; ?></p>
			<!--
			<p class="social">
				<b>Find us on social networks:</b>
				<a href="#"><img src="<?php echo base_url(); ?>static/theme/calisto/img/ico-youtube-1.png" alt="YouTube" width="23" height="21" /></a>
				<a href="#"><img src="<?php echo base_url(); ?>static/theme/calisto/img/ico-facebook-1.png" alt="Facebook" width="23" height="21" /></a>
				<a href="#"><img src="<?php echo base_url(); ?>static/theme/calisto/img/ico-twitter-1.png" alt="Twitter" width="23" height="21" /></a>
				<a href="#"><img src="<?php echo base_url(); ?>static/theme/calisto/img/ico-flickr-1.png" alt="Flickr" width="23" height="21" /></a>
				<a href="#"><img src="<?php echo base_url(); ?>static/theme/calisto/img/ico-rss-1.png" alt="RSS" width="23" height="21" /></a>
			</p>
			<p class="social">
				<b>We accept:</b>
				<a href="#"><img src="<?php echo base_url(); ?>static/theme/calisto/img/ico-visa-1.png" alt="VISA" width="30" height="19" /></a>
				<a href="#"><img src="<?php echo base_url(); ?>static/theme/calisto/img/ico-mastercard-1.png" alt="MasterCard" width="30" height="19" /></a>
				<a href="#"><img src="<?php echo base_url(); ?>static/theme/calisto/img/ico-americanexpress-1.png" alt="American Express" width="30" height="19" /></a>
				<a href="#"><img src="<?php echo base_url(); ?>static/theme/calisto/img/ico-paypal-1.png" alt="PayPal" width="30" height="19" /></a>
			</p>
			-->
		</div>

		<div class="copyright">
			<table>
				<tr>
					<td>
						<span>&copy; <?php echo date("Y"); ?> Ecommerce software by Simetri
					</td>
				</tr>
			</table>
		</div>

	</div>
</div>