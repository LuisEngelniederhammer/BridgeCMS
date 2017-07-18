<?php

namespace system;

require_once 'core.class.php';

class TemplateController
{
	private $debug = SYSTEM_ROOT . '/templates/default.json';
	private $templateArray,$templateID;
	const DATA_PHP = 'php';
	const DATA_HTML = 'html';
	const DATA_FILE = 'file';
	const TEMPLATE_HEAD = 'TPL_HEAD';
	const TEMPLATE_BODY = 'TPL_BODY';
	
	public final function __construct($templateID)
	{
		$this->templateID = $templateID;
	}
	public function getBody()
	{
		// check if template exsists
		if ($this->templateID == 'debug')
		{
			$content = file_get_contents ( $this->debug );
			$this->templateArray = json_decode ( $content, true );
			
			foreach ( $this->templateArray ['head'] as $id => $header )
			{
				if ($header ['typ'] == TemplateController::DATA_PHP)
				{
					eval ( $header ['data'] );
				}
				elseif ($header ['typ'] == TemplateController::DATA_FILE)
				{
					include ($header ['data']);
				}
				else
				{
					echo $header ['data'];
				}
			}
			
			foreach ( $this->templateArray ['body'] as $id => $element )
			{
				// create top element
				echo "<div name='{$id}' id='{$element['id']}' class='{$element['class']}'>";
				
				// check for sub elements (only one level for now)
				if (is_array ( $element ['data'] ))
				{
					foreach ( $element ['data'] as $SubID => $SubElement )
					{
						// create sub element
						echo "<div id='{$SubElement['id']}' class='{$SubElement['class']}'>";
						// sub element content
						// echo "ID: {$SubID} | Typ: {$SubElement['typ']} | Data: {$SubElement['data']}";
						if ($SubElement ['typ'] == TemplateController::DATA_PHP)
						{
							eval ( $SubElement ['data'] );
						}
						elseif ($SubElement ['typ'] == TemplateController::DATA_FILE)
						{
							include ($SubElement ['data']);
						}
						else
						{
							echo $SubElement ['data'];
						}
						echo "</div>";
					}
				}
				else
				{
					// if no sub element found, put top content
					// echo "ID: {$id} | Typ: {$element['typ']} | Data: {$element ['data']}";
					if ($element ['typ'] == TemplateController::DATA_PHP)
					{
						eval ( $element ['data'] );
					}
					elseif ($element ['typ'] == TemplateController::DATA_FILE)
					{
						include ($element ['data']);
					}
					else
					{
						echo $element ['data'];
					}
				}
				// close top element
				echo "</div>";
			}
		}
	}
}