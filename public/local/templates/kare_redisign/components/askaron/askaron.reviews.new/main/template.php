<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->createFrame()->begin( GetMessage( "ASKARON_REVIEWS_NEW_LOADING" ) );
?>
<div class="reviews">
	<div class="reviews__wrapper">
		<?if( $arResult["NEW_ADDED"] ):?>
			<p class="ask-ok"><?=$arResult["NEW_ADDED_TEXT"]?>
			<?if( $arResult["PREMODERATE"] ):?>
				<br /><?=$arResult["NEW_ADDED_PREMODERATE_TEXT"]?>
			<?endif?>
			</p>
		<?else:?>
			<?if( count( $arResult["ERRORS"] ) > 0 ):?>
				<div class="ask-error">
					<?foreach ( $arResult["ERRORS"] as $error ):?>
						<?=$error?><br />
					<?endforeach ?>
					<script>
						$(".tabs #product_reviews_tab").addClass("cur").siblings().removeClass("cur");
						$(".tabs_content .reviews_block_wrapp").addClass("cur").siblings().removeClass("cur");
						$('html, body').animate({scrollTop : $(".tabs #product_reviews_tab").offset().top - 20},200);
					</script>
				</div>
			<?endif;?>
			<form class="reviews__form js-reviews-form" id="reviews-form" action="<?=POST_FORM_ACTION_URI?>" method="POST">
				<?=bitrix_sessid_post()?>
				<input type="hidden" name="new_review_added" value="" >
				<div class="reviews__rating">
					<label class="reviews__caption">Ваша оценка:</label>
					<span class="reviews-check | checkboxes">
						<?for ( $i=1; $i <= 5; $i++ ):?>
							<label for="askaron_reviews_grade_<?=$i?>" class="reviews-check__label icon-wrapper icon-wrapper_star">
								<input
									class="reviews-check__radio js-reviews-checkbox"
									required
									id="askaron_reviews_grade_<?=$i?>"
									type="radio"
									name="new_review[GRADE]"
									value="<?=$i?>"
									<?if ( $arResult["FIELDS"]["GRADE"]["VALUE"] == $i ):?>
										checked
									<?endif?>
								>
								<?= \Local\Svg::get('star', 'icon icon_star') ;?>
							</label>
						<?endfor?>
					</span>
				</div>
				<?if ($arResult["FIELDS"]["AUTHOR_NAME"]):?>
					<div class="reviews__input-wrap input-wrap">
						<input id="new_review[AUTHOR_NAME]" class="reviews__field form-field" type="text" required name="new_review[AUTHOR_NAME]" placeholder="Ваше имя*" value="<?=$arResult["FIELDS"]["AUTHOR_NAME"]["VALUE"]?>">
					</div>
				<?endif?>
				<?if ($arResult["FIELDS"]["AUTHOR_EMAIL"]):?>
					<div class="reviews__input-wrap input-wrap">
						<input id="new_review[AUTHOR_EMAIL]" class="reviews__field form-field" type="email" required name="new_review[AUTHOR_EMAIL]" placeholder="Email*" value="<?=$arResult["FIELDS"]["AUTHOR_EMAIL"]["VALUE"]?>">
					</div>
				<?endif?>
				<div class="reviews__input-wrap reviews__input-wrap_textarea">
					<textarea id="new_review[TEXT]" class="reviews__field form-field reviews__filed_textarea" name="new_review[TEXT]" placeholder="Комментарий"><?=$arResult["FIELDS"]["TEXT"]["VALUE"]?></textarea>
				</div>

				<div class="checkbox-input checkbox-input-br">
					<input class="checkbox-input__input js-personal-data" type="checkbox" id="rev_personal_data" name="rev_personal_data" checked required>
					<label class="checkbox-input__label" for="rev_personal_data">
						Я даю <a href="/help/dogovor-oferty/" target="_blank">согласие на обработку своих персональных данных</a> и соглашаюсь с правилами безопасности компании *
					</label>
				</div>
				<div class="reviews__bottom cf">
					<button type="submit" name="new_review_form" class="btn reviews__btn" value="submit">Отправить</button>
					<input type="hidden" name="new_review_form" value="Y">
					<span class="reviews__note">&#042;Обязательно для заполнения</span>
				</div>
			</form>
			<?/*if ( $arResult["PREMODERATE"] ):?>
				<div class="ask-note"><?=GetMessage("ASKARON_REVIEWS_NEW_PREMODERATE")?></div>
			<?endif*/?>
		<?endif?>
	</div>
</div>
