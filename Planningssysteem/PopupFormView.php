<?php
class PopupFormView  extends AppView{
    protected $contentNode = 'form';

    protected $title = '';
    protected $action = '';

    public function PopupFormView_setup() 
    {
        $this->list = $this->ul;
    }

    public function PopupFormView_run() 
    {   
        $this -> class('popup__window') -> method('post') -> action($this->action);
        $this -> h3($this->title) -> class('popup__header');

        $this -> add( $this->list ) -> class('form-list');

        $this -> add( $this->content );

        $this->content -> add('div') -> class('button-container')
                       -> add('button', 'Annuleren') -> type('button') -> class('button-cancel close-popup')
                       -> add('button', 'Opslaan')   -> type('submit') -> class('button-yes');

    }

    protected function fill_form_fields($objectId, $object) {
        if($objectId) { Form::populate_fields($this->list, $object); }
    }
    
    
    protected function show_requested_fields_only($subcategory) {
        /* Only show the fields of the requested subcategory. */
        if($subcategory != ''){
            $this->get_by_attribute('class','input-container','li') -> class('display-none');
            $this->get_by_attribute('id', $subcategory.'-container','li') -> class('display');
        }
    }


    protected function add_input($label, $name, $value = '', $maxLength = '', /*5*/$autoFocus = '', $type = 'text') {
        if($autoFocus) { $autoFocus = ' auto-focus'; }

        $li = $this->list -> li() -> id($name.'-container') -> class('input-container');

        $input = $li -> label($label) -> set_for($name) -> also
                     -> input($value) -> type($type) -> name($name) -> id($name) -> class($autoFocus);
                                        
        if($maxLength != '') {
            $input -> maxlength($maxLength);
        }
    }

    protected function add_sub_list($name) {
        return $this->list -> li() -> id($name.'-container') -> class('input-container') 
                           -> ul() -> class('form-sub-list');
    }

    protected function add_input_to_sub_list($ul, $label, $name, $value = '', $maxLength = '', /*6*/$autoFocus = '', $type = 'text') {
        if($autoFocus) { $autoFocus = 'auto-focus'; }

        $input = $ul -> li()
                     -> label($label) -> set_for($name) -> also
                     -> input($value) -> type($type) -> name($name) -> id($name) -> class($autoFocus);
                                        
        if($maxLength != '') {
            $input -> maxlength($maxLength);
        }
    }


    protected function add_hidden($value, $name) {
        $this -> input($value) -> type('hidden') -> name($name);
    }
    

    protected function add_select($label, $name, $names=array(), $values=array(), $selected=array(), /*6*/$autoFocus = '', $defaultText = 'Kies...')
    {
        if($autoFocus) { $autoFocus = 'auto-focus'; }

        $li = $this->list -> li() -> id($name.'-container') -> class('input-container');
        
        $select = $li -> label($label) -> set_for($name) -> also
                      -> select() -> name($name) -> id($name) -> class($autoFocus);

        $option = $select -> add('option', $defaultText) -> value('');          
        foreach($names as $i => $n) {
            $option = $select -> add('option', $n) -> value(isset($values[$i])?$values[$i]:$i);
            if(in_array($values[$i],$selected)) $option -> selected('selected');
        }

        return $select;
    }


    protected function add_color_picker($object) {
        $select = $this->list -> li() -> id('color-container') -> class('input-container')
                      -> label('Kleur') -> set_for('color-picker') -> also
                      -> select() -> name('color') -> id('color-picker') -> class('color-picker');  
        
        $this->print_color_options($select, $object);
    }

    /* Print colors for color picker. */
    function print_color_options($select, $object) {
        $colors = array('ffffff','ffccc9','ffce93','fffc9e','ffffc7','9aff99','96fffb','cdffff','cbcefb','cfcfcf','fd6864','fe996b','fffe65','fcff2f','67fd9a','38fff8','68fdff','9698ed','c0c0c0','fe0000','f8a102','ffcc67','f8ff00','34ff34','68cbd0','34cdf9','6665cd','9b9b9b','cb0000','f56b00','ffcb2f','ffc702','32cb00','00d2cb','3166ff','6434fc','656565','9a0000','ce6301','cd9934','999903','009901','329a9d','3531ff','6200c9','343434','680100','963400','986536','646809','036400','34696d','00009b','303498','000000','330001','643403','663234','343300','013300','003532','010066','340096');
                
        foreach($colors as $color) {
            $option = $select -> add('option', $color) -> value($color);
            if($object && $color == $object->color) {
                $option -> selected('selected');
            }
        }
    }

    /* Selector for Day, Month, Year */
    public function print_date_selection_forms($unformattedDate, $label, $selectNameDay, $selectNameMonth, $selectNameYear, $futureDate='', $containerName, $autoFocus, $yearRequired=true) {
        
        if($autoFocus) { $autoFocus = ' auto-focus'; }

        $day       = date('d', strtotime($unformattedDate));
        $month     = date('m', strtotime($unformattedDate));
        $monthName = date('M', strtotime($unformattedDate));
        $year      = date('Y', strtotime($unformattedDate));
        
        $containerDiv = $this->list -> li() -> id($containerName.'-container') -> class('input-container');
        $containerDiv -> add('label', $label);

        if($yearRequired) {
            //The formfield for year is optional.
            $selectYear = $containerDiv -> add('select') -> name($selectNameYear) -> class('datetime');
            
            if($futureDate == 'true') {
                for($i=date('Y'); $i <= date('Y')+10; $i++) {
                    $this->add_option($selectYear, $i, $i, $year);
                    
                }
            } else {
                for($i=date('Y')+1; $i>=1940; $i--) {
                    $this->add_option($selectYear, $i, $i, $year);
                    
                }
            }
        }
        
        $selectMonth = $containerDiv -> add('select') -> name($selectNameMonth) -> class('datetime');
        for($i=1; $i<=12; $i++) {
            $this->add_option($selectMonth, HandleDbData::format_month($i), $i, $month);
        }

        $selectDay = $containerDiv -> add('select') -> name($selectNameDay) -> class('datetime'.$autoFocus);
        for($i=1; $i<=31; $i++) {
            $this->add_option($selectDay, HandleDbData::format_leading_zero($i), $i, $day);
        }
    }

    /* Yes-no selector for CAO and ATW */
    public function print_boolean_form($label, $row, $autoFocus='') 
    {
        if($autoFocus) { $autoFocus = ' auto-focus'; }

        $li = $this->list -> li() -> id('value-container') -> class('input-container');
        $li -> label($label) -> set_for('value');

        $selectBoolean = $li -> add('select') -> name('value') -> class($autoFocus);
        
        if($row['value'] < 1) {
            $selectBoolean -> add('option', 'ja') -> value(1)
                           -> add('option', 'nee') -> value(0) -> selected('selected');
        } else {
            $selectBoolean -> add('option', 'ja') -> value(1) -> selected('selected')
                           -> add('option', 'nee') -> value(0);
        }
    }
    
    /* Date selector */
    public function print_date_form($label, $row)
    {
        $li = $this->list -> li() -> id($name.'-container') -> class('input-container');
        $li -> label($label) -> set_for($name);
        
        $selectMonths = $li -> add('select') -> name('months') -> class('datetime');
        
        for($i=1; $i<=12; $i++) {
            $optionMonths = $selectMonths -> add('option', HandleDbData::format_month($i)) -> value(HandleDbData::format_leading_zero($i));
            
            if($i == substr($row['value'], 0, 2)) {
                $optionMonths -> selected('selected');
            }
        }   

        $selectDays = $li -> add('select') -> name('days') -> class('datetime');
        
        for($i=1; $i<=31; $i++) {
            $optionDays = $selectDays -> add('option', HandleDbData::format_leading_zero($i)) -> value(HandleDbData::format_leading_zero($i));
            
            if($i == substr($row['value'], -2)) {
                $optionDays -> selected('selected');
            }
        }   
    }
    
    /* Time selector */
    public function print_time_form($label, $row)
    {
        $li = $this->list -> li() -> id('hours-container') -> class('input-container');
        $li -> label($label) -> set_for('hours');
                    
        $selectMinutes = $li -> add('select') -> name('minutes') -> class('datetime');
        
        for($i=0; $i<60; $i=$i+15) {
            $optionMinutes = $selectMinutes -> add('option', HandleDbData::format_leading_zero($i)) -> value($i);
            
            if($i == substr($row['value'], -2)) {
                $optionMinutes -> selected('selected');
            }
        }

        $selectHours = $li -> add('select') -> name('hours') -> class('datetime');
                        
        for($i=0; $i<24; $i++) {
            $optionHours = $selectHours -> add('option', HandleDbData::format_leading_zero($i)) -> value($i);
            
            if($i == substr($row['value'], 0, 2)) {
                $optionHours -> selected('selected');
            }
        }   
    }

    public static function title() 
    {
        return 'title';
    }
    
    public static function authorized() 
    {
        return true;
    }
    

}
?>
