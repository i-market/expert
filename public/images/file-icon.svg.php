<?
header('Content-type: image/svg+xml');
?>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="50" height="66" viewBox="0 0 50 66">
    <defs>
        <style>
            .cls-1, .cls-3, .cls-4 {
            fill: #4a97e5;
            }

            .cls-1 {
            stroke: #4a97e5;
            stroke-linejoin: round;
            stroke-width: 2px;
            fill-opacity: 0;
            }

            .cls-2, .cls-3 {
            font-size: 14px;
            }

            .cls-3 {
            text-anchor: middle;
            font-family: "OpenSans-Bold";
            font-weight: bold;
            }
        </style>
    </defs>
    <g>
        <rect x="1" y="1" width="48" height="64" class="cls-1"/>
        <text x="25" y="57" class="cls-2"><tspan class="cls-3"><?= mb_strtoupper($_REQUEST['extension']) ?></tspan></text>
        <rect x="7" y="21" width="36" height="2" class="cls-4"/>
        <rect x="11" y="11" width="28" height="4" class="cls-4"/>
        <rect x="7" y="29" width="36" height="2" class="cls-4"/>
        <rect x="7" y="37" width="36" height="2" class="cls-4"/>
    </g>
</svg>
