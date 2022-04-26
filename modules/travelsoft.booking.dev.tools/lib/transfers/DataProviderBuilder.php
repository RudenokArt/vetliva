<?php

namespace travelsoft\booking\transfers;
use travelsoft\booking\transfers\DataProvider;
use travelsoft\booking\transfers\TransfersBuilder;
use travelsoft\booking\abstractions\DataProviderBuilder as AbstractDataProviderBuilder;
use travelsoft\booking\datastores\TransfersDataStore;
use travelsoft\booking\datastores\ClassAutoDataStore;
use travelsoft\booking\datastores\ServicesDataStore;
use travelsoft\booking\datastores\TransfersRatesDataStore;
use travelsoft\booking\datastores\PTRatesDataStore;
use travelsoft\booking\datastores\PricesDataStore;


/**
 * Класс "строитель" для поставщика данных по трансферам
 *
 * @author dimabresky
 */
class DataProviderBuilder extends AbstractDataProviderBuilder{
    
    /**
     * @param \travelsoft\booking\transfers\Request $request
     */
    public function __construct (Request $request) {
        parent::__construct($request);
    }
    
    /**
     * @return \travelsoft\booking\transfers\DataProvider
     */
    public function build () {
        
        $parameters["transfer"] = TransfersBuilder::build($this->request->point_A, $this->request->point_B);
        $classAutoDataStore = new ClassAutoDataStore(array("filter" => array(">=UF_CAPACITY" => $this->request->adults)));

        $CAID = array_keys($classAutoDataStore->fetch(array("ID")));

        if ($CAID) {
            
            $parameters["transfers"] = new TransfersDataStore(self::_qftr());
            $TID = array_keys($parameters["transfers"]->fetch(array("ID")));

            if ($TID) {
                $parameters["services"] = new ServicesDataStore(array("filter" => array("UF_IBLOCK_ELEMENT_ID" => $TID)));
                $SID = array_keys($parameters["services"]->fetch(array("ID")));
                if ($SID) {
                    
                    $parameters["transfersRates"] = new TransfersRatesDataStore(self::_qftrr($CAID, $SID));
                    
                    $RID = array_keys($parameters["transfersRates"]->fetch(array("ID")));
                    
                    if ($RID) {
                        $parameters["priceTypesRates"] = new PTRatesDataStore(array("filter" => array("UF_RATE_CATEGORY_ID" => $RID)));
                        $PTRID = array_keys($parameters["priceTypesRates"]->fetch(array("ID")));
                        if ($PTRID) {
                            
                            $pricesDataStore = new PricesDataStore(self::_qftrp($SID, $PTRID));
                            if (!empty($pricesDataStore->fetch())) {
                                
                                if ((string)$this->request->date_from === (string)$this->request->date_to) {
                                    $pricesDataStore->multiplyGross(2);
                                } else {
                                    $dates = [$this->request->date_from];
                                    
                                    if (strlen($this->request->date_to) > 0) {
                                        $dates[] = $this->request->date_to;
                                    }
                                    
                                    $pricesDataStore->filterByEmptyPricesPerDates($dates);
                                }
                                
                                $parameters["prices"] = $pricesDataStore;
                            }
                        }
                    }
                }
            }
        }
        
        return new DataProvider($parameters);
    }
    
    /**
     * @return array
     */
    protected function _qftr() {
        return array(
            "filter" => array(
                array(
                    "LOGIC" => "OR",
                    array("UF_POINT_A" => $this->request->point_A, "UF_POINT_B" => $this->request->point_B),
                    array("UF_POINT_A" => $this->request->point_B, "UF_POINT_B" => $this->request->point_A),
                    array("UF_POINT_A" => $this->request->point_A, "UF_POINT_B" => false),
                    array("UF_POINT_A" => $this->request->point_B, "UF_POINT_B" => false)
                )
            )
        );
    }
    
    /**
     * @param array $SID
     * @param array $PTRID
     * @return array
     */
    protected function _qftrp(array $SID, array $PTRID) {
        $filter["UF_DATE"] = $this->request->date_from;
        if ($this->request->date_to) {
            $filter["UF_DATE"] = array($this->request->date_from, $this->request->date_to);
        }
        $filter['>UF_GROSS'] = 0;
        $filter["UF_SERVICE_ID"] = $SID;
        $filter["UF_PTPR_ID"] = $PTRID;
        return array("filter" => $filter);
    }
    
    /**
     * @return array
     */
    protected function _qftrr ($CAID, $SSID) {
        $filter["UF_CLASS_AUTO"] = $CAID;
        $filter["UF_TRANSFER"] = $SSID;
        if ($this->request->rate_id) {
            $filter["ID"] = $this->request->rate_id;
        }
        return array("filter" => $filter);
    }
    
}
