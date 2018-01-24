<?php
/**
 * @var array $arResult
 * @var array $arParams
 * @var \CMain $APPLICATION
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
global $USER;
include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/props_format.php");?>
<div class="two-cols__left">
	<? if ($_REQUEST['PERMANENT_MODE_STEPS'] == 1) {
		?>
		<input type="hidden" name="PERMANENT_MODE_STEPS" value="1"/>
		<?
	}

	if (!empty($arResult["ERROR"])) { ?>
		<script type="text/javascript">
			top.BX.scrollToNode(top.BX('ORDER_FORM'));
		</script>
	<? }

	if ($USER->IsAuthorized()) {
		include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/person_type.php");
		include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/props.php");
		include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery.php");
		include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/related_props.php");
		include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/paysystem.php");?>
		<div class="bordered-block bordered-block--ind">
			<? PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"]['Подписка'], $arParams["TEMPLATE_LOCATION"]);?>
		</div>
	<? } else {
		$APPLICATION->IncludeComponent(
			"bitrix:system.auth.form",
			"order_redisign",
			Array(
				"REGISTER_URL" => SITE_DIR."auth/registration/",
				"PROFILE_URL" => SITE_DIR."personal/history-of-orders/",
				"FORGOT_PASSWORD_URL" => SITE_DIR."auth/forgot-password",
				"SHOW_ERRORS" => "Y"
			)
		);
	} ?>
</div>
<div class="two-cols__right">
	<? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/small_summary.php");
	if (strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
		echo $arResult["PREPAY_ADIT_FIELDS"];
	?>
</div>
<? if ($USER->IsAuthorized()) { ?>
	<div class="order-checkout-info">Внимание! Получить товар может только покупатель по паспорту.</div>
	<div class="cart__ctrl cart__ctrl--column">
		<div class="custom-input-block custom-input-block--sms">
			<label for="sms-code-input" class="custom-input-block__label custom-input-block__label--sms">Поле ввода SMS-кода*</label>
			<input id="sms-code-input" class="custom-input js-sms-confirm-input" type="text" name="sms_confirm" placeholder="Поле ввода sms-кода*">
			<div class="js-sms-check-msg sms-msg sms-msg--error"></div>
		</div>
		<button class="butn btn-base js-sms-confirm" data-ajax-url="/ajax/order_confirm.php" type="button">Получить код подтверждения заказа по sms</button>
		<div class="js-sms-confirm-msg sms-msg"></div>
	</div>
<? } ?>