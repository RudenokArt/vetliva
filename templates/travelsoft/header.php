<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$oAsset = Bitrix\Main\Page\Asset::getInstance();
CJSCore::Init();

/*if ($_REQUEST['login'] === 'yes'){
                                    LocalRedirect($APPLICATION->GetCurPageParam("", array("login"), false));
}*/
if(CSite::InDir(SITE_DIR.'private-office/')) {
    global $USER; if ($USER->IsAuthorized()) {
        if (CSite::InGroup ([26, 11, 12, 13, 14, 25])) LocalRedirect('/partners/');
        if (CSite::InGroup ([28, 9])) LocalRedirect('/agent/private-office/');
        if (CSite::InGroup ([30, 31])) LocalRedirect('/agentIHWC/private-office/');
    }
}
if(CSite::InDir(SITE_DIR.'agent/private-office/')) {
    global $USER; if ($USER->IsAuthorized()) {
        if (CSite::InGroup ([26, 11, 12, 13, 14, 25])) LocalRedirect('/partners/');
    }
}
if(CSite::InDir(SITE_DIR.'partners/')) {
    global $USER; if ($USER->IsAuthorized()) {
        if (CSite::InGroup ([28, 9])) LocalRedirect('/agent/private-office/');
        if (CSite::InGroup ([30, 31])) LocalRedirect('/agentIHWC/private-office/');
    }
}
?>
<!doctype html>
<html lang="<?= LANGUAGE_ID ?>">
    <head>

<?if (LANGUAGE_ID=='by'):?>
<meta property="og:image" content="/local/templates/travelsoft/images/vet_logo_by.png" />
<?elseif (LANGUAGE_ID=='en'):?>
<meta property="og:image" content="/local/templates/travelsoft/images/vet_logo_com.png" />
<?else:?>
<meta property="og:image" content="/local/templates/travelsoft/images/vet_logo_ru.png" />
<?endif;?>
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
        <? /*$APPLICATION->AddHeadString('<link href="'.SITE_TEMPLATE_PATH.'/css/fonts.min.css"  type="text/css" rel="preload" />',true)*/ ?>
        <? /*$APPLICATION->AddHeadString('<link href="'.SITE_TEMPLATE_PATH.'/css/font-awesome.min.css"  type="text/css" rel="preload" />',true)*/ ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/fonts.min.css"); ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/font-awesome.min.css"); ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/bootstrap.min.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/jquery-ui.min.css") ?>
		<? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/jquery.arcticmodal.min.css") ?>
		<? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/arctic-themes/simple.min.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/owl.carousel.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/slick-slider.min.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/slick-theme.min.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/jquery.fancybox.min.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/style.min.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/grid.module.css") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/tscustom.min.css?v=03042018") ?>
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/slider_new_style.css") ?>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/v4-shims.css">
        <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/css/main.css") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery-1.11.0.min.js") ?>

        <? //$oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery3.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery-ui.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/bootstrap.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.fancybox.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/script.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/owl.carousel.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/slick.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/parallax.min.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.nicescroll.min.js") ?>
		<? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.matchHeight.js") ?>
        <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.ui.touch-punch.min.js") ?>
		
		<?if ($APPLICATION->GetCurPage(false) === '/'): ?>
			<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/owl.carousel.min_new.js"></script>
			<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/owl.video.js"></script>
		<?else:?>
	
			<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/owl.carousel.min.js"></script>
			<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/css/owl.carousel.min_old.css">

		<? endif;?>


<? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.mb.YTPlayer.min.js") ?>
<? // $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/SmoothScroll.js") ?> 
<? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/statistic_.min.js") ?>
<!--Подключение плагина arctic modal -->
<? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.arcticmodal.min.js") ?>
<script src="//yandex.st/jquery/cookie/1.0/jquery.cookie.min.js"></script>
<? $oAsset->addJs(SITE_TEMPLATE_PATH . "/js/custom.js") ?>


<?php if($GLOBALS["USER"]->IsAuthorized()):?>
    <script>
        var stUserId = '<?=$GLOBALS["USER"]->getId();?>';
        var dataLayer = [];
        dataLayer.push({'UserId': stUserId});
    </script>

<?php endif;?> 
<?if($_SERVER['SERVER_NAME'] == 'vetliva.ru'):?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PJTKD39');</script>
<!-- End Google Tag Manager -->
<?elseif($_SERVER['SERVER_NAME'] == 'vetliva.by'):?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PJTKD39');</script>
<!-- End Google Tag Manager -->
<?elseif($_SERVER['SERVER_NAME'] == 'vetliva.com'):?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KBBHD7Q');</script>
<!-- End Google Tag Manager -->
<?endif?> 


<?// подключение языкового файла для js
$ARJSMESS = Loc::LoadLanguageFile($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/header.php");
if (!empty($ARJSMESS)) {
    // Добавляем объект с переводами.
    $oAsset->AddString("<script type=\"text/javascript\">BX.message(".CUtil::PhpToJSObject($ARJSMESS).")</script>");
}
?>
        <script>
            $('.header .logotype').css('background-image', 'url(<?= SITE_TEMPLATE_PATH ?><?= Loc::getMessage("LOGO") ?>)');
        </script>
    </head>
	<body <? if (!empty($APPLICATION->GetDirProperty("IMG_BG_BODY"))): ?>style="background-image: url('<?= $APPLICATION->GetDirProperty("IMG_BG_BODY"); ?>');background-repeat: no-repeat;"<? endif; ?> class="body-<?= LANGUAGE_ID ?> <? if ($APPLICATION->GetCurPage(false) === '/'): ?> index-body <?endif;?> <?if(CSite::InDir('/booking/')):?>booking-body<?endif;?>">
		<? //include_once ($_SERVER['DOCUMENT_ROOT'] . "/metrics.php"); ?>
<?if($_SERVER['SERVER_NAME'] == 'vetliva.ru'):?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PJTKD39"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?elseif($_SERVER['SERVER_NAME'] == 'vetliva.by'):?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PJTKD39"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?elseif($_SERVER['SERVER_NAME'] == 'vetliva.com'):?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KBBHD7Q"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?endif?>
<!-- check templates --><div id="check_templates_1"></div>
<!-- Уведомление о куках -->
<template id="cookies-notify">
  <div class="box-modal" id="boxUserFirstInfo">
    <div class="box-modal_close arcticmodal-close">ОК</div>
    <?=Loc::getMessage("COOKIE_MSG"); ?>
  </div>
</template>


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

<script>
    window.Statistics = new Statistic(<?= $metric_lang?>,"<?=$id_goal?>","/","<?=$url_statistic?>");
    window.Statistics.cookieCheck();
</script>


        <!-- Wrap -->
      <div id="wrap" class="<?if((CSite::InDir('/belarus/shoping/')) 
								|| (CSite::InDir('/belarus/cafes-and-restaurants/')) 
								|| (CSite::InDir('/belarus/sport/sport-complexes/')) 
								|| (CSite::InDir('/belarus/poster/')) 
								|| (CSite::InDir('/belarus/blog/'))):?>show-filters<?endif;?> <?if(CSite::InDir('/belarus/')):?>belarus-wrap<?endif;?> <?if(CSite::InDir('/belarus/news/')):?>news-wrap<?endif;?> <?if(CSite::InDir('/belarus/getting-there/')):?>getting-wrap<?endif;?> ">
            <!-- Header -->
			<!--<div class="overlay"></div>-->
            <header id="header" class="header">

              <!-- <div class="container top-search-form-header-wrapper" id="top-search-form-header-wrapper">
                <div class="top-search-form-header">
                  <form id="search__by__name_top" action="/search" method="get">
                    <input id="search_top" type="text" name="q" placeholder="<?= Loc::getMessage("WHAT_SEARCH") ?>?" autocomplete="off">
                    <button><i class="fa fa-search" aria-hidden="true"></i></button>
                  </form>
                </div>
              </div> -->
              
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
							 
							         
                                     <?$APPLICATION->IncludeComponent(
                                        		"bitrix:main.include",
                                        		"",
                                        		Array(
                                        			"AREA_FILE_SHOW" => "file",
                                        			"PATH" => SITE_DIR."/include/header_phones.php"
                                        		)
                                        );
                                      ?>
						
                           <?/* <li class="mobile-lang">
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
                                
                            </li>*/?>
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
										if (check_smartphone()) $level = 3;
										else $level = 2;
										$APPLICATION->IncludeComponent(
                                         "bitrix:menu", "top", array(
                                        "ROOT_MENU_TYPE" => "top",
                                        "MAX_LEVEL" => $level,
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
									
									
									
									
								<ul class="sub-menu lang">
                                                <li>
                                                    <input id="langru" type="radio" <?if (Loc::getMessage("LANG")=='/images/ru20.png'):?>checked=""<?endif;?> onclick="location.href='https://vetliva.ru<?= $_SERVER['REQUEST_URI'] ?>'"/>
                                                    <label for="langru"> <span>русский</span></label>
                                                </li>
                                                <li>
                                                    <input id="langby" type="radio" <?if (Loc::getMessage("LANG")=='/images/by20.png'):?>checked=""<?endif;?> onclick="location.href='https://vetliva.by<?= $_SERVER['REQUEST_URI'] ?>'"/>
                                                    <label for="langby"> <span>беларуская</span></label>
                                                </li>
                                                <li>
                                                    <input id="langen" type="radio" <?if (Loc::getMessage("LANG")=='/images/en20.png'):?>checked=""<?endif;?> onclick="location.href='https://vetliva.com<?= $_SERVER['REQUEST_URI'] ?>'"/>
                                                    <label for="langen"><span>english</span></label>
                                                </li>
                                                <li>
                                                    <input id="langch" type="radio"  onclick="window.open('http://belarustrip.by','_blank');"/>
                                                    <label for="langch"><span>中文</span></label>
                                                </li>
                                                <li>
                                                    <input id="langar" type="radio"  onclick="window.open('http://ar.vetliva.com','_blank');"/>
                                                    <label for="langar"> <span>العربية</span></label>
                                                </li>
                                            </ul>
									<ul class="auth-list">					
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
                                <!-- Switches -->
                                <div class="flow-switches">
                                    <ul class="menu-parent">
                                       <?
                                            $APPLICATION->IncludeComponent(
                                        		"bitrix:main.include",
                                        		"",
                                        		Array(
                                        			"AREA_FILE_SHOW" => "file",
                                        			"PATH" => SITE_DIR."/include/header_phones.php"
                                        		)
                                        	);
                                        ?>
										
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
                                    <?$APPLICATION->IncludeComponent(
                                      "vetliva:exchange_rates_list",
                                      "vidget",
                                      Array('VIDGET'=>'Y')
                                    );?>
                                    
                                    
                                </div>
                                <!-- End switches -->
                            </div>
                        </div>

                    </nav>
                    <script>
                        MenuResponsive();
                    </script>
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
                        <div class="main-cn content-page bg-white clearfix <?if(CSite::InDir('/belarus/poster/')):?>detail-cn <?endif;?>">
                            <!--Breakcrumb-->
                            <section class="breakcrumb-sc">
								<? if ($APPLICATION->GetDirProperty("SHOW_PHONE") == "Y"): ?>
                                <div class="support float-right">
                                    <small><?= Loc::getMessage("HAVEAQUESTION") ?></small> <?= Loc::getMessage("PHONE") ?>
                                </div>
								<?endif;?>
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
                            </section>
                                <?php include_once $_SERVER['DOCUMENT_ROOT'].'/include/search_filter.php' ?>
                            <!--End Breakcrumb-->
							<h1 class="mobile-h1"><?= $APPLICATION->ShowTitle(false) ?><?= $APPLICATION->ShowViewContent('cnt__elements_header') ?></h1>
								<? if ($APPLICATION->GetDirProperty("NOT_SHOW_SIDEBAR") != "Y"): ?>
                                <div class="col-md-3 col-md-push-0">
                                    <div class="sidebar-cn <?if(CSite::InDir('/private-office/')):?>private-office-menu<?endif;?> <?if((CSite::InDir('/belarus/made-in-belarus/')) || (CSite::InDir('/belarus/getting-there/'))):?>made-in-sidebar<?endif;?> <?if((CSite::InDir('/belarus/getting-there/')) || (CSite::InDir('/belarus/news/'))):?>getting-sidebar<?endif;?>">
									
									<? if ($APPLICATION->GetDirProperty("NOT_SHOW_HIDE_MENU_BUTTON") != "Y"): ?>
                                      <? if (CSite::InGroup ([28, 9])): ?>  <div class="active aside-menu active" data="toogle-menu"><span class="ts-d-lg-none"><span/> </div><?endif;?>
                                        <div class="sidebar-cn <?if (check_smartphone()):?>not-active <?else:?> active <?endif;?> <?if(CSite::InDir('/belarus/made-in-belarus/')):?>made-in-sidebar<?endif;?>" data="collapse" >
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
                                        <?if($APPLICATION->GetProperty("SHOW_FEEDBACK") == 'Y' && $USER->IsAuthorized()):?>
                                            <div class="feedback-wrap">
                                                <?$APPLICATION->IncludeComponent(
                                                    "cit:main.feedback",
                                                    "",
                                                    Array(
                                                        "EMAIL_TO" => "support@vetliva.com",
                                                        "EVENT_MESSAGE_ID" => array("123"),
                                                        "OK_TEXT" => "Спасибо, ваше сообщение принято.",
                                                        "REQUIRED_FIELDS" => array("MESSAGE"),
                                                        "USE_CAPTCHA" => "N"
                                                    )
                                                );?>

                                                <?  $APPLICATION->IncludeComponent("bitrix:main.include", "",
                                                    Array(
                                                        "AREA_FILE_SHOW" => "file",
                                                        "PATH" => "/local/include/left_side_bar_phones.php"
                                                    )
                                                );?>
                                            </div>
                                        <?endif?>	
									<? if ($APPLICATION->GetDirProperty("NOT_SHOW_HIDE_MENU_BUTTON") != "Y"): ?>
                                        </div>
									<? endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-9 col-md-pull-0 content-page-detail">
                                     <div class="wrp-ttl">
									
									<?if(CSite::InDir('/belarus/poster/')):?>
									
									    <h1><?= $APPLICATION->ShowTitle(false) ?></h1> 
									<?else:?>
									
										<h1 id="header_name"><?= $APPLICATION->ShowTitle(false) ?></h1>
									
									<?endif;?>
                                      
                                        <?if((!CSite::InDir('/belarus/getting-there/')) && (!CSite::InDir('/belarus/news/')) && (!CSite::InDir('/belarus/made-in-belarus/')) && (!CSite::InDir('/belarus/blog/')) && (!CSite::InDir('/belarus/poster/'))):?> <?php $APPLICATION->ShowViewContent('map-link')?> <?endif;?>
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

                                                if (clickNode && collapseNode) {
                                                    let collapseNav = new CollapseNav(clickNode,collapseNode,[`<?= Loc::getMessage('MENU_LANG_3') ?>`,`<?= Loc::getMessage('MENU_LANG_4') ?>`]);
                                                    collapseNav.collapseClick();       
                                                }
                                            }

                                        })

                                    </script>
									<? endif; ?>
<script>
  $(window).bind('scroll', function () {
    if (pageYOffset > 120) {
      $('#top-search-form-header-wrapper').slideUp();
    } else {
      $('#top-search-form-header-wrapper').slideDown();
    }
    
  });
  // 
</script>
  <div class="container">
    <?php include_once $_SERVER['DOCUMENT_ROOT'].'/include/search_filter.php' ?>
  </div>
