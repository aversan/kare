<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>

	<form action="<?=$arResult["FORM_ACTION"]?>" class="search1" id="search-header">
		<div class="search-input-wrap js-popup js-closed js-search">
			<input id="<?=$arParams["INPUT_ID"]?>" class="header-search__field" type="text" name="q" placeholder="<?=GetMessage("placeholder")?>" autocomplete="off" />
			
			<button class="search-input-wrap__btn js-close-btn" type="button">
				<div class="icon-wrapper active">
					<?= \Local\Svg::get('close', 'icon icon_close icon_close-search') ;?>
				</div>
			</button>
		</div>
		<?if ($arParams["USE_SEARCH_TITLE"]=="Y"):?>
			<div id="<?=$arParams["CONTAINER_ID"]?>"></div>
			<?$APPLICATION->IncludeComponent("bitrix:search.title", "catalog", array(
				"NUM_CATEGORIES" => "1",
				"TOP_COUNT" => "5",
				"ORDER" => "date",
				"USE_LANGUAGE_GUESS" => "Y",
				"CHECK_DATES" => "Y",
				"SHOW_OTHERS" => "N",
				"PAGE" => $arParams["PAGE"],
				"CATEGORY_0_TITLE" => GetMessage("CATEGORY_PRODUÑTCS_SEARCH_NAME"),
				"CATEGORY_0" => array(
					0 => "iblock_aspro_kshop_catalog",
				),
				"CATEGORY_0_iblock_aspro_kshop_catalog" => array(
					0 => "11",
				),
				"SHOW_INPUT" => "N",
				"INPUT_ID" => $arParams["INPUT_ID"],
				"CONTAINER_ID" => $arParams["CONTAINER_ID"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"PRICE_VAT_INCLUDE" => "Y",
				"SHOW_ANOUNCE" => "N",
				"PREVIEW_TRUNCATE_LEN" => "50",
				"SHOW_PREVIEW" => "Y",
				"PREVIEW_WIDTH" => "38",
				"PREVIEW_HEIGHT" => "38",
				"CONVERT_CURRENCY" => "N"
				),
				false,
				array(
				"ACTIVE_COMPONENT" => "Y"
				)
			);?>
		<?endif;?>
	</form>
