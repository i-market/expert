<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обследование конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования");

use App\Iblock;
?><p align="center">
</p>
<blockquote>
	<p>
	</p>
</blockquote><p style="text-align: center;"><span style="text-align: center; "><b><span style="font-size: 24pt; color: #0054a5;">Что такое «обследование»?</span></b></span><span style="text-align: center; "><b><span style="font-size: 24pt; color: #464646;">&nbsp;</span></b></span></p><p>
 <span style="color: #000000; "><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp;В соответствии с</span><span style="color: #0000ff;"><span style="font-size: 12pt; color: #000000;"> </span><span style="color: #000000; font-size: 12pt;">ГОСТ 31937-2011 «Здания и сооружения. Правила обследования и мониторинга технического состояния»</span></span><span style="font-size: 12pt;"><span style="color: #000000;">,</span><span style="font-size: 13pt; color: #000000;"> </span></span></span><span style="color: #000000; font-size: 12pt; "><span style="color: #0054a5;"><span style="font-size: 13pt; color: #0054a5;"><b>обследование это</b></span></span></span><span style="color: #000000; "><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">– комплекс мероприятий по определению и оценке фактических значений контролируемых параметров, характеризующих эксплуатационное состояние, пригодность и работоспособность объектов обследования определяющих возможность их да</span></span><span style="color: #000000; font-size: 12pt;">льнейшей эксплуатации или необходимость восстановления и усиления.</span><span style="color: #000000; font-size: 12pt; ">&nbsp;</span><span style="color: #000000; font-size: 12pt; "><span style="color: #000000;"> </span><br>
 </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt; "> </span><span style="color: #000000; ">
	<span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp; </span></span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; "><span style="color: #000000; font-size: 12pt;">В соответствии с </span><span style="color: #000000; font-size: 12pt;">СП 13-102-2003 «Правила обследования несущих строительных конструкций зданий и сооружений»</span><span style="color: #000000; font-size: 12pt;">, </span><span style="color: #0054a5; font-size: 13pt;"><b>обследование это</b></span></span><span style="color: #000000; font-size: 12pt;"> - комплекс мероприятий по определению и оценке фактических значений контролируемых параметров грунтов основания, строительных конструкций, инженерного обеспечения (оборудования, трубопроводов, электрических сетей и др.), характеризующих работоспособность объекта обследования и определяющих возможность его дальнейшей эксплуатации, реконструкции или необходимость восстановления, усиления, ремонта, и включающий в себя обследование технического состояния здания (сооружения), теплотехнических и акустических свойств конструкций, систем инженерного обеспечения объекта, за исключением технологического оборудования.</span></p><p style="text-align: center;"><b><span style="color: #0054a5; font-size: 13pt;">Обобщающее определение понятия «обследование»</span></b><span style="font-size: 13pt;"><b><span style="color: #0054a5;"><br></span></b></span><i style="font-size: 12pt; text-align: left;"><span style="font-size: 13pt; color: #0054a5;">Обследование конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования – это комплекс работ по оценке технического состояния строительных конструкций, инженерных сетей и оборудования помещений, зданий или сооружений, с целью принятия, на основании этой оценки, решения о необходимости и (или) возможности проведения ремонта, выполнения реконструкции или сноса (демонтажа).</span></i></p><p></p><b style=""><span style="color: #0054a5; font-size: 13pt;"><div style="text-align: center;"><span style="font-size: 12pt; text-align: left;"><i><span style="font-size: 13pt;">.</span></i></span></div></span></b><p></p><p style="text-align: center;"><span style="font-size: 15pt;"><b><span style="font-size: 24pt; color: #0054a5;">Какие нормативно-правовые документы регламентируют работы по выполнению обследования</span></b></span></p><p><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Основными нормативными и правовыми документами
регламентирующими работы по выполнению обследование являются:</span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;1.&nbsp;СП 13-102-2003 «Правила обследования несущих строительных конструкций зданий и сооружений»;</span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;2.&nbsp;ГОСТ Р 53778-2010 «Здания и сооружения. Правила обследования и мониторинга технического состояния»;&nbsp;</span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;3.&nbsp;ГОСТ 31937-2011
«Здания и сооружения. Правила обследования и мониторинга технического
состояния»;</span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;4.&nbsp;Постановление Правительства
Российской Федерации от 19.01.2006 г. № 20 «Об инженерных изысканиях для
подготовки проектной документации, строительства, реконструкции объектов
капитального строительства»;&nbsp;</span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;5.&nbsp;МГСН
301.01-96 «Положение по организации капитального ремонта жилых зданий в г.Москве», п.4.3,
п.4.7-4.12;</span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;6.&nbsp;Пособие по
обследованию строительных конструкций зданий.</span><br></p><p><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Основными нормативными и правовыми документами
регламентирующими, в каких случаях требуется выполнение обследования являются: </span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;1. Градостроительный кодекс РФ от 29 декабря 2004 г. № 190-ФЗФ,&nbsp;статья 47; </span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;2. Федеральный закон от 30 декабря 2009 г. N 384-ФЗ «Технический регламент о
безопасности зданий и сооружений»,&nbsp;статья
15; </span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;3. Постановление Правительства Р Ф
от 16 февраля 2008 г. № 87 «О составе разделов проектной документации и
требованиях к их содержанию», п. 10 п.п. б; п. 11; </span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;4. ВСН 61-89 (р) «Реконструкция и
капитальный ремонт жилых домов. Нормы проектирования.»,&nbsp;п.3, п.п. 3.1.; п.4, п.п. 4.1.1.; </span><br><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">5. МДС 13-14.2000 «Положение проведении планово-предупредительного ремонта
производственных зданий и сооружений»,&nbsp;п.
6.9.&nbsp;(действует только по
г. </span><span style="font-size: 12pt; color: #000000;">Москва).</span></p><p style="text-align: left;"><b style="font-size: 12pt; text-align: center;"><span style="font-size: 15pt;"><br></span></b></p><p style="text-align: left;"></p><p style="text-align: center;"></p><p style="text-align: center;"><b style="font-size: 12pt; text-align: center;"><span style="font-size: 15pt;"><span style="color: #0054a5; font-size: 24pt;">Какие нормативно-правовые документы регулируют деятельность организаций выполняющих</span><span style="color: #0054a5; font-size: 24pt;">&nbsp;</span></span></b><b><span style="color: #0054a5; font-size: 24pt;">обследование</span><span style="font-size: 24pt;">&nbsp;</span></b></p><p style="text-align: center;"></p><p></p><p style="text-align: left;"><span style="text-align: left;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Основ</span><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">ными нормативно-правовыми документами регулирующими деятельность организаций выполняющих обследование являются:&nbsp;</span><br></span></span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;1. Приказ Министерства регионального развития РФ от 30 декабря 2009 г. N 624, </span><span style=""><span style="font-size: 12pt; color: #000000;">раздел 2, п. 12;&nbsp;</span><br></span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;2. Градостроительный кодекс РФ от 29 декабря 2004 г. № 190-ФЗФ, </span><span style=""><span style="font-size: 12pt; color: #000000;">статья 47, п.2;&nbsp;</span><br></span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;3. СП 13-102-2003 «Правила обследования несущих строительных конструкций зданий и сооружений», </span><span style=""><span style="font-size: 12pt; color: #000000;">п. 4, п.п. 4.1.;&nbsp;</span><br></span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;4. ГОСТ 31937-2011 «Здания и сооружения. Правила обследования и мониторинга технического состояния», </span><span style="font-size: 12pt; color: #000000;">п. 4, п.п. 4.1.&nbsp;</span></p><p style="text-align: center;"><span style="font-size: 12pt; color: #000000;"><span style="color: #0000ff;"><br>
 </span></span><span style="font-size: 12pt; color: #000000;"> </span><br><span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">В каких случаях Вам необходимо обследование </span></b></span><br>
 <span style="color: #000000; "> </span><span style="font-size: 12pt; color: #000000;">
	</span></p><p style="text-align: left;"><span style="font-size: 12pt; color: #000000;">&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;</span><span style="font-size: 12pt;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">Обследование выполняются в следующих случаях: </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;1. При реконструкции или капитальном ремонте, с целью сбора исходных данных для проектирования зданий, сооружений, инженерных сетей и оборудования; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;2. При необходимости оценки возможности дальнейшей безаварийной эксплуатации зданий, сооружений, инженерных сетей и оборудования, определения необходимости восстановления, усиления, и пр.; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;3. В случаях наличия значительного износа и, связанных с ним, повреждений, прогибов и прочих дефектов и недостатков зданий, сооружений, инженерных сетей и оборудования; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp; 4. В случаях расположения зданий, сооружений, инженерных сетей и оборудования в зоне влияния строек и природно-техногенных воздействий, с целью установления возможности дальнейшей безопасной эксплуатации; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;5. Для определения фактического состояния зданий, сооружений, инженерных сетей и оборудования уже официально отнесенных к ограниченно работоспособному или аварийному состоянию; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;6. Для осуществления контроля за состоянием конструкций, в том числе высотных и большепролетных зданий и сооружений, с целью предотвращения катастроф и обрушений; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;7. При плановой проверке зданий, сооружений, инженерных сетей и оборудования, с целью осуществлением контроля за техническим состоянием; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;8. В случае истечения нормативных сроков эксплуатации зданий, сооружений, инженерных сетей и оборудования с целью определения возможности дальнейшей эксплуатации; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;9. При изменении технологического назначения зданий, сооружений, инженерных сетей и оборудования, с целью установления допустимости эксплуатации в изменившихся условиях; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;10. При обнаружении, в процессе технического обслуживания зданий, сооружений, инженерных сетей и оборудования, значительных дефектов, повреждений и деформаций несвязанных с физическим износом; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;11. С целью оценки степени воздействия пожаров, стихийных бедствий и аварий на эксплуатационные характеристики зданий, сооружений, инженерные сети и оборудование; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;12. По предписанию органов, уполномоченных на проведение инспекционных проверок за эксплуатацией и надзора за строительством и монтажом зданий, сооружений, инженерных сетей и оборудования; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;13. В случае выявлении отступлений от проекта, снижающих несущую способность и эксплуатационные качества, зданий, сооружений, инженерных сетей и оборудования; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;14. При отсутствии проектно-технической и исполнительной документации на здания, сооружения, инженерные сети и оборудование; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;15. В случае возобновления прерванного строительства при выполнении консервации, при отсутствии консервации или по истечении трех лет после прекращения строительства зданий, сооружений, инженерных сетей и оборудования; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;16. При выявлении деформаций грунтовых оснований зданий, сооружений. </span></p><p style="text-align: center;"><br>
 <span style="font-size: 12pt; color: #000000;"> </span>
</p><p></p><p></p>
<p style="text-align: center;"></p><p style="text-align: center;"><b><span style="font-size: 24pt; color: #0054a5;">Задачи обследования&nbsp;</span></b><span style="font-size: 24pt; color: #0054a5;">&nbsp;&nbsp;</span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	</span></p><p style="text-align: left;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; В зависимости от поставленной цели, в задачи обследования может входит: </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;1. Установление конструктивной схемы и конструктивных особенностей зданий, сооружений, инженерных сетей и оборудования; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;2. Выявление дефектов, повреждений и недостатков строительных конструкций, инженерных сетей и оборудования; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;3. Установление и фиксация существующих объемно-планировочных решений зданий и сооружений; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;4. Установление и фиксация принципиальных схем инженерных сетей и оборудования; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;5. Определение физико-механических характеристик бетонных и железобетонных конструкций и материалов (прочность, твердость, плотность, влажность, водопроницаемость и пр.); </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;6. Определение физико-механических характеристик каменных конструкций и материалов (прочность, твердость, плотность, влажность, водопроницаемость и пр.); </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;7. Определение физико-механических характеристик металлических конструкций и материалов (прочность, твердость, плотность, влажность, водопроницаемость и пр.); </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;8. Определение физико-механических характеристик деревянных конструкций и материалов (прочность, твердость, плотность, влажность, водопроницаемость и пр.); </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;9. Установление фактических нагрузок и воздействий воспринимаемых строительными конструкциями; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;10. Установление фактических нагрузок приходящих на инженерные сети и оборудование; </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><span style="font-size: 12pt; color: #000000;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;11. Выполнение поверочных расчетов конструкций и элементов зданий и сооружений. </span><br><span style="font-size: 12pt;">
 </span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp; 12. Выдача рекомендаций по устранению выявленных дефектов, повреждений и недостатков, а также по дальнейшей эксплуатации зданий и сооружений.</span></p><p style="text-align: left;"><br></p><p style="text-align: center;"><b><span style="font-size: 24pt; color: #0054a5;">Этапы выполнения обследования</span></b></p><div>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Обследование строительных конструкций зданий и сооружений, инженерных сетей и оборудования проводится, как правило, в три связанных между собой этапа: </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- подготовка к проведению обследования; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- предварительное (визуальное) обследование; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- детальное (инструментальное) обследование. </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">
	&nbsp;&nbsp; </span><span style="font-size: 12pt;">&nbsp; &nbsp;</span><span style="font-size: 12pt; color: #000000;">Последовательность действий и состав работ на каждом этапе включают: </span><br>
 <b><span style="font-size: 13pt; color: #0054a5;">&nbsp; &nbsp;1. Подготовительные работы:</span></b><b><span style="font-size: 13pt; color: #0054a5;"> </span></b><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- ознакомление с объектом обследования, его объемно-планировочным и конструктивным решением, характеристиками инженерных сетей материалами инженерно-геологических изысканий: </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- подбор и анализ проектно-технической документации; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- составление программы работ (при необходимости) на основе полученного от заказчика технического задания. Техническое задание разрабатывается заказчиком или проектной организацией и, возможно, с участием исполнителя обследования.&nbsp;Техническое задание утверждается заказчиком, согласовывается исполнителем и, при необходимости, проектной организацией - разработчиком проекта задания. </span><br>
 <span style="font-size: 12pt; color: #000000;">
	&nbsp; </span><br>
 <b><span style="font-size: 13pt; color: #0054a5;">&nbsp; &nbsp;2. Предварительное (визуальное) обследование:</span></b><br>
 <span style="color: #000000;">&nbsp; &nbsp;- сплошное визуальное обследование конструкций зданий, сооружений, инженерных сетей и оборудования с целью выявление дефектов и повреждений по внешним признакам с необходимыми <span style="font-size: 13pt; color: #000000;">замерами и фиксацией.</span></span><br><span style="font-size: 13pt; color: #0054a5;">
 </span><span style="font-size: 13pt; color: #0054a5;"> </span><br><span style="font-size: 13pt; color: #0054a5;">
 </span><b><span style="font-size: 13pt; color: #0054a5;">&nbsp; &nbsp;3. Детальное (инструментальное) обследование:</span></b><b><span style="font-size: 13pt; color: #0054a5;"> </span></b><br>
 <span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- работы по обмерам и замерам необходимых параметров зданий, конструкций, &nbsp;элементов и узлов, а также показаний инженерных сетей и оборудования, в том числе с применением геодезических приборов; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- инструментальное определение параметров дефектов и повреждений; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- определение фактических прочностных характеристик материалов основных несущих конструкций и их элементов; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- измерение параметров эксплуатационной среды, присущей технологическому процессу в здании и сооружении; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- определение фактических эксплуатационных нагрузок и воздействий, воспринимаемых обследуемыми конструкциями с учетом влияния деформаций грунтового основания; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- определение фактических нагрузок действующих на инженерные сети и оборудование; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- определение фактической расчетной схемы здания и его отдельных конструкций; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- определение расчетных усилий в несущих конструкциях, воспринимающих эксплуатационные нагрузки; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- определение расчетных нагрузок в инженерных сетях и оборудовании; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- расчет несущей способности конструкций по результатам обследования; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- расчет фактических эксплуатационных нагрузок в инженерных сетях и оборудовании; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- камеральная обработка и анализ результатов обследования и поверочных расчетов; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- анализ причин появления дефектов, повреждений и недостатков; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- составление итогового документа с выводами по результатам обследования; </span><br><span style="font-size: 12pt;">
 </span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;- разработка рекомендаций по устранению выявленных дефектов, повреждений и недостатков.</span><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;"><br></span></i></span><br><br><br></div><br><br><p style="text-align: center;"><span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">Сроки выполнения обследования</span></b></span><span style="font-size: 24pt; color: #0054a5;">&nbsp;</span></p><p style="text-align: left;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; В зависимости от поставленных задач, специфики
объекта, объема работ, а также места расположения объекта продолжительность
обследования может варьироваться от 10 до 60 рабочих дней.</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">
</span><br><span style="color: #000000; font-size: 12pt;">
</span></span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Точно установить продолжительность
работ можно только после получения исходных данных.</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">

</span><br></span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp;Узнать сроки выполнения работ
можно следующими способами:</span><span style="color: #000000; font-size: 12pt;">
</span><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">
</span><span style="color: #000000; font-size: 12pt;">
</span><br><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp;- в режиме online в разделе</span></span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;"><a href="/what-we-do/inspection/calculator/"><u><span style="color: #0000ff;"><b><span style="color: #0054a5; font-size: 13pt;">«</span></b><b><span style="color: #0054a5; font-size: 13pt;">Online определение
стоимости и сроков выполнения обследования</span></b><b><span style="color: #0054a5; font-size: 13pt;">»</span></b></span></u><span style="color: #0000ff;">; </span></a></span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">

</span><span style="color: #000000; font-size: 12pt;">&nbsp;- сделать запрос на выполнение обследования в разделе</span><span style="font-size: 12pt; color: #000000;"><span style="color: #000000;"> </span><a href="/what-we-do/#modal=request-inspection"><u><span style="color: #0000ff;"><b><span style="font-size: 13pt; color: #0054a5;">«Заявка на выполнение обследования»</span></b></span></u></a><span style="color: #0000ff;">;</span></span><span style="font-size: 12pt; color: #000000;">
</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp;&nbsp;</span><span style="color: #000000; font-size: 12pt;">- связавшись с нашими
специалистами по телефонам:</span><span style="font-size: 12pt; color: #000000;"><span style="color: #000000;"> </span><b><span style="font-size: 13pt; color: #0054a5;">+7 (495) 641-70-69</span></b>;<span style="color: #0054a5; font-size: 14pt;"> </span></span><span style="font-size: 12pt; color: #000000;"><b><span style="color: #0054a5; font-size: 13pt;">+7 (499) 340-34-71</span></b>; </span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">

</span><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp;- сделать запрос по эл.почте:</span> </span><span style="font-size: 12pt; color: #000000;"><a href="mailto:6417069@bk.ru"><b><span style="font-size: 13pt; color: #0054a5;">6</span></b><b><span style="font-size: 13pt; color: #0054a5;">417069@bk.ru</span></b></a></span><span style="color: #000000; font-size: 12pt;">, </span><span style="color: #000000; font-size: 12pt;">другой адрес.</span></p><p style="text-align: left;"><span style="font-size: 12pt; color: #000000;"><br></span></p><p style="text-align: center;"></p><p></p><p style="text-align: center;"><b><span style="font-size: 24pt; color: #0054a5;">Что Вы получите в результате выполненного обследования</span></b></p><p><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; В результате выполненного
обследования выдается ОТЧЕТ выполненный по форме установленной ГОСТ 31937-2011 </span><span style="color: #000000; font-size: 12pt;">Приложения Б, В</span><span style="font-size: 12pt; color: #000000;">, с учетом требований СП 13-102-2003</span><span style="color: #000000; font-size: 12pt;"> Глава 11,</span><span style="font-size: 12pt; color: #000000;"> а также в соответствии с рекомендациям
прописанными в Пособии по обследованию
строительных конструкций зданий.</span></span><span style="color: #000000; font-size: 12pt;">

</span><br><span style="color: #000000;"><span style="color: #000000;">&nbsp; &nbsp;<span style="font-size: 12pt; color: #000000;">Подробно ознакомиться
с вариантами выполнения отчетов можно в разделе </span></span><a href="<?= Iblock::sectionUrl(99) ?>"><span style="color: #0054a5;"><u><b><span style="font-size: 13pt; color: #0054a5;">«Примеры выполненных работ.&nbsp;</span></b></u></span></span><span style="font-size: 12pt;"><u><span style="color: #0000ff;"><b><span style="color: #0054a5; font-size: 13pt;">Примеры технических отчетов по результатам выполненных обследований
конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования»</span></b></span></u><span style="color: #0000ff;">.</span></span></a><span style="font-size: 12pt;">&nbsp;</span></p><p style="text-align: center;"><br><span style="font-size: 20pt; color: #000000;">&nbsp;</span><span style="font-size: 20pt; color: #000000;">

</span><span style="font-size: 20pt; color: #000000;">&nbsp;</span><span style="font-size: 12pt; color: #000000;"><span style="font-size: 20pt;">
</span>
</span><br><span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">Для чего Вам необходимы результаты
выполненного обследования</span></b></span><span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">
</span></b></span><br></p><p style="text-align: left;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Результаты выполненного
обследования необходимы:</span><span style="color: #000000; font-size: 12pt;">
</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">

</span><span style="color: #000000; font-size: 12pt;">&nbsp;- для предоставления
исходных данных проектировщикам при реконструкции, капитальном ремонте,
изменении функционального назначения здания или сооружения, техническом
перевооружении;</span><span style="color: #000000; font-size: 12pt;">
</span><br><span style="color: #000000; font-size: 12pt;">

&nbsp;&nbsp;&nbsp;- для документального
подтверждения фактического технического состояния здания или сооружения с целью
определения возможности безаварийной эксплуатации, а также планированию
мероприятий по предотвращению аварийных ситуаций;</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;- для признания здания или сооружения аварийным, в
качестве основания для сноса;
</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp;&nbsp;- по
требования государственных надзорных органов в качестве документального подтверждения
о состоянии здания или сооружения.</span></p><p style="text-align: center;"><span style="font-size: 12pt; color: #000000;"><br></span><span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">Срок действия результатов выполненного обследования</span></b></span></p><p><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Срок действия
результатов обследования зависит от целей его проведения, а именно:</span><span style="color: #000000; font-size: 12pt;">
</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp;&nbsp;- если
обследование проведено с целью сбора исходных данных для проектирования;</span><span style="color: #000000; font-size: 12pt;">

</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp;&nbsp;- если
обследование проведено с целью установления фактического состояния конструкций
зданий и сооружений.</span><span style="color: #000000; font-size: 12pt;">
</span><span style="font-size: 12pt; color: #000000;">
<br></span><span style="font-size: 12pt; color: #000000;"><b><br></b></span></p><p style="text-align: left;"></p><p style="text-align: center;"><span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 13pt; color: #0054a5;">Срок
действия обследования выполненного с целью сбора исходных данных для
проектирования</span></b></span></p><p style="color: #0054a5; font-size: 13pt; text-align: left;"><span style="text-align: left;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;Если
обследование выполнено с целью сбора исходных данных для проектирования (при
реконструкции, капитальном ремонте, изменении технологического назначения
здания и пр.), то полученные результаты являются частью проектной документации
(поскольку относятся к инженерным изысканиям). Следовательно, срок действия
результатов обследования устанавливается нормативными документами относящимися
к проектной документаци</span><span style="color: #000000; font-size: 12pt;">и.&nbsp;</span></span></p><p style="text-align: left;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; К нормативным документам устанавливающим срок
действия проектной документации относятся:</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">

</span><br></span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;-</span><span style="color: #000000; font-size: 12pt;"> ВСН
58-88(р) «Положение об организации и проведении реконструкции, ремонта и
технического обслуживания зданий, объектов коммунального и
социально-культурного назначения», </span><span style="color: #000000; font-size: 12pt;">п.5.10.;</span></span><span style="color: #000000; font-size: 12pt;">
</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">

</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp;- МГСН
301.01-96 «Положение по организации капитального ремонта жилых зданий в
г.Москве», </span><span style="color: #000000; font-size: 12pt;">п.4.3.</span><span style="color: #000000; font-size: 12pt;"> (действует только
по г.Москва).</span></span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">
</span><span style="color: #000000; font-size: 12pt;">
</span><br></span><br><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; В
соответствии с ВСН 58-88(р) </span><span style="color: #000000; font-size: 12pt;">п.5.10:</span><span style="color: #000000; font-size: 12pt;"> </span><i><span style="color: #000000; font-size: 12pt;">«Интервал времени между утверждением
проектно-сметной документации и началом ремонтно-строительных работ </span><b><span style="color: #000000; font-size: 12pt;">не должен превышать 2 лет</span></b><span style="color: #000000; font-size: 12pt;">. Устаревшие
проекты должны перерабатываться проектными организациями по заданиям заказчиков
с целью доведения их технического уровня до современных требований и
переутверждаться в порядке, установленном для утверждения вновь разработанных
проектов.». </span></i></span><span style="color: #000000;"><i><span style="color: #000000; font-size: 12pt;">

</span></i></span><span style="color: #000000;"><i><span style="color: #000000; font-size: 12pt;">&nbsp;</span></i></span><span style="color: #000000;"><i><span style="color: #000000; font-size: 12pt;">
</span><br></i><span style="color: #000000; font-size: 12pt;">
</span></span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Из выше
сказанного следует что, в случае если утвержденная (получившая положительное
заключение экспертизы) проектно-сметная документация в течение 2 лет не была
использована по назначению, ее необходимо заново согласовывать в установленном
порядке. При этом необходимо учесть изменения состояния здания или сооружения
касающиеся, также, инженерных изысканий (в том числе результатов обследования)
и проектных решений которые могли произойти за 2 года. В случае отсутствия
изменений состояния здания или сооружения, проект (в том числе результатов
обследования) без существенных правок выпускается под новой датой.</span><br><br><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; В соответствии с МГСН 301.01-96 </span><span style="color: #000000; font-size: 12pt;">п.4.3: </span><i><span style="color: #000000; font-size: 12pt;">«Вм</span><span style="color: #000000; font-size: 12pt;">есте
с заданием на проектирование комплексного капитального ремонта с
перепланировкой (встройкой, пристройкой, надстройкой, устройством мансардных
этажей) заказчик выдает проектной организации: - инвентаризационные поэтажные
планы (в кальке) с указанием площадей помещений и объема здания по данным бюро
технической инвентаризации (БТИ), </span><b><span style="color: #000000; font-size: 12pt;">проведенной
не позднее 3-х лет до начала проектирования;».
</span><br></b></i><span style="color: #000000; font-size: 12pt;">
</span></span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">Из выше
сказанного следует что, если планы БТИ выполнены позднее 3-х лет до начала проектирования,
то необходимо заново проводить обмерочные работы. (следует также учесть что, МГСН
301.01-96 действует только по г.Москва).</span><span style="color: #000000; font-size: 12pt;">
</span><span style="color: #000000;"><span style="color: #000000;">
</span><br></span><br></p><p style="text-align: center;"><span style="font-size: 12pt; color: #000000;"><b><span style="color: #0054a5; font-size: 13pt;">Срок
действия обследования проведенного с целью установления фактического состояния
здания или сооружения</span></b></span></p><p style="text-align: left;"><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;&nbsp;</span><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp;Основным
нормативным документом, регламентирующим срок действия результатов обследования
с целью установления фактического состояния здания или сооружения, является ГОСТ
31937-2011 </span><span style="color: #000000; font-size: 12pt;">п.4.3-4.4</span><span style="font-size: 12pt; color: #000000;">, в соответствии
с которым:</span><br></span><span style="color: #000000; font-size: 12pt;">

</span><br><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp;&nbsp;&nbsp; </span><i><span style="font-size: 12pt; color: #000000;">«4.3 </span><b><span style="font-size: 12pt; color: #000000;">Первое
обследование технического состояния зданий и сооружений проводится не позднее
чем через два года после их ввода в эксплуатацию. </span></b><span style="font-size: 12pt; color: #000000;">В дальнейшем обследование
технического состояния зданий и сооружений проводится </span><b><span style="font-size: 12pt; color: #000000;">не реже одного раза в 10 лет и не реже одного раза в пять лет для зданий и
сооружений или их отдельных элементов, работающих в неблагоприятных условиях</span></b><span style="font-size: 12pt; color: #000000;">
(агрессивные среды, вибрации, повышенная влажность, сейсмичность района 7
баллов и более и др.). Для уникальных зданий и сооружений устанавливается
постоянный режим мониторинга.</span></i></span><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">

</span><br></i></span><br><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">&nbsp;&nbsp; 4.4 Обследование и мониторинг технического
состояния зданий и сооружений проводят также:</span></i></span><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">

</span></i></span><br><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по истечении нормативных сроков
эксплуатации зданий и сооружений;</span></i></span><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">

</span></i></span><br><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - при обнаружении значительных дефектов,
повреждений и деформаций в процессе технического обслуживания, осуществляемого
собственником здания (сооружения);</span></i></span><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">

</span></i></span><br><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по результатам последствий пожаров,
стихийных бедствий, аварий, связанных с разрушением здания (сооружения);</span></i></span><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">

</span></i></span><br><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по инициативе собственника объекта;</span></i></span><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">

</span></i></span><br><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - при изменении технологического
назначения здания (сооружения);</span></i></span><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">

</span></i></span><br><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - по предписанию органов, уполномоченных
на ведение государственного строительного надзора.»</span></i></span><span style="color: #000000;"><i><span style="font-size: 12pt; color: #000000;">
</span></i><span style="font-size: 12pt; color: #000000;">
</span></span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">

</span><br><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Из выше сказанного
становится понятно что, в соответствии с нормативными требованиями обследование
новостроек проводится в обязательном порядке после 2 лет эксплуатации. Здания и
сооружения эксплуатирующиеся более 2-х лет подлежат обязательному обследованию
через 10 лет (через 5 лет для зданий и сооружений работающих в неблагоприятных
условиях). Также, возможно проведение обследование раньше установленных сроков
в случаях перечисленных выше </span><span style="color: #000000; font-size: 12pt;">в </span><span style="color: #000000; font-size: 12pt;">п.4.4.</span><span style="font-size: 12pt; color: #000000;"> ГОСТ 31937-2011.</span></span></p><p></p><p></p><p><span style="font-size: 12pt; color: #000000;"><br></span></p><p align="center"></p><p></p><p></p><p style="text-align: center;"><span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">В каких случаях Вам лучше провести мониторинг</span></b></span></p><p><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; &nbsp; Мониторинг
технического состояния здании или сооружений - система наблюдений за развитием
дефектов, повреждений и деформаций объекта во времени с целью получения
достоверных параметров технического состояния, для своевременного выявления&nbsp;недопустимых отклонений,
а также для предупреждения и устранения возможных негативных явлений и
процессов.</span></span><span style="font-size: 12pt;"><br></span></p><p><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Средняя продолжительность мониторинга, в
зависимости от специфики и технического состояния объекта, составляет 6
месяцев.</span></p><p><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Перед мониторингом, как правило, выполняется
обследование в объеме необходимом для сбора требуемых исходных данных. На
основании этих данных устанавливаются наиболее проблемные участки объекта и
составляется программа мониторинга.</span><span style="color: #000000;"><span style="color: #000000; font-size: 12pt;">
</span></span><span style="font-size: 12pt; color: #000000;"><br></span></p><p><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Мониторинг рекомендуется выполнять в
следующих случаях:</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;1. При необходимости отслеживания динамики развития
дефектов и повреждений, определения фактических величин деформаций конструкций
зданий и сооружений и сравнения их с расчетными и допустимыми значениями;</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">2. С целью определения причин возникновения и установления
степени опасности деформаций для нормальной эксплуатации зданий и сооружений;</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">3. Для принятия своевременных мер по борьбе с
возникающими дефектами, повреждениями и деформациями, или по устранению
последствий их появления;</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">4. С целью уточнения расчетных данных и
физико-механических характеристик грунтов;</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;</span><span style="color: #000000; font-size: 12pt;">5. С целью уточнения расчетных схем для различных
типов зданий, сооружений и коммуникаций;</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; </span><span style="color: #000000; font-size: 12pt;">6. С целью подтверждения эффективности принимаемых
профилактических и защитных мероприятий по предотвращения развития деформаций,
дефектов, повреждений и пр.</span></p><p><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Подробно ознакомиться с
полным комплексом работ выполняемых в составе мониторинга можно в разделе</span><span style="color: #000000; font-size: 12pt;"> </span><span style="font-size: 12pt; color: #000000;"><span style="color: #0000ff;"><a href="/what-we-do/monitoring/"><b><span style="color: #0054a5;"><u><span style="font-size: 13pt; color: #0054a5;">«Мониторинг технического состояния зданий и сооружений»</span></u></span></b></a>.</span></span></p><p style="text-align: center;"><span style="font-size: 12pt;"><u><br></u></span><br><span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">В каких случаях Вам лучше провести строительно-техническую экспертизу конструкций,
зданий, сооружений, помещений, инженерных сетей и оборудования. Судебную
экспертизу.</span></b></span><br></p><p style="text-align: left;"><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Заключение
эксперта значительно отличается от Отчета по результатам проведенного
обследования по форме, содержанию, а также по назначению.</span><br></span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; В отличие от обследования,
при проведении строительно-технической экспертизы, а также судебной экспертизы состояние
зданий и сооружений, дефекты, повреждения и недостатки рассматриваются, прежде
всего, с позиции нарушения требований действующих нормативно-правовых
документов. </span><br></span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; При
проведении строительно-технической экспертизы, а также судебной экспертизы
выявленный дефект или недостаток считается таковым и описывается только в том
случае&nbsp;</span><br></span><br><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">если нарушает требования строительных норм или
законодательных актов (см. «Примеры выполнения работ. </span><br></span><br><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Кроме того, в
составе экспертизы может производиться фиксация выполненных (не выполненных),
не качественно выполненных работ, а также определяться их стоимость. </span><br></span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Строительно-техническую
экспертизу, а также судебную экспертизу рекомендуется выполнять в следующих
случаях:</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp;1.
Установление технического состояния здания или сооружения с целью получения
средств на ремонтно-восстановительные мероприятия (в т.ч. для суда);</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp;2.
Установление причин возникновения повреждений, дефектов и недостатков с целью
взыскать средства с лиц виновных в их возникновении (в т.ч. для суда);</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; 3. Признание
здания или сооружения не пригодным к эксплуатации и представляющим опасность
для жизни и здоровья людей (в т.ч. для суда);</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;4. С цель
получения обоснования необходимости сноса здания или сооружения (в т.ч. для
суда);</span><span style="color: #000000; font-size: 12pt;">&nbsp;</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp; 5. При вводе
здания или сооружения в эксплуатацию через суд;</span><br><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;6. Установление
технического состояния здания или сооружения с целью взыскать средства с лиц
виновных в ухудшении эксплуатационных характеристик здания или сооружения (в
т.ч. для суда);</span><br></span><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp;&nbsp;7. Установление
качества выполненных работ (в т.ч. для суда);</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; 8.
Установление объема качественно (не качественно) выполненных работ (в т.ч. для
суда);</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;9.
Установление стоимости фактически выполненных (не выполненных) работ (в т.ч.
для суда);</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp;&nbsp;10.
Установление стоимости качественно (не качественно) выполненных работ (в т.ч.
для суда.</span><br></span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Подробно ознакомиться с полным комплексом
работ выполняемых в составе строительно-технической экспертизы можно в разделе</span><span style="font-size: 12pt; color: #000000;"><span style="color: #000000;"> </span><a href="/what-we-do/examination/"><span style="color: #0000ff;"><b><span style="color: #0054a5;"><span style="color: #0054a5;"><u><span style="font-size: 13pt; color: #0054a5;">«Строительно-техническая экспертиза конструкций,
зданий, сооружений, помещений, инженерных сетей и оборудования. Судебная
экспертиза</span></u></span><span style="color: #0054a5;"><u><span style="font-size: 13pt; color: #0054a5;">»</span></u></span></span></b></span></a><span style="color: #2f3192;">.</span></span></p><p style="text-align: center;"><span style="font-size: 12pt; color: #000000;"><br></span><br><span style="font-size: 12pt; color: #000000;"><b><span style="font-size: 24pt; color: #0054a5;">В каких случаях Вам лучше выполнить отдельные виды работ по экспертизе и
обследованию, или провести экспертизу отдельных материалов, деталей, изделий,
конструкций, элементов конструкций и пр.</span></b></span><span style="font-size: 12pt; color: #000000;"><b><span style="color: #0054a5; font-size: 24pt;">
</span></b></span><br></p><p style="text-align: left;"><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Выполнение
отдельных видов работ по обследованию и экспертизе производятся в случаях,
когда необходимо зафиксировать отдельные данные и установить отдельные
параметры строительных конструкций зданий и сооружений, инженерных сетей и
оборудования.&nbsp;</span><br></span><br><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; В результате проведенных работ фиксируются фактические
данные и устанавливаются фактические параметры строительных конструкций зданий
и сооружений, инженерных сетей и оборудования, а также определяется
соответствие зафиксированных данных и установленных параметров требованиям договорной,
проектной, исполнительной, нормативной документации и пр.</span><br></span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; В формате
данных работ заказчик самостоятельно устанавливает объем и их состав исходя из
своих потребностей. При этом, стоимость зависит только от перечня выполняемых
работ без учета размеров здания или сооружения, размеров и типа обследуемых
конструкций, количества инженерных сетей и оборудования.</span><span style="font-size: 12pt;"><br></span></p><p style="text-align: left;"><span style=""><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; Отдельные
виды работ по обследованию и экспертизе следует выполнять в следующих случаях:</span><br></span><span style=""><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;1. При
необходимости установить степень уплотнения естественных грунтов основания, а
также насыпных грунтов;</span><br></span><span style=""><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;2.&nbsp;При
необходимости установить прочность бетона, определить его класс и марку,
определить прочность кирпича, раствора и пр.;</span><br></span><span style=""><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;3. При
необходимости установления марки стали;</span><br></span><span style=""><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;4. При
необходимости определения качества выполнения сварных швов;</span><br></span><span style=""><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;5. При
необходимости установления усилия затяжки гаек болтовых соединений;</span><br></span><span style=""><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;6. При
необходимости установления величины термического сопротивления ограждающих
конструкций (стен, кровли и пр.);</span><br></span><span style=""><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;7. При
необходимости установления соответствия уровня шума нормативным параметрам;</span><br></span><span style=""><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;8. При
необходимости определения величины влажности материалов;</span><br></span><span style=""><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;9.&nbsp;При
необходимости выявить участки ограждающих конструкций, через которые происходят
теплопотери;</span><br></span><span style="font-size: 12pt; color: #000000;">&nbsp; &nbsp;10. При
необходимости определить и зафиксировать температуру теплоносителя в системе
отопления или температуру воды в системе водоснабжения.</span></p><p style="text-align: left;"><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;&nbsp;</span><span style="color: #000000;"><span style="font-size: 12pt; color: #000000;">&nbsp;Обследование и экспертиза отдельных материалов,
деталей, изделий, узлов, конструкций, элементов конструкций и пр. производится
в случаях, когда необходимо установить их техническое состояния, качество
изготовления (выявить брак или подделку), качество монтажа и причины разрушения
(повреждения). При этом, для выполнения экспертизы или обследования не
требуется учитывать влияние размеров, объемно-планировочных решений,
конструктивных и технических особенностей здания или сооружения, конструктивной
схемы и особенности инженерных сетей и оборудования здания или сооружения.</span><br></span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; При выполнении обследования и экспертизы в
данном формате объем работ устанавливается заказчиком, а стоимость работ
зависит только от количества обследуемых объектов (как правило количество
штук), без учета размеров здания или сооружения, объемно-планировочных и
конструктивных особенностей здания или сооружения, количества и технических
особенностей инженерных сетей и (или) оборудования.</span><span style="font-size: 12pt; color: #000000;"><br></span></p><p style="text-align: left;"><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Обследование и экспертиза отдельных
материалов, деталей, изделий, узлов, конструкций, элементов конструкций и пр.
производится в следующих случаях:</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;1. При
необходимости установить техническое состояние или качество выполнения
отдельного оборудования, отдельной конструкции, материала, изделия, детали и
пр.;</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;2.&nbsp;При
необходимости установить причины повреждения, разрушения и пр. отдельной
детали, изделия и пр.;</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp;3.&nbsp;При
необходимости установить и зафиксировать брак или подделку изделия, детали,
материала и пр.</span><br><span style="color: #000000; font-size: 12pt;">&nbsp;</span><span style="color: #000000; font-size: 12pt;">







</span><br><span style="color: #000000; font-size: 12pt;">&nbsp; &nbsp; &nbsp; Подробно ознакомиться
с полным комплексом работ производимых в составе выполнения отдельных видов
работ по экспертизе и обследованию можно в разделе</span><span style="font-size: 12pt; color: #000000;"> <span style="color: #0000ff;"><a href="/what-we-do/individual/"><span style="color: #0054a5;"><b><u><span style="font-size: 13pt; color: #0054a5;">«Выполнение
отдельных видов работ по экспертизе и обследованию. Экспертиза отдельных
материалов, деталей, изделий, конструкций, элементов конструкций и пр.»</span></u></b></span></a>.</span></span></p><p style="text-align: center;"></p><p></p><p></p><p></p><p></p><p></p><p></p>
<p>
</p>
<p>
</p>
<p>
</p>
<p>
</p><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>