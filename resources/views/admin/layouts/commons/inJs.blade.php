<script>
    var $=jQuery;

    window.Laravel = {!! json_encode([
        'user' => auth()->check() ? auth()->user()->id : null,
    ]) !!};

    // $('#left_bar_collapse_btn').on('click',function(){
    //     setTimeout(function () {
    //         $('.select2').css('width','100%');
    //         $('.select-tag').css('width','100%');
    //         if($.fn.dataTable!==undefined) {
    //             $($.fn.dataTable.tables(true)).DataTable()
    //                 .columns.adjust();
    //         }
    //     },300);
    // });

    // $('input[type=number]').on('mousewheel',function(e){ console.log('OK'); $(this).blur(); });

    $('.btn-close').on('click',function (e){
        this.closest('.toast').remove();
    });

    $(window).on('load',function(){
        $('.select2').select2();
        $('.select2-tag').select2({
            'tags':true
        });
    });

    function percent(total,percent){
        let result= (total/100)*percent;
        return result;
    }

    function percent_reverse(total,amount){
        let result= (100/total)*amount;
        return result;
    }


    function readURL(input,imgid) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#'+imgid).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('wheel.disableScroll', function (e) {
            e.preventDefault();
        })
    });
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('wheel.disableScroll');
    });

    function get_day_by_date(input_date,bn=false){
        if(input_date!==''){
            input_date=input_date.split('-');
            input_date=input_date[1]+'-'+input_date[2]+'-'+input_date[0];
            var d = new Date(input_date);
            var n = d.getDay();
            let days=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','রবি','সোম','মঙ্গল','বুধ','বৃহস্পতি','শুক্র','শনি'];
            if(bn){
                return days[n+7];
            }
            else{
                return days[n];
            }
        }
        else
        {
            return '';
        }
    }

    function clearThis(id) {
        $('#'+id).val('');
    }

    function printDiv(divref, u, p, logo){
        // if(guardHtml)
        // {
        //     var content = document.getElementById(divId).innerText;
        // }
        // else
        // {
        //     var content = document.getElementById(divId).innerHTML;
        // }
        //
        // var mywindow = window.open('', 'Print', 'height=600,width=1024');
        //
        // mywindow.document.write('<html onload="window.close();"><head><title>Print</title>' + '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"><style>\n' +
        //     '    th,td{\n' +
        //     '        font-size: 13px;\n' +
        //     '        padding-top:.5rem !important;\n' +
        //     '        padding-bottom:.5rem !important;\n' +
        //     '    }</style>');
        // mywindow.document.write('</head><body onload="window.print();">');
        // mywindow.document.write(content);
        // mywindow.document.write('</body></html>');
        //
        // mywindow.document.close();
        // mywindow.focus()
        // return true;

        var url = (typeof u != 'undefined') ? u : '';
        var w = (typeof p != 'undefined') ? 900 : 700;

        var content_vlue;
        var dStr;
        if (url != '') {
            var parameter = (url.indexOf("?") != -1) ? "&" : "?";
            parameter += Report.CreateQuery();
            parameter += '&state=gray';
            content_vlue = ContentOf(url + parameter);
            dStr = '<html<body> <head><link href="styles/gray.css" rel="stylesheet" type="text/css">';
            dStr += '</head><body onLoad="self.print()">';
            dStr += '<div style="text-align: center;">';
        } else {

            content_vlue = document.getElementById(divref).innerHTML;
            dStr = '<link rel="preconnect" href="https://fonts.gstatic.com">';
            dStr = '<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">';
            dStr += '<link href="{{ URL::asset('admin/dist/icons/bootstrap-icons-1.4.0/bootstrap-icons.min.css')}}" rel="stylesheet" type="text/css">';
            dStr += '<link href="{{ URL::asset('admin/dist/css/bootstrap-docs.css')}}" rel="stylesheet" type="text/css">';
            dStr += '<link href="{{ URL::asset('admin/libs/slick/slick.css')}}" rel="stylesheet" type="text/css">';
            dStr += '<link href="{{ URL::asset('admin/dist/css/app.min.css')}}" rel="stylesheet" type="text/css">';
            dStr += '<link href="{{ URL::asset('bower/DataTables/datatables.min.css')}}" rel="stylesheet" type="text/css">';
            dStr += '<link rel="stylesheet" href="{{ URL::asset('bower/select2/dist/css/select2.css')}}">';
            dStr += '<link rel="stylesheet" href="{{ URL::asset('css/print.css')}}">';
            dStr += '<style type="text/css" media="print"> body { height: 99%;   margin-top: 10px; margin-bottom: 10px;} ' +

                ' </style>';
            dStr += '</head><body class="" onLoad="self.print()" style="background: #fff;"> ';
            dStr += '<div style="text-align: center;" >';
        }
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=no,  left=0, top=10";

        var docprint = window.open("", "", disp_setting);
        docprint.document.open();
        docprint.document.write('<html><head><style>@page{margin-top: 15px;margin-bottom: 15px;}</style><title>PrintView</title>');
        docprint.document.write(dStr);
        docprint.document.write(content_vlue);
        docprint.document.write('</div></div></body></html>');
        docprint.document.close();
        docprint.focus();
    }
    function exportToExcel(ref){
        var htmltable= document.getElementById(ref);
        var html = htmltable.outerHTML;
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
    }

    let currency2Format = function(value) {
        value = parseFloat(value).toFixed(2);
        return value; //for chaining
    };

    function date_to_number(date)
    {
        let arr = date.split('-');
        let str= arr[0]+arr[1]+arr[2];
        return parseInt(str);
    }

    function today_datetime() {
        var today = new Date();
        var dd = today.getDate();

        var mm = today.getMonth()+1;
        var yyyy = today.getFullYear();

        return yyyy+'-'+mm+'-'+dd;
    }

    function bn_Numbers(input) {
        var numbers = {
            0:'০',
            1:'১',
            2:'২',
            3:'৩',
            4:'৪',
            5:'৫',
            6:'৬',
            7:'৭',
            8:'৮',
            9:'৯'
        };
        var output = [];
        for (var i = 0; i < input.length; ++i) {
            if (numbers.hasOwnProperty(input[i])) {
                output.push(numbers[input[i]]);
            } else {
                output.push(input[i]);
            }
        }
        return output.join('');
    }

    function resetForm(id,header,body,okMessage='From reset successful.',cancelMessage=null) {
        alertify.confirm('<strong>'+header+'</strong>',body,
            function(){
                document.getElementById(id).reset();
                if(okMessage){
                    alertify.success(okMessage);
                }
            },
            function(){
                if(cancelMessage){
                    alertify.success(cancelMessage);
                }
            });
    }

    function taka_format(num,toFixed=2)
    {
        var minus="";
        if(num<0)
        {
            num=Math.abs(num);
            minus="-";
        }
        let strNum;
        if(toFixed!=null&&num>=0)
        {
            strNum = num.toFixed(toFixed);
        }
        else
        {
            strNum=num;
            strNum=String(num);
        }
        //let strNum=String(floatNum);
        let res=strNum.split(".");
        let firstres=[];
        let j=0;
        let c=0;
        for(let i=res[0].length-1; i>=0; i--)
        {
            firstres[j]=res[0][i];
            j++;
            if(j==3&&i!=0)
            {
                firstres[j]=",";
                j++;
            }
            else if(j>4&&j<11&&c==1&&i!=0){
                firstres[j]=",";
                j++;
                c=-1;
            }

            if(j>4)
            {
                c++;
            }
        }
        let first_half=firstres.reverse();
        if(res[1]===undefined)
        {
            res[1]='00';
        }
        return minus+first_half.join("")+'.'+res[1];
    }

    $('form').on('submit',function(){
        $(":submit").attr('disabled','disabled');
        let btnHtml=$(":submit").html();
        if(btnHtml!=='Submit' && btnHtml !=='Update'){
            btnHtml='Submit';
        }
        $(":submit").html('Processing...');
        setTimeout(function(){
            $(":submit").removeAttr('disabled');
            $(":submit").html(btnHtml);
        },4000)
    });

    function filterKeyNumber(el,evt,error_code) {
        // console.log(evt);
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 45 && charCode != 8 && (charCode != 46) && (charCode < 48 || charCode > 57)){
            let myVar = el.value;
            var digit = myVar.toString()[0];
            if((digit >=0 && digit<=9) || digit=='.'){
                $("#"+error_code).hide();
            }else{
                $("#"+error_code).show();
            }

            return false;
        }else{
            $("#"+error_code).hide();
        }

        if (charCode == 46) {
            if ((el.value) && (el.value.indexOf('.') >= 0)){
                $("#"+error_code).hide();
                return false;
            }
            else{
                $("#"+error_code).hide();
                return true;
            }

        }
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var number = el.value.split('.');
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            $("#"+error_code).show();
            return false;
        }
        $("#"+error_code).hide();
        return true;
    }
    function jqueryCalendar (ref_id) {
        return new Pikaday({
            field: $('#'+ref_id)[0] ,
            firstDay: 1,
            format: 'YYYY-MM-DD',
            toString: function (date, format) {
                var day   = date.getDate();
                var month = date.getMonth() + 1;
                var year  = date.getFullYear();

                var yyyy = year;
                var mm   = ((month > 9) ? '' : '0') + month;
                var dd   = ((day > 9)   ? '' : '0') + day;

                return yyyy + '-' + mm + '-' + dd;
            },
            position: 'bottom right',
            minDate: new Date('1900-01-01'),
            maxDate: new Date('2040-12-31'),
            yearRange: [1900, 2040]
        });
    }
    function monthCalendar (ref_id) {
      var  months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        return new Pikaday({
            field: $('#'+ref_id)[0] ,
            firstDay: 1,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'MMM YYYY',
            toString: function (date, format) {
                var day   = date.getDate();
                var month = date.getMonth() + 1;
                var year  = date.getFullYear();

                var yyyy = year;
                var mm   = ((month > 9) ? '' : '0') + month;
                var dd   = ((day > 9)   ? '' : '0') + day;

                return months[mm-1]+' '+ yyyy;
            },
            position: 'bottom right',
            minDate: new Date('1900-01-01'),
            maxDate: new Date('2040-12-31'),
            yearRange: [1900, 2040]
        });
    }

</script>
