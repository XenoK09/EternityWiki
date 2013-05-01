<?php

namespace Eternity;
class html
{
	# void elements in HTML5 (elements with />)
	private static $void_elements = array(
		'area',
		'base',
		'br',
		'col',
		'command',
		'embed',
		'hr',
		'img',
		'input',
		'keygen',
		'link',
		'meta',
		'param',
		'source',
		'track',
		'wbr',
	);
	
	#
	private static $boolAttributes = array(
		'async',
		'autofocus',
		'autoplay',
		'checked',
		'controls',
		'default',
		'defer',
		'disabled',
		'formnovalidate',
		'hidden',
		'ismap',
		'itemscope',
		'loop',
		'multiple',
		'muted',
		'novalidate',
		'open',
		'pubdate',
		'readonly',
		'required',
		'reversed',
		'scoped',
		'seamless',
		'selected',
		'truespeed',
		'typemustmatch',
		'itemscope',
	);
	
	/**
	*	This function is used to properly open the element. 
	* 	it will call other functions to properly ensure that 
	*	the element is properly rendered.
	**/
	public static function  openelement($element, $attributes)
	{
		# transform the element to all lowercase characters
		$element = strtolower($element);
		
		
		
		# append opening carrot
		$element = '<' . $element . " " . self::attributes($attributes) . '>';
		return $element;
	}
	
	public static function closeelement($daelement)
	{
		$daelement = '</' . $daelement . '>';
		return $daelement;
	}
	
	public static function element($element, $attributes, $contents)
	{
		if(in_array($element, self::$void_elements))
		{
			$element .= '<' . $element . self::attributes($attributes) . '/>';
			return $element;
		}
		else
		{
			$newelement = self::openelement($element, $attributes) . $contents . self::closeelement($element);
			return $newelement;
		}
	}
	
	/** 
	*	This function is used to properly include the attributes
	* 	of the desired element.  The paramter is an array, so we 
	* 	are taking that array, and breaking it down.
	**/
	public static function  attributes($attributes)
	{
		foreach($attributes as $key => $value)
		{		
			# transform to all lowercase
			$key = strtolower($key);
			
			# check to see if it is a bool attribute, and set the 
			# key to equal the value.
			if(is_int($key) && in_array($value, self::$boolAttributes))
			{
				$key = $value;
			}
			
			/*
			****************************************************************************
			*	We are working with space separated values here.  So, we declare
			*   the space separated values, and convert it all into a correct array.
			*
			*/
			
			// declare space separated values.
			$space_separated_values = array(
				'class',
				'accesskey',
				'rel'
			);
			
			// check to see if the key has a space separated value.
			if(in_array($key, $space_separated_values))
			{
				// if the value is an array,  we must get the values into 
				// a correct array, and merge with the main one.
				if(is_array($value))
				{
					// create the new value to manipulate
					$new_value = array();
						
					// convert into a correct array
					foreach($value as $k => $v)
					{
						if(is_string($v))
						{
							if(!isset($value[$v]))
							{
								$new_value[] = $v;
							}
						}
						elseif($v)
						{
							$new_value = $k;
						}
					}
					
					// implode these values into the main array
					$value = implode(' ', $new_value);
				}
				
				// explode the array to get the correct values.
				$value = explode(' ', $value);
				
				// remove duplicates, and remove extra spaces, create the string
				$value = array_diff($value, array('',' '));
				$value = implode(' ', array_unique($value));
			}
			/*
			*	(END OF SPACE SEPARATED VALUES)
			****************************************************************************
			*/
			
			// we must escape these characters
			$escapers = array(
				'&' => '&amp;',
				"\n" => '&#10;',
				"\r" => '&#13;',
				"\t" => '&#9;'
			);
				
			$return_value = $key . '="' . strtr($value, $escapers) . '"';
		}
		
		return $return_value;
	}
}