<?php

namespace system
{

	require_once __DIR__ . '/core.class.php';
	use \PDO;


	class databaseManager
	{

		protected $dbHND;

		protected $currentSQLStatement;

		protected $bind;

		public function __construct()
		{
			// . ';port=' . config::port
			if ($this->dbHND = new PDO ( 'mysql:host=' . config::host . ';dbname=' . config::database . ';port=' . config::port, config::user, config::password ))
			{
				$this->bind = null;
			}
			else
			{
				return false;
			}
		}

		static public function getDB()
		{
			$tmpInstance = new self ();
			return $tmpInstance;
		}
		
		//FIXME have the binding values as an optional array parameter
		public function prepareSQL(string $sqlQueryString = null, array $bindingValues = array())
		{
			if ($sqlQueryString === null)
			{
				$this->currentSQLStatement === false;
				return false;
			}
			elseif (!empty ( $bindingValues ))
			{
				if ($this->currentSQLStatement = $this->dbHND->prepare ( $sqlQueryString ))
				{
					$this->bind = $bindingValues;
					return $this;
				}
				else
				{
					throw new \Exception ( "Could not prepare SQL Statement, " . print_r ( $this->dbHND->errorInfo () ) );
					return false;
				}
			}
			else
			{
				if ($this->currentSQLStatement = $this->dbHND->prepare ( $sqlQueryString ))
				{
					return $this;
				}
				else
				{
					throw new \Exception ( "Could not prepare SQL Statement, " . print_r ( $this->dbHND->errorInfo () ) );
					return false;
				}
			}
		}

		public function executeSQL()
		{
			if ($this->bind != null)
			{
				if ($this->currentSQLStatement->execute ( $this->bind ))
				{
					return true;
				}
				else
				{
					throw new \Exception ( "Could not execute SQL Query:{ " . $this->currentSQLStatement->errorInfo () [2] . " }" );
					return false;
				}
			}
			else
			{
				if ($this->currentSQLStatement->execute ())
				{
					return true;
				}
				else
				{
					throw new \Exception ( "Could not execute SQL Query:{ " . $this->currentSQLStatement->errorInfo () [2] . " }" );
					return false;
				}
			}
		}

		public function fetchArray($fetchType = PDO::FETCH_ASSOC)
		{
			if (! $this->currentSQLStatement)
			{
				return false;
			}
			elseif ($this->bind != null)
			{
				$this->currentSQLStatement->execute ( $this->bind );
				return $this->currentSQLStatement->fetch ( $fetchType);
			}
			else
			{
				$this->currentSQLStatement->execute ();
				return $this->currentSQLStatement->fetch ( $fetchType);
			}
		}

		public function fetchAll($fetchType = PDO::FETCH_ASSOC)
		{
			if (! $this->currentSQLStatement)
			{
				return false;
			}
			elseif ($this->bind != null)
			{
				$this->currentSQLStatement->execute ( $this->bind );
				return $this->currentSQLStatement->fetchAll ( $fetchType);
			}
			else
			{
				$this->currentSQLStatement->execute ();
				return $this->currentSQLStatement->fetchAll ( $fetchType);
			}
		}

		public function getRowCount()
		{
			return $this->currentSQLStatement->rowCount ();
		}
	
	}

}
?>