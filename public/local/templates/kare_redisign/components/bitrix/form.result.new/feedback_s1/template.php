<article class="feedback">
	<div class="feedback__wrapper">
		<h2 class="feedback__title">Обратная связь</h2>
		<div class="js-feedback-form-msg feedback-form-msg" id="js-feedback-form-msg">
			<? if ($_REQUEST['formresult'] == 'addok') { ?>
				<h3>Спасибо! Мы свяжемся с Вами в течение рабочего дня.</h3>
			<? } else { ?>
				<? if ($arResult['isFormErrors'] == "Y") { ?>
					<?= $arResult['FORM_ERRORS_TEXT'] ?>
				<? } ?>

				<form class="feedback-form js-feedback-form" name="<?= $arResult["arForm"]["SID"] ?>" data-ajax="<?= $arParams['ACTION_URL'] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
					<?= bitrix_sessid_post(); ?>
					<input type="hidden" name="WEB_FORM_ID" value="<?= $arResult["arForm"]["ID"] ?>">
					<input type="hidden" name="WED_FORM_SID" value="<?= $arResult["arForm"]["SID"] ?>">

					<? foreach ($arResult["QUESTIONS"] as $key => $arQuestion) { ?>
						<div class="feedback-form__input-wrap">
							<?= $arQuestion["HTML_CODE"] ?>
						</div>
					<? } ?>
					<div class="checkbox-input checkbox-input-br">
						<input class="checkbox-input__input js-personal-data" type="checkbox" id="dis_personal_data" name="dis_personal_data" checked required>
						<label class="checkbox-input__label" for="dis_personal_data">
							Я даю <a href="/help/dogovor-oferty/" target="_blank">согласие на обработку своих персональных данных</a> и соглашаюсь с правилами безопасности компании *
						</label>
					</div>
					<input class="feedback-form__btn js-feedback-submit" type="submit" name="web_form_submit" value="<?= $arResult["arForm"]["BUTTON"] ?>">
					<span class="feedback-form__note">&#042;Обязательно для заполнения</span>
				</form>
			<? } ?>
		</div>
	</div>
</article>