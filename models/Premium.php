<?php
	namespace models;
	require_once 'vendor/autoload.php';
	use MercadoPago;
	use models\Database;
	use models\User;
	use PDO;

	class Premium extends MainModel
	{
		private $access_token = 'APP_USR-1453999510390458-081317-6c1d59bfe543238612aefe8ee701232f-622016938';// TEST-5423471742987996-080712-fdc23018a167bc52bd4b26c856f07d9c-622016938
		private $maximum_price = 1000;
		private $minimum_price = 14.90;
		private $notification_url = PATH.'/mercadopagoipn?source_news=ipn';
		private $price = 39.90;

		function applyPremium($email) {
			$email = (string)$email;
			$pdo = Database::languages();
			$sql = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
			$sql->execute(array($email));
			$id = $sql->fetch(PDO::FETCH_ASSOC)['id'];

			$sql = $pdo->prepare("UPDATE users, premium SET premium.payments = premium.payments + 1, premium.premium = 1, premium.premium_date = ? WHERE users.id = ? LIMIT 1");
			$sql->execute(array(date('Y-m-d', strtotime('+365 days')), $id));

			$sql = $pdo->prepare("INSERT INTO my_notifications (id, user_id, title, content) VALUES (?,?,?,?)");
			global $lang;
			$sql->execute(array(null, $id, '['.date('Y-m-d').']'.$lang->get('premium_notification_title'), $lang->get('premium_notification_content')));
		}

		function checkPremium() {
			$this->checkLogin();

			if ($this->isPremium()) {
				if ($_SESSION['premium_date'] > date('Y-m-d')) {
					$data['premium'] = true;
					$this->successWithData($data);
				}
				else {
					$user = new User();
					$sql = Database::languages();
					$sql = $sql->prepare("UPDATE users, premium, settings SET premium.premium = ?, premium.premium_date = ?, settings.ads = ? WHERE users.id = ? LIMIT 1");
					$sql->execute(array(0, null, 1, $user->getId()));
					$_SESSION['premium'] = 0;
					$_SESSION['premium_date'] = null;
				}
			}

			$data['premium'] = false;
			$this->successWithData($data);
		}

		function getBuyLink() {
			$this->checkLogin();

			$user = new User();
			$sql = Database::languages();
			$sql = $sql->prepare("SELECT name, email FROM users WHERE id = ? LIMIT 1");
			$sql->execute(array($user->getId()));
			$user = $sql->fetch(PDO::FETCH_ASSOC);

			MercadoPago\SDK::setAccessToken($this->access_token);
			$item = new MercadoPago\Item();
			$payer = new MercadoPago\Payer();
			$preference = new MercadoPago\Preference();

			$item->quantity = 1;
			$payer->name = $user['name'];
			$payer->email = $user['email'];
			//$preference->payer = $payer;
			$preference->notification_url = $this->notification_url;

			if ($this->isPremium()) {
				$price = floatval($_GET['price']);
				if ($price < $this->minimum_price) {
					$price = $this->minimum_price;
				}
				else if ($price > $this->maximum_price) {
					$price = $this->maximum_price;
				}

				$item->id = 'renew_subscription';
				$item->title = 'Nihongo Idiomas - Renovação de assinatura';
				$item->unit_price = $price;
				$preference->items = array($item);
			}
			else {
				$item->id = 'buy_premium';
				$item->title = 'Nihongo Idiomas Premium';
				$item->unit_price = $this->price;
				$preference->items = array($item);
			}

			$preference->save();
			$data['url'] = $preference->init_point;
			$this->successWithData($data);
		}

		function getMinimumPrice() {
			$data['price'] = $this->minimum_price;
			$this->successWithData($data);
		}

		function getPremium() {
			$this->checkLogin();

			$user = new User();
			$sql = Database::languages();
			$sql = $sql->prepare("SELECT premium, premium_date FROM premium WHERE user_id = ? LIMIT 1");
			$sql->execute(array($user->getId()));
			$sql = $sql->fetch(PDO::FETCH_ASSOC);

			$_SESSION['premium'] = (int)$sql['premium'];
			$_SESSION['premium_date'] = $sql['premium_date'];
			$data['premium'] = $_SESSION['premium'];
			$data['date'] = $_SESSION['premium_date'];

			$this->successWithData($data);
		}

		function mercadoPagoIPN() {
	    switch($_GET['topic']) {
	      case 'merchant_order': {
	      	MercadoPago\SDK::setAccessToken($this->access_token);
	        $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET['id']);
	        break;
	      }
	      default: {
	      	$this->badRequest();
	      }
	    }

	    switch ($merchant_order->order_status) {
	    	case 'expired':
	    	case 'partially_reverted':
	    	case 'reverted':
	    	{
	    		$this->removePremium($merchant_order->payer->email);
	    		break;
	    	}

	    	case 'paid':
	    	case 'partially_paid':
	    	{
	    		$this->applyPremium($merchant_order->payer->email);
	    		break;
	    	}
	    }

	    $this->success();
		}

		function removePremium($email) {
			$email = (string)$email;
			$sql = Database::languages();
			$sql = $sql->prepare("UPDATE users, premium, settings SET premium.premium = 0, premium.premium_date = null, settings.ads = 1 WHERE users.email = ? LIMIT 1");
			$sql->execute(array($email));
		}
	}
?>