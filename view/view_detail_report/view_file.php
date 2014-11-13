<span class="help-block">Пакетная загрузка</span>
<br />
<form id="myform" class="myform" <?php echo "action='".$this->url.$this->num_menu."' enctype='multipart/form-data' method='POST'";?> >
    <br />
    <div style="position:relative;">
        <a class='btn btn-primary' href='javascript:;'>
            Занрузить файл...
            <input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file" size="40"  onchange='$("#upload-file-info").html($(this).val());'>
        </a>
        &nbsp;
        <span class='label label-info' id="upload-file-info"></span>
    </div>
    <br />
    <br />
    <button type="submit" name="submit" value="send_file" id="send_file" class="btn btn-info">Отправить</button>
</form>