<?php

require_once "cronHeader.php";
Bitrix\Main\Loader::includeModule("iblock");
$CURDATE = date("d.m.Y");
$IBLOCK_ID = 83;
# удаление наступивших
$res = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID, "<PROPERTY_TIMESTART" => date('Y-m-d H:i')), false, false, ['ID', 'PROPERTY_EVENT_ID', 'IBLOCK_ID']);
while ($tmpdata  =$res->GetNext()) CIBlockElement::Delete($tmpdata['ID']);
# список мероприятий
$arEvents  =[];
$res = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID, ">=PROPERTY_TIMESTART" => date('Y-m-d H:i')), false, false, ['ID', 'PROPERTY_EVENT_ID', 'IBLOCK_ID']);
while ($tmpdata  =$res->GetNext()) $arEvents[] = $tmpdata['PROPERTY_EVENT_ID_VALUE'];

$data = json_decode(file_get_contents('https://www.ticketpro.by/widget/data-export/?organizer_id=107280'),1);
$places = $cities = []; 
foreach ($data['places'] as $placetmp) $places[$placetmp['id']] = $placetmp;
foreach ($data['cities'] as $citytmp) $cities[$citytmp['id']] = $citytmp;
$ibel = new CIBlockElement;
foreach ($data['events'] as $event) {
    if (!in_array($event['id'], $arEvents)) {
        $arSave = array(
            "IBLOCK_ID" =>$IBLOCK_ID, 
            "NAME" => $event['name'],
            "DETAIL_PICTURE"=>CFile::MakeFileArray($event['image']),
            "ACTIVE" => "Y",
            "PROPERTY_VALUES" => [
                'event_id'=>$event['id'], 
                'max_price'=>$event['max_price'],  
                'min_price'=>$event['min_price'], 
                'city'=>$cities[$places[$event['place_id']]['city_id']]['name'],  
                'place'=> $places[$event['place_id']]['name'],
                'TIMESTART'=> date('d.m.Y H:i', strtotime($event['timeStart'])),
                'have_tickets'=>$event['have_tickets'],
                 'widget'=>$event['widget'][0],
            ]
        );
       $ibel->Add($arSave);
    }
}
$arEventsExist = [];
$res = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID, ">=PROPERTY_TIMESTART" => date('Y-m-d H:i')), false, false, ['ID', 'PROPERTY_EVENT_ID', 'IBLOCK_ID']);
while ($tmpdata  =$res->GetNext()) $arEventsExist[$tmpdata['PROPERTY_EVENT_ID_VALUE']] = $tmpdata['ID'];
$data = json_decode(file_get_contents('https://www.en.ticketpro.by/widget/data-export/?organizer_id=104429'),1);
$places = $cities = []; 
foreach ($data['places'] as $placetmp) $places[$placetmp['id']] = $placetmp;
foreach ($data['cities'] as $citytmp) $cities[$citytmp['id']] = $citytmp;
$ibel = new CIBlockElement;
foreach ($data['events'] as $event) {
    if ($arEventsExist[$event['id']]>0) CIBlockElement::SetPropertyValuesEx($arEventsExist[$event['id']], false, 
        array('NAME_EN'=>$event['name'],
              'city_EN' => $cities[$places[$event['place_id']]['city_id']]['name'], 
              'place_EN'=>$places[$event['place_id']]['name'], 
              'widget_EN'=>$event['widget'][0]
              ));
}