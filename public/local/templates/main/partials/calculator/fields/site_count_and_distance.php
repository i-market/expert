<?
use App\View as v;
?>
<? $macros->showInput('SITE_COUNT', $site_count['label'], [
    'required' => true,
    'type' => 'number',
    'input_attrs' => v::attrs([
        'class' => 'site-count',
        'min' => 1,
        'ic-post-to' => $apiEndpoint.'?hide_errors=1'
    ])
]) ?>
<? $macros->showOptionalSelect('DISTANCE_BETWEEN_SITES', $options['DISTANCE_BETWEEN_SITES'], 'Удаленность объектов друг от друга', [
    'required' => true,
    'show' => $showDistanceSelect,
    'class' => 'distance-between-sites',
    'show_warning' => $showDistanceWarning
]) ?>
