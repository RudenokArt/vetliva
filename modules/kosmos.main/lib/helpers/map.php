<?php
namespace Kosmos\Main\Helpers;

class Map
{
    protected $request;
    protected $result;

    protected $itemId;
    protected $iblockId;
    protected $filter = false;

    protected $items;

    protected const CACHE_DIR = '/map';
    protected const CACHE_TTL = 86000; 

    public static function process($request)
    {
        $map = static::create();
        $map->request = $request;

        try{

            $map->includeModules();
            $map->getParams();
            $map->getItems();
            $map->setSelectedItem();
            $map->getHtml();

            $map->result['success'] = true;
            $map->result['message'] = '';

        }
        catch(\Exception $e){
            $map->message = $e->getMessage();
        }

        return $map->result;

    }

    private function __construct()
    {
        $this->result = [
            'success' => false,
            'message' => 'Unknown error',
        ];
    }

    protected static function create()
    {
        $className = __CLASS__;
        return new $className;
    }

    protected function includeModules()
    {
        if(!\Bitrix\Main\Loader::includeModule('iblock')){
            throw new \Exception('Include module \'iblock\' failed');
        }
    }

    protected function getParams()
    {
        $this->itemId = (int) $this->request->get('id');

        if(!($this->itemId > 0)){
            throw new \Exception('Bad item id');
        }

        $this->getIblockId();

        $this->filter = $this->request->get('filter');
    }

    protected function getIblockId()
    {
        $result = \Bitrix\Iblock\ElementTable::getList([
            'select' => ['ID', 'IBLOCK_ID'],
            'filter' => ['=ID' => $this->itemId]
        ]);

        if($row = $result->fetch()){
            $this->iblockId = (int) $row['IBLOCK_ID'];
        }
        else{
            throw new \Exception('Item not found');
        }
    }

    protected function getItems()
    {
        if($this->filter){
            $this->fetchItems();
        }
        else{
            $cacheId = \Kosmos\Main\Helpers\Common::getCacheId('map-iblock-' . $this->iblockId);
            $cacheDir = static::CACHE_DIR;
            $cacheTtl = static::CACHE_TTL;

            $obCache = new \CPHPCache;
            if($obCache->InitCache($cacheTtl, $cacheId, $cacheDir)){
                $this->items = $obCache->GetVars();
            }
            elseif($obCache->StartDataCache()){
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache($cacheDir);

                $this->fetchItems();

                $CACHE_MANAGER->EndTagCache();
                $obCache->EndDataCache($this->items);
            }
            else{
                throw new \Exception('Cache system error');
            }
        }

        if(empty($this->items)){
            throw new \Exception('Nothing found');
        }
    }

    protected function fetchItems()
    {
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $this->iblockId,
            '!PROPERTY_MAP' => false
        ];

        if($this->filter){
            foreach ($this->filter as $key=>&$val) {
                if (is_array($val)) {
                    foreach ($val as &$val2)
                        if ($val2=='false')
                            $val2 = false;
                }
                elseif ($val=='false') $val = false;
            }
            $arFilter = array_merge($arFilter, $this->filter);
        }

        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'NAME',
            'DETAIL_PAGE_URL',
            'PROPERTY_MAP',
            'PROPERTY_NAME' . POSTFIX_PROPERTY
        ];

        $this->items = [];

        $result = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while($row = $result->GetNext()){

            $name = (LANGUAGE_ID === 'ru') ? $row['NAME'] : $row['PROPERTY_NAME' . POSTFIX_PROPERTY . '_VALUE'];
            if ($this->filter['TYPE']=='route') {
                $this->items[] = [
                    'type' => 'Feature',
                    'id' => $row['ID'],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => explode(',', $row['PROPERTY_MAP_VALUE'])
                    ],
                    'properties' => [
                        'id' => $row['ID'],
                        'name' => $name,
                        'url' => $row['DETAIL_PAGE_URL'],
                        'selected' => 'N',
                        'balloonContent' => '<a href="'.$row['DETAIL_PAGE_URL'].'" target="_blank" title="">'.$name.'</a>',
                    ]
                ];
            }
            else {
                $this->items[] = [
                    'type' => 'Feature',
                    'id' => $row['ID'],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => explode(',', $row['PROPERTY_MAP_VALUE'])
                    ],
                    'properties' => [
                        'id' => $row['ID'],
                        'name' => $name,
                        'url' => $row['DETAIL_PAGE_URL'],
                        'selected' => 'N',
                        'balloonContent' => ($row['ID'] == $this->itemId) ? $name : '<a href="'.$row['DETAIL_PAGE_URL'].'" target="_blank" title="">'.$name.'</a>',
                    ]
                ];
            }
            

            if(!$this->filter){
                global $CACHE_MANAGER;
                $CACHE_MANAGER->RegisterTag('iblock_id_' . $row['IBLOCK_ID']);
            }

        }
    }

    protected function setSelectedItem()
    {
        foreach($this->items as $key => $item){
            if($item['id'] == $this->itemId){
                $this->items[$key]['properties']['selected'] = 'Y';
                break;
            }
        }
    }

    protected function getHtml()
    {
        switch(LANGUAGE_ID){
            case 'en':
                $lang = 'en-US';
                break;
            default:
                $lang = 'ru-RU';
                break;
        }
        if ($this->filter['TYPE']=='route') {
            $tmpdata = $this->items;
            foreach ($tmpdata as $tmpcount=>&$tmpval) 
                if ($tmpcount==0 || $tmpcount==(count($tmpdata)-1))
                    $tmpval['properties']['selected'] ='Y';
                else 
                    $tmpval['properties']['selected'] ='N';       
            $objectManager = \Bitrix\Main\Web\Json::encode([
                'type'	=> 'FeatureCollection',
                'features' => $tmpdata
            ]);     
        }
        else {
            $objectManager = \Bitrix\Main\Web\Json::encode([
                'type'	=> 'FeatureCollection',
                'features' => $this->items
            ]);
        }
        

        ob_start();
        if ($this->filter['TYPE']=='route')
            include __DIR__ . '/templates/maproute.php';
        else
            include __DIR__ . '/templates/map.php';

        $this->result['html'] = ob_get_clean();
    }
}