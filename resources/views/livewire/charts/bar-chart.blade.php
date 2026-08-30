@php
    $chartWidth = 750;
    $chartHeight = 350;
    $padTop = 20;
    $padRight = 20;
    $padBottom = 100;
    $padLeft = 80;

    $maxAmount = !empty($categoryBreakdown) ? max(array_column($categoryBreakdown, 'amount')) : 0;
    $niceMax = max(1, ceil($maxAmount / 55) * 55);
    $plotHeight = $chartHeight - $padTop - $padBottom;
    $plotWidth = $chartWidth - $padLeft - $padRight;

    $barCount = count($categoryBreakdown);
    $barSlotWidth = $barCount > 0 ? $plotWidth / $barCount : $plotWidth;
    $barWidth = $barSlotWidth * 0.6;
    $barOffset = $barSlotWidth * 0.2;

    $yTicks = [0, $niceMax / 4, $niceMax / 2, 3 * $niceMax / 4, $niceMax];
@endphp

<div class="reports-chart-wrapper">
    <div class="reports-bar-chart">
        <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" width="100%" preserveAspectRatio="xMidYMid meet">
            @foreach($yTicks as $tick)
                @php
                    $y = $padTop + $plotHeight - ($tick / $niceMax) * $plotHeight;
                @endphp
                <line
                    x1="{{ $padLeft }}" y1="{{ $y }}"
                    x2="{{ $chartWidth - $padRight }}" y2="{{ $y }}"
                    stroke="#e5e7eb" stroke-dasharray="4,4"
                />
                <text
                    x="{{ $padLeft - 10 }}" y="{{ $y + 4 }}"
                    text-anchor="end"
                >{{ $tick }}</text>
            @endforeach

            <line
                x1="{{ $padLeft }}" y1="{{ $padTop + $plotHeight }}"
                x2="{{ $chartWidth - $padRight }}" y2="{{ $padTop + $plotHeight }}"
                stroke="#e5e7eb" stroke-width="1"
            />

            @foreach($categoryBreakdown as $index => $cat)
                @php
                    $barHeight = ($cat['amount'] / $niceMax) * $plotHeight;
                    $x = $padLeft + ($index * $barSlotWidth) + $barOffset;
                    $y = $padTop + $plotHeight - $barHeight;
                @endphp
                <rect
                    x="{{ $x }}" y="{{ $y }}"
                    width="{{ $barWidth }}" height="{{ $barHeight }}"
                    fill="#8B5CF6" rx="4"
                />
            @endforeach

            @foreach($categoryBreakdown as $index => $cat)
                @php
                    $x = $padLeft + ($index * $barSlotWidth) + ($barSlotWidth / 2);
                    $y = $padTop + $plotHeight + 16;
                @endphp
                <text
                    x="{{ $x }}" y="{{ $y }}"
                    text-anchor="end"
                    transform="rotate(-45, {{ $x }}, {{ $y }})"
                >{{ $cat['name'] }}</text>
            @endforeach
        </svg>
    </div>
</div>
