<?php

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
use Bitrix\Main\UserTable;

class ReviewMail
{
	public static function send()
	{
		if(REVIEW_MAILS_ENABLED){

			$hlblock = HLBT::getById(BOOKING_HL_BLOCK)->fetch();  
	        $entity = HLBT::compileEntity($hlblock);
	        $entity_data_class = $entity->getDataClass();

	        //получаем записи заказов за вчерашний день, которые еще не отправлены
	        $result = $entity_data_class::getList(array(
			    "select" => array("*"),
			    "order" => array("ID" => "ASC"),
			    "filter" => array("UF_SENDED" => 0, '<UF_DATE_END' => date('d.m.Y'), 'UF_TO_BE_SEND' => 1),
			));

			while ($arRow = $result->Fetch())
			{		
				//получаем фио пользователя, который оформляет услугу
				$user_name = UserTable::getList(Array(
			       "select"=>array("NAME", "SECOND_NAME"),
			       "filter"=> array('EMAIL' => $arRow['UF_EMAIL']),
			    ))->fetch();			

				//получаем ссылку на детальную страницу услуги
				$itemIBlock = CIBlockElement::GetByID($arRow['UF_IBLOCK_ELEMENT_ID']);
				if($ar_res = $itemIBlock->GetNext()){
					$detail_page_url = $ar_res['DETAIL_PAGE_URL'];
				}

				//заполняем поля шаблона письма
				$arFields = Array(
					"EMAIL_TO" => $arRow['UF_EMAIL'],
					"USER_NAME" => implode(' ', $user_name),
					"LINK" => $detail_page_url,
					"NAME_SERVICE" => $arRow['UF_SERVICE_NAME'],
					"DOGOVOR_CODE" => $arRow['UF_DOGOVOR_CODE'],
				);

				CEvent::Send(REVIEW_EVENT_TYPE, "s1", $arFields, "N", REVIEW_EVENT_MESSAGE_ID);

				//помечаем что письмо уже отправлено
				$entity_data_class::update($arRow['ID'], array(
					'UF_SENDED' => '1',
				));
			}			
		}        

		return "ReviewMail::send();";
	}
}