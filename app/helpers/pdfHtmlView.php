<?php


namespace App\helpers;


class pdfHtmlView
{
    public static function pdfView($data){
        $header = ' <html><head>
        <title></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">        <style>
            table {
                border-collapse: collapse;
                  width: 100%;

            }
            table td {

                padding-left: 5px;
                padding-right: 3px;
                background-color: #FFF;
                font-size: 12px;
            }
            .table-bordered{
            border: 1px solid #ddd;
            }
            .rowspan {
                border-left-width: 10px;
            }
            .table-bordered td {

             border: 1px solid #ddd;
            }
        </style>
    </head>';
        $body = '<body  style="width: 100%;margin: auto;"> <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-12 mx-auto ">
                <div class="card">  <div style="margin-left: 0px !important; margin-right: 0px !important;" class="card-body"'.$data['body'];
        $footer = '</div></div></div></div></body></html>';
        return $header.$body.$footer;
    }
}
