<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<form id="auth_params" action="<?=SITE_DIR?>ajax/show_personal_block.php">
	<input type="hidden" name="REGISTER_URL" value="<?=$arParams["REGISTER_URL"]?>" />
	<input type="hidden" name="FORGOT_PASSWORD_URL" value="<?=$arParams["FORGOT_PASSWORD_URL"]?>" />
	<input type="hidden" name="PROFILE_URL" value="<?=$arParams["PROFILE_URL"]?>" />
	<input type="hidden" name="SHOW_ERRORS" value="<?=$arParams["SHOW_ERRORS"]?>" />
</form>
<?
$frame = $this->createFrame()->begin('');
$frame->setBrowserStorage(true);
?>
<?if(!$USER->IsAuthorized()):?>
	<div class="user-block__item no-have-user">
		<div class="js-login-btn">
			<a class="user-block__enter-btn" href="#">Вход</a>
			<div class="user-block__icon icon-wrapper">
				<?= \Local\Svg::get('ic-user', 'icon icon_user icon_grey') ;?>
			</div>
		</div>
		<div class="user-block__popup js-closed js-popup js-auth-popup">
			<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

			<div class="bx-system-auth-form js-auth-form-errors">

			<?
			if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
				ShowMessage($arResult['ERROR_MESSAGE']);
			?>

			<?if($arResult["FORM_TYPE"] == "login"):?>

			<form class="auth-form js-auth-form-top" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="/auth/?login=yes">
			<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif?>
			<?foreach ($arResult["POST"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>
				<input type="hidden" name="AUTH_FORM" value="Y" />
				<input type="hidden" name="TYPE" value="AUTH" />
				<input type="hidden" name="isAuth" value="Y" />
				<table width="100%">
					<tr>
						<td class="auth-form__td auth-form__td_top" colspan="3">
							<span class="auth-form__title">Вход в личный кабинет</span>
							<button class="btn btn_close icon-wrapper js-close-btn" type="button">
								<?= \Local\Svg::get('close', 'icon icon_close icon_darkgrey') ;?>
							</button>
						</td>
					</tr>
					<tr>
						<td class="auth-form__td" colspan="3">
							<div class="auth-form__input-wrap">
								<input class="auth-form__input js-phone js-auth-phone" id="phone-field" type="tel" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" size="17" placeholder="Телефон&#42;" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td class="auth-form__td" colspan="3">
							<div class="auth-form__input-wrap">
								<input class="auth-form__input" id="password-field" type="password" name="USER_PASSWORD" maxlength="50" size="17" autocomplete="off" placeholder="Пароль&#42;" required />
							</div>
							<?if($arResult["SECURE_AUTH"]):?>
							<span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
								<div class="bx-auth-secure-icon"></div>
							</span>
							<noscript>
							<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
								<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
							</span>
							</noscript>
							<script type="text/javascript">
							document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
							</script>
							<?endif?>
						</td>
					</tr>
					<tr>
						<td class="auth-form__td auth-form__td_foget" colspan="3">
							<noindex><a href="/ajax/send_password.php" rel="nofollow" class="js-pass-request">Выслать пароль по sms</a></noindex>
							<div class="send-pass-msg js-send-pass-msg"></div></td>
					</tr>
				<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
					<tr>
						<td class="auth-form__td auth-form__td_enter">
							<button  class="auth-form__btn btn_popup" type="submit">Войти</button>
							<input type="hidden" name="Login" value="y">
						</td>
						<td class="auth-form__td auth-form__td_remember" width="100%">
							<div class="checkbox-input">
								<input class="checkbox-input__input auth-form__input" type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" />
								<label class="checkbox-input__label" for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
							</div>
						</td>
					</tr>
				<?endif?>

				<?if($arResult["NEW_USER_REGISTRATION"] == "Y"):?>
					<tr>
						<td class="auth-form__td auth-form__td_bottom" colspan="3"><noindex><a href="<?=$arParams["REGISTER_URL"]?>" rel="nofollow" class="js-auth-request"><?=GetMessage("AUTH_REGISTER")?></a></noindex><br /></td>
					</tr>
				<?endif?>



				</table>
			</form>

			<?if($arResult["AUTH_SERVICES"]):?>
			<?
			$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
				array(
					"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
					"AUTH_URL"=>$arResult["AUTH_URL"],
					"POST"=>$arResult["POST"],
					"POPUP"=>"Y",
					"SUFFIX"=>"form",
				),
				$component,
				array("HIDE_ICONS"=>"Y")
			);
			?>
			<?endif?>

			<?
			elseif($arResult["FORM_TYPE"] == "otp"):
			?>

			<form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
			<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif?>
				<input type="hidden" name="AUTH_FORM" value="Y" />
				<input type="hidden" name="TYPE" value="OTP" />
				<table width="95%">
					<tr>
						<td colspan="2">
						<?echo GetMessage("auth_form_comp_otp")?><br />
						<input type="text" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off" /></td>
					</tr>
			<?if ($arResult["CAPTCHA_CODE"]):?>
					<tr>
						<td colspan="2">
						<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
						<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
						<input type="text" name="captcha_word" maxlength="50" value="" /></td>
					</tr>
			<?endif?>
			<?if ($arResult["REMEMBER_OTP"] == "Y"):?>
					<tr>
						<td valign="top"><input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y" /></td>
						<td width="100%"><label for="OTP_REMEMBER_frm" title="<?echo GetMessage("auth_form_comp_otp_remember_title")?>"><?echo GetMessage("auth_form_comp_otp_remember")?></label></td>
					</tr>
			<?endif?>
					<tr>
						<td colspan="2"><input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /></td>
					</tr>
					<tr>
						<td colspan="2"><noindex><a href="<?=$arResult["AUTH_LOGIN_URL"]?>" rel="nofollow"><?echo GetMessage("auth_form_comp_auth")?></a></noindex><br /></td>
					</tr>
				</table>
			</form>

			<?
			else:
			?>

			<form action="<?=$arResult["AUTH_URL"]?>">
				<table width="95%">
					<tr>
						<td align="center">
							<?=$arResult["USER_NAME"]?><br />
							[<?=$arResult["USER_LOGIN"]?>]<br />
							<a href="<?=$arResult["PROFILE_URL"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><?=GetMessage("AUTH_PROFILE")?></a><br />
						</td>
					</tr>
					<tr>
						<td align="center">
						<?foreach ($arResult["GET"] as $key => $value):?>
							<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
						<?endforeach?>
						<input type="hidden" name="logout" value="yes" />
						<input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" />
						</td>
					</tr>
				</table>
			</form>
			<?endif?>
			</div>

		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			jqmEd('enter', 'auth', '.avtorization-call.enter');
		});
		</script>
	</div>

<?else:?>
	<div class="user-block__item have-user">
		<?$name = explode(' ', $arResult["USER_NAME"]);?>
		<?$name = $name[0];?>
		<!--noindex--><a href="<?=$arResult["PROFILE_URL"]?>" class="reg" rel="nofollow"><?=$arResult["USER_NAME"]?></a><!--/noindex-->
		/
		<!--noindex--><a href="<?=SITE_DIR?>?logout=yes" class="exit" rel="nofollow"><?=GetMessage("AUTH_LOGOUT_BUTTON");?></a><!--/noindex-->
		<script type="text/javascript">
		$(document).ready(function(){
			$('user-block__item.have-user .have_user_name').each(function() {
				//$(this).dotdotdot();
			});
		});
		</script>
	</div>
<?endif;?>
<?$frame->end();?>