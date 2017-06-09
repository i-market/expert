<div id="global-error-message" style="display: none">
    <strong class="heading">Что-то пошло не так.</strong><br> Похоже, что в нашей системе что-то сломалось.
    <? if ($sentry['enabled']): ?>
        Не паникуйте — мы отправили письмо разработчикам с информацией о том, что произошло.
    <? elseif ($adminEmailMaybe !== null): ?>
        Пожалуйста, <a href="<?= "mailto:{$adminEmailMaybe}" ?>">напишите нам письмо</a> с описанием того, что вы пытались сделать, когда произошла эта ошибка.
    <? endif ?>
    Пока мы исправляем ошибку, вы можете попробовать <a href="javascript:window.location.reload(true)">обновить страницу</a> и повторить действие.
</div>
