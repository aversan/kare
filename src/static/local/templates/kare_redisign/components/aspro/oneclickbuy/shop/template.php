<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="popup-wrap">
	<div class="popup-title"><?=GetMessage('FORM_HEADER_CAPTION')?></div>

	<a class="jqmClose popup-close">
		<?= \Local\Svg::get('close', 'icon icon_close icon_gray') ;?>
	</a>
	<form method="post" id="one_click_buy_form" action="<?=$arResult['SCRIPT_PATH']?>/script.php">
		<div class="input-wrap input-wrap_popup">
			<input class="form-field" type="text" name="USER_NAME" value="" id="ONE_CLICK_USER_NAME" placeholder="Ваше имя*" required>
		</div>
		<div class="input-wrap input-wrap_popup">
			<input class="form-field" type="tel" name="USER_PHONE" value="" id="ONE_CLICK_USER_PHONE" placeholder="Контактный телефон*" required>
		</div>
		<div class="input-wrap input-wrap_popup">
			<input class="form-field" type="text" name="USER_COMMENT" value="" id="ONE_CLICK_COMMENT" placeholder="Комментарий к заказу">
		</div>
		<?if($arParams['USE_SKU']=="Y"):?>
			<input type="hidden" name="IBLOCK_ID" value="<?=$arParams['IBLOCK_ID']?>" />
			<input type="hidden" name="USE_SKU" value="Y" />
			<input type="hidden" name="SKU_CODES" value="<?=$arResult['SKU_PROPERTIES_STRING']?>" />
			<select name="ELEMENT_ID">
				<?foreach($arResult['OFFERS'] as $id => $offer_data):?>
					<option value="<?=$id?>"><?=$offer_data?></option>
				<?endforeach;?>
			</select>
		<?endif;?>
		<div class="but-r cf">
			<!--noindex-->
				<div class="form-note">*Обязательно для заполнения</div>
				<button class="btn form-btn" type="submit" id="one_click_buy_form_button" name="one_click_buy_form_button" value="<?=GetMessage('ORDER_BUTTON_CAPTION')?>" <?if ($arParams["BUY_ALL_BASKET"]=="Y"):?>onClick="ga('send', 'event', 'Button4', 'Buttonclick7');"<?else:?>onClick="ga('send', 'event', 'Button4', 'Buttonclick5');"<?endif;?>><span><?=GetMessage("ORDER_BUTTON_CAPTION")?></span></button>
			<!--/noindex-->
		</div>
		<?if($arParams['USE_SKU']!="Y"):?>
			<input type="hidden" name="ELEMENT_ID" value="<?=$arParams['ELEMENT_ID']?>" />
		<?endif;?>
		<?if($arParams['BUY_ALL_BASKET']=="Y"):?>
			<input type="hidden" name="BUY_TYPE" value="ALL" />
		<?endif;?>
		<?if(intVal($arParams['ELEMENT_QUANTITY'])):?>
			<input type="hidden" name="ELEMENT_QUANTITY" value="<?=intVal($arParams['ELEMENT_QUANTITY']);?>" />
		<?endif;?>
		<input type="hidden" name="CURRENCY" value="<?=$arParams['DEFAULT_CURRENCY']?>" />
		<input type="hidden" name="PROPERTIES" value='<?=serialize($arParams['PROPERTIES'])?>' />
		<input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arParams['DEFAULT_PAYMENT']?>" />
		<input type="hidden" name="DELIVERY_ID" value="<?=$arParams['DEFAULT_DELIVERY']?>" />
		<input type="hidden" name="PERSON_TYPE_ID" value="<?=$arParams['DEFAULT_PERSON_TYPE']?>" />
		<input type="hidden" name="FIO" value="asdf asdf asdf" />
		<?=bitrix_sessid_post()?>
	</form>
	<div class="one_click_buy_result" id="one_click_buy_result">
		<div class="one_click_buy_result_success"><?=GetMessage('ORDER_SUCCESS')?></div>
		<div class="one_click_buy_result_fail"><?=GetMessage('ORDER_ERROR')?></div>
		<div class="one_click_buy_result_text"><?=GetMessage('ORDER_SUCCESS_TEXT')?></div>
	</div>
</div>
<script type="text/javascript">
	$('#one_click_buy_form').validate({
		rules: {
			USER_NAME: "required",
			USER_PHONE: "required"
		},

		messages: {
			USER_NAME: "Введите Ваше имя",
			USER_PHONE: {
				required: "Введите Ваш контактный телефон",
				USER_PHONE: "Пожалуйста, введите номер полностью",
			}
		},

		focusInvalid: false,

		onfocusin: function(field) {
			var $currentField = $(field);

			$currentField.addClass('is-full');
		},
		onfocusout: function(field) {
			var $currentField = $(field);

			if ($currentField.val() === '') {
				$currentField.removeClass('is-full');
			}
		},
		invalidHandler: function(evt, validator) {
			$.each(validator.errorList, function(index, item) {
				var $field = $(item.element);

				if ($field.val() !== '') {
					$field.addClass('is-full');
				}
			});
		}
	});
	$('.popup').jqmAddClose('.jqmClose');

	$('#ONE_CLICK_USER_PHONE').inputmask({"mask": "+7 (999) 999-9999",showMaskOnHover: false,removeMaskOnSubmit: true,autoUnmask: true});
</script>