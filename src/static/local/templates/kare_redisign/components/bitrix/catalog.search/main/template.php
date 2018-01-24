<?php
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$show = $arParams["PAGE_ELEMENT_COUNT"];
if (array_key_exists("show", $_REQUEST)) {
	if (intval($_REQUEST["show"]) && in_array(intval($_REQUEST["show"]), array(18, 36, 72))) {
		$show = intval($_REQUEST["show"]);
		$_SESSION["show"] = $show;
	} elseif ($_SESSION["show"]) {
		$show = intval($_SESSION["show"]);
	}
}
?>
<div class="search-results">
	<h1 class="main-catalog__title">Поиск</h1>
	<?
	$arElements = $APPLICATION->IncludeComponent(
		"bitrix:search.page",
		"",
		Array(
			"RESTART" => $arParams["RESTART"],
			"NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
			"USE_LANGUAGE_GUESS" => $arParams["USE_LANGUAGE_GUESS"],
			"CHECK_DATES" => $arParams["CHECK_DATES"],
			"arrFILTER" => array("iblock_".$arParams["IBLOCK_TYPE"]),
			"arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"]),
			"USE_TITLE_RANK" => "N",
			"DEFAULT_SORT" => "rank",
			"FILTER_NAME" => "",
			"SHOW_WHERE" => "N",
			"arrWHERE" => array(),
			"SHOW_WHEN" => "N",
			"PAGE_RESULT_COUNT" => 200,
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_TITLE" => "",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "N",
		),
		$component
	);
	?>
	<div class="sort_filter_wrapper cf">
		<div class="sort_header">
			<!--noindex-->
			<?
			$arDisplays = array("block", "list", "table");
			if (array_key_exists("display", $_REQUEST) || (array_key_exists("display", $_SESSION)) || $arParams["DEFAULT_LIST_TEMPLATE"]) {
				if ($_REQUEST["display"] && (in_array(trim($_REQUEST["display"]), $arDisplays))) {
					$display = trim($_REQUEST["display"]);
					$_SESSION["display"] = trim($_REQUEST["display"]);
				} elseif ($_SESSION["display"] && (in_array(trim($_SESSION["display"]), $arDisplays))) {
					$display = $_SESSION["display"];
				} elseif ($arSection["DISPLAY"]) {
					$display = $arSection["DISPLAY"];
				} else {
					$display = $arParams["DEFAULT_LIST_TEMPLATE"];
				}
			} else {
				$display = "block";
			}
			$template = "catalog_" . $display;
			?>
			<button class="filter_open_btn icon-wrapper js-open-filter" type="button">
				<?= \Local\Svg::get('filter', 'icon icon_filter') ;?>
				<?= GetMessage("CATALOG_SMART_FILTER_TITLE")?>
			</button>
			<div class="sort_filter">
		<span class="sort_main">
			<input class="sort_main_checkbox js-price-sort" id="sort_by_price" type="checkbox" name="sortPrice" value="" <?=($_REQUEST['order'] == 'desc') ? 'checked' : ''?> <?=($display == 'list') ? 'disabled' : ''?>>
			<label class="sort_btn" for="sort_by_price">По цене</label>
		</span>
			</div>
			<!-- <div class="sort_display">
				<a rel="nofollow" href="<?#= $APPLICATION->GetCurPageParam('display=block' , array('display', 'store', 'order')) #?>" class="sort_btn sort_btn_base <?#= ($display == 'block' ? 'current' : '') #?>" title="Плиткой">
				</a>
				<a href="<?#= $APPLICATION->GetCurPageParam('display=list' , array('display', 'store', 'order')) #?>" class="sort_btn sort_btn_list <?#= ($display == 'list' ? 'current' : '') #?>">
					<i title="Списком"></i>
					А-Я
				</a>
			</div> -->
			<div class="additional_sort">
				<div class="available_filter">
					<input class="js-store-filter" id="available-on-store" type="checkbox" name="store" value="" <?=($_REQUEST['store'] == 'Y') ? 'checked' : ''?>>
					<label for="available-on-store" class="additional_sort_label">Наличие в России</label>
					<?
					if ($_REQUEST['store'] == 'Y') {
						global $KSHOP_SMART_FILTER;
						$KSHOP_SMART_FILTER['PROPERTY_AVAILABLE_VALUE'] = 'Y';
					}
					?>
				</div>
			</div>
			<div class="sort_show-number">
				<? if ($arParams["DISPLAY_SHOW_NUMBER"] != "N"): ?>
					<div class="pagination__show-number cf">
						<span class="pagination__show-title">Показывать по:</span>
						<span class="pagination__show-list">
					<? for ($i = 18; $i <= 72; $i *= 2) { ?>
						<a <? if ($i == $show): ?>class="current"<? endif; ?> href="<?= $APPLICATION->GetCurPageParam('show=' . $i, array('show', 'mode')) ?>"><?= $i ?></a>
					<? } ?>
				</span>
					</div>
				<? endif; ?>
			</div>
			<!--/noindex-->
		</div>
	</div>
	<?
	if (is_array($arElements) && !empty($arElements))
	{
		global $searchFilter;
		$searchFilter = array(
			"=ID" => $arElements,
		);
		if ($bAvailableShow)
			$searchFilter = array_merge($searchFilter, array("PROPERTY_227" => 794));

		$APPLICATION->IncludeComponent(
			"bitrix:catalog.section",
			"catalog_block",
			array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ELEMENT_SORT_FIELD" => 'CATALOG_PRICE_3',
				"ELEMENT_SORT_ORDER" => !empty($_REQUEST['order']) ? $_REQUEST['order'] :$arParams['ELEMENT_SORT_ORDER'],
				"PAGE_ELEMENT_COUNT" => $show,
				"LINE_ELEMENT_COUNT" => 5,
				"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
				"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
				"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
				"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
				"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
				"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
				"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
				"SECTION_URL" => $arParams["SECTION_URL"],
				"DETAIL_URL" => $arParams["DETAIL_URL"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
				"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
				"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
				"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
				"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"],
				"DISPLAY_TOP_PAGER" => 'Y',
				"DISPLAY_BOTTOM_PAGER" => 'Y',
				"PAGER_TITLE" => $arParams["PAGER_TITLE"],
				"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
				"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
				"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
				"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
				"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
				"FILTER_NAME" => "searchFilter",
				"SECTION_ID" => "",
				"SECTION_CODE" => "",
				"SECTION_USER_FIELDS" => array(),
				"INCLUDE_SUBSECTIONS" => "Y",
				"SHOW_ALL_WO_SECTION" => "Y",
				"META_KEYWORDS" => "",
				"META_DESCRIPTION" => "",
				"BROWSER_TITLE" => "",
				"ADD_SECTIONS_CHAIN" => "N",
				"SET_TITLE" => "N",
				"SET_STATUS_404" => "N",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "N",
				"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"],
				"DISPLAY_SHOW_NUMBER" => "N",
				"DEFAULT_COUNT" => "1",
				'SEF_FOLDER' => $arParams['SEF_FOLDER'],
				"DISPLAY_SHOW_NUMBER" => 18
			),
			$arResult["THEME_COMPONENT"]
		);
	}
	else
	{
		echo GetMessage("CT_BCSE_NOT_FOUND")."<br /><br />";
	}
	$strOrder = '?';
	if (!empty($_REQUEST['q'])) {
		foreach ($_REQUEST as $key => $value) {
			$strOrder .= $key . '=' . $value . '&';
		}
	}
	?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		//$('body').on('click', '.sort_filter span:not(.extended):not(.adaptive_filter)' , function() {
		$('.sort_filter span:not(.adaptive_filter)').click(function(e) {
			var _this = $(this);
			var _thisGet = _this.data('param');
			jsAjaxUtil.ShowLocalWaitWindow( 'id', 'catalog', true );
			if (!_this.hasClass('active')) {
				$.ajax({
					method: 'GET',
					url: _thisGet,
					dataType: 'html',
					success: function(html) {
						$('#catalog').html(html);
						jsAjaxUtil.CloseLocalWaitWindow( 'id', 'catalog' );
						window.History.pushState(null, document.title, _thisGet);
						equalizeHeight($('.block_items .prod_item'), '.item-equalize');
						equalizeHeight($('.block_items .prod_item:not(.big_item)'), '.name');
						equalizeHeight($('.block_items .prod_item:not(.big_item)'), '.price_bl');
					}
				});
			}
			e.stopPropagation();
		});
		$(".sort_filter_wrapper .extended_sort .extended").on("click", function(e)
		{
			console.log("click");
			console.log($(this).find("input").length);
			if ($(this).find("input").attr("checked")=="checked") {
				$(this).find("input").removeAttr("checked");
			} else {
				$(this).find("input").attr("checked", "checked");
			}
		});

		$(".search-page form").after($(".sort_filter_wrapper"));
		$(".sort_filter_wrapper").show();
	});
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('change', '.js-price-sort', function (e) {
            var priceOrder = 'asc';
            if (this.checked) {
                priceOrder = 'desc';
            }
            window.location = '<?=$strOrder?>' + 'order=' + priceOrder;
        });
        $('body').on('change', '.js-store-filter', function (e) {
            var storeFilter = 'N';
            if (this.checked) {
                storeFilter = 'Y';
            }
            window.location = '<?=$strOrder?>' + 'store=' + storeFilter;
        });
    });
    $(".js-open-filter").click(function () {
        $(".js-mobile-filter").toggleClass("opened");
    });
</script>