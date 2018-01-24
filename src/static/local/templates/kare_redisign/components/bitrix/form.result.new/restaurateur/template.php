<? if ($arResult['isFormErrors'] == "Y") { ?>
	<?=$arResult['FORM_ERRORS_TEXT']?>
<? } ?>
<? if ($_REQUEST['formresult'] == 'addok') { ?>
	<h3 class="js-feedback-form-msg">Спасибо! Мы свяжемся с Вами в течение рабочего дня.</h3>
<? } else { ?>
	<div id="rest-order" class="custom-modal modal">
		<div class="custom-modal__title">Оставить заявку</div>
		<form class="js-feedback-form js-rest-form" name="<?= $arResult["arForm"]["SID"] ?>" data-ajax="<?= $arParams['ACTION_URL'] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
			<?=bitrix_sessid_post(); ?>
			<input type="hidden" name="WEB_FORM_ID" value="<?=$arResult["arForm"]["ID"]?>">
			<input type="hidden" name="WED_FORM_SID" value="<?=$arResult["arForm"]["SID"]?>">
			<?=$arResult['QUESTIONS']['CASE']['HTML_CODE'];?>
			<?=$arResult['QUESTIONS']['CASE_ID']['HTML_CODE'];?>
			<? foreach ($arResult['QUESTIONS'] as $key => $question) {
				if (in_array($key, ['CASE', 'CASE_ID'])) {
					continue;
				} ?>
				<div class="custom-input-block">
					<?=$question['HTML_CODE'];?>
				</div>
			<? } ?>
			<div class="checkbox-input checkbox-input-br">
				<input class="checkbox-input__input js-personal-data" type="checkbox" id="rest_personal_data" name="rest_personal_data" checked required>
				<label class="checkbox-input__label" for="rest_personal_data">
					Я даю <a href="/help/dogovor-oferty/" target="_blank">согласие на обработку своих персональных данных</a> и соглашаюсь с правилами безопасности компании *
				</label>
			</div>
			<div class="two-sides">
				<div>
					<input type="submit" value="Оставить заявку" name="" id="" class="butn btn-base">
				</div>
				<div>
					<span class="base-text comments__required">* Обязательно для заполнения</span>
				</div>
			</div>
		</form>
	</div>
	<script>
		$(function(){
			$('.js-files').attr('accept', 'application/pdf, application/msword');
		});
	</script>
<? } ?>