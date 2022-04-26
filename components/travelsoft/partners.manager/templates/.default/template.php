
<? if (!$arParams['IS_AJAX']): ?>
    <div class="panel panel-flat">
        <div class="white-area">
            <div class="partners-manager">
                <div class="partners-manager-breadcrumbs"></div>
                <div class="form-group partners-manager-selection-provider">
                </div>
                <div class="partners-manager-data"></div>
                <div class="partners-manager-preloader hidden">
                    <img src="<?= $templateFolder ?>/preloader.gif">
                </div>
            </div>
        </div>
    </div>
    <script>
        var partnersManager = new VetlivaPartnersManager({
            'ajax-url': '<?= $templateFolder?>/ajax.php',
            'partners.manager:actions-data-store': <?= json_encode($arResult['partners.manager:actions-data-store']) ?>,
            'breadcrumbs-container': document.querySelector('.partners-manager-breadcrumbs'),
            'selection-provider-container': document.querySelector('.partners-manager-selection-provider'),
            'action-container': document.querySelector('.partners-manager-data'),
            'preloader-container': document.querySelector('.partners-manager-preloader')
        });
    </script>
    <?
else:

    header('Content-Type: application/json');

    include "views/{$arResult['current-action']}.php";
    $action_content = ob_get_clean();

    $APPLICATION->RestartBuffer();

    echo json_encode([
        'errors' => false,
        'action-content' => $action_content
    ]);

    die();

endif;
?>

