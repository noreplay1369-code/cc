<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$op_nonce = wp_create_nonce( 'op_nonce' );

$op_durations = array(
    'today'        => __( 'Today', 'openpos' ),
    'yesterday'    => __( 'Yesterday', 'openpos' ),
    'this_week'    => __( 'This Week', 'openpos' ),
    'last_7_days'  => __( 'Last 7 Days', 'openpos' ),
    'this_month'   => __( 'This Month', 'openpos' ),
    'last_30_days' => __( 'Last 30 days', 'openpos' ),
);
?>
<div class="wrap op-dashboard-v2">
    <h1 class="wp-heading-inline"><?php echo esc_html__( 'POS Dashboard', 'openpos' ); ?></h1>
    <hr class="wp-header-end">

    <div class="op-dash-toolbar">
        <div class="op-dash-rangebox">
            <span class="op-dash-rangelabel"><?php echo esc_html__( 'Date range:', 'openpos' ); ?></span>
            <div class="op-dash-filter" role="group">
                <?php foreach ( $op_durations as $key => $label ) : ?>
                    <button type="button"
                            class="op-dash-pill duration-option <?php echo ( $duration === $key ) ? 'is-active' : ''; ?>"
                            data-duration="<?php echo esc_attr( $key ); ?>">
                        <?php echo esc_html( $label ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        <a href="<?php echo esc_url( $pos_url ); ?>" class="op-dash-cta" target="_blank">
            <span class="dashicons dashicons-store"></span>
            <?php echo esc_html__( 'Goto POS', 'openpos' ); ?>
        </a>
    </div>

    <!-- Performance -->
    <div class="op-section-head"><h2><?php echo esc_html__( 'Performance', 'openpos' ); ?></h2></div>
    <div class="op-perf op-widget-ajax-data">
        <div class="op-perf__cell">
            <span class="op-perf__label"><?php echo esc_html__( 'Total Sales', 'openpos' ); ?></span>
            <span class="op-perf__value" id="op-perf-total-sales">&ndash;</span>
        </div>
        <div class="op-perf__cell">
            <span class="op-perf__label"><?php echo esc_html__( 'Profit', 'openpos' ); ?></span>
            <span class="op-perf__value" id="op-perf-net-profit">&ndash;</span>
        </div>
        <div class="op-perf__cell">
            <span class="op-perf__label"><?php echo esc_html__( 'Orders', 'openpos' ); ?></span>
            <span class="op-perf__value" id="op-perf-orders">&ndash;</span>
        </div>
        <div class="op-perf__cell">
            <span class="op-perf__label"><?php echo esc_html__( 'Avg. Order Value', 'openpos' ); ?></span>
            <span class="op-perf__value" id="op-perf-avg-order">&ndash;</span>
        </div>
        <div class="op-perf__cell">
            <span class="op-perf__label"><?php echo esc_html__( 'Cash Flow', 'openpos' ); ?></span>
            <span class="op-perf__value" id="op-perf-cash-flow">&ndash;</span>
        </div>
    </div>

    <!-- Balances -->
    <div class="op-section-head"><h2><?php echo esc_html__( 'Cash Drawers', 'openpos' ); ?></h2></div>
    <div class="op-dash-stats">
        <div class="op-stat-card op-stat-card--cash">
            <span class="op-stat-label"><?php echo esc_html__( 'Cash Balance', 'openpos' ); ?></span>
            <div class="op-stat-value-row">
                <span class="op-stat-value" id="openpos-cash-balance"><?php echo $dashboard_data['cash_balance']; ?></span>
                <a href="javascript:void(0);" id="reset-balance" class="op-stat-reset" title="<?php echo esc_attr__( 'Reset Balance', 'openpos' ); ?>">
                    <span class="dashicons dashicons-image-rotate"></span>
                </a>
            </div>
        </div>
        <?php if ( isset( $dashboard_data['debt_balance'] ) ) : ?>
            <div class="op-stat-card op-stat-card--debit">
                <span class="op-stat-label"><?php echo esc_html__( 'Debit Balance', 'openpos' ); ?></span>
                <div class="op-stat-value-row">
                    <span class="op-stat-value" id="openpos-debit-balance"><?php echo $dashboard_data['debt_balance']; ?></span>
                    <a href="javascript:void(0);" id="reset-debit-balance" class="op-stat-reset" title="<?php echo esc_attr__( 'Reset Balance', 'openpos' ); ?>">
                        <span class="dashicons dashicons-image-rotate"></span>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Charts -->
    <div class="op-section-head"><h2><?php echo esc_html__( 'Charts', 'openpos' ); ?></h2></div>

    <div class="op-card op-card--full">
        <div class="op-card__head"><h3><?php echo esc_html__( 'Sales overview', 'openpos' ); ?></h3></div>
        <div class="op-card__body op-widget-ajax-data">
            <div class="op-chart-box op-chart-box--tall"><canvas id="myChart"></canvas></div>
        </div>
    </div>

    <div class="op-dash-grid-2">
        <div class="op-card">
            <div class="op-card__head"><h3><?php echo esc_html__( 'Sale by Register', 'openpos' ); ?></h3></div>
            <div class="op-card__body op-widget-ajax-data">
                <div class="op-chart-box"><canvas id="myChart-pie"></canvas></div>
            </div>
        </div>
        <div class="op-card">
            <div class="op-card__head"><h3><?php echo esc_html__( 'Sales by Payment', 'openpos' ); ?></h3></div>
            <div class="op-card__body op-widget-ajax-data">
                <div class="op-chart-box"><canvas id="myChart-payment"></canvas></div>
            </div>
        </div>
    </div>

    <div class="op-card op-card--full">
        <div class="op-card__head"><h3><?php echo esc_html__( 'Sales by Seller', 'openpos' ); ?></h3></div>
        <div class="op-card__body op-widget-ajax-data">
            <div class="op-chart-box"><canvas id="myChart-seller"></canvas></div>
        </div>
    </div>

    <!-- Leaderboard / Last orders -->
    <div class="op-section-head"><h2><?php echo esc_html__( 'Last Orders', 'openpos' ); ?></h2></div>
    <div class="op-card op-card--full">
        <div class="op-card__body op-card__body--flush" id="table_div_latest_orders">
            <table class="wp-list-table widefat striped op-dash-table" id="lastest-order">
                <thead>
                    <tr>
                        <th><?php echo esc_html__( 'Order', 'openpos' ); ?></th>
                        <th><?php echo esc_html__( 'Customer', 'openpos' ); ?></th>
                        <th><?php echo esc_html__( 'Grand Total', 'openpos' ); ?></th>
                        <th><?php echo esc_html__( 'Sale By', 'openpos' ); ?></th>
                        <th><?php echo esc_html__( 'Created At', 'openpos' ); ?></th>
                        <th><?php echo esc_html__( 'Status', 'openpos' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $dashboard_data['order'] ) ) : ?>
                        <tr><td colspan="6" class="op-dash-empty"><?php echo esc_html__( 'No orders yet.', 'openpos' ); ?></td></tr>
                    <?php else : ?>
                        <?php foreach ( $dashboard_data['order'] as $order ) : ?>
                            <tr>
                                <td><?php echo $order['view']; ?></td>
                                <td><?php echo esc_html( $order['customer_name'] ); ?></td>
                                <td><?php echo $order['total']; ?></td>
                                <td><?php echo esc_html( $order['cashier'] ); ?></td>
                                <td><?php echo $order['created_at']; ?></td>
                                <td class="order_status"><?php echo $order['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .op-dashboard-v2 { margin-right: 20px; color: #1e1e1e; }
    .op-dashboard-v2 * { box-sizing: border-box; }

    /* Toolbar / date range */
    .op-dash-toolbar {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
        gap: 12px; margin: 16px 0 24px;
    }
    .op-dash-rangebox { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .op-dash-rangelabel { font-size: 13px; font-weight: 600; color: #50575e; }
    .op-dash-filter {
        display: inline-flex; flex-wrap: wrap; background: #fff; border: 1px solid #c3c4c7;
        border-radius: 6px; padding: 3px; gap: 2px;
    }
    .op-dash-pill {
        appearance: none; border: 0; background: transparent; color: #50575e;
        font-size: 13px; font-weight: 500; line-height: 1.6; padding: 6px 14px;
        border-radius: 4px; cursor: pointer; transition: background .15s ease, color .15s ease;
    }
    .op-dash-pill:hover { background: #f3eefa; color: #7f54b3; }
    .op-dash-pill.is-active { background: #7f54b3; color: #fff; }

    .op-dash-cta {
        display: inline-flex; align-items: center; gap: 6px; background: #7f54b3; color: #fff;
        text-decoration: none; font-size: 13px; font-weight: 600; padding: 8px 16px; border-radius: 6px;
        transition: background .15s ease;
    }
    .op-dash-cta:hover { background: #6b4794; color: #fff; }
    .op-dash-cta .dashicons { font-size: 18px; width: 18px; height: 18px; }

    /* Section header with rule (WooCommerce Analytics style) */
    .op-section-head { display: flex; align-items: center; gap: 16px; margin: 8px 0 12px; }
    .op-section-head h2 { margin: 0; font-size: 16px; font-weight: 600; color: #1e1e1e; white-space: nowrap; }
    .op-section-head::after { content: ""; flex: 1; height: 1px; background: #dcdcde; }

    /* Performance cards row */
    .op-perf {
        display: flex; flex-wrap: wrap; background: #fff; border: 1px solid #e0e0e0;
        border-radius: 4px; margin-bottom: 28px; position: relative; min-height: 96px; overflow: hidden;
    }
    .op-perf__cell {
        flex: 1 1 0; min-width: 160px; padding: 20px 22px; border-right: 1px solid #e0e0e0;
    }
    .op-perf__cell:last-child { border-right: 0; }
    .op-perf__label { display: block; font-size: 13px; color: #6c7781; margin-bottom: 10px; }
    .op-perf__value { display: block; font-size: 28px; font-weight: 400; color: #1e1e1e; line-height: 1.2; }
    .op-perf__value .woocommerce-Price-currencySymbol { font-weight: 400; }
    @media screen and (max-width: 960px) {
        .op-perf__cell { flex-basis: 50%; border-bottom: 1px solid #e0e0e0; }
    }

    /* Stat (balance) cards */
    .op-dash-stats {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px; margin-bottom: 28px;
    }
    .op-stat-card {
        background: #fff; border: 1px solid #e0e0e0; border-left: 4px solid #7f54b3;
        border-radius: 4px; padding: 16px 20px;
    }
    .op-stat-card--debit { border-left-color: #d63638; }
    .op-stat-label {
        display: block; font-size: 13px; color: #6c7781; margin-bottom: 8px;
    }
    .op-stat-value-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .op-stat-value { font-size: 26px; font-weight: 600; color: #1e1e1e; line-height: 1.2; }
    .op-stat-card--debit .op-stat-value { color: #d63638; }
    .op-stat-reset {
        display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px;
        border-radius: 50%; color: #757575; background: #f6f7f7; text-decoration: none;
        transition: background .15s ease, color .15s ease;
    }
    .op-stat-reset:hover { background: #7f54b3; color: #fff; }

    /* Cards */
    .op-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; margin-bottom: 16px; overflow: hidden; }
    .op-card__head { padding: 16px 20px; border-bottom: 1px solid #f0f0f1; }
    .op-card__head h3 { margin: 0; font-size: 15px; font-weight: 600; color: #1e1e1e; }
    .op-card__body { padding: 20px; position: relative; min-height: 80px; }
    .op-card__body--flush { padding: 0; }

    .op-dash-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media screen and (max-width: 1100px) { .op-dash-grid-2 { grid-template-columns: 1fr; } }

    /* Chart sizing */
    .op-chart-box { position: relative; height: 300px; }
    .op-chart-box--tall { height: 340px; }
    .op-chart-box canvas { max-width: 100%; }

    /* Loading state */
    .op-widget-ajax-data.loading { opacity: .45; pointer-events: none; transition: opacity .2s ease; }
    .op-widget-ajax-data.loading::after {
        content: ""; position: absolute; top: 50%; left: 50%; width: 26px; height: 26px; margin: -13px 0 0 -13px;
        border: 3px solid #e0d6f0; border-top-color: #7f54b3; border-radius: 50%; animation: op-spin .7s linear infinite;
    }
    @keyframes op-spin { to { transform: rotate(360deg); } }

    /* Last orders table (override legacy #table_div_latest_orders styles with id-level specificity) */
    .op-dashboard-v2 #table_div_latest_orders .op-dash-table { border: 0; margin: 0; border-collapse: collapse; width: 100%; }
    .op-dashboard-v2 #table_div_latest_orders thead { background: #fff; color: #757575; }
    .op-dashboard-v2 #table_div_latest_orders thead th {
        background: #fff; text-align: left; border-bottom: 1px solid #e0e0e0; padding: 12px 16px;
        font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; color: #757575;
    }
    /* reset cramped/centered legacy cell styles + dark zebra */
    .op-dashboard-v2 #table_div_latest_orders tbody tr td { text-align: left; padding: 12px 16px; background: #fff; border-top: 1px solid #f0f0f1; }
    .op-dashboard-v2 #table_div_latest_orders tbody tr:first-child td { border-top: 0; }
    .op-dashboard-v2 #table_div_latest_orders tbody tr:nth-child(even) td { background: #fbfbfc; }
    .op-dashboard-v2 #table_div_latest_orders tbody tr:hover td { background: #f3eefa; }
    .op-dashboard-v2 #table_div_latest_orders td { vertical-align: middle; color: #2c3338; }
    /* right-align the monetary column (3rd) */
    .op-dashboard-v2 #table_div_latest_orders thead th:nth-child(3),
    .op-dashboard-v2 #table_div_latest_orders tbody td:nth-child(3) { text-align: right; }
    .op-dashboard-v2 #table_div_latest_orders tr td a { color: #7f54b3; font-weight: 600; text-decoration: none; text-transform: none; padding: 0; }
    .op-dashboard-v2 #table_div_latest_orders tr td a:hover { text-decoration: underline; }
    .op-dash-empty { text-align: center !important; color: #757575; padding: 28px 0 !important; }
    .op-dashboard-v2 #table_div_latest_orders .order_status span {
        display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;
        line-height: 1.6; background: #e6e6e6; color: #50575e; text-transform: capitalize;
    }
    .op-dashboard-v2 #table_div_latest_orders .order_status span.completed { background: #c6e1c6; color: #2c4700; }
    .op-dashboard-v2 #table_div_latest_orders .order_status span.processing { background: #c8d7e1; color: #003a5c; }
    .op-dashboard-v2 #table_div_latest_orders .order_status span.pending,
    .op-dashboard-v2 #table_div_latest_orders .order_status span.pending-payment { background: #f8dda7; color: #573b00; }
    .op-dashboard-v2 #table_div_latest_orders .order_status span.on-hold { background: #f8dda7; color: #573b00; }
    .op-dashboard-v2 #table_div_latest_orders .order_status span.refunded { background: #e6e6e6; color: #50575e; }
    .op-dashboard-v2 #table_div_latest_orders .order_status span.cancelled,
    .op-dashboard-v2 #table_div_latest_orders .order_status span.failed { background: #eba3a3; color: #5c0000; }
</style>

<script type="text/javascript">
    (function($) {
        $('body').on('click','#reset-balance',function () {
            if(confirm('<?php echo esc_js( __('This function to reset cash balance on your all cashdrawers to 0. Are you sure ?','openpos') ); ?>'))
            {
                $.ajax({
                    url: openpos_admin.ajax_url, type: 'post', dataType: 'json',
                    data:{action:'admin_openpos_reset_balance',op_nonce: '<?php echo $op_nonce; ?>'},
                    success:function(data){ $('#openpos-cash-balance').text(0); }
                })
            }
        });
        $('body').on('click','#reset-debit-balance',function () {
            if(confirm('<?php echo esc_js( __('This function to reset debit balance on your all cashdrawers to 0. Are you sure ?','openpos') ); ?>'))
            {
                $.ajax({
                    url: openpos_admin.ajax_url, type: 'post', dataType: 'json',
                    data:{action:'admin_openpos_reset_debit_balance',op_nonce: '<?php echo $op_nonce; ?>'},
                    success:function(data){ $('#openpos-debit-balance').text(0); }
                })
            }
        });

        $(document).on('ready',function(){
            <?php
                $label = array();
                $sale_data = array();
                $transaction_data = array();
                $commision_data = array();
                foreach($chart_data as $index =>  $c)
                {
                    if($index == 0)
                    {
                        continue;
                    }
                    $label[] = $c[0];
                    $sale_data[] = round($c[1],wc_get_price_decimals());
                    $transaction_data[] = $c[2];
                    $commision_data[] = round($c[3],wc_get_price_decimals());
                }
            ?>
            if (window.Chart && Chart.defaults && Chart.defaults.global) {
                Chart.defaults.global.defaultFontColor = '#50575e';
                Chart.defaults.global.defaultFontFamily = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif';
                Chart.defaults.global.legend.labels.usePointStyle = true;
            }
            var op_palette = ['#7f54b3','#3858e9','#00a32a','#f0b849','#e65054','#0aa2c0','#a7aaad','#674399'];

            var ctx = document.getElementById("myChart").getContext("2d");
            var   sale_data = <?php echo json_encode($sale_data) ?>;
            var   commission_data = <?php echo json_encode($commision_data) ?>;
            var   transaction_data = <?php echo json_encode($transaction_data) ?>;
            var labels =  <?php echo json_encode($label) ?>;

            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                    {
                        label: '<?php echo esc_js( __('Sales','openpos') ); ?>',
                        data: sale_data,
                        borderColor: '#7f54b3', backgroundColor: 'rgba(127,84,179,0.12)',
                        pointBackgroundColor: '#7f54b3', borderWidth: 2, fill: true, lineTension: 0.3
                    },
                    {
                        label: '<?php echo esc_js( __('Profit','openpos') ); ?>',
                        data: commission_data,
                        borderColor: 'rgba(0, 163, 42, 1)', backgroundColor: 'rgba(0, 163, 42, 0.12)',
                        pointBackgroundColor: 'rgba(0, 163, 42, 1)', borderWidth: 2, fill: true, lineTension: 0.3
                    }
                ]
                },
                options: { responsive: true, maintainAspectRatio: false, title: { display: false }, legend: { position: 'top', align: 'end' } }
            });

            var data = { datasets: [{ data: [], backgroundColor: [] }], labels: [] };

            var ctx_pie = document.getElementById("myChart-pie").getContext("2d");
            var myPieChart = new Chart(ctx_pie, { type: 'pie', data: data, options: { responsive: true, maintainAspectRatio: false, title: { display: false }, legend: { position: 'bottom' } } });

            var ctx_seller = document.getElementById("myChart-seller").getContext("2d");
            var mySellerChart = new Chart(ctx_seller, { type: 'horizontalBar', data: { labels: [], datasets: [] }, options: { responsive: true, maintainAspectRatio: false, title: { display: false }, legend: { display: false } } });

            var ctx_payment = document.getElementById("myChart-payment").getContext("2d");
            var myPaymentChart = new Chart(ctx_payment, { type: 'pie', data: data, options: { responsive: true, maintainAspectRatio: false, title: { display: false }, legend: { position: 'bottom' } } });

            function op_apply_palette(chartData){
                if(chartData && chartData.datasets && chartData.datasets[0]){
                    var ds = chartData.datasets[0];
                    if(!ds.backgroundColor || !ds.backgroundColor.length){
                        ds.backgroundColor = (chartData.labels || []).map(function(_, i){ return op_palette[i % op_palette.length]; });
                    }
                }
                return chartData;
            }

            function loadChart(duration){
                    $.ajax({
                            url: openpos_admin.ajax_url, type: 'post', dataType: 'json',
                            data: {action: 'op_dashboard', op_nonce: '<?php echo $op_nonce; ?>',duration:duration},
                            beforeSend:function(){ $('.op-widget-ajax-data').addClass('loading'); },
                            success:function(response){
                                var sale_data = response['sale_data'];
                                var register_data = response['register_data'];
                                var payment_data = response['payment_data'];
                                var seller_data = response['seller_data'];
                                //performance cards
                                if(response.performance){
                                    $('#op-perf-total-sales').html(response.performance.total_sales);
                                    $('#op-perf-net-profit').html(response.performance.net_profit);
                                    $('#op-perf-orders').html(response.performance.orders);
                                    $('#op-perf-avg-order').html(response.performance.avg_order);
                                    $('#op-perf-cash-flow').html(response.performance.cash_flow);
                                }
                                //sale chart
                                myChart.data.labels = sale_data.label;
                                myChart.data.datasets[0]['data'] = sale_data.data;
                                myChart.data.datasets[1]['data'] = sale_data.commission_data;
                                myChart.update();
                                //register chart
                                myPieChart.data = op_apply_palette(register_data.data);
                                myPieChart.update();
                                //seller chart
                                if(seller_data && seller_data.datasets && seller_data.datasets[0] && !seller_data.datasets[0].backgroundColor){
                                    seller_data.datasets[0].backgroundColor = '#7f54b3';
                                }
                                mySellerChart.data = seller_data;
                                mySellerChart.update();
                                //payment chart
                                myPaymentChart.data = op_apply_palette(payment_data);
                                myPaymentChart.update();

                                $('.op-widget-ajax-data').removeClass('loading');
                            }
                    });
            }

            loadChart('<?php echo esc_js( $duration ); ?>');

            $(document).on('click','.duration-option',function(){
                $('.duration-option').removeClass('is-active');
                $(this).addClass('is-active');
                var duration = $(this).data('duration');
                loadChart(duration);
            });
        });
    }(jQuery));
</script>
