<?php
namespace app\index\controller;
use think\Db;
use think\cache\driver\Redis;
use qcloud\cos\Qcloud;
use OSS\OssClient;

class Index
{
    public function index()
    {
        return view('index');
     // $this->display('index');
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
    //文件上传
    public function upload2(){
          // 获取表单上传文件 例如上传了001.jpg
          $file = request()->file('file');
        
          // 移动到框架应用根目录/public/uploads/ 目录下
          if($file){
              $info = $file->move('uploads');
              dump($info);
              die;
             
              if($info){
                  // 成功上传后 获取上传信息
                  // 输出 jpg
               //   echo $info->getExtension();
                  // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                 // echo $info->getSaveName();
                  // 输出 42a79759f284b767dfcb2a0197904287.jpg
                 // echo $info->getFilename(); 
                 echo json_encode(array('errcode'=>0,'filePath'=>'uploads/'.$info->getSaveName()));
                 exit;
              }else{
                  // 上传失败获取错误信息
                  echo $file->getError();
              }
          }
    }
    //对腾讯云的oss进行测试
    public function upload(){
        $qclound=new Qcloud();
        $cosClient=$qclound->getCosClient();
        #listBuckets
        try {
            $result = $cosClient->listBuckets();
          //  print_r($result);
        } catch (\Exception $e) {
            echo "$e\n";
        }
        #createBucket
        try {
            $result = $cosClient->createBucket(array('Bucket' => 'picspace-1251731674'));
            //print_r($result);
            } catch (\Exception $e) {
        //    echo "$e\n";
        }
        $body=file_get_contents($_FILES['file']['tmp_name']);
     //   $folder=$cosClient->createFolder('picspace',"/sub3/");
         #uploadbigfile
         try {
            $result = $cosClient->upload(
                $bucket='picspace-1251731674',
                $key = '/test/'.$_FILES['file']['name'],
                $body = $body,
                $options = array(
                    "ACL"=>'private',
                    'CacheControl' => 'private',
                    'ServerSideEncryption' => 'AES256'));
            print_r($result);
        } catch (\Exception $e) {
           // echo "$e\n";
        }

    }
    //对阿里云的Oss进行操作
    public function alupload(){
        $ossClient=new OssClient('LTAIMEYgzFiSJjFO','7JIVLmLdT21V79NZl6sC2ZGo7jI31P','oss-cn-shenzhen.aliyuncs.com');
        $content=file_get_contents($_FILES['file']['tmp_name']);
        $bucket='picspace2';
        try {
            $result=$ossClient->putObject($bucket, 'hhj2/'.$_FILES['file']['name'], $content);
            dump($result);
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }
}
