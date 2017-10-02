<? use App\View as v; ?>

<button type="submit" class="recaptcha big_btn" data-sitekey="<?= App\App::recaptchaKey() ?>">
    <span class="text"><span>Выполнить расчет</span></span>
    <span class="img">
    <img src="<?= v::asset('images/calc.svg') ?>">
  </span>
</button>
