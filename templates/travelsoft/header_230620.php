<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$oAsset = Bitrix\Main\Page\Asset::getInstance();
CJSCore::Init();

/*if ($_REQUEST['login'] === 'yes'){
                                    LocalRedirect($APPLICATION->GetCurPageParam("", array("login"), false));
}*/
?>
<!doctype html>
<html lang="<?= LANGUAGE_ID ?>">
    <head>
<? $APPLICATION->ShowHead() ?>
        <title><?= $APPLICATION->ShowTitle() ?></title>
		<meta name="cmsmagazine" content="086bf995668ed83bd849f19995805bec" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<? if(LANGUAGE_ID == "en"):?>
		<meta name="p:domain_verify" content="319baa7f3d019783879a066d0121a74c"/>
		<? endif; ?>
        <link rel="shortcut icon" href="/favicon.ico">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
		<!--<meta property="og:image" content="//vetliva.ru/local/templates/travelsoft/images/logo-header.png" />-->
        <!-- Font Google -->
        <!--link href='http://fonts.googleapis.com/css?family=Lato:300,400%7COpen+Sans:300,400,600' rel='stylesheet' type='text/css'-->
        <!-- End Font Google -->
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/fonts.css"); ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/font-awesome.min.css"); ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/bootstrap.min.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/jquery-ui.min.css") ?>
		<? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/jquery.arcticmodal.css") ?>
		<? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/arctic-themes/simple.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/owl.carousel.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/slick-slider.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/slick-theme.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/jquery.fancybox.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/style.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/grid.module.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/tscustom.css?v=03042018") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/slider_new_style.css") ?>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/v4-shims.css">
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery-1.11.0.min.js") ?>

        <? //$oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery3.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery-ui.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/bootstrap.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.fancybox.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/script.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/owl.carousel.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/slick.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/parallax.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.nicescroll.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.ui.touch-punch.min.js") ?>


<? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.mb.YTPlayer.min.js") ?>
<? // $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/SmoothScroll.js") ?> 
<? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/statistic_.js") ?>
<!--Подключение плагина arctic modal -->
<? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.arcticmodal.js") ?>
<script src="//yandex.st/jquery/cookie/1.0/jquery.cookie.min.js"></script>
<?// подключение языкового файла для js
$ARJSMESS = Loc::LoadLanguageFile($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/header.php");
if (!empty($ARJSMESS)) {
    // Добавляем объект с переводами.
    $oAsset->AddString("<script type=\"text/javascript\">BX.message(".CUtil::PhpToJSObject($ARJSMESS).")</script>");
}
?>
        <style>
            .header .logotype {background-image: url('<?= SITE_TEMPLATE_PATH ?><?= Loc::getMessage("LOGO") ?>');}
        </style>
    </head>
	<body <? if (!empty($APPLICATION->GetDirProperty("IMG_BG_BODY"))): ?>style="background-image: url('<?= $APPLICATION->GetDirProperty("IMG_BG_BODY"); ?>');background-repeat: no-repeat;"<? endif; ?> class="body-<?= LANGUAGE_ID ?>">
<? include_once ($_SERVER['DOCUMENT_ROOT'] . "/metrics.php"); ?>

<!-- Всплывающий банер по экскурсиям 
		<style>
		.modal-dialog.modal-top-right {
			top: 10px;
			right: 10px;
		}
		.modal-side {
			position: absolute;
			bottom: 10px;
			right: 10px;
			margin: 0;
			width: 400px;
		}
		#EGNotify .modal-backdrop.in {
			opacity: .0;
		}
		</style>
        <div class="modal fade right" id="EGNotify" tabindex="-1" role="dialog" aria-labelledby="EGNotifyLabel" 
        aria-hidden="true">
			<div class="modal-dialog modal-side modal-top-right" role="document" style="width:15%;height:50%;">
                <div class="modal-content">
                    <div class="modal-body" style="padding:5px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
						<a target="_blank" href="https://vetliva.<?=(LANGUAGE_ID != "en")?LANGUAGE_ID:"com"?>/tourism/cognitive-tourism/?utm_source=vetliva.ru&utm_medium=banner&utm_campaign=june">
							<img width="100%" src="<?=SITE_TEMPLATE_PATH ?>/images/excur_not_<?=LANGUAGE_ID ?>.jpg">
						</a>
                    </div>
                </div>
            </div>
        </div>
	-->

<!-- Уведомление о куках -->
<!--<div style="display: none;">-->
<template id="cookies-notify">
  <div class="box-modal" id="boxUserFirstInfo">
    <div class="box-modal_close arcticmodal-close">ОК</div>
    <?=Loc::getMessage("COOKIE_MSG"); ?>
  </div>
</template>
<!--</div>-->

<!-- Для работы авторизации через фэйсбук -->
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '1374418662728994',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v5.0'
    });
  };
</script>
<script async defer src="https://connect.facebook.net/en_US/sdk.js"></script>

<? $APPLICATION->ShowHeadStrings(); // Для вывода разметки Schema в формате JSON?>

<?
$url_statistic = md5($APPLICATION->GetCurPage(false));
$metric_lang = METRIC_KEY;
$id_goal = GOAL_ID;
?>
<!--<script>console.log(<?/*=$metric_lang*/?>,"<?/*=$id_goal*/?>","<?/*=$url_statistic*/?>");</script>-->
<script>
    window.Statistics = new Statistic(<?= $metric_lang?>,"<?=$id_goal?>","/","<?=$url_statistic?>");
    window.Statistics.cookieCheck();
    //console.log(document.cookie);
</script>
        <!-- Wrap -->
        <div id="wrap">
            <!-- Header -->
            <header id="header" class="header">
                <div class="container">
                    <!-- Logo -->
                    <div class="logo float-left" style="opacity: 1; visibility: visible;">
                        <a href="/" title="Vetliva"><div class="logotype"></div></a>
                    </div>
                    <!-- End Logo -->

                    <!-- Bars -->
                    <div class="bars" id="bars" style="display:none"></div>
                    <!-- End Bars -->
                    <div class="nav-desktop nav-mobile">
                        <ul class="menu-parent">
                                
                            <?/*<li class="switch">
                                <a href="javascript:void(0)"><img class="language" src='<?= SITE_TEMPLATE_PATH ?><?= Loc::getMessage("LANG") ?>' title='<?= Loc::getMessage("LANGTITLE") ?>'></a>
                                <ul class="sub-menu lang">
                                    <li><a href="https://vetliva.ru<?= $_SERVER['REQUEST_URI'] ?>"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/ru20.png" alt="русский"> <span>русский</span></a></li>
                                    <li><a href="https://vetliva.by<?= $_SERVER['REQUEST_URI'] ?>"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/by20.png" alt="беларуская"> <span>беларуская</span></a></li>
                                    <li><a href="https://vetliva.com<?= $_SERVER['REQUEST_URI'] ?>"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/en20.png" alt="english"> <span>english</span></a></li>
                                    <li><a href="http://belarustrip.by" target="_blank" rel="nofollow"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/cn20.png" alt="china"> <span>中文</span></a></li>
                                </ul>
                            </li>*/?>
                            <li class="mobile-lang">
									<a href="#header-langs" class="header-lang"><img class="language" src='<?= SITE_TEMPLATE_PATH ?><?= Loc::getMessage("LANG") ?>' title='<?= Loc::getMessage("LANGTITLE") ?>'></a>
                    
									<div id="header-langs" class="header-lang-form mfp-hide">
											<div class="mfp-head"><?=Loc::getMessage("CHOOSE_LANG")?></div>
											<ul class="sub-menu lang">
                                                <li>
                                                    <input id="langru" type="radio" <?if (Loc::getMessage("LANG")=='/images/ru20.png'):?>checked=""<?endif;?> onclick="location.href='https://vetliva.ru<?= $_SERVER['REQUEST_URI'] ?>'"/>
                                                    <label for="langru"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/ru20.png" alt="русский"> <span>русский</span></label>
                                                </li>
                                                <li>
                                                    <input id="langby" type="radio" <?if (Loc::getMessage("LANG")=='/images/by20.png'):?>checked=""<?endif;?> onclick="location.href='https://vetliva.by<?= $_SERVER['REQUEST_URI'] ?>'"/>
                                                    <label for="langby"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/by20.png" alt="беларуская"> <span>беларуская</span></label>
                                                </li>
                                                <li>
                                                    <input id="langen" type="radio" <?if (Loc::getMessage("LANG")=='/images/en20.png'):?>checked=""<?endif;?> onclick="location.href='https://vetliva.com<?= $_SERVER['REQUEST_URI'] ?>'"/>
                                                    <label for="langen"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/en20.png" alt="english"> <span>english</span></label>
                                                </li>
                                                <li>
                                                    <input id="langch" type="radio"  onclick="window.open('http://belarustrip.by','_blank');"/>
                                                    <label for="langch"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/cn20.png" alt="china"> <span>中文</span></label>
                                                </li>
                                                <li>
                                                    <input id="langar" type="radio"  onclick="window.open('http://ar.vetliva.com','_blank');"/>
                                                    <label for="langar"><img class="language" src="<?= SITE_TEMPLATE_PATH ?>/images/for_ar_op.png" alt="arabian"> <span>العربية</span></label>
                                                </li>
                                            </ul>
														
									</div>	
								</li>
								<script>				
									$(document).ready(function (){
											$(".header-lang").magnificPopup({
											type: "inline",
											mainClass: 'mfp-lang-mobile',
											midClick: true
										});
										
									});

								</script>
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
                             <div class="mobile-menu-head"><?=Loc::getMessage("MENU_TITLE")?></div>
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
                            <!--Breakcrumb-->
                            <section class="breakcrumb-sc">
                                <?
                                $APPLICATION->IncludeComponent(
                                        "bitrix:breadcrumb", "breadcrumb", array(
                                    "PATH" => "",
                                    "SITE_ID" => LANGUAGE_ID,
                                    "START_FROM" => "1",
                                    "COMPONENT_TEMPLATE" => "breadcrumb"
                                        ), false
                                );
                                ?>
								<? if ($APPLICATION->GetDirProperty("SHOW_PHONE") == "Y"): ?>
                                <div class="support float-right">
                                    <small><?= Loc::getMessage("HAVEAQUESTION") ?></small> <?= Loc::getMessage("PHONE") ?>
                                </div>
								<?endif;?>
                            </section>
                            <!--End Breakcrumb-->
							<h1 class="mobile-h1"><?= $APPLICATION->ShowTitle(false) ?><?= $APPLICATION->ShowViewContent('cnt__elements_header') ?></h1>
								<? if ($APPLICATION->GetDirProperty("NOT_SHOW_SIDEBAR") != "Y"): ?>
                                <div class="col-md-3 col-md-push-0">
                                    <div class="sidebar-cn <?if(CSite::InDir('/private-office/')):?>private-office-menu<?endif;?> <?if(CSite::InDir('/belarus/made-in-belarus/' && '/belarus/getting-there/')):?>made-in-sidebar<?endif;?>">
									<? if ($APPLICATION->GetDirProperty("NOT_SHOW_HIDE_MENU_BUTTON") != "Y"): ?>
                                        <div class="active aside-menu active" data="toogle-menu"><span class="ts-d-lg-none"><span/> </div>
                                        <div class="sidebar-cn active <?if(CSite::InDir('/belarus/made-in-belarus/')):?>made-in-sidebar<?endif;?>" data="collapse" >
									<? endif; ?>
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
									<? if ($APPLICATION->GetDirProperty("NOT_SHOW_HIDE_MENU_BUTTON") != "Y"): ?>
                                        </div>
									<? endif; ?>
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
                                    <div class="wrp-ttl">
                                        <h1><?= $APPLICATION->ShowTitle(false) ?></h1>
                                        <?php $APPLICATION->ShowViewContent('map-link')?>
                                    </div>
                                <? endif; ?>
                            <? endif; ?>
									<? if ($APPLICATION->GetDirProperty("NOT_SHOW_HIDE_MENU_BUTTON") != "Y"): ?>
                                    <script>
                                        $(document).ready(function (e) {
                                            class CollapseNav{
                                                constructor(node,collapseNode,navText){
                                                    this.navText = navText;
                                                    this.collapseNode = collapseNode;
                                                    this.node = node;
                                                    this.resize();
                                                    this.deleteCollapse();
                                                };
                                                resize(node = this.node,collapseNode = this.collapseNode){
                                                    if(window.innerWidth<768) {
                                                        collapseNode.classList.remove('active');
                                                        node.classList.remove('active');
                                                        node.children[0].innerText = this.navText[0];
                                                    };
                                                }
                                                deleteCollapse(){
                                                    [].slice.call(document.querySelectorAll('[data="toogle-menu"]')).forEach((item,index)=>{
                                                        if(index>0){
                                                        item.remove();
                                                    }
                                                })
                                                }
                                                collapseClick(node = this.node, collapseNode = this.collapseNode){
                                                    node.onclick = (e) =>{
                                                        node.classList.toggle('active');
                                                        collapseNode.classList.toggle('active');
                                                        if(node.classList.contains('active')){
                                                            node.children[0].innerText = this.navText[1];
                                                        }
                                                        else{
                                                            node.children[0].innerText = this.navText[0];
                                                        }
                                                    }
                                                }

                                            }
                                            if(document.querySelector('[data="collapse"]')){
                                                const clickNode = document.querySelector('[data="toogle-menu"]');
                                                const collapseNode = document.querySelector('[data="collapse"]');

                                                let collapseNav = new CollapseNav(clickNode,collapseNode,[`<?= Loc::getMessage('MENU_LANG_3') ?>`,`<?= Loc::getMessage('MENU_LANG_4') ?>`]);
                                                collapseNav.collapseClick();
                                            }

                                        })

                                    </script>
									<? endif; ?>
