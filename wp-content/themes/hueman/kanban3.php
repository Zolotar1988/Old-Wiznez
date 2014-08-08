<?php
/*
Template Name: KANBAN VER X-FIELS 1
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><style id="css_disabled_color_picker" type="text/css">.mColorPicker[disabled] + span, .mColorPicker[disabled="disabled"] + span, .mColorPicker[disabled="true"] + span {filter:alpha(opacity=50);-moz-opacity:0.5;-webkit-opacity:0.5;-khtml-opacity: 0.5;opacity: 0.5;cursor:default;}</style>
    <title>banzai!</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="keywords" content="kanban, agile, dashboard, board, SCRUM, visual kanban, virtual kanban, lean, tablero, tablero kanban">
    <meta name="description" content="Virtual kanban is a free online tool, like a phisycal kanban board, for composing your own board online in order to get informed about and control your production proccess">
    <script src="http://95.85.16.177/kanban1_files/ga.js" async="" type="text/javascript"></script><script src="http://95.85.16.177/kanban1_files/jquery-1.js" type="text/javascript">
    </script>
    <script type="text/javascript" src="http://95.85.16.177/kanban1_files/home.js">
    </script>
    <script type="text/javascript" src="http://95.85.16.177/kanban1_files/kanban.js">
    </script>
    <script src="http://95.85.16.177/kanban1_files/jquery-ui.js" type="text/javascript">
    </script>
    <script type="text/javascript" src="http://95.85.16.177/kanban1_files/mColorPicker_min.js" charset="UTF-8"></script>

    <link rel="stylesheet" href="http://95.85.16.177/kanban1_files/kanban.css" type="text/css" media="all">
    <link rel="stylesheet" href="http://95.85.16.177/kanban1_files/jquery-ui.css" type="text/css" media="all">
    <link rel="stylesheet" href="http://95.85.16.177/kanban1_files/home.css" type="text/css" media="all">
    <link rel="stylesheet" href="http://95.85.16.177/kanban1_files/bootstrap.css" type="text/css" media="all">
    <link href="http://95.85.16.177/kanban1_files/css.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="http://virtualkanban.net/img/VirtualKanban.ico">
    <script src="http://95.85.16.177/kanban1_files/google_analytics_auto.js"></script></head>
<body style="cursor: auto;" class="background">

<table width="99%">
    <tbody><tr>
        <td width="50%"></td>

        <td>
            <div style="top: 0px;" id="galleta">
                <p>&nbsp;</p>
                <p style="line-height:.1em;">

                </p>

                <p style="line-height:.1em;">

                </p>
            </div>
        </td>
    </tr>
    </tbody></table>



<div id="main_div">
    <div>
        <input id="add_task" value="NEW TASK" class="btn btn-info" type="button">
        <input id="add_col" value="NEW COLUMN" class="btn btn-primary" type="button">
        <input id="remove_col" value="DELETE COLUMN" class="btn btn-danger" type="button">
    </div>
    <table class="table rounded" border="1">
        <tbody><tr id="task_pool_header_container">
            <th class="task_pool_header  dotted_separator"><div class="header_name click"><img class="title_bullet" src="http://95.85.16.177/kanban1_files/bullet.png"><span class="title_text">Backlog</span></div><div wip="0" class="WIP">WIP: Unlimited</div></th>
            <th class="task_pool_header dotted_separator"><div class="header_name click"><img class="title_bullet" src="http://95.85.16.177/kanban1_files/bullet.png"><span class="title_text">In Process 2</span></div><div wip="0" class="WIP">WIP: Unlimited</div></th>

            <th class="task_pool_header dotted_separator"><div class="header_name click">2</div><div wip="0" class="WIP">WIP: Ilimitado</div></th><th class="task_pool_header"><div class="header_name click">3</div><div wip="0" class="WIP">WIP: Ilimitado</div></th></tr>
        <tr id="task_pool_container">
            <td class="task_pool dotted_separator ui-sortable"><div></div> 		   		  <div class="big_container"> 			  <div id="box_itm1" class="box_itm rounded"> 				  <div id="name1" class="name">Item 1</div> 				  <div class="dotted_hr"></div> 				  <div id="resp1" class="name">Resp 1</div> 				  <progress max="100" id="progress_bar1" class="pbar" value="0"></progress> 				  <div class="small"> 					  <div style="display: none;" n="1" class="itm_box_option"><input style="background-color: rgb(247, 148, 29); color: black;" id="color_2" n="1" class="color colorete mColorPicker" data-text="hidden" data-colorlink="box_itm1" value="rgb(247, 148, 29)" type="hidden"><span style="display: inline-block; cursor: pointer; border: 1px solid black; float: left; width: 10px; height: 10px; background-color: rgb(247, 148, 29); color: black;" class="mColorPickerTrigger color colorete mColorPicker" id="mcp_color_2">&nbsp;</span></div> 					  <div style="display: none;" n="1" class="option close itm_box_option"><button class="btn btn-danger btn-mini"><i class="icon-white icon-remove"></i></button></div> 					  <div style="display: none;" n="1" class="option edit itm_box_option"><button class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></button></div> 				  </div> 				  <div class="clear"></div> 			  </div> 			  <div id="box_itm1_shadow" class="shadow"></div> 			<div> 		</div></div></td>
            <td class="task_pool ui-sortable dotted_separator"><div></div></td>

            <td class="task_pool ui-sortable dotted_separator"><div></div></td><td class="task_pool ui-sortable"><div></div></td></tr>
        </tbody></table>
</div>








<div style="display: none; background: none repeat scroll 0% 0% black; opacity: 0.01; position: absolute; top: 0px; right: 0px; bottom: 0px; left: 0px;" id="mColorPickerBg"></div><div style="position: absolute; border: 1px solid rgb(204, 204, 204); color: rgb(255, 255, 255); width: 194px; height: 184px; font-size: 12px; font-family: times; display: none;" data-mcolorpicker="true" id="mColorPicker"><div style="position: relative; border: 1px solid gray;" id="mColorPickerWrapper"><div style="height: 136px; width: 192px; border: 0px none; cursor: crosshair; background-image: url(&quot;http://meta100.github.com/mColorPicker/images/picker.png&quot;);" class="mColor" id="mColorPickerImg"></div><div style="border-right: 1px solid rgb(0, 0, 0);" id="mColorPickerSwatches"><div style="background-color: rgb(255, 255, 255); height: 18px; width: 18px; border: 1px solid rgb(0, 0, 0); float: left;" class="mPastColor" id="cell0">&nbsp;</div><div style="background-color: rgb(255, 255, 0); height: 18px; width: 18px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(0, 0, 0) rgb(0, 0, 0) rgb(0, 0, 0) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; float: left;" class="mPastColor mNoLeftBorder" id="cell1">&nbsp;</div><div style="background-color: rgb(0, 255, 0); height: 18px; width: 18px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(0, 0, 0) rgb(0, 0, 0) rgb(0, 0, 0) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; float: left;" class="mPastColor mNoLeftBorder" id="cell2">&nbsp;</div><div style="background-color: rgb(0, 255, 255); height: 18px; width: 18px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(0, 0, 0) rgb(0, 0, 0) rgb(0, 0, 0) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; float: left;" class="mPastColor mNoLeftBorder" id="cell3">&nbsp;</div><div style="background-color: rgb(0, 0, 255); height: 18px; width: 18px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(0, 0, 0) rgb(0, 0, 0) rgb(0, 0, 0) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; float: left;" class="mPastColor mNoLeftBorder" id="cell4">&nbsp;</div><div style="background-color: rgb(255, 0, 255); height: 18px; width: 18px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(0, 0, 0) rgb(0, 0, 0) rgb(0, 0, 0) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; float: left;" class="mPastColor mNoLeftBorder" id="cell5">&nbsp;</div><div style="background-color: rgb(255, 0, 0); height: 18px; width: 18px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(0, 0, 0) rgb(0, 0, 0) rgb(0, 0, 0) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; float: left;" class="mPastColor mNoLeftBorder" id="cell6">&nbsp;</div><div style="background-color: rgb(76, 43, 17); height: 18px; width: 18px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(0, 0, 0) rgb(0, 0, 0) rgb(0, 0, 0) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; float: left;" class="mPastColor mNoLeftBorder" id="cell7">&nbsp;</div><div style="background-color: rgb(59, 59, 59); height: 18px; width: 18px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(0, 0, 0) rgb(0, 0, 0) rgb(0, 0, 0) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; float: left;" class="mPastColor mNoLeftBorder" id="cell8">&nbsp;</div><div style="background-color: rgb(0, 0, 0); height: 18px; width: 18px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(0, 0, 0) rgb(0, 0, 0) rgb(0, 0, 0) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; float: left;" class="mPastColor mNoLeftBorder" id="cell9">&nbsp;</div><div style="clear: both;" class="mClear"></div></div><div style="background-image: url(&quot;http://meta100.github.com/mColorPicker/images/grid.gif&quot;); position: relative; height: 26px;" id="mColorPickerFooter"><input value="rgb(247, 148, 29)" style="border: 1px solid gray; font-size: 10pt; margin: 3px; width: 80px;" id="mColorPickerInput" type="text"><span style="font-size: 16px; color: rgb(0, 0, 0); padding-right: 30px; padding-top: 3px; cursor: pointer; overflow: hidden; float: right;" class="mColor mColorTransparent" id="mColorPickerTransparent">transparent</span><a style="float: right;" target="_blank" alt="Meta100 - Designing Fun" title="Meta100 - Designing Fun" href="http://meta100.com/"><img style="border-width: 0px 0px 0px 1px; border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color rgb(170, 170, 170); -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; right: 0px; position: absolute;" alt="Meta100 - Designing Fun" title="Meta100 - Designing Fun" src="http://95.85.16.177/kanban1_files/meta100.png"></a></div></div></div><div style="display: none;" id="mColorPickerTest"></div></body></html>