<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
/**
 * @var array $arParams
 * @var array $arResult
 * @var $component
 */
global $APPLICATION;
$arViewedID = array();
if(CModule::IncludeModule('sale')){
	$CSaleViewedProduct = new CSaleViewedProduct();
	$CSaleBasket = new CSaleBasket();
	$resViewed = $CSaleViewedProduct->GetList(array("DATE_VISIT" => "DESC"), array("LID" => SITE_ID, "FUSER_ID" => $CSaleBasket->GetBasketUserID()), false, false, array("ID", "PRODUCT_ID"));
	while($arItem = $resViewed->Fetch()){
		$arViewedID[] = $arItem["PRODUCT_ID"];
		if(count($arViewedID) >= 16){
			break;
		}
	}
}
?>
<section id="catalog" class="main-catalog">
	<?
	if( isset( $_SERVER["HTTP_X_REQUESTED_WITH"] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == "xmlhttprequest" )
		$GLOBALS["APPLICATION"]->RestartBuffer();
	?>
	<div class="main-catalog__wrapper">
		<h1 class="main-catalog__title">Каталог</h1>
		<div class="main-catalog__left">
				<?if('Y' == $arParams['USE_FILTER']):?>
				<?
				if(CModule::IncludeModule("iblock")){
					$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y");
					if(0 < intval($arResult["VARIABLES"]["SECTION_ID"])){
						$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
					}
					elseif('' != $arResult["VARIABLES"]["SECTION_CODE"]){
						$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
					}
					$obCache = new CPHPCache();
					if($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")){
						$arCurSection = $obCache->GetVars();
					}
					else{
						$arCurSection = array();
						$CIBlockSection = new CIBlockSection();
						$dbRes = $CIBlockSection->GetList(array(), $arFilter, false, array("ID"));
						if(defined("BX_COMP_MANAGED_CACHE")){
							global $CACHE_MANAGER;
							$CACHE_MANAGER->StartTagCache("/iblock/catalog");
							if($arCurSection = $dbRes->GetNext()){
								$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
							}
							$CACHE_MANAGER->EndTagCache();
						}
						else{
							if(!$arCurSection = $dbRes->GetNext()){
								$arCurSection = array();
							}
						}
						$obCache->EndDataCache($arCurSection);
					}
				}
				?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:catalog.smart.filter",
					"main",
					Array(
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						//"SECTION_ID" => $arCurSection['ID'],
						"FILTER_NAME" => $arParams["FILTER_NAME"],
						"PRICE_CODE" => $arParams["PRICE_CODE"],
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "36000000",
						"CACHE_NOTES" => "",
						"CACHE_GROUPS" => "N",
						"SAVE_IN_SESSION" => "N",
						"XML_EXPORT" => "Y",
						"SECTION_TITLE" => "NAME",
						"SECTION_DESCRIPTION" => "DESCRIPTION",
						"SHOW_HINTS" => $arParams["SHOW_HINTS"],
						"DISPLAY_ELEMENT_COUNT" => "Y"
					),
					$component);
				?>
			<?endif;?>
			</div>
			<?
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
			<div class="main-catalog__right">
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
<!-- 
						<div class="sort_display">
							<a rel="nofollow" href="<?#= $APPLICATION->GetCurPageParam('display=block' , array('display', 'store', 'order')) #?>" class="sort_btn sort_btn_base <?#= ($display == 'block' ? 'current' : '') #?>" title="Плиткой">
							</a>
							<a href="<?#= $APPLICATION->GetCurPageParam('display=list' , array('display', 'store', 'order')) #?>" class="sort_btn sort_btn_list <?#= ($display == 'list' ? 'current' : '') #?>">
								<i title="Списком"></i>
								А-Я
							</a>
						</div> -->

<!--						<div class="extended_sort"></div>-->

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
									<span class="pagination__show-title"><?= GetMessage("CATALOG_DROP_TO") ?></span>
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
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.section",
				$template,
				Array(
					"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
					"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
					"ELEMENT_SORT_FIELD" => $arParams['ELEMENT_SORT_FIELD'],
					"ELEMENT_SORT_ORDER" => !empty($_REQUEST['order']) ? $_REQUEST['order'] :$arParams['ELEMENT_SORT_ORDER'],
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
					"SHOW_ALL_WO_SECTION" => $arParams["SHOW_ALL_WO_SECTION"],
					"PAGE_ELEMENT_COUNT" => $show,
					"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
					"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
					"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
					"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
					"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
					"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
					"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
					"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
					"BASKET_URL" => $arParams["BASKET_URL"],
					"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
					"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
					"PRODUCT_QUANTITY_VARIABLE" => "quantity",
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
					"AJAX_MODE" => $arParams["AJAX_MODE"],
					"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
					"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
					"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
					"CACHE_TYPE" =>$arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
					"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
					"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
					"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
					"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
					"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
					"SET_TITLE" => $arParams["SET_TITLE"],
					"SET_STATUS_404" => $arParams["SET_STATUS_404"],
					"CACHE_FILTER" => $arParams["CACHE_FILTER"],
					"PRICE_CODE" => $arParams["PRICE_CODE"],
					"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
					"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
					"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
					"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
					"OFFERS_CART_PROPERTIES" => array(),
					"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
					"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
					"PAGER_TITLE" => $arParams["PAGER_TITLE"],
					"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
					"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
					"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
					"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
					"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
					"AJAX_OPTION_ADDITIONAL" => "",
					"ADD_CHAIN_ITEM" => "N",
					"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
					"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
					"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					"USE_STORE" => $arParams["USE_STORE"],
					"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
					"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
					"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
					"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
					"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
					"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
					"LIST_DISPLAY_POPUP_IMAGE" => $arParams["LIST_DISPLAY_POPUP_IMAGE"],
					"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
					"SHOW_HINTS" => $arParams["SHOW_HINTS"],
					"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
					"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
					"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
					'SEF_FOLDER' => $arParams['SEF_FOLDER'],
					'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES']
				), $component
			);?>
		</div>
	</div>
</section>

<?
if( isset( $_SERVER["HTTP_X_REQUESTED_WITH"] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == "xmlhttprequest" )
	die();
?>
<?if($arViewedID):?>
	<?global $viewedFilter;?>
	<?$viewedFilter = array('ID' => $arViewedID);?>
	<div class="product-slider js-product-gallery">
			<div class="product-slider__inner">
			<?$APPLICATION->IncludeComponent(
				"bitrix:catalog.top",
				"kare_slider",
				Array(
					"SHOW_ALL_WO_SECTION" => "Y",
					"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"SECTION_ID" => "",
					"SECTION_CODE" => "",
					"ELEMENT_SORT_FIELD" => "ID",//"FILTER",
					"ELEMENT_SORT_ORDER" => "ASC",
					"FILTER_NAME" => 'viewedFilter',
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGE_ELEMENT_COUNT" => 20,
					//"LINE_ELEMENT_COUNT" => 1,
					"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
					"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
					"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
					"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
					"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
					"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
					"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
					"BASKET_URL" => $arParams["BASKET_URL"],
					"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
					"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
					"PRODUCT_QUANTITY_VARIABLE" => "quantity",
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
					"AJAX_MODE" => $arParams["AJAX_MODE"],
					"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
					"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
					"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => "Y",
					"META_KEYWORDS" => "",
					"META_DESCRIPTION" => "",
					"BROWSER_TITLE" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
					"DISPLAY_COMPARE" => "N",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => $arParams["SET_STATUS_404"],
					"CACHE_FILTER" => "Y",
					"PRICE_CODE" => $arParams["PRICE_CODE"],
					"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
					"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
					"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
					"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
					"OFFERS_CART_PROPERTIES" => array(),
					"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
					"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
					"PAGER_TITLE" => $arParams["PAGER_TITLE"],
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => "",
					"PAGER_DESC_NUMBERING" => "",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
					"PAGER_SHOW_ALL" => "",
					"AJAX_OPTION_ADDITIONAL" => "",
					"ADD_CHAIN_ITEM" => "N",
					"SHOW_QUANTITY" => "N",
					"SHOW_QUANTITY_COUNT" => "",
					"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					"USE_STORE" => "",
					"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
					"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
					"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
					"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
					"DISPLAY_WISH_BUTTONS" => $arParams['DISPLAY_WISH_BUTTONS'],
					"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
					"LIST_DISPLAY_POPUP_IMAGE" => $arParams["LIST_DISPLAY_POPUP_IMAGE"],
					"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
					"SHOW_HINTS" => $arParams["SHOW_HINTS"],
					"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
					"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
					"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
					"VIEWED_COUNT" => "3",
					"VIEWED_NAME" => "Y",
					"VIEWED_IMAGE" => "Y",
					"VIEWED_PRICE" => "Y",
					"VIEWED_CURRENCY" => "default",
					"VIEWED_CANBUY" => "Y",
					'TITLE_SECTION' => 'Просмотренные товары',
					'LINK_ALL' => 'N',
					'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES']
				), $component
			);?>
		</div>
	</div>
<?endif;
//dump($GLOBALS["KSHOP_SMART_FILTER"],1,1);?>
<script type="text/javascript">
	$(document).ready(function () {
		$('body').on('change', '.js-price-sort', function (e) {
			var priceOrder = 'asc';
			if (this.checked) {
				priceOrder = 'desc';
			}

			var params = new URLSearchParams(window.location.search);
			if (params.has('order')) {
				params.set('order', priceOrder);
			} else {
				params.append('order', priceOrder);
			}
			window.location = '?' + params.toString();
		});
		$('body').on('change', '.js-store-filter', function (e) {
			var storeFilter = 'N';
			if (this.checked) {
				storeFilter = 'Y';
			}

			var params = new URLSearchParams(window.location.search);
			if (params.has('store')) {
				params.set('store', storeFilter);
			} else {
				params.append('store', storeFilter);
			}
			window.location = '?' + params.toString();
		});
	});
	$(".js-open-filter").click(function () {
		$(".js-mobile-filter").toggleClass("opened");
	});
</script>