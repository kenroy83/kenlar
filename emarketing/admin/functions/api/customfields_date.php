<?php
/**
* The Custom Fields Date API. This only handles the date custom field type.
*
* @version     $Id: customfields_date.php,v 1.13 2007/09/05 04:06:20 chris Exp $
* @author Chris <chris@sCRiPTz-TEAM.iNFO>
*
* @package API
* @subpackage CustomFields_API
*/

/**
* Include the custom fields api if we need to.
* This includes the base API class itself, so we don't need to worry about it.
*/
require_once(dirname(__FILE__) . '/customfields.php');

/**
* This class handles validation and checking of datebox custom fields.
*
* @package API
* @subpackage CustomFields_API
*/
class CustomFields_Date_API extends CustomFields_API
{

	/**
	* Months
	* An array of months. This lets us quickly grab the right language pack variable.
	*
	* @see GetLang
	* @see DisplayFieldOptions
	*
	* @var Array
	*/
	var $Months = array(
		'1' => 'Jan',
		'2' => 'Feb',
		'3' => 'Mar',
		'4' => 'Apr',
		'5' => 'May',
		'6' => 'Jun',
		'7' => 'Jul',
		'8' => 'Aug',
		'9' => 'Sep',
		'10' => 'Oct',
		'11' => 'Nov',
		'12' => 'Dec'
	);

	/**
	* This overrides the parent classes setting so we know what sort it is.
	*
	* @see CustomFields_API::fieldtype
	*
	* @var String
	*/
	var $fieldtype = 'date';

	/**
	* Options for this custom field type.
	*
	* @var Array
	*/
	var $Options = array(
		'Key' => Array()
	);

	/**
	* Constructor
	* Calls the parent object's constructor.
	* If an id is not passed in, the default order of fields is set along with the default start year and default end year.
	* The default start year is set to 0, the default end year is set to the current year.
	*
	* @param Int $fieldid The field to load up. This is passed to the parent constructor for processing.
	* @param Boolean $connect_to_db Whether to connect to the database or not. If this is set to false, you need to set the database up yourself.
	*
	* @see CustomFields_API::CustomFields_API
	* @see Options
	* @see Db
	*
	* @return Boolean Returns the parent's constructor.
	*/
	function CustomFields_Date_API($fieldid=0, $connect_to_db=true)
	{
		if ($fieldid <= 0) {
			$this->Options['Key'][0] = 'day';
			$this->Options['Key'][1] = 'month';
			$this->Options['Key'][2] = 'year';
			$this->Options['Key'][3] = (date('Y') - 5);
			$this->Options['Key'][4] = date('Y');
		}
		return $this->CustomFields_API($fieldid, $connect_to_db);
	}

	/**
	* Set
	* Sets the current settings to the values passed in based on which options this particular custom field has.
	*
	* @param Array $newvalues List of new values to set the current settings to.
	*
	* @see GetOptions
	* @see Settings
	*
	* @return Void Doesn't return anything.
	*/
	function Set($newvalues=array())
	{
		$myoptions = $this->GetOptions();

		foreach ($myoptions as $name => $val) {
			$newval = $newvalues[$name];
			if (is_array($newval)) {
				$checkvals = array();
				foreach ($newval as $k => $v) {
					if (gettype($v) == 'boolean' && $v === false) {
						continue;
					}
					$checkvals[] = $v;
				}
				$newval = $checkvals;
			}
			$this->Settings[$name] = $newval;
		}
	}

	/**
	* CheckData
	* Checkdata makes sure the data passed in is valid for this field type.
	* If the array passed in is empty, this returns false. If it contains bad data, or if the data is outside the range of this custom field, this returns false.
	*
	* <b>Example</b>
	* <code>
	* $data = array('dd' => 01, 'mm' => 01, 'yy' => 01);
	* </code>
	*
	* @param Array $data The data to validate.
	*
	* @see ValidData
	* @see Settings
	* @see IsLoaded
	*
	* @return Boolean If the data passed in contains a valid date within the range for this particular custom field, this will return true. If the data passed in contains an invalid date or is outside the range for this field, this will return false.
	*/
	function CheckData($data=array(), $return_date_format=false)
	{
		if (!$this->IsLoaded()) {
			return false;
		}

		if (!is_array($data)) {
			if (strpos($data, '/') !== false) {
				$data = explode('/', $data);
			}
			if (sizeof($data) != 3) {
				return false;
			}
		}

		// if it's not already in the right array format, convert it across.
		if (!isset($data['dd']) || !isset($data['mm']) || !isset($data['yy'])) {
			foreach ($this->Settings['Key'] as $p => $type) {
				// only need to check the first three options which will be the order of the elements.
				if ($p > 2) {
					break;
				}
				switch ($type) {
					case 'day':
						$data['dd'] = $data[$p];
					break;
					case 'month':
						$data['mm'] = $data[$p];
					break;
					case 'year':
						$data['yy'] = $data[$p];
					break;
				}
			}
		}

		if ($return_date_format) {
			return $data;
		}

		// an empty date - this should be fine as long as the field is not required.
		$empty = true;
		foreach (array('dd', 'mm', 'yy') as $field) {
			if (trim($data[$field]) != '') {
				$empty = false;
			}
		}
		if ($empty) {
			return true;
		}

		// missing options?
		if (!isset($data['dd']) || !isset($data['mm']) || !isset($data['yy'])) {
			return false;
		}

		// make sure the fields are within proper ranges.
		if ((int)$data['dd'] <= 0 || (int)$data['dd'] > 31) {
			return false;
		}

		if ((int)$data['mm'] <= 0 || (int)$data['mm'] > 12) {
			return false;
		}

		// if the year passed in is before "start year" (key[3]),
		// or if the year passed in is after the "end year" (key[4]),
		// return false.
		if ((int)$data['yy'] < (int)$this->Settings['Key'][3]) {
			return false;
		}

		// If key[4] is set to "0" that means "this year", so check that instead.
		if ((int)$this->Settings['Key'][4] == 0) {
			$this->Settings['Key'][4] = date('Y');
		}

		if ((int)$data['yy'] > (int)$this->Settings['Key'][4]) {
			return false;
		}
		return true;
	}

	/**
	* DisplayFieldOptions
	* This displays options for the custom field if it is loaded. Each type handles this differently.
	* It will return the options and the items (eg checkboxes).
	*
	* @param Array $chosen A list of chosen checkboxes (keys not values).
	* @param Boolean $useoptions This isn't used in the function, needed as a parameter placeholder. Set to false.
	* @param Int $formid The form we are generating the content for. This is used to modify the form id's and javascript slightly to allow multiple forms to be on the same page.
	*
	* @see IsLoaded
	* @see Settings
	*
	* @return String Returns a blank string if the custom field hasn't been loaded. If it has, it will create a list of checkboxes with pre-selected items (if supplied).
	*/
	function DisplayFieldOptions($chosen=array(), $useoptions=false, $formid=0)
	{
		if (!$chosen) {
			$chosen = array();
		}

		if (!$this->IsLoaded()) {
			return '';
		}

		$formid = (int)$formid;

		$return_string = '';

		$yy_start = $this->Settings['Key'][3];
		$yy_end = $this->Settings['Key'][4];
		if ($yy_end == 0) {
			$yy_end = date('Y');
		}

		$field_order = array_slice($this->Settings['Key'], 0, 3);

		$daylist = '<select name="CustomFields['.$this->fieldid.'][dd]" id="CustomFields['.$this->fieldid.'][dd]">';

		for ($i=1; $i<=31; $i++) {
			$daylist.='<option value="'.sprintf("%02d",$i).'">'.$i . '</option>';
		}
		$daylist .= '</select>';

		$monthlist = '<select name="CustomFields['.$this->fieldid.'][mm]" id="CustomFields['.$this->fieldid.'][mm]">';
		for ($i=1; $i<=12; $i++) {
			$monthlist.='<option value="'.sprintf("%02d",$i).'">'.GetLang($this->Months[$i]) . '</option>';
		}
		$monthlist.='</select>';

		$yearlist = '<select name="CustomFields['.$this->fieldid.'][yy]" id="CustomFields['.$this->fieldid.'][yy]">';
		for ($i=$yy_start; $i<=$yy_end; $i++) {
			$yearlist.='<option value="'.$i.'">'. $i . '</option>';
		}
		$yearlist.='</select>';

		foreach ($field_order as $p => $order) {
			switch ($order) {
				case 'day':
					$return_string .= $daylist;
				break;
				case 'month':
					$return_string .= $monthlist;
				break;
				case 'year':
					$return_string .= $yearlist;
				break;
			}
		}
		return $return_string;
	}

	/**
	* GetRealValue
	* This gets the 'real' value from the custom field. This is used when sending the list owner a notification of a subscription.
	* If an array is passed in, the date format is turned in to whatever the custom field date order is set to (ie mm/dd/yy or dd/mm/yy or other).
	* If a string is passed in, only a string with 2 '/'s in it will be parsed.
	* If there are not 2 /'s then this returns false.
	*
	* @param Array $value The value to check against the Settings list. This checks against the settings key.
	*
	* @see Settings
	*
	* @return String Returns the real value based on the custom field type.
	*/
	function GetRealValue($value = array())
	{
		if (!is_array($value)) {
			if (strpos($value, '/') !== false) {
				$options = explode('/', $value);
				if (sizeof($options) != 3) {
					return false;
				}

				$value = array(
					'dd' => (int)$options[0],
					'mm' => (int)$options[1],
					'yy' => (int)$options[2]
				);
			} else {
				return false;
			}
		}

		$return_value = array();

		$field_order = array_slice($this->Settings['Key'], 0, 3);

		foreach ($field_order as $p => $order) {
			switch ($order) {
				case 'day':
					$return_value[] = $value['dd'];
				break;
				case 'month':
					$return_value[] = $value['mm'];
				break;
				case 'year':
					$return_value[] = $value['yy'];
				break;
			}
		}
		return implode('/', $return_value);
	}

}

?>
