<?php 
class SaveHoliday extends Action
{
	public $requiredPosts = array('name', 'day', 'month');
	public $requiredGets  = array();
	
	public function execute( )
	{ 	
		$db = DbHolidays::get(Post::id());
		$db->holidayId       = Post::id();
		$db->name            = $this->translate_existing_holiday(Post::name());
		$db->day             = Post::day();
		$db->month           = Post::month();
		$db->easterDeviation = Post::easterDeviation();
		$save          		 = $db->save();
	
		if($save) {
			Message::add_success('~SaveSettingsSuccess~');
		} else {
			Message::add_failure('~SaveSettingsFailure~');
		}
	}	

	private static function translate_existing_holiday($holiday) {
		$eggHolidays = self::egg_holidays();

		if($eggHolidays[$holiday]['name']) {
			return Text::get($eggHolidays[$holiday]['name']);
		} 
		return $holiday;
	}

	private static function egg_holidays($res = array()) 
    {
    	$file = ROOT.'/config/holidays.egg';
    	if(!file_exists($file)) return false;
    	
    	foreach (EggFile::get_array($file) as $name => $item) 	
    		if(isset($item['_pars'])) $res[$name] = array('name'=>$name, 'day'=>$item['_pars'][0], 'month'=>$item['_pars'][1], 'easterDeviation'=>$item['_pars'][2]);
    	
    	return $res;
    }
	
	public function authorized()
	{
		return User::is_logged_in();
	}
}
?>