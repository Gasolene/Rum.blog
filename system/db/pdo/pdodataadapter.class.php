<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\DB\MySQLi;
	use \System\DB\DataAdapter;


	/**
	 * Represents an open connection to a MySQL database using the mysqli driver
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	final class PDODataAdapter extends DataAdapter
	{
		/**
		 * Handle to the open PDO object
		 * @var PDO
		 */
		private $pdo;


		/**
		 * opens a connection to a mysql database
		 * @return bool						TRUE if successfull
		 */
		public function open()
		{
			if( !$this->pdo )
			{
				try
				{
					$this->pdo = new PDO($this->args['dsn'], $this->args['username'], $this->args['password']);
				}
				catch(\PDOException $e)
				{
					throw new \System\DB\DataAdapterException("Could not connect to database " . $e->getMessage());
				}
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection already open");
			}
		}


		/**
		 * closes an open connection
		 *
		 * @return bool					true if successfull
		 */
		public function close()
		{
			if( $this->pdo )
			{
				$this->pdo = null;
				return true;
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection already closed");
			}
		}


		/**
		 * returns true if a connection to a datasource is currently open
		 *
		 * @return bool					true if connection open
		 */
		public function opened()
		{
			return (bool)$this->pdo;
		}


		/**
		 * Executes a query procedure on the current connection and return the result
		 *
		 * @param  string		$query		sql query
		 * @return resource
		 */
		protected function query( $query )
		{
			if( $this->pdo )
			{
				$result = $this->pdo->query( $query );

				if( !$result )
				{
					throw new \System\DB\DatabaseException($this->pdo->errorInfo());
				}

				return $result;
			}
			else
			{
				throw new \System\DB\DataAdapterException("PDO resource in not a valid PDO object");
			}
		}


		/**
		 * fills a DataSet object with the current DataAdapter
		 *
		 * @param  DataSet	&$ds		empty DataSet object
		 * @return void
		 */
		public function fill( \System\DB\DataSet &$ds )
		{
			if( $this->link )
			{
				$result = $this->runQuery( $ds->source );

				if( $result )
				{
					$fields = array();
					$fieldMeta = array();
					$fieldCount = \mysqli_num_fields($result);

					for($i=0; $i < $fieldCount; $i++)
					{
						$meta = \mysqli_fetch_field_direct($result, $i);

						$fields[] = $meta->name;
						$fieldMeta[] = $this->getColumnSchema($meta);
					}

					$rows = array();
					$rowCount = $result->rowCount();
					for( $row=0; $row < $rowCount; $row++ )
					{
						$rows[] = \mysqli_fetch_assoc( $result );
					}

					$ds->setTable($meta->table);
					$ds->setFieldMeta( $fieldMeta );
					$ds->setFields( $fields );
					$ds->setRows( $rows );

					\mysqli_free_result( $result );
				}
				else
				{
					throw new \System\DB\DatabaseException(\mysqli_error($this->link));
				}
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection is closed");
			}
		}


		/**
		 * builds a DataBaseSchema object
		 *
		 * @return DatabaseSchema
		 */
		public function buildSchema()
		{
			$databaseProperties = array();
			$tableSchemas = array();

			$tables = $this->runQuery( "SHOW TABLES" );

			while($table = \mysqli_fetch_array($tables, MYSQL_NUM))
			{
				$i=0;
				$tableProperties = array('name'=>$table[0]);
				$foreignKeys = array();
				$columnSchemas = array();

				$columns = $this->runQuery( "SELECT * FROM `{$table[0]}` WHERE 0" );
				while($i < \mysqli_num_fields($columns))
				{
					$meta = \mysqli_fetch_field_direct($columns, $i);

					if($meta->flags & 2)
					{
						$tableProperties['primaryKey'] = $meta->name;
					}

					$columnSchemas[] = $this->getColumnSchema($meta);
					$i++;
				}

				$tableSchemas[] = new \System\DB\TableSchema($tableProperties, $foreignKeys, $columnSchemas);
			}

			return new \System\DB\DatabaseSchema($databaseProperties, $tableSchemas);
		}


		/**
		 * creats a TableSchema object
		 *
		 * @return DatabaseSchema
		 */
		public function addTableSchema( \System\DB\TableSchema &$tableSchema )
		{
			$columns = "";
			foreach($tableSchema->columnSchemas as $columnSchema)
			{
				$primaryKeys = array();
				$indexKeys = array();
				$uniqueKeys = array();
				$type = "";

				if($columnSchema->integer)
				{
					$type = "INT({$columnSchema->length})";
				}
				elseif($columnSchema->real)
				{
					$type = "DOUBLE({$columnSchema->length})";
				}
				elseif($columnSchema->boolean)
				{
					$type = "TINYINT(1)";
				}
				elseif($columnSchema->year)
				{
					$type = "YEAR";
				}
				elseif($columnSchema->date)
				{
					$type = "DATE";
				}
				elseif($columnSchema->time)
				{
					$type = "TIME";
				}
				elseif($columnSchema->datetime)
				{
					$type = "DATETIME";
				}
				elseif($columnSchema->blob)
				{
					$type = "MEDIUMBLOB";
				}
				else
				{
					$type = "VARCHAR({$columnSchema->length}) CHARACTER SET {$this->charset} COLLATE {$this->collation}";
				}

				if($columns) $columns .= ",\n	";
				$columns .= "`{$columnSchema->name}` {$type}".($columnSchema->notNull?' NOT NULL':'').($columnSchema->autoIncrement?' AUTO_INCREMENT':'');

				if($columnSchema->primaryKey)
				{
					$primaryKeys[] = $columnSchema->name;
				}

				if($columnSchema->foreignKey)
				{
					$indexKeys[] = $columnSchema->name;
				}

				if($columnSchema->unique)
				{
					$uniqueKeys[] = $columnSchema->name;
				}
			}

			if($primaryKeys)
			{
				$column = "";
				foreach($primaryKeys as $primaryKey)
				{
					if($column) $column .= ", ";
					$column .= "`{$primaryKey}`";
				}

				$columns .= ",\n	PRIMARY KEY ({$column})";
			}

			if($indexKeys)
			{
				$column = "";
				foreach($indexKeys as $indexKey)
				{
					if($column) $column .= ", ";
					$column .= "`{$indexKey}`";
				}

				$columns .= ",\n	INDEX ({$column})";
			}

			if($uniqueKeys)
			{
				$column = "";
				foreach($uniqueKeys as $uniqueKey)
				{
					if($column) $column .= ", ";
					$column .= "`{$uniqueKey}`";
				}

				$columns .= ",\n	UNIQUE ({$column})";
			}

			$this->execute("CREATE TABLE `{$tableSchema->name}` (\n	{$columns}\n);");
		}


		/**
		 * alters a TableSchema object
		 *
		 * @return DatabaseSchema
		 */
		public function alterTableSchema( \System\DB\TableSchema &$tableSchema )
		{
			throw new \System\Base\MethodNotImplementedException();
		}


		/**
		 * drops a TableSchema object
		 *
		 * @return DatabaseSchema
		 */
		public function dropTableSchema( \System\DB\TableSchema &$tableSchema )
		{
			throw new \System\Base\MethodNotImplementedException();
		}


		/**
		 * attempt to insert a record into the datasource
		 *
		 * @param  DataSet	&$ds		empty DataSet object
		 * @return void
		 */
		public function insert( \System\DB\DataSet &$ds )
		{
			if( $this->link )
			{
				$tableSchema = $ds->dataAdapter->getSchema()->seek($ds->table);
				$this->queryBuilder()
					->insertInto($ds->table, $ds->fields)
					->values($ds->row)
					->runQuery();

				$ds[$tableSchema->primaryKey] = $this->getLastInsertId();
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection is closed");
			}
		}


		/**
		 * attempt to update a record in the datasource
		 *
		 * @param  DataSet	&$ds		empty DataSet object
		 * @return void
		 */
		public function update( \System\DB\DataSet &$ds )
		{
			if( $this->link )
			{
				$tableSchema = $ds->dataAdapter->getSchema()->seek($ds->table);

				$this->queryBuilder()
					->update($ds->table)
					->setColumns($ds->table, $ds->fields, $ds->row)
					->where($ds->table, $tableSchema->primaryKey, '=', $ds[$tableSchema->primaryKey])
					->runQuery();
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection is closed");
			}
		}


		/**
		 * attempt to delete a record in the datasource
		 *
		 * @param  DataSet	&$ds		empty DataSet object
		 * @return void
		 */
		public function delete( \System\DB\DataSet &$ds )
		{
			if( $this->link )
			{
				$tableSchema = $ds->dataAdapter->getSchema()->seek($ds->table);
				$this->queryBuilder()
					->delete()
					->from($ds->table)
					->where($ds->table, $tableSchema->primaryKey, '=', $ds[$tableSchema->primaryKey])
					->runQuery();
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection is closed");
			}
		}


		/**
		 * creats a QueryBuilder object
		 *
		 * @return MySQLQueryBuilder
		 */
		public function queryBuilder()
		{
			return new MySQLiQueryBuilder($this);
		}


		/**
		 * creats a Transaction object
		 *
		 * @return MySQLTransaction
		 */
		public function beginTransaction()
		{
			return new MySQLiTransaction($this);
		}


		/**
		 * return id of last record inserted
		 *
		 * @return int
		 */
		public function getLastInsertId()
		{
			if( $this->link )
			{
				return \mysqli_insert_id( $this->link );
			}
			else
			{
				throw new \System\DB\DataAdapterException("MySQLi resource is not a valid link identifier");
			}
		}


		/**
		 * return affected rows
		 *
		 * @return int
		 */
		public function getAffectedRows()
		{
			if( $this->link )
			{
				return mysqli_affected_rows( $this->link );
			}
			else
			{
				throw new \System\DB\DataAdapterException("MySQLi resource is not a valid link identifier");
			}
		}


		/**
		 * Returns escaped string
		 *
		 * @param  string $unescaped_string		String to escape
		 * @return string						Escaped string
		 */
		public function escapeString( $unescaped_string )
		{
			return \mysqli_real_escape_string( $this->link, $unescaped_string );
		}


		/**
		 * returns a populated ColumnSchema object
		 * @param object $meta
		 * @return \System\DB\ColumnSchema 
		 */
		private function getColumnSchema($meta)
		{
			return new \System\DB\ColumnSchema(array(
				'name' => $meta->name,
				'table' => $meta->table,
				'type' => $meta->type,
				'length' => $meta->length,
				'notNull' => $meta->flags & 1,
				'primaryKey' => $meta->flags & 2,
				'multipleKey' => $meta->flags & 16384,
				'foreignKey' => FALSE,
				'unique' => $meta->flags & 4,
				'numeric' => $meta->flags & 32768,
				'blob' => $meta->flags & 16,
				'string' => $meta->type === MYSQLI_TYPE_STRING || $meta->type === MYSQLI_TYPE_VAR_STRING,
				'integer' => $meta->type === MYSQLI_TYPE_INT24 || $meta->type === MYSQLI_TYPE_LONG || $meta->type === MYSQLI_TYPE_LONGLONG || $meta->type === MYSQLI_TYPE_BIT || $meta->type === MYSQLI_TYPE_TINY,
				'real' => $meta->type === MYSQLI_TYPE_DECIMAL || $meta->type === MYSQLI_TYPE_DOUBLE || $meta->type === MYSQLI_TYPE_FLOAT || $meta->type === MYSQLI_TYPE_NEWDECIMAL,
				'year' => $meta->type === MYSQLI_TYPE_YEAR,
				'date' => $meta->type === MYSQLI_TYPE_DATE,
				'time' => $meta->type === MYSQLI_TYPE_TIME,
				'datetime' => $meta->type === MYSQLI_TYPE_DATETIME,
				'boolean' => $meta->type === MYSQLI_TYPE_BIT || $meta->type === MYSQLI_TYPE_TINY,
				'autoIncrement' => $meta->flags & 512,
				'binary' => $meta->flags & 128));
		}
	}
?>