<div class="top-callback__wrapper">
	<? if ($_REQUEST['formresult'] == 'addok') { ?>
		<h3>Благодарим за обращение. Менеджер позвонит Вам в ближайшее время.</h3>
	<? } else { ?>
		<? if ($arResult['isFormErrors'] == "Y") { ?>
			<?=$arResult['FORM_ERRORS_TEXT']?>
		<? } ?>

		<form class="top-callback-form js-form js-call-me-back" name="<?=$arResult["arForm"]["SID"]?>" data-ajax="/ajax/forms.php" action="<?=POST_FORM_ACTION_URI?>" method="POST" enctype="multipart/form-data" class="form form--top-callback">
			<?=bitrix_sessid_post(); ?>
			<input type="hidden" name="WEB_FORM_ID" value="<?=$arResult["arForm"]["ID"]?>">
			<input type="hidden" name="WED_FORM_SID" value="<?=$arResult["arForm"]["SID"]?>">
			<input type="hidden" name="web_form_submit" value="Y">
			<table width="100%">
				<tr>
					<td class="top-callback-form__td top-callback-form__td_top" colspan="2">
						<span class="top-callback-form__title"><?=$arResult["arForm"]["NAME"];?></span>
						<button class="btn btn_close icon-wrapper js-close-btn" type="button">
							<?= \Local\Svg::get('close', 'icon icon_close icon_darkgrey') ;?>
						</button>
					</td>
				</tr>
				<? foreach ($arResult["QUESTIONS"] as $key => $arQuestion) { ?>
					<tr>
						<td class="top-callback-form__td" colspan="2">
							<div class="top-callback-form__input-wrap">
								<?=$arQuestion["HTML_CODE"]?>
							</div>
						</td>
					</tr>
				<? } ?>

				<tr>
					<td class="top-callback-form__td top-callback-form__td_required" colspan="2"><span>&#42;</span>Обязательно для заполнения</td>
				</tr>
				<tr>
					<td class="top-callback-form__td top-callback-form__td--check" colspan="2">
						<div class="checkbox-input checkbox-input-br">
							<input class="checkbox-input__input js-personal-data" type="checkbox" id="recall_personal_data" name="recall_personal_data" checked required>
							<label class="checkbox-input__label" for="recall_personal_data">
								Я даю <a href="/help/dogovor-oferty/" target="_blank">согласие на обработку своих персональных данных</a> *
							</label>
						</div>
					</td>
				</tr>
				<tr>
					<td class="top-callback-form__td top-callback-form__td_send" colspan="2">
						<input class="top-callback-form__btn btn_popup fran-f__btn js-submit" type="submit" name="web_form_submit" value="<?=$arResult["arForm"]["BUTTON"]?>">
					</td>
				</tr>
				<tr>
					<td class="top-callback-form__td top-callback-form__td_bottom top-callback-form__td_free">Бесплатно<br> по России</td>
					<td class="top-callback-form__td top-callback-form__td_bottom top-callback-form__td_phone">
						<? $APPLICATION->IncludeComponent("bitrix:main.include","",Array(
								"AREA_FILE_SHOW" => "file",
								'PATH' => SITE_TEMPLATE_PATH . '/include_areas/phone_number_inc.php',
								"AREA_FILE_SUFFIX" => "inc",
							)
						);?>
					</td>
				</tr>
			</table>
			<?=$arResult["FORM_FOOTER"]?>
	<? } ?>
</div>