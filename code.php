<?php

/**
 * Autoloadify
 *
 * Highlight PHP using pseudo markup tags
 *
 * @package		TaS PHP Syntax Highlight 
 * @author		Thilo Savage
 * @copyright		Copyright (c) 2012
 * @since		Version 0.001
 */
static class code {

	/*
	 *	@brief Wrap HTML around code
	 *	@param	string	Highlighted code to wrap
	 *	@return	string	Wrapped code
	 */
	private function _wrapper($code)
	{
		$html = "<pre style='color: white; background-color: black; padding: 4px 15px;'>";
		$html .= $code;
		$html .= "</pre>";
		return $html;
	}
	
	/*
	 *	Opening tag - start output buffering
	 */
	public static function start()
	{
		ob_start();
	}

	/*
	 *	Closing tag - process code and wrap
	 *	@param	bool	apply coloring
	 */	
	public static function end($colors = true)
	{
		$str= ob_get_clean();

		$lines = explode(PHP_EOL,$str);
		
		
		if ($colors) {
			
			foreach ($lines as $line)
			{
				$str = htmlentities($line);
				
				$firstChars = substr(trim($line),0,2);

				// end comment
				
				if (strpos(" ".$str, '*/ ')) $continueComment = false;

				// if start comment or end comment or continue comment
				if ($firstChars == '/*' || strpos(" ".$str, '*/ ') || $continueComment)
				{
					// continue comment
					$continueComment = true;
					
					// show the comment as gray
					$str = "<span style='color: gray'>".$str."</span>";
					
					// end comment
					if (strpos(" ".$str, '*/')) {
						$continueComment = false;
					}
					
				}
				else
				{
				
					// true and false
					$str = preg_replace("/ (true)/i","<span style='color: #FF3300;'> $1</span>",$str);
					$str = preg_replace("/ (false)/i","<span style='color: #FF3300;'> $1</span>",$str);	
				
					$str = str_replace("return \$","<span style='color:#ff0000; '>return </span>\$",$str);
					
					// function definition
					$str = preg_replace("/function ([a-zA-Z_]*)/","<strong style='color: #77ffaa'>function $1</strong>",$str);
					
					// class definition
					$str = preg_replace("/class ([a-zA-Z]*) {/","<span style='color: #77ffaa'>class $1 {</span>",$str);
					
					// variables
					$str = preg_replace("/\\$([a-zA-Z0-9=_\[\'\]]*)/","<span style='color: #ffff55'>$$1</span>",$str);
					
					// equals signs
					$str = preg_replace("/( = )/","<span style='color: red;'> = </span>",$str);
					$str = preg_replace("/( .= )/","<span style='color: red; '> .= </span>",$str);
					
					// between double quotes
					$str = preg_replace('/&quot;(.*)&quot;/',"<span style='color: orange;'>\"$1\"</span>",$str);
					
					
					// block comments after code
					$str = preg_replace('/\/\*(.*)\*\//',"<span style='color: gray;'>/*$1*/</span>", $str);
					
					//$str = preg_replace("/[^\:]\/\/(.*)\\n/","<span style='color: #aaaaaa'>//$1</span>",$str);
					
					// content inside parinthesis
					$str = preg_replace("/[(](.*)[)]/","<span style='color: orange;'>($1)</span>",$str);				

					// static methods
					$str = preg_replace("/([a-zA-Z]*)::([a-zA-Z]*)/"," <span style='color: #19ccff; '>$1::$2</span></span>",$str);

					
				
					
					// other special stuff
					$str = str_replace("new ","<span style='color: #ff0;'>new </span>",$str);
					$str = str_replace("var ","<span style='color: #99ccff;'>var </span>",$str);
					$str = str_replace("public ","<span style='color: #a6ff77;'>public </span>",$str);
					$str = str_replace("static ","<span style='color: #a5ff77;'>static </span>",$str);
					
					
					//$str = str_replace("function","<span style='color: #ee55aa;'>function</span>",$str);
					
					//$str = str_replace("class ","<span style='color:#77ffaa;'>class </span>",$str);
					$str = str_replace("{","<span style='color:#FF33ff;'>{</span>",$str);
					$str = str_replace("}","<span style='color:#FF33ff;'>}</span>",$str);
					$str = str_replace("(","<span style='color:#FF3388;'>(</span>",$str);
					$str = str_replace(")","<span style='color:#FF3388;'>)</span>",$str);
					$str = str_replace("echo ","<span style='color:#FF7788;'>echo</span>",$str);
					$str = str_replace("::","<span style='color:#ff3;'>::</span>",$str);
					$str = str_replace("-&gt;","<span style='color:#99ccff;'>-&gt;</span>",$str);
					
					// content inside parinthesis
					$str = preg_replace("/\(\'(.*)\)\'/","<span style='color: orange;'>($1)</span>",$str);
					
					$str = preg_replace("/\/\/(.*)/","<strong style='color: gray;'>// $1</strong>",$str, -1, $count);

					if ($count)
					{
						$split = explode('//', $str, 2);
						$second = strip_tags($split[1]);
						$str = $split[0]."//".$second."</strong>";
					}
				}
				$stack .= $str."<br>";
			}
		}
		else
		{
			$stack = htmlentities($str);
		}
		echo self::_wrapper($stack);
	}
}

?>