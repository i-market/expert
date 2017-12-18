<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Исследование конструкций и материалов. Экспертиза деталей, изделий, узлов, элементов и пр.");

use App\Iblock;
?><p align="center">
</p>
<p style="text-align: center;">
 <span style="color: #000000; font-size: 12pt;"><b><span style="color: #0054a5; font-size: 24pt;">Что такое "Исследование конструкций и материалов. Экспертиза&nbsp;деталей, изделий, узлов,&nbsp;элементов&nbsp;и пр."?</span></b></span><br>
 <span style="color: #000000; font-size: 12pt;"> </span>
</p>
<p style="text-align: left;">
	 &nbsp; &nbsp;<span style="color: #0054a5; font-size: 13pt; font-weight: bold;">Исследования конструкций и материалов</span><span style="color: #000000;"> <span style="color: #000000; font-size: 12pt;">– это исследования, проводимые с использованием специального оборудования, направленные на определение фактических физико-механических характеристик и установление конструктивных параметров.&nbsp;</span></span><span style="color: #000000;"><br>
 </span><br>
	 &nbsp; &nbsp;<span style="color: #0054a5; font-size: 13pt; font-weight: bold;">Экспертиза деталей, изделий, узлов, элементов и пр</span><span style="color: #000000; font-size: 12pt;"><span style="font-size: 13pt; color: #0054a5;">.</span> </span><span style="color: #000000; font-size: 12pt;">– это исследования проводимые, как правило, в лабораторных условиях, в составе которых устанавливаются фактические свойства,&nbsp;техническое состояние, наличие и причины возникновения дефектов (разрушений, повреждений и пр.) предоставленных на экспертизу объектов.</span><span style="color: #000000; font-size: 12pt;"> <br></span><span style="color: #000000; font-size: 12pt;"><br>
 </span><br>
</p>
<p style="text-align: center;">
 <span style="color: #000000; font-size: 12pt;"><b><span style="font-size: 24pt; color: #0054a5;">Какие нормативно-правовые документы регламентируют работы по исследованиям&nbsp;и экспертизе?</span></b></span>
</p>
<p style="text-align: left;">
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;Состав и методику выполнения исследований конструкций и материалов,&nbsp;проведения экспертизы деталей, изделий, узлов, элементов и пр.,&nbsp;регламентируют следующие нормативные документы:</span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp; <span style="color: #000000; font-size: 13pt;">1.</span><span style="font-size: 13pt; color: #000000;"> </span></span></span><span style="color: #000000; font-size: 12pt;">СП 13-102-2003 «Правила обследования несущих строительных конструкций зданий и сооружений»;</span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp; </span><span style="font-size: 13pt; color: #000000;">2</span><span style="color: #000000; font-size: 13pt;">. </span><span style="color: #000000; font-size: 12pt;">Г</span><span style="color: #000000; font-size: 12pt;">О</span><span style="color: #000000; font-size: 12pt;">СТ 31937-2011 «Здания и сооружения. Правила обследования и мониторинга технического состояния»;</span><span style="color: #000000; font-size: 12pt;">&nbsp;</span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp; </span><span style="font-size: 13pt; color: #000000;">3</span><span style="color: #000000; font-size: 12pt;"><span style="font-size: 13pt; color: #000000;">.</span> </span><span style="color: #000000; font-size: 12pt;">Постановление Правительства Российской Федерации от 19.01.2006 г. № 20 «Об инженерных изысканиях для подготовки проектной документации, строительства, реконструкции объектов капитального строительства»;</span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp; </span><span style="font-size: 13pt; color: #000000;">4</span><span style="color: #000000; font-size: 12pt;"><span style="font-size: 13pt; color: #000000;">. </span>МГСН 301.01-96 «Положение по организации капитального ремонта жилых зданий в г.Москве», </span><span style="color: #000000; font-size: 12pt;">п</span></span><span style="color: #000000; font-size: 12pt;">.4.3, п.4.7-4.12;</span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp; </span><span style="color: #000000; font-size: 13pt;">5</span><span style="color: #000000; font-size: 13pt;">. </span></span><span style="color: #000000; font-size: 12pt;">Пособие по обследованию строительных конструкций зданий;<br>
	 &nbsp; &nbsp; &nbsp; &nbsp;и пр.</span>
</p>
<p style="text-align: left;">
 <br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">Правовыми документами регламентирующими назначение и состав работ по выполнению&nbsp;исследований конструкций и материалов,&nbsp;проведению&nbsp;экспертизы деталей, изделий, узлов, элементов и пр.,&nbsp;являются:</span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp; 1. Административный процессуальный кодекс РФ, статьи </span><span style="color: #000000; font-size: 12pt;">82, 83, 84, 85, 86, 87;</span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp; 2. Гражданский процессуальный кодекс РФ, статьи </span><span style="color: #000000; font-size: 12pt;">79, 80, 82, 83, 84, 85, 86, 87;</span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">
	&nbsp;&nbsp; 3. Уголовный процессуальный кодекс РФ, статьи </span><span style="color: #000000; font-size: 12pt;">195, 197, 198, 199, 200, 201, 202, 204, 205, 206, 207.</span>&nbsp;<br>
 <br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;Кроме того, эксперты, проводившие обследование по определению или постановлению суда несут ответственность предусмотренную</span><span style="font-size: 12pt;"><span style="color: #000000;"> </span></span><span style="font-size: 12pt; color: #000000;">ст. 57</span><span style="font-size: 12pt; color: #000000;"> УПК РФ, и по </span><span style="font-size: 12pt; color: #000000;">ст. 307, 30</span><span style="font-size: 12pt; color: #000000;">9</span><span style="font-size: 12pt; color: #000000;"> УК РФ за дачу заведомо ложного&nbsp;</span><span style="text-align: center; font-size: 12pt; color: #000000;">заключения.<br></span><span style="color: #000000; font-size: 12pt;"><br>
 </span>
</p>
<p>
</p>
<p>
</p>
<p>
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
 <br>
 <span style="color: #000000; font-size: 12pt;"><b><span style="color: #0054a5; font-size: 24pt;">Какие нормативно-правовые документы регулируют деятельность по выполнению&nbsp;исследований и проведению экспертизы?</span></b></span>
</p>
<p style="text-align: center;">
</p>
<p style="text-align: left;">
	 &nbsp; &nbsp; &nbsp;&nbsp;<span style="color: #000000; font-size: 12pt;">Основными нормативно-правовыми документами регулирующими деятельность по выполнению&nbsp;исследований конструкций и материалов,&nbsp;проведению&nbsp;экспертизы деталей, изделий, узлов, элементов и пр.&nbsp;являются:</span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;1. ГОСТ 31937-2011 «Здания и сооружения. Правила обследования и мониторинга технического состояния», </span><span style="color: #000000; font-size: 12pt;">п. 4, п.п. 4.1.;</span><span style="color: #000000; font-size: 12pt;"><span style="color: #0000ff;"> </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;2. СП 13-102-2003 «Правила обследования несущих строительных конструкций зданий и сооружений», </span><span style="color: #000000; font-size: 12pt;">п. 4, п.п. 4.1.;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;3.&nbsp;Градостроительный кодекс РФ от 29 декабря 2004 г. № 190-ФЗФ, </span><span style="color: #000000; font-size: 12pt;">статья 47, п.2;</span><span style="color: #000000; font-size: 12pt;"><span style="color: #0000ff;"> </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;4.&nbsp;Приказ Министерства регионального развития РФ от 30 декабря 2009 г. N 624, </span><span style="color: #000000; font-size: 12pt;">раздел 2, п. 12;</span><span style="color: #000000; font-size: 12pt;"><span style="color: #0000ff;"> </span></span><br>
 <span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;5.&nbsp;Административный процессуальный</span><span style="color: #000000; font-size: 12pt;"> кодекс РФ, </span></span><span style="color: #000000; font-size: 12pt;">статьи</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;">83;</span></span><span style="color: #000000; font-size: 12pt;"><span style="color: #000000;"><br>
 </span></span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;6.&nbsp;Гражданский процессуальный кодекс РФ, статьи 7</span><span style="color: #000000; font-size: 12pt;">9;&nbsp;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">7.&nbsp;Уголовный процессуальный кодекс РФ, статьи </span><span style="font-size: 12pt; color: #000000;">195.<br><br></span>
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
<p style="text-align: center;">
 <br>
 <span style="color: #000000; font-size: 12pt;"><b><span style="font-size: 24pt; color: #0054a5;">В каких случаях требуется выполнение исследований и проведение экспертизы деталей, изделий, узлов, элементов и пр.</span></b></span><b style="font-size: 12pt;"><span style="font-size: 24pt; color: #0054a5;">?</span></b>
</p>
<p>
 <span style="color: #000000; font-size: 12pt;"><b><span style="font-size: 13pt; color: #0054a5;">&nbsp; &nbsp;Выполнение работ по исследованию конструкций и материалов.</span></b></span><br>
 <span style="color: #000000;">&nbsp; Работы</span><span style="color: #000000; font-size: 12pt;">&nbsp;по исследованию конструкций и материалов производятся в случаях, когда необходимо&nbsp;определить&nbsp;фактические&nbsp;физико-механические&nbsp;характеристики и установить&nbsp;конструктивные&nbsp;параметры отдельных материалов и конструкций, таких как&nbsp;бетонные и железобетонные конструкции, металлические, каменные, деревянные, пластмассовые&nbsp;и пр. </span><span style="color: #000000; font-size: 12pt;">&nbsp;<br>
 </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;В результате проведенных работ фиксируются фактические параметры, характеризующие ключевые эксплуатационные свойства объекта исследования, такие как&nbsp;прочность, твердость, гибкость, упругость, эластичность, водопроницаемость, паропроницаемость, теплопроводнсть и пр.&nbsp;&nbsp;</span><br>
 <br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">В формате данных работ заказчик самостоятельно устанавливает объем и состав исследований исходя из своих потребностей. При этом, стоимость работ не зависит от размеров здания или сооружения, а зависит от количества выполненных замеров.</span><br>
 <br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;По результатам проведенных исследований разрабатывается отчет или заключение. В отчете или заключении фиксируются только результаты&nbsp;проведенных исследований. Выводов&nbsp;о техническом состоянии и&nbsp;соответствии нормативным требованиям конструкций, зданий&nbsp;или сооружений&nbsp;в целом&nbsp;не делается.<br>
 </span><br>
 <br>
 <span style="color: #000000; font-size: 12pt;"><b><span style="font-size: 13pt; color: #0054a5;">&nbsp; &nbsp;Экспертиза деталей, изделий, узлов, элементов&nbsp;и пр.</span></b></span><span style="color: #000000; font-size: 12pt;"><b><span style="font-size: 13pt; color: #0054a5;">&nbsp;</span></b></span><br>
 <span style="color: #000000;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">Экспертиза деталей, изделий, узлов, элементов и пр. производится в случаях, когда необходимо установить их техническое состояние, качество изготовления (выявить брак или подделку), дефекты монтажа и причины разрушения (повреждения). При этом, объект экспертизы предоставляется заказчиком, и все работы по экспертизе выполняются в лабораторных условиях.<br>
 </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;При выполнении экспертизы, в данном формате, объем работ устанавливается заказчиком, а стоимость работ зависит только от количества объектов исследования (как правило количества&nbsp;штук), без учета размеров здания или сооружения, объемно-планировочных и конструктивных особенностей здания или сооружения, количества и технических особенностей инженерных сетей и (или) оборудования и пр.</span><br>
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
 <br>
 <br>
 <span style="color: #000000; font-size: 12pt;"><b><span style="font-size: 24pt; color: #0054a5;">Этапы выполнения исследований и проведения экспертизы деталей, изделий, узлов, элементов&nbsp;и пр.</span></b></span>
</p>
<p style="text-align: center;">
 <span style="font-size: 12pt;"> </span>
</p>
<p>
</p>
<p>
</p>
<p>
</p>
<p>
</p>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Выполнение работ по&nbsp;исследованию конструкций и материалов, проведению&nbsp;экспертизы&nbsp;деталей, изделий, узлов, элементов и пр. производится в два этапа:</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- получение оснований для выполнения работ;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- проведение работ.</span><span style="color: #000000; font-size: 12pt;"><br>
 </span>
<p>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Последовательность действий и состав работ на каждом этапе включают:</span>
</p>
<p>
 <b><span style="color: #0054a5; font-size: 13pt;">&nbsp; &nbsp; &nbsp; 1. Получение оснований для выполнения работ</span></b><span style="color: #0054a5;"><span style="color: #0054a5; font-size: 13pt;">.</span><br>
 </span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Основанием для выполнения работ является:</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- договор на выполнение исследований или проведение обследования;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- постановление&nbsp;суда (при рассмотрении уголовных дел);</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- определение суда (при рассмотрении гражданских дел).</span><br>
</p>
 <span style="color: #000000;"> </span>
<p>
 <span style="color: #000000;"> </span><b><span style="color: #0054a5;"><span style="color: #000000;"><span style="color: #0054a5; font-size: 13pt;">&nbsp; &nbsp;</span><span style="color: #0054a5; font-size: 13pt;"> &nbsp; 2. П</span></span><span style="color: #0054a5; font-size: 13pt;">роведение работ.</span><br>
 </span></b><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Состав работ по выполнению исследований конструкций и материалов, проведению экспертизы деталей, изделий, узлов, элементов и пр. зависит&nbsp;от поставленных задач и может включать:</span>
</p>
<p>
</p>
<p>
 <span style="color: #0054a5; font-size: 13pt;">&nbsp;</span><b><span style="color: #0054a5;"><span style="color: #0054a5; font-size: 13pt;">&nbsp;</span><span style="font-size: 13pt; color: #0054a5;">&nbsp; &nbsp; Исследование конструкций и материалов</span><span style="color: #0054a5;"><span style="font-size: 13pt; color: #0054a5;">:</span><br>
 </span></span></b><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">- установление участков (места выполнения замеров и пр.) проведения работ;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- проведение работ по определению и фиксации требуемых величин и параметров;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- камеральная расшифровка и обработка полученных данных;</span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- разработка заключения или отчета.</span>
</p>
<p>
 <span style="color: #0054a5; font-size: 13pt;">&nbsp;&nbsp;</span><b><span style="color: #0054a5;"><span style="font-size: 13pt; color: #0054a5;">&nbsp; &nbsp; Экспертиза деталей, узлов, элементов и пр.:</span><br>
 </span></b><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">- визуальный осмотр с целью выявления&nbsp;недостатков, дефектов и повреждений;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- инструментальное обследование с целью сбора данных, определение&nbsp;требуемых параметров, установление характера недостатков, дефектов, повреждений и пр.;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- установление&nbsp;конструктивных и технических особенностей объекта экспертизы;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- установление фактических характеристик материалов;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- установление фактических эксплуатационных и расчетных нагрузок, воздействий;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- обработка и анализ полученных результатов;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- анализ причин появления дефектов, повреждений и недостатков;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- составление итогового документа (заключения или отчета) с выводами по результатам проведенной экспертизы;</span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;- разработка рекомендаций по устранению выявленных дефектов, повреждений,&nbsp;недостатков и пр. (при необходимости).<br><br></span>
</p>
 <span style="color: #000000;"> </span>
<p>
 <span style="color: #000000;"> </span>
</p>
 <span style="color: #000000;"> </span>
<p style="text-align: center;">
 <span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">Сроки выполнения исследований и проведения экспертизы деталей, изделий, узлов, элементов и пр. </span></b></span><br>
 <span style="font-size: 12pt; color: #000000;"> </span>
</p>
<p style="text-align: left;">
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; В зависимости от объема работ и специфики объекта продолжительность выполнения работ может составлять от 10 до 20 рабочих дней. </span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;</span>&nbsp; &nbsp;<span style="font-size: 12pt; color: #000000;">Узнать сроки выполнения работ можно следующими способами: </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- в режиме online в разделе <a href="/what-we-do/individual/calculator/"><u><span style="color: #0000ff;"><b><span style="color: #0054a5; font-size: 13pt;">«Online определение стоимости и сроков»</span></b></span></u></a><span style="color: #0000ff;">; </span></span><br>
 <span style="font-size: 12pt; color: #000000;"> <span style="color: #000000;">&nbsp; </span></span><span style="font-size: 12pt; color: #000000;"> </span><span style="color: #000000;"><span style="color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">&nbsp;- отправив&nbsp;заявку&nbsp;в разделе</span> <a href="/what-we-do/#modal=request-individual"><u><span style="color: #0000ff;"><b><span style="color: #0054a5; font-size: 13pt;">«Заявка на выполнение исследований конструкций и материалов,&nbsp;проведение экспертизы деталей, изделий, узлов, элементов и пр.»</span></b></span></u></a><span style="color: #0000ff;">; </span></span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp;&nbsp;</span><span style="font-size: 12pt; color: #000000;">- по телефонам: <span style="color: #0054a5;"><b><span style="color: #000000; font-size: 13pt;">+7 (495) 641-70-69</span></b></span><span style="color: #000000;">; </span></span><span style="font-size: 12pt; color: #000000;"><b><span style="color: #000000; font-size: 13pt;">+7 (499) 340-34-73</span></b><span style="color: #000000;">;</span></span><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp;</span><br>
 </span><span style="color: #000000;">&nbsp; </span><span style="font-size: 12pt; color: #000000;">&nbsp;- сделав запрос по эл.почте:</span> <a href="mailto:6417069@bk.ru"><b><span style="color: #0054a5; font-size: 13pt;"><u>6417069@bk.ru</u></span></b></a><span style="color: #0000ff; font-size: 12pt;">, </span><u><span style="color: #0000ff; font-size: 12pt;">другой адрес</span></u><span style="color: #0000ff; font-size: 12pt;">.</span>
</p>
 <u><span style="color: #0000ff;"><br>
 </span></u><span style="color: #0000ff;">
<p align="center">
</p>
 </span>
<p style="text-align: center;">
 <b><span style="font-size: 24pt; color: #0054a5;">Что Вы получите в результате выполнения исследований&nbsp;и проведения экспертизы деталей, изделий, узлов, элементов и пр. </span></b><br>
 <span style="color: #000000; "> </span><span style="color: #000000; "> </span><span style="color: #000000; ">
	&nbsp; </span><span style="color: #000000; "> </span><br>
 <span style="color: #000000; "> </span>
</p>
<p style="text-align: left;">
 <span style="color: #000000; "><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Если работы выполняются в формате экспертизы - выдается ЗАКЛЮЧЕНИЕ выполненное в соответствии с </span><span style="color: #000000; font-size: 12pt;">требова</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">ниями</span><span style="color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">с</span><span style="color: #000000;"> </span></span><span style="color: #000000; font-size: 12pt;">ст. 83 </span><span style="color: #000000; font-size: 12pt;">АПК РФ, </span><span style="color: #000000; font-size: 12pt;">ст. 79</span><span style="color: #000000; font-size: 12pt;"> ГПК РФ, </span><span style="color: #000000; font-size: 12pt;">ст. 195</span><span style="color: #000000; font-size: 12pt;"> УПК РФ. </span></span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; "><span style="color: #000000; font-size: 12pt;">
	&nbsp;</span><span style="font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; Если работы&nbsp;выполняются в формате обследования - выдается ОТЧЕТ выполненный по форме установленной ГОСТ 31937-2011 </span><span style="font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">Приложения Б, В,</span><span style="color: #000000; font-size: 12pt;"> с учетом требований СП 13-102-2003 Глава 11, а также в </span><span style="color: #000000; font-size: 12pt;">соответствии с рекомендациям</span><span style="color: #000000; font-size: 12pt;"> п</span><span style="color: #000000; font-size: 12pt;">рописанным</span><span style="color: #000000; font-size: 12pt;">и в </span><span style="color: #000000; font-size: 12pt;">Пособии по обследованию строительных конструкций зданий</span><span style="color: #000000; font-size: 12pt;">. </span></span><br>
 <span style="color: #000000; "> </span><span style="color: #000000; ">&nbsp; &nbsp;</span>
</p>
<p style="text-align: left;">
 <span style="color: #000000; "><span style="color: #0000ff;"><span style="color: #0054a5;"><br>
 </span></span></span>
</p>
<p>
</p>
<div style="text-align: center;">
 <span style="font-size: 12pt; color: #000000;">
	<p style="text-align: center;">
 <b><span style="font-size: 24pt; color: #0054a5;">Для чего необходимы результаты выполненных&nbsp;исследований&nbsp;и проведенной экспертизы деталей, изделий, узлов, элементов и пр</span></b><span style="font-size: 24pt; color: #0054a5;">.</span>
	</p>
 </span>
</div>
<div>
 <span style="font-size: 12pt; color: #000000;"> <b><span style="font-size: 13pt; color: #0054a5;">&nbsp; &nbsp; &nbsp;Результаты выполненных исследований конструкций и материалов,&nbsp;проведенных экспертиз деталей, изделий, узлов, элементов и пр. необходимы в следующих случаях: </span></b></span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- при соответствующем решении суда; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;</span><span style="font-size: 12pt; color: #000000;">&nbsp; - в качестве доказательства при спорах в суде; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp;&nbsp;- для фиксации качества; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;- для подтверждения или установления качества; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp;</span><span style="font-size: 12pt; color: #000000;">&nbsp;- в качестве доказательства при спорах с контрагентами; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;- для фиксации технического состояния; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;</span><span style="font-size: 12pt; color: #000000;">&nbsp; - в качестве подтверждения технического состояния; </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp;&nbsp;- для предъявления государственным надзорны</span><span style="font-size: 13pt; color: #000000;">м</span><span style="font-size: 12pt; color: #000000;">&nbsp;органам, и органам власти</span>;<br>
	 <span style="font-size: 13pt; color: #000000;">&nbsp; &nbsp;- и пр.</span><br><br><br>
</div>
<p style="text-align: center;">
 <span style="font-size: 24pt;"> </span>
</p>
<div style="text-align: center;">
 <span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">Срок действия результатов выполненных исследований&nbsp;и проведенных экспертиз&nbsp;деталей, изделий, узлов, элементов и пр.</span></b></span>
</div>
<div>
 <br>
 <span style="font-size: 12pt; color: #000000;"><b>&nbsp; &nbsp; &nbsp;<span style="font-size: 13pt;"> </span><span style="color: #0054a5; font-size: 13pt;">Результаты работ проведенных&nbsp;в формате</span><span style="color: #0054a5; font-size: 13pt;"><span style="color: #0054a5;">&nbsp;</span>экспертизы</span></b></span><br>
 <span style="font-size: 12pt; color: #000000;"> <span style="color: #000000;">&nbsp;&nbsp; </span></span><span style="color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">В целом законодательно не установлен</span><span style="color: #000000;">&nbsp;</span><span style="font-size: 12pt; color: #000000;">срок действия результатов экспертизы.&nbsp;Следовательно, заключение экспертизы не имеет ограничений по сроку действия и будет иметь силу в любое время.&nbsp;</span><span style="font-size: 12pt;"><br>
 <span style="color: #000000;"> </span></span><span style="color: #000000;"> </span>
</div>
 <span style="color: #000000;"> </span>
<div>
 <span style="color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;</span><span style="color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">Однако, следует учесть, что&nbsp;экспертиза, выполненная для суда, имеет практическую ценность только в формате рассматриваемого дела, т.е. до того момента пока решение суда не вступит в силу. </span><br>
 <span style="color: #000000;"> </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="color: #000000;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="font-size: 12pt; color: #000000;">Кроме того, бывают исключения, когда необходимо выполнить рецензию (обновить календарную дату и содержание) имеющегося заключения или провести новую экспертизу.&nbsp;Это относится к ситуациям, когда проводится экспертиза объекта, параметры которого меняются со временем. Срок действия такой экспертизы ограничен периодом до изменения состояния объекта.&nbsp; </span><span style="color: #000000;">&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;</span><br>
 <span style="font-size: 12pt; color: #000000;"><br>
 </span><span style="color: #000000;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="font-size: 12pt; color: #000000;">Примерами подобных ситуаций являются: </span><br>
 <span style="color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- строящееся здание или сооружение (в процессе строительства&nbsp;здание изменяется); </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- аварийное состояние здания или сооружения (со временем состояние ухудшается). </span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;"><b><span style="color: #0054a5;">&nbsp; &nbsp; &nbsp; <span style="color: #0054a5; font-size: 13pt;">Результаты&nbsp;</span><span style="color: #0054a5; font-size: 13pt;">р</span></span><span style="color: #0054a5; font-size: 13pt;">абот, выполненных&nbsp;в составе&nbsp;обследования</span><span style="color: #0054a5; font-size: 13pt;"> </span></b></span><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Срок действия результатов обследования зависит от целей его проведения: </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp;&nbsp;-</span>&nbsp;<span style="font-size: 12pt; color: #000000;">обследование проведено с целью сбора исходных данных для проектирования; </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp;&nbsp;- обследование проведено с целью установления фактического состояния конструкций зданий и сооружений. </span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 13pt; color: #0054a5;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #0054a5; font-size: 13pt;">Срок действия обследования выполненного с целью сбора исходных данных для проектирования</span> </b></span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp; </span><span style="font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">Если обследование выполнено с целью сбора исходных данных для проектирования (при реконструкции, капитальном ремонте, изменении технологического назначения здания и пр.), то полученные результаты являются частью проектной документации (поскольку относятся к инженерным изысканиям). Следовательно, срок действия результатов обследования устанавливается нормативными документами, относящимися к проектной документации. </span><br>
 <span style="font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">К нормативным документам, устанавливающим срок действия проектной документации относятся: </span><br>
 <span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- </span><span style="color: #000000;"><span style="font-size: 12pt;">ВСН</span><span style="color: #000000; font-size: 12pt;"> 58-88(р) «Положение об организации и проведении реконструкции, ремонта и технического обслуживания зданий, объектов коммунального и социально-культурного назначения», </span></span><span style="color: #000000; font-size: 12pt;">п.5.10.; </span></span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">- МГСН 301.01-96 «Положение по организации капитального ремонта жилых&nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">зданий в г.Москве», </span><span style="color: #000000; font-size: 12pt;">п.4.3. </span><span style="color: #000000; font-size: 12pt;">(действует только по г.Москва). </span><br>
 <span style="color: #000000; font-size: 12pt;"> </span></span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">В соответствии с ВСН 58-88(р) </span><span style="color: #000000; font-size: 12pt;">п.5.10</span><span style="color: #000000; font-size: 12pt;">: </span><i><span style="color: #000000; font-size: 12pt;">«Интервал времени между утверждением проектно-сметной документации и началом ремонтно-строительных работ </span><b><span style="color: #000000; font-size: 12pt;">не должен превышать 2 лет.</span></b><span style="color: #000000; font-size: 12pt;"> Устаревшие проекты должны перерабатываться проектными организациями по заданиям заказчиков с целью доведения их технического уровня до современных требований и переутверждаться в порядке, установленном для утверждения вновь разработанных проектов.». </span><br>
 <span style="color: #000000; font-size: 12pt;"> </span></i></span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">Из выше сказанного следует, что&nbsp;в случае, если утвержденная (получившая положительное заключение экспертизы) проектно-сметная документация в течение 2 лет не была использована по назначению, ее необходимо заново согласовывать в установленном порядке. При этом необходимо учесть изменения состояния здания или сооружения касающиеся, также, инженерных изысканий (в том числе результатов обследования) и проектных решений которые могли произойти за 2 года. В случае отсутствия изменений состояния здания или сооружения, проект (в том числе результатов обследования) без существенных правок выпускается под новой датой. </span><br>
 <span style="color: #000000; font-size: 12pt;"> </span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #000000;"><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">В соответствии с МГСН 3</span><span style="color: #000000; font-size: 12pt;">01.01-96 </span></span><span style="color: #000000; font-size: 12pt;">п.4.3: </span><i><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">«</span><span style="font-size: 12pt; color: #000000;">Вместе с заданием на проектирование комплексного капитального ремонта с перепланировкой (встройкой, пристройкой, надстройкой, устройством мансардных этажей) заказчик выдает проектной организации: - инвентаризационные поэтажные планы (в кальке) с указанием площадей помещений и объема здания по данным бюро технической инвентаризации (БТИ), проведенной </span></span><b><span style="color: #000000; font-size: 12pt;">не позднее 3-х лет до начала проектирования;</span></b><span style="color: #000000; font-size: 12pt;">». </span><br>
 <span style="font-size: 12pt;"> </span></i></span><span style="color: #000000; font-size: 12pt;"> </span><br>
 <span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">Из выше сказанного следует что, если планы БТИ выполнены позднее 3-х лет до начала проектирования, то необходимо заново проводить обмерочные работы&nbsp;(следует также учесть что, МГСН 301.01-96 действует только по г.Москва). </span><br>
 <span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;"><b><span style="color: #0054a5; font-size: 13pt;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="color: #0054a5;"><span style="font-size: 13pt; color: #0054a5;">Срок действия обследования проведенного с целью установления состояния</span><span style="font-size: 13pt; color: #0054a5;"> </span></span></b></span><br>
 <span style="color: #000000;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="font-size: 12pt; color: #000000;">Основным нормативным документом, регламентирующим срок действия результатов обследования с целью установления фактического состояния является ГОСТ 31937-2011 п.4.3-4.4, в соответствии с которым: </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp;&nbsp; <i>«4.3 <b>Первое обследование технического состояния зданий и сооружений проводится не позднее чем через два года после их ввода в эксплуатацию.</b> В дальнейшем обследование технического состояния зданий и сооружений проводится <b>не реже одного раза в 10 лет и не реже одного раза в пять лет для зданий и сооружений или их отдельных элементов, работающих в неблагоприятных условиях</b> (агрессивные среды, вибрации, повышенная влажность, сейсмичность района 7 баллов и более и др.). Для уникальных зданий и сооружений устанавливается постоянный режим мониторинга. </i></span><br>
 <span style="font-size: 12pt; color: #000000;"><i>
	&nbsp;&nbsp; 4.4 Обследование и мониторинг технического состояния зданий и сооружений проводят также: </i></span><span style="font-size: 12pt; color: #000000;"><i> </i></span><br>
 <span style="font-size: 12pt; color: #000000;"><i>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по истечении нормативных сроков эксплуатации зданий и сооружений; </i></span><span style="font-size: 12pt; color: #000000;"><i> </i></span><br>
 <span style="font-size: 12pt; color: #000000;"><i>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - при обнаружении значительных дефектов, повреждений и деформаций в процессе технического обслуживания, осуществляемого собственником здания (сооружения); </i></span><span style="font-size: 12pt; color: #000000;"><i> </i></span><br>
 <span style="font-size: 12pt; color: #000000;"><i> </i></span><span style="font-size: 12pt; color: #000000;"><i> </i></span><span style="font-size: 12pt; color: #000000;"><i>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по результатам последствий пожаров, стихийных бедствий, аварий, связанных с разрушением здания (сооружения); </i></span><span style="font-size: 12pt; color: #000000;"><i> </i></span><br>
 <span style="font-size: 12pt; color: #000000;"><i>&nbsp; &nbsp; &nbsp; - по инициативе собственника объекта; </i></span><span style="font-size: 12pt; color: #000000;"><i> </i></span><br>
 <span style="font-size: 12pt; color: #000000;"><i>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - при изменении технологического назначения здания (сооружения); </i></span><span style="font-size: 12pt; color: #000000;"><i> </i></span><br>
 <span style="font-size: 12pt; color: #000000;"><i>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по предписанию органов, уполномоченных на ведение государственного строительного надзора.» </i></span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;"> </span><br>
 <span style="color: #000000;">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="font-size: 12pt; color: #000000;">Из выше сказанного следует, что в соответствии с нормативными требованиями обследование конструкций новостроек проводится в обязательном порядке после 2 лет эксплуатации. Здания и сооружения, эксплуатирующиеся более 2-х лет подлежат обязательному обследованию через 10 лет (через 5 лет для зданий и сооружений работающих в неблагоприятных условиях). Также, возможно проведение обследования раньше установленных сроков в </span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">случа</span><span style="color: #000000; font-size: 12pt;">ях пе</span><span style="color: #000000; font-size: 12pt;">речисленных выше в </span><span style="color: #000000; font-size: 12pt;">п.4.4.</span><span style="color: #000000; font-size: 12pt;"> ГОСТ 31937-2011.<br>
 <br>
 </span></span><br>
</div>
<div style="text-align: center;">
 <br><b>
 </b><span style="font-size: 15pt;"><b> </b></span><span style="font-size: 15pt;"><b> </b></span><br><b>
 </b><span style="font-size: 15pt;"><b> </b></span><span style="font-size: 15pt;"><b> </b></span><a href="http://expert-staging.i-market.ru/about/"><span style="font-size: 15pt;"><b> </b></span></a><span style="font-size: 15pt;"><a href="http://expert-staging.i-market.ru/about/"><b> </b></a></span><span style="font-size: 15pt;"><a href="http://expert-staging.i-market.ru/about/"><b> </b></a></span><span style="font-size: 15pt;"><a href="http://expert-staging.i-market.ru/about/"><b> </b></a></span><span style="font-size: 15pt;"><a href="http://expert-staging.i-market.ru/about/"><b> </b></a></span><span style="font-size: 15pt;"><a href="http://expert-staging.i-market.ru/about/"><b> </b></a></span><span style="font-size: 15pt;"><a href="http://expert-staging.i-market.ru/about/"><b> </b></a></span><span style="font-size: 15pt;"><a href="http://expert-staging.i-market.ru/about/"><b> </b></a></span><a href="http://expert-staging.i-market.ru/about/"><span style="font-size: 15pt;"><b> </b></span></a><span style="font-size: 15pt;"><b> </b></span><span style="font-size: 15pt;"><b> </b></span><b>
</b></div><b>
</b><i><b> </b></i><i><b> </b></i><i><span style="color: #0054a5; font-size: 14pt;"><a href="/about/"><span style="color: #0054a5; font-size: 15pt;"><b>ТЕХНИЧЕСКАЯ СТРОИТЕЛЬНАЯ ЭКСПЕРТИЗА</b></span></a></span></i><span style="font-size: 15pt;"> </span>
<p>
 <br>
</p>
<p>
 <i><span style="color: #0054a5;"><u><a href="/what-we-do/individual/calculator/"><span style="color: #0054a5; font-size: 15pt;"><b>Узнать стоимость и сроки online</b></span></a></u></span></i><span style="font-size: 14pt;"><i>,&nbsp;а также по тел.: +7(495) 641-70-69;&nbsp;+7(499) 340-34-73; e-mail:&nbsp;</i></span><a href="mailto:6417069@bk.ru"><i><span style="color: #0000ff;"><u><span style="color: #0054a5;"><b><span style="color: #0054a5; font-size: 14pt;">6417069@bk.ru</span></b></span></u></span></i></a><br>
 <br>
 <br>
 <br>
</p>
<p style="text-align: center;">
 <i><span style="color: #0054a5; font-size: 15pt;">Читайте также:</span></i><br>
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
 <i><span style="color: #0054a5; font-size: 12pt;"><a href="/what-we-do/inspection/"><b><span style="color: #0054a5; font-size: 13pt;">Обследование конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования</span></b></a></span></i>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt;"><b><i><span style="color: #0054a5; font-size: 12pt;"> </span></i></b></span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt;"><b><i><span style="color: #0054a5; font-size: 12pt;"> </span></i></b></span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt;"><b><i><span style="color: #0054a5; font-size: 12pt;"> </span></i></b></span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <a href="/what-we-do/examination/"><b><i><span style="color: #0054a5; font-size: 13pt;">Строительно-техническая экспертиза конструкций, помещений, зданий, сооружений, помещений, инженерных сетей и оборудования. Судебная экспертиза.</span></i></b></a><br>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt;"><b><i><span style="color: #0054a5; font-size: 12pt;"> </span></i></b></span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt;"><b><i><span style="color: #0054a5; font-size: 12pt;"> </span></i></b></span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt;"><b><i><span style="color: #0054a5; font-size: 12pt;"> </span></i></b></span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <a href="/what-we-do/monitoring/"><b><i><span style="color: #0054a5; font-size: 13pt;">Мониторинг технического состояния зданий и сооружений</span></i></b></a><a href="http://expert-staging.i-market.ru/what-we-do/monitoring/"></a><br>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt;"><b><i><span style="color: #0054a5; font-size: 12pt;"> </span></i></b></span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt;"><b><i><span style="color: #0054a5; font-size: 12pt;"> </span></i></b></span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt;"><b><i><span style="color: #0054a5; font-size: 12pt;"> </span></i></b></span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
</p>
 <span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span><span style="font-size: 13pt; color: #0054a5;"> </span>
<p style="text-align: center;">
 <a href="/what-we-do/design/"><b><i><span style="color: #0054a5; font-size: 13pt;">Разработка проектных решений</span></i></b></a><br>
</p>
<p>
</p>
<p>
</p>
<p>
</p>
 <span style="color: #0000ff;">
<p>
</p>
 </span>
<p>
</p>
<p>
</p><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>