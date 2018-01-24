<? if ($arResult['isFormErrors'] == "Y") { ?>
	<?=$arResult['FORM_ERRORS_TEXT']?>
<? } ?>
<div id="apply" class="custom-modal">
	<? if ($_REQUEST['formresult'] == 'addok') { ?>
		<h3 class="js-feedback-form-msg">Спасибо! Мы свяжемся с Вами в течение рабочего дня.</h3>
	<? } else { ?>
	<? if ($arResult['isFormErrors'] == "Y") { ?>
		<?= $arResult['FORM_ERRORS_TEXT'] ?>
	<? } ?>
	<form name="<?=$arResult["arForm"]["SID"]?>" data-ajax="/ajax/ajax-forms.php" action="<?=POST_FORM_ACTION_URI?>" method="POST" enctype="multipart/form-data" class="form form--top-callback js-job-form">
		<?=bitrix_sessid_post(); ?>
		<input type="hidden" name="WEB_FORM_ID" value="<?=$arResult["arForm"]["ID"]?>">
		<input type="hidden" name="WED_FORM_SID" value="<?=$arResult["arForm"]["SID"]?>">
		<?=$arResult['QUESTIONS']['JOB']['HTML_CODE'];?>
		<?=$arResult['QUESTIONS']['JOB_ID']['HTML_CODE'];?>
		<div class="custom-modal__title"><?=$arResult["arForm"]["NAME"];?></div>
		<? foreach ($arResult['QUESTIONS'] as $key => $question) {
			if (in_array($key, ['FILE', 'JOB', 'JOB_ID'])) {
				continue;
			} ?>
			<div class="custom-input-block">
				<?=$question['HTML_CODE'];?>
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
				<button type="submit" class="butn btn-base butn--marg" name="web_form_submit" value="Y">Отправить</button>
			</div>
			<div>
				<label class="file-label" for="form_field_<?=$arResult['QUESTIONS']['FILE']['STRUCTURE'][0]['ID']?>">
					<svg width="16" height="14" id="modal" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 14.6285"><title>vacancy</title><path d="M5208.41,1233.6832l-0.075-.0741a4.2458,4.2458,0,0,0-5.9478.05l-7.5756,7.5777a4.041,4.041,0,0,0-1.1855,2.54,3.2726,3.2726,0,0,0,3.2728,3.27h0a3.2533,3.2533,0,0,0,2.3143-.9594l6.54-6.54a2.3012,2.3012,0,0,0,0-3.2557,2.3025,2.3025,0,0,0-3.2558,0l-5.0087,5.0089a0.6856,0.6856,0,1,0,.9649.9741l0,0,5.0134-5.0143a0.9308,0.9308,0,1,1,1.3158,1.3169l-6.54,6.54a1.8907,1.8907,0,0,1-1.3446.5576h0a1.901,1.901,0,0,1-1.9011-1.8987,2.7533,2.7533,0,0,1,.7841-1.57l7.5752-7.5781a2.8725,2.8725,0,0,1,4.031-.0272l0.0728,0.0732a2.8751,2.8751,0,0,1-.0451,4.0143l-6.27,6.2705a0.6854,0.6854,0,1,0,.9659.9728l0,0,6.2737-6.2741A4.2473,4.2473,0,0,0,5208.41,1233.6832Z" transform="translate(-5193.6261 -1232.4181)" style="fill:#ff6174"/></svg>
					<span> Прикрепить резюме<br>(PDF или DOC)</span>
				</label>
				<?=$arResult['QUESTIONS']['FILE']['HTML_CODE'];?>
				<input type="text" name="form_field_helper" id="form_field_helper" class="error">
			</div>
		</div>
		<div class="base-text">* Обязательно для заполнения</div>
	</form>
	<? } ?>
</div>
<script>
	$(function(){
		$('.js-files').attr('accept', 'application/pdf, application/msword');
	});
</script>