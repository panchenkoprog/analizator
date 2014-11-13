<div class="user-block">
    <div class="username"><?php if($this->session){
            echo 'login:' . $_SESSION['login'];
        }?></div>
    <div class="useremail"><?php if($this->session){
            echo "<span>email:</span>" . '<span class="email">' . $_SESSION['email'] . "</span>";
        }?></div>
    <div class="userbalance"><?php if($this->session){
             echo "<span>balance:</span>" . '<span class="balance">' . $_SESSION['balance'] ."</span>";
        }?></div>
    <div class="userbalance"><?php if($this->session){
            echo "<a href='".$this->url.(count($this->ar_title_session)-(1+$this->admin))."'>пополнить баланс</a>";
        }?></div>
    <?php if($this->session)
    { ?>
        <div class="but-session"><a href="<?php echo $this->url . 'exit'; ?>">выход</a></div>
    <?php
    } ?>
</div>