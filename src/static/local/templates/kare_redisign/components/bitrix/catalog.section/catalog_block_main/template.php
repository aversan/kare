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

if (count($arResult["ITEMS"]) >= 1) {?>
	<?
	$hit = $arParams['HIT'];
	?>

	<section class="catalog <?=($hit) ? 'catalog_hit js-catalog-hit' : 'catalog_new js-catalog-new'?>">
		<div class="catalog__top-wrapper">
			<h2 class="catalog__title"><?=$arParams['TITLE_SECTION'];?></h2>
			<div class="catalog-navigation <?=($hit) ? 'js-catalog-hit-nav' : 'js-catalog-new-nav'?>">
				<div class="catalog-navigation__wrapper">
					<div class="button-wrap">
						<a class="btn btn_all" href="<?=$arResult['SHOW_ALL_URL']?>">Показать все</a>
					</div>
					<div class="button-wrap button-wrap--slider">
						<button class="btn btn_prev <?=($hit) ? 'js-slick-hit-prev' : 'js-slick-new-prev'?>">Prev</button>
					</div>
					<div  class="button-wrap button-wrap--slider">
						<button class="btn btn_next <?=($hit) ? 'js-slick-hit-next' : 'js-slick-new-next'?>">Next</button>
					</div>
				</div>
			</div>
		</div>
		<div class="catalog__wrapper <?=($hit) ? 'js-slick-hit js-catalog-list-hit' : 'js-slick-new js-catalog-list-new'?>">
			<?foreach($arResult["ITEMS"] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
				?>

				<div class="catalog-item-wrap js-product">
					<article class="catalog-item <?=(($_GET['q'])) ? 's' : ''?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<div class="catalog-item__inner cf">
							<div class="catalog-item-data">
								<div class="catalog-item-data__parameter">Коллекция - <span><?=$arItem["PROPERTIES"]["KOLLEKTSIYA"]["VALUE"]?></span></div>
								<div class="catalog-item-data__parameter">Артикул - <span><?=$arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span></div>
								<div class="catalog-item-data__parameter">Цвета - <span><?=$arItem["PROPERTIES"]["TSVETA"]["VALUE"]["0"]?></span></div>
							</div>
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
														<?if($arCountPricesCanAccess>1):?><div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div><?endif;?>
														<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]){?>
															<?$discount = true;?>
															<div class="price discount_yes"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
															<div class="price-discount"><strike><?=$arPrice["PRINT_VALUE"]?></strike></div>
														<?}else{?>
															<div class="price"><?=$arPrice["PRINT_VALUE"]?></div>
														<?}?>
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
												rel="nofollow" href="/basket/" data-type="basket" data-item="<?=$arItem['ID']?>">В корзине
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
								</div>
							</div>
						</div>
					</div>
				</article>
			<?endforeach;?>
		</div>
	</section>
<? } ?>
<div class="clear"></div>