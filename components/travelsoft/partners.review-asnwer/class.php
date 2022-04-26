<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/**
 * Ответ партнера на отзыв
 */
class PartnersReviewAnswer extends CBitrixComponent {

    protected $_review = null;
    
    public function prepareParameters() {

        global $USER;

        if (!$USER->IsAuthorized()) {
            throw new Exception('Пожалуйста, авторизуйтесь.');
        }

        if ($this->arParams['REVIEW_ID'] <= 0) {
            throw new Exception('ID отзыва не указан.');
        }
        
        if (!$this->arParams['ANSWER_IBPROPERTY_CODE']) {
            throw new Exception('Не указан код свойства ответа.');
        }

        Bitrix\Main\Loader::includeModule('iblock');

        $this->_review = CIBlockElement::GetByID($this->arParams['REVIEW_ID'])->GetNextElement();
        if (!$this->_review) {
            throw new Exception('Отзыв не найден.');
        }

        // TODO: принадлежность отзыва обекту партнера!
    }

    /**
     * Только для ajax запроса
     * @throws Exception
     */
    public function processingRequest() {
        global $USER;
        try {

            $this->prepareParameters();
            if (!($this->request->isPost() && $this->request->isAjaxRequest() && check_bitrix_sessid())) {
                throw new Exception('Ваша сессия истекла. Пожалуйста, авторизуйтесь еще раз и попробуйте снова.');
            }

            $answer = trim(strip_tags($this->request->getPost('answer')));
            if (!$answer) {
                throw new Exception('Поле ответа не может быть пустым.');
            }
            
            CIBlockElement::SetPropertyValuesEx($this->arParams['REVIEW_ID'], false, [
                $this->arParams['ANSWER_IBPROPERTY_CODE'] => ['VALUE' => ['TEXT' => $answer, 'TYPE' => 'HTML']]
            ]);
            
            $properties = $this->_review->GetProperties();
            $fields = $this->_review->GetFields();
            $user = [];
            if ($properties['USER']['VALUE'] > 0) {
                $user = CUser::GetByID($properties['USER']['VALUE'])->Fetch();
            }
            
            CEvent::Send('ADD_REVIEWS', SITE_ID, [
                'EMAIL_TO' => $user['EMAIL'],
                'USER_NAME' => $USER->GetFullName(),
                'MESSAGE' => $fields['PREVIEW_TEXT'],
                'ANSWER' => $answer
            ], 'N', $this->arParams['MESSAGE_ID']);
            
            echo json_encode(['error' => false]);
        } catch (Exception $ex) {
            echo json_encode(['error' => true, 'message' => "partners.review-answer: {$ex->getMessage()}"]);
        }
        
        die();
    }

    public function executeComponent() {

        try {

            $this->prepareParameters();
            CJSCore::Init(['ajax']);
            $this->includeComponentTemplate();
        } catch (Exception $ex) {
            ShowError("partners.review-answer: {$ex->getMessage()}");
        }
    }

}
