

if (!window.sendPartnersReviewAnswer) {
    window.sendPartnersReviewAnswer = (e) => {
        let answer_input = e.target.querySelector('textarea[name=answer]');
        e.preventDefault();
        BX.showWait();
        BX.ajax({
            url: e.target.action,
            method: 'POST',
            data: {
                sessid: BX.bitrix_sessid(),
                review_id: e.target.querySelector('input[name=review_id]').value,
                answer: answer_input.value
            },
            processData: true,
            onsuccess: (response) => {
                let alert_block = e.target.querySelector('.alert-success');
                response = JSON.parse(response);
                BX.closeWait();
                if (response.error) {
                    alert(response.message);
                    return;
                }
                answer_input.value = '';
                alert_block.classList.remove('hidden');
                alert_block.innerText = 'Ответ успешно сохранен. После перезагрузки страницы Вы сможете его увидеть.';
                e.target.querySelector('button[name=save_answer]').disabled = true;
                e.target.closest('.review-answer').querySelector('.review-answer__btn').disabled = true;
            },
            onfailure: () => {
                BX.closeWait();
            }
        });
    };
}