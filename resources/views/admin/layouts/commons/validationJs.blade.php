<script>
    function check_unknown(e,id){
        if(e.checked)
        {
            clearThis(id);
            $('#'+id).attr('readonly','readonly');
        }
        else
        {
            $('#'+id).removeAttr('readonly');
        }
    }

    function nullCheck(value) {
        if(value==null){
            return '';
        }
        else
        {
            return value;
        }
    }

    function isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
                return false;
        }
        return true;
    }


    function file_check(e,regex,$message,img_id=null,default_image=null,callback=null){
        if (e.value.match(regex)) {
        } else {
            e.value = "";
            if(callback!==null){
                callback(img_id,default_image);
            }
            alertify.notify($message, 'error', 5, function(){  });
        }
    }

</script>
