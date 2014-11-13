<?php
class ListLink
{
    protected $ar_link = array();//массив обьектов Link
    protected $count_link = 0;//количество обьектов в списке ListLink

    function __construct(){}

    function GetArrURL()
    {
        if($this->count_link > 0)
        {
            $tmp_ar_url = array();
            for($i=0; $i < $this->count_link; $i++)
            {
                $tmp_ar_url[] = $this->ar_link[$i]->url;
            }
            if(count($tmp_ar_url))
                return $tmp_ar_url;
            else
                return false;
        }
        else
            return false;
    }

    function GetCount()
    {
        return $this->count_link;
    }

    //возвращает массив обьектов или false
    function GetArrObject()
    {
        if($this->count_link > 0)
            return $this->ar_link;
        else
            return false;
    }

    //добавляем елемент в список
    //return mixed
    function AddLink( $link )
    {
        if ( $link instanceof Link )
        {
            $this->ar_link[$this->count_link++] = $link;
            return $this->count_link;
        }
        else
            return false;
    }

    //проверяем содержит ли список обьект с указанным Index в списке
    //return bool
    function ListExistIndex( $index )
    {
        if( is_int($index) )
        {
            for($i=0; $i < $this->count_link; $i++)
            {
                if($i == $index) return true;
            }
        }
        return false;
    }

    //проверяем содержит ли список обьект с указанным ID
    //return bool
    function ListExistId( $id )
    {
        if( is_int($id) )
        {
            for($i=0; $i < $this->count_link; $i++)
            {
                if($this->ar_link[$i]->id == $id) return true;
            }
        }
        return false;
    }

    //проверяем содержит ли список обьект с указанным URL
    //return bool
    function ListExistURL( $url )
    {
        if( is_string($url) )
        {
            for($i=0; $i < $this->count_link; $i++)
            {
                if($this->ar_link[$i]->url == $url) return true;
            }
        }
        return false;
    }

    //возвращаем елемент по Index в списке
    //return mixed
    function SearchByIndex( $index )
    {
        if( is_int($index) )
        {
            for($i=0; $i < $this->count_link; $i++)
            {
                if($i == $index) return $this->ar_link[$i];
            }
        }
        return false;
    }

    //возвращаем елемент по ID
    //return mixed
    function SearchById( $id )
    {
        if(is_int($id))
        {
            for($i=0; $i < $this->count_link; $i++)
            {
                if($this->ar_link[$i]->id == $id)
                    return $this->ar_link[$i];
            }
        }
        return false;
    }

    //возвращаем елемент по URL
    //return mixed
    function SearchByUrl( $url )
    {
        if(is_string($url))
        {
            for($i=0; $i < $this->count_link; $i++)
            {
                if($this->ar_link[$i]->url == $url)
                    return $this->ar_link[$i];
            }
        }
        return false;
    }

    function Clear()
    {
        $this->ar_link = array();
        $this->count_link = 0;
    }

    function __destruct()
    {
        $this->Clear();
        if(isset($this->ar_link))
            unset($this->ar_link);
        if(isset($this->count_link))
            unset($this->count_link);
    }
}
?>