<?php
/*
 * класс используется в поиске и парсинге страницы
 */
class Link
{
    public $url='';
    public $level=0;
    public $parent_url = '';
    public $id = 0;

    function __construct($url, $level, $parent_url, $id=null)
    {
        $this->url = $url;
        $this->level = $level;
        $this->parent_url = $parent_url;
        if($id !== null) $this->id = $id;
    }

    function __destruct()
    {
        $this->url = '';
        $this->level = 0;
        $this->parent_url = '';
        $this->id = 0;
        unset($this->url);
        unset($this->level);
        unset($this->parent_url);
        unset($this->id);
    }
}


class Page
{
    private $ar_property = array();

    public $id = 0;
    public $url = '';
    public $page_code = '';
    public $http_code = 0;
    public $content_type = '';
    public $redirect_url = '';
    public $parent_url = '';
    public $load_count = 0;
    public $load_timeout = 0;
    public $title = 0;
    public $keywords = 0;
    public $description = 0;
    public $level_links = 0;
    public $connect_time = 0;
    public $total_time = 0;
    public $page_size = 0;
    public $page_speed = 0;
    public $links = 0;
    public $images = 0;
    public $content = 0;
    public $email   = 0;
    public $scan    = 0;
    public $exist   = 0;
    public $create_page = 0;
    public $update_page = 0;
    public $delete_page = 0;
    public $date_change_page = '';

    public $obj_title = null;
    public $obj_keywords = null;
    public $obj_description = null;
    public $obj_links = null;
    public $obj_images = null;
    public $obj_content = null;
    public $obj_email = null;

    public $change_page_code = 0;
    public $change_http_code = 0;
    public $change_content_type = 0;
    public $change_redirect_url = 0;
    public $change_parent_url = 0;
    public $change_load_count = 0;
    public $change_load_timeout = 0;
    public $change_title = 0;
    public $change_keywords = 0;
    public $change_description = 0;
    public $change_level_links = 0;
    public $change_total_time = 0;
    public $change_page_size = 0;
    public $change_page_speed = 0;
    public $change_links = 0;
    public $change_images = 0;
    public $change_content = 0;
    public $change_email = 0;
    public $change_exist = 0;

    public $reserve_http_code = 0;
    public $reserve_content_type = '';

    public function __set($name, $value)
    {
        $this->ar_property[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->ar_property))
        {
            return $this->ar_property[$name];
        }
        else
        {
            return null;
        }
    }

    public function __isset($name)
    {
        return isset($this->ar_property[$name]);
    }

    public function __unset($name)
    {
        if (array_key_exists($name, $this->ar_property))
        {
            unset($this->ar_property[$name]);
        }
    }

    function __destruct()
    {
        if(count($this->ar_property))
        {
            foreach($this->ar_property as $key=>$val)
            {
                unset($this->ar_property[$key]);
            }
            unset($this->ar_property);
        }

        unset($this->id);
        unset($this->url);
        unset($this->page_code);
        unset($this->http_code);
        unset($this->content_type);
        unset($this->redirect_url);
        unset($this->parent_url);
        unset($this->load_count);
        unset($this->load_timeout);
        unset($this->title);
        unset($this->keywords);
        unset($this->description);
        unset($this->level_links);
        unset($this->connect_time);
        unset($this->total_time);
        unset($this->page_size);
        unset($this->page_speed);
        unset($this->links);
        unset($this->images);
        unset($this->content);
        unset($this->email);
        unset($this->scan);
        unset($this->exist);
        unset($this->create_page);
        unset($this->update_page);
        unset($this->delete_page);
        unset($this->date_change_page);

        unset($this->obj_title);
        unset($this->obj_keywords);
        unset($this->obj_description);
        unset($this->obj_links);
        unset($this->obj_images);
        unset($this->obj_content);
        unset($this->obj_email);

        unset($this->change_page_code);
        unset($this->change_http_code);
        unset($this->change_content_type);
        unset($this->change_redirect_url);
        unset($this->change_parent_url);
        unset($this->change_load_count);
        unset($this->change_load_timeout);
        unset($this->change_title);
        unset($this->change_keywords);
        unset($this->change_description);
        unset($this->change_level_links);
        unset($this->change_total_time);
        unset($this->change_page_size);
        unset($this->change_page_speed);
        unset($this->change_links);
        unset($this->change_images);
        unset($this->change_content);
        unset($this->change_email);
        unset($this->change_exist);

        unset($this->reserve_http_code);
        unset($this->reserve_content_type);
    }
}

//base class
class TemplateClass
{
    public $id = 0;
    public $info = '';
    public $count_words = 0;
    public $count_symbols = 0;

    public function Compare( & $object )
    {
        if($this->info == $object->info) return true;
        else return false;
    }

    public function __destruct()
    {
        unset($this->id);
        unset($this->info);
        unset($this->count_words);
        unset($this->count_symbols);
    }
}

//<h1>...<h6>, <b>, <strong>
class TagPage extends TemplateClass
{
    public $page_id = 0;

    public function CompareObjectsInArray( & $ar_tp )
    {
        for($i=0; $i < count($ar_tp); $i++)
        {
            if( ($ar_tp[$i]->info == $this->info) )
            {
                return true;
            }
        }
        return false;
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->page_id);
    }
}

//<title> || <meta name='title'>
class TitlePage extends TemplateClass{}

//<meta name='keywords'>
class KeywordsPage extends TemplateClass{}

//<meta name='description'>
class DescriptionPage extends TemplateClass{}

class LinkPage
{
    public $id = 0;
    public $page_id = 0;
    public $internal_link = 0;
    public $href = '';
    public $anchor = '';

    public function CompareObjectsInArray( & $ar_lp )
    {
        for($i=0; $i < count($ar_lp); $i++)
        {
            if( ($ar_lp[$i]->href == $this->href) && ($ar_lp[$i]->anchor == $this->anchor) ) //&& ($ar_lp[$i]->internal_link == $this->internal_link)
            {
                return true;
            }
        }
        return false;
    }

    function __destruct()
    {
        unset($this->id);
        unset($this->page_id);
        unset($this->internal_link);
        unset($this->href);
        unset($this->anchor);
    }
}

class ImagePage
{
    public $id = 0;
    public $page_id = 0;
    public $src = '';
    public $title = '';
    public $count_word_title = 0;
    public $alt = '';
    public $count_word_alt = 0;
    public $width = 0;
    public $height = 0;
    public $size = 0;

    public function CompareObjectsInArray( & $ar_ip )
    {
        for($i=0; $i < count($ar_ip); $i++)
        {
            if( ($ar_ip[$i]->src == $this->src) && ($ar_ip[$i]->title == $this->title) && ($ar_ip[$i]->alt == $this->alt) && ($ar_ip[$i]->width == $this->width) && ($ar_ip[$i]->height == $this->height) )
            {
                return true;
            }
        }
        return false;
    }

    function __destruct()
    {
        unset($this->id);
        unset($this->page_id);
        unset($this->src);
        unset($this->title);
        unset($this->count_word_title);
        unset($this->alt);
        unset($this->count_word_alt);
        unset($this->width);
        unset($this->height);
        unset($this->size);
    }
}

class ContentPage
{
    public $id = 0;
    public $original_text = '';
    public $text_words = null;
    public $count_words = 0;
    public $count_symbols = 0;

    function Compare( & $object )
    {
        if($this->original_text == $object->original_text) return true;
        else return false;
    }

    function __destruct()
    {
        unset($this->id);
        unset($this->original_text);
        unset($this->text_words);
        unset($this->count_words);
        unset($this->count_symbols);
    }
}

class EmailPage
{
    public $id = 0;
    public $id_page = 0;
    public $url = '';
    public $domain = '';
    public $title = '';
    public $keywords = '';
    public $description = '';
    public $email = '';
    public $hash  = '';
    public $not_send = 0;

    function __destruct()
    {
        unset($this->id);
        unset($this->id_page);
        unset($this->url);
        unset($this->domain);
        unset($this->title);
        unset($this->keywords);
        unset($this->description);
        unset($this->email);
        unset($this->hash);
        unset($this->not_send);
    }
}
?>