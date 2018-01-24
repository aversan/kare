<?php
/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
} ?>

<div class="to-designers__form">
	<div class="main-catalog__title"><?=$arResult["FORM_TITLE"]?></div>
	<? if ($_REQUEST['formresult'] == 'addok') { ?>
		<h3 class="js-feedback-form-msg">Спасибо! Мы свяжемся с Вами в течение рабочего дня.</h3>
	<? } else { ?>
		<? if ($arResult['isFormErrors'] == "Y") { ?>
			<?= $arResult['FORM_ERRORS_TEXT'] ?>
		<? } ?>

		<form class="js-feedback-form" name="<?= $arResult["arForm"]["SID"] ?>" data-ajax="<?= $arParams['ACTION_URL'] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
			<?= bitrix_sessid_post(); ?>
			<input type="hidden" name="WEB_FORM_ID" value="<?= $arResult["arForm"]["ID"] ?>">
			<input type="hidden" name="WED_FORM_SID" value="<?= $arResult["arForm"]["SID"] ?>">

			<? foreach ($arResult["QUESTIONS"] as $key => $arQuestion) { ?>
				<div class="custom-input-block">
					<?= $arQuestion["HTML_CODE"] ?>
				</div>
			<? } ?>
			<div class="checkbox-input checkbox-input-br">
				<input class="checkbox-input__input js-personal-data" type="checkbox" id="dis_personal_data" name="dis_personal_data" checked required>
				<label class="checkbox-input__label" for="dis_personal_data">
					Я даю <a href="/help/dogovor-oferty/" target="_blank">согласие на обработку своих персональных данных</a> и соглашаюсь с правилами безопасности компании *
				</label>
			</div>
			<div class="two-sides">
				<div>
					<input class="butn btn-base" type="submit" name="web_form_submit" value="<?= $arResult["arForm"]["BUTTON"] ?>">
				</div>
				<div>
					<span class="base-text comments__required">* Обязательно для заполнения</span>
				</div>
			</div>
		</form>
	<? } ?>
</div>