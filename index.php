<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>Bitcoin Indonesia</title>
    <link rel="stylesheet" href="https://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/styles/jqx.base.css" type="text/css" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1 minimum-scale=1" />	
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-demo/scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/jqxtabs.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-demo/scripts/demos.js"></script>
</style>
</head>
<body class='default'>
    <script type="text/javascript">
    var url = "<?php echo $url?>";
    $(document).ready(function () {
        showDataBtc();
        showDataIdr();
    });
    
    function showDataBtc(){
        var source = {
            dataType: "json",
            dataFields: [
                { name: 'id', type: 'string' },
                { name: 'market', type: 'string' },
                { name: 'prices', type: 'string' },
                { name: 'change', type: 'string' },
                { name: 'change_persen', type: 'float' },
                { name: 'code',type: 'string'}
                
            ],
            id: 'id',
            url: "curlBtc.php",
            cache:false
        };
        var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
                if (value < 0) {
                    return '<span style="margin-top: 6px; margin-right: 4px; float: ' + columnproperties.cellsalign + '; color: #ff0000;">' + (columnfield=='change_persen'?Math.abs(value): Math.abs(value).toFixed(8)) + (columnfield=='change_persen'?'%':' BTC') + '</span>';
                }
                else {
                    return '<span style="margin-top: 6px; margin-right: 4px; float: ' + columnproperties.cellsalign + '; color: #008000;">' + value + (columnfield=='change_persen'?'%':' BTC') + '</span>';
                }
            }
        var cellsrendererPrices = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
                return '<span style="margin-top: 6px; margin-right: 4px; float: ' + columnproperties.cellsalign + ';">' + value + ' BTC</span>';
            }
        var akses = function (row, column, value) {
            if (value.indexOf('#') != -1) {
                value = value.substring(0, value.indexOf('#'));
            }   
            var html = '<a title="Akses" id="btnakses" style="padding-left: 35%;" class ="btn btn-pencil" onclick="'+value+'">Detail</a>';    
            return html;
        };
        var filterChanged = false;
        var dataAdapter = new $.jqx.dataAdapter(source); 
        
        $("#jqxgridBtc").jqxGrid({
            width: "50%",
            autoheight: true,
            source: dataAdapter,
            columns: [
              { text: 'Market', width:"20%", dataField: 'market', isdefault: true, cellsalign: 'left', align: 'center'},
              { text: 'Last Prices', width:"30%", dataField: 'prices', cellsalign: 'right',  cellsrenderer: cellsrendererPrices, align: 'center'},
              { text: 'Prices', width:"30%", columngroup: 'Changes', dataField: 'change', cellsrenderer: cellsrenderer, cellsalign: 'right', align: 'center', cellsformat: 'D' },
              { text: '%', width:"20%", columngroup: 'Changes', dataField: 'change_persen', cellsrenderer: cellsrenderer, cellsalign: 'right', align: 'center'},
            ],
            columngroups: [
                { text: 'Change', align: 'center', name: 'Changes' }
            ]
        });

        $("#jqxgridBtc").on('rowselect', function (event) {
            var market="";
            if(event.args.row) market = event.args.row.id;

            showDataDetail(market);
        });
    }

    function showDataIdr(){
        var source = {
            dataType: "json",
            dataFields: [
                { name: 'id', type: 'string' },
                { name: 'market', type: 'string' },
                { name: 'prices', type: 'string' },
                { name: 'change', type: 'string' },
                { name: 'change_persen', type: 'float' },
                { name: 'code',type: 'string'}
                
            ],
            id: 'market',
            url: "curlIdr.php",
            cache:false
        };
        var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
                if (value < 0) {
                    return '<span style="margin-top: 6px; margin-right: 4px; float: ' + columnproperties.cellsalign + '; color: #ff0000;">' + (columnfield=='change_persen'?(value*-1): formatNum(value*-1))  + (columnfield=='change_persen'?'%':' IDR') + '</span>';
                }
                else {
                    return '<span style="margin-top: 6px; margin-right: 4px; float: ' + columnproperties.cellsalign + '; color: #008000;">' + (columnfield=='change_persen'?value:formatNum(value))  + (columnfield=='change_persen'?'%':' IDR') + '</span>';
                }
            }
        var cellsrendererPrices = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
                return '<span style="margin-top: 6px; margin-right: 4px; float: ' + columnproperties.cellsalign + ';">' + formatNum(value) + ' IDR</span>';
            }
        var akses = function (row, column, value) {
            if (value.indexOf('#') != -1) {
                value = value.substring(0, value.indexOf('#'));
            }   
            var html = '<a title="Akses" id="btnakses" style="padding-left: 35%;" class ="btn btn-pencil" onclick="'+value+'">Detail</a>';    
            return html;
        };
        var filterChanged = false;
        var dataAdapter = new $.jqx.dataAdapter(source); 
        
        $("#jqxgridIdr").jqxGrid({
            width: "50%",
            autoheight: true,
            source: dataAdapter,
            columns: [
              { text: 'Market', width:"20%", dataField: 'market', isdefault: true, cellsalign: 'left', align: 'center'},
              { text: 'Last Prices', width:"30%", dataField: 'prices', cellsalign: 'right',  cellsrenderer: cellsrendererPrices, align: 'center'},
              { text: 'Prices', width:"30%", columngroup: 'Changes', dataField: 'change', cellsrenderer: cellsrenderer, cellsalign: 'right', align: 'center'},
              { text: '%', width:"20%", columngroup: 'Changes', dataField: 'change_persen', cellsrenderer: cellsrenderer, cellsalign: 'right', align: 'center'},
            ],
            columngroups: [
                { text: 'Change', align: 'center', name: 'Changes' }
            ]
        });

        $("#jqxgridIdr").on('rowselect', function (event) {
            var market="";
            if(event.args.row) market = event.args.row.id;

            showDataDetail(market);
        });
    }

    function showDataDetail(market){
        console.log(market);
    }

    function formatNum(rawNum) {
        rawNum = "" + rawNum; // converts the given number back to a string
        var retNum = "";
        var j = 0;
        for (var i = rawNum.length; i > 0; i--) {
            j++;
            if (((j % 3) == 1) && (j != 1))
                retNum = rawNum.substr(i - 1, 1) + "." + retNum;
            else
                retNum = rawNum.substr(i - 1, 1) + retNum;
        }
        return retNum;
    }

    function showClearBtc(){
        $("#jqxgridBtc").jqxGrid('updatebounddata', 'refresh');
    }

    function showClearIdr(){
        $("#jqxgridIdr").jqxGrid('updatebounddata', 'refresh');
    }

    setInterval(function(){ 
        showClearIdr();
        showClearBtc();
    }, (6000) );
    
</script>
    <table>
        <tr>
            <td valign="top"><div id="jqxgridBtc"></div></td>
            <td valign="top"><div id="jqxgridIdr"></div></td>
        </tr>
    </table>
</body>
</html>
