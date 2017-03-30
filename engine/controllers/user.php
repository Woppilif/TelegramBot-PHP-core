<?php
class User
{
	private $chat_id;
	private $user_id;
	private $user_name;
	private $user_balance;
	private $user_reg_date;
	private $user_language;
	private $user_currency;
	private $account_id;
	public function __construct($chat_id)
	{
		if(isset($chat_id))
		{
			$users =  R::getAll( 'SELECT * FROM users WHERE chat_id = :chat_id',[':chat_id' => $chat_id ]); 
			if($users)
			{
				$this->user_id = $users[0]['id'];
				$this->user_name = $users[0]['name'];
				$this->user_balance = $users[0]['balance'];
				$this->user_reg_date = $users[0]['reg_date'];

				$this->user_language = $users[0]['language'];
				$this->user_currency = $users[0]['currency'];
				$this->account_id = $users[0]['account_id'];
				$this->chat_id = $chat_id;
				$this->LastUpdate();
			}
			else
			{

				/**/
				$accounts = R::dispense( 'accounts' );
				$accounts->chat_id = $chat_id;
				$accounts->account_name = 'default';
				$accounts->account_balance = 0.00;
				$accounts->account_currency = '₽';
				$acc_id = R::store( $accounts );
				/**/
				$current_time = time() + 10800;
				$users = R::dispense( 'users' );
				$users->chat_id = $chat_id;
				$users->name = 'Пользователь';
				$users->balance = 0.00;
	
				$users->reg_date = $current_time;
				$users->language = 'ru';
				$users->currency = '₽';
				$users->account_id = $acc_id;
				$users->allow_messages = 1;
				$id = R::store( $users );

				$this->user_id = $id;
				$this->user_name = 'Пользователь';
				$this->user_balance = 0.00;
				$this->user_reg_date = $current_time;
				$this->user_language = 'ru';
				$this->user_currency = '₽';
				$this->account_id = $acc_id;

			}
			


		}
	}

	public function getId()
	{
		return $this ->user_id;
	}
	public function GetUserAccountId()
	{
		return $this ->account_id;
	}
	public function SetUserAccountId($name)
	{
		
		//$account  = R::exec('SELECT id FROM accounts WHERE ',[ $name ,$this->chat_id ]);
		$account  = R::findOne( 'accounts', ' account_name=? AND chat_id = ?', [ $name ,$this->chat_id ] );
		
		if($account)
		{
			$user = R::load('users', $this->user_id);
			$user->account_id = $account['id'];
			R::store($user);
			return true;
		}
		else { return false; }
		
	}
	public function getName()
	{
		return $this ->user_name;
	}
	public function LastUpdate()
	{
		$current_time = time() + 10800;
		$user = R::load('users', $this->user_id);
		$user->last_update = $current_time;
		R::store($user);
	}
	public function setName($message)
	{
		$user = R::load('users', $this->user_id);
		$user->name = $message;
		R::store($user);
	}
	public function getBalance()
	{
		return $this ->user_balance;
	}
	public function setBalance($balance)
	{
		$user = R::load('users', $this->user_id);
		$user->balance = $balance;
		R::store($user);
	}
	public function upBalance($balance)
	{
		$this->user_balance = (float)$this->user_balance + $balance;
		$user = R::load('users', $this->user_id);
		$user->balance = $this->user_balance;
		R::store($user);
	}
	public function downBalance($balance)
	{
		$this->user_balance = (float)$this->user_balance - $balance;
		$user = R::load('users', $this->user_id);
		$user->balance = $this->user_balance;
		R::store($user);
	}
	public function getRegDate()
	{
		return $this ->user_reg_date;
	}
	
	public function getLanguage()
	{
		return $this->user_language;
	}
	public function setLanguage($lang)
	{
		
		switch ($lang)
		{
			case 'Русский':
				$lang = 'ru';
			break;
			case 'English':
				$lang = 'en';
			break;
			case 'Deutsch':
				$lang = 'de';
			break;
			default: $lang = 'ru';
		}
		$user = R::load('users', $this->user_id);
		$user->language = $lang;
		R::store($user);
	}
	public function getCurrency()
	{
		return $this->user_currency;
	}
	public function setCurrency($curr)
	{
		switch ($curr)
		{
			case '₽':
				$curr = '₽';
			break;
			case '$':
				$curr = '$';
			break;
			case '€':
				$curr = '€';
			break;
			case '£':
				$curr = '£';
			break;
			default: $curr = '₽';
		}
		$user = R::load('users', $this->user_id);
		$user->currency = $curr;
		R::store($user);
	}
	/*public function Hello($chat_id)
	{
		$this->apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'HALLO'));
	}*/
}
