<?php

$classes = array(
        
    "travelsoft\\booking\\Converter" => "lib/Converter.php",
    "travelsoft\\booking\\Utils" => "lib/Utils.php",
    "travelsoft\\booking\\Basket" => "lib/Basket.php",
    "travelsoft\\booking\\LoyalityProgramm" => "lib/LoyalityProgramm.php",
    "travelsoft\booking\Promo" => "lib/Promo.php",
    "travelsoft\\booking\\Validation" => "lib/Validation.php",
    "travelsoft\\booking\\Gateway" => "lib/Gateway.php",
    "travelsoft\\booking\\Encoder" => "lib/Encoder.php",
    "travelsoft\\booking\\CalculationWidgets" => "lib/CalculationWidgets.php",
        
    # data stores
    "travelsoft\\booking\\datastores\\Autostopsale" => "lib/datastores/Autostopsale.php",
    "travelsoft\\booking\\datastores\\FoodDataStore" => "lib/datastores/FoodDataStore.php",
    "travelsoft\\booking\\datastores\\PTRatesDataStore" => "lib/datastores/PTRatesDataStore.php",
    "travelsoft\\booking\\datastores\\PriceTypesDataStore" => "lib/datastores/PriceTypesDataStore.php",
    "travelsoft\\booking\\datastores\\PricesDataStore" => "lib/datastores/PricesDataStore.php",
    "travelsoft\\booking\\datastores\\QuotasDataStore" => "lib/datastores/QuotasDataStore.php",
    "travelsoft\\booking\\datastores\\RatesQuotasDataStore" => "lib/datastores/RatesQuotasDataStore.php",
    "travelsoft\\booking\\datastores\\RatesDataStore" => "lib/datastores/RatesDataStore.php",
    "travelsoft\\booking\\datastores\\TransfersDataStore" => "lib/datastores/TransfersDataStore.php",
    "travelsoft\\booking\\datastores\\TransfersRatesDataStore" => "lib/datastores/TransfersRatesDataStore.php",
    "travelsoft\\booking\\datastores\\ClassAutoDataStore" => "lib/datastores/ClassAutoDataStore.php",
    "travelsoft\\booking\\datastores\\ServicesDataStore" => "lib/datastores/ServicesDataStore.php",
    "travelsoft\\booking\\datastores\\CitizenshipDataStore" => "lib/datastores/CitizenshipDataStore.php",
    "travelsoft\\booking\\datastores\\PlacementsDataStore" => "lib/datastores/PlacementsDataStore.php",
    "travelsoft\\booking\\datastores\\SanatoriumDataStore" => "lib/datastores/SanatoriumDataStore.php",
    "travelsoft\\booking\\datastores\\ExcursionsDataStore" => "lib/datastores/ExcursionsDataStore.php",
    
    #abstractions
    "travelsoft\\booking\\abstractions\\BasketItem" => "lib/abstractions/BasketItem.php", 
    "travelsoft\\booking\\abstractions\\DataProvider" => "lib/abstractions/DataProvider.php", 
    "travelsoft\\booking\\abstractions\\DataProviderBuilder" => "lib/abstractions/DataProviderBuilder.php", 
    "travelsoft\\booking\\abstractions\\Adapter1DataStore" => "lib/abstractions/Adapter1DataStore.php",
    "travelsoft\\booking\\abstractions\\Adapter2DataStore" => "lib/abstractions/Adapter2DataStore.php",
    "travelsoft\\booking\\abstractions\\DataStoreInterface" => "lib/abstractions/DataStoreInterface.php",
    "travelsoft\\booking\\abstractions\\Getter" => "lib/abstractions/Getter.php",
    "travelsoft\\booking\\abstractions\\PriceCalculator" => "lib/abstractions/PriceCalculator.php",
    "travelsoft\\booking\\abstractions\\Request" => "lib/abstractions/Request.php",
    
    #commons
    "travelsoft\\booking\\abstractions\\commons\\BasketItem" =>  "lib/abstractions/commons/BasketItem.php",
    "travelsoft\\booking\\abstractions\\commons\\DataProvider" =>  "lib/abstractions/commons/DataProvider.php",
    "travelsoft\\booking\\abstractions\\commons\\DataProviderBuilder" =>  "lib/abstractions/commons/DataProviderBuilder.php",
    "travelsoft\\booking\\abstractions\\commons\\PriceCalculator" =>  "lib/abstractions/commons/PriceCalculator.php",
    "travelsoft\\booking\\abstractions\\commons\\Request" =>  "lib/abstractions/commons/Request.php",
    
    #placements
    "travelsoft\\booking\\placements\\BasketItem" => "lib/placements/BasketItem.php",
    "travelsoft\\booking\\placements\\DataProvider" => "lib/placements/DataProvider.php",
    "travelsoft\\booking\\placements\\DataProviderBuilder" => "lib/placements/DataProviderBuilder.php",
    "travelsoft\\booking\\placements\\PriceCalculator" => "lib/placements/PriceCalculator.php",
    "travelsoft\\booking\\placements\\Request" => "lib/placements/Request.php",
    
    #sanatorium
    "travelsoft\\booking\\sanatorium\\BasketItem" => "lib/sanatorium/BasketItem.php",
    "travelsoft\\booking\\sanatorium\\DataProvider" => "lib/sanatorium/DataProvider.php",
    "travelsoft\\booking\\sanatorium\\DataProviderBuilder" => "lib/sanatorium/DataProviderBuilder.php",
    "travelsoft\\booking\\sanatorium\\PriceCalculator" => "lib/sanatorium/PriceCalculator.php",
    "travelsoft\\booking\\sanatorium\\Request" => "lib/sanatorium/Request.php",
    
    #excursions
    "travelsoft\\booking\\excursions\\BasketItem" => "lib/excursions/BasketItem.php",
    "travelsoft\\booking\\excursions\\DataProvider" => "lib/excursions/DataProvider.php",
    "travelsoft\\booking\\excursions\\DataProviderBuilder" => "lib/excursions/DataProviderBuilder.php",
    "travelsoft\\booking\\excursions\\PriceCalculator" => "lib/excursions/PriceCalculator.php",
    "travelsoft\\booking\\excursions\\Request" => "lib/excursions/Request.php",
    
    #excursionstours
    "travelsoft\\booking\\excursionstours\\BasketItem" => "lib/excursionstours/BasketItem.php",
    "travelsoft\\booking\\excursionstours\\DataProvider" => "lib/excursionstours/DataProvider.php",
    "travelsoft\\booking\\excursionstours\\DataProviderBuilder" => "lib/excursionstours/DataProviderBuilder.php",
    "travelsoft\\booking\\excursionstours\\PriceCalculator" => "lib/excursionstours/PriceCalculator.php",
    "travelsoft\\booking\\excursionstours\\Request" => "lib/excursionstours/Request.php",
    
    #transfers
    "travelsoft\\booking\\transfers\\BasketItem" => "lib/transfers/BasketItem.php",
    "travelsoft\\booking\\transfers\\DataProvider" => "lib/transfers/DataProvider.php",
    "travelsoft\\booking\\transfers\\DataProviderBuilder" => "lib/transfers/DataProviderBuilder.php",
    "travelsoft\\booking\\transfers\\PriceCalculator" => "lib/transfers/PriceCalculator.php",
    "travelsoft\\booking\\transfers\\Request" => "lib/transfers/Request.php",
    "travelsoft\\booking\\transfers\\GeoPoint" => "lib/transfers/GeoPoint.php",
    "travelsoft\\booking\\transfers\\GeoPointBuilder" => "lib/transfers/GeoPointBuilder.php",
    "travelsoft\\booking\\transfers\\Transfer" => "lib/transfers/Transfer.php",
    "travelsoft\\booking\\transfers\\TransfersBuilder" => "lib/transfers/TransfersBuilder.php",
    
    "travelsoft\\booking\\Cache" => "lib/CacheAdapter.php"
    
);
CModule::AddAutoloadClasses("travelsoft.booking.dev.tools", $classes);
