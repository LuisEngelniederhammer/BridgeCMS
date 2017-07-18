<?php

namespace system
{

	require_once __DIR__ . '/core.class.php';
	require_once __DIR__ . '/pagemgr.class.php';
	require_once __DIR__ . '/access.class.php';


	class menuManager
	{

		private $pages;

		private function __construct()
		{
		}

		static public function init($isBacked = false)
		{
			$tmp = new self ();
			$tmp->pages;
			if (! $isBacked)
			{
				$tmp->pages = pageManager::init ()->getAllPages ();
			}
			else
			{
				$tmp->pages = pageManager::init ()->getBackendPages();
			}
			return $tmp;
		}

		public final function setActive($pageName = "Home")
		{
			if (isset ( $this->pages [$pageName] ))
			{
				$this->pages [$pageName] ['active'] = true;
				return $this;
			}
			return $this;
		}

		private function displayMenuDropdown()
		{
		}

		public function displayMenu($isBackend = false)
		{
			// var_dump ( $this->pages );
			echo ('<ul>');
			if (! $isBackend)
			{
				foreach ( $this->pages as $key => $value )
				{
					$css_class = "";
					if ($this->pages [$key] ['active'])
					{
						$css_class = "active";
					}
					if (is_array ( $this->pages [$key] ['restriction'] ))
					{
						// restrictions have to apply to this element
						foreach ( $this->pages [$key] ['restriction'] as $role )
						{
							access::setup ()->requireRole ( RoleManager::init ()->getRoleByName ( $role ) );
						}
					}
					else
					{
						// no restrictions for this element
						echo ('<li><a href="' . SYSTEM_ROOT_HOST . "/index.php?" . $this->pages [$key] ['uid'] . '" class="' . $css_class . '">' . $this->pages [$key] ['name'] . '</a></li>');
					}
				}
			}
			else
			{
				foreach ( $this->pages as $key => $value )
				{
					$css_class = "";
					if ($this->pages [$key] ['active'])
					{
						$css_class = "active";
					}
					// no restrictions for this element
					echo ('<li><a href="' . SYSTEM_ROOT_HOST . "/admin/" . $this->pages [$key] ['url'] . '" class="' . $css_class . '">' . $this->pages [$key] ['name'] . '</a></li>');
				}
				
			}
			echo ('</ul>');
		}
	
	}

}