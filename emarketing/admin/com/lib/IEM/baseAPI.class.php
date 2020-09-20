<?php
/**
 *
 * @todo all
 *
 */
abstract class IEM_baseAPI
{
	/**
	 * Get record by ID
	 * This method will fetch a record from the database.
	 *
	 * NOTE: An $id can be a composite of columns (ie. having more than one column for the primary key).
	 * If this is the case, you will need to pass an associative array for the $id parameter.
	 *
	 * NOTE: Each implementation will return different "baseRecord". For example API_USERS will return
	 * record_User object.
	 *
	 * NOTE: Each implementation may add extra parameters to the signature, but they need to be optional,
	 * and will still need to follow the guideline of this base method signature.
	 *
	 * @param mixed $id Record ID to fetch
	 * @return IEM_baseRecord|FALSE Returns base record if successful, FALSE otherwise
	 */
	abstract static public function getRecordByID($id);

	/**
	 * Delete record by ID
	 * This method will delete record from database
	 *
	 * NOTE: An $id can be a composite of columns (ie. having more than one column for the primary key).
	 * If this is the case, you will need to pass an associative array for the $id parameter.
	 *
	 * NOTE: Each implementation may add extra parameters to the signature, but they need to be optional,
	 * and will still need to follow the guideline of this base method signature.
	 *
	 * @param mixed $id ID of the record to be deleted
	 * @return boolean Returns TRUE if successful, FALSE otherwise
	 */
	abstract static public function deleteRecordByID($id);
}

class exception_IEM_baseAPI
{
	const UNABLE_TO_QUERY_DATABASE = 1;
}