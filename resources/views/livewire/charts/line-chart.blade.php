@php
    $chartWidth = 750;
    $chartHeight = 350;
    $padTop = 20;
    $padRight = 20;
    $padBottom = 60;
    $padLeft = 80;

    $maxAmount = !empty($spendingData) ? max(array_column($spendingData, 'total')) : 0;
    $niceMax = max(1, ceil($maxAmount / 75) * 75);
    $plotHeight = $chartHeight - $padTop - $padBottom;
    $plotWidth = $chartWidth - $padLeft - $padRight;

    $dataCount = count($spendingData);
    $pointSpacing = $dataCount > 1 ? $plotWidth / ($dataCount - 1) : $plotWidth;

    $yTicks = [0, $niceMax * 0.25, $niceMax * 0.5, $niceMax * 0.75, $niceMax];

    $points = [];
    foreach ($spendingData as $index => $item) {
        $x = $padLeft + ($index * $pointSpacing);
        $y = $padTop + $plotHeight - ($item['total'] / $niceMax) * $plotHeight;
        $points[] = ['x' => $x, 'y' => $y, 'total' => $item['total']];
    }

    $pathD = '';
    if (count($points) > 1) {
        $pathD = 'M ' . $points[0]['x'] . ' ' . $points[0]['y'];
        for ($i = 0; $i < count($points) - 1; $i++) {
            $cp1x = $points[$i]['x'] + $pointSpacing * 0.4;
            $cp1y = $points[$i]['y'];
            $cp2x = $points[$i + 1]['x'] - $pointSpacing * 0.4;
            $cp2y = $points[$i + 1]['y'];
            $pathD .= ' C ' . $cp1x . ' ' . $cp1y . ', ' . $cp2x . ' ' . $cp2y . ', ' . $points[$i + 1]['x'] . ' ' . $points[$i + 1]['y'];
        }
    }
@endphp

<div class="reports-chart-wrapper">
    <div class="reports-line-chart">
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
                >${{ number_format($tick, 0) }}</text>
            @endforeach

            <line
                x1="{{ $padLeft }}" y1="{{ $padTop + $plotHeight }}"
                x2="{{ $chartWidth - $padRight }}" y2="{{ $padTop + $plotHeight }}"
                stroke="#e5e7eb" stroke-width="1"
            />

            @foreach($spendingData as $index => $item)
                @php
                    $x = $padLeft + ($index * $pointSpacing);
                    $y = $padTop + $plotHeight + 20;
                    $label = $timePeriod === 'monthly' ? \Carbon\Carbon::parse('1 ' . $item['month'])->format('M') : 'Week ' . ($index + 1);
                @endphp
                <text
                    x="{{ $x }}" y="{{ $y }}"
                    text-anchor="middle"
                >{{ $label }}</text>
            @endforeach

            @if(!empty($pathD))
                <path
                    d="{{ $pathD }}"
                    fill="none"
                    stroke="#8B5CF6"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            @endif

            @foreach($points as $point)
                <circle
                    cx="{{ $point['x'] }}"
                    cy="{{ $point['y'] }}"
                    r="5"
                    fill="#8B5CF6"
                    stroke="#ffffff"
                    stroke-width="2"
                />
            @endforeach
        </svg>
    </div>
</div>
