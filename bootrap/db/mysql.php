<?php
if(!defined("SQL_LAYER"))
{	define("SQL_LAYER","mysql");

if (function_exists('mysqli_report')) {
	mysqli_report(MYSQLI_REPORT_OFF);
}

class sql_db
{	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $persistency;
	var $user;
	var $password;
	var $server;
	var $dbname;

	function __construct($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
	{	$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;

		$server = $this->server;
		if($this->persistency && strpos($server, 'p:') !== 0)
		{	$server = 'p:' . $server;
		}

		$this->db_connect_id = @mysqli_connect($server, $this->user, $this->password);

		if($this->db_connect_id)
		{	if($database != "")
			{	$this->dbname = $database;
				$dbselect = @mysqli_select_db($this->db_connect_id, $this->dbname);
				if(!$dbselect)
				{	@mysqli_close($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}
			}
			@mysqli_set_charset($this->db_connect_id, 'utf8mb4');
			return $this->db_connect_id;
		} else
		{	return false;
		}
	}

	function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
	{	$this->__construct($sqlserver, $sqluser, $sqlpassword, $database, $persistency);
	}

	function sql_result_key($query_id)
	{	if (is_object($query_id))
		{	return function_exists('spl_object_id') ? spl_object_id($query_id) : spl_object_hash($query_id);
		}
		return $query_id;
	}

	// Dinh nghia cac phuong thuc chuan
	function sql_close()
	{	if($this->db_connect_id)
		{	if($this->query_result instanceof mysqli_result)
				@mysqli_free_result($this->query_result);
			$result = @mysqli_close($this->db_connect_id);
			return $result;
		}
		else
		{	return false;
		}
	}

	function sql_query($query = "", $transaction = FALSE)
	{	unset($this->query_result);
		if($query != "")
		{
			$this->num_queries++;

			$this->query_result = @mysqli_query($this->db_connect_id, $query);
		}
		if($this->query_result)
		{
			$key = $this->sql_result_key($this->query_result);
			unset($this->row[$key]);
			unset($this->rowset[$key]);
			return $this->query_result;
		}
		else
		{
			return ( defined('END_TRANSACTION') && $transaction == END_TRANSACTION ) ? true : false;
		}
	}

	function sql_numrows($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id instanceof mysqli_result)
		{
			$result = @mysqli_num_rows($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_affectedrows()
	{	if($this->db_connect_id)
		{	$result = @mysqli_affected_rows($this->db_connect_id);
			return $result;
		}
		else
		{	return false;
		}
	}

	function sql_numfields($query_id = 0)
	{	if(!$query_id)
		{	$query_id = $this->query_result;
		}
		if($query_id instanceof mysqli_result)
		{	$result = @mysqli_num_fields($query_id);
			return $result;
		}
		else
		{	return false;
		}
	}

	function sql_fieldname($offset, $query_id = 0)
	{	if(!$query_id)
		{	$query_id = $this->query_result;
		}
		if($query_id instanceof mysqli_result)
		{	$field = @mysqli_fetch_field_direct($query_id, $offset);
			return $field ? $field->name : false;
		}
		else
		{	return false;
		}
	}

	function sql_fieldtype($offset, $query_id = 0)
	{	if(!$query_id)
		{	$query_id = $this->query_result;
		}
		if($query_id instanceof mysqli_result)
		{	$field = @mysqli_fetch_field_direct($query_id, $offset);
			return $field ? $field->type : false;
		}
		else
		{	return false;
		}
	}

	function sql_fetchrow($query_id = 0)
	{	if(!$query_id)
		{	$query_id = $this->query_result;
		}
		if($query_id instanceof mysqli_result)
		{	$key = $this->sql_result_key($query_id);
			$this->row[$key] = @mysqli_fetch_array($query_id);
			return $this->row[$key];
		}
		else
		{	return false;
		}
	}

	function sql_fetchrowset($query_id = 0)
	{	if(!$query_id)
		{	$query_id = $this->query_result;
		}
		if($query_id instanceof mysqli_result)
		{	$key = $this->sql_result_key($query_id);
			$result = array();
			unset($this->rowset[$key]);
			unset($this->row[$key]);
			while($this->rowset[$key] = @mysqli_fetch_array($query_id))
			{	$result[] = $this->rowset[$key];
			}
			return $result;
		}
		else
		{	return false;
		}
	}

	function sql_fetchfield($field, $rownum = -1, $query_id = 0)
	{	if(!$query_id)
		{	$query_id = $this->query_result;
		}
		if($query_id instanceof mysqli_result)
		{	$key = $this->sql_result_key($query_id);
			if($rownum > -1)
			{	@mysqli_data_seek($query_id, $rownum);
				$row = @mysqli_fetch_array($query_id);
				$result = $row ? $row[$field] : false;
			}
			else
			{	if(empty($this->row[$key]) && empty($this->rowset[$key]))
				{	if($this->sql_fetchrow($query_id))
					{	$result = $this->row[$key][$field];
					}
				}
				else
				{	if(!empty($this->rowset[$key]))
					{	$result = $this->rowset[$key][$field];
					}
					else if(!empty($this->row[$key]))
					{	$result = $this->row[$key][$field];
					}
				}
			}
			return isset($result) ? $result : false;
		}
		else
		{	return false;
		}
	}

	function sql_rowseek($rownum, $query_id = 0)
	{	if(!$query_id)
		{	$query_id = $this->query_result;
		}
		if($query_id instanceof mysqli_result)
		{	$result = @mysqli_data_seek($query_id, $rownum);
			return $result;
		}
		else
		{	return false;
		}
	}
	
	function sql_nextid()
	{	if($this->db_connect_id)
		{	$result = @mysqli_insert_id($this->db_connect_id);
			return $result;
		}
		else
		{	return false;
		}
	}
	
	function sql_freeresult($query_id = 0)
	{	if(!$query_id)
		{	$query_id = $this->query_result;
		}

		if ( $query_id instanceof mysqli_result )
		{	$key = $this->sql_result_key($query_id);
			unset($this->row[$key]);
			unset($this->rowset[$key]);
			@mysqli_free_result($query_id);
			return true;
		}
		else
		{	return false;
		}
	}

	function sql_error($query_id = 0)
	{	$result["message"] 	= $this->db_connect_id ? @mysqli_error($this->db_connect_id) : @mysqli_connect_error();
		$result["code"] 	= $this->db_connect_id ? @mysqli_errno($this->db_connect_id) : @mysqli_connect_errno();
		return $result;
	}

} // class sql_db

} // if ... define

?>
