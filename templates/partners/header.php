<?

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

CJSCore::Init();

$oAsset = Bitrix\Main\Page\Asset::getInstance();

\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
/**
 * является ли пользователь потавщиком или пользователем с ограниченными правами
 */
define('USER_IS_PROVIDER', travelsoft\booking\Utils::checkUserIsProvider() || $GLOBALS["USER"]->IsAdmin());
?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID?>">
    <head>


        <meta charset="<?= LANG_CHARSET?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?$APPLICATION->ShowHead()?>
        <title><?= $APPLICATION->ShowTitle()?></title>
        <?
        // Global stylesheets
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/ui/moment/moment.min.js');
        $oAsset->addCss('https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900');
        $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/icons/icomoon/styles.css");
        $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/minified/bootstrap.min.css");
        $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/minified/core.min.css");
        $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/minified/components.min.css");
        $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/minified/colors.min.css");
        $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/tscustom.css");
        ?>

        <?
        // Core JS files
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/loaders/pace.min.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/core/libraries/jquery.min.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/core/libraries/bootstrap.min.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/loaders/blockui.min.js');
        ?>

        <?
        // theme JS files
        $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.magnific-popup.js");
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/visualization/d3/d3.min.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/visualization/d3/d3_tooltip.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/forms/selects/select2.min.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/forms/styling/switchery.min.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/forms/styling/uniform.min.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/forms/selects/bootstrap_multiselect.js');

        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/pickers/daterangepicker.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/plugins/ui/nicescroll.min.js');
        
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/core/app.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/pages/layout_fixed_custom.js');
        $oAsset->addJs(SITE_TEMPLATE_PATH . '/js/pages/form_layouts.js');

        // font-awesome
        $APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
        ?>

    </head>

    <body class="navbar-top">
   
        <?
        $APPLICATION->ShowPanel();
        ?>
        <!-- Main navbar -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-header">
                <a class="navbar-brand" href="/<?/*= PROVIDER_RELATIVE_PATH*/?>"><img src="<?= SITE_TEMPLATE_PATH?>/images/logo-vetliva-partner.png" alt=""></a>

                <ul class="nav navbar-nav visible-xs-block">
                    <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
                    <?if (USER_IS_PROVIDER):?>
                    <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
                    <?endif?>
                </ul>
                
            </div>

            <div class="navbar-collapse collapse" id="navbar-mobile">
                <ul class="nav navbar-nav">
                    <?if (USER_IS_PROVIDER):?>
                    <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
                    <?endif?>
                </ul>
                <?if (USER_IS_PROVIDER):
                    $db_placements  = current(travelsoft\booking\datastores\PlacementsDataStore::get(array(
                        "filter" => array("ACTIVE" => "Y", "PROPERTY_USER" => $GLOBALS["USER"]->GetID()),
                        "select" => array("ID")
                    )));
                    $db_sanatorium  = current(travelsoft\booking\datastores\SanatoriumDataStore::get(array(
                        "filter" => array("ACTIVE" => "Y", "PROPERTY_USER" => $GLOBALS["USER"]->GetID()),
                        "select" => array("ID")
                    )));
                    $db_excursions  = current(travelsoft\booking\datastores\ExcursionsDataStore::get(array(
                        "filter" => array("ACTIVE" => "Y", "PROPERTY_USER_ID" => $GLOBALS["USER"]->GetID()),
                        "select" => array("ID")
                    )));
                    if (!$db_placements["ID"] && !$db_sanatorium["ID"] && !$db_excursions["ID"]):
                    ?>
                <p class="navbar-text"><span class="label bg-danger-400">В ПРОДАЖЕ НЕТ АКТИВНЫХ ЭЛЕМЕНТОВ</span></p>
                <?endif?>
                <?endif?>
                <ul class="nav navbar-nav navbar-right">

                    <!-- <li><a href="http://vetliva.by">vetliva.by</a></li>
                    <li><a href="http://vetliva.ru">vetliva.ru</a></li>
                    <li><a href="http://vetliva.com">vetliva.com</a></li> -->
                    
					<? if (USER_IS_PROVIDER):?>
						<li  class="logout-link"><a href="<?= PROVIDER_RELATIVE_PATH?>/?logout=yes"><?= Loc::getMessage('USER_EXIT')?></a></li>
                    <? endif?>
                </ul>
            </div>
        </div>
        <!-- /main navbar -->


        <!-- Page container -->
        <div class="page-container <?= $APPLICATION->ShowViewContent('htmlClass')?>">

            <!-- Page content -->
            <div class="page-content">
            <?if (USER_IS_PROVIDER):?>
                <!-- Main sidebar -->
                <div class="sidebar sidebar-main sidebar-fixed">
                    <div class="sidebar-content">

                        <!-- User menu -->
                        <div class="sidebar-user">
                            <div class="category-content">
                                <div class="media">
                                    <? 
                                    $avatar = SITE_TEMPLATE_PATH."/images/placeholder.jpg";
                                    $arr_user = $GLOBALS["USER"]->GetByID($GLOBALS["USER"]->GetID())->GetNext();
                                    if ($arr_user["ID"] > 0) {
                                        $arr_company_name = $arr_user["WORK_COMPANY"];
                                        $arr_avatar = CFile::GetFileArray($arr_user["PERSONAL_PHOTO"]);
                                        if ($arr_avatar["ID"] > 0) {
                                            $avatar = $arr_avatar["SRC"];
                                        }
                                    }
                                    ?>
                                    <a href="javascript:void(0)" class="media-left"><img src="<?= $avatar?>" class="img-circle img-sm" alt=""></a>
                                    <div class="media-body">
                                        <span class="media-heading text-semibold"><?= isset($arr_company_name) && !empty($arr_company_name) ? $arr_company_name : $GLOBALS["USER"]->GetFullName()?></span>
                                        <div class="text-size-mini text-muted">
                                            <?= $GLOBALS["USER"]->GetLogin()?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /user menu -->

<?$APPLICATION->IncludeComponent("bitrix:menu","travelsoft_vertical_multilevel",Array(
        "ROOT_MENU_TYPE" => "top", 
        "MAX_LEVEL" => "3", 
        "CHILD_MENU_TYPE" => "left", 
        "USE_EXT" => "N",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "Y",
        "MENU_CACHE_TYPE" => "N", 
        "MENU_CACHE_TIME" => "3600", 
        "MENU_CACHE_USE_GROUPS" => "Y", 
        "MENU_CACHE_GET_VARS" => "" 
    )
);?>

                    </div>
                </div>
                <!-- /main sidebar -->
            <?endif?>

                <!-- Main content -->
                <div class="content-wrapper">
            <?if (USER_IS_PROVIDER):?>
                    <!-- Page header -->
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title">
                                <h4>
                                    
                                    <span class="text-semibold"><?$APPLICATION->ShowTitle()?></span></h4>
                            </div>

                            <div class="heading-elements">
                                <div class="heading-btn-group">

                                    <a href="/partners/statistika/" class="btn btn-link btn-float has-text"><i class="icon-bars-alt text-primary"></i><span><?= Loc::getMessage('STATISTICS')?></span></a>
		 <a href="#" class="btn btn-link btn-float has-text" style="display:none"><i class="icon-calculator text-primary"></i> <span><?= Loc::getMessage('INVOICES')?></span></a>
                                    <a href="/partners/support/" class="btn btn-link btn-float has-text"><i class="icon-comment-discussion"></i> <span><?= Loc::getMessage('SUPPORT')?></span></a>

                                </div>
                            </div>
                        </div>

                        <div class="breadcrumb-line">
                            
                        </div>
                    </div>
                    <!-- /page header -->
            <?endif?>
                    <!-- Content area -->
                    <div class="content">