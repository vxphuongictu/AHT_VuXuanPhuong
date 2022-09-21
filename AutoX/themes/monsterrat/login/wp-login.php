<?php
/* Template Name: Login Page */
get_header('login');
if (is_user_logged_in()):
    wp_redirect(home_url());
endif;
?>
<article id="login-page" class="container-fluid">
	<section class="login-page">

		<div style='background-image: url("<?php echo empty(get_theme_mod('background-image-login-page')) === false ? get_theme_mod('background-image-login-page') : ''; ?>")'; class="background-image-login-page">
			<div class="login-form">
				<div class="login-form-group">
					<div class="login-title">
						<h1>
							<?php echo empty(get_theme_mod('login-title')) === false ? get_theme_mod('login-title') : ''; ?>
						</h1>
					</div>

					<div class="login-content">
						<p><?php echo empty(get_theme_mod('login-content')) === false ? get_theme_mod('login-content') : ''; ?>
						</p>
					</div>

					<div class="wp-login-form">
						<div name="login-form" id="login-form">
							<p class="login-username">
								<label for="user_login"></label>
								<input type="text" name="user_login" id="user_login" autocomplete="username" class="input" value="" size="20" placeholder="<?php echo empty(get_theme_mod('placeholder-username')) === false ? get_theme_mod('placeholder-username') : ''; ?>">
							</p>

							<p class="login-password">
								<label for="user_pass"></label>
								<input type="password" name="user_pass" id="user_pass" autocomplete="current-password" class="input" value="" size="20" placeholder="<?php echo empty(get_theme_mod('placeholder-password')) === false ? get_theme_mod('placeholder-password') : ''; ?>">
							</p>

							<div class="option-form">
								<p class="login-remember">
									<label>
										<input name="rememberme" type="checkbox" id="rememberme" value="forever">
										<?php echo empty(get_theme_mod('login-label-remember')) === false ? get_theme_mod('login-label-remember') : ''; ?>
									</label>
								</p>

								<p class="login-lost-password">
									<a name="lostpassword" id="lostpassword" href="<?php echo empty(get_theme_mod('lost-password-url')) === false ? get_theme_mod('lost-password-url') : ''; ?>" class="lostpassword" target="_blank">
										<?php echo empty(get_theme_mod('lost-password-label')) === false ? get_theme_mod('lost-password-label') : ''; ?>
									</a>
								</p>
							</div>

							<?php wp_nonce_field( 'login_security', 'login_nonce' ); ?>
							<p class="login-submit">
								<input type="submit" name="wp-submit-login" id="wp-submit-login" class="button button-primary" value="<?php echo empty(get_theme_mod('btn-login-label')) === false ? get_theme_mod('btn-login-label') : ''; ?>">
							</p>

							<p class="regsiter-submit">
								<input type="button" name="register-submit-login-page" id="register-submit-login-page" class="button button-primary" value="<?php echo empty(get_theme_mod('label-regsiter')) === false ? get_theme_mod('label-regsiter') : ''; ?>" />
							</p>
						</div>
					</div>
				</div>

				<div class="register-form-group">
					<div class="register-title">
						<h1>
							<?php echo empty(get_theme_mod('regsiter-title')) === false ? get_theme_mod('regsiter-title') : ''; ?>
						</h1>
					</div>

					<div class="register-content">
						<p>
							<?php echo empty(get_theme_mod('regsiter-content')) === false ? get_theme_mod('regsiter-content') : ''; ?>
						</p>
					</div>

					<div class="wp-register-form">
						<div name="register-form" id="register-form" >
							<p class="register-fullname">
								<label for="fullname_register"></label>
								<input type="text" name="fullname" id="fullname_register" autocomplete="fullname" class="input" value="" size="20" placeholder="<?php echo empty(get_theme_mod('placeholder-fullname')) === false ? get_theme_mod('placeholder-fullname') : ''; ?>">
							</p>

							<p class="register-username">
								<label for="user_register"></label>
								<input type="text" name="user_register" id="user_register" autocomplete="username" class="input" value="" size="20" placeholder="<?php echo empty(get_theme_mod('placeholder-phonenumber')) === false ? get_theme_mod('placeholder-phonenumber') : ''; ?>">
							</p>

							<p class="register-email">
								<label for="email_register"></label>
								<input type="text" name="email_register" id="email_register" autocomplete="email" class="input" value="" size="20" placeholder="<?php echo empty(get_theme_mod('placeholder-email')) === false ? get_theme_mod('placeholder-email') : ''; ?>">
							</p>

							<p class="register-password">
								<label for="new_pass"></label>
								<input type="password" name="new_pass" id="new_pass" autocomplete="new-password" class="input" value="" size="20" placeholder="<?php echo empty(get_theme_mod('placeholder-password')) === false ? get_theme_mod('placeholder-password') : ''; ?>">
							</p>

							<p class="register-confirm-password">
								<label for="user_confirm_pass"></label>
								<input type="password" name="user_confirm_pass" id="user_confirm_pass" autocomplete="confirm-password" class="input" value="" size="20" placeholder="<?php echo empty(get_theme_mod('placeholder-confirmpwd')) === false ? get_theme_mod('placeholder-confirmpwd') : ''; ?>">
							</p>

							<div class="option-form">
								<p class="register-agree">
									<label>
										<input name="agree" type="checkbox" id="agree" value="checked">
										<?php echo empty(get_theme_mod('agree-content')) === false ? get_theme_mod('agree-content') : ''; ?>
									</label>
								</p>
							</div>

							<?php wp_nonce_field( 'register_security', 'register_nonce' ); ?>

							<p class="register-submit">
								<input type="submit" name="wp-submit-register" id="wp-submit-register" class="button button-primary" value="<?php echo empty(get_theme_mod('regsiter-title')) === false ? get_theme_mod('regsiter-title') : ''; ?>">
							</p>

							<p class="login-submit">
								<input type="button" name="login-submit-register-page" id="login-submit-register-page" class="button button-primary" value="<?php echo empty(get_theme_mod('label-login-btn-register-page')) === false ? get_theme_mod('label-login-btn-register-page') : ''; ?>" />
							</p>
						</div>
					</div>
				</div>

				<a class="login-logo" href="<?php echo get_home_url(); ?>">
					<img src="<?php echo empty(get_theme_mod('login-logo')) === false ? get_theme_mod('login-logo') : ''; ?>" alt="login-logo">
				</a>
			</div>
		</div>
	</section>
</article>

<?php echo do_action('login_scripts'); ?>
<?php get_footer('login'); ?>