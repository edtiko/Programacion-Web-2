<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="web/css/principal.css"></link>
        <link rel="stylesheet" type="text/css" href="web/css/reset.css"></link>
        <link rel="stylesheet" type="text/css" media="screen" href="lib/jquery/themes/ui-darkness/jquery-ui-1.9.2.custom.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="lib/jquery/themes/ui.jqgrid.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="lib/jquery/themes/ui.multiselect.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="lib/jquery/jMenu.jquery.css" />
        <link href="lib/jquery/ui.core.css" media="screen" rel="Stylesheet"/>
        <link href="lib/jquery/ui.timepickr.css" media="screen" rel="Stylesheet"/>
        <script type="text/javascript" src="lib/jquery/jquery.js"></script>
        <script src="lib/jquery/jquery.utils.js" type="text/javascript"></script>
        <script src="lib/jquery/jquery.strings.js" type="text/javascript"></script>
        <script src="lib/jquery/jquery.ui.all.js" id="jquery" type="text/javascript"></script>
        <script type="text/javascript" src="lib/jquery/jquery.maskedinput-1.3.js"></script>
        <script type="text/javascript" src="lib/jquery/jquery.ui.datepicker-es.js"></script>
        <script src="lib/jquery/i18n/grid.locale-sp.js" type="text/javascript"></script>
        <script src="lib/jquery/jquery.jqGrid.min.js" type="text/javascript"></script>
        <script src="lib/jquery/jquery-ui-custom.min.js" type="text/javascript"></script> 
        <script src="lib/jquery/ajaxfileupload.js" type="text/javascript"></script>
        <script src="lib/jquery/jMenu.jquery.js" type="text/javascript"></script>
        <script src="apps/main/web/js/utils.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                // simple jMenu plugin called
                $("#jMenu").jMenu();
 
                // more complex jMenu plugin called
                $("#jMenu").jMenu({
                    ulWidth : 'auto',
                    animatedText : false
                });
            });
        </script>
        <!--{$FUNCTION}-->
        <title>.::Prestamos::.</title>
        <style type="text/css">
            body {
                margin-left: 10px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <ul id="jMenu" class="jMenu">
            <li>
                <a href="riesgo/index.html" class="fNiv">Análisis de Riesgo</a>
                <ul style="top: 30px; left: 10px; width: 117px; display: none;">

                </ul>
            </li>
        </ul>
        Sistema Todos UNIAJC<br />
        <br />
        <img src="web/images/shim.gif" alt="" width="20" height="10" /> <br />

        <center>
            <div id="divSearch"></div>
            <div id="jqgrid">
                <table id="bodyGrid"></table><div id="pageGrid"></div>
            </div>
        </center>

        <div style="position:absolute;left:90%;top:1px">
            <a href="index.php?route=fn_exit">Salir</a>
        </div>

        <div id="dialog-cargar" style="visibility: hidden" align="center"> 
            Su solicitud esta siendo atendida, por favor espere.
        </div>
    </body>
</html>
