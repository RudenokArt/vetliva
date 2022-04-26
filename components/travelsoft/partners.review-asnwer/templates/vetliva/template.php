<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>

<div class="review-answer">
    <div class="review-answer__btn_center review-answer__btn_margin">
        <button data-toggle="modal" data-target="#review-answer-modal-<?= $arParams['REVIEW_ID'] ?>" name="save_anwer" type="submit" value="save_anwer" class="btn-lg btn btn-primary review-answer__btn">Ответить</button>
    </div>

    <div id="review-answer-modal-<?= $arParams['REVIEW_ID'] ?>" class="modal fade review-answer__modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Ответ на отзыв</h4>
                </div>
                <form onsubmit="sendPartnersReviewAnswer(event)" action="<?= $templateFolder ?>/ajax.php">
                    <div class="modal-body">

                        <input type="hidden" name="review_id" value="<?= $arParams['REVIEW_ID'] ?>">
                        <div class="form-group">
                            <div class="alert alert-success hidden" role="alert"></div>
                            <label>Текст ответа</label>
                            <textarea rows="10" required name="answer" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <button  name="save_answer" type="submit" value="save_answer" class="btn btn-primary">Ответить</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
