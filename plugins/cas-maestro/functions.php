<?php
/***************************************************************
 ***  CAS Maestro functions file 							 ***
 ***************************************************************/

/**
 * removeEmptyItems
 * To be used with array_filter
 * @param &$item passed by reference, the array item being tested.
 */
function removeEmptyItems(&$item) {
	//Verify is the
    if (is_array($item) && $item) {
        $item = array_filter($item, 'removeEmptyItems');
    }

    return !empty($item);
}

/**
 * Check for a empty value and if there is an error defined
 * and print a class.
 */
function check_empty($variable) {
	if(empty($variable) && isset($_GET['error'])) {
		echo "class='required_field'";
	}
}