<div class="reports">
    <h1 class="title">Expense Reports</h1>
    <p class="description">Analyze your spending patterns and trends</p>

    <div class="reports-filter-card">
        <div class="reports-filter-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            <span>Filters</span>
        </div>

        <div class="reports-filter-dates">
            <div class="reports-date-group">
                <label for="startDate">From Date</label>
                <div class="reports-date-input-wrapper">
                    <svg class="reports-date-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                    <input type="date" id="startDate" wire:model.live="startDate" class="reports-date-input">
                </div>
            </div>
            <div class="reports-date-group">
                <label for="endDate">To Date</label>
                <div class="reports-date-input-wrapper">
                    <svg class="reports-date-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                    <input type="date" id="endDate" wire:model.live="endDate" class="reports-date-input">
                </div>
            </div>
            <button wire:click="generateReport" class="reports-generate-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
                Generate Report
            </button>
        </div>

        <div class="reports-category-filter">
            <p class="reports-category-label">Filter by Categories (optional)</p>
            <div class="reports-category-pills">
                @foreach($categories as $category)
                    <button
                        wire:click="toggleCategory({{ $category->id }})"
                        class="reports-pill {{ in_array($category->id, $selectedCategories) ? 'active' : '' }}"
                    >
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="reports-stats">
        <div class="reports-stat-card">
            <div class="reports-stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" x2="12" y1="2" y2="22"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <div class="reports-stat-info">
                <p class="reports-stat-label">Total Expenses</p>
                <p class="reports-stat-value">${{ number_format($totalExpenses) }}</p>
            </div>
        </div>

        <div class="reports-stat-card">
            <div class="reports-stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1Z"></path><path d="M14 8h-4"></path><path d="M14 12h-4"></path><path d="M14 16h-4"></path></svg>
            </div>
            <div class="reports-stat-info">
                <p class="reports-stat-label">Transactions</p>
                <p class="reports-stat-value">{{ $transactionCount }}</p>
            </div>
        </div>

        <div class="reports-stat-card">
            <div class="reports-stat-icon orange">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
            </div>
            <div class="reports-stat-info">
                <p class="reports-stat-label">Average Weekly</p>
                <p class="reports-stat-value">${{ number_format($averageWeekly) }}</p>
            </div>
        </div>
    </div>

    <div class="reports-breakdown">
        <div class="reports-breakdown-header">
            <h2 class="reports-breakdown-title">Breakdown by Category</h2>
            <div class="reports-chart-toggle-group">
                <button
                    wire:click="switchChartType('pie')"
                    class="reports-chart-toggle {{ $chartType === 'pie' ? 'active' : '' }}"
                >Pie Chart</button>
                <button
                    wire:click="switchChartType('bar')"
                    class="reports-chart-toggle {{ $chartType === 'bar' ? 'active' : '' }}"
                >Bar Chart</button>
            </div>
        </div>

        @if($chartType === 'pie')
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

            <table class="reports-breakdown-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th style="text-align: right;">Total Amount</th>
                        <th style="text-align: right;">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoryBreakdown as $cat)
                        <tr>
                            <td>
                                <span class="category-cell">
                                    <span class="category-dot" style="background-color: {{ $cat['hex'] }};"></span>
                                    {{ $cat['name'] }}
                                </span>
                            </td>
                            <td class="amount-cell" style="text-align: right;">${{ number_format($cat['amount'], 2) }}</td>
                            <td class="percent-cell" style="text-align: right;">{{ $cat['percentage'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="reports-chart-wrapper">
                <p style="color: #9ca3af; font-size: 14px; padding: 40px 0;">Bar chart coming soon.</p>
            </div>
        @endif
    </div>
</div>
