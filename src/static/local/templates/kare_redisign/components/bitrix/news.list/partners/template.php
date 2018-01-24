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
$this->setFrameMode(true);
?>

<section>
	<div class="contacts-page__wrapper contacts-page__wrapper--partners">
		<div class="container">
			<section class="partners-map">
				<?if (!empty($arResult["ITEMS"])):?>
					<div class="contacts-partners">
						<?if (!empty($arResult["REGIONS"])):?>
							<div class="contacts-select js-contacts-select">
								<div class="contacts-select__initial">
									<span class="text">Выберите регион</span>
									<i class="dropdown__icon"></i>
								</div>
								<div class="contacts-select__dropdown">
									<?foreach ($arResult["REGIONS"] as $arRegion):?>
										<div class="contacts-select__item js-choose-region" data-region="<?=$arRegion["XML_ID"]?>"><?=$arRegion["VALUE"]?></div>
									<?endforeach;?>
								</div>
							</div>
						<?endif;?>
						
						<div class="contacts-partners__scrollable">
							<?foreach ($arResult["ITEMS"] as $arItem):?>
								<?
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
								?>
								<div class="contacts-partners__item js-partner" data-id="p<?=$arItem["ID"]?>" data-region="<?=$arItem["DISPLAY_PROPERTIES"]["REGION"]["VALUE_XML_ID"];?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<span class="contacts-partners__caret js-caret"></span>
									<div><?=$arItem["DISPLAY_PROPERTIES"]["CITY"]["DISPLAY_VALUE"].($arItem["DISPLAY_PROPERTIES"]["STREET"] ? ', '.$arItem["DISPLAY_PROPERTIES"]["STREET"]["DISPLAY_VALUE"] : "")?></div>
									<div class="contacts-partners__title"><?=$arItem["NAME"]?></div>
									<div class="contacts-partners__hidden-part js-hidden-part">
										<?if ($arItem["DISPLAY_PROPERTIES"]["ADDRESS"]):?>
											<div class="contacts-partners__addr contacts-partners__icon">
												<?=$arItem["DISPLAY_PROPERTIES"]["ADDRESS"]["DISPLAY_VALUE"];?>
											</div>
										<?endif;?>
										<?if ($arItem["DISPLAY_PROPERTIES"]["PHONES"]):?>
											<div class="contacts-partners__tel contacts-partners__icon">
												<?=$arItem["DISPLAY_PROPERTIES"]["PHONES"]["DISPLAY_VALUE"];?>
											</div>
										<?endif;?>
										<?if ($arItem["DISPLAY_PROPERTIES"]["HOURS"]):?>
											<div class="contacts-partners__hours contacts-partners__icon">
												<?=$arItem["DISPLAY_PROPERTIES"]["HOURS"]["DISPLAY_VALUE"];?>
											</div>
										<?endif;?>
									</div>
								</div>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
				<div class="contacts-map__wrapper" id="partners-map"></div>
			</section>
		</div>
	</div>
</section>

<script>
	var showrooms = [
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<?$arCoords = explode(',', $arItem["DISPLAY_PROPERTIES"]["COORDINATES"]["VALUE"]);?>
		{
			ID: "p<?=$arItem["ID"]?>",
			REGION: "<?=$arItem["DISPLAY_PROPERTIES"]["REGION"]["VALUE_XML_ID"]?>",
			NAME: "<?=$arItem["NAME"]?>",
			// CITY: "<?=$arItem["DISPLAY_PROPERTIES"]["CITY"]["VALUE"]?>",
			// STREET:"<?=$arItem["DISPLAY_PROPERTIES"]["STREET"]["VALUE"]?>",
			// FULL_ADDRESS: "<?=$arItem["DISPLAY_PROPERTIES"]["ADDRESS"]["VALUE"]?>",
			COORDINATES: {
				lat: "<?=$arCoords[0]?>",
				long: "<?=$arCoords[1]?>"
			},
			// PHONE_NUMBERS: ["+7 977 870 42 32", "+7 916 366 73 33"],
			// WORK_HOURS: "пн-сб: 10:00 - 21:00\nвс: 10:00 - 20:00"
		},
	<?endforeach;?>
	];
	
	
	$(function(){
		$('.js-contacts-select').on('click', function(){
			$(this).toggleClass('opened');
			$(this).find('.contacts-select__dropdown').slideToggle({duration:200});
		});
		$('.contacts-select__item').on('click', function(){
			$(this).closest('.contacts-select').find('.contacts-select__item').removeClass('selected');
			var text = $(this).html();
			$(this).closest('.contacts-select').find('.contacts-select__initial .text').html(text);
			$(this).addClass('selected');
		});
	});
</script>