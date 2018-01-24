<? /**
 * @var array $arResult
 * @var array $arParams
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
?>
<input type="hidden" name="PROFILE_ID" id="ID_PROFILE_ID" value="<?= reset($arResult["ORDER_PROP"]["USER_PROFILES"])["ID"] ?>"/>

<div class="bx_section bordered-block bordered-block--ind">
	<div class="bordered-block__title bordered-block__title--person">
		Контактная информация
	</div>
	<div id="sale_order_props" class="bordered-block__form">
		<?
		PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"]['Личные данные'], $arParams["TEMPLATE_LOCATION"], $arResult['ERROR']);
		?>
	</div>
</div>
<div class="bordered-block bordered-block--ind">
	<div class="bordered-block__title bordered-block__title--delivery">Адрес доставки</div>
	<div class="bordered-block__form">

		<? if (!CSaleLocation::isLocationProEnabled()): ?>
			<div style="display:none;">

				<? $APPLICATION->IncludeComponent(
					"bitrix:sale.ajax.locations",
					$arParams["TEMPLATE_LOCATION"],
					array(
						"AJAX_CALL" => "N",
						"COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
						"REGION_INPUT_NAME" => "REGION_tmp",
						"CITY_INPUT_NAME" => "tmp",
						"CITY_OUT_LOCATION" => "Y",
						"LOCATION_VALUE" => "",
						"ONCITYCHANGE" => "submitForm()",
					),
					null,
					array('HIDE_ICONS' => 'Y')
				); ?>

			</div>
		<? endif ?>
		<? PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"]['Данные для доставки'], $arParams["TEMPLATE_LOCATION"], $arResult['ERROR']); ?>
	</div>
