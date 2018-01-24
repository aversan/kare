<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$style = (is_array($arResult["ORDER_PROP"]["RELATED"]) && count($arResult["ORDER_PROP"]["RELATED"])) ? "" : "display:none";
?><div class="bordered-block__form">
	<div class="bx_section custom-input-block custom-input-block--addr custom-input-block--ind" style="<?=$style?>">
		<?
		foreach ($arResult["ORDER_PROP"]["RELATED"]['ADDRESS'] as $property) {
			if (!empty($arResult['ERROR']) && array_key_exists($property['NAME'], $arResult['ERROR'])) {
				ShowError($arResult['ERROR'][$property['NAME']]);
			}
		} ?>
		<?=PrintPropsForm($arResult["ORDER_PROP"]["RELATED"]['ADDRESS'], $arParams["TEMPLATE_LOCATION"])?>
	</div>
	<p class="gray-text">Стоимость и срок доставки сообщим дополнительно после обработки заказа.</p>
</div>


</div>