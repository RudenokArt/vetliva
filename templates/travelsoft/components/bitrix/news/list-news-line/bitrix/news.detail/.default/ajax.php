<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?
	$data = explode("-", $_POST["inf"]);

	if (CModule::IncludeModule('iblock'))
	{
		$arFilter = array('IBLOCK_ID' => $data[0], 'ID' => $data[1], 'ACTIVE' => 'Y');
		$arSelect = array("ID","IBLOCK_ID");
	
		$res = CIBlockElement::GetList(array(), $arFilter, false,  false, $arSelect);
		$ob = $res->GetNextElement();
		$arProp = $ob->GetProperties();
		$prop_vote = ($data[2] == "L")?"LIKES":"DISLIKES";
		$prop_vote_neg = ($data[2] == "L")?"DISLIKES":"LIKES";
		$bInvInc = false; $inc = 0;

		//Подготовка массива свойств
		switch($data[3]) {
			case "N":
				$inc = 1; break;
			case "liked":
				if($data[2] == "L")
					$inc = -1;
				else if($data[2] == "D") {$bInvInc = true; $inc = 1;}
				break;
			case "disliked":
				if ($data[2] == "D")
					$inc = -1;
				else if($data[2] == "L") {$bInvInc = true; $inc = 1;}
				break;
		}

		$must_change = array();

		if($bInvInc) {		
			$must_change = array(
				$prop_vote => $arProp[$prop_vote]["VALUE"] + $inc,
				$prop_vote_neg => $arProp[$prop_vote_neg]["VALUE"] - 1,
			);
			$result_like = -1;
		} else {
			$must_change = array(
				$prop_vote => $arProp[$prop_vote]["VALUE"] + $inc,
			);
			$result_like = $arProp[$prop_vote]["VALUE"] + $inc;
		}
		
		CIBlockElement::SetPropertyValuesEx($data[1], $data[0], $must_change);
		echo $result_like;
	}
	else
		echo "";
?>