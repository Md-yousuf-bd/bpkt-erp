var Report = {
    close: function () {
        hide_div(document.getElementById('window_div'), 'right', 300);
        document.getElementById('window_content').innerHTML = '';
        if (typeof modal != "undefined") {
            modal.closeveil();
        }

    },

    Generate: function (url) {
        show_status(4, 'processing');
        get_content(url, Report.setContent)
    },

    setContent: function (c) {
        //alert(c);
        document.getElementById('window_content').innerHTML = "<div style='height:100%'>" + c + "</div>";
        show_status();
        Report.SetInnerScroll(50);

        if ($("#theHandle").length) {
            let elem = $("#theHandle").parent();
            let elem2 = $("#export_icon tr").find('td:nth-of-type(2) img');
            let prevAction = elem2.attr('onClick');
            let newAction = "$('#dataTbl').remove();";
            elem2.attr('onClick', newAction + prevAction);
            let prevActionForClose = "window_close('window_div','right',300);";
            if ($("#dataTbl").length == 0) {
                $("#win_closeb").removeAttr('onClick');
                $("#win_closeb").attr('onClick', newAction + prevActionForClose);
            }
            if ($("#dataTbl").length == 0) {
                $("'<td id='dataTbl' align='left'><div>&nbsp;\n" +
                    "                <input type=\"text\" name=\"additional_filter\"\n" +
                    "                id=\"additional_filter\" width=\"140px\"\n" +
                    "                style=\"border: 0px;\n" +
                    "                        height: 26px;\n" +
                    "                        margin: -28px 0px 0px 0px;\n" +
                    "                        background-color: #ffb488\">\n" +
                    "                <input type=\"button\" value=\"search\" name=\"btn_list_search\" id=\"btn_list_search\" class=\"btn_win\"\n" +
                    "                style=\"margin-top: 2px !important;\"\n" +
                    "                onclick=\"getAndFilterTblData($(\'#additional_filter\').val());\">\n" +
                    "                \n" +
                    "              &nbsp;&nbsp;&nbsp;" +
                    "</div></td>'").insertBefore(elem).parent();


            }
        } else {

        }
    },

    View: function (anchorobj, url, flag) {
        var parameter = (url.indexOf("?") != -1) ? "&" : "?";
        parameter += 'state=s1';
        load_content(url + parameter, 'window_div', true, anchorobj, '', '', 'Report.SetInnerScroll()');

        if ($('#dataTbl').length) {
            $('#dataTbl').remove();
        }
    },

    restore_filter: function (fref, fval) {
        if (fref.length > 0) {		//FILTER USED
            for (var i = 0, len = fref.length; i < len; i++) {
                var ref = eval("document.frm_input_r." + fref[i]);
                if (ref) {
                    // ref.value = fval[i];

                    //START: below code is for radio and checkbox single| group
                    if (ref.value)		//IS A SINGLE VALUE
                    {
                        if (ref.type == 'checkbox' || 'radio') {
                            ref.checked = (ref.value == fval[i]) ? true : false;
                            // alert("true >-" + ref.type + "rv:" + ref.value + "fv:" + fval[i]);
                        } else {
                            ref.value = fval[i];
                        }
                    } else {				//Control array Then must be a check box or redo

                        try {
                            set_selected_value(ref, fval[i]);
                        } catch (e) {
                            ref.value = fval[i];
                        }

                    }//END: radio|checkbox
                }
            }
        }
    },

    CreateQuery: function () {
        try {
            var frm = document.frm_input_r;
            var inp = frm.getElementsByTagName("input");
            var qstr = '';
            for (var i = 0, len = inp.length; i < len; i++) {
                if (i > 0)
                    qstr = qstr + "&";
                qstr += inp[i].name + '=' + inp[i].value;
            }
            return qstr;
        } catch (exception) {
            return qstr;
        }
    },

    OrderBy: function (f, v) {
        eval('document.frm_input_r.' + f + ".value=v");
    },

    Sort: function (f, v, url) {		//F=field name, v=value
        eval('document.frm_input_r.' + f + ".value=(v=='ASC')?'DESC':'ASC'");
        var parameter = (url.indexOf("?") != -1) ? "&" : "?";
        parameter += Report.CreateQuery();
        parameter += '&state=change';
        show_status(4, 'sorting...');
        load_active_content(url + parameter, 'window_sub_content', 'Report.SetInnerScroll(50)', 0, "document.getElementById('window_sub_content').style.height=1;show_status()");

    },

    SetInnerScroll: function (ex) {
        try {
            var exOff = (typeof ex != 'undefined') ? ex : 85;
            document.getElementById('window_sub_content').style.height = parseInt(document.getElementById('window_content').offsetHeight) - exOff;
        } catch (exception) {
            //Get out from error
        }
    },

    printView: function (divref, u, p, logo) {
        var url = (typeof u != 'undefined') ? u : '';
        var w = (typeof p != 'undefined') ? 900 : 700;

        var content_vlue;
        var dStr;
        if (url != '') {
            var parameter = (url.indexOf("?") != -1) ? "&" : "?";
            parameter += Report.CreateQuery();
            parameter += '&state=gray';
            content_vlue = ContentOf(url + parameter);
            dStr = '<link href="styles/gray.css" rel="stylesheet" type="text/css">';
            dStr += '</head><body onLoad="self.print()">';
            dStr += '<div style="text-align: center;">';
        } else {
            content_vlue = document.getElementById(divref).innerHTML;
            dStr = '<link href="styles/color.css" rel="stylesheet" type="text/css">';
            dStr += '<link href="styles/style.css" rel="stylesheet" type="text/css">';
            dStr += '</head><body onLoad="self.print()">';
            dStr += '<div style="text-align: center;">';
        }
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=" + w + ", height=600, left=100, top=25";

        var docprint = window.open("", "", disp_setting);
        docprint.document.open();
        docprint.document.write('<html><head><title>ZXY PrintView</title>');
        docprint.document.write(dStr);
        docprint.document.write(content_vlue);
        docprint.document.write('</div></body></html>');
        docprint.document.close();
        docprint.focus();
    },

    export_ppt: function (divref, fname) {
        this.getFile(divref, 0, fname + '.ppt');
    },

    export_xls: function (divref, fname) {
        this.getFile(divref, 1, fname + '.xls');
    },

    export_doc: function (divref, fname) {
        this.getFile(divref, 2, fname + '.doc');
    },

    export_pdf: function (divref, fname, format) {
        let fm = typeof (format) != 'undefined' ? format : '';
        this.getFile(divref, 3, fname + '.pdf', fm);
    },

    getFile: function (divref, type, fname, format) {
        var ftype = ['vnd.ms-powerpoint', 'x-excel', 'msword', 'pdf'];
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=700, height=600, left=100, top=25";

        let fm = typeof (format) != 'undefined' ? format : '';

        var content_vlue = document.getElementById(divref).innerHTML;
        var docprint = window.open("", "", disp_setting);
        docprint.document.open();
        docprint.document.write('<html><head><title>Please wait while creating pdf...</title>');
        docprint.document.write('</head><body>');
        docprint.document.write('<div id="' + divref + '">');
        docprint.document.write(content_vlue);
        docprint.document.write('</div>');
        docprint.document.write('<form name="frm_exp" ' +
            'method="post" ' +
            'action="export.php?type=' + ftype[type] +
                              '&fname=' + fname + '&format=' + fm + ' "  enctype="multipart/form-data">');
        docprint.document.write('<input type="hidden" name="content" id="content" value=""></form>');
        docprint.document.write('</body></html>');
        docprint.document.close();

        setTimeout(function () {
            content_vlue = docprint.document.getElementById(divref).innerHTML;
            docprint.document.frm_exp.content.value = content_vlue;
            docprint.document.frm_exp.submit();
            docprint.focus();

        }, 2000);

    }
};

