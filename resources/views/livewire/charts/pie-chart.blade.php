@php
    $size = 280;
    $center = $size / 2;
    $radius = $size / 5;
    $circumference = 2 * pi() * $radius;
    $offset = 0;
@endphp

<div class="reports-chart-wrapper">
    <div class="reports-pie-chart-container" style="position: relative; width: {{ $size }}px; height: {{ $size }}px;">
        <svg
            viewBox="0 0 {{ $size }} {{ $size }}"
            width="{{ $size }}"
            height="{{ $size }}"
            style="transform: rotate(-90deg);"
        >
            @foreach($categoryBreakdown as $index => $cat)
                @php
                    $segmentLength = ($cat['percentage'] / 100) * $circumference;
                @endphp
                <circle
                    cx="{{ $center }}"
                    cy="{{ $center }}"
                    r="{{ $radius }}"
                    fill="none"
                    stroke="{{ $cat['hex'] }}"
                    stroke-width="{{ 2 * $radius }}"
                    stroke-dasharray="{{ $segmentLength }} {{ $circumference - $segmentLength }}"
                    stroke-dashoffset="{{ -$offset }}"
                />
                @php
                    $offset += $segmentLength;
                @endphp
            @endforeach
        </svg>

        @php
            $labelRadius = $center + 35;
            $angle = -90;
        @endphp

        @foreach($categoryBreakdown as $cat)
            @php
                $midAngle = $angle + ($cat['percentage'] / 360) * 360 / 2;
                $midAngleRad = deg2rad($midAngle);
                $labelX = $center + $labelRadius * cos($midAngleRad);
                $labelY = $center + $labelRadius * sin($midAngleRad);
                $angle += ($cat['percentage'] / 100) * 360;
            @endphp
            <span
                class="reports-chart-label"
                style="
                    position: absolute;
                    left: {{ $labelX }}px;
                    top: {{ $labelY }}px;
                    transform: translate(-50%, -50%);
                    color: {{ $cat['hex'] }};
                    font-size: 13px;
                    font-weight: 500;
                    white-space: nowrap;
                "
            >{{ $cat['name'] }}: {{ $cat['percentage'] }}%</span>
        @endforeach
    </div>

    <div class="reports-legend">
        @foreach($categoryBreakdown as $cat)
            <div class="reports-legend-item">
                <span class="reports-legend-dot" style="background-color: {{ $cat['hex'] }};"></span>
                <span class="reports-legend-name">{{ $cat['name'] }}</span>
            </div>
        @endforeach
    </div>
</div>
