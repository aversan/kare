<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<article class="ask-question ajax-form">
	<div class="ask-question__wrapper cf">
		<div class="ask-question__info">
			<?$APPLICATION->IncludeFile(SITE_DIR."include/ask_tab_detail_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ASK_DESCRIPTION')));?>
		</div>
		<div class="ask-question__form js-ask-form" id="js-ask-form">
			<? if ($_REQUEST['formresult'] == 'addok') { ?>
				<h3>Спасибо! Мы свяжемся с Вами в течение рабочего дня.</h3>
			<? } else {
				if ($arResult['isFormErrors'] == "Y") {
					echo $arResult['FORM_ERRORS_TEXT'];
				} ?>

			<form class="question-form js-question-form js-form" name="<?=$arResult["arForm"]["SID"]?>" data-ajax="/ajax/forms.php" action="/ajax/forms.php" method="POST" enctype="multipart/form-data">
				<div class="question-form__inner">
					<?=bitrix_sessid_post();?>
					<input type="hidden" name="WEB_FORM_ID" value="<?=$arResult["arForm"]["ID"]?>">
					<input type="hidden" name="WED_FORM_SID" value="<?=$arResult["arForm"]["SID"]?>">

					<? foreach ($arResult["QUESTIONS"] as $key => $arQuestion) { ?>
						<div class="input-wrap question-form__input-wrap">
							<?=$arQuestion["HTML_CODE"]?>
						</div>
					<? } ?>
				</div>
				<div class="checkbox-input checkbox-input-br">
					<input class="checkbox-input__input js-personal-data" type="checkbox" id="dis_personal_data" name="dis_personal_data" checked required>
					<label class="checkbox-input__label" for="dis_personal_data">
						Я даю <a href="/help/dogovor-oferty/" target="_blank">согласие на обработку своих персональных данных</a> и соглашаюсь с правилами безопасности компании *
					</label>
				</div>
				<div class="question-form__bottom">
					<input type="hidden" name="web_form_submit" value="Y">
					<input class="question-form__btn" type="submit" name="web_form_submit" value="Отправить">
					<span class="question-form__note">&#042;Обязательно для заполнения</span>
				</div>
			</form>
			<? } ?>
		</div>
	</div>
</article>