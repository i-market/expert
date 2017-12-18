<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мониторинг технического состояния зданий и сооружений");

use App\Iblock;
?><p align="center">
</p>
<p>
</p>
<p style="text-align: center;">
 <span style="font-size: 15pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">Что такое «мониторинг»? </span></b></span><span style="font-size: 15pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;"> </span></b></span><span style="font-size: 15pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">&nbsp;</span><span style="color: #0054a5;"> &nbsp;</span></b></span>
</p>
<p>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">В соответствии </span><span style="color: #000000; font-size: 12pt;">с ГОСТ 31937-2011 «Здания и сооружения. Правила обследования и мониторинга технического состояния»</span><span style="color: #000000; font-size: 12pt;">:</span><span style="color: #000000; font-size: 12pt;">&nbsp;</span></span>
</p>
<p>
 <span style="color: #000000;"><span style="color: #0054a5;">&nbsp; &nbsp; &nbsp;&nbsp;</span><b><span style="color: #0054a5; font-size: 13pt;">Общий мониторинг технического состояния зданий (сооружений)</span></b> <span style="color: #000000; font-size: 12pt;">- это система наблюдения и контроля, проводимая по определенной программе, утверждаемой заказчиком, для выявления объектов, на которых произошли значительные изменения напряженно-деформированного состояния несущих конструкций или крена, и для которых необходимо обследование их технического состояния (изменения напряженно-деформированного состояния характеризуются изменением имеющихся и возникновением новых деформаций или определяются путем инструментальных измерений). </span></span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;"><b>&nbsp; &nbsp; &nbsp;&nbsp;<span style="color: #0054a5; font-size: 13pt;">Мониторинг технического состояния зданий (сооружений), попадающих в зону влияния строек и природно-техногенных воздействий</span></b> - система наблюдения и контроля, проводимая по определенной программе на объектах, попадающих в зону влияния строек и природно-техногенных воздействий, для контроля их технического состояния и своевременного принятия мер по устранению возникающих негативных факторов, ведущих к ухудшению этого состояния. </span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;"><b>&nbsp; &nbsp; &nbsp;&nbsp;<span style="color: #0054a5; font-size: 13pt;">Мониторинг технического состояния зданий (сооружений), находящихся в ограниченно работоспособном или аварийном состоянии</span></b> - система наблюдения и контроля, проводимая по определенной программе, для отслеживания степени и скорости изменения технического состояния объекта и принятия в случае необходимости экстренных мер по предотвращению его обрушения или опрокидывания, действующая до момента приведения объекта в работоспособное техническое состояние.</span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;"><b>&nbsp; &nbsp; &nbsp;&nbsp;<span style="color: #0054a5; font-size: 13pt;">Мониторинг технического состояния уникальных зданий (сооружений)</span></b> - система наблюдения и контроля, проводимая по определенной программе для обеспечения безопасного функционирования уникальных зданий или сооружений за счет своевременного обнаружения на ранней стадии негативного изменения напряженно-деформированного состояния конструкций и грунтов оснований или крена, которые могут повлечь за собой переход объектов в ограниченно работоспособное или в аварийное состояние. </span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="color: #000000;">&nbsp; &nbsp;&nbsp; &nbsp;<b><span style="color: #0054a5; font-size: 13pt;">Система мониторинга технического состояния несущих конструкций</span></b><span style="font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">- совокупность технических и программных средств, позволяющая осуществлять сбор и обработку информации о различных параметрах строительных конструкций (геодезические, динамические, деформационные и др.) в целях оценки технического состояния зданий и сооружений.&nbsp;</span></span>
</p>
<p style="text-align: center;">
 <span style="color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">Какие нормативно-правовые документы регламентируют работы по ведению мониторинга&nbsp;</span></b></span>
</p>
<p style="text-align: center;">
</p>
<p style="text-align: left;">
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Основными нормативными и правовыми документами регламентирующими работы по проведению мониторинга являются: </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">1. </span><span style="color: #000000; font-size: 12pt;">ГОСТ Р 56198-2014 «Мониторинг технического состояния объектов культурного наследия. Недвижимые памятники. Общие требования»; </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">2. </span><span style="color: #000000; font-size: 12pt;">ГОСТ Р 53778-2010 «Здания и сооружения. Правила обследования и мониторинга технического состояния»;</span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">3. </span><span style="color: #000000; font-size: 12pt;">ГОСТ 32019-2012 «Мониторинг технического состояния уникальных зданий и сооружений. Правила проектирования и установки стационарных систем (станций) мониторинга»; </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">4. </span><span style="color: #000000; font-size: 12pt;">ГОСТ 31937- 2011 «Здания и сооружения. Правила обследования и мониторинга технического состояния»; </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">5. </span><span style="color: #000000; font-size: 12pt;">ПРАВИТЕЬЛСТВО МОСКВЫ. МОСКОМАРХИТЕКТУРА. Рекомендации по обследованию и мониторингу технического состояния эксплуатируемых зданий, расположенных вблизи нового строительства или реконструкции. </span></span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">Основными нормативными и правовыми документами регламентирующими, в каких случаях требуется проведение мониторинга являются: </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">1. Градостроительный кодекс РФ от 29 декабря 2004 г. № 190-ФЗФ, </span><span style="color: #000000; font-size: 12pt;">статья 47; </span></span><br>
 <span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;2. Федеральный закон от 30 декабря 2009 г. N 384-ФЗ «Технический регламент о безопасности зданий и сооружений», </span><span style="color: #000000; font-size: 12pt;">статья 15; </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">3. Постановление Правительства РФ от 16 февраля 2008 г. № 87 «О составе разделов проектной документации и требованиях к их содержанию», </span><span style="color: #000000; font-size: 12pt;">п. 10 п.п. б; п. 11; </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">4. ВСН 61-89 (р) «Реконструкция и капитальный ремонт жилых домов. Нормы проектирования.», </span><span style="color: #000000; font-size: 12pt;">п.3, п.п. 3.1.; п.4, п.п. 4.1.1.; </span></span><br>
 <span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;5. МДС 13-14.2000 «Положение проведении планово-предупредительного ремонта производственных зданий и сооружений», </span><span style="color: #000000; font-size: 12pt;">п. 6.9.</span><span style="color: #000000; font-size: 12pt;"> (действует только по г.Москва).</span></span><span style="font-size: 12pt; color: #000000;"><span style="color: #000000;"> </span></span>
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
 <span style="font-size: 12pt; color: #000000;"><br>
 <span style="color: #0072bc;"> </span></span><span style="font-size: 12pt; color: #0072bc;"> </span>
</p>
<p style="text-align: center;">
 <br>
 <span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">Какие нормативно-правовые документы регулируют деятельность организаций выполняющих мониторинг</span></b></span>
</p>
<p style="text-align: center;">
</p>
<p>
</p>
<p>
</p>
<p>
</p>
<p style="text-align: left;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: left;">
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">Основн</span><span style="color: #000000; font-size: 12pt;">ыми нормативно-правовыми документами регулирующими деятельность организаций выполняющих мониторинг являются: </span></span><br>
 <span style="color: #000000;"><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">1. Приказ Министерства регионального развития РФ от 30 декабря 2009 г. N 624, </span></span><span style="color: #000000; font-size: 12pt;">раздел 2, п. 12; </span></span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">
	&nbsp; </span><span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">
	&nbsp;2. Градостроительный кодекс РФ от 29 декабря 2004 г. № 190-ФЗФ,</span><span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">статья 47, п.2; </span></span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;3. СП 13-102-2003 «Правила обследования несущих строительных конструкций зданий и сооружений», </span><span style="color: #000000; font-size: 12pt;">п. 4, п.п. 4.1.;</span></span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">&nbsp; </span><span style="color: #000000;"><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp;4. ГОСТ 31937-2011 «Здания и сооружения. Правила обследования и мониторинга технического состоя</span><span style="color: #000000; font-size: 12pt;">ния»,</span></span><span style="color: #000000; font-size: 12pt;"> п. 4, п.п. 4.1.</span></span>
</p>
<p style="text-align: center;">
 <span style="font-size: 12pt; color: #000000;"><span style="color: #0000ff;"><u> </u></span></span>
</p>
<p>
</p>
<p>
</p>
<p>
</p>
<p style="text-align: center;">
</p>
<p>
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
 <br>
 <span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">В каких случаях Вам необходим мониторинг </span></b></span><br>
 <span style="font-size: 12pt; color: #000000;"> </span>
</p>
<p style="text-align: left;">
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Мониторинг проводится в следующих случаях: </span><br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">1. При реконструкции или капитальном ремонте, с целью сбора исходных данных для проектирования зданий и сооружений; </span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;2. При необходимости оценки возможности дальнейшей безаварийной эксплуатации зданий и сооружений, определения необходимости восстановления, усиления, и пр.; </span><br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">3. В случаях наличия значительного износа и, связанных с ним, повреждений, прогибов и прочих дефектов и недостатков зданий, сооружений; </span><br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">4. В случаях расположения зданий и сооружений в зоне влияния строек и природно-техногенных воздействий, с целью установления возможности дальнейшей безопасной эксплуатации; </span><br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">5. Для определения фактического состояния зданий и сооружений уже официально отнесенных к ограниченно работоспособному или аварийному состоянию; </span><br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">6. Для осуществления контроля за состоянием конструкций, в том числе высотных и большепролетных зданий и сооружений, с целью предотвращения катастроф и обрушений; </span><br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">7. При изменении технологического назначения зданий и сооружений с целью установления допустимости эксплуатации в изменившихся условиях; </span><br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">8. При обнаружении, в процессе технического обслуживания зданий и сооружений, значительных дефектов, повреждений и деформаций несвязанных с физическим износом; </span><br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">9. С целью оценки степени воздействия пожаров, стихийных бедствий и аварий на эксплуатационные характеристики зданий и сооружений; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="color: #000000;"> <span style="color: #000000; font-size: 12pt;">&nbsp; </span></span><span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="text-align: center; color: #000000; font-size: 12pt;">10. При выявлении деформаций грунтовых оснований зданий, сооружений;<br>
 </span><span style="text-align: center;"><span style="font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;и пр.</span></span>
</p>
<p>
</p>
<p style="text-align: left;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
 <b><span style="font-size: 24pt; color: #0054a5;">Задачи мониторинга</span></b>
</p>
<p>
</p>
<p>
</p>
<p>
</p>
<p>
</p>
<p>
</p>
<p>
</p>
<div>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; В зависимости от поставленной цели, в задачи мониторинга может входит: </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;1. Выявление наличия и развития недостатков, дефектов и повреждений в конструкциях зданий и сооружений с течением времени; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;2. Контроль за развитием недостатков, дефектов и повреждений строительных конструкций зданий и сооружений в течении времени; &nbsp;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;3. Установление влияния внешних и внутренних факторов и воздействий на появление и развитие недостатков, дефектов и повреждений в конструкциях зданий и сооружений; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;4. Установление влияния и воздействия выявленных недостатков, дефектов и повреждений на эксплуатационные характеристики зданий и сооружений в течении времени; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;5. Контроль изменений физико-механических характеристик конструкций и материалов (прочность, твердость, плотность, влажность, водопроницаемость и пр.) в течении времени; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;6. Контроль за изменением технического состояния, развитием дефектов, повреждений и недостатков в зависимости от изменения фактических нагрузок и воздействий воспринимаемых строительными конструкциями; &nbsp; &nbsp;&nbsp;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;7. Выдача рекомендаций по устранению выявленных дефектов, повреждений и недостатков, а также по дальнейшей эксплуатации зданий и сооружений;<br>
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; &nbsp;и пр.</span><br>
 <br>
</div>
<p style="text-align: center;">
 <span style="font-size: 12pt;"><b><span style="font-size: 24pt; color: #0054a5;">Этапы проведения мониторинга</span></b></span>
</p>
<p style="text-align: left;">
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Мониторинг строительных конструкций, зданий и сооружений проводится, как правило, в несколько связанных между собой этапов:</span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- подготовительные работы; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- визуальное и инструментальное обследование перед началом мониторинга; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- монтажные работы; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- мониторинг. </span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="font-size: 12pt; color: #000000;">Последовательность действий и состав работ на каждом этапе включает: </span><br>
 <span style="font-size: 12pt;"> </span><br>
 <span style="color: #0054a5; font-size: 13pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><b style="font-size: 12pt;"><span style="color: #0054a5; font-size: 13pt;">1. Подготовительные работы:</span><span style="color: #0054a5;">&nbsp;</span><br>
 </b><span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- ознакомление с объектом мониторинга, его объемно-планировочным и конструктивными решениями, материалами инженерно-геологических изысканий и пр.:&nbsp;</span><br>
 </span><span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- подбор и анализ проектно-технической документации;&nbsp;</span><br>
 </span><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- составление программы работ (при необходимости) на основе полученного от заказчика технического задания. Техническое задание разрабатывается заказчиком или проектной организацией и, возможно, с участием исполнителя обследования.&nbsp; </span><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp;</span></span>
</p>
<p style="text-align: left;">
 <span style="font-size: 12pt;"> </span><br>
 <span style="color: #0054a5;">&nbsp; &nbsp; &nbsp;&nbsp;</span><b><span style="color: #0054a5; font-size: 13pt;">2. Визуальное и инструментальное обследование перед началом мониторинга: </span></b><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- инструментальное определение контрольных параметров, выявление недостатков, &nbsp;дефектов и повреждений; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- определение фактических прочностных характеристик материалов основных несущих конструкций и их элементов; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- измерение параметров эксплуатационной среды, присущей технологическому&nbsp;</span><span style="color: #000000; font-size: 12pt;">процессу в здании и сооружении; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- определение фактических эксплуатационных нагрузок и воздействий, воспринимаемых обследуемыми конструкциями с учетом влияния деформаций грунтового основания; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- определение фактической расчетной схемы здания и его отдельных конструкций; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- определение расчетных усилий в несущих конструкциях, воспринимающих эксплуатационные нагрузки; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- расчет несущей способности конструкций по результатам обследования; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- камеральная обработка и анализ результатов обследования и поверочных расчетов; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- анализ причин появления дефектов, повреждений и недостатков; </span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Некоторые из перечисленных работ могут исключаться или добавляться в программу мониторинга в зависимости от специфики объекта мониторинга, его состояния и задач, определенных техническим заданием. </span><br>
 <span style="font-size: 12pt;"> </span><br>
 <span style="color: #0054a5; font-size: 13pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><b><span style="color: #0054a5; font-size: 13pt;">3. Монтажные работы:</span></b><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;Установка контрольно-измерительного оборудования для ведения мониторинга на выбранных участках. </span><br>
 <span style="font-size: 12pt;"> </span><br>
 <span style="color: #0054a5; font-size: 13pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><b><span style="color: #0054a5; font-size: 13pt;">4. Мониторинг: </span></b><br>
 <span style="font-size: 12pt;"> <span style="color: #000000;">&nbsp;&nbsp;</span></span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;"> Систематический к</span><span style="font-size: 12pt; color: #000000;">онтроль состояния строительных конструкций, а также замеры и снятие показаний с установленного контрольно-измерительного оборудования: </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp; - контроль изменений геометрических параметров здания (зданий) или сооружения (сооружений), конструкций, элементов и узлов; </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp; - визуальный и инструментальный контроль параметров выявленных дефектов и повреждений; </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp; - инструментальный контроль характеристик материалов основных несущих конструкций и их элементов; </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp; - контроль изменения параметров эксплуатационной среды, присущей технологическому процессу в здании и сооружении (при необходимости); </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp; &nbsp;- анализ динамики изменения технического состояния конструкций</span><span style="font-size: 13pt; color: #000000;">;</span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp; - составление промежуточных документов&nbsp; с указанием результатов полученных в ходе проведения мониторинга; </span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp; - составление итогового документа о результатах проведенного мониторинга; </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span><span style="color: #000000; font-size: 12pt;">- разработка рекомендаций по устранению выявленных дефектов, повреждений и недостатков выявленных в результате проведенного мониторинга (при необходимости).</span> </span>
</p>
<p style="text-align: center;">
 <br>
 <span style="font-size: 12pt;"> </span><br>
 <span style="font-size: 12pt;"><b><span style="font-size: 24pt; color: #0054a5;">Сроки проведения мониторинга&nbsp;</span></b></span>
</p>
<p style="text-align: left;">
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span><span style="color: #000000; font-size: 12pt;">В зависимости от поставленных задач, специфики объекта, объема работ, а также места расположения объекта, мониторинг, как правило, длиться от 3-х до 24-х месяцев.&nbsp;</span><br>
 </span><span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; </span><span style="color: #000000; font-size: 12pt;">Точно установить продолжительность мониторинга можно только после получения исходных данных.&nbsp;</span></span>
</p>
<p style="text-align: left;">
 <span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; </span><span style="color: #000000; font-size: 12pt;">Узнать сроки выполнения работ можно следующими способами: </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- в режиме online в разделе </span><span style="font-size: 12pt;"><a href="/what-we-do/monitoring/calculator/"><u><span style="color: #0000ff;"><b><span style="color: #0054a5; font-size: 13pt;">«Online определение стоимости и сроков проведения мониторинга»</span></b></span></u></a><span style="color: #0000ff;">; </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- сделать запрос на выполнение обследования в разделе </span><a href="/what-we-do/#modal=request-monitoring"><span style="font-size: 12pt;"><span style="color: #0000ff;"><u><b><span style="color: #0054a5; font-size: 13pt;">«Заявка на проведение мониторинга»</span></b></u>; </span></span></a><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- связавшись с нашими специалистами по телефонам:<span style="color: #000000;"> </span></span><span style="font-size: 12pt;"><b><span style="color: #000000; font-size: 13pt;">+7 (495) 641-70-69</span></b><span style="color: #000000;">; </span></span><span style="font-size: 12pt;"><b><span style="color: #000000; font-size: 13pt;">+7 (499) 340-34-73</span></b><span style="color: #000000;">; </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- сделать запрос по эл.почте: </span><span style="font-size: 12pt;"><a href="mailto:6417069@bk.ru"><b><span style="color: #0054a5; font-size: 13pt;"><u>6417069@bk.ru</u></span></b></a></span><span><span style="font-size: 12pt;">, </span><span style="color: #000000; font-size: 12pt;">другой адрес</span><span style="font-size: 12pt;">.</span></span><span style="font-size: 12pt;"><span style="color: #0000ff;"> </span></span>
</p>
<p style="text-align: center;">
 <span style="font-size: 12pt;"><br>
 </span><span style="font-size: 12pt;"> </span><br>
 <span style="font-size: 12pt;"><b><span style="font-size: 24pt; color: #0054a5;">Что Вы получите в результате проведенного мониторинга </span></b></span><br>
 <span style="font-size: 12pt;"> </span>
</p>
<p style="text-align: left;">
 <span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;"> &nbsp;В процессе ведения мониторинга составляются промежуточные ОТЧЕТЫ. По завершению мониторинга разрабатывается итоговое ЗАКЛЮЧЕНИЕ,&nbsp; выполненное&nbsp;по форме установленной ГОСТ 31937-2011 </span><span style="color: #000000; font-size: 12pt;">Приложения Б, В.</span><span style="color: #000000; font-size: 12pt;"> </span></span><br>
 <br>
</p>
<p style="text-align: center;">
 <span style="font-size: 12pt;"> </span><br>
 <span style="font-size: 12pt;"><b><span style="font-size: 24pt; color: #0054a5;">Для чего Вам необходимы результаты проведенного мониторинга </span></b></span><br>
</p>
<p style="text-align: left;">
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Результаты проведенного мониторинга необходимы: </span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">- для предоставления исходных данных проектировщикам при реконструкции, капитальном ремонте, изменении функционального назначения здания или сооружения, техническом перевооружении; </span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">- для установления и документального подтверждения изменения технического состояния здания или сооружения с течением времени, с целью определения возможности дальнейшей безаварийной эксплуатации, а также планирования мероприятий по предотвращению аварийных ситуаций.</span>
</p>
<p style="text-align: left;">
 <span style="font-size: 12pt;"><br>
 </span>
</p>
<p style="text-align: center;">
 <span style="font-size: 12pt;"><b><span style="font-size: 24pt; color: #0054a5;">Срок действия результатов проведенного мониторинга</span></b><span style="font-size: 24pt; color: #0054a5;"> </span></span><br>
</p>
<p style="text-align: left;">
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Срок действия результатов проведенного мониторинга зависит от целей его проведения, а именно: </span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">- если мониторинг выполнен с целью сбора исходных данных для проектирования; </span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">- если мониторинг выполнен с целью установления состояния конструкций зданий и сооружений.&nbsp;</span>
</p>
<p style="text-align: left;">
 <span style="color: #0054a5;"><span style="font-size: 13pt;">&nbsp; </span><span style="color: #0072bc; font-size: 13pt;">&nbsp; &nbsp;&nbsp;</span></span><b><span style="color: #0054a5; font-size: 13pt;">Срок действия результатов полученных с целью сбора исходных данных для проектирования </span></b><br>
 <span> <span style="color: #000000; font-size: 12pt;">&nbsp;</span></span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; Если мониторинг проведен с целью сбора исходных данных для проектирования (при реконструкции, капитальном ремонте, изменении технологического назначения здания&nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">и пр.), то полученные результаты являются частью проектной документации (поскольку относятся к инженерным изысканиям). Следовательно, срок действия результатов проведенного мониторинга устанавливается нормативными документами относящимися к проектной документации.&nbsp;</span><br>
 </span><span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; К нормативным документам устанавливающим срок действия проектной документации относятся:&nbsp;</span><br>
 </span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- </span><span style="color: #000000; font-size: 12pt;">ВСН 58-88(р) «Положение об организации и проведении реконструкции, ремонта и технического обслуживания зданий, объектов коммунального и социально-культурного назначения», </span><span><span style="color: #000000; font-size: 12pt;">п.5.10.;</span><br>
 </span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- МГСН 301.01-96 «Положение по организации капитального ремонта жилых зданий в г.Москве», </span><span style="color: #000000; font-size: 12pt;">п.4.3.</span><span style="color: #000000; font-size: 12pt;"> (действует только по г.Москва).</span>
</p>
<p style="text-align: left;">
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span><span style="color: #000000; font-size: 12pt;">В соответствии с ВСН 58-88(р) </span><span style="color: #000000; font-size: 12pt;">п.5.10</span><span style="color: #000000; font-size: 12pt;">:</span><span style="color: #000000; font-size: 12pt;"> </span><i><span style="color: #000000; font-size: 12pt;">«Интервал времени между утверждением проектно-сметной документации и началом ремонтно-строительных работ </span><b><span style="color: #000000; font-size: 12pt;">н</span></b><b><span style="color: #000000; font-size: 12pt;">е должен превышать 2 лет</span></b><span style="color: #000000; font-size: 12pt;">. Устаревшие проекты должны перерабатываться проектными организациями по заданиям заказчиков с целью доведения их технического уровня до современных требований и переутверждаться в порядке, установленном для утверждения вновь разработанных проектов.». </span></i></span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">Из выше сказанного следует, что&nbsp;в случае, если утвержденная (получившая положительное заключение экспертизы) проектно-сметная документация в течение 2 лет не была использована по назначению, ее необходимо заново согласовывать в установленном порядке. При этом необходимо учесть изменения состояния здания или сооружения, которые могли произойти за 2 года, и внести соответствующие правки в проектную документацию в том числе касающиеся инженерных изысканий. В случае отсутствия изменений состояния здания или сооружения, проект (в том числе результатов обследования) без существенных правок выпускается под новой датой. </span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span><span style="color: #000000; font-size: 12pt;">В соответствии с МГСН 301.01-96</span><span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">п.4.3:</span><span style="color: #000000; font-size: 12pt;"> </span><i><span style="color: #000000; font-size: 12pt;">«Вместе с заданием на проектирование комплексного капитального ремонта с перепланировкой (встройкой, пристройкой, надстройкой, устройством мансардных этажей) заказчик выдает проектной организации инвентаризационные поэтажные планы (в кальке) с указанием площадей помещений и объема здания по данным бюро технической инвентаризации (БТИ), проведенной </span><b><span style="color: #000000; font-size: 12pt;">не позднее 3-х лет до начала проектирования;»</span></b><span style="color: #000000; font-size: 12pt;">. </span></i></span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">Из выше сказанного следует, что&nbsp;если планы БТИ выполнены позднее 3-х лет от начала выполнения проектных работ, то необходимо заново проводить обмерочные работы. (МГСН 301.01-96 действует только по г. Москва).&nbsp;</span>
</p>
<p style="text-align: left;">
 <span style="font-size: 12pt;"> </span><br>
 <span style="color: #0054a5;"><span style="font-size: 13pt;">&nbsp; </span><span style="color: #0054a5; font-size: 13pt;">&nbsp; &nbsp;</span><span style="font-size: 13pt; color: #0054a5;">&nbsp;</span></span><b><span style="color: #0054a5; font-size: 13pt;">С</span></b><b><span style="color: #0054a5; font-size: 13pt;">рок действия результатов полученных для установления фактического состояния здания или сооружения </span></b><br>
 <span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Основным нормативным документом, регламентирующим работы по обследованию и мониторингу, а также срок действия результатов обследования или мониторинга зданий или сооружений, является ГОСТ 31937-2011 </span><span style="color: #000000; font-size: 12pt;">п.4.3-4.4,</span><span style="color: #000000; font-size: 12pt;"> в соответствии с которым:&nbsp;</span></span>
</p>
<p style="text-align: left;">
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><i style="font-size: 12pt;"><span style="color: #000000; font-size: 12pt;">«4.3 </span><b><span style="color: #000000; font-size: 12pt;">Первое обследование технического состояния зданий и сооружений проводится не позднее чем через два года после их ввода в эксплуатацию.</span></b><span style="color: #000000; font-size: 12pt;"> В дальнейшем обследование технического состояния зданий и сооружений проводится </span><b><span style="color: #000000; font-size: 12pt;">не&nbsp;реже одного раза в 10 лет и не реже одного раза в пять лет для зданий и сооружений или их отдельных элементов, работающих в неблагоприятных условиях</span></b><span style="color: #000000; font-size: 12pt;"> (агрессивные среды, вибрации, повышенная влажность, сейсмичность района 7 баллов и более и др.). Для уникальных зданий и сооружений устанавливается постоянный режим мониторинга.</span></i><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;"> </span><span><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp;</span><i><span style="color: #000000; font-size: 12pt;"> 4.4 Обследование и мониторинг технического состояния зданий и сооружений проводят также: </span></i></span><i><span style="color: #000000; font-size: 12pt;"> </span></i><br>
 <i><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по истечении нормативных сроков эксплуатации зданий и сооружений; </span></i><i><span style="color: #000000; font-size: 12pt;"> </span></i><br>
 <i><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - при обнаружении значительных дефектов, повреждений и деформаций в процессе технического обслуживания, осуществляемого собственником здания (сооружения); </span></i><i><span style="color: #000000; font-size: 12pt;"> </span></i><br>
 <i><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по результатам последствий пожаров, стихийных бедствий, аварий, связанных с разрушением здания (сооружения); </span></i><i><span style="color: #000000; font-size: 12pt;"> </span></i><br>
 <i><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по инициативе собственника объекта; </span></i><i><span style="color: #000000; font-size: 12pt;"> </span></i><br>
 <i><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - при изменении технологического назначения здания (сооружения); </span></i><i><span style="color: #000000; font-size: 12pt;"> </span></i><br>
 <i><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по предписанию органов, уполномоченных на ведение государственного строительного надзора.» </span></i><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">
	&nbsp; </span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span><span style="color: #000000; font-size: 12pt;">Здания и сооружения эксплуатирующиеся более 2-х лет подлежат обязательному обследованию или мониторингу через 10 лет (через 5 лет для зданий и сооружений работающих в неблагоприятных условиях). Также, возможно проведение обследования или мониторинга раньше установленных сроков в случаях перечисленных выше в </span><span style="color: #000000; font-size: 12pt;">п.4.4</span><span style="color: #000000;"><span style="font-size: 12pt;">.<span style="color: #000000;"> <span style="font-size: 13pt; color: #000000;">ГОСТ 31937-2011.</span></span><br>
 <br>
 </span><br>
 </span></span>
</p>
<p style="text-align: left;">
</p>
<p>
 <i><span style="color: #0054a5;"><a href="/about/"><b><span style="font-size: 15pt; color: #0054a5;">ТЕХНИЧЕСКАЯ СТРОИТЕЛЬНАЯ ЭКСПЕРТИЗА</span></b></a></span></i><br>
 <br>
</p>
<p>
 <i><span style="color: #0054a5;"><a href="/what-we-do/monitoring/calculator/"><span style="font-size: 15pt; color: #0054a5;"><u><b>Узнать стоимость и сроки online</b></u></span></a></span></i><span style="font-size: 14pt;"><i>,&nbsp;а также по тел.: +7(495) 641-70-69;&nbsp;+7(499) 340-34-73; e-mail:&nbsp;</i></span><a href="mailto:6417069@bk.ru"><i><span style="color: #0000ff;"><u><span style="color: #0054a5;"><b><span style="font-size: 14pt; color: #0054a5;">6417069@bk.ru</span></b></span></u></span></i></a><br>
 <br>
 <br>
</p>
<p>
 <br>
</p>
<p style="text-align: center;">
 <i><span style="color: #0054a5;"><span style="font-size: 14pt; color: #0054a5;">Читайте также:</span></span></i><br>
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
 <a href="/what-we-do/inspection/"><b><i><span style="color: #0054a5; font-size: 13pt;">Обследование конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования.</span></i></b></a><br>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <a href="/what-we-do/examination/"><span style="color: #0054a5; font-size: 13pt;"><b><i>Строительно-техническая экспертиза конструкций, помещений, зданий, сооружений, помещений, инженерных сетей и оборудования. Судебная экспертиза.</i></b></span></a><br>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <a href="/what-we-do/individual/"><b><i><span style="color: #0054a5; font-size: 13pt;">Исследование конструкций и материалов. Экспертиза&nbsp;деталей, изделий, узлов,&nbsp;элементов&nbsp;и пр.</span></i></b></a><br>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
</p>
 <span style="font-size: 13pt;"> </span><i><b><span style="color: #0054a5; font-size: 13pt;"> </span></b></i><span style="font-size: 13pt;"> </span>
<p style="text-align: center;">
 <i><span style="color: #0054a5;"><a href="/what-we-do/design/"><span style="font-size: 13pt; color: #0054a5;"><b>Разработка проектных решений</b></span></a></span></i><br>
</p>
<p>
 <a href="http://expert-staging.i-market.ru/info-block/opinions/interesno/?bitrix_include_areas=Y#"></a>
</p>
<p>
</p>
<p>
</p>
<p align="center">
</p>
<p>
</p>
<p>
</p><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>