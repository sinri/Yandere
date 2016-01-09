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
        label {
            width: 80px;
            display: inline-block;
        }

        .possible_result_area {
            margin: 10px 0;
        }
        .possible_keyword_area_btn {
            margin: 0px 10px;
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

                    var h=generateYamaiListWithData(data);

                    $('#yamai_list').html(h);

                }else{
                    $('#yamai_list').html('Update Failed!');
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                $('#yamai_list').html('AJAX Failed!');
            });
        }

        function searchKeywords(){
            $.ajax({
                url: './controller/CommonAgent.php',
                type: 'POST',
                data: {
                    act: 'search',
                    keywords: $('#search_keywords').val(),
                },
                dataType: 'json',
            })
            .done(function( data, textStatus, jqXHR ) {
                if(data && data.list){
                    // $('#yamai_list').html('Update Done! '+ JSON.stringify(data));

                    var h=generateYamaiListWithData(data);

                    $('#yamai_search_list').html(h);

                }else{
                    $('#yamai_search_list').html('Update Failed!');
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                $('#yamai_search_list').html('AJAX Failed!');
            });
        }

        function generateYamaiListWithData(data){
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

            return h;
        }

        function onKeywordInputChange(action){
            var act='';
            var keyword='';
            var target_id="";
            var target_container='';
            var type='';
            if(action=='keyword'){
                act='possible_keywords';
                type='';
                keyword=$('#keyword').val();
                target_id='keyword';
                target_container='possible_keyword_area';
            }else if(action=='system'){
                act='possible_yamai';
                type='yamai_system';
                keyword=$('#yamai_system').val();
                target_id='yamai_system';
                target_container='possible_system_area';
            }else if(action=='type'){
                act='possible_yamai';
                type='yamai_type';
                keyword=$('#yamai_type').val();
                target_id='yamai_type';
                target_container='possible_type_area';
            }else if(action=='yamai'){
                act='possible_yamai';
                type='yamai_name';
                keyword=$('#yamai_name').val();
                target_id='yamai_name';
                target_container='possible_yamai_area';
            }
            $('#'+target_container).css('display','block');
            $('#'+target_container).html('Querying...');
            $.ajax({
                url: './controller/CommonAgent.php',
                type: 'POST',
                data: {
                    act: act,
                    type: type,
                    keyword: keyword,
                },
                dataType: 'json',
            })
            .done(function( data, textStatus, jqXHR ) {
                if(data && data.list){
                    if(data.list.length>0){
                        var h="Might be ";//JSON.stringify(data);
                        for (var i =0;i< data.list.length ; i++) {
                            h=h+"<button class='btn possible_keyword_area_btn' onclick='$(\"#"+target_id+"\").val(\""+data.list[i].itemName+"\");$(\"#"+target_container+"\").css(\"display\",\"none\")'>"+data.list[i].itemName+"</button>";
                        };
                        $('#'+target_container).html(h);
                    }
                    else{
                        $('#'+target_container).html('Query Result Empty!');
                    }
                }else{
                    $('#'+target_container).html('Query Failed!');
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                $('#'+target_container).html('AJAX Failed!');
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
                                <a href="#panel-search" data-toggle="tab">Search</a>
                            </li>
                            <li>
                                <a href="#panel-update" data-toggle="tab">Update</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="panel-view">
                                <div>
                                    Refresh the data.
                                    <button class="btn" onclick="refreshYamaiList()">Refresh</button>
                                </div>
                                <div id="yamai_list">

                                </div>
                            </div>
                            <div class="tab-pane" id="panel-search">
                                <div>
                                    <h3>Search Keywords</h3>
                                    <p>Separate keywords with space.</p>
                                    <textarea style="width:80%;height:50px;" id="search_keywords"></textarea>
                                    <button class="btn" onclick="searchKeywords()">Search</button>
                                </div>
                                <div id="yamai_search_list">

                                </div>
                            </div>
                            <div class="tab-pane" id="panel-update">
                                <div class="col-xs-12">
                                    <blockquote><p>YAMAI</p></blockquote>
                                    <div>
                                        <label>System:</label>
                                        <input type="text" id="yamai_system" onkeyup="onKeywordInputChange('system')">
                                        <div id="possible_system_area" class="possible_result_area"></div>
                                    </div>
                                    <div>
                                        <label>Type:</label>
                                        <input type="text" id="yamai_type" onkeyup="onKeywordInputChange('type')">
                                        <div id="possible_type_area" class="possible_result_area"></div>
                                    </div>
                                    <div>
                                        <label>Name:</label>
                                        <input type="text" id="yamai_name" onkeyup="onKeywordInputChange('yamai')">
                                        <div id="possible_yamai_area" class="possible_result_area"></div>
                                    </div>
                                    <blockquote><p>KEYWORD</p></blockquote>
                                    <div>
                                        <label>Keyword:</label>
                                        <input type="text" id="keyword" onkeyup="onKeywordInputChange('keyword')">
                                        <!-- onchange="onKeywordInputChange()" -->
                                        <div id="possible_keyword_area" class="possible_result_area"></div>
                                    </div>    
                                    <div>
                                        <label>Score:</label>
                                        <!-- <input type="text" id="score"> -->
                                        <select name="score">
                                            <option value='-5'>-5</option>
                                            <option value='-4'>-4</option>
                                            <option value='-3'>-3</option>
                                            <option value='-2'>-2</option>
                                            <option value='-1'>-1</option>
                                            <option value='0'>0</option>
                                            <option value='1'>1</option>
                                            <option value='2'>2</option>
                                            <option value='3' selected="selected">3</option>
                                            <option value='4'>4</option>
                                            <option value='5'>5</option>
                                        </select>
                                    </div>
                                    <blockquote><p>Action</p></blockquote>
                                    <div>
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
