<?php
require_once(__DIR__."/toolkit/init.php");
?>
<!doctype html>
<html>
    <head>
    	<title>Yandere</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
        <link rel="stylesheet" href="style/bootstrap-combined.min.css">
        <link rel="stylesheet" href="style/common.css">
        <style type="text/css">
        .yamai_list_item_box {
            margin: 0;
            padding: 2px;
            border-bottom: 1px solid gray;
        }
        .yamai_list_item_id{
            width: 15%;
            text-align: left;
            float: left;
        }
        .yamai_list_item_name{
            width: 25%;
            text-align: left;
            float: left;
        }
        .yamai_list_item_type{
            width: 20%;
            text-align: left;
            float: left;
        }
        .yamai_list_item_system{
            width: 20%;
            text-align: left;
            float: left;
        }
        .yamai_list_item_keyword{
            width: 20%;
            text-align: left;
            float: left;
        }
        </style>
        <script src="./style/jquery.min.js"></script>
        <script type="text/javascript" src="./style/bootstrap.min.js"></script>
        <script type="text/javascript" src="./style/common.js"></script>
        <script type="text/javascript">
        function onUpdateYK(){
            $.ajax({
                url: './controller/CommonAgent.php',
                type: 'POST',
                data: {
                    act: 'update',
                    yamai_name: $('#yamai_name').val(),
                    yamai_type: $('#yamai_type').val(),
                    yamai_system: $('#yamai_system').val(),
                    keyword: $('#keyword').val(),
                    score: $('#score').val()
                },
                dataType: 'json',
            })
            .done(function( data, textStatus, jqXHR ) {
                if(data && data.updated==1){
                    $('#update_feedback').html('Update Done!');
                }else{
                    $('#update_feedback').html('Update Failed!');
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                $('#update_feedback').html('AJAX Failed!');
            });
        }

        function refreshYamaiList(){
            $.ajax({
                url: './controller/CommonAgent.php',
                type: 'POST',
                data: {
                    act: 'list_yamai',
                    // yamai_name: $('#yamai_name').val(),
                    // yamai_type: $('#yamai_type').val(),
                    // yamai_system: $('#yamai_system').val(),
                    // keyword: $('#keyword').val(),
                    // score: $('#score').val()
                },
                dataType: 'json',
            })
            .done(function( data, textStatus, jqXHR ) {
                if(data && data.list){
                    // $('#yamai_list').html('Update Done! '+ JSON.stringify(data));

                    var h="";

                    h=h+"<div class='yamai_list_item_box'>";
                    h=h+"<div class='yamai_list_item_id'>ID</div>";
                    h=h+"<div class='yamai_list_item_name'>NAME</div>";
                    h=h+"<div class='yamai_list_item_type'>TYPE</div>";
                    h=h+"<div class='yamai_list_item_system'>SYSTEM</div>";
                    h=h+"<div class='yamai_list_item_keyword'>KEYWORD</div>";
                    h=h+"<div style='clear:both'></div></div>";

                    for (var i = 0; i <= data.list.length - 1; i++) {
                        h=h+"<div class='yamai_list_item_box'>";
                        h=h+"<div class='yamai_list_item_id'>"+data.list[i].yamai_id+"</div>";
                        h=h+"<div class='yamai_list_item_name'>"+data.list[i].yamai_name+"</div>";
                        h=h+"<div class='yamai_list_item_type'>"+data.list[i].yamai_type+"</div>";
                        h=h+"<div class='yamai_list_item_system'>"+data.list[i].yamai_system+"</div>";
                        h=h+"<div class='yamai_list_item_keyword'>"+data.list[i].keyword_list+"</div>";
                        h=h+"<div style='clear:both'></div></div>";
                    };

                    $('#yamai_list').html(h);

                }else{
                    $('#yamai_list').html('Update Failed!');
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                $('#yamai_list').html('AJAX Failed!');
            });
        }

        $(document).ready(function(){
            refreshYamaiList();
        });
        </script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="col-xs-10 col-xs-offset-1">
                    <div class="page-header">
                        <span class="header_title">
                            Yandere
                        </span>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="col-xs-12">
                    <div class="tabbable col-xs-10 col-xs-offset-1" id="tabs-865103">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#panel-view" data-toggle="tab">View</a>
                            </li>
                            <li>
                                <a href="#panel-update" data-toggle="tab">Update</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="panel-view">
                                <div id="yamai_list">

                                </div>
                            </div>
                            <div class="tab-pane" id="panel-update">
                                <div class="col-xs-10 col-xs-offset-1">
                                    <div>
                                        <h3>YAMAI</h3>
                                        System:
                                        <input type="text" id="yamai_system">
                                        Type:
                                        <input type="text" id="yamai_type">
                                        Name:
                                        <input type="text" id="yamai_name">
                                    </div>
                                    <div>
                                        <h3>KEYWORD</h3>
                                        Keyword:
                                        <input type="text" id="keyword">
                                        Score:
                                        <input type="text" id="score">
                                        &nbsp;&nbsp;
                                        <input type="button" class="btn" value="Update" onclick="onUpdateYK()">
                                        &nbsp;&nbsp;
                                        <span id="update_feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row-fluid" style="border-top:1px solid gray;margin-top:10px;padding:10px;text-align:center;">
                <div class="col-xs-12 footer_bar">
                    <p>大鲵就是邪恶</p>
                </div>
            </div>
        </div>
    </body>
</html>
