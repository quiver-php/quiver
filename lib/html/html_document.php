<?php

// ============================================================
// HTML Document
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - http://danielcarvalho.com/
// ============================================================

namespace quiver\html;

class html_document
{
	private $lang = 'en';
	private $title;
	private $meta = array();
	private $css = array();
	private $javascript = array();
	private $style = array();
	private $script = array();
	private $blocks = array();
	
	public function __construct($title)
	{
		$this->set_title($title);
	}
	
	public function get_lang()
	{
		return $this->lang;
	}
	
	public function set_lang($lang = 'en')
	{
		$this->lang = $lang;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function set_title($title)
	{
		$this->title = $title;
	}
	
	public function get_meta()
	{
		$html = '';
		
		for ($i = 0; $i < count($this->meta); $i++)
		{
			$html .= '
				<meta name="' . $this->meta[$i]['name'] . '" content="' . $this->meta[$i]['content'] . '" />
			';
		}
		
		return $html;
	}
	
	public function add_meta($name, $content)
	{
		$meta = array(
			
			'name' => $name,
			'content' => $content
		
		);
		
		array_push($this->meta, $meta);
	}
	
	public function get_css()
	{
		$html = '';
		
		for ($i = 0; $i < count($this->css); $i++)
		{
			$html .= '
				<link href="' . $this->css[$i]['href'] . '" media="' . $this->css[$i]['media'] . '" rel="stylesheet" type="text/css" />
			';
		}
		
		return $html;
	}
	
	public function add_css($href, $media = 'all')
	{
		$css = array(
		
			'href' => $href,
			'media' => $media
			
		);
		
		array_push($this->css, $css);
	}
	
	public function get_javascript()
	{
		$html = '';
		
		for ($i = 0; $i < count($this->javascript); $i++)
		{
			$html .= '
				<script src="' . $this->javascript[$i]['src'] . '" type="text/javascript"></script>
			';
		}
		
		return $html;
	}
	
	public function add_javascript($src)
	{
		$javascript = array('src' => $src);
		
		array_push($this->javascript, $javascript);
	}
	
	public function get_style()
	{
		$html = '';
		
		if ( !empty($this->style) )
		{
			$html = '
				<style type="text/css">
			';
			
			for ($i = 0; $i < count($this->style); $i++)
			{
				$html .= $this->style[$i];
			}
			
			$html .= '
				</style>
			';
		}
		
		return $html;
	}
	
	public function add_style($style)
	{		
		array_push($this->style, $style);
	}
	
	public function get_script()
	{
		$html = '';
		
		if ( !empty($this->script) )
		{
			$html = '
				<script type="text/javascript">
			';
			
			for ($i = 0; $i < count($this->script); $i++)
			{
				$html .= $this->script[$i];
			}
			
			$html .= '
				</script>
			';
		}
		
		return $html;
	}
	
	public function add_script($script)
	{		
		array_push($this->script, $script);
	}
	
	public function add_block($block_name, $parent_element = 'body')
	{
		if ( !$this->block_exists($block_name) )
		{
			$block = array(
			
				'name' => $block_name,
				'parent_element' => $parent_element,
				'content' => ''
				
			);

			// Store the block by name
			$this->blocks[$block_name] = $block;
		}
		else
		{
			throw new \Exception('Block "' . $block_name . '" already exists.');
		}
	}
	
	public function get_block_content($block_name = '', $parent_element = 'body')
	{
		$html = '';
		
		// Return content of all the blocks for a given parent element
		if ( empty($block_name) )
		{
			foreach ($this->blocks as $block)
			{
				if ( $block['parent_element'] == $parent_element )
				{
					$html .= $block['content'];	
				}
				
			}
		}
		// Return content of a specific block regardless of parent element
		else
		{
			if ( $this->block_exists($block_name) )
			{
				$html = $this->blocks[$block_name]['content'];
			}
			else
			{
				throw new \Exception('No such block "' . $block_name . '" exists.');
			}
		}
		
		return $html;
	}
	
	private function block_exists($block_name)
	{
		$exists = array_key_exists($block_name, $this->blocks);
		
		return $exists;
	}
	
	public function add_content($content, $block_name)
	{
		if ( $this->block_exists($block_name) )
		{
			$this->blocks[$block_name]['content'] .= $content;
		}
		else
		{
			throw new \Exception('No such block "' . $block_name . '" exists.');
		}
	}
	
	public function replace_content($content, $block_name)
	{
		if ( $this->block_exists($block_name) )
		{
			$this->blocks[$block_name]['content'] = $content;
		}
		else
		{
			throw new \Exception('No such block "' . $block_name . '" exists.');
		}
	}

	public function render($block_name = '', $render = true)
	{
		$html = '';
		
		if ( empty($block_name) )
		{		
			$html = '
				<!doctype html>
				
				<html lang="' . $this->get_lang() . '">
				
				<head>					
					<meta charset="utf-8" />

					<title>' . $this->get_title() . '</title>
					
					' . $this->get_meta() . '
					
					' . $this->get_css() . '
					
					' . $this->get_javascript() . '
					
					' . $this->get_style() . '
					
					' . $this->get_script() . '

					' . $this->get_block_content('', 'head') . '
				</head>
				
				<body>
				
				' . $this->get_block_content('', 'body') . '
				
				</body>			
				
				</html>
			';
		}
		else // Render partial. Avoids wrapper HTML.
		{
			$html = $this->get_block_content($block_name);
		}
		
		if ($render)
		{
			echo $html;
		}
		else
		{
			return $html;
		}
	}
}

?>