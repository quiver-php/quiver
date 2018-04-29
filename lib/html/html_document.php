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
	
	public function __construct(string $title)
	{
		$this->set_title($title);
	}
	
	public function get_lang()
	{
		return $this->lang;
	}
	
	public function set_lang(string $lang = 'en')
	{
		$this->lang = $lang;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function set_title(string $title)
	{
		$this->title = $title;
	}
	
	public function get_meta()
	{
		$html = '';
		
		foreach ($this->meta as $meta)
		{
			$html .= '
				<meta name="' . $meta['name'] . '" content="' . $meta['content'] . '">
			';
		}
		
		return $html;
	}
	
	public function add_meta(string $name, string $content)
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
		
		foreach ($this->css as $css)
		{
			$html .= '
				<link href="' . $css['href'] . '" media="' . $css['media'] . '" rel="stylesheet" type="text/css">
			';
		}
		
		return $html;
	}
	
	public function add_css(string $href, string $media = 'all')
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
		
		foreach ($this->javascript as $javascript)
		{
			$html .= '
				<script src="' . $javascript['src'] . '" type="text/javascript"></script>
			';
		}
		
		return $html;
	}
	
	public function add_javascript(string $src)
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
			
			foreach ($this->style as $style)
			{
				$html .= $style;
			}
			
			$html .= '
				</style>
			';
		}
		
		return $html;
	}
	
	public function add_style(string $style)
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
			
			foreach ($this->script as $script)
			{
				$html .= $script;
			}
			
			$html .= '
				</script>
			';
		}
		
		return $html;
	}
	
	public function add_script(string $script)
	{		
		array_push($this->script, $script);
	}
	
	public function add_block(string $block_name, string $parent_element = 'body')
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
	
	public function get_block_content(string $block_name = '', string $parent_element = 'body')
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
	
	private function block_exists(string $block_name)
	{
		$exists = array_key_exists($block_name, $this->blocks);
		
		return $exists;
	}
	
	public function add_content(string $content, string $block_name)
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
	
	public function replace_content(string $content, string $block_name)
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

	public function render(string $block_name = '')
	{
		$html = '';
		
		if ( empty($block_name) )
		{		
			$html = '
				<!doctype html>
				
				<html lang="' . $this->get_lang() . '">
				
				<head>					
					<meta charset="utf-8">

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
		
		return $html;
	}
}
