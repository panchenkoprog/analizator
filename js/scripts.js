/*//обработчик закрытия окна
window.onbeforeunload = function (event)
{
    //window.onbeforeunload = null;
    return ' нажмите ОСТАТЬСЯ НА СТРАНИЦЕ! ';
}*/

jQuery(document).ready(function()
{
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

    var file_php  = "/index.php";    // файл для отправки скрипта
    var file_php_analise  = "/index.php?page=analise";// файл для отправки скрипта для анализа
    var count_div = 0;               // номер div-класса при динамическом создании элементов в jQuery

    var _http     = "http://";       // протокол для поиска в URL
    var _https    = "https://";      // протокол для поиска в URL

    var tariff    = 0.05;            // тариф за одну просканированную страницу
    var ar_errors = ["url", "limit"];// название ошибок
    //var currency  = " grn.";       // текущая валюта

    var analise_pay = false;         // тип анализа (true - платный, false - бесплатный)

    //URL по умолчанию
	jQuery(".url").val("http://varilki.com.ua/"); //http://www.masterenergoservice.com/  http://test53.stll.ru/  http://varilki.com.ua/  http://stll.com.ua/   http://outfit.org.ua/  http://iportal.com.ua/
    //по умолчанию - скрываем кнопку "АНАЛИЗ"
    jQuery(".analysis").hide();
    //по умолчанию - скрываем поле для ввода количества страниц
    jQuery(".div_calculate").hide();
    //по умолчанию - делаем первый елемент в списке отображаемым (selected) и недоступным для выбора (disabled)
    jQuery(".type_analize option[class='empty']").attr("selected", "true").attr("disabled", "true");

    //обработчик нажатия на кнопку "АНАЛИЗ"
	jQuery(".analysis").click(function()
    {
        var obj_errors   = {};
        var count_error = 0;

        //<<проверки URL на пустоту--------------
        var url = jQuery(".url").val();
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
        jQuery(".type_analize option").each(function()
        {
            if(jQuery(this).hasClass("pay") && this.selected)
            {
                var v_price   = parseFloat(jQuery(".price").text());
                var v_balance = parseFloat(jQuery(".balance").text());
                if(v_price == 0)
                {
                    count_error++;
                    obj_errors["limit"] = "Ошибка: лимит страниц введён не верно.";
                }
                else if(v_price > v_balance)
                {
                    count_error++;
                    var col_page = v_balance / tariff;
                    obj_errors["limit"] = "Ошибка: указанный лимит страниц превышает доступный для вас (" + col_page + " страниц)";
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
            jQuery(".div_errors").css("border", "2px solid red");
        }
        else
        {
            jQuery(".div_errors").css("border", "none");
        }

        for(var i=0; i< ar_errors.length; i++)
        {
            if(obj_errors.hasOwnProperty(ar_errors[i]))
            {
                jQuery(".div_error_"+ar_errors[i]).text(obj_errors[ar_errors[i]]);
            }
        }
        //>>end-----------------------------------

        //если ошибок нет - отправляем данные!!!--------
        if( !count_error )
        {
            var robots = jQuery(".robots").prop("checked");
            var analysis = 1;
            var limit = parseInt(jQuery('.input_calculate').val());

            if(analise_pay)//платный анализ
            {
                jQuery.post(file_php,{"url":url, "robots":robots, "analysis":1, "analise_pay":analise_pay, "limit":limit},function(data){

                    if(data == "empty !!!")
                    {
                        alert(data);
                    }
                    else
                    {
                        jQuery("<div>", { class : "div_response_"+count_div,
                            width : "auto",
                            height : "auto"
                        }).appendTo(".div_response");
                        jQuery(".div_response_"+count_div).html(data);
                        count_div++;
                    }
                });
            }
            else//бесплатный анализ
            {
                jQuery.post(file_php,{"url":url, "robots":robots, "analysis":1, "analise_pay":analise_pay},function(data){

                    if(data == "empty !!!")
                    {
                        alert(data);
                    }
                    else
                    {
                        jQuery("<div>", { class : "div_response_"+count_div,
                            width : "auto",
                            height : "auto"
                        }).appendTo(".div_response");
                        jQuery(".div_response_"+count_div).html(data);
                        count_div++;
                    }
                });
            }
        }
        //>>end-----------------------------------
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

            }, 12);
        }
        else
        {
            return false;
        }
    });

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

            }, 12);
        }
    });

    //обработчик для поля-ввода количества страниц
    //отработает при копировании в поле каких либо данных,
    //удалит все символы и оставит только цифры
    jQuery('.input_calculate').on('paste', function()
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

            jQuery('.input_calculate').val(tmp_str);//заносим новую строку в поле-ввода количества страниц

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
    });

    //изменение типа анализа - Платный или Бесплатный
    jQuery(".type_analize").change(function()
    {
        jQuery(".analysis").show();// делаем кнопку "АНАЛИЗ" - видимой

        //<<скрываем ошибки------------------------
        jQuery(".div_errors").css("border", "none");
        for(var i=0; i< ar_errors.length; i++)
        {
            jQuery(".div_error_"+ar_errors[i]).text("");
        }
        //>>end------------------------------------

        //<<перебераем все елементы в списке-------
        jQuery(".type_analize option").each(function()
        {
            if(jQuery(this).hasClass("pay") && this.selected)
            {
                jQuery(".div_calculate").show();// отображаем поле для ввода количества страниц
                analise_pay = true;             // запоминаем что тип анализа Платный
            }
            else if(jQuery(this).hasClass("pay") && !this.selected)
            {
                jQuery(".div_calculate").hide();// скрываем поле для ввода количества страниц
                analise_pay = false;            // запоминаем что тип анализа Бесплатный
            }

            // удаляем первый елемент в списке - "Выберите тип анализа"
            if(jQuery(this).hasClass("empty"))
            {
                jQuery(this).remove();
            }
        });
        //>>end------------------------------------

    });
});