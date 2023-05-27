<style>
    body {
        font-family: 'SolaimanLipi', Arial, sans-serif !important;
        /*font-family: 'AdorshoLipi', Arial, sans-serif !important;*/
        /*font-family: 'Bangla', Arial, sans-serif !important;*/
    }
    /*.text-sm .brand-link {*/
    /*    font-size: 20px;*/
    /*}*/
    /*.user-panel .image {*/
    /*    padding-top: 5px;*/
    /*}*/
    /*.card{*/
    /*    box-shadow: 0 0 15px rgba(0,0,0,.1), 0 20px 30px rgba(0,0,0,.2);*/
    /*}*/

    div.dataTables_wrapper div.dataTables_filter input {
        width: 150px !important;
    }

    form .error {
        color: #ff0000;
        font-size: 12px;
        float: right;
    }
    .card-title{
        font-size: 20px!important;
    }

    .os-content-glue{
        height: 0px !important;
    }
    .card-sub-header{
        padding-top: 10px;
        padding-bottom: 5px;
        background-color: #eeeeee;
        text-align: center;
    }
    .card-header{
        background-color: #007bff;
        color:white;
        padding-top: 20px!important;
    }
    .content-wrapper {
        background-image: url("/jitabazar/public/images/defaults/back.jpg") !important;
        background-repeat: repeat;
    }
    .btn{
        padding: .2rem 1rem !important;
    }
    th {
        white-space: nowrap !important;
    }
    #filter_submit{
        height: 29px !important;
    }
    .select2-container .select2-selection--single .select2-selection__rendered{
        padding-top: 6px;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
    .custom-select.form-control
    {
        height: 1.8rem;
        font-size:12px;
    }

    .form-control,.input-group-text
    {
        height: 1.8rem;
        font-size:14px;
    }

    .dataTables_length label .form-control
    {
        padding-top: 0px;
        padding-bottom: 0px;
    }
    /*.select2{*/
    /*    font-size:14px;*/
    /*}*/

    /*.select2-container--default .select2-selection--single .select2-selection__rendered {*/
    /*    color: #444;*/
    /*    line-height: 15px;*/
    /*}*/

    /*.select2-container {*/
    /*    !*min-width: 350px;*!*/
    /*}*/

    /*.select2-container--default .select2-selection--multiple .select2-selection__choice{*/
    /*    color: #444;*/
    /*}*/


    /*.select2-results__option {*/
    /*    !*padding-right: 20px;*!*/
    /*    vertical-align: middle;*/
    /*    !*min-width:450px;*!*/
    /*    white-space: nowrap !important;*/
    /*    padding-left:4px;*/
    /*}*/
    /*.select2-results__option:before {*/
    /*    content: "";*/
    /*    display: inline-block;*/
    /*    position: relative;*/
    /*    height: 20px;*/
    /*    width: 20px;*/
    /*    border: 2px solid #e9e9e9;*/
    /*    border-radius: 4px;*/
    /*    background-color: #fff;*/
    /*    margin-right: 3px;*/
    /*    vertical-align: middle;*/
    /*}*/
    /*.select2-results__option[aria-selected=true]:before {*/
    /*    font-family: "Font Awesome 5 Free";*/
    /*    content: "\f14a";*/
    /*    color: #1799f7;*/
    /*    background-color: #fff;*/
    /*    border: 0;*/
    /*    display: inline-block;*/
    /*    padding-left: 3px;*/
    /*}*/
    /*.select2-container--default .select2-results__option[aria-selected=true] {*/
    /*    background-color: #fff;*/
    /*}*/
    /*.select2-container--default .select2-results__option--highlighted[aria-selected] {*/
    /*    background-color: #eaeaeb;*/
    /*    color: #272727;*/
    /*}*/
    /*.select2-container--default .select2-selection--multiple {*/
    /*    margin-bottom: 10px;*/
    /*}*/
    /*.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {*/
    /*    border-radius: 4px;*/
    /*}*/
    /*.select2-container--default.select2-container--focus .select2-selection--multiple {*/
    /*    border-color: #1799f7;*/
    /*    border-width: 2px;*/
    /*}*/
    /*.select2-container--default .select2-selection--multiple {*/
    /*    border-width: 2px;*/
    /*}*/
    /*.select2-container--open .select2-dropdown--below {*/

    /*    border-radius: 6px;*/
    /*    box-shadow: 0 0 10px rgba(0,0,0,0.5);*/

    /*}*/
    /*.select2-selection .select2-selection--multiple:after {*/
    /*    content: 'hhghgh';*/
    /*}*/

    .up-image .container {
        position: relative;
        padding-left: 0px;
        padding-right: 0px;
        cursor:pointer;
    }

    .up-image .container .image {
        opacity: 1;
        display: block;
        transition: .5s ease;
        backface-visibility: hidden;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        color: #3b3b3b !important;
    }

    .card-title{
        color: #3b3b3b;
    }

    a{
        cursor: pointer !important;
    }

    input{
        margin-bottom: 10px !important;
    }

    .up-image .container .middle {
        transition: .5s ease;
        opacity: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
    }

    .up-image .container:hover .image {
        opacity: 0.2;
    }

    .up-image .container:hover .middle {
        opacity: 1;
    }

    .up-image .container .middle .text {
        background-color: #fff;
        opacity: 0.9;
        color: black;
        font-size: 14px;
        padding: 12px 24px;
    }

    .dataTables_wrapper .dataTables_processing {
        position: fixed ;
        width: 100px !important;
        /*height: 80px !important;*/
        margin-left: 7% !important;
        margin-top: 0 !important;
        padding-top: 0;
        text-align: center;
        font-size: 1.2em;
        background: none;
        opacity: 0.9 !important;
        border:none;
        z-index:100;

    }

    .dataTables_scroll{
        position:static !important;
    }
    @media only screen and (max-width: 600px){

        .dataTables_wrapper .dataTables_processing {
            position: relative ;
            width: 100px !important;
            /*height: 80px !important;*/
            margin-left: -15% !important;
            margin-top: 0 !important;
            padding-top: 0;
            text-align: center;
            font-size: 1.2em;
            background: none;
            opacity: 0.9 !important;
            border:none;
            z-index:100;

        }
    }

    @media only screen and (min-width: 700px){

        .dataTables_filter
        {
            width:22% !important;
            float:left !important;
            text-align:left;
        }

        .dt-buttons
        {
            width:38% !important;
            float:right !important;
            text-align:right;
        }

        .dataTables_length
        {
            width:38% !important;
            float:right !important;
            text-align:center;
        }
    }

    @media print {
        .no-print {
            display: none;
        }
    }

    @page {
        size: auto;
    }

</style>
