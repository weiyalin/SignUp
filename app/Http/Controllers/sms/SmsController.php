<?php

	namespace App\Http\Controllers\sms;

	use Aliyun\Core\Config;
	use Aliyun\Core\Profile\DefaultProfile;
	use Aliyun\Core\DefaultAcsClient;
	use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
	use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

	use App\Http\Controllers\Controller;


	// 加载区域结点配置
	Config::load();

	class SmsController extends Controller 
	{

		/**
		 * 构造器
		 * @param string $accessKeyId    必填，AccessKeyId
		 * @param string $accessKeySecret 必填，AccessKeySecret
		 */
		public function __construct($accessKeyId="LTAIVpIapr2gZTYh",$accessKeySecret="IV5bwFf3N9lJunnX3ZKKyg09rlBCyu")
		{

			// 短信API产品名
 			$product = "Dysmsapi";

			// 短信API产品域名
 			$domain = "dysmsapi.aliyuncs.com";

 			 // 暂时不支持多Region
 			$region = "cn-hangzhou";

 			 // 服务结点
 			$endPointName = "cn-hangzhou";

 			 // 初始化用户Profile实例
 			$profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

 			 // 增加服务结点
 			DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

 			 // 初始化AcsClient用于发起请求
 			$this->acsClient = new DefaultAcsClient($profile);

		}


		/**
		 *  发送短信范例
		 * @param  [type] $phoneNumbers 必填, 短信接收号码
		 * @param  string $signName    必填, 短信签名，应严格"签名名称"填写，
		 * @param  string $templateCode  必填, 短信模板Code，应严格按"模板CODE"填写,
		 * @param  [type] $outId        选填, 假如模板中存在变量需要替换则为必填项
		 * @return [type]               [description]
		 */
		public function sendSms($phoneNumbers,$signName="三月软件",$templateCode="SMS_98795004",$outId=null)
		{

			 // 初始化SendSmsRequest实例用于设置发送短信的参数
			$request = new SendSmsRequest;

			 // 必填，设置雉短信接收号码
			$request->setPhoneNumbers($phoneNumbers);

			 // 必填，设置签名名称
			$request->setSignName($signName);

			// 必填，设置模板CODE
			$request->setTemplateCode($templateCode);

			$num = rand(100000,999999);

			 // 可选，设置模板参数
			$request->setTemplateParam(json_encode(
				Array(
					"code" => "$num"
				)
			));

			  // 可选，设置流水号
			if($outId) {
				$request->setOutId($outId);
			}

			 // 发起访问请求
			$acsResponse = $this->acsClient->getAcsResponse($request);


		}

		/**
		 * 查询短信发送情况范例
		 * @param  [type]  $phoneNumbers 必填, 短信接收号码
		 * @param  [type]  $sendDate     必填，短信发送日期，格式Ymd，支持近30天记录查询 
		 * @param  integer $pageSize   必填，分页大小
		 * @param  integer $currentPage  必填，当前页码
		 * @param  [type]  $bizId        选填，短信发送流水号
		 * @return [type]                [description]
		 */
		public function queryDetails($phoneNumbers,$sendDate,$pageSize=10,$currentPage=1,$bizId=null)
		{

			 // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
			$request = new QuerySendDetailsRequest();

			$request->setPhoneNumber($phoneNumbers);

			$request->setSendDate($sendDate);

			$request->setPageSize($pageSize);

			$request->setCurrentPage($currentPage);

			if($bizId) {
				$request->setBizId($bizId);
			}

			$acsResponse = $this->acsClient->getAcsResponse($request);

		}

	}

