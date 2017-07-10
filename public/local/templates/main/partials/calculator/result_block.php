<? use App\View as v; ?>

<? if ($screen !== 'hidden'): ?>
    <div class="total_price_block">
        <div class="inner">
            <div class="block">
                <? if ($screen === 'sent'): ?>
                    <? // TODO improve ux ?>
                    <h4 class="message">Коммерческое предложение отправлено.</h4>
                <? elseif ($screen === 'result'): ?>
                    <div class="total_price">
                        <p>Стоимость работ: <span><?= $result['total_price'] ?></span></p>
                        <? foreach ($result['summary_values'] as $label => $value): ?>
                            <? // TODO lower first letter only ?>
                            <p><?= $label.':' ?> <span><?= v::lower($value) ?></span></p>
                        <? endforeach ?>
                    </div>
                    <h4>Получите коммерческое предложение на почту</h4>
                    <div class="commercial_proposal error">
                        <input type="text" name="EMAIL" value="<?= $params['EMAIL'] ?>" placeholder="Введите ваш E-mail">
                        <label>
                            <? // TODO api uri ?>
                            <button ic-post-to="<?= $apiUri ?>"
                                    ic-select-from-response=".total_price_block"
                                    ic-target="closest .total_price_block"
                                    <? // TODO override inherited value ?>
                                    ic-replace-target="false">
                                Получить предложение
                            </button>
                            <span class="ico"></span>
                        </label>
                        <div class="error-message"><?= v::get($errors, 'EMAIL', '') ?></div>
                    </div>
                <? endif ?>
            </div>
        </div>
    </div>
<? endif ?>
