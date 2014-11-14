/*//обработчик закрытия окна
 window.onbeforeunload = function (event)
 {
 //window.onbeforeunload = null;
 return ' нажмите ОСТАТЬСЯ НА СТРАНИЦЕ! ';
 }*/

jQuery(document).ready(function()
{
    jQuery('input').on('paste', function()
    {
        var element = this;
        setTimeout(function()
        {
            var id = element.id;
            
            if(id == 'lname' || id == 'fname')
            {
                var text = element.value;
    			var count = 0;
                for(var i = 0; i < text.length; i++)
    			{
    				var charCode = text.charCodeAt(i);
                    if((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && (charCode != 1025) && (charCode < 1040 || charCode > 1103) && (charCode != 1105))
                    {
                         count++;
                    }
                }
                if(text.length > 20)
                    $('#error_'+element.id).text('слишком длинная строка !');
                else if(count > 0)
                    $('#error_'+element.id).text('запрещённые символы !');
                else 
                    $('#error_'+element.id).text('');
                
                if($(element).val() != '')
                    $(element).tooltip('hide');//скрываем tooltip
            }
            else if(id == 'login')
            {
                var text = element.value;
    			var count = 0;
                for(var i = 0; i < text.length; i++)
    			{
    				var charCode = text.charCodeAt(i);
                    if((charCode < 48 || charCode > 57) && (charCode < 97 || charCode > 122))
                    {
                         count++;
                    }
                }
                if(text.length > 20)
                    $('#error_'+element.id).text('слишком длинный логин !');
                else if(count>0) 
                    $('#error_'+element.id).text('запрещённые символы !');
                else 
                {
                    $('#error_'+element.id).text('');
                    var str = $(element).val();
                    jQuery.post('model/checklogin.php', {'chlogin':str}, function(data)
                    { 
                        jQuery('#error_'+element.id).text(data.chlogin);
                    }, 'json'); 
                }
                if($(element).val() != '')
                    $(element).tooltip('hide');//скрываем tooltip
            }
            else if(id == 'password')
            {
                var text = element.value;
    			var count = 0;
                for(var i = 0; i < text.length; i++)
    			{
    				var charCode = text.charCodeAt(i);
                    if((charCode != 33) && (charCode != 35) && (charCode != 36) && (charCode != 42) && (charCode != 43) && (charCode != 45) && (charCode != 46) && (charCode < 48 || charCode > 57) && (charCode != 61) && (charCode != 63) && (charCode != 64) && (charCode < 97 || charCode > 122))
                    {
                         count++;
                    }
                }
                if(text.length > 20)
                    $('#error_'+element.id).text('слишком длинный пароль !');
                else if(count>0) 
                    $('#error_'+element.id).text('запрещённые символы !');
                else if(text.length > 0 && text.length < 6)
                    $('#error_'+element.id).text('слишком кароткий пароль !');
                else 
                {
                    $('#error_'+element.id).text('');
                    
                }
                if($(element).val() != '')
                    $(element).tooltip('hide');//скрываем tooltip
            }
            else if(id == 'email')
            {
                    var text = element.value;
                if(text == '')
                {
                    $('#error_'+element.id).text('');
                    return;
                } 
    			var reg = new RegExp("^[a-z0-9_\\.\\-]+@[a-z]+\\.[a-z]{2,4}$");
                if(!reg.test(text))
                {
                    $('#error_'+element.id).text('некоректный email');
                }
                else $('#error_'+element.id).text('');                
                
                if($(element).val() != '')
                    $(element).tooltip('hide');//скрываем tooltip
            }
            else if(id == 'telephone')
            {
                var text = element.value;
                if(text == '')
                {
                    $('#error_'+element.id).text('');
                    return;
                } 
    			var reg = new RegExp(/^\d{3}\s\d{7}$/);
                if(!reg.test(text))
                {
                    $('#error_'+element.id).text('*** *******  формат номера');
                }
                else $('#error_'+element.id).text('');                
                
                if($(element).val() != '')
                    $(element).tooltip('hide');//скрываем tooltip
            }
            else if(id == 'capt')
            {
                var text = element.value;
    			var count = 0;
                for(var i = 0; i < text.length; i++)
    			{
    				var charCode = text.charCodeAt(i);
                    if((charCode < 48 || charCode > 57) && (charCode < 97 || charCode > 122))
                    {
                         count++;
                    }
                }
                if(text.length > 10)
                    $('#error_'+element.id).text('слишком длинная строка !');
                else if(count>0) 
                    $('#error_'+element.id).text('запрещённые символы !');
                else 
                    $('#error_'+element.id).text('');
                    
                if($(element).val() != '')
                    $(element).tooltip('hide');//скрываем tooltip
            }
            else if(id == 'limit')
            {
                var text = element.value;
                var tmp_str = "";

                for(var i = 0; i < text.length; i++)
                {
                    var charCode = text.charCodeAt(i);
                    if(charCode >= 48 && charCode <= 57)
                    {
                        tmp_str = tmp_str + text[i];
                    }
                }

                jQuery(element).val(tmp_str);//заносим новую строку в поле-ввода количества страниц
                if(jQuery(element).val() != '')
                {
                    jQuery(element).tooltip('hide');//скрываем tooltip
                    //jQuery(".br_error").hide();
                    tool_tip_analysis_type = false;
                }

                //<<сразу же делаем перерасчёт стоимости
                var v = 0;
                if(tmp_str != "")
                {
                    v = parseInt(tmp_str, 10);
                }
                v = v * tariff;
                v = (v).toFixed(2);
                jQuery('.price').text(v);//показываем сколько это будет стоить !!!
                //>>end

                //-----------------------------------------------
                if(element.value != "")
                {
                    var v_price   = parseFloat(jQuery(".price").text());
                    var v_balance = parseFloat(jQuery(".balance").text());
                    if(v_price == 0)
                    {
                        jQuery("#error_limit").text("лимит страниц введён не верно.");
                    }
                    else if(v_price > v_balance)
                    {
                        var col_page = v_balance / tariff;
                        jQuery("#error_limit").text("превышение лимита (" + col_page + " страниц)");
                    }
                }
                else
                {
                    jQuery("#error_limit").text("");
                }
                //------------------------------------------------
            }
            else if(id == 'timeout')
            {
                var text = element.value;
                var tmp_str = "";

                for(var i = 0; i < text.length; i++)
                {
                    var charCode = text.charCodeAt(i);
                    if(charCode >= 48 && charCode <= 57)
                    {
                        tmp_str = tmp_str + text[i];
                    }
                }

                jQuery(element).val(tmp_str);//заносим новую строку в поле-ввода количества дней - "Повторное сканирование"

                if(jQuery(element).val() != '')
                {
                    jQuery(element).tooltip('hide');//скрываем tooltip

                    var v = 0;
                    v = parseInt(tmp_str, 10);
                    v = v%10;
                    var n = "&nbsp;";

                    if(v == 0)
                    {
                        //0 дней
                        jQuery('.day_timeout').html(n+"дней");
                    }
                    else if(v == 1)
                    {
                        //1 день
                        jQuery('.day_timeout').html(n+"день");
                    }
                    else if(v > 1 && v < 5)
                    {
                        //2,3,4 дня
                        jQuery('.day_timeout').html(n+"дня");
                    }
                    else if(v >= 5)
                    {
                        //5,6,7,8,9 дней
                        jQuery('.day_timeout').html(n+"дней");
                    }
                    else
                    {
                        //дней
                        jQuery('.day_timeout').text("");
                    }
                }
                else
                {
                    jQuery('.day_timeout').text("");
                }
            }
            
        }, 12);        
    });
    
    //проверка на коректность ввода данных lname и fname
    jQuery('#lname,#fname').keypress(function(e)
    {
        var element = this;
        setTimeout(function()
        { 
            //(charCode >= 65 && charCode <= 90)  (A-Z)
            //(charCode >= 97 && charCode <= 122) (a-z)
            //(charCode == 1025)(Ё)
            //(charCode >= 1040 && charCode <= 1103)(А-Яа-я)
            //(charCode == 1105)(ё)
            var text = element.value;
			var count = 0;
            for(var i = 0; i < text.length; i++)
			{
				var charCode = text.charCodeAt(i);
                if((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && (charCode != 1025) && (charCode < 1040 || charCode > 1103) && (charCode != 1105))
                {
                     count++;
                }
            }
            if(text.length > 20)
                $('#error_'+element.id).text('слишком длинная строка !');
            else if(count > 0)
                $('#error_'+element.id).text('запрещённые символы !');
            else 
                $('#error_'+element.id).text('');
            
            if($(element).val() != '')
                $(element).tooltip('hide');//скрываем tooltip
        },12);
    });
    
    //проверка на коректность ввода данных + проверка на существование дубликатов логина в б.д. + меняем tooltip
    jQuery('#login').keypress(function(e)
    {
        var element = this;
        setTimeout(function()
        {            
            var text = element.value;
			var count = 0;
            for(var i = 0; i < text.length; i++)
			{
				var charCode = text.charCodeAt(i);
                if((charCode < 48 || charCode > 57) && (charCode < 97 || charCode > 122))
                {
                     count++;
                }
            }
            if(text.length > 20)
                $('#error_'+element.id).text('слишком длинный логин !');
            else if(count>0) 
                $('#error_'+element.id).text('запрещённые символы !');
            else 
            {
                $('#error_'+element.id).text('');
                var str = $(element).val();
                jQuery.post('model/checklogin.php', {'chlogin':str}, function(data)
                { 
                    jQuery('#error_'+element.id).text(data.chlogin);
                }, 'json'); 
            }
            if($(element).val() != '')
                $(element).tooltip('hide');//скрываем tooltip
        },12);
    });
    
    //проверка на коректность ввода данных + проверка на существование дубликатов логина в б.д. + меняем tooltip
    jQuery('#password').keypress(function(e)
    {
        var element = this;
        setTimeout(function()
        {    
            //(charCode == 33)(!)
            //(charCode == 35)(#)
            //(charCode == 36)($)
            //(charCode == 42)(*)
            //(charCode == 43)(+)
            //(charCode == 46)(.)
            //(charCode == 45)(-)  
            //(charCode >= 48 && charCode <= 57)  (0-9)
            //(charCode == 61)(=)
            //(charCode == 63)(?)
            //(charCode == 64)(@)
            //(charCode >= 97 && charCode <= 122) (a-z)
            var text = element.value;
			var count = 0;
            for(var i = 0; i < text.length; i++)
			{
				var charCode = text.charCodeAt(i);
                if((charCode != 33) && (charCode != 35) && (charCode != 36) && (charCode != 42) && (charCode != 43) && (charCode != 45) && (charCode != 46) && (charCode < 48 || charCode > 57) && (charCode != 61) && (charCode != 63) && (charCode != 64) && (charCode < 97 || charCode > 122))
                {
                     count++;
                }
            }
            
            if(text.length > 16)
                $('#error_'+element.id).text('слишком длинный пароль !');
            else if(count>0) 
                $('#error_'+element.id).text('запрещённые символы !');
            else if(text.length > 0 && text.length < 6)
                $('#error_'+element.id).text('слишком кароткий пароль !');
            else 
            {
                $('#error_'+element.id).text('');
                
            }
            if($(element).val() != '')
                $(element).tooltip('hide');//скрываем tooltip
        },12);
    });
    
    //проверка на коректность ввода email
    jQuery('#email').keypress(function(e)
    {
        var element = this;
        setTimeout(function(){            
            var text = element.value;
            if(text == '')
            {
                $('#error_'+element.id).text('');
                return;
            } 
			var reg = new RegExp("^[a-z0-9_\\.\\-]+@[a-z]+\\.[a-z]{2,4}$");
            if(!reg.test(text))
            {
                $('#error_'+element.id).text('некоректный email');
            }
            else $('#error_'+element.id).text('');                
            
            if($(element).val() != '')
                $(element).tooltip('hide');//скрываем tooltip
        },12);
    });
    
    //проверка на коректность ввода email
    jQuery('#telephone').keypress(function(e)
    {
        var element = this;
        setTimeout(function(){            
            var text = element.value;
            if(text == '')
            {
                $('#error_'+element.id).text('');
                return;
            } 
			var reg = new RegExp(/^\d{3}\s\d{7}$/);
            if(!reg.test(text))
            {
                $('#error_'+element.id).text('*** *******  формат номера');
            }
            else $('#error_'+element.id).text('');                
            
            if($(element).val() != '')
                $(element).tooltip('hide');//скрываем tooltip
        },12);
    });
    
    jQuery('#capt').keypress(function(e)
    {
        var element = this;
        setTimeout(function()
        {            
            var text = element.value;
			var count = 0;
            for(var i = 0; i < text.length; i++)
			{
				var charCode = text.charCodeAt(i);
                if((charCode < 48 || charCode > 57) && (charCode < 97 || charCode > 122))
                {
                     count++;
                }
            }
            if(text.length > 10)
                $('#error_'+element.id).text('слишком длинная строка !');
            else if(count>0) 
                $('#error_'+element.id).text('запрещённые символы !');
            else 
                $('#error_'+element.id).text('');
                
            if($(element).val() != '')
                $(element).tooltip('hide');//скрываем tooltip
        },12);
    });
    
    //при отправке данных формы проверяем на незаполненность полей
    /*jQuery('#myform').submit(function()
    {		
        var ar = jQuery(":text,:password");
        var count = 0;        
        for(var i = 0; i < ar.length; i++)
        {
            if(ar[i].value == "")
            {
                //$('#'+ar[i].id).tooltip('show');
                //count++;

                //<<этот блок кода только для элемента jQuery('.input_calculate');
                if(jQuery(ar[i]).hasClass("input_calculate") && jQuery(ar[i]).is(":visible"))
                {
                    //jQuery(".br_error").show();
                    $('#'+ar[i].id).tooltip('show');
                    tool_tip_analysis_type = true;
                    count++;
                }
                else if(jQuery(ar[i]).hasClass("input_calculate") && !jQuery(ar[i]).is(":visible"))
                {
                    //tooltip и <br /> скрывается! если тип анализа Бесплатный и нажата кнопка "submit"
                    //jQuery(".br_error").hide();
                    //$('#'+ar[i].id).tooltip('hide');
                    //tool_tip_analysis_type = false;
                }
                //>>end
                //<<этот блок кода только для элемента jQuery('.input_timeout');
                else if(jQuery(ar[i]).hasClass("input_timeout") && jQuery(ar[i]).is(":visible"))
                {
                    $('#'+ar[i].id).tooltip('show');
                    tool_tip_analysis_timeout = true;
                    count++;
                }
                else if(jQuery(ar[i]).hasClass("input_timeout") && !jQuery(ar[i]).is(":visible"))
                {
                    //tooltip скрывается! если тип анализа Бесплатный и нажата кнопка "submit"
                     //$('#'+ar[i].id).tooltip('hide');
                     //tool_tip_analysis_timeout = false;
                }
                //>>end
                else
                {
                    $('#'+ar[i].id).tooltip('show');
                    count++;
                }

            }
            else $('#'+ar[i].id).tooltip('hide');
        }        
        if(count>0)
        {
            return false;
        }
        //return click_analysis();
        return true;
    });*/
    
    //меняем картинку капчи
    jQuery('#another').click(function()
    {
        jQuery.post('captcha/another.php', {"key":true}, function(data)
        {
            jQuery('#capcha-image').attr('src', 'captcha/captcha.php?key='+data.key );
        }, 'json');
        jQuery('#error_captcha').text('');
    });

    //меняем название кнопки при выставлении галочки - Удалить профиль
    /*jQuery("#del").change(function()
    {
        if($(this).prop("checked"))
          jQuery('#submit').text('Удалить');
        else
          jQuery('#submit').text('Обновить');
    });*/
    
    //меняем режим отображения пароля ( password | text ) при выставлении галочки - показать пароль
    jQuery("#chpass").change(function()
    {
        if($(this).prop("checked"))
        {
            var marker = $('<input />').insertBefore('#password,#inp2');
            $('#password,#inp2').detach().attr('type', 'text').insertAfter(marker).focus();
            marker.remove();
            jQuery('#chpass_text').text('скрыть пароль');
        }
        else
        {            
            var marker = $('<input />').insertBefore('#password,#inp2');
            $('#password,#inp2').detach().attr('type', 'password').insertAfter(marker).focus();
            marker.remove();
            jQuery('#chpass_text').text('показать пароль');
        }
    });
    
    //меняем размер iframe по размеру контента - при загрузке
    jQuery('#myiframe').load(function(){
        var w = $('.content').width();
        var h = $('.content').height();
        $('#myiframe').width(w-4);
        $('#myiframe').height(h);
    });
    //меняем размер iframe по размеру контента - при изменении размера окна браузера
    jQuery(window).resize(function(){
        var w = $('.content').width();
        var h = $('.content').height();
        $('#myiframe').width(w-4);
        $('#myiframe').height(h);
    });


    //========================================================================
    //=================обработчики клавиш Backspace и Delete==================
    //========================================================================
    //=================http://learn.javascript.ru/keyboard-events=============

    //проверка на коректность ввода данных + проверка на существование дубликатов логина в б.д. + меняем tooltip
    jQuery('#login').keydown(function(e)
    {
        if(e.keyCode == 46 || e.keyCode == 8)
        {
            var element = this;
            setTimeout(function()
            {
                var text = element.value;
                var count = 0;
                for(var i = 0; i < text.length; i++)
                {
                    var charCode = text.charCodeAt(i);
                    if((charCode < 48 || charCode > 57) && (charCode < 97 || charCode > 122))
                    {
                        count++;
                    }
                }
                if(text.length > 20)
                    $('#error_'+element.id).text('слишком длинный логин !');
                else if(count>0)
                    $('#error_'+element.id).text('запрещённые символы !');
                else
                {
                    $('#error_'+element.id).text('');
                    var str = $(element).val();
                    jQuery.post('model/checklogin.php', {'chlogin':str}, function(data)
                    {
                        jQuery('#error_'+element.id).text(data.chlogin);
                    }, 'json');
                }
                if($(element).val() != '')
                    $(element).tooltip('hide');//скрываем tooltip
            },12);
        }
    });

    //проверка на коректность ввода данных + проверка на существование дубликатов логина в б.д. + меняем tooltip
    jQuery('#password').keydown(function(e)
    {
        if(e.keyCode == 46 || e.keyCode == 8)
        {
            var element = this;
            setTimeout(function()
            {
                //(charCode == 33)(!)
                //(charCode == 35)(#)
                //(charCode == 36)($)
                //(charCode == 42)(*)
                //(charCode == 43)(+)
                //(charCode == 46)(.)
                //(charCode == 45)(-)
                //(charCode >= 48 && charCode <= 57)  (0-9)
                //(charCode == 61)(=)
                //(charCode == 63)(?)
                //(charCode == 64)(@)
                //(charCode >= 97 && charCode <= 122) (a-z)
                var text = element.value;
                var count = 0;
                for(var i = 0; i < text.length; i++)
                {
                    var charCode = text.charCodeAt(i);
                    if((charCode != 33) && (charCode != 35) && (charCode != 36) && (charCode != 42) && (charCode != 43) && (charCode != 45) && (charCode != 46) && (charCode < 48 || charCode > 57) && (charCode != 61) && (charCode != 63) && (charCode != 64) && (charCode < 97 || charCode > 122))
                    {
                        count++;
                    }
                }

                if(text.length > 16)
                    $('#error_'+element.id).text('слишком длинный пароль !');
                else if(count>0)
                    $('#error_'+element.id).text('запрещённые символы !');
                else if(text.length > 0 && text.length < 6)
                    $('#error_'+element.id).text('слишком кароткий пароль !');
                else
                {
                    $('#error_'+element.id).text('');

                }
                if($(element).val() != '')
                    $(element).tooltip('hide');//скрываем tooltip
            },12);
        }
    });

    //проверка на коректность ввода email
    jQuery('#email').keydown(function(e)
    {
        if(e.keyCode == 46 || e.keyCode == 8)
        {
            var element = this;
            setTimeout(function(){
                var text = element.value;
                if(text == '')
                {
                    $('#error_'+element.id).text('');
                    return;
                }
                var reg = new RegExp("^[a-z0-9_\\.\\-]+@[a-z]+\\.[a-z]{2,4}$");
                if(!reg.test(text))
                {
                    $('#error_'+element.id).text('некоректный email');
                }
                else $('#error_'+element.id).text('');

                if($(element).val() != '')
                    $(element).tooltip('hide');//скрываем tooltip
            },12);
        }
    });

    jQuery('#capt').keydown(function(e)
    {
        if(e.keyCode == 46 || e.keyCode == 8)
        {
            var element = this;
            setTimeout(function()
            {
                var text = element.value;
                var count = 0;
                for(var i = 0; i < text.length; i++)
                {
                    var charCode = text.charCodeAt(i);
                    if((charCode < 48 || charCode > 57) && (charCode < 97 || charCode > 122))
                    {
                        count++;
                    }
                }
                if(text.length > 10)
                    $('#error_'+element.id).text('слишком длинная строка !');
                else if(count>0)
                    $('#error_'+element.id).text('запрещённые символы !');
                else
                    $('#error_'+element.id).text('');

                if($(element).val() != '')
                    $(element).tooltip('hide');//скрываем tooltip
            },12);
        }
    });

    //========================================================================
    //==================обработчики для формы АНАЛИЗа сайта===================
    //========================================================================

    /*//по умолчанию - отдаём фокус окну window
     //это нужно - потому что если перезагрузить страницу F5 или Ctr+F5 без фокуса
     //тогда ниже указанные обработчики не отработают
     jQuery(window).focus();

     //обработчик если перезагрузить страницу F5 или Backspace
     jQuery(window).keydown(function(event)
     {
     var r = event.keyCode;
     if(event.keyCode == 116 || event.keyCode == 8)
     {
     window.onbeforeunload = null;
     }
     });

     //обработчик если перезагрузить страницу Ctr+F5
     jQuery(window).keyup(function(event)
     {
     var r = event.keyCode;
     if(event.keyCode == 116)
     {
     window.onbeforeunload = null;
     }
     });

     //обработчик если уходим с сайта по внешней ссылке (у которой хост не такой как у текущего сайта!!!)
     jQuery('a').click(function (event)
     {
     var h = this.href;

     if(h.indexOf(window.location.host) != -1)
     {
     window.onbeforeunload = null;
     }
     });*/

    var file_php                  = "/index.php";    // файл для отправки скрипта
    var file_validation_data_php  = "model/validation_data.php";    // файл для отправки скрипта
    var file_save_tools_php       = "model/save_tools.php";    // файл для отправки скрипта
    var file_change_balance_php   = "model/get_balance.php";    // файл для отправки скрипта
    var file_report_php           = "model/project_report.php";
    var file_analyze_php          = "model/test_analyze.php";    // файл для отправки скрипта

    var count_div = 0;               // номер div-класса при динамическом создании элементов в jQuery

    var _http     = "http://";       // протокол для поиска в URL
    var _https    = "https://";      // протокол для поиска в URL

    var tariff    = 0.05;            // тариф за одну просканированную страницу
    var ar_errors = ["url", "limit"];// название ошибок
    //var currency  = " grn.";       // текущая валюта

    var analise_pay = false;         // тип анализа (true - платный, false - бесплатный)
    var tool_tip_analysis_type    = false;// для отслеживаня состояния tooltip на эелементе jQuery(".input_calculate");
    var tool_tip_analysis_timeout = false;// для отслеживаня состояния tooltip на эелементе jQuery(".analysis_timeout");

    var timeout_balance = 5000; // 5 сек. == 5000

    //jQuery(".br_error").hide();
    //jQuery(".analysis_type option[class='pay']").attr("selected", "true");
    //jQuery("#analysis").hide();

    //jQuery(".div_free").hide();
    //jQuery(".timeout").hide();

    //обработчик нажатия на кнопку "АНАЛИЗ"
    function analysis()
    {
        var obj_errors   = {};
        var count_error = 0;

        //<<проверки URL на пустоту--------------
        var url = jQuery("#url").val() || jQuery("#url").text();
        if(url == "")
        {
            count_error++;
            obj_errors["url"] = "Ошибка: неверный формат адреса сайта.";
        }
        else
        {
            if( (url.indexOf(_http) == -1) && (url.indexOf(_https) == -1) )
            {
                url = _http + url;
            }
            obj_errors["url"] = "";
        }
        //>>end-----------------------------------

        //<<проверки лимита страниц---------------
        jQuery(".analysis_type option").each(function()
        {
            if(jQuery(this).hasClass("pay") && this.selected)
            {
                var v_price   = parseFloat(jQuery(".price").text());
                var v_balance = parseFloat(jQuery(".balance").text());
                if(v_price == 0)
                {
                    count_error++;
                    obj_errors["limit"] = "лимит страниц введён не верно.";
                }
                else if(v_price > v_balance)
                {
                    count_error++;
                    var col_page = v_balance / tariff;
                    obj_errors["limit"] = "превышение лимита (" + col_page + " страниц)";
                }
                else
                {
                    obj_errors["limit"] = "";
                }
            }
        });
        //>>end-----------------------------------

        //если есть ошибки - выводим их!!!--------
        if(count_error)
        {
            //jQuery(".div_errors").css("border", "2px solid red");
            for(var i=0; i< ar_errors.length; i++)
            {
                if(obj_errors.hasOwnProperty(ar_errors[i]))
                {
                    jQuery("#error_"+ar_errors[i]).text(obj_errors[ar_errors[i]]);
                }
            }
            return false;
        }
        else
        {
            //jQuery(".div_errors").css("border", "none");
            for(var i=0; i< ar_errors.length; i++)
            {
                if(obj_errors.hasOwnProperty(ar_errors[i]))
                {
                    jQuery("#error_"+ar_errors[i]).text(obj_errors[ar_errors[i]]);
                }
            }
            return true;
        }
    }

    //скрываем tooltip в поле url
    jQuery('#url').keypress(function(e)
    {
        var element = this;
        setTimeout(function()
        {
            if($(element).val() != '')
                $(element).tooltip('hide');//скрываем tooltip
        },12);
    });

    //обработчик для поля-ввода количества страниц
    //пропускаем любые символы кроме цыфр (0-9),
    //а цыфры обрабатываем делаем пересчет стоимости
    jQuery('.input_calculate').keypress(function(e)
    {
        //(charCode >= 48 && charCode <= 57)  (0-9)
        if(e.charCode >= 48 && e.charCode <= 57)
        {
            var element = this;
            //ф-я setTimeout() - нужна потому что при наступления события данных ещё нет в поле-ввода
            //делаем минимальный таймаут 0,012 сек. - этого таймаута достаточно, что бы данные попали в поле-ввода
            //делать таймаут меньше 0,012 сек. нет смысла!!! Так как всё что меньше 0,012 сек. - отработает одинаково!
            setTimeout(function()
            {
                var text = element.value;
                var v = 0;

                if(text != "")
                {
                    v = parseInt(text, 10);
                }

                v = v * tariff;
                v = (v).toFixed(2);
                jQuery('.price').text(v);//показываем сколько это будет стоить !!!

                //-----------------------------------------------
                var v_price   = parseFloat(jQuery(".price").text());
                var v_balance = parseFloat(jQuery(".balance").text());
                if(v_price == 0)
                {
                    jQuery("#error_limit").text("лимит страниц введён не верно.");
                }
                else if(v_price > v_balance)
                {
                    var col_page = v_balance / tariff;
                    jQuery("#error_limit").text("превышение лимита (" + col_page + " страниц)");
                }
                else
                {
                    jQuery("#error_limit").text("");
                }
                //------------------------------------------------

                if(jQuery(element).val() != '')
                {
                    jQuery(element).tooltip('hide');//скрываем tooltip
                    //jQuery(".br_error").hide();
                    tool_tip_analysis_type = false;
                }

            }, 12);
        }
        else
        {
            return false;
        }
    });

    // События cut, copy, paste
    //Эти события используются редко, но иногда бывают полезны. Они происходят при вырезании/вставке/копировании значения в поле.
    // http://learn.javascript.ru/events-change
    jQuery('.input_calculate').on("cut",function(e)
    {
        var element = this;

        setTimeout(function()
        {
            if(element.value == "")
            {
                jQuery("#error_limit").text("");
                //<<сразу же делаем перерасчёт стоимости
                var v = 0;
                if(element.value != "")
                {
                    v = parseInt(element.value, 10);
                }
                v = v * tariff;
                v = (v).toFixed(2);
                jQuery('.price').text(v);//показываем сколько это будет стоить !!!
                //>>end
            }
            else
            {
                var text = element.value;
                var tmp_str = "";

                for(var i = 0; i < text.length; i++)
                {
                    var charCode = text.charCodeAt(i);
                    if(charCode >= 48 && charCode <= 57)
                    {
                        tmp_str = tmp_str + text[i];
                    }
                }

                jQuery(element).val(tmp_str);//заносим новую строку в поле-ввода количества страниц
                if(jQuery(element).val() != '')
                {
                    jQuery(element).tooltip('hide');//скрываем tooltip
                    //jQuery(".br_error").hide();
                    tool_tip_analysis_type = false;
                }

                //<<сразу же делаем перерасчёт стоимости
                var v = 0;
                if(tmp_str != "")
                {
                    v = parseInt(tmp_str, 10);
                }
                v = v * tariff;
                v = (v).toFixed(2);
                jQuery('.price').text(v);//показываем сколько это будет стоить !!!
                //>>end

                //-----------------------------------------------
                if(element.value != "")
                {
                    var v_price   = parseFloat(jQuery(".price").text());
                    var v_balance = parseFloat(jQuery(".balance").text());
                    if(v_price == 0)
                    {
                        jQuery("#error_limit").text("лимит страниц введён не верно.");
                    }
                    else if(v_price > v_balance)
                    {
                        var col_page = v_balance / tariff;
                        jQuery("#error_limit").text("превышение лимита (" + col_page.toFixed(0) + " страниц)");
                    }
                    else
                    {
                        jQuery("#error_limit").text("");
                    }
                }
                else
                {
                    jQuery("#error_limit").text("");
                }
                //------------------------------------------------
            }
        }, 12);
    });

    // http://learn.javascript.ru/events-change
    // input - очень хорошее событие - альтернатива всем событиям (cut,keypress,keydown,past)
    // не убираю все остальные обработчики, потому что не тестировал и как говорится в мануале (не работает в IE<9)
    /*jQuery('.input_calculate').on("input",function(e)
    {
        var element = this;

        setTimeout(function()
        {
            if(element.value == "")
            {
                jQuery("#error_limit").text("");
                //<<сразу же делаем перерасчёт стоимости
                var v = 0;
                if(element.value != "")
                {
                    v = parseInt(element.value, 10);
                }
                v = v * tariff;
                v = (v).toFixed(2);
                jQuery('.price').text(v);//показываем сколько это будет стоить !!!
                //>>end
            }
            else
            {
                var text = element.value;
                var tmp_str = "";

                for(var i = 0; i < text.length; i++)
                {
                    var charCode = text.charCodeAt(i);
                    if(charCode >= 48 && charCode <= 57)
                    {
                        tmp_str = tmp_str + text[i];
                    }
                }

                jQuery(element).val(tmp_str);//заносим новую строку в поле-ввода количества страниц
                if(jQuery(element).val() != '')
                {
                    jQuery(element).tooltip('hide');//скрываем tooltip
                    //jQuery(".br_error").hide();
                    tool_tip_analysis_type = false;
                }

                //<<сразу же делаем перерасчёт стоимости
                var v = 0;
                if(tmp_str != "")
                {
                    v = parseInt(tmp_str, 10);
                }
                v = v * tariff;
                v = (v).toFixed(2);
                jQuery('.price').text(v);//показываем сколько это будет стоить !!!
                //>>end

                //-----------------------------------------------
                if(element.value != "")
                {
                    var v_price   = parseFloat(jQuery(".price").text());
                    var v_balance = parseFloat(jQuery(".balance").text());
                    if(v_price == 0)
                    {
                        jQuery("#error_limit").text("лимит страниц введён не верно.");
                    }
                    else if(v_price > v_balance)
                    {
                        var col_page = v_balance / tariff;
                        jQuery("#error_limit").text("превышение лимита (" + col_page + " страниц)");
                    }
                    else
                    {
                        jQuery("#error_limit").text("");
                    }
                }
                else
                {
                    jQuery("#error_limit").text("");
                }
                //------------------------------------------------
            }
        }, 12);
    });*/

    //обработчик для поля-ввода количества страниц
    //пропускаем любые символы кроме (Backspace || Delete),
    //при нажатии этих клавиш делаем пересчет стоимости
    jQuery('.input_calculate').keydown(function(e)
    {
        //(charCode == 8 || charCode == 46)  (Backspace || Delete)
        if(e.keyCode == 8 || e.keyCode == 46)
        {
            var element = this;
            //ф-я setTimeout() - нужна потому что при наступления события данных ещё нет в поле-ввода
            //делаем минимальный таймаут 0,012 сек. - этого таймаута достаточно, что бы данные попали в поле-ввода
            //делать таймаут меньше 0,012 сек. нет смысла!!! Так как всё что меньше 0,012 сек. - отработает одинаково!
            setTimeout(function()
            {
                var text = element.value;
                var v = 0;

                if(text != "")
                {
                    v = parseInt(text, 10);
                }

                v = v * tariff;
                v = (v).toFixed(2);
                jQuery('.price').text(v);//показываем сколько это будет стоить !!!

                //-----------------------------------------------
                if(element.value != "")
                {
                    var v_price   = parseFloat(jQuery(".price").text());
                    var v_balance = parseFloat(jQuery(".balance").text());
                    if(v_price == 0)
                    {
                        jQuery("#error_limit").text("лимит страниц введён не верно.");
                    }
                    else if(v_price > v_balance)
                    {
                        var col_page = v_balance / tariff;
                        jQuery("#error_limit").text("превышение лимита (" + col_page + " страниц)");
                    }
                    else
                    {
                        jQuery("#error_limit").text("");
                    }
                }
                else
                {
                    jQuery("#error_limit").text("");
                }
                //------------------------------------------------

            }, 12);
        }
    });

    //обработчик для поля-ввода количества страниц
    //отработает при копировании в поле каких либо данных,
    //удалит все символы и оставит только цифры
    /*jQuery('.input_calculate').on('paste', function()
    {
        var element = this;
        //ф-я setTimeout() - нужна потому что при наступления события данных ещё нет в поле-ввода
        //делаем минимальный таймаут 0,012 сек. - этого таймаута достаточно, что бы данные попали в поле-ввода
        //делать таймаут меньше 0,012 сек. нет смысла!!! Так как всё что меньше 0,012 сек. - отработает одинаково!
        setTimeout(function()
        {
            var text = element.value;
            var tmp_str = "";

            for(var i = 0; i < text.length; i++)
            {
                var charCode = text.charCodeAt(i);
                if(charCode >= 48 && charCode <= 57)
                {
                    tmp_str = tmp_str + text[i];
                }
            }

            jQuery(element).val(tmp_str);//заносим новую строку в поле-ввода количества страниц
            if(jQuery(element).val() != '')
            {
                jQuery(element).tooltip('hide');//скрываем tooltip
                jQuery(".br_error").hide();
            }

            //<<сразу же делаем перерасчёт стоимости
            var v = 0;
            if(tmp_str != "")
            {
                v = parseInt(tmp_str, 10);
            }
            v = v * tariff;
            v = (v).toFixed(2);
            jQuery('.price').text(v);//показываем сколько это будет стоить !!!
            //>>end

        }, 12);
    });*/

    jQuery(".input_calculate").trigger("cut");

    //изменение типа анализа - Платный или Бесплатный
    jQuery(".analysis_type").change(function()
    {
        //jQuery(".analysis").show();// делаем кнопку "АНАЛИЗ" - видимой

        //<<скрываем ошибки------------------------
        /*jQuery(".div_errors").css("border", "none");
        for(var i=0; i< ar_errors.length; i++)
        {
            jQuery(".div_error_"+ar_errors[i]).text("");
        }*/
        //>>end------------------------------------

        //<<перебераем все елементы в списке-------
        jQuery(".analysis_type option").each(function()
        {
            if(jQuery(this).hasClass("pay") && this.selected)
            {
                jQuery(".div_calculate").show();// отображаем поле для ввода количества страниц
                analise_pay = true;             // запоминаем что тип анализа Платный
                jQuery(".div_free").hide();
                jQuery(".div_pay").show();
                /*if(tool_tip_analysis_type)
                {
                    jQuery(".br_error").show();
                }*/
                jQuery(".div_timeout").show(); // отображаем поле для ввода количества дней - "Повторное сканирование"


                //jQuery("#analysis").show();// делаем кнопку "АНАЛИЗ" - видимой
                //jQuery(".empty").remove();
            }
            else if(jQuery(this).hasClass("pay") && !this.selected)
            {
                jQuery(".div_calculate").hide();// скрываем поле для ввода количества страниц
                analise_pay = false;            // запоминаем что тип анализа Бесплатный
                jQuery(".div_free").show();
                jQuery(".div_pay").hide();
                //jQuery(".br_error").hide();
                jQuery(".div_timeout").hide();// скрываем поле для ввода количества дней - "Повторное сканирование"


                //jQuery("#analysis").show();// делаем кнопку "АНАЛИЗ" - видимой
                //jQuery(".empty").remove();
            }

            // удаляем первый елемент в списке - "Выберите тип анализа"
            /*if(jQuery(this).hasClass("empty"))
            {
                jQuery(".analysis").show();// делаем кнопку "АНАЛИЗ" - видимой
                jQuery(this).remove();
            }*/
        });
        //>>end------------------------------------

    });

    //изменение количества дней - "Повторное сканирование"
    jQuery(".analysis_timeout").change(function()
    {
        //<<перебераем все елементы в списке-------
        jQuery(".analysis_timeout option").each(function()
        {
            if(jQuery(this).hasClass("three") && this.selected)
            {
                jQuery(".timeout").show();// отображаем поле для ввода количества дней - "Повторное сканирование"
                jQuery(".input_timeout").focus();
            }
            else if(jQuery(this).hasClass("three") && !this.selected)
            {
                jQuery(".timeout").hide();// скрываем поле для ввода количества дней - "Повторное сканирование"
            }
        });
        //>>end------------------------------------
    });

    //<<вызываем обработчики - делаем чтобы скрылись ненужные поля
    jQuery(".analysis_timeout").change();
    jQuery(".analysis_type").change();
    //>>end

    //обработчик для поля-ввода количества дней - "Повторное сканирование"
    //пропускаем любые символы кроме цыфр (0-9),
    //а цыфры обрабатываем делаем пересчет
    jQuery('.input_timeout').keypress(function(e)
    {
        //(charCode >= 48 && charCode <= 57)  (0-9)
        if(e.charCode >= 48 && e.charCode <= 57)
        {
            var element = this;
            //ф-я setTimeout() - нужна потому что при наступления события данных ещё нет в поле-ввода
            //делаем минимальный таймаут 0,012 сек. - этого таймаута достаточно, что бы данные попали в поле-ввода
            //делать таймаут меньше 0,012 сек. нет смысла!!! Так как всё что меньше 0,012 сек. - отработает одинаково!
            setTimeout(function()
            {
                var text = element.value;
                var v = 0;

                if(text != "")
                {
                    jQuery(element).tooltip('hide');//скрываем tooltip

                    v = parseInt(text, 10);
                    var v = v%10;
                    var n = "&nbsp;";

                    if(v == 0)
                    {
                        //0 дней
                        jQuery('.day_timeout').html(n+"дней");
                    }
                    else if(v == 1)
                    {
                        //1 день
                        jQuery('.day_timeout').html(n+"день");
                    }
                    else if(v > 1 && v < 5)
                    {
                        //2,3,4 дня
                        jQuery('.day_timeout').html(n+"дня");
                    }
                    else if(v >= 5)
                    {
                        //5,6,7,8,9 дней
                        jQuery('.day_timeout').html(n+"дней");
                    }
                    else
                    {
                        //дней
                        jQuery('.day_timeout').text("");
                    }
                }

            }, 12);
        }
        else
        {
            return false;
        }
    });

    //обработчик для поля-ввода количества дней - "Повторное сканирование"
    //пропускаем любые символы кроме (Backspace || Delete),
    //при нажатии этих клавиш делаем пересчет
    jQuery('.input_timeout').keydown(function(e)
    {
        //(charCode == 8 || charCode == 46)  (Backspace || Delete)
        if(e.keyCode == 8 || e.keyCode == 46)
        {
            var element = this;
            //ф-я setTimeout() - нужна потому что при наступления события данных ещё нет в поле-ввода
            //делаем минимальный таймаут 0,012 сек. - этого таймаута достаточно, что бы данные попали в поле-ввода
            //делать таймаут меньше 0,012 сек. нет смысла!!! Так как всё что меньше 0,012 сек. - отработает одинаково!
            setTimeout(function()
            {
                var text = element.value;
                var v = 0;

                if(text != "")
                {
                    v = parseInt(text, 10);
                    v = v%10;
                    var n = "&nbsp;";

                    if(v == 0)
                    {
                        //0 дней
                        jQuery('.day_timeout').html(n+"дней");
                    }
                    else if(v == 1)
                    {
                        //1 день
                        jQuery('.day_timeout').html(n+"день");
                    }
                    else if(v > 1 && v < 5)
                    {
                        //2,3,4 дня
                        jQuery('.day_timeout').html(n+"дня");
                    }
                    else if(v >= 5)
                    {
                        //5,6,7,8,9 дней
                        jQuery('.day_timeout').html(n+"дней");
                    }
                    else
                    {
                        //дней
                        jQuery('.day_timeout').text("");
                    }
                }
                else
                {
                    //дней
                    jQuery('.day_timeout').text("");
                }

            }, 12);
        }
    });

    jQuery('.input_timeout').on("cut",function(e)
    {
        var element = this;

        setTimeout(function()
        {
            if(element.value == "")
            {
                jQuery('.day_timeout').text("");
            }
            else
            {
                var text = element.value;
                var tmp_str = "";

                for(var i = 0; i < text.length; i++)
                {
                    var charCode = text.charCodeAt(i);
                    if(charCode >= 48 && charCode <= 57)
                    {
                        tmp_str = tmp_str + text[i];
                    }
                }

                jQuery(element).val(tmp_str);//заносим новую строку в поле-ввода количества дней - "Повторное сканирование"

                if(jQuery(element).val() != '')
                {
                    var v = 0;
                    v = parseInt(tmp_str, 10);
                    v = v%10;
                    var n = "&nbsp;";

                    if(v == 0)
                    {
                        //0 дней
                        jQuery('.day_timeout').html(n+"дней");
                    }
                    else if(v == 1)
                    {
                        //1 день
                        jQuery('.day_timeout').html(n+"день");
                    }
                    else if(v > 1 && v < 5)
                    {
                        //2,3,4 дня
                        jQuery('.day_timeout').html(n+"дня");
                    }
                    else if(v >= 5)
                    {
                        //5,6,7,8,9 дней
                        jQuery('.day_timeout').html(n+"дней");
                    }
                    else
                    {
                        //дней
                        jQuery('.day_timeout').text("");
                    }
                }
                else
                {
                    jQuery('.day_timeout').text("");
                }
            }
        }, 12);
    });

    // http://learn.javascript.ru/events-change
    // input - очень хорошее событие - альтернатива всем событиям (cut,keypress,keydown,past)
    // не убираю все остальные обработчики, потому что не тестировал и как говорится в мануале (не работает в IE<9)
    /*jQuery('.input_timeout').on("input",function(e)
    {
        var element = this;

        setTimeout(function()
        {
            if(element.value == "")
            {
                jQuery('.day_timeout').text("");
            }
            else
            {
                var text = element.value;
                var tmp_str = "";

                for(var i = 0; i < text.length; i++)
                {
                    var charCode = text.charCodeAt(i);
                    if(charCode >= 48 && charCode <= 57)
                    {
                        tmp_str = tmp_str + text[i];
                    }
                }

                jQuery(element).val(tmp_str);//заносим новую строку в поле-ввода количества дней - "Повторное сканирование"

                if(jQuery(element).val() != '')
                {
                    var v = 0;
                    v = parseInt(tmp_str, 10);
                    v = v%10;
                    var n = "&nbsp;";

                    if(v == 0)
                    {
                        //0 дней
                        jQuery('.day_timeout').html(n+"дней");
                    }
                    else if(v == 1)
                    {
                        //1 день
                        jQuery('.day_timeout').html(n+"день");
                    }
                    else if(v > 1 && v < 5)
                    {
                        //2,3,4 дня
                        jQuery('.day_timeout').html(n+"дня");
                    }
                    else if(v >= 5)
                    {
                        //5,6,7,8,9 дней
                        jQuery('.day_timeout').html(n+"дней");
                    }
                    else
                    {
                        //дней
                        jQuery('.day_timeout').text("");
                    }
                }
                else
                {
                    jQuery('.day_timeout').text("");
                }
            }
        }, 12);
    });*/

    jQuery(".input_timeout").trigger("cut");

    function click_analysis()
    {
        var ar = jQuery(":text,:password");
        var count = 0;
        for(var i = 0; i < ar.length; i++)
        {
            if(ar[i].value == "")
            {
                /*$('#'+ar[i].id).tooltip('show');
                 count++;*/

                //<<этот блок кода только для элемента jQuery('.input_calculate');
                if(jQuery(ar[i]).hasClass("input_calculate") && jQuery(ar[i]).is(":visible"))
                {
                    //jQuery(".br_error").show();
                    $('#'+ar[i].id).tooltip('show');
                    tool_tip_analysis_type = true;
                    count++;
                }
                else if(jQuery(ar[i]).hasClass("input_calculate") && !jQuery(ar[i]).is(":visible"))
                {
                    //tooltip и <br /> скрывается! если тип анализа Бесплатный и нажата кнопка "submit"
                     $('#'+ar[i].id).tooltip('hide');
                     tool_tip_analysis_type = false;
                }
                //>>end
                //<<этот блок кода только для элемента jQuery('.input_timeout');
                else if(jQuery(ar[i]).hasClass("input_timeout") && jQuery(ar[i]).is(":visible"))
                {
                    $('#'+ar[i].id).tooltip('show');
                    tool_tip_analysis_timeout = true;
                    count++;
                }
                else if(jQuery(ar[i]).hasClass("input_timeout") && !jQuery(ar[i]).is(":visible"))
                {
                    //tooltip скрывается! если тип анализа Бесплатный и нажата кнопка "submit"
                     $('#'+ar[i].id).tooltip('hide');
                     tool_tip_analysis_timeout = false;
                }
                //>>end
                else
                {
                    $('#'+ar[i].id).tooltip('show');
                    count++;
                }

            }
            else $('#'+ar[i].id).tooltip('hide');
        }
        if(count>0)
        {
            return false;
        }
        return analysis();
        //return true;
    }

    jQuery("#analysis").click(function(e)
    {
        if(click_analysis())
        {
            var url = jQuery('#url').val();
            if( (url.indexOf(_http) == -1) && (url.indexOf(_https) == -1) )
            {
                url = _http + url;
            }

            /*var robots           = + jQuery(".robots").prop("checked");
             var analysis_type    = jQuery(".analysis_type option:selected").val();
             var limit            = jQuery('.input_calculate').val();
             var analysis_timeout = jQuery(".analysis_timeout option:selected").val();
             var timeout          = jQuery('.input_timeout').val();*/

            var analysis_type = parseInt( jQuery(".analysis_type option:selected").val(), 10 );
            var limit = "";
            var analysis_timeout = 0;
            var timeout = "";

            if( analysis_type )//платный анализ
            {
                limit = jQuery('.input_calculate').val();
                analysis_timeout = parseInt( jQuery(".analysis_timeout option:selected").val(), 10 );

                if(analysis_timeout == 1)//нет повторного сканирования
                {

                }
                else if(analysis_timeout == 2)//раз в 2-недели
                {

                }
                else//После окончания сканирования
                {
                   timeout = jQuery('.input_timeout').val();
                }
            }
            /*else//бесплатный анализ
            {
                analysis_timeout = parseInt( jQuery(".analysis_timeout option:selected").val(), 10 );
            }*/

            /*var obj = {
            url              : url,
            robots           : + jQuery(".robots").prop("checked"),
            analysis_type    : jQuery(".analysis_type option:selected").val(),
            limit_pages      : jQuery('.input_calculate').val(),
            analysis_timeout : jQuery(".analysis_timeout option:selected").val(),
            timeout          : jQuery('.input_timeout').val()
            };*/

            var obj = {
                url              : url,
                robots           : + jQuery(".robots").prop("checked"),
                email            : jQuery("#email").val(),
                analysis_type    : analysis_type,
                limit_pages      : limit,
                analysis_timeout : analysis_timeout,
                timeout          : timeout
            };

            jQuery("#analysis").prop('disabled', true);

            jQuery("<div>", { id : "loaderImage"}).appendTo(".div_response");

            jQuery.post(file_validation_data_php, obj, function(data)
            {
                if(data.error == "empty")
                {
                    //нет ошибок
                    //jQuery.post(file_analyze_php, obj);//- отправляем данные повторно jQuery.post для анализа сайта
                    jQuery(".div_response").html(data.response);
                    jQuery("span.balance").text(data.balance);
                    var ar = jQuery(":text,:password");
                    var count = 0;
                    for(var i = 0; i < ar.length; i++)
                    {
                        ar[i].value = "";
                        jQuery(ar[i]).trigger("cut");//можно вызвать обработчик "input" но как говорится в мануале (не работает в IE<9)
                    }
                    if(jQuery("div").is(".user-block") && jQuery("div").is(".useremail"))
                    {
                        jQuery("#email").val(jQuery("span.email").text());
                    }
                }
                else
                {
                    jQuery(".div_response").html(data.error);
                }
                jQuery("#analysis").prop('disabled', false);
                jQuery("#loaderImage").remove();
            }, "json");
        }
        /*else
        {
            alert("заполните данные");
        }*/
    });

    //project_report
    /*jQuery(".project_report").click(function(e)
    {
        var _href = this.href;
        jQuery.post(_href, null, function(data)
        {
            jQuery(".content").html(data.response);
        }, "json");

        return false;
    });*/

    jQuery("#save_tools").click(function(e)
    {
        if(click_analysis())
        {
            var url = jQuery('#url').text();
            if( (url.indexOf(_http) == -1) && (url.indexOf(_https) == -1) )
            {
                url = _http + url;
            }

            /*var robots           = + jQuery(".robots").prop("checked");
             var analysis_type    = jQuery(".analysis_type option:selected").val();
             var limit            = jQuery('.input_calculate').val();
             var analysis_timeout = jQuery(".analysis_timeout option:selected").val();
             var timeout          = jQuery('.input_timeout').val();*/

            var analysis_type = parseInt( jQuery(".analysis_type option:selected").val(), 10 );
            var limit = "";
            var analysis_timeout = 0;
            var timeout = "";

            if( analysis_type )//платный анализ
            {
                limit = jQuery('.input_calculate').val();
                analysis_timeout = parseInt( jQuery(".analysis_timeout option:selected").val(), 10 );

                if(analysis_timeout == 1)//нет повторного сканирования
                {

                }
                else if(analysis_timeout == 2)//раз в 2-недели
                {

                }
                else//После окончания сканирования
                {
                    timeout = jQuery('.input_timeout').val();
                }
            }
            /*else//бесплатный анализ
            {
                analysis_timeout = parseInt( jQuery(".analysis_timeout option:selected").val(), 10 );
            }*/

            /*var obj = {
             url              : url,
             robots           : + jQuery(".robots").prop("checked"),
             analysis_type    : jQuery(".analysis_type option:selected").val(),
             limit_pages      : jQuery('.input_calculate').val(),
             analysis_timeout : jQuery(".analysis_timeout option:selected").val(),
             timeout          : jQuery('.input_timeout').val()
             };*/

            var obj = {
                url              : url,
                robots           : + jQuery(".robots").prop("checked"),
                email            : jQuery("#email").val(),
                analysis_type    : analysis_type,
                limit_pages      : limit,
                analysis_timeout : analysis_timeout,
                timeout          : timeout
            };

            //jQuery.post(file_validation_data_php,{"url":url, "robots":robots, "analysis_type":analysis_type, "limit":limit, "analysis_timeout":analysis_timeout, "timeout":timeout},function(data)
            jQuery.post(file_save_tools_php, obj, function(data)
            {
                if(data.error == "empty")
                {
                    //нет ошибок
                    jQuery(".div_response").html(data.response);
                    /*var ar = jQuery(":text,:password");
                    var count = 0;
                    for(var i = 0; i < ar.length; i++)
                    {
                        ar[i].value = "";
                        jQuery(ar[i]).trigger("cut");//можно вызвать обработчик "input" но как говорится в мануале (не работает в IE<9)
                    }*/
                }
                else
                {
                    jQuery(".div_response").html(data.error);
                }
            }, "json");
        }
    });

    //нажатие на кнопку - повторный анализ
    jQuery(".restart_analysis").click(function(e)
     {
         var _href = this.href;

         /*var tmp_str = "";
         var params = [];
         var arg2   = [];
         for(var i=0; i<_href.length; i++)
         {
             if(_href[i] == '?')
             {
                 tmp_str = _href.substring(i+1, _href.length);
                 params = tmp_str.split("&");
                 arg2 = params[1].split("=");
                 break;
             }
         }*/

         jQuery.ajax(
             {
                 url      : _href,
                 success  : function(data)
                              {
                                  if(data.error == "empty")
                                  {
                                      //перезагружаем страницу, через 2 сек. после получения ответа от сервера
                                      setTimeout(function(){location.reload();}, 1000);
                                  }
                                  else
                                  {
                                      //задаём НЕпрозрачность
                                      jQuery("span#" + data.error).text(data.response).css({opacity: 1.0});
                                      var id = "span#" + data.error;

                                      //делаем анимацию - затухание текста
                                      setTimeout(function()
                                      {
                                          jQuery(id).animate({opacity:0.0},1500,null,function()
                                          {
                                              jQuery(id).text("");
                                          });
                                      }, 1000);
                                  }
                              },
                 async    : true, //true - асинхронно , false - синхронно
                 dataType : "json"
             });

         return false;
     });

    //постоянный мониторинг баланса пользователя
    if(jQuery("div").is(".user-block") && jQuery("span").is(".balance"))
    {
        setInterval(function(){
            var obj = { change_balance : "change_balance" };

            jQuery.post(file_change_balance_php, obj, function(data)
            {
                if(data.error == "empty")
                {
                    //нет ошибок
                    jQuery("span.balance").text(data.balance);
                    if(jQuery("input").is(".input_calculate") && jQuery("span").is(".pages"))
                    {
                        jQuery(".input_calculate").trigger("cut");

                        //-----------------------------------------------
                        var v_balance = parseFloat(jQuery(".balance").text());
                        var v = v_balance / tariff;
                        jQuery("span.pages").text((v).toFixed(0));
                    }
                }
            }, "json");
        }, timeout_balance);
    }

});
