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
$class = randString();
?>

<?if($arResult["ITEMS"]):?>
		<div class="product-slider__control cf">
			<?if ($arParams['TITLE_SECTION']):?>
				<h2 class="product-slider__title"><?=$arParams['TITLE_SECTION']?></h2>
			<?endif;?>
			<div class="product-slider__buttons">
				<?if ($arParams['TITLE_SECTION'] && $arParams['LINK_ALL'] != 'N'):?>
					<a class="but but-transparent" href="<?=($arParams['SECTION_PAGE_FILTER'] ? $arParams['SECTION_PAGE_FILTER'] : $arParams['SECTION_PAGE_URL'])?>"><?=(!$arParams['LINK_ALL_NAME'] ? 'Все '.$arParams['TITLE_SECTION'] : $arParams['LINK_ALL_NAME'])?></a>
				<?endif;?>
				<div class="button-wrap">
					<button class="btn btn_prev js-catalog-prev" data-url="/" data-ajax-data="PAGEN_2=0&amp;AJAX=Y">Prev</button>
				</div>
				<div class="button-wrap">
					<button class="btn btn_next js-main-pager js-catalog-next" data-url="/" data-ajax-data="PAGEN_2=2&amp;AJAX=Y">Next</button>
				</div>
			</div>
		</div>
		<div class="catalog-list | items slider_items <?=$class;?> js-catalog-product-slider">
			<?$n=1;?>
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
				?>
					<div class="catalog-list__item catalog-list__item_slider">
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
														class="basket-button basket-button_in-cart js-in-basket-btn <?=$arItem['ADDED'] ? '' : 'hidden' ?>"
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
											<div class="catalog-item__availability catalog-item__availability_available"><?=($arItem['PROPERTIES']['AVAILABLE']['VALUE_XML_ID'] == 'Y') ? 'В наличии' : 'Срок отгрузки: ' . $arItem['PROPERTIES']['VOZMOZHNAYA_DATA_OTGRUZKI_IZ_MYUNKHENA']['VALUE'];?></div>
										</div>
									</div>
								</div>
						</article>
					</div>
				<?$n++;?>
			<?endforeach;?>
		</div>
<?endif;?>
