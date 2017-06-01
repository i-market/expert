<?
require_once __DIR__.'/CalculatorMacros.php';

use App\View as v;
use App\Templates\CalculatorMacros as macros;

$macros = new macros($state);
?>
<section class="calculator_certain_types">
    <div class="wrap">
        <div class="wrap_title">
            <h2>On-line калькулятор</h2>
        </div>
    </div>
    <div class="calculator_certain_types_top">
        <div class="wrap">
            <div class="top">
                <p><?= $heading ?></p>
            </div>
            <div class="middle">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_RECURSIVE" => "Y",
                        "AREA_FILE_SHOW" => "sect",
                        "AREA_FILE_SUFFIX" => 'calculator_form_help',
                        "EDIT_TEMPLATE" => "",
                        "PATH" => ""
                    )
                ); ?>
            </div>
        </div>
    </div>
    <div class="calculator_content">
        <div class="wrap">
            <div class="left_side">
                <? $macros->showTextarea('DESCRIPTION', 'Описание объекта(ов) мониторинга', ['required' => true]) ?>
                <? $macros->showSelect('LOCATION', [['value' => 42, 'text' => 'some text']], 'Местонахождение', ['required' => true]) ?>
                <? $macros->showTextarea('ADDRESS', 'Адрес(а)') ?>
                <? // TODO this should be a select ?>
                <? $macros->showInput('SITE_COUNT', 'Количество объектов мониторинга', ['required' => true]) ?>
                <? // TODO reveal ?>
                <? $macros->showOptionalSelect('DISTANCE_BETWEEN_SITES', [['value' => 42, 'text' => 'some text']], 'Удаленность объектов друг от друга', ['required' => true]) ?>
                <? $macros->showSelect('USED_FOR', [['value' => 42, 'text' => 'some text']], 'Назначение объекта(ов) мониторинга', ['required' => true]) ?>
                <? $macros->showInput('TOTAL_AREA', 'Общая площадь объекта(ов), кв.м', ['required' => true]) ?>
                <? $macros->showInput('VOLUME', 'Строительный объем объекта(ов), куб.м') ?>
                <? // TODO unclear ?>
                <div class="wrap_calc_item">
                    <p class="title">Количество надземных этажей <span class="red">*</span></p>
                    <div class="inner inner_some">
                        <div class="left">
                            <span class="text">Строение&nbsp;1</span>
                            <input type="text" value="от 1 до 30">
                        </div>
                        <div class="right">
                            <span class="tooltip" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias omnis eveniet dolorem maxime architecto fuga perspiciatis illo, voluptatibus numquam vel similique iste pariatur placeat nobis assumenda soluta voluptas aliquid laudantium."></span>
                        </div>
                    </div>
                    <div class="inner inner_some">
                        <div class="left">
                            <span class="text">Строение&nbsp;2</span>
                            <input type="text" value="от 1 до 30">
                        </div>
                    </div>
                    <div class="inner inner_some">
                        <div class="left">
                            <span class="text">Строение&nbsp;3</span>
                            <input type="text" value="от 1 до 30">
                        </div>
                    </div>
                </div>
                <div class="wrap_calc_item">
                    <p class="title">Наличие подполья, подвала, подземных этажей</p>
                    <div class="inner">
                        <div class="left left--radio hidden_block">
                            <input type="radio" hidden="hidden" name="family" id="some_111" data-name="some_block" class="open_block">
                            <label for="some_111" class="radio_label">Да</label>
                            <input type="radio" hidden="hidden" checked name="family" id="some_222" data-name="some_block">
                            <label for="some_222" class="radio_label">Нет</label>
                        </div>
                        <div class="right">
                            <span class="tooltip" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias omnis eveniet dolorem maxime architecto fuga perspiciatis illo, voluptatibus numquam vel similique iste pariatur placeat nobis assumenda soluta voluptas aliquid laudantium."></span>
                        </div>
                    </div>
                </div>
                <div class="wrap_calc_item_block some_block">
                    <div class="top">
                        <p class="title">Количество подземных этажей <span class="red">*</span></p>
                    </div>
                    <select name="" id="">
                        <option value="">более 3х</option>
                        <option value="">более 4х</option>
                        <option value="">более 5х</option>
                    </select>
                </div>
                <div class="wrap_calc_item">
                    <p class="title">Цели мониторинга <span class="red">*</span></p>
                    <div class="inner">
                        <div class="left">
                            <select name="" id="">
                                <option value="">Какое-то длинное описание, которое не поместится наверняка</option>
                                <option value="">Текст</option>
                                <option value="">Текст</option>
                                <option value="">Текст</option>
                                <option value="">Текст</option>
                            </select>
                        </div>
                        <div class="right">
                            <span class="tooltip" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias omnis eveniet dolorem maxime architecto fuga perspiciatis illo, voluptatibus numquam vel similique iste pariatur placeat nobis assumenda soluta voluptas aliquid laudantium."></span>
                        </div>
                    </div>
                </div>
                <div class="wrap_calc_item">
                    <p class="title">Продолжительность мониторинга <span class="red">*</span></p>
                    <div class="inner">
                        <div class="left">
                            <select name="" id="">
                                <option value="">Какое-то длинное описание, которое не поместится наверняка</option>
                                <option value="">Текст</option>
                                <option value="">Текст</option>
                                <option value="">Текст</option>
                                <option value="">Текст</option>
                            </select>
                        </div>
                        <div class="right">
                            <span class="tooltip" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias omnis eveniet dolorem maxime architecto fuga perspiciatis illo, voluptatibus numquam vel similique iste pariatur placeat nobis assumenda soluta voluptas aliquid laudantium."></span>
                        </div>
                    </div>
                </div>
                <div class="wrap_calc_item">
                    <p class="title">Транспортная доступность <span class="red">*</span></p>
                    <div class="inner">
                        <div class="left">
                            <select name="" id="">
                                <option value="">Какое-то длинное описание, которое не поместится наверняка</option>
                                <option value="">Текст</option>
                                <option value="">Текст</option>
                                <option value="">Текст</option>
                                <option value="">Текст</option>
                            </select>
                        </div>
                        <div class="right">
                            <span class="tooltip" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias omnis eveniet dolorem maxime architecto fuga perspiciatis illo, voluptatibus numquam vel similique iste pariatur placeat nobis assumenda soluta voluptas aliquid laudantium."></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right_side">
                <div class="wrap_calc_item">
                    <div class="inner">
                        <div class="left">
                            <p class="title">Конструкции подлежащие мониторингу: <span class="red">*</span></p>
                        </div>
                        <div class="right">
                            <span class="tooltip" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias omnis eveniet dolorem maxime architecto fuga perspiciatis illo, voluptatibus numquam vel similique iste pariatur placeat nobis assumenda soluta voluptas aliquid laudantium."></span>
                        </div>
                    </div>
                </div>
                <div class="wrap_calc_item hidden_block">
                    <input type="radio" hidden="hiden" checked name="family_2" id="some_3" data-name="some_block_2">
                    <label for="some_3" class="radio_label">Комплексный мониторинг состояния строительных конструкций, зданий и сооружений</label>
                    <input type="radio" hidden="hiden" name="family_2" id="some_4" data-name="some_block_2" class="open_block">
                    <label for="some_4" class="radio_label">Выборочное обследование</label>
                </div>
                <div class="wrap_calc_item_block wrap_calc_item_block--checkbox some_block_2">
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния фундаментов</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния технических подпольев, цокольных помещений, подвальных помещений, подземных гаражей и стоянок</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Комплексный мониторинг состояния полов выполненных по грунтовому основанию (бетонных, железобетонных, фибробетонных)</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния стен, колонн, пилонов и пр.</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния окон, дверей, витражных и светопрозрачных конструкций</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния перекрытий, лестничных площадок и маршей, покрфтий</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния конструкций кровли</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния бассейнов, резервуаров</label>
                    </div>
                </div>
                <div class="wrap_calc_item">
                    <p class="title">Наличие документов: <span class="red">*</span></p>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Результаты выполненых обследований или экспертиз</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Результаты ранее проведенного мониторинга</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Результаты гидрогеологических изысканий</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Проектная документация</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Рабочая документация</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Планы БТИ</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="calculator_content_robot">
        <div class="wrap_robot_block">
            <?= v::render('partials/form_error_message', [
                'errors' => $state['errors'],
                'action' => 'рассчитать стоимость'
            ]) ?>
            <?= v::render('partials/form_loader') ?>
            <button type="submit" class="big_btn">
                <span class="text"><span>Выполнить расчет</span></span>
                <span class="img">
    <img src="<?= v::asset('images/calc.svg') ?>" alt="">
  </span>
            </button>
        </div>
    </div>
    <div class="total_price_block">
        <div class="inner">
            <div class="block">
                <div class="total_price">
                    <p>Стоимость работ: <span>150 000 руб/мес</span></p>
                    <p>Продолжительность выполнения работ: <span>18 месяцев</span></p>
                </div>
                <h4>Получите коммерческое предложение на почту</h4>
                <div class="commercial_proposal">
                    <input type="text" placeholder="Введите ваш E-mail">
                    <label>
                        <button type="submit">Получить предложение</button>
                        <span class="ico"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</section>
