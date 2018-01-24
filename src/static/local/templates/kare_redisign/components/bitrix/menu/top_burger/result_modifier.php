<?php
$result = $arResult;
$arResult = [];
$index = 0;
foreach ($result as $item){
    if($item['DEPTH_LEVEL'] == 1) {
        $arResult[$index] = $item;
        $index++;
    }
    if($item['DEPTH_LEVEL'] == 2) {
        $arResult[$index-1]['CHILD'][] = $item;
    }
}