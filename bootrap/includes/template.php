<?php
class Template {
	var $classname = "Template";
	var $_tpldata = array();
	var $files = array();
	var $root = "";
	var $compiled_code = array();
	var $uncompiled_code = array();

	function __construct($root = ".")
	{	$this->set_rootdir($root);
	}

	function Template($root = ".")	// constructor
	{	$this->set_rootdir($root);
	}

	function destroy()
	{	$this->_tpldata = array();
	}

	function set_rootdir($dir)
	{	if (!is_dir($dir))
		{	return false;
		}

		$this->root = $dir;
		return true;
	}

	function set_filenames($filename_array)
	{	if (!is_array($filename_array))
		{	return false;
		}

		foreach ($filename_array as $handle => $filename)
		{
			$this->files[$handle] = $this->make_filename($filename);
		}

		return true;
	}
	
	function set_filenames_new($filename_array)
	{	global $theme, $skin, $langpath;
		if (!is_array($filename_array))
		{	return false;
		}
		foreach ($filename_array as $handle => $filename)
		{	if (file_exists("templates/$skin/$langpath/".$filename))
				$this->files[$handle] = $this->make_filename("templates/$skin/$langpath/".$filename);
			else
				$this->files[$handle] = $this->make_filename("templates/default/$langpath/".$filename);
		}

		return true;
	}

	function read_fileformat($handle)
	{	if (!$this->loadfile($handle))
		{
			die("EmailTemplate->pparse(): Couldn't load template file for handle $handle");
		}
		
		$content = "";
		
		if (!isset($this->compiled_code[$handle]) || empty($this->compiled_code[$handle]))
		{	$this->compiled_code[$handle] = $this->compile($this->uncompiled_code[$handle], true, 'content');
		}

		eval($this->compiled_code[$handle]);
		
		return $content;
	}

	function pparse($handle)
	{	if (!$this->loadfile($handle))
		{	die("Template->pparse(): Couldn't load template file for handle $handle");
		}

		if (!isset($this->compiled_code[$handle]) || empty($this->compiled_code[$handle]))
		{	$this->compiled_code[$handle] = $this->compile($this->uncompiled_code[$handle]);
		}

		eval($this->compiled_code[$handle]);	
		return true;
	}

	function assign_var_from_handle($varname, $handle)
	{
		if (!$this->loadfile($handle))
		{	die("Template->assign_var_from_handle(): Couldn't load template file for handle $handle");
		}

		$_str = "";
		$code = $this->compile($this->uncompiled_code[$handle], true, '_str');

		eval($code);

		$this->assign_var($varname, $_str);
		return true;
	}

	function assign_block_vars($blockname, $vararray)
	{	if (strstr($blockname, '.'))
		{
			$blocks = explode('.', $blockname);
			$blockcount = sizeof($blocks) - 1;
			$str = '$this->_tpldata';
			for ($i = 0; $i < $blockcount; $i++)
			{
				$str .= '[\'' . $blocks[$i] . '.\']';
				eval('$lastiteration = sizeof(' . $str . ') - 1;');
				$str .= '[' . $lastiteration . ']';
			}

			$str .= '[\'' . $blocks[$blockcount] . '.\'][] = $vararray;';

			eval($str);
		}
		else
		{
			$this->_tpldata[$blockname . '.'][] = $vararray;
		}

		return true;
	}

	function assign_vars($vararray)
	{	foreach ($vararray as $key => $val)
		{
			$this->_tpldata['.'][0][$key] = $val;
		}

		return true;
	}

	function assign_var($varname, $varval, $mode = 0)
	{	if ($mode == 0)
			$this->_tpldata['.'][0][$varname] = $varval;
		else
		{	if ($this->_tpldata['.'][0][$varname] != "")
				$this->_tpldata['.'][0][$varname] .=  ("<br>" . $varval);
			else
				$this->_tpldata['.'][0][$varname] = $varval;
		}
		return true;
	}

	function make_filename($filename)
	{	if (substr($filename, 0, 1) != '/')
		{	$filename = $this->root . '/' . $filename;
		}

		if (!file_exists($filename))
		{	die("Template->make_filename(): Error - file $filename does not exist");
		}

		return $filename;
	}

	function loadfile($handle)
	{
		if (isset($this->uncompiled_code[$handle]) && !empty($this->uncompiled_code[$handle]))
		{
			return true;
		}
		if (!isset($this->files[$handle]))
		{
			die("Template->loadfile(): No file specified for handle $handle");
		}

		$filename = $this->files[$handle];
		$str = implode("", @file($filename));
		if (empty($str))
		{
			die("Template->loadfile(): File $filename for handle $handle is empty");
		}

		$this->uncompiled_code[$handle] = $str;
		return true;
	}

	function compile($code, $do_not_echo = false, $retvar = '')
	{	global $languageid;
		// replace \ with \\ and then ' with \'.
		$code = str_replace('\\', '\\\\', $code);
		$code = str_replace('\'', '\\\'', $code);

		// change template varrefs into PHP varrefs
		// This one will handle varrefs WITH namespaces
		$varrefs = array();
		preg_match_all('#\{(([a-z0-9\-_]+?\.)+?)([a-z0-9\-_]+?)\}#is', $code, $varrefs);
		$varcount = sizeof($varrefs[1]);
		for ($i = 0; $i < $varcount; $i++)
		{
			$namespace = $varrefs[1][$i];
			$varname = $varrefs[3][$i];
			$new = $this->generate_block_varref($namespace, $varname);

			$code = str_replace($varrefs[0][$i], $new, $code);
		}

		// This will handle the remaining root-level varrefs
		$code = preg_replace('#\{([a-z0-9\-_]*?)\}#is', '\' . ( ( isset($this->_tpldata[\'.\'][0][\'\1\']) ) ? $this->_tpldata[\'.\'][0][\'\1\'] : \'\' ) . \'', $code);

		// Break it up into lines.
		$code_lines = explode("\n", $code);

		$block_nesting_level = 0;
		$block_names = array();
		$block_names[0] = ".";

		// Second: prepend echo ', append ' . "\n"; to each line.
		$line_count = sizeof($code_lines);
		for ($i = 0; $i < $line_count; $i++)
		{	$code_lines[$i] = chop($code_lines[$i]);
			if (preg_match('#<!-- (BEGIN|START|LOOP|BLOCK) (.*?) -->#', $code_lines[$i], $m))
			{	$n[0] = $m[0];
				$n[1] = $m[1];			
				$n[2] = $m[2];

				if ( preg_match('#<!-- (END|ENDLOOP|ENDBLOCK) (.*?) -->#', $code_lines[$i], $n) )
				{	$block_nesting_level++;
					$block_names[$block_nesting_level] = $m[2];
					if ($block_nesting_level < 2)
					{	// Block is not nested.
						$code_lines[$i] = '$_' . $a[1] . '_count = ( isset($this->_tpldata[\'' . $n[2] . '.\']) ) ?  sizeof($this->_tpldata[\'' . $n[2] . '.\']) : 0;';
						$code_lines[$i] .= "\n" . '$_' . $n[2] . '_i = 0;' . "\n" . 'while ($_' . $n[2] . '_i < $_' . $n[2] . '_count)';
						$code_lines[$i] .= "\n" . '{';
					}
					else
					{	// This block is nested.
						// Generate a namespace string for this block.
						$namespace = implode('.', $block_names);
						// strip leading period from root level..
						$namespace = substr($namespace, 2);
						// Get a reference to the data array for this block that depends on the
						// current indices of all parent blocks.
						$varref = $this->generate_block_data_ref($namespace, false);
						// Create the for loop code to iterate over this block.
						$code_lines[$i] = '$_' . $a[1] . '_count = ( isset(' . $varref . ') ) ? sizeof(' . $varref . ') : 0;';
						$code_lines[$i] .= "\n" . '$_' . $n[2] . '_i = 0;' . "\n" . 'while ($_' . $n[2] . '_i < $_' . $n[2] . '_count)';
						$code_lines[$i] .= "\n" . '{';
					}

					// We have the end of a block.
					unset($block_names[$block_nesting_level]);
					$block_nesting_level--;
					$code_lines[$i] .= '} // END ' . $n[2];
					$m[0] = $n[0];
					$m[1] = $n[1];
					$m[2] = $n[2];					
				} else
				{	// We have the start of a block.
					$block_nesting_level++;
					$block_names[$block_nesting_level] = $m[2];
					if ($block_nesting_level < 2)
					{	// Block is not nested.
						$code_lines[$i] = '$_' . $m[2] . '_count = ( isset($this->_tpldata[\'' . $m[2] . '.\']) ) ? sizeof($this->_tpldata[\'' . $m[2] . '.\']) : 0;';
						$code_lines[$i] .= "\n" . '$_' . $m[2] . '_i = 0;' . "\n" . 'while ($_' . $m[2] . '_i < $_' . $m[2] . '_count)';
						$code_lines[$i] .= "\n" . '{';
					} else
					{	// This block is nested.
						// Generate a namespace string for this block.
						$namespace = implode('.', $block_names);
						// strip leading period from root level..
						$namespace = substr($namespace, 2);
						// Get a reference to the data array for this block that depends on the
						// current indices of all parent blocks.
						$varref = $this->generate_block_data_ref($namespace, false);
						// Create the for loop code to iterate over this block.
						$code_lines[$i] = '$_' . $m[2] . '_count = ( isset(' . $varref . ') ) ? sizeof(' . $varref . ') : 0;';
						$code_lines[$i] .= "\n" . '$_' . $m[2] . '_i = 0;' . "\n" . 'while ($_' . $m[2] . '_i < $_' . $m[2] . '_count)';						
						$code_lines[$i] .= "\n" . '{';
					}
				}
			}
			else if (preg_match('#<!-- (END|ENDLOOP|ENDBLOCK) (.*?) -->#', $code_lines[$i], $m))
			{	// We have the end of a block.
				unset($block_names[$block_nesting_level]);
				$block_nesting_level--;
				$code_lines[$i] = '$_' .$m[2] . '_i++;' . "\n" . '} // END ' . $m[2];
			} else if (preg_match('#<!-- INCLUDE (.*?) -->#', $code_lines[$i], $match))
			{	global $root_path;	
				$match[1] = str_replace("[SKIN]", (isset($skin)) ? $skin : "default", $match[1]);
				$match[1] = str_replace("[ROOT]", $root_path, $match[1]);
				$code_lines[$i] = "\n" . "include(\"" . $match[1] . "\");";	
			} else if (preg_match('#<!-- CONTENT -->#', $code_lines[$i]))
			{	global $root_path, $mainTemplate;	
				$code_lines[$i] = "\n" . "include(\"" . $mainTemplate . "\");";						
			} else if (preg_match('#<!-- (CONTINUE|NEXT) (.*?) -->#', $code_lines[$i], $m))
			{	$code_lines[$i]  = "\n" . 'if ($_' . $m[2] . '_i < $_' . $m[2] . '_count - 1)';		 
				$code_lines[$i] .= "\n" . '$_' . $m[2] . '_i++;';
				$code_lines[$i] .= "\n" . 'else';
				$code_lines[$i] .= "\n" . 'break;';
			} else if (preg_match('#<!-- (CODE|FUNCTION|DO) (.*?) -->#', $code_lines[$i], $function))
			{	$function[2] = str_replace("[LANGUAGEID]", $languageid, $function[2]);
				$code_lines[$i]  = "\n" . $function[2] . ';';
			} else if (preg_match('#<!-- IF (.*?) -->#', $code_lines[$i], $condition))
			{	$code_lines[$i]  = "\n" . 'if (' . $condition[1] . ')';
				$code_lines[$i] .= "\n" . '{';
			} else if (preg_match('#<!-- ELSE -->#', $code_lines[$i]))
			{	$code_lines[$i]  = "\n" . '} else {';
			} else if (preg_match('#<!-- ENDIF -->#', $code_lines[$i]))
			{	$code_lines[$i]  = "\n" . '}';
			} else			
			{	if (!$do_not_echo)
				{	$code_lines[$i] = 'echo \'' . $code_lines[$i] . '\' . "\\n";';
				}	else
				{	$code_lines[$i] = '$' . $retvar . '.= \'' . $code_lines[$i] . '\' . "\\n";'; 
				}
			}
		}
		$code = implode("\n", $code_lines);
		return $code;
	}

	function generate_block_varref($namespace, $varname)
	{	// Strip the trailing period.
		$namespace = substr($namespace, 0, strlen($namespace) - 1);

		// Get a reference to the data block for this namespace.
		$varref = $this->generate_block_data_ref($namespace, true);
		// Prepend the necessary code to stick this in an echo line.

		// Append the variable reference.
		$varref .= '[\'' . $varname . '\']';
		$varref = '\' . ( ( isset(' . $varref . ') ) ? ' . $varref . ' : \'\' ) . \'';
		return $varref;
	}

	function generate_block_data_ref($blockname, $include_last_iterator)
	{	// Get an array of the blocks involved.
		$blocks = explode(".", $blockname);
		$blockcount = sizeof($blocks) - 1;
		$varref = '$this->_tpldata';
		// Build up the string with everything but the last child.
		for ($i = 0; $i < $blockcount; $i++)
		{	$varref .= '[\'' . $blocks[$i] . '.\'][$_' . $blocks[$i] . '_i]';
		}
		// Add the block reference for the last child.
		$varref .= '[\'' . $blocks[$blockcount] . '.\']';
		// Add the iterator for the last child if requried.
		if ($include_last_iterator)
		{	$varref .= '[$_' . $blocks[$blockcount] . '_i]';
		}
		return $varref;
	}
}
?>
