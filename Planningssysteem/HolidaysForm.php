<?php
class HolidaysForm extends PopupFormView {

    public static $renderDepth = 2;
    
    public function HolidaysForm_setup() 
    {
        $this->eggHolidays     = self::egg_holidays();
        $this->chosenHoliday   = $this->post('holiday') ? $this->post('holiday') : 0;
        $this->easterDeviation = 'NULL';

        /* If an existing holiday is chosen, set the corresponding data. */
        if(self::existing_holiday($this->chosenHoliday)) {
            $this->name = $this->eggHolidays[$this->chosenHoliday]['name'];
            
            //Check if it is an easter-dependent holiday (date and month differ per year).
            if($this->eggHolidays[$this->chosenHoliday]['easterDeviation'] != 'NULL') {
                //If it is Easter-dependent:
                $this->day   = 'NULL';
                $this->month = 'NULL';
                $this->easterDeviation = $this->eggHolidays[$this->chosenHoliday]['easterDeviation'];
            } else {
                //Set corresponding day and month of existing holiday.
                $this->day   = $this->eggHolidays[$this->chosenHoliday]['day'];
                $this->month = $this->eggHolidays[$this->chosenHoliday]['month'];
            }
        }

        if($this->holidayId) {
            $this->title  = '~'.$this->holiday->name.'~';
        } else {
            $this->title  = 'Feestdag toevoegen';
        }
        $this->action = '/settings/company/holidays/action:SaveHoliday';
    }

    public function HolidaysForm_run() 
    {   
        //Choose existing holiday.
        $selectHolidays = $this->list -> li() -> class('input-container')
                                      -> label('Feestdag') -> also
                                      -> select() -> name('holiday') -> selected($this->chosenHoliday) -> class('js-on-change-send-to-self');
        
        self::add_option($selectHolidays, 'Zelf aanmaken', 0);
        foreach($this->eggHolidays as $holiday) {
            self::add_option($selectHolidays, Text::get($holiday['name']), $holiday['name']);
        }


        //Add name input if no existing holiday is chosen.
        if(!self::existing_holiday($this->chosenHoliday)) {
            $this->add_input('~Name~', 'name', '', '', true);
        } else {
            //Add hidden input with name of existing holiday.
            $this->add_hidden($this->chosenHoliday, 'name');
        }
        
        $dateRow = $this->list -> li();

        //Select month.
        $selectMonth = $dateRow -> select() -> name('month') -> selected($this->month) -> class('datetime');
        for($i=1; $i<=12; $i++) {
            self::add_option($selectMonth, HandleDbData::format_month($i), $i);
        }
                                 
        //Select day.
        $selectDay = $dateRow -> label('~Date~') -> also 
                              -> select() -> name('day') -> selected($this->day) -> class('datetime');

        for($i=1; $i<=31; $i++) {
            self::add_option($selectDay, HandleDbData::format_leading_zero($i), $i);
        }

        //If an existing holiday is chosen, the day and month may not be customized.
        if(self::existing_holiday($this->chosenHoliday)) {
            $selectDay -> disabled();
            $selectMonth -> disabled();
            $this -> input($this->day) -> type('hidden') -> name('day');
            $this -> input($this->month) -> type('hidden') -> name('month');
        }

        //If an existing Easter-dependent holiday is chosen, a NULL value (no set month/day) should be available to be submitted automatically.
        if($this->easterDeviation != 'NULL') {
            $selectDay -> option('~automatic~') -> value('NULL');
            $selectMonth -> option('~automatic~') -> value('NULL');
            $this -> input('NULL') -> type('hidden') -> name('day');
            $this -> input('NULL') -> type('hidden') -> name('month');
        }

        /* Hidden inputs */
        $this -> input($this->holidayId) -> type('hidden') -> name('id');
        $this -> input($this->easterDeviation) -> type('hidden') -> name('easterDeviation');
        
    }

    private static function existing_holiday($chosenHoliday) {
        //The value "0" is a self-made holiday.
        if($chosenHoliday && $chosenHoliday != '0') { 
            return true;
        }
        return false;
    }

    private static function egg_holidays($res = array()) 
    {
        $file = ROOT.'/config/holidays.egg';
        if(!file_exists($file)) return false;
        
        foreach (EggFile::get_array($file) as $name => $item)   
            if(isset($item['_pars'])) $res[$name] = array('name'=>$name, 'day'=>$item['_pars'][0], 'month'=>$item['_pars'][1], 'easterDeviation'=>$item['_pars'][2]);
        
        return $res;
    }
        
    public static function authorized()
    {
        return User::is_logged_in();
    }
    
    
}
?>