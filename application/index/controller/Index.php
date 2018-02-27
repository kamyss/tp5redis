<?php
namespace app\index\controller;
use think\Db;
use think\cache\driver\Redis;

class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) 2018新年快乐</h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
    //用来测试thinkphp5 redis的具体使用
    public function redis(){
        // echo phpinfo();
        // die;
        $config=$this->getConfig();
        $Redis=new Redis($config);
        $Redis->set("test","test");
        $myArray=['first','second','third'];
        $Redis->set('myData',$myArray);
        // $Redis->lpush('myData','first');
        // $Redis->lpush('myData','second');
        // $Redis->lpush('myData','third');
        $oldRedis=$Redis->getOldRedis();
        //从左边插入数据
        // dump($oldRedis->lpush('test2','123'));
        // die;
        //弹出所需要的数据
        dump($oldRedis->lpop('test2'));
        die;
        dump($Redis->get('myData'));
        die;

        echo $Redis->get("test");
    }
    //php redis的高级用法hash的使用
    public function hash(){
        $config=$this->getConfig();
        $Redis=new Redis($config);
        $oldRedis=$Redis->getOldRedis();
        //给hash表添加键值对
        $oldRedis->hset('hash','lang1',1);
        $oldRedis->hset('hash','lang2','java');
        $oldRedis->hset('hash','lang3','mysql');
        //取出整个hash表
        // dump($oldRedis->hgetall('hash'));
        // die;
        //求hash表的长度
        // echo $oldRedis->hlen('hash');
        // die;
        //删除hash表里面的某个索引
        // $oldRedis->hdel('hash','lang3');
        // dump($oldRedis->hgetall('hash'));
        //只返回hash表的值
        // dump($oldRedis->hvals('hash'));
        //判断hash表的某个元素是否有值
        // echo $oldRedis->hExists('hash','lang1');
        //给hash表里面的数值加值
        // echo $oldRedis->hincrby('hash','lang1',3);
        //批量向hash表添加数据
        $oldRedis->hMset('hash',['lang4'=>'python','lang5'=>'golang']);
        // dump($oldRedis->hgetall('hash'));
        //批量获取hash表的数据
        dump($oldRedis->hMget('hash',['lang1','lang2']));

    }
    //获取redisconfig
    public function getConfig(){
        $config=[
            'host'=>'127.0.0.1',
            'port'=>6379,
            'password'=>'',
            'select'=>0,
            'timeout'=>0,
            'expire'=>0,
            'persistent'=>false,
            'prefix'=>'',
        ];
        return $config;
    }
}
