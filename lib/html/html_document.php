<?php

// ============================================================
// HTML Document
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - https://danielcarvalho.com/
//
// This constitutes the only view code in Quiver. It is
// experimental and therefore subject to frequent changes.
// It is not meant to be a strict nor complete implementation
// of the HTML spec, and is probably far from the most
// performant way of approaching this problem.
//
// The aim of this class is to produce a clean, consistently
// formatted, and structured HTML document where some of the
// needless toil is done for you.
//
// Use at your own risk.
// ============================================================

namespace quiver\html;

class html_document
{
	private const TEMPLATE_HTML_DOCUMENT = <<<END
			<!doctype html>

			<html lang=":lang">

			<head>
				<meta charset="utf-8">

				<title>:title</title>

				:head
			</head>

			<body>
			:body
			</body>

			</html>
			END;

	private const TAG_END = "\n\t";

	private string $lang = 'en';
	private string $title;
	private $meta = [];
	private $link = [];
	private $javascript = [];
	private $style = [];
	private $script = [];
	private $blocks = [];

	public function __construct(string $title = '')
	{
		$this->set_title($title);
	}

	public function get_lang(): string
	{
		return $this->lang;
	}

	public function set_lang(string $lang = 'en')
	{
		$this->lang = $lang;
	}

	public function get_title(): string
	{
		return $this->title;
	}

	public function set_title(string $title)
	{
		$this->title = $title;
	}

	public function get_meta(): string
	{
		$html = '';

		$is_first = true;

		foreach ($this->meta as $meta)
		{
			$html .= $this->get_comment( $meta['comment'], $is_first );

			if ( array_key_exists('name', $meta) )
				$html .= self::fill_template('<meta name="::name" content="::content">', $meta);
			else
				$html .= self::fill_template('<meta property="::property" content="::content">', $meta);

			$html .= self::TAG_END;

			$is_first = false;
		}

		return $html;
	}

	public function add_meta(string $name, string $content, ?string $comment = null)
	{
		$meta = [
			'name'    => $name,
			'content' => $content,
			'comment' => $comment
		];

		array_push($this->meta, $meta);
	}

	public function add_meta_with_property(string $property, string $content, ?string $comment = null)
	{
		$meta = [
			'property' => $property,
			'content'  => $content,
			'comment'  => $comment
		];

		array_push($this->meta, $meta);
	}

	public function get_link(): string
	{
		$template = '<link::attributes>' . self::TAG_END;

		$html = '';

		$is_first = true;

		foreach ($this->link as $link)
		{
			// Add the comment header and then remove it from the array so that
			// it doesn't get added as an attribute
			$html .= $this->get_comment( $link['comment'], $is_first );
			array_pop($link);

			$attributes = [
				'attributes' => $this->get_attributes($link)
			];

			$html .= self::fill_template($template, $attributes);

			$is_first = false;
		}

		return $html;
	}

	public function add_link(string $rel, string $href, array $attributes = [], ?string $comment = null)
	{
		$link = [
			'rel' => $rel,
			'href' => $href,
			...$attributes,
			'comment' => $comment
		];

		array_push($this->link, $link);
	}

	public function get_javascript(): string
	{
		$template = '::comment<script type="text/javascript" src="::src"::attributes></script>' . self::TAG_END;

		// Define which attributes are the boolean type
		$attribute_types = [
			'nomodule' => true,
			'async' => true,
			'defer' => true
		];

		$html = '';

		$javascript_total = count($this->javascript);

		for ($i = 0; $i < $javascript_total; $i++)
		{
			// Get element entry
			$javascript = $this->javascript[$i];

			// Build element comment
			$html_comment = $this->get_comment( $javascript['comment'], $i == 0 );

			// Build element attributes
			$html_attributes = $this->get_attributes( $javascript['attributes'], $attribute_types );

			// Composite entire element
			$html .= $this->fill_template($template, [
				'src' => $javascript['src'],
				'attributes' => $html_attributes,
				'comment' => $html_comment
			]);
		}

		return $html;
	}

	public function add_javascript(string $src, array $attributes = [], ?string $comment = null)
	{
		$javascript = [
			'src' => $src,
			'attributes' => $attributes,
			'comment' => $comment
		];

		array_push($this->javascript, $javascript);
	}

	public function get_style(): string
	{
		$html = '';

		if ( !empty($this->style) )
		{
			foreach ($this->style as $style)
				$html .= $style;

			$html = '<style type="text/css">' . $html . '</style>';
		}

		return $html;
	}

	public function add_style(string $style)
	{
		array_push($this->style, $style);
	}

	public function get_script(): string
	{
		$html = '';

		if ( !empty($this->script) )
		{
			foreach ($this->script as $script)
				$html .= $script;

			$html = '<script type="text/javascript">' . $html . '</script>';
		}

		return $html;
	}

	public function add_script(string $script)
	{
		array_push($this->script, $script);
	}

	private function get_attributes(array $attributes = [], array $attribute_types = []): string
	{
		$html = '';

		$attribute_types_supplied = !empty($attribute_types);

		foreach ($attributes as $name => $value)
		{
			// Is there an attribute type defined for this attribute?
			if ( $attribute_types_supplied && array_key_exists($name, $attribute_types) )
			{
				$attribute_type = $attribute_types[$name];

				// Is it a boolean attribute?
				if ( is_bool($attribute_type) )
				{
					// Is it true (should it be added)?
					if ($value === true)
						$html .= " $name";

					continue;
				}
			}

			// Build the attribute as a standard string attribute
			$html .= <<<END
				 $name="$value"
				END;
		}

		return $html;
	}

	private function get_comment(?string $comment = null, bool $is_first = false): string
	{
		if ($comment === null)
			return '';

		if ( empty($comment) )
		{
			$template = '::newline';
		}
		else if ($is_first)
		{
			$template = '<!-- ::comment -->::newline';
		}
		else
		{
			$template = '::newline<!-- ::comment -->::newline';
		}

		$data = [
			'newline' => self::TAG_END,
			'comment' => $comment
		];

		return self::fill_template($template, $data);
	}

	public function add_block(string $block_name, string $parent_element = 'body')
	{
		if ( !$this->block_exists($block_name) )
		{
			$block = [
				'name' => $block_name,
				'parent_element' => $parent_element,
				'content' => ''
			];

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

	private function get_head(): string
	{
		// Collate all the head elements
		$elements = [
			':meta'       => $this->get_meta(),
			':link'       => $this->get_link(),
			':javascript' => $this->get_javascript(),
			':style'      => $this->get_style(),
			':script'     => $this->get_script()
		];

		$html = '';

		// Avoid adding erroneous whitespaces by adding only the elements that
		// contain HTML
		foreach ($elements as $element => $element_html)
		{
			if ( !empty($element_html) )
				$html .= $element_html . self::TAG_END;
		}

		return rtrim($html);
	}

	public function render(string $block_name = ''): string
	{
		$html = '';

		if ( empty($block_name) )
		{
			$html_inner = [
				':lang'  => $this->get_lang(),
				':title' => $this->get_title(),
				':head'  => $this->get_head(),
				':body'  => $this->get_block_content('', 'body')
			];

			$html = strtr(self::TEMPLATE_HTML_DOCUMENT, $html_inner);
		}
		else // Render partial. Avoids wrapper HTML.
		{
			$html = $this->get_block_content($block_name);
		}

		return $html;
	}

	public static function tokenize_array(array $article, string $prefix = '::'): array
	{
		$article_tokenized = [];

		// Populate a new array where each key is prefixed
		foreach ($article as $key => $value)
			$article_tokenized[ $prefix . $key ] = $value;

		return $article_tokenized;
	}

	public static function fill_template(string $template, array $data): string
	{
		// Tokenize the array keys so that it can be used as input data for templates
		$data_tokenized = self::tokenize_array($data);

		return strtr($template, $data_tokenized);
	}
}
