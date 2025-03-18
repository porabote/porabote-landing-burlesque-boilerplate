// const captcha_key_site_v2 = '6LchN_IqAAAAAAI7L7VeU63csc5_SW63nbGbxF_o';
const captcha_key_site_v3 = '6LchN_IqAAAAAAI7L7VeU63csc5_SW63nbGbxF_o';

window.getTokenV3 = async () => {
    return await validateCaptcha(captcha_key_site_v3).then(token => { // Here wait token generated
        if(token) {
            return token;
        }
    })
}

function validateCaptcha(key) {
    return new Promise((res, rej) => {
        grecaptcha.ready(() => {
            grecaptcha.execute(key, {action: 'homepage'}).then((token) => {
                return res(token);
            })
        })
    })
}


// window.captchaChecker = async (form, backendFormHandlerUrl, successHandler) => {
//
//     // const captcha_key_site_v2 = '6LchN_IqAAAAAAI7L7VeU63csc5_SW63nbGbxF_o';
//     const captcha_key_site_v3 = '6LchN_IqAAAAAAI7L7VeU63csc5_SW63nbGbxF_o';
//
//     let widgetCaptcha = false;
//
//     function sendData(formData) {
//         $.ajax({
//             url: backendFormHandlerUrl,
//             type: 'POST',
//             data: formData,
//             success: function (resp) {
//                 let responseJSON = JSON.parse(resp);
//
//                 if (responseJSON.success) {
//                     successHandler(resp);
//                 } else if (responseJSON.error) {
//
//                     // если была ошибка капчи, сбрасываем капчку v2 при наличии
//                     if (widgetCaptcha !== false) {
//
//                         grecaptcha.reset(widgetCaptcha)
//                     }
//                     // если ошибка была в версии v3, показываем видимую капчу v2
//                     // widgetCaptcha - идентификатор, т.е. можно рендерить и управлять несколькими штуками
//                     if (responseJSON.error === 'fall_captcha_v3' && !widgetCaptcha) {
//
//                         widgetCaptcha = grecaptcha.render('captcha', {
//                             'sitekey': captcha_key_site_v2,
//                             'theme': 'dark',
//                             'callback': setTokenV2,
//                             'data-size': "compact",
//                         });
//                         return;
//                     }
//                 }
//
//             }
//         });
//     }
//
//     // функция-колбек добавляет полученный токен второй версии капчи в скрытое поле формы
//     function setTokenV2(token) {
//         form.find('input[name="captcha_token_v2"]').val(token)
//         //submitSubscribeFooterForm()
//     }
//
//     return {
//         getTokenV3,
//     };
// }