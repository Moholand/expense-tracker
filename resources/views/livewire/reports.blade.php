<div class="reports">
    <h1 class="title">Expense Reports</h1>
    <p class="description">Analyze your spending patterns and trends</p>

    <div class="reports-filter-card">
        <div class="reports-filter-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
            <span>Filters</span>
        </div>

        <div class="reports-filter-dates">
            <div class="reports-date-group">
                <label for="startDate">From Date</label>
                <div class="reports-date-input-wrapper">
                    <input type="date" id="startDate" wire:model.live="startDate" class="reports-date-input">
                </div>
            </div>
            <div class="reports-date-group">
                <label for="endDate">To Date</label>
                <div class="reports-date-input-wrapper">
                    <input type="date" id="endDate" wire:model.live="endDate" class="reports-date-input">
                </div>
            </div>
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
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="12" x2="12" y1="2" y2="22"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="reports-stat-info">
                <p class="reports-stat-label">Total Expenses (Toman)</p>
                <p class="reports-stat-value">{{ number_format($totalExpenses) }}</p>
            </div>
        </div>

        <div class="reports-stat-card">
            <div class="reports-stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1Z"></path>
                    <path d="M14 8h-4"></path>
                    <path d="M14 12h-4"></path>
                    <path d="M14 16h-4"></path>
                </svg>
            </div>
            <div class="reports-stat-info">
                <p class="reports-stat-label">Transactions</p>
                <p class="reports-stat-value">{{ $transactionCount }}</p>
            </div>
        </div>

        <div class="reports-stat-card">
            <div class="reports-stat-icon orange">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                    <polyline points="16 7 22 7 22 13"></polyline>
                </svg>
            </div>
            <div class="reports-stat-info">
                <p class="reports-stat-label">Average Weekly (Toman)</p>
                <p class="reports-stat-value">{{ number_format($averageWeekly) }}</p>
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
            <livewire:chart.pie-chart
                :key="'pie-'.md5(json_encode($categoryBreakdown))"
                :categoryBreakdown="$categoryBreakdown"
            />
        @else
            <livewire:chart.bar-chart
                :key="'bar-'.md5(json_encode($categoryBreakdown))"
                :categoryBreakdown="$categoryBreakdown"
            />
        @endif

        <table class="reports-breakdown-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th style="text-align: center;">Total Amount (Toman)</th>
                    <th style="text-align: center;">Percentage</th>
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
                        <td class="amount-cell" style="text-align: center;">{{ number_format($cat['amount'], 2) }}</td>
                        <td class="percent-cell" style="text-align: center;">{{ $cat['percentage'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="reports-spending-over-time">
        <div class="reports-breakdown-header">
            <h2 class="reports-breakdown-title">Spending Over Time</h2>
            <div class="reports-chart-toggle-group">
                <button
                    wire:click="switchTimePeriod('weekly')"
                    class="reports-chart-toggle {{ $timePeriod === 'weekly' ? 'active' : '' }}"
                >Weekly</button>
                <button
                    wire:click="switchTimePeriod('monthly')"
                    class="reports-chart-toggle {{ $timePeriod === 'monthly' ? 'active' : '' }}"
                >Monthly</button>
            </div>
        </div>

        @php
            $spendingData = $timePeriod === 'monthly' ? $monthlySpending : $weeklySpending;
        @endphp

        <livewire:chart.line-chart
            :key="$timePeriod.'-'.md5(json_encode($spendingData))"
            :spendingData="$spendingData"
            :timePeriod="$timePeriod"
        />

        <table class="reports-breakdown-table">
            <thead>
                <tr>
                    <th>{{ $timePeriod === 'monthly' ? 'Month' : 'Week' }}</th>
                    <th style="text-align: center;">Transactions</th>
                    <th style="text-align: center;">Total (Toman)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($spendingData as $item)
                    <tr>
                        <td>{{ $timePeriod === 'monthly' ? $item['month'] : $item['week'] }}</td>
                        <td style="text-align: center;">{{ $item['transactions'] }}</td>
                        <td style="text-align: center;">{{ number_format($item['total'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
