
<?
/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 */


if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

if( count( $arResult["ITEMS"] ) >= 1 ){?>
	<?
	$itemsCount = $arParams['PAGE_ELEMENT_COUNT'];
	if (!empty($_REQUEST['show'])) {
		$itemsCount = $_REQUEST['show'];
	} elseif (!empty($_SESSION['show'])) {
		$itemsCount = $_SESSION['show'];
	}
	?>
	<div class="js-pagenav" data-mobile-width="768" data-mobile-items-count="3" data-desktop-items-count="<?=$itemsCount?>">
		<ul class="catalog-list js-pagenav-list">
			<?foreach($arResult["ITEMS"] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
				?>
				<?
				if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"]))
				{ $arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext(); }

				?>
					<li class="catalog-list__item catalog-list__item_catalog-page js-catalog-item">
						<article class="catalog-item catalog-item_base <?=(($_GET['q'])) ? 's' : ''?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<div class="catalog-item__inner catalog-item__inner_higher cf">
								<div class="catalog-item__icon">
									<a class="catalog-item__link" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
										<?if ($arItem['PICTURE']['SMALL']['src']):?>
											<img src="<?=$arItem['PICTURE']['SMALL']['src']?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
										<?else:?>
											<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_big.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
										<?endif;?>
									</a>
								</div>
								<div class="ribbons">
										<?if (is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
											<?if( in_array("NEW", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribbon ribbon_new">new</span><?endif;?>
											<?if( in_array("HIT", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ):?><span class="ribbon ribbon_hit">хит</span><?endif;?>
											<?if( in_array("RECOMMEND", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribbon ribbon_recomend"></span><?endif;?>
											<?if( in_array("STOCK", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribbon ribbon_action"></span><?endif;?>
										<?endif;?>
										<?if ($arItem['DISCOUNT_ICO'] == 'Y'):?>
											<span class="ribbon ribbon_discount">-20%</span>
										<?endif;?>
								</div>
								<div class="catalog-item__info">
									<div class="catalog-item__top-info">
										<span class="catalog-item__article">Артикул: <span><?=$arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span></span>
										<a class="catalog-item__name" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
									</div>
									<div class="catalog-item__bottom-info">
										<div class="catalog-item__cost">
											<div class="catalog-item__price">
												<?if( $arItem["OFFERS"]){?>
													<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$arItem["MIN_PRODUCT_OFFER_PRICE_PRINT"]?></div>
												<?} elseif ( $arItem["PRICES"] ){?>
												<? $arCountPricesCanAccess = 0; foreach( $arItem["PRICES"] as $key => $arPrice ) { if($arPrice["CAN_ACCESS"]){$arCountPricesCanAccess++;} } ?>
													<?foreach( $arItem["PRICES"] as $key => $arPrice ){?>
														<?if( $arPrice["CAN_ACCESS"] ){?>
															<?$price = CPrice::GetByID($arPrice["ID"]); ?>
															<?if($arCountPricesCanAccess>1):?>
                                                                <?/*?><div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div><?*/?>
															<?if($key == "Скидка на артикул"){?>
																<?$discount = true;?>
																<div class="price discount_yes"><?=$arPrice["PRINT_VALUE"];?></div>
																<div class="price-discount"><strike style="font-size: 20px;"><?=$arItem["PRICES"]["Каре розничная"]["PRINT_VALUE"]?></strike></div>
															<?}?>
                                                            <?else:?>
                                                                <div class="price"><?=$arPrice["PRINT_VALUE"]?></div>
                                                            <?endif;?>
														<?}?>
													<?}?>
												<?}?>

											</div>
										</div>
										<div class="catalog-buttons cf js-catalog-buttons">
											<!--noindex-->
											<?if(!$arItem["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
												<div class="catalog-buttons__item">
													<a title="Добавить в WishList" class="basket-button js-to-basket js-to-wish-btn <?=$arItem['DELAY'] ? 'added' : ''?>" rel="nofollow" href="/ajax/item.php" data-item="<?=$arItem["ID"]?>" data-type="wish">
														<div class="icon-wrapper icon-wrapper_like">
															<?= \Local\Svg::get('ic-like', 'icon icon_like icon_white') ;?>
														</div>
													</a>
												</div>
											<?endif;?>
											<div class="catalog-buttons__item">
												<a title="Перейти в корзину"
												   class="basket-button basket-button_in-cart js-in-basket-btn <?=$arItem['ADDED'] ? 'added' : 'hidden' ?>"
												   rel="nofollow" href="/basket/" data-item="<?=$arItem['ID']?>">В корзине
													<div class="icon-wrapper icon-wrapper_like">
														<?= \Local\Svg::get('ic-cart', 'icon icon_basket icon_white'); ?>
													</div>
												</a>
												<a title="Добавить в корзину"
												   class="basket-button basket-button_to-cart js-to-basket js-to-basket-btn <?=$arItem['ADDED'] ? 'hidden' : '' ?>"
												   rel="nofollow" href="/ajax/item.php"
												   onclick="ga('send', 'event', 'Button1', 'Buttonclick12');" data-type="basket" data-item="<?=$arItem["ID"]?>">В корзину
													<div class="icon-wrapper icon-wrapper_like">
														<?= \Local\Svg::get('ic-cart', 'icon icon_basket icon_white'); ?>
													</div>
												</a>
											</div>
											<!--/noindex-->
										</div>
                                        <?
                                        /*
                                         * получаем минимальный Возможный срок отгрузки среди всез складов.
                                         * хранится в св-ве товара.
                                         * */
                                        $storeComingDate = $arItem['PROPERTIES']["MIN_SHIPMENT_DATE"]["VALUE"];
                                        ?>
                                        <? if ( $storeComingDate ) { ?>
										    <div class="catalog-item__availability catalog-item__availability_available">Дата отгрузки: <?=$storeComingDate?></div>
                                        <? } ?>
									</div>
								</div>
							</div>
						</article>
					</li>
				<?endforeach;?>
		</ul>
		<?if(($arResult['NEXT_PAGE_NUM'] + 1) <= $arResult['PAGES_COUNT']):?>
			<button class="catalog-list__more btn btn_text js-pagenav-more-btn" data-url="<?=$arResult['AJAX_URI']?>" data-ajax-data="PAGEN_<?=$arResult['PAGE_NUMBER']?>=<?=$arResult['NEXT_PAGE_NUM'] + 1?>&AJAX=Y" type="button">Показать еще</button>
		<?endif;?>
	</div>
	<?if( $arParams["DISPLAY_BOTTOM_PAGER"]=="Y" || $arParams["DISPLAY_SHOW_NUMBER"]!="N"):?>
		<section class="pagination cf">
			<div class="pagination__pages">
				<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
				<?
					$show=$arParams["PAGE_ELEMENT_COUNT"];
					if (array_key_exists("show", $_REQUEST))
					{
						if ( intVal($_REQUEST["show"]) && in_array(intVal($_REQUEST["show"]), array(18, 36, 72)) ) {$show=intVal($_REQUEST["show"]); $_SESSION["show"] = $show;}
						elseif ($_SESSION["show"]) {$show=intVal($_SESSION["show"]);}
					}
				?>
			</div>
			<?if ($arParams["DISPLAY_SHOW_NUMBER"]!="N"):?>
				<div class="pagination__show-number cf">
					<span class="pagination__show-title"><?=GetMessage("CATALOG_DROP_TO")?></span>
					<span class="pagination__show-list">
						<?for( $i = 18; $i <= 72; $i*=2 ){?>
							<a <?if ($i == $show):?>class="current"<?endif;?> href="<?=$APPLICATION->GetCurPageParam('show='.$i, array('show', 'mode'))?>"><?=$i?></a>
						<?}?>
					</span>
				</div>
			<?endif;?>
		</section>
	<?endif;?>
<?}else{?>
	<div class="no_products"><?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?></div>
<?}?>

<?if (!$_REQUEST["PAGEN_1"]):?>
	<?if ($arResult["~DESCRIPTION"]):?>
		<div class="main-catalog__description">
			<?=$arResult["~DESCRIPTION"]?>
		</div>
	<?else:?>
		<?$arSection = CIBlockSection::GetList(array(), array( "IBLOCK_ID" => $arResult["IBLOCK_ID"], "ID" => $arResult["ID"] ), false, array( "ID", "UF_SECTION_DESCR"))->GetNext();?>
		<?if ($arSection["UF_SECTION_DESCR"]):?>
			<div class="main-catalog__description">
				<?=htmlspecialcharsBack($arSection["UF_SECTION_DESCR"]);?>
			</div>
		<?endif;?>
	<?endif;?>
<?endif;?>
		</div>
	</div>
