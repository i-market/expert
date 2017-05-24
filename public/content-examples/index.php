<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Примеры контента");
?>

<h2>Заголовки</h2>

<h1>h1. Заголовок</h1>
<h2>h2. Заголовок</h2>
<h3>h3. Заголовок</h3>
<h4>h4. Заголовок</h4>
<h5>h5. Заголовок</h5>
<h6>h6. Заголовок</h6>

<h2>Элементы текста</h2>

<p><del>This line of text is meant to be treated as deleted text.</del></p>
<p><u>This line of text will render as underlined</u></p>
<p><b>This line rendered as bold text.</b></p>
<p><i>This line rendered as italicized text.</i></p>

<h2>Цитаты</h2>

<blockquote>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
</blockquote>

<h2>Списки</h2>

<h3>Неупорядоченный список</h3>

<ul>
    <li>Lorem ipsum dolor sit amet</li>
    <li>Consectetur adipiscing elit</li>
    <li>Integer molestie lorem at massa</li>
    <li>Facilisis in pretium nisl aliquet</li>
    <li>Nulla volutpat aliquam velit
        <ul>
            <li>Phasellus iaculis neque</li>
            <li>Purus sodales ultricies</li>
            <li>Vestibulum laoreet porttitor sem</li>
            <li>Ac tristique libero volutpat at</li>
        </ul>
    </li>
    <li>Faucibus porta lacus fringilla vel</li>
    <li>Aenean sit amet erat nunc</li>
    <li>Eget porttitor lorem</li>
</ul>

<h3>Упорядоченный список</h3>

<ol>
    <li>Lorem ipsum dolor sit amet</li>
    <li>Consectetur adipiscing elit</li>
    <li>Integer molestie lorem at massa</li>
    <li>Facilisis in pretium nisl aliquet</li>
    <li>Nulla volutpat aliquam velit</li>
    <li>Faucibus porta lacus fringilla vel</li>
    <li>Aenean sit amet erat nunc</li>
    <li>Eget porttitor lorem</li>
</ol>

<h2>Таблицы</h2>

<table border="1" cellpadding="1" cellspacing="1">
    <tbody>
    <tr>
        <td>
            #
        </td>
        <td>
            First Name
        </td>
        <td>
            Last Name
        </td>
        <td>
            Username
        </td>
    </tr>
    <tr>
        <td>
            1
        </td>
        <td>
            Mark
        </td>
        <td>
            Otto
        </td>
        <td>
            @mdo
        </td>
    </tr>
    <tr>
        <td>
            2
        </td>
        <td>
            Jacob
        </td>
        <td>
            Thornton
        </td>
        <td>
            @fat
        </td>
    </tr>
    <tr>
        <td>
            3
        </td>
        <td>
            Larry
        </td>
        <td>
            the Bird
        </td>
        <td>
            @twitter
        </td>
    </tr>
    </tbody>
</table>

<h2>Картинки</h2>

<h3>Выравнивание по центру</h3>

<p style="text-align: center;">
    <img src="/content-examples/placeholder.png" width="100" height="100">
</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac consectetur ex, et accumsan sapien. Morbi sit amet ex eu mi laoreet gravida non eu nibh. Nulla euismod eleifend orci in facilisis. Nulla velit sem, facilisis lacinia facilisis sed, sodales aliquam massa. Cras sem libero, fringilla eget luctus sed, euismod placerat sapien. Praesent sed ullamcorper nisi. Morbi ut lorem ac ex blandit tincidunt vel non orci. Proin ullamcorper efficitur viverra. Praesent id augue vitae nunc varius egestas. Nulla nec ornare eros, sit amet pellentesque nunc. Donec tincidunt neque velit, vel semper purus consectetur ac. Sed hendrerit urna tortor, at lacinia dolor finibus vitae. Donec consequat libero vitae mauris aliquet egestas.</p>

<h3>Выравнивание справа</h3>

<img src="/content-examples/placeholder.png" width="100" height="100" align="right">
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac consectetur ex, et accumsan sapien. Morbi sit amet ex eu mi laoreet gravida non eu nibh. Nulla euismod eleifend orci in facilisis. Nulla velit sem, facilisis lacinia facilisis sed, sodales aliquam massa. Cras sem libero, fringilla eget luctus sed, euismod placerat sapien. Praesent sed ullamcorper nisi. Morbi ut lorem ac ex blandit tincidunt vel non orci. Proin ullamcorper efficitur viverra. Praesent id augue vitae nunc varius egestas. Nulla nec ornare eros, sit amet pellentesque nunc. Donec tincidunt neque velit, vel semper purus consectetur ac. Sed hendrerit urna tortor, at lacinia dolor finibus vitae. Donec consequat libero vitae mauris aliquet egestas.</p>

<h2>Два абзаца</h2>

<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac consectetur ex, et accumsan sapien. Morbi sit amet ex eu mi laoreet gravida non eu nibh. Nulla euismod eleifend orci in facilisis. Nulla velit sem, facilisis lacinia facilisis sed, sodales aliquam massa. Cras sem libero, fringilla eget luctus sed, euismod placerat sapien. Praesent sed ullamcorper nisi. Morbi ut lorem ac ex blandit tincidunt vel non orci. Proin ullamcorper efficitur viverra. Praesent id augue vitae nunc varius egestas. Nulla nec ornare eros, sit amet pellentesque nunc. Donec tincidunt neque velit, vel semper purus consectetur ac. Sed hendrerit urna tortor, at lacinia dolor finibus vitae. Donec consequat libero vitae mauris aliquet egestas.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac consectetur ex, et accumsan sapien. Morbi sit amet ex eu mi laoreet gravida non eu nibh. Nulla euismod eleifend orci in facilisis. Nulla velit sem, facilisis lacinia facilisis sed, sodales aliquam massa. Cras sem libero, fringilla eget luctus sed, euismod placerat sapien. Praesent sed ullamcorper nisi. Morbi ut lorem ac ex blandit tincidunt vel non orci. Proin ullamcorper efficitur viverra. Praesent id augue vitae nunc varius egestas. Nulla nec ornare eros, sit amet pellentesque nunc. Donec tincidunt neque velit, vel semper purus consectetur ac. Sed hendrerit urna tortor, at lacinia dolor finibus vitae. Donec consequat libero vitae mauris aliquet egestas.</p>

<h2>Текст не завернутый в тег</h2>

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac consectetur ex, et accumsan sapien. Morbi sit amet ex eu mi laoreet gravida non eu nibh. Nulla euismod eleifend orci in facilisis. Nulla velit sem, facilisis lacinia facilisis sed, sodales aliquam massa. Cras sem libero, fringilla eget luctus sed, euismod placerat sapien. Praesent sed ullamcorper nisi. Morbi ut lorem ac ex blandit tincidunt vel non orci. Proin ullamcorper efficitur viverra. Praesent id augue vitae nunc varius egestas. Nulla nec ornare eros, sit amet pellentesque nunc. Donec tincidunt neque velit, vel semper purus consectetur ac. Sed hendrerit urna tortor, at lacinia dolor finibus vitae. Donec consequat libero vitae mauris aliquet egestas.

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>