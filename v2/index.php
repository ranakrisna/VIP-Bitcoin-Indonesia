<html>
<head>
    <title id='Description'>Bitcoin Indonesia</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <style type="text/css">
        table.loading {
            position: relative;
        }
        table.loading:after {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.1);
            background-image: url(loading.gif);
            background-position: center;
            background-repeat: no-repeat;
            background-size: 30px 30px;
            content: "";
        }
    </style>
</head>
<body class='default'>
    <input type="hidden" id="market">
        <div class="container-fluid">
            <div class="col-md-4">
                <div class="table-responsive">
                    <label><b>Market List</b></label>
                    <table id='market_prices' class="table table-sm table-bordered table-hover table-condensed table-sortable">
                        <thead style="font-size: 13px;">
                            <tr class="active">
                                <td style="vertical-align : middle;text-align:center;" rowspan="2" width="25%">Market</td>
                                <td style="vertical-align : middle;text-align:center;" rowspan="2" width="30%">Price</td>
                                <td align="center" colspan="2" width="40%">Change</td>
                            </tr>
                            <tr class="active">
                                <td align="center" width="28%">Price</td>
                                <td align="center" width="12%">%</td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-8 market-detail">
                <div class="row">
                    <div class="col-md-3">
                        <label> Last Price : </label>
                        <label id="last_price">-</label>
                    </div>
                    <div class="col-md-3">
                        <label> High : </label>
                        <label id="high">-</label>
                    </div>
                    <div class="col-md-3">
                        <label> Low : </label>
                        <label id="low">-</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <label><b>Buy Orders</b></label>
                            <table id='buy_orders' class="table table-sm table-bordered table-hover table-condensed table-sortable">
                                <thead style="font-size: 13px;">
                                    <tr class="active">
                                        <td align="center" width="30%">Harga</td>
                                        <td align="center" width="35%" class="price_from">BTC</td>
                                        <td align="center" width="35%" class="price_to">IDR</td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <label><b>Sell Orders</b></label>
                            <table id='sell_orders' class="table table-sm table-bordered table-hover table-condensed table-sortable">
                                <thead style="font-size: 13px;">
                                    <tr class="active">
                                        <td align="center" width="30%">Harga</td>
                                        <td align="center" width="35%" class="price_from">BTC</td>
                                        <td align="center" width="35%" class="price_to">IDR</td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <label><b>Last Trades</b></label>
                            <table id='last_trades' class="table table-sm table-bordered table-hover table-condensed table-sortable">
                                <thead style="font-size: 13px;">
                                    <tr class="active">
                                        <td align="center" width="20%">Waktu</td>
                                        <td align="center" width="10%">Jenis</td>
                                        <td align="center" width="25%">Harga</td>
                                        <td align="center" width="20%" class="price_from">BTC</td>
                                        <td align="center" width="25%" class="price_to">IDR</td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script type="text/javascript">
        $(document).ready(function () {
            getData();
        });
        function getData(detail = 'btcidr', )
        {
            $('.market-detail table').addClass("loading");
            $("#market").val(detail);
            price_to = detail.slice(-3);
            price_from = detail.replace(price_to, '');
            $.ajax({
                url: "curl.php",
                dataType: "json",
                data: {
                    id: detail,
                },
                success: function(data) {
                    $("#last_price").text(data._24h.last_price);
                    $("#high").text(data._24h.high);
                    $("#low").text(data._24h.low);
                    $("#market_prices tbody").html(data.market);
                    $("#buy_orders tbody").html(data.frame_buy_orders);
                    $("#sell_orders tbody").html(data.frame_sell_orders);
                    $("#last_trades tbody").html(data.frame_last_trades);
                    tableClass();
                    $('.price_to').text(price_to.toUpperCase());
                    $('.price_from').text(price_from.toUpperCase());
                    $('.market-detail table').removeClass("loading");
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('.market-detail table').removeClass("loading");
                    console.log("Ada kesalahan sistem!");
                }
            });
        }
        function tableClass(){
            $("#buy_orders tbody tr").slice(+10).remove();
            $("#sell_orders tbody tr").slice(+10).remove();
            $("#last_trades tbody tr").slice(+20).remove();
            $('td small').each(function() {
                $(this).replaceWith($(this).text());
            });
            $("table tbody").css("font-size", "12px"); 
        };
        setInterval(function(){ 
            getData($("#market").val());
        }, (10000) );
    </script>
</body>
</html>
