<?php

namespace Core;

class ShareButtons {
    static function shareUrls($url, $text) {
        return [
            'facebook' => "https://facebook.com/sharer/sharer.php?u=${url}",
            'twitter' => "https://twitter.com/intent/tweet/?text=${text}&amp;url=${url}",
            'odnoklassniki' => "https://ok.ru/dk?st.cmd=addShare&st.s=1&st._surl=${url}&st.comments=${text}",
            'vkontakte' => "https://vk.com/share.php?title=${$text}&amp;url=${url}",
            'google' => "https://plus.google.com/share?url=${url}"
        ];
    }
}