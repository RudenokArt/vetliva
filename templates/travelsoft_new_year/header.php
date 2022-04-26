<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$oAsset = Bitrix\Main\Page\Asset::getInstance();
CJSCore::Init();
?>
<!doctype html>
<html lang="<?= LANGUAGE_ID ?>">
    <head>
<?if($_SERVER['SERVER_NAME'] == 'vetliva.ru'):?>
<!-- Google Tag Manager -->
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KJ3M9FP');</script>
<!-- End Google Tag Manager -->
<?elseif($_SERVER['SERVER_NAME'] == 'vetliva.by'):?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KDFDZGC');</script>
<!-- End Google Tag Manager -->
<?endif?>
<? $APPLICATION->ShowHead() ?>
        <title><?= $APPLICATION->ShowTitle() ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="shortcut icon" href="/favicon.ico">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <!-- Font Google -->
        <!--link href='http://fonts.googleapis.com/css?family=Lato:300,400%7COpen+Sans:300,400,600' rel='stylesheet' type='text/css'-->
        <!-- End Font Google -->
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/fonts.css"); ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/font-awesome.min.css"); ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/bootstrap.min.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/jquery-ui.min.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/owl.carousel.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/style.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/tscustom.css") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery-1.11.0.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery-ui.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/bootstrap.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/script.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/owl.carousel.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/parallax.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.nicescroll.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.ui.touch-punch.min.js") ?>
<? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.mb.YTPlayer.min.js") ?>
<? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/SmoothScroll.js") ?> 

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/v4-shims.css">

        <style>
            .header .logotype {background-image: url('<?= SITE_TEMPLATE_PATH ?><?= Loc::getMessage("LOGO") ?>');}
        </style>
    </head>
    <body <? if (!empty($APPLICATION->GetDirProperty("IMG_BG_BODY"))): ?>style="background-image: url('<?= $APPLICATION->GetDirProperty("IMG_BG_BODY"); ?>')"<? endif; ?>>
<?if($_SERVER['SERVER_NAME'] == 'vetliva.ru'):?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJ3M9FP" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?elseif($_SERVER['SERVER_NAME'] == 'vetliva.by'):?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id= GTM-KDFDZGC "
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?endif?>
<!-- check templates --><div id="check_templates_1"></div>
<? include_once ($_SERVER['DOCUMENT_ROOT'] . "/metrics.php"); ?>
        <!-- Wrap -->
        <div id="wrap">
            <!-- Header -->
            <header id="header" class="header">
                <div class="container">
                    <!-- Logo -->
                    <div class="logo float-left" style="opacity: 1; visibility: visible;">
                        <a href="/" title="Vetliva"><div style="background-repeat:no-repeat;" class="logotype"></div></a>
                    </div>
                    <!-- End Logo -->

                    <!-- Bars -->
                    <div class="bars" id="bars" style="display:none"></div>
                    <!-- End Bars -->
                    <div class="nav-desktop nav-mobile">
                        <ul class="menu-parent">
                                
                            <li class="switch">
                                <a href="javascript:void(0)"><img class="language" src='<?= SITE_TEMPLATE_PATH ?><?= Loc::getMessage("LANG") ?>' title='<?= Loc::getMessage("LANGTITLE") ?>'></a>
                                <ul class="sub-menu lang">
                                    <li><a href="https://vetliva.ru<?= $_SERVER['REQUEST_URI'] ?>"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/ru20.png" alt="русский"> <span>русский</span></a></li>
                                    <li><a href="https://vetliva.by<?= $_SERVER['REQUEST_URI'] ?>"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/by20.png" alt="беларуская"> <span>беларуская</span></a></li>
                                    <li><a href="https://vetliva.com<?= $_SERVER['REQUEST_URI'] ?>"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/en20.png" alt="english"> <span>english</span></a></li>
                                </ul>
                            </li>
                            <?
                            $APPLICATION->IncludeComponent(
                                    "travelsoft:travelsoft.switch.currency", "modern", Array()
                            );
                            ?>
                            <li class="switch">
                                            <? $APPLICATION->IncludeComponent(
                                                "travelsoft:small.basket", "vetliva", Array()
                                        );?>                                            
                                        </li>
                            <li class="switch login">
                                <?
                                $APPLICATION->IncludeComponent("bitrix:system.auth.form", "header-auth", Array(
                                    "REGISTER_URL" => "/private-office/index.php",
                                    "FORGOT_PASSWORD_URL" => "/private-office/index.php",
                                    "PROFILE_URL" => "/private-office/",
                                    "SHOW_ERRORS" => "Y",
                                    "IS_MOBILE" => 'Y'
                                        )
                                );
                                ?>
                            </li>
                        </ul>
                    </div>
                    <!--Navigation-->
                    <nav class="navigation nav-c nav-desktop" id="navigation" data-menu-type="1000">
                        <div class="nav-inner">
                            <a href="#" class="bars-close" id="bars-close" style="display:none">Закрыть</a>
                            <div class="tb">
                                <div class="tb-cell">
                                    <?
                                    //Главное меню сайта
                                    $APPLICATION->IncludeComponent(
                                            "bitrix:menu", "top", array(
                                        "ROOT_MENU_TYPE" => "top",
                                        "MAX_LEVEL" => "2",
                                        "CHILD_MENU_TYPE" => "left",
                                        "USE_EXT" => "N",
                                        "DELAY" => "N",
                                        "ALLOW_MULTI_SELECT" => "N",
                                        "MENU_CACHE_TYPE" => "A",
                                        "MENU_CACHE_TIME" => "3600",
                                        "MENU_CACHE_USE_GROUPS" => "Y",
                                        "MENU_CACHE_GET_VARS" => array(
                                        ),
                                        "COMPONENT_TEMPLATE" => ".default"
                                            ), false
                                    );
                                    ?>
                                </div>
                                <!-- Switches -->
                                <div class="flow-switches">
                                    <ul class="menu-parent">
                                        
                                        <li class="switch lang-sw">
                                            <a href="javascript:void(0)"><img class="language" src='<?= SITE_TEMPLATE_PATH ?><?= Loc::getMessage("LANG") ?>' title='<?= Loc::getMessage("LANGTITLE") ?>' alt="lang"></a>
                                            <ul class="sub-menu lang">
                                                <li><a href="https://vetliva.ru<?= $_SERVER['REQUEST_URI'] ?>"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/ru20.png" alt="русский"> <span>русский</span></a></li>
                                                <li><a href="https://vetliva.by<?= $_SERVER['REQUEST_URI'] ?>"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/by20.png" alt="беларуская"> <span>беларуская</span></a></li>
                                                <li><a href="https://vetliva.com<?= $_SERVER['REQUEST_URI'] ?>"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/en20.png" alt="english"> <span>english</span></a></li>
                                    			<li><a href="http://belarustrip.by" target="_blank" rel="nofollow"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/cn20.png" alt="china"> <span>中文</span></a></li>
												<li><a href="http://ar.vetliva.com" target="_blank" rel="nofollow"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/for_ar_op.png" alt="arabian"> <span>العربية</span></a></li>
                                            </ul>
                                        </li>
                                        <?
                                        $APPLICATION->IncludeComponent(
                                                "travelsoft:travelsoft.switch.currency", "modern", Array("CACHE_TYPE" => "A", "CACHE_TIME" => 3600000)
                                        );
                                        ?>
                                        <li class="switch basket">
                                            <? $APPLICATION->IncludeComponent(
                                                "travelsoft:small.basket", "vetliva", Array()
                                        );?>                                            
                                        </li>
                                        <li class="switch login">
                                            <?
                                            $APPLICATION->IncludeComponent("bitrix:system.auth.form", "header-auth", Array(
                                                "REGISTER_URL" => "/private-office/user-profile/",
                                                "FORGOT_PASSWORD_URL" => "/private-office/user-profile/",
                                                "PROFILE_URL" => "/private-office/user-profile/",
                                                "SHOW_ERRORS" => "Y"
                                                    )
                                            );
                                            ?>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End switches -->
                            </div>
                        </div>

                    </nav>
                    <script>
                        function MenuResponsive() {
                            var WindowWidth = $(window).width();
                            var menuType = $('.navigation').data('menu-type'),
                                    windowWidth = window.innerWidth,
                                    _Navigation = $('.navigation'),
                                    _Header = $('.header');
                            if (windowWidth < menuType) {
                                _Navigation
                                        .addClass('nav')
                                        .removeClass('nav-desktop')
                                        .closest('.header');
                                _Header.next().css('margin-top', 0);
                                $('.bars, .bars-close, .logo-banner').show();

                                $('.navigation .sub-menu').each(function () {
                                    $(this)
                                            .removeClass('left right');
                                });
                            } else {
                                _Navigation
                                        .removeClass('nav')
                                        .addClass('nav-desktop')
                                        .closest('.header');
                                _Header
                                        .css('background-color', '#fff')
                                        .find('.logo')
                                        .css({
                                            'opacity': '1',
                                            'visibility': 'visible'
                                        });
                                _Header.next().css('margin-top', $('.header').height());
                                $('.bars, .bars-close, .logo-banner').hide();

                                $('.navigation .sub-menu').each(function () {
                                    var offsetLeft = $(this).offset().left,
                                            width = $(this).width(),
                                            offsetRight = (WindowWidth - (offsetLeft + width));
                                    if (offsetRight < 60) {
                                        $(this)
                                                .removeClass('left')
                                                .addClass('right');
                                    } else {
                                        $(this)
                                                .removeClass('right');
                                    }
                                    if (offsetLeft < 60) {
                                        $(this)
                                                .removeClass('right')
                                                .addClass('left');
                                    } else {
                                        $(this)
                                                .removeClass('left');
                                    }
                                });
                            }
                        }
                        MenuResponsive();
                    </script>
                    <!--End Navigation-->
                </div>
            </header>
            <!-- End Header -->
            <? if ($APPLICATION->GetDirProperty("NOT_SHOW_FORM") != "Y"): ?>
                <?
                // Баннер без формы поиска
                $APPLICATION->IncludeComponent(
                        "bitrix:main.include", ".default", array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc_form",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "",
                    "COMPONENT_TEMPLATE" => ".default",
                    "PATH" => "/banner_form.php"
                        ), false
                );
                ?>
            <? else: ?>
                <?
                // Баннер без формы поиска
                $APPLICATION->IncludeComponent(
                        "bitrix:main.include", ".default", array(
                    "AREA_FILE_SHOW" => "sect",
                    "AREA_FILE_SUFFIX" => "inc_noform",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "",
                    "COMPONENT_TEMPLATE" => ".default",
                        //"PATH" => "banner_noform.php"
                        ), false
                );
                ?>
<? endif; ?>
<? if ($APPLICATION->GetDirProperty("NOT_SHOW_INDEX") != "N"): ?>
                <div class="main">
                    <div class="container">
                        <div class="main-cn content-page bg-white clearfix">
    <? if ($APPLICATION->GetDirProperty("NOT_SHOW_SIDEBAR") != "Y"): ?>
                                <div class="col-md-3 col-md-push-0">
                                    <div class="sidebar-cn">
                                        <?
                                        $APPLICATION->IncludeComponent(
                                                "bitrix:main.include", "", Array(
                                            "AREA_FILE_RECURSIVE" => "Y",
                                            "AREA_FILE_SHOW" => "sect",
                                            "AREA_FILE_SUFFIX" => "inc",
                                            "EDIT_TEMPLATE" => ""
                                                )
                                        );
                                        ?>
                                        <!--<div class="sidebar-banner">
                                            <?/*
                                            $APPLICATION->IncludeComponent(
	"bitrix:advertising.banner", 
	".default", 
	array(
		"CACHE_TIME" => "0",
		"CACHE_TYPE" => "A",
		"NOINDEX" => "Y",
		"QUANTITY" => "1",
		"TYPE" => "sidebar",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);
                                            */?>
                                        </div>-->
                                    </div>
                                </div>
                                <div class="col-md-9 col-md-pull-0 content-page-detail">
                                    <h1><?= $APPLICATION->ShowTitle(false) ?></h1>
                                <? endif; ?>
                            <? endif; ?>

