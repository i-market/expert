<? use App\View as v; ?>

<? if (isset($result['screen'])): ?>
    <div class="total_price_block">
        <div class="inner">
            <div class="block">
                <? if ($result['screen'] === 'sent'): ?>
                    <? // TODO improve ux ?>
                    <h4 class="message">Коммерческое предложение отправлено.</h4>
                <? elseif ($result['screen'] === 'result'): ?>
                    <div class="total_price">
                        <p>Стоимость работ: <span><?= $result['formatted_total_price'] ?></span></p>
                        <p>Продолжительность выполнения работ: <span><?= v::lower($result['duration']) ?></span></p>
                    </div>
                    <h4>Получите коммерческое предложение на почту</h4>
                    <div class="commercial_proposal error">
                        <input type="text" name="EMAIL" value="<?= isset($email) ? $email : '' ?>" placeholder="Введите ваш E-mail">
                        <label>
                            <? // TODO api uri ?>
                            <button ic-post-to="<?= $result['api_uri'] ?>"
                                    ic-target="closest .total_price_block">
                                Получить предложение
                            </button>
                            <span class="ico"></span>
                        </label>
                        <div class="error-message"><?= v::get($result['errors'], 'EMAIL', '') ?></div>
                    </div>
                <? endif ?>
            </div>
        </div>
    </div>
<? endif ?>
