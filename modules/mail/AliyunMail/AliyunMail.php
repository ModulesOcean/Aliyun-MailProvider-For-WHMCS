<?php
namespace WHMCS\Module\Mail;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use WHMCS\Exception\Mail\SendFailure;
use WHMCS\Exception\Module\InvalidConfiguration;
use WHMCS\Mail\Message;
use WHMCS\Module\Contracts\SenderModuleInterface;
use WHMCS\Module\MailSender\DescriptionTrait;
use WHMCS\Database\Capsule;

use AlibabaCloud\SDK\Dm\V20151123\Dm;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dm\V20151123\Models\SingleSendMailRequest;

class AliyunMail implements SenderModuleInterface {
	use DescriptionTrait;
	
	/**
	 * Constructor
	 *
	 * Any instance of a mail module should have the display name at the ready.
	 * Therefore it is recommend to ensure these
	 * values are set during object instantiation.
	 *
	 * @see \WHMCS\Module\MailSender\DescriptionTrait::setDisplayName()
	 */
	public function __construct() {
		$this->setDisplayName('Aliyun Mail Push');
	}

	/**
	 * An array of configuration options for the Mail Provider.
	 *
	 * @return array
	 */
	public function settings() {
		return [
			'accessKeyId' => [
				'FriendlyName' => 'AccessKey ID',
				'Type' => 'text',
				'Description' => '阿里云 accessKeyId.',
			],
			'accessKeySecret' => [
				'FriendlyName' => 'AccessKey Secret',
				'Type' => 'text',
				'Description' => '阿里云 accessKeySecret.',
			],
			'addressType' => [
				'FriendlyName' => '发信地址类型',
				'Type' => 'dropdown',
				'Options' => [
					'0' => '随机地址',
					'1' => '系统地址',
				],
			],
		];
	}
	
	/**
	 * 使用AK&SK初始化账号Client
	 * @param string $accessKeyId
	 * @param string $accessKeySecret
	 * @return Dm Client
	 */
	public static function createClient($accessKeyId, $accessKeySecret) {
		$config = new Config([
			// 您的AccessKey ID
			"accessKeyId" => $accessKeyId,
			// 您的AccessKey Secret
			"accessKeySecret" => $accessKeySecret
		]);
		// 访问的域名
		$config->endpoint = "dm.aliyuncs.com";
		return new Dm($config);
	}	

	/**
	 * @param string[] $args
	 * @return void
	 */
	public static function sendMail($args = [], $accessKeyId, $accessKeySecret) {
		$client = self::createClient($accessKeyId, $accessKeySecret);
		$args['replyToAddress'] = "false";
		$singleSendMailRequest = new SingleSendMailRequest($args);
		$singleSendMail = $client->singleSendMail($singleSendMailRequest);
		return $singleSendMail;
	}
	/**
	 * Test the connection to the Mail Provider.
	 *
	 * @param array $params Module configuration parameters.
	 * @throws InvalidConfiguration On error, InvalidConfiguration will be thrown.
	 */
	public function testConnection(array $params) {
		// Get Admin ID
		$adminid = $_SESSION['adminid'];
		$adminemail = Capsule::table('tbladmins')->where('id', $adminid)->value('email');
				
		$accessKeyId = $params['accessKeyId'];
		$accessKeySecret = $params['accessKeySecret'];
		$args['addressType'] = $params['addressType'];
		$args['accountName'] = $GLOBALS['CONFIG']['Email'];
		$args['fromAlias'] = $GLOBALS['CONFIG']['CompanyName'];
		$args['toAddress'] = $adminemail;
		$args['subject'] = 'Postal Email Server Connection Test.';
		$args['htmlBody'] = '<p>When you receive this email, it means that you can connect to this postal server.</p>';

		$message = self::sendMail($args, $accessKeyId, $accessKeySecret);
		
		return $message;
	}

	/**
	 * Send an email.
	 *
	 * @param array $params Module configuration parameters.
	 * @param Message $message The Message object containing details specific to the message.
	 *
	 * @return void
	 * @throws SendFailure
	 */
	public function send(array $params, Message $message) {
		// Get parameters
		$subject = $message->getSubject();
		$body = $message->getBody();
		$plainTextBody = $message->getPlainText();

		$replyTo = '';
		if ($message->getReplyTo()) {
			$replyTo = $message->getReplyTo();
		}		
				
		$accessKeyId = $params['accessKeyId'];
		$accessKeySecret = $params['accessKeySecret'];
		
		$args['addressType'] = $params['addressType'];
		$args['accountName'] = $message->getFromEmail();
		$args['fromAlias'] = $message->getFromName();

		// Retrieve recipients.
		foreach ($message->getRecipients('to') as $to) {			
			$args['toAddress'] = $to[0];
		}
		
		$args['subject'] = $message->getSubject();
		$args['htmlBody'] = $message->getBody();
		$args['textBody'] = $message->getPlainText();

		$message = self::sendMail($args, $accessKeyId, $accessKeySecret);

		// // Set attachments
		// foreach ($message->getAttachments() as $attachment) {
		// 	if (array_key_exists('data', $attachment)) {
		// 		$sendMessage->attach($attachment['filename'], 'text/plain', $attachment['data']);
		// 	} else {
		// 		$sendMessage->attach($attachment['filename'], 'text/plain', $attachment['filepath']);
		// 	}
		// }

		return $message;
	}
}