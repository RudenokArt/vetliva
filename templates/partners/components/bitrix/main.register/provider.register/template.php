<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */
/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
  die();
$APPLICATION->AddViewContent('htmlClass', 'login-container');
?>
<?if ($_SESSION["JUST_REGISTER_PROVIDER"]):?>
<div class="panel panel-body login-form">
  <div class="text-center">
    <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
    <h5 class="content-group">Благодарим Вас за регистрацию!</h5>
    <p>После проверки регистрационных данных, Ваш личный кабинет будет активирован в течение 24 часов, а на указанную при регистрации почту будет отправлено соответствующее уведомление.</p>
  </div>
</div>
<?unset($_SESSION["JUST_REGISTER_PROVIDER"]); return; endif; ?>
<!-- Registration form -->
<div class="container">
  <div class="row">
    <div class="col">
      <a href="/partners-information/" class="btn bg-teal-400 button-back">
        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</div>
<form method="post" action="<?= POST_FORM_ACTION_URI ?>" name="bform" id="reg_user_form">
<input type="hidden" name="user_type" value="partner">
  <input type="hidden" name="IS_PROVIDER" value="Y" />
  <input type="hidden" name="backurl" value="/partners/">
  <input type="hidden" name="passwordcorret" value="N">
  <div class="row">
    <div class="col-lg-6 col-lg-offset-3">
      <div class="panel registration-form">
        <div class="panel-body">

          <div class="text-center">
            <div class="icon-object border-success text-success"><i class="icon-plus3"></i></div>
            <h5 class="content-group-lg"><?= GetMessage("AUTH_REGISTER") ?> <small class="display-block"><?= GetMessage('ALL_REQUIRED_FIELDS_TXT') ?></small></h5>
          </div>
          <div class="text-left">
            <?
            foreach ($arResult['ERRORS'] as $key => $error) {
              if (is_numeric($key)) {
                ShowError($error);
              }
            }
            ?>
          </div>
          <div class="content-divider text-muted form-group"><span class="black-color"><?= GetMessage('REGISTER_DATA') ?></span></div>

          <?
          if ($arResult["ERRORS"]["LOGIN"]) {
            $ERROR = $arResult["ERRORS"]["LOGIN"];
          }
          if ($arResult["ERRORS"]["EMAIL"]) {
            $ERROR = $arResult["ERRORS"]["EMAIL"];
          }
          if ($ERROR) {
            echo getRegError($ERROR, GetMessage("AUTH_EMAIL"));
          }
          ?>
          <div class="form-group has-feedback has-feedback-left">
            <input required type="email" placeholder="<?= GetMessage("AUTH_EMAIL") ?>" name="REGISTER[EMAIL]" maxlength="255" value="<?= $arResult["VALUES"]["EMAIL"] ?>" class="form-control" />
            <div class="form-control-feedback">
              <i class="icon-mention text-muted"></i>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <?
              if ($arResult["ERRORS"]["NAME"]) {
                echo getRegError($arResult["ERRORS"]["NAME"], GetMessage("AUTH_NAME"));
              }
              ?>
              <div class="form-group has-feedback">
                <input required type="text" name="REGISTER[NAME]" maxlength="50" value="<?= $arResult["VALUES"]["NAME"] ?>" class="form-control" placeholder="<?= GetMessage("AUTH_NAME") ?>">
                <div class="form-control-feedback">
                  <i class="icon-user-check text-muted"></i>
                </div>
              </div>
            </div>


            <div class="col-md-6">
              <?
              if ($arResult["ERRORS"]["LAST_NAME"]) {
                echo getRegError($arResult["ERRORS"]["LAST_NAME"], GetMessage("AUTH_LAST_NAME"));
              }
              ?>
              <div class="form-group has-feedback">
                <input required type="text" name="REGISTER[LAST_NAME]" maxlength="50" value="<?= $arResult["VALUES"]["LAST_NAME"] ?>" class="form-control" placeholder="<?= GetMessage("AUTH_LAST_NAME") ?>">
                <div class="form-control-feedback">
                  <i class="icon-user-check text-muted"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <script>
                function viewPassword(element) {
                  if (element.closest('.form-group').find('input').attr('type')=='password') {
                    element.closest('.form-group').find('input').attr('type','text');
                    element.attr('class', 'icon-eye text-muted');
                  }
                  else {
                    element.closest('.form-group').find('input').attr('type','password');
                    element.attr('class', 'icon-eye-blocked text-muted');
                  }
                }
              </script>
              <?
              $security = \CUser::GetGroupPolicy([26]);
              if ($arResult["ERRORS"]["PASSWORD"]) {
                echo getRegError($arResult["ERRORS"]["PASSWORD"], GetMessage("AUTH_PASSWORD_REQ", Array ("#LENGTH#" => $security['PASSWORD_LENGTH'])));
              }
              ?>
              <div class="form-group has-feedback">
                <input id="pass" onchange="check_pass()" required pattern="^(.{6,})$" type="password" autocomplete="off" placeholder="<?= GetMessage("AUTH_PASSWORD_REQ", Array ("#LENGTH#" => $security['PASSWORD_LENGTH'])) ?>" name="REGISTER[PASSWORD]" maxlength="50" value="" class="form-control" />
                <div class="form-control-feedback form-control-psw">
                  <i class="icon-eye-blocked text-muted" aria-hidden="true" onClick="viewPassword($(this))"></i>
                  <i class="icon-user-lock text-muted"></i>
                </div>
              </div>
              <div class="passerror-container"></div>
            </div>
            <div class="col-md-6">
              <?
              if ($arResult["ERRORS"]["CONFIRM_PASSWORD"]) {
                echo getRegError($arResult["ERRORS"]["PASSWORD"], GetMessage("AUTH_CONFIRM"));
              }
              ?>
              <div class="form-group has-feedback">
                <input required pattern="^(.{6,})$" type="password" autocomplete="off" placeholder="<?= GetMessage("AUTH_CONFIRM") ?>" name="REGISTER[CONFIRM_PASSWORD]" maxlength="50" value="" class="form-control" />
                <div class="form-control-feedback form-control-psw">
                  <i class="icon-eye-blocked text-muted" aria-hidden="true" onClick="viewPassword($(this))"></i>
                  <i class="icon-user-lock text-muted"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="content-divider text-muted form-group"><span class="black-color"><?= GetMessage('PROVIDER_REGISTER_DATA') ?></span></div>

          <div class="row">
            <div class="col-md-6">
              <?
              if ($arResult["ERRORS"]["UF_LEGAL_NAME"]) {
                echo getRegError($arResult["ERRORS"]["UF_LEGAL_NAME"], GetMessage("AUTH_UF_LEGAL_NAME"));
              }
              ?>
              <div class="form-group">
                <input required type="text" name="REGISTER[UF_LEGAL_NAME]" maxlength="250" value="<?= $arResult["VALUES"]["UF_LEGAL_NAME"] ?>" class="form-control" placeholder="<?= GetMessage("AUTH_UF_LEGAL_NAME") ?>">
              </div>
            </div>

            <div class="col-md-6">
              <?
              if ($arResult["ERRORS"]["WORK_COUNTRY"]) {
                echo getRegError($arResult["ERRORS"]["WORK_COUNTRY"], "Выберите страну");
              }
              ?>
              <div class="form-group">
                <select required name="REGISTER[WORK_COUNTRY]" data-placeholder="Выберите страну" class="select" id="work_country">
                  <option></option>
                  <? foreach ($arResult['COUNTRIES']['reference'] as $key => $country): ?>
                    <option <?if($arResult["VALUES"]['WORK_COUNTRY'] == $arResult['COUNTRIES']['reference_id'][$key]):?>selected<?endif?> value="<?= $arResult['COUNTRIES']['reference_id'][$key] ?>"><?= $country ?></option>
                  <? endforeach ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <?
              if ($arResult["ERRORS"]["UF_LEGAL_ADDRESS"]) {
                echo getRegError($arResult["ERRORS"]["UF_LEGAL_ADDRESS"], GetMessage("UF_LEGAL_ADDRESS"));
              }
              ?>
              <div class="form-group">
                <input required type="text" name="REGISTER[UF_LEGAL_ADDRESS]" maxlength="250" value="<?= $arResult["VALUES"]["UF_LEGAL_ADDRESS"] ?>" class="form-control" placeholder="<?= GetMessage("AUTH_UF_LEGAL_ADDRESS") ?>">
              </div>
            </div>

                        <!--<div class="col-md-6">
                        <? /*
                          if ($arResult["ERRORS"]["WORK_MAILBOX"]) {
                          echo getRegError($arResult["ERRORS"]["WORK_MAILBOX"], GetMessage("AUTH_WORK_MAILBOX"));
                          }
                         */ ?>
                            <div class="form-group">
                                <input required type="email" name="REGISTER[WORK_MAILBOX]" maxlength="250" value="<? /* =$arResult["VALUES"]["WORK_MAILBOX"] */ ?>" class="form-control" placeholder="<? /* =GetMessage("AUTH_WORK_MAILBOX") */ ?>">
                            </div>
                          </div>-->
                          <div class="col-md-6">
                            <?
                            if ($arResult["ERRORS"]["WORK_PHONE"]) {
                              echo getRegError($arResult["ERRORS"]["WORK_PHONE"], GetMessage("AUTH_WORK_PHONE"));
                            }
                            ?>
                            <div class="form-group">
                              <input required type="text" pattern="^\+?[0-9,\s]{0,}$" name="REGISTER[WORK_PHONE]" maxlength="250" value="<?= $arResult["VALUES"]["WORK_PHONE"] ?>" class="form-control" placeholder="<?= GetMessage("AUTH_WORK_PHONE") ?>">
                            </div>
                          </div>
                        </div>

                    <!--<div class="row">
                        <div class="col-md-6">
                    <? /*
                      if ($arResult["ERRORS"]["WORK_PHONE"]) {
                      echo getRegError($arResult["ERRORS"]["WORK_PHONE"], GetMessage("AUTH_WORK_PHONE"));
                      }
                     */ ?>
                            <div class="form-group">
                                <input required type="text" pattern="^\+?[0-9,\s]{0,}$" name="REGISTER[WORK_PHONE]" maxlength="250" value="<? /* =$arResult["VALUES"]["WORK_PHONE"] */ ?>" class="form-control" placeholder="<? /* =GetMessage("AUTH_WORK_PHONE") */ ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="content-divider text-muted form-group"><span class="black-color">Реквизиты</span></div>
                    
                    <div class="row">
                        <div class="col-md-6">
                    <? /*
                      if ($arResult["ERRORS"]["UF_LEGAL_NAME"]) {
                      echo getRegError($arResult["ERRORS"]["UF_LEGAL_NAME"], GetMessage("AUTH_UF_LEGAL_NAME"));
                      }
                     */ ?>
                            <div class="form-group">
                                <input required type="text" name="REGISTER[UF_LEGAL_NAME]" maxlength="250" value="<? /* =$arResult["VALUES"]["UF_LEGAL_NAME"] */ ?>" class="form-control" placeholder="<? /* =GetMessage("AUTH_UF_LEGAL_NAME") */ ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                    <? /*
                      if ($arResult["ERRORS"]["UF_LEGAL_ADDRESS"]) {
                      echo getRegError($arResult["ERRORS"]["UF_LEGAL_ADDRESS"], GetMessage("AUTH_UF_LEGAL_ADDRESS"));
                      }
                     */ ?>
                            <div class="form-group">
                                <input required type="text" name="REGISTER[UF_LEGAL_ADDRESS]" maxlength="250" value="<? /* =$arResult["VALUES"]["UF_LEGAL_ADDRESS"] */ ?>" class="form-control" placeholder="<? /* =GetMessage("AUTH_UF_LEGAL_ADDRESS") */ ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                    <? /*
                      if ($arResult["ERRORS"]["UF_BANK_NAME"]) {
                      echo getRegError($arResult["ERRORS"]["UF_BANK_NAME"], GetMessage("AUTH_UF_BANK_NAME"));
                      }
                     */ ?>
                            <div class="form-group">
                                <input type="text" name="REGISTER[UF_BANK_NAME]" maxlength="250" value="<? /* =$arResult["VALUES"]["UF_BANK_NAME"] */ ?>" class="form-control" placeholder="<? /* =GetMessage("AUTH_UF_BANK_NAME") */ ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                    <? /*
                      if ($arResult["ERRORS"]["UF_BANK_ADDRESS"]) {
                      echo getRegError($arResult["ERRORS"]["UF_BANK_ADDRESS"], GetMessage("AUTH_UF_BANK_ADDRESS"));
                      }
                     */ ?>
                            <div class="form-group">
                                <input type="text" name="REGISTER[UF_BANK_ADDRESS]" maxlength="250" value="<? /* =$arResult["VALUES"]["UF_BANK_ADDRESS"] */ ?>" class="form-control" placeholder="<? /* =GetMessage("AUTH_UF_BANK_ADDRESS") */ ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                    <? /*
                      if ($arResult["ERRORS"]["UF_BANK_CODE"]) {
                      echo getRegError($arResult["ERRORS"]["UF_BANK_CODE"], GetMessage("AUTH_UF_BANK_CODE"));
                      }
                     */ ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="REGISTER[UF_BANK_CODE]" maxlength="250" value="<? /* =$arResult["VALUES"]["UF_BANK_CODE"] */ ?>" class="form-control" placeholder="<? /* =GetMessage("AUTH_UF_BANK_CODE") */ ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                    <? /*
                      if ($arResult["ERRORS"]["UF_CHECKING_ACCOUNT"]) {
                      echo getRegError($arResult["ERRORS"]["UF_CHECKING_ACCOUNT"], GetMessage("AUTH_UF_CHECKING_ACCOUNT"));
                      }
                     */ ?>
                            <div class="form-group">
                                <input type="text" name="REGISTER[UF_CHECKING_ACCOUNT]" maxlength="250" value="<? /* =$arResult["VALUES"]["UF_CHECKING_ACCOUNT"] */ ?>" class="form-control" placeholder="<? /* =GetMessage("AUTH_UF_CHECKING_ACCOUNT") */ ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                    <? /*
                      if ($arResult["ERRORS"]["UF_UNP"]) {
                      echo getRegError($arResult["ERRORS"]["UF_UNP"], GetMessage("AUTH_UF_UNP"));
                      }
                     */ ?>
                            <div class="form-group">
                                <input required type="text" name="REGISTER[UF_UNP]" maxlength="250" value="<? /* =$arResult["VALUES"]["UF_UNP"] */ ?>" class="form-control" placeholder="<? /* =GetMessage("AUTH_UF_UNP") */ ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                    <? /*
                      if ($arResult["ERRORS"]["UF_OKPO"]) {
                      echo getRegError($arResult["ERRORS"]["UF_OKPO"], GetMessage("AUTH_UF_OKPO"));
                      }
                     */ ?>
                            <div class="form-group">
                                <input type="text" name="REGISTER[UF_OKPO]" maxlength="250" value="<? /* =$arResult["VALUES"]["UF_OKPO"] */ ?>" class="form-control" placeholder="<? /* =GetMessage("AUTH_UF_OKPO") */ ?>">
                            </div>
                        </div>
                      </div>-->

                      <div class="content-divider text-muted form-group"><span class="black-color">Тип услуг</span></div>

                      <?
                      $ppg = Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "placements_provider_group");
                      $psg = Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "sanatorium_provider_group");
                      $peg = Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "excursions_provider_group");
                      $ptg = Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "transfers_provider_group");
                      $pgd = Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "guide_group");
                      ?>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="checkbox">
                              <label for="PROVIDER_GROUPS[0]">
                                <input type="checkbox" <? if ($_POST["PROVIDER_GROUPS"][0] == $ppg) echo "checked" ?> name="PROVIDER_GROUPS[0]" value="<?= $ppg ?>" > Объекты размещений
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="checkbox">
                              <label for="PROVIDER_GROUPS[1]">
                                <input type="checkbox" <? if ($_POST["PROVIDER_GROUPS"][1] == $psg) echo "checked" ?> name="PROVIDER_GROUPS[1]" value="<?= $psg ?>" > Санатории
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="checkbox">
                              <label for="PROVIDER_GROUPS[2]">
                                <input type="checkbox" <? if ($_POST["PROVIDER_GROUPS"][2] == $peg) echo "checked" ?> name="PROVIDER_GROUPS[2]" value="<?= $peg ?>" > Экскурсионные туры
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="checkbox">
                              <label for="PROVIDER_GROUPS[3]">
                                <input type="checkbox" <? if ($_POST["PROVIDER_GROUPS"][3] == $ptg) echo "checked" ?> name="PROVIDER_GROUPS[3]" value="<?= $ptg ?>" > Трансферы
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="checkbox">
                              <label for="PROVIDER_GROUPS[4]">
                                <input type="checkbox" <? if ($_POST["PROVIDER_GROUPS"][4] == $pgd) echo "checked" ?> name="PROVIDER_GROUPS[4]" value="<?= $pgd ?>" > Гид
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?
                      /* CAPTCHA */
                      if ($arResult["USE_CAPTCHA"] == "Y") {
                        ?>
                        <div class="content-divider text-muted form-group"><span class="black-color"><?= GetMessage("CAPTCHA_REGF_TITLE") ?></span></div>

                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>" />
                              <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" class="captcha-img" alt="CAPTCHA" />
                            </div>
                          </div>
                          <div class="col-md-8 col-sm-8">
                            <div class="form-group">
                              <input required type="text" name="captcha_word" maxlength="250" value="" class="form-control" placeholder="<?= GetMessage("CAPTCHA_REGF_PROMT") ?>">
                            </div>
                          </div>
                        </div>

                        <?
                      }
                      /* CAPTCHA */
                      ?>

                      <div class="text-right mt-20">
                        <i class="icon-arrow-left13 position-left"></i> <a href="/partners/"><?= GetMessage("AUTH_AUTH") ?></a>
                        <button type="submit" name="register_submit_button" value="<?= GetMessage("AUTH_REGISTER") ?>" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-10"><b><i class="icon-plus3"></i></b> <?= GetMessage("AUTH_REGISTER") ?></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <script type="text/javascript">

              document.bform["REGISTER[EMAIL]"].focus();
              if (typeof jQuery === "function") {
                function check_pass() {
                 var pass = $('#pass').val();
                 if (pass!='') {
                   var formData = new FormData(); 
                   formData.append('pass', pass);
                   var xhrCustom = new XMLHttpRequest();
                   xhrCustom.open("POST", "<?=$templateFolder?>/ajax.php");

                   xhrCustom.onreadystatechange = function() {
                     if (xhrCustom.readyState == 4 && xhrCustom.status == 200) {
                       var dataCheck = xhrCustom.responseText;
                       console.log(dataCheck);
                       var resultCustom = jQuery.parseJSON( dataCheck);
                       console.log(resultCustom);
                       if (resultCustom.length==0) {
                        jQuery("input[name='passwordcorret']").val('Y');
                        $('.passerror-container').html('');
                        $('.passerror-container').hide();
                      }
                      else {
                        jQuery("input[name='passwordcorret']").val('N');
                        var errortext = '<ul>';
                        for (j = 0; j < resultCustom.length; j++) {
                         errortext+='<li>'+resultCustom[j]+'</li>';
                       }
                       errortext+='</ul>';
                       console.log(errortext);
                       $('.passerror-container').html(errortext);
                       $('.passerror-container').show();
                     }
                   }
                 };
                 xhrCustom.send(formData);
               }
             } 
             jQuery("#reg_user_form").submit(function () {
              if (!jQuery("input[name='REGISTER[LOGIN]']").length) {
                jQuery(this).append("<input name='REGISTER[LOGIN]' value='" + jQuery("input[name='REGISTER[EMAIL]']").val() + "' type='hidden'>");
              }
              if (!jQuery("input[name^='PROVIDER_GROUPS[']:checked").length) {
                alert("Пожалуйста укажите тип предоставляемых Вами услуг");
                return false;
              }
              if (jQuery("input[name='passwordcorret']").val()!='Y') {
               $([document.documentElement, document.body]).animate({
                scrollTop: $("#pass").offset().top
              }, 2000);
               $("#pass").focus();

               return false;
             }

             return true;
           });

           }

           $('#reg_user_form').find('input[type="checkbox"]').change(function () {
             var arr = $('#reg_user_form').find('input[type="checkbox"]');
             var arr_provider_groups = [];
             for (var i = 0; i < arr.length; i++) {
               if (arr[i].checked) {
                arr_provider_groups.push(i);
              }
            }
            $.post('/local/templates/partners/components/bitrix/main.register/provider.register/ajax.php', 
              {PROVIDER_GROUPS:arr_provider_groups}, function (data) {
                console.log(data);
              });
          });

           $('#work_country').change(function () {
            $.post('/local/templates/partners/components/bitrix/main.register/provider.register/ajax.php', 
              {WORK_COUNTRY:this[this.selectedIndex].text}, function (data) {
                console.log(data);
              });
          });

        </script>
        <!-- /registration form -->

        <?

        // if (isset($_POST['PROVIDER_GROUPS'])) {
        //   $localStorage = \Bitrix\Main\Application::getInstance()->getLocalSession('PROVIDER_GROUPS');
        //   $arr_provider_groups = [];
        //   $b24_provider_groups = ['2146','2147','2148','2149','2150',];
        //   foreach ($_POST['PROVIDER_GROUPS'] as $key => $value) {
        //     $arr_provider_groups[$key] = $b24_provider_groups[$key];
        //   }
        //   $localStorage->set('PROVIDER_GROUPS',$arr_provider_groups);
        //   print_r($localStorage->get('PROVIDER_GROUPS'));
        // }
// вывод ошибок при регистрации поставщика
        function getRegError($mess, $replace) {
          return "<div class='reg__error'>" . str_replace("#FIELD_NAME#", "&quot;" . $replace . "&quot;", $mess) . "</div>";
        }
        ?>

