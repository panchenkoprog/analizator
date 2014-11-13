
<?php 
    if(!$this->session)
    {
       include_once('view_login.php'); 
       include_once('view_register.php');
    }
?>
<div class="header">
    <div class="fon">
        <div class="top-login"></div>
        <div class="container">
            <?php if($this->session)    
            {
                include_once('view_user_window.php');
                include_once('view_logo.php');
                ?>
            </div>
        </div>
    </div>
                <ul class="top-menu">
                <?php
                for( $i=0; $i < count($this->ar_title_session); $i++)
                { ?>

                    <li>
                        <a <?php if($i == $this->num_menu) echo $this->class; ?>href="<?php echo $this->url . $i; ?>"><?php echo $this->ar_title_session[$i]; ?></a>
                    </li>

                <?php } ?>
               </ul>
                
            <?php } 
                else {?>
                <a href="#" class="but-reg" id="sub-reg">Регистрация</a>
                <a href="#" class="but-log" id="sub-login">Вход</a>
                <?php
                    include_once('view_logo.php');
                ?>
                </div>
            </div>
        </div>
                <?php } ?>


