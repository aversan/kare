<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?><?/*
<div class="subscribe-form"  id="subscribe-form">
<?
$frame = $this->createFrame("subscribe-form", false)->begin();
?>
	<form action="<?=$arResult["FORM_ACTION"]?>">
111
	<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
		<label for="sf_RUB_ID_<?=$itemValue["ID"]?>">
			<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?> /> <?=$itemValue["NAME"]?>
		</label><br />
	<?endforeach;?>

		<table border="0" cellspacing="0" cellpadding="2" align="center">
			<tr>
				<td><input type="text" name="sf_EMAIL" size="20" value="<?=$arResult["EMAIL"]?>" title="<?=GetMessage("subscr_form_email_title")?>" /></td>
			</tr>
			<tr>
				<td align="right"><input type="submit" name="OK" value="<?=GetMessage("subscr_form_button")?>" /></td>
			</tr>
		</table>
	</form>
<?
$frame->beginStub();
?>
	<form action="<?=$arResult["FORM_ACTION"]?>">
222
		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<label for="sf_RUB_ID_<?=$itemValue["ID"]?>">
				<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>" /> <?=$itemValue["NAME"]?>
			</label><br />
		<?endforeach;?>

		<table border="0" cellspacing="0" cellpadding="2" align="center">
			<tr>
				<td><input type="text" name="sf_EMAIL" size="20" value="" title="<?=GetMessage("subscr_form_email_title")?>" /></td>
			</tr>
			<tr>
				<td align="right"><input type="submit" name="OK" value="<?=GetMessage("subscr_form_button")?>" /></td>
			</tr>
		</table>
	</form>
<?
$frame->end();
?>
</div>
*/?>

<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="subscribe"  id="subscribe_form">
	<div class="subscribe__wrapper">
		<div class="subscribe__icon"></div>
		<div class="subscribe__text">
			<?$APPLICATION->IncludeFile(SITE_DIR."include/subscribe_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("SUBSCRIBE_TEXT"),));?>
		</div>
		<?if ($_REQUEST["bitrix_include_areas"]=="Y" || $_SESSION["SESS_INCLUDE_AREAS"]):?>
			<div class="popup_texts">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/succes_subscribe_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("SUCCES_SUBSCRIBE_TEXT"),));?>
				<?$APPLICATION->IncludeFile(SITE_DIR."include/already_subscribe_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("ALREADY_SUBSCRIBED_TEXT"),));?>
			</div>
		<?endif;?>
		<div class="subscribe__inner">
				<form class="subscribe__form" action="<?=$arResult["FORM_ACTION"];?>">
					<div class="subscribe__input-wrap">
						<input class="subscribe__input" type="email" name="sf_EMAIL" required size="20" value="<?=$arResult["EMAIL"]?>" placeholder="<?=GetMessage("subscr_form_email_title")?>" />
					</div>
					<button class="btn btn_subscribe js-subscribe-form" type="submit"><?=GetMessage("subscr_form_button");?></button>
					<div class="checkbox-input checkbox-input-br">
						<input class="checkbox-input__input" type="checkbox" id="sub_personal_data" name="sub_personal_data" checked required>
						<label class="checkbox-input__label" for="sub_personal_data">
							Я даю <a href="/help/dogovor-oferty/" target="_blank">согласие на обработку своих персональных данных</a> и соглашаюсь с правилами безопасности компании *
						</label>
					</div>
				</form>
		</div>
	</div>
</div>

