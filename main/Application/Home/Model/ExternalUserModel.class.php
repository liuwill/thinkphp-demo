<?php

namespace Home\Model;
use Think\Model\ViewModel;

class ExternalUserModel extends ViewModel  {
    public $viewFields = array(
        'usertb'=>array('uid','username','email'),
        'userinfotb'=>array('mobile','_on'=>'usertb.uid=userinfotb.uid'),
    );
    protected $connection = 'mysql://locale:123456@localhost:3306/externalserver#utf8';//'EX_LOCALE_DB_CONFIG';
}
