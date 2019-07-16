<?php
/*
phpGrace.com 轻快的实力派！ 
*/
class indexController extends grace{
	
	//__init 函数会在控制器被创建时自动运行用于初始化工作，如果您要使用它，请按照以下格式编写代码即可：
	
	public function __init(){
		parent::__init();
		//your code ......
	}
	
	
	public function index(){
		exit;
	}

	public function buildyue(){
		$_POST['endtime'] = strtotime($_POST['bd'].' '.$_POST['bt'].':00');
		if(time()>=$_POST['endtime'])
			$this->json('','-1','运动时间过期');
		$yueid = db('yueparty')->add($_POST);
		if($yueid){
			$this->joinInc($yueid,$_POST['uid']);
			$this->json($yueid);
		}else{
			$this->json('','-1','创建失败，请稍后再试');
		}
	}

	public function codeToSession(){
		$appid = 'wx41245dd430035de4';
		$secret = '07603983645cde794fd57c44db9d2975';
		$url =  "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid.
        "&secret=".$secret."&js_code=".$_GET['code']."&grant_type=authorization_code";
		$curl = new phpGrace\tools\curl();
		$res = $curl->get($url);
		$this->json($res);
	}

	public function login(){
		if(isset($_POST['openid']) && $_POST['openid']){
			$member = db('user')->where('openid=?',array($_POST['openid']))->fetch();
			// 用户未注册
			if(empty($member)){
				$_POST['nickName'] = urlencode($_POST['nickName']);
				$member['id'] = db('user')->add($_POST);
			}
			
			if(empty($member['id'] )){
				$this->json('','-1','注册失败，请返回重试');
			}
			// 如果用户已经注册 member 变量中已经保存用户信息
			// 返回用户信息
			$this->json($member);
		}else{
			$this->json('','-1','请稍等');
		}
	}
	public function myyue(){
		$yueids = db('yuejoin')->where('uid=?',array($_GET['uid']))->fetchAll('yueid');
		if(!empty($yueids)){
			$yueid = "(";
			foreach ($yueids as $value) {
				$yueid .= $value['yueid'].',';
			}
			$yueid = rtrim($yueid,',').")";
			$data = db('yueparty')->where('id in '.$yueid.' and endtime>?',array(time()))->fetchAll('id,uid,title,place,bd,bt,joinnum,num,moshi');
			foreach ($data as $key => $value) {
				if($value['uid'] == $_GET['uid']){
					$data[$key]['cancancel'] = '1';
				}else{
					$data[$key]['cancancel'] = '0';
				}
			}
			$this->json($data,'1000');
		}else{
			$this->json([],'1001');
		}
	}

	public function delyue(){
		db('yueparty')->where('id=?',array($_GET['yueid']))->delete();
		db('yuejoin')->where('yueid=?',array($_GET['yueid']))->delete();
		$this->json('');
	}

	public function yueinfo(){
		$yueinfo = db('yueparty')->where('id=?',array($_GET['yueid']))->fetch();
		$data['longitude'] = $yueinfo['longitude'];
		$data['latitude'] = $yueinfo['latitude'];
		$data['markers'][0]['id'] =  $yueinfo['id'];
		$data['markers'][0]['latitude'] =  $yueinfo['latitude'];
		$data['markers'][0]['longitude'] =  $yueinfo['longitude'];
		$data['markers'][0]['width'] =  50;
		$data['markers'][0]['height'] =  50;
		$data['markers'][0]['callout']['content'] =  $yueinfo['place'];
		$data['yueinfo'] = $yueinfo;
		$data['join'] = db('yuejoin')->join('as a left join user as b on a.uid = b.id')
		->where('a.yueid=?',array($_GET['yueid']))
		->fetchAll('a.create_time, b.nickName,b.avatarUrl');
		foreach ($data['join'] as $key => $value) {
			$data['join'][$key]['nickName'] = urldecode($value['nickName']);
		}
		$this->json($data,'1000');
	}

	public function joinyue(){
		//已经加入
		$hasjoin = db('yuejoin')->where('yueid=? and uid=?',array($_POST['yueid'],$_POST['uid']))->count();
		if($hasjoin>0)
			$this->json('','-1','已经加入');
		//人满
		$yueinfo = db('yueparty')->where('id=?',array($_POST['yueid']))->fetch('num,joinnum');
		if($yueinfo['num'] == $yueinfo['joinnum'])
			$this->json('','-1','人数已满');
		$this->joinInc($_POST['yueid'],$_POST['uid']);
			$this->json('');
	}

	private function joinInc($yueid,$uid){
		db('yuejoin')->add(array('yueid'=>$yueid,'uid'=>$uid));
		db('yueparty')->where('id=?',array($yueid))->field('joinnum', 1);
	}
}