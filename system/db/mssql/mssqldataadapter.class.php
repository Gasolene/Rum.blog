<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\DB\MSSQL;
	use \System\DB\DataAdapter;


	/**
	 * Represents an open connection to a MySQL database
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	class MSSQLDataAdapter extends SQLDataAdapter
	{
		/**
		 * fetches DataSet from database string using source string
		 *
		 * @param  DataSet	&$ds		empty DataSet object
		 * @return void
		 */
		public function fill( DataSet &$ds )
		{
			if( $this->link )
			{
				$source = '';
				if( $ds->source instanceof QueryBuilder )
				{
					$source = $this->getQuery( $ds->source );
				}
				elseif( !strstr( strtoupper($ds->source), 'SELECT' ) &&
					!strstr( strtoupper($ds->source), 'DESCRIBE' ) &&
					!strstr( strtoupper($ds->source), 'SHOW' ))
				{
					// source is table name
					$source = 'SELECT * FROM [' . $ds->source . ']';
				}
				else
				{
					$source = $ds->source;
				}

				// establish link to db resource
				// replaced mysql_query with $this->execute
				$result = $this->executeInternal( $source );
				$fields = array();
				if( $result )
				{
					// get table of mysqli types
					$mssql_type = array();
					// int
					$mssql_type[-7]							= 'BIT';
					$mssql_type[-6]							= 'TINYINT';
					$mssql_type[5]							= 'SMALLINT';
					$mssql_type[4]							= 'INT';
					$mssql_type[-5]							= 'BIGINT';
					$mssql_type[-2]							= 'TIMESTAMP';
					$mssql_type[-11]						= 'UNIQUEIDENTIFIER';

					$mssql_type[2]							= 'NUMERIC';
					$mssql_type[3]							= 'DECIMAL';
					$mssql_type[6]							= 'FLOAT';
					$mssql_type[7]							= 'REAL';

					$mssql_type[91]							= 'DATE';
					$mssql_type[93]							= 'DATETIME';
					$mssql_type[-155]						= 'DATETIMEOFFSET';
					$mssql_type[-154]						= 'TIME';

					$mssql_type[1]							= 'CHAR';
					$mssql_type[-8]							= 'NCHAR';
					$mssql_type[12]							= 'VARCHAR';
					$mssql_type[-9]							= 'NVARCHAR';
					$mssql_type[-1]							= 'TEXT';
					$mssql_type[-10]						= 'NTEXT';

					$mssql_type[-2]							= 'BINARY';
					$mssql_type[-3]							= 'VARBINARY';
					$mssql_type[-4]							= 'IMAGE';
					$mssql_type[-151]						= 'UDT';
					$mssql_type[-152]						= 'XML';

					/*
					 * create field objects
					 *
					 * this code loops through fields of resultset
					 * checks is field is primary key, if so, set the primary key field name
					 * then adds all field names to an array
					 *
					 * cannot be used when resultset is emtpy (mysql_num_fields wil fail)
					 */
					$colcount = sqlsrv_num_fields( $result );

					// set table property
					$ds->properties['table'] = $this->getTableFromSQL( $source );

					for( $i=0; $i < $colcount; $i++ )
					{
						$fieldMeta = sqlsrv_field_metadata( $result );
						$field = $this->getField($ds->properties['table'], $fieldMeta[$i]["Name"]);

						// mssql field info
						$field->length	      =        $fieldMeta[$i]["Size"];
						$field->type          =        $mssql_type[$fieldMeta[$i]["Type"]];

						// mssql column types
						$field->string        = (bool) (( $fieldMeta[$i]["Type"] === 1 ) ||
														( $fieldMeta[$i]["Type"] === -8 ) ||
														( $fieldMeta[$i]["Type"] === 12 ) ||
														( $fieldMeta[$i]["Type"] === -9 ) ||
														( $fieldMeta[$i]["Type"] === -1 ) ||
														( $fieldMeta[$i]["Type"] === -10 ));

						$field->integer       = (bool) (( $fieldMeta[$i]["Type"] === -7 ) ||
														( $fieldMeta[$i]["Type"] === -6 ) ||
														( $fieldMeta[$i]["Type"] === 5 ) ||
														( $fieldMeta[$i]["Type"] === 4 ) ||
														( $fieldMeta[$i]["Type"] === -5 ) ||
														( $fieldMeta[$i]["Type"] === -2 ) ||
														( $fieldMeta[$i]["Type"] === -11 ));

						$field->real          = (bool) (( $fieldMeta[$i]["Type"] === 2 ) ||
														( $fieldMeta[$i]["Type"] === 3 ) ||
														( $fieldMeta[$i]["Type"] === 6 ) ||
														( $fieldMeta[$i]["Type"] === 7 ));

						$field->date          = (bool)  ( $fieldMeta[$i]["Type"] === 91 );
						$field->time          = (bool)  ( $fieldMeta[$i]["Type"] === -154 );
						$field->datetime      = (bool)  ( $fieldMeta[$i]["Type"] === 93 );

						$field->blob          = (bool) (( $fieldMeta[$i]["Type"] === -2 ) ||
														( $fieldMeta[$i]["Type"] === -3 ) ||
														( $fieldMeta[$i]["Type"] === -4 ));

						$field->numeric       = (bool) (( $fieldMeta[$i]["Type"] === -7 ) ||
														( $fieldMeta[$i]["Type"] === -6 ) ||
														( $fieldMeta[$i]["Type"] === 5 ) ||
														( $fieldMeta[$i]["Type"] === 4 ) ||
														( $fieldMeta[$i]["Type"] === -5 ) ||
														( $fieldMeta[$i]["Type"] === -2 ) ||
														( $fieldMeta[$i]["Type"] === -11 ) ||
														( $fieldMeta[$i]["Type"] === 2 ) ||
														( $fieldMeta[$i]["Type"] === 3 ) ||
														( $fieldMeta[$i]["Type"] === 6 ) ||
														( $fieldMeta[$i]["Type"] === 7 ));

						$field->boolean       = (bool)  ( $fieldMeta[$i]["Type"] === -7 );

						// mssql field flags
						$field->notNull       = (bool)  ( !$fieldMeta[$i]["Nullable"] );
						//TODO:$field->unique       = (bool) ( !$fieldMeta["Nullable"] );

						$field->binary        = (bool) (( $fieldMeta[$i]["Type"] === -2 ) ||
														( $fieldMeta[$i]["Type"] === -3 ));

						// add field to field collection
						$fields[] = $field;
					}

					/*
					 * create record objects
					 *
					 * this code loops through all rows and fields
					 * then creates the following array...
					 * DataSet[row number][field name] = value
					 */

					$rowcount = sqlsrv_num_rows( $result );
					$rows = array();
					for( $row=0; $row < $rowcount; $row++ )
					{
						// add row to DataSet
						$rows[] = sqlsrv_fetch( $result );
					}

					// set rows
					$ds->setRows( $rows );

					// cleanup
					sqlsrv_free_stmt( $result );
				}
				else
				{
					throw new \System\DB\DatabaseException(implode(' ', array_pop(sqlsrv_errors())));
				}
			}
			else
			{
				throw new \System\Base\InvalidOperationException("connection is closed");
			}
		}


		/**
		 * opens a connection to a mysql database
		 * @return bool						TRUE if successfull
		 */
		public function open()
		{
			if( !$this->link )
			{
				if( isset( $this->args['server'] ) &&
					isset( $this->args['uid'] ) &&
					isset( $this->args['pwd'] ) &&
					isset( $this->args['database'] ))
				{
					$this->link = sqlsrv_connect( 'DTOWN\SQLEXPRESS', array( "UID"=>$this->args['uid'], "PWD"=>$this->args['pwd'], "Database"=>$this->args["database"] ));

					if( $this->link )
					{
						return true;
					}
					else
					{
						throw new DatabaseException("could not connect to database " . implode(' ', array_pop(sqlsrv_errors())));
					}
				}
				else
				{
					throw new DataAdapterException("missing required connection string parameter");
				}
			}
			else
			{
				throw new \System\Base\InvalidOperationException("connection already open");
			}
		}


		/**
		 * closes an open connection
		 *
		 * @return bool					true if successfull
		 */
		public function close()
		{
			if( $this->link )
			{
				if( sqlsrv_close( $this->link ))
				{
					$this->link = null;
					return true;
				}
				else
				{
					throw new DatabaseException("could not close mssql connection");
				}
			}
			else
			{
				throw new \System\Base\InvalidOperationException("connection already closed");
			}
		}


		/**
		 * attempt to insert a record into the datasource
		 *
		 * @param  DataSet	&$ds		reference to a DataSet
		 * @return void
		 */
		final public function insertDataRecord( DataSet &$ds )
		{
			if( $this->link )
			{
				/**
				 * connection to mysql database
				 * attempt to insert record into table using DataSet
				 */
				$set_fields = '';
				$set_values = '';

				// build sql string
				foreach( $ds->fields as $field )
				{
					// get primary key name
					if( $field->autoIncrement )
					{
						$idkey_name = $field->name;
					}
					else
					{
						$set_fields .= '`' . $field->name . '`, ';

						if( $ds[$field->name] === null )
						{
							$set_values .= 'NULL, ';
						}
						elseif( $field->numeric )
						{
							$set_values .= (real)$ds[$field->name] . ', ';
						}
						else
						{
							$set_values .= '"' . $this->getEscapeString($ds[$field->name]) . '", ';
						}
					}
				}

				// rem last comma
				$set_fields = substr( $set_fields, 0, strlen( $set_fields ) - 2 );
				$set_values = substr( $set_values, 0, strlen( $set_values ) - 2 );

				$sql = '
					INSERT INTO `' . $ds->getProperty('table') . '`(' . $set_fields . ')
					VALUES(' . $set_values . ')';

				// insert record into source
				$this->executeInternal( $sql );

				// get primary key value
				if( isset( $idkey_name ))
				{
					$ds[$idkey_name] = $this->getLastInsertId();
				}
			}
			else
			{
				throw new \System\Base\InvalidOperationException("connection is closed");
			}
		}


		/**
		 * attempt to update a record in the datasource
		 *
		 * @param  DataSet	&$ds		reference to a DataSet
		 * @return void
		 */
		final public function updateDataRecord( DataSet &$ds )
		{
			if( $this->link )
			{
				/**
				 * connection to mysql database
				 * attempt to update record in table using DataSet
				 */
				$set_vars = '';
				$where	= '';

				// get reference to fieldset
				$primarykeys = array();

				// build sql string
				foreach( $ds->fields as $field )
				{
					if( $field->primaryKey )
					{
						$primarykey = array();
						$primarykey['name']	= $field->name;
						$primarykey['value'] = $field->value;
						$primarykey['numeric'] = (bool) $field->numeric;
						$primarykeys[] = $primarykey;
					}

					if( $ds[$field->name] === null )
					{
						$set_vars .= '`' . $field->name . '` = null, ';
					}
					elseif( $field->numeric )
					{
						$set_vars .= '`' . $field->name . '` = ' . (real)$ds[$field->name] . ', ';
					}
					else
					{
						$set_vars .= '`' . $field->name . '` = "' . $this->getEscapeString($ds[$field->name]) . '", ';
					}
				}

				// rem last comma
				$set_vars = substr( $set_vars, 0, strlen( $set_vars ) - 2 );

				foreach( $primarykeys as $primarykey )
				{
					if( $where )
					{
						$where .= "
					AND ";
					}

					if( $primarykey['numeric'] )
					{
						$where .= "`{$primarykey['name']}` = " . (real) $primarykey['value'];
					}
					else
					{
						$where .= "`{$primarykey['name']}` = \"{$primarykey['value']}\"";
					}
				}

				$sql = "
					UPDATE `{$ds->getProperty('table')}`
					SET {$set_vars}
					WHERE {$where}";

				// update source
				$this->executeInternal( $sql );
			}
			else
			{
				throw new \System\Base\InvalidOperationException("connection is closed");
			}
		}


		/**
		 * attempt to delete a record in the datasource
		 *
		 * @param  DataSet	&$ds		reference to a DataSet
		 * @return void
		 */
		final public function deleteDataRecord( DataSet &$ds )
		{
			if( $this->link )
			{
				/**
				 * connection to mysql database
				 * attempt to delete record in table using DataSet
				 */
				$where = '';
				$primarykeys = array();

				// get primary key
				foreach( $dr->dataSet->fields as $field )
				{
					if( $field->primaryKey )
					{
						$primarykey = array();
						$primarykey['value']   = $ds[$field->name];
						$primarykey['name']	= $field->name;
						$primarykey['numeric'] = (bool) $field->numeric;
						$primarykeys[] = $primarykey;
					}
				}

				foreach( $primarykeys as $primarykey )
				{
					if( $where )
					{
						$where .= "
					AND ";
					}

					if( $primarykey['numeric'] )
					{
						$where .= "`{$primarykey['name']}` = " . (real) $primarykey['value'];
					}
					else
					{
						$where .= "`{$primarykey['name']}` = \"{$primarykey['value']}\"";
					}
				}

				$sql = "
					DELETE FROM `{$ds->properties['table']}`
					WHERE {$where}";

				// delete record from source
				$this->executeInternal( $sql );
			}
			else
			{
				throw new \System\Base\InvalidOperationException("connection is closed");
			}
		}


		/**
		 * Executes a sql query or procedure on the current connection
		 *
		 * @param  string		$query		sql query
		 * @return void
		 */
		public function execute( $query )
		{
			$this->executeInternal( $query );
		}


		/**
		 * begin transaction
		 *
		 * @return void
		 */
		public function begin()
		{
			if( $this->link )
			{
				sqlsrv_begin_transaction( $this->link );
			}
			else
			{
				throw new \System\Base\InvalidOperationException("mssql resource is not a valid link identifier");
			}
		}


		/**
		 * rollback transaction
		 *
		 * @return void
		 */
		public function rollback()
		{
			if( $this->link )
			{
				sqlsrv_rollback( $this->link );
			}
			else
			{
				throw new \System\Base\InvalidOperationException("mssql resource is not a valid link identifier");
			}
		}


		/**
		 * commit transaction
		 *
		 * @return void
		 */
		public function commit()
		{
			if( $this->link )
			{
				sqlsrv_commit( $this->link );
			}
			else
			{
				throw new \System\Base\InvalidOperationException("mssql resource is not a valid link identifier");
			}
		}


		/**
		 * set foreign key checks
		 *
		 * @param  bool	 $set		set/unset foreign key checks
		 * @return void
		 */
		final public function setForeignKeyChecks( $set )
		{
			if((bool)$set)
			{
				$this->executeInternal( 'SET FOREIGN_KEY_CHECKS=1;' );
			}
			else
			{
				$this->executeInternal( 'SET FOREIGN_KEY_CHECKS=0;' );
			}
		}


		/**
		 * returns a DataSet of tables
		 *
		 * @return DataSet
		 */
		public function getTables()
		{
			return $this->openDataSet( "SHOW TABLES FROM [{$this->args['database']}]" );
		}


		/**
		 * return id of last record inserted
		 *
		 * @return int
		 */
		public function getLastInsertId()
		{
			throw new \System\Base\InvalidOperationException("mssql resource is not a valid link identifier");
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
				return sqlsrv_rows_affected( $this->link );
			}
			else
			{
				throw new \System\Base\InvalidOperationException("mssql resource is not a valid link identifier");
			}
		}


		/**
		 * Executes a sql query or procedure on the current connection
		 *
		 * @param  string		$query		sql query
		 * @return resource					mysql resultset
		 */
		protected function executeInternal( $query )
		{
			$sql = '';
			if( $query instanceof QueryBuilder )
			{
				$sql = $this->getQuery( $query );
			}
			else
			{
				$sql = $query;
			}

			if( $this->link )
			{
				// start timer
				$timer = new \System\Utils\Timer(true);
				$this->lastQuery = $sql;
				$this->lastQueryTime = 0;

				$link = sqlsrv_query( $this->link, $sql );

				if( !$link )
				{
					throw new SQLException(implode(' ', array_pop(sqlsrv_errors())));
				}

				// end time
				$this->lastQueryTime = $timer->elapsed();
				$this->queryCount++;
				$this->queryTime += $this->lastQueryTime;

				return $link;
			}
			else
			{
				throw new \System\Base\InvalidOperationException("mysql resource in not a valid link identifier");
			}
		}


		/**
		 * Returns SQL based on the QueryBuilder
		 *
		 * @param  QueryBuilder		$queryBuilder	QueryBuilder object
		 * @return string							SQL query
		 */
		final protected function getQuery( QueryBuilder $queryBuilder ) {

			// select
			if( $queryBuilder->getStatement() === 'select' ) {
				$sql = 'select';

				// columns
				$columns = '';
				foreach( $queryBuilder->getColumns() as $column ) {

					if( strlen( $columns ) > 0 ) {
						$columns .= '
	, ';
					}
					else {
						$columns = ' ';
					}

					if( $column['table'] === '*' ) {
						$columns .= '*';
					}
					else {
						$columns .= '`' . $column['table'] . '`';

						if( $column['column'] === '*' ) {
							$columns .= '.*';
						}
						else {
							$columns .= '.`' . $column['column'] . '`';
							$columns .= ' as `' . $column['alias'] . '`';
						}
					}
				}

				$sql .= isset( $columns )?$columns:'';

				// from
				$tables = '';
				foreach( $queryBuilder->getTables() as $table ) {
					if( strlen( $tables ) > 0 ) {
						$tables .= '
	, `' . $table['table'] . '`' . ' as `' . $table['alias'] . '`';
					}
					else {
						$tables = '
	from `' . $table['table'] . '`' . ' as `' . $table['alias'] . '`';
					}
				}

				$sql .= isset( $tables )?$tables:'';
			}

			// insert
			elseif( $queryBuilder->getStatement() === 'insert' ) {
				$sql = 'insert';

				$tables = $queryBuilder->getTables();

				$sql .= '
	into `' . $tables[0]['table'] . '` (';

				// columns
				$columns = '';
				foreach( $queryBuilder->getColumns() as $column ) {
					if( strlen( $columns ) > 0 ) {
						$columns .= ',`' . $column['column'] . '`';
					}
					else {
						$columns = '`' . $column['column'] . '`';
					}
				}

				$sql .= isset( $columns )?$columns:'';
				$sql .= ')';

				$sql .= '
	values(';

				// values
				$values = '';
				foreach( $queryBuilder->getValues() as $value ) {
					if( strlen( $values ) > 0 ) {
						$values .= ',';
					}
					else {
						$values = '';
					}
					if( is_null( $value )) {
						$values .= 'null';
					}
					elseif( is_bool( $value )) {
						$values .= $value?'true':'false';
					}
					elseif( is_int( $value )) {
						$values .= (int)$value;
					}
					elseif( is_float( $value )) {
						$values .= (real)$value;
					}
					else {
						$values .= '"' . $this->getEscapeString( $value ) . '"';
					}
				}

				$sql .= $values . ')';
			}

			// update
			elseif( $queryBuilder->getStatement() === 'update' ) {
				$sql = 'update';

				$tables = $queryBuilder->getTables();
				$sql .= ' `' . $tables[0]['table'] . '`';

				// set
				$columns = $queryBuilder->getColumns();
				$values = $queryBuilder->getValues();
				$setClause = '';
				for( $i = 0; $i < count( $columns ); $i++ ) {
					if( strlen( $setClause ) > 0 ) {
						$setClause .= '
	, ';
					}
					else {
						$setClause = '
set ';
					}

					if( is_null( $values[$i] )) {
						$setClause .= '`' . $columns[$i]['table'] . '`.`' . $columns[$i]['column'] . '` = null';
					}
					elseif( is_bool( $values[$i] )) {
						$setClause .= '`' . $columns[$i]['table'] . '`.`' . $columns[$i]['column'] . '` = ' . ($values[$i]?'true':'false');
					}
					elseif( is_int( $values[$i] )) {
						$setClause .= '`' . $columns[$i]['table'] . '`.`' . $columns[$i]['column'] . '` = ' . (int)$values[$i];
					}
					elseif( is_float( $values[$i] )) {
						$setClause .= '`' . $columns[$i]['table'] . '`.`' . $columns[$i]['column'] . '` = ' . (real)$values[$i];
					}
					else {
						$setClause .= '`' . $columns[$i]['table'] . '`.`' . $columns[$i]['column'] . '` = "' . $this->getEscapeString( $values[$i] ) . '"';
					}
				}

				$sql .= isset( $setClause )?$setClause:'';
			}

			// delete
			elseif( $queryBuilder->getStatement() === 'delete' ) {
				$sql = 'delete';

				// from
				$tables = '';
				foreach( $queryBuilder->getTables() as $table ) {
					if( strlen( $tables ) > 0 ) {
						$tables .= '
	, `' . $table['table'] . '`';
					}
					else {
						$tables = '
	from `' . $table['table'] . '`';
					}
				}

				$sql .= isset( $tables )?$tables:'';
			}

			// joins
			foreach( $queryBuilder->getJoins() as $join ) {
				$sql .= '
' . $join['type'] . '
	join `' . $join['lefttable'] . '` as `' . $join['alias'] . '`
		on `' . $join['alias'] . '`.`' . $join['leftcolumn'] . '` = `' . $join['righttable'] . '`.`' . $join['rightcolumn'] . '`';


			}

			// where
			$whereClause = '';
			foreach( $queryBuilder->getWhereClauses() as $where ) {
				if( strlen( $whereClause ) > 0 ) {
					$whereClause .= '
and';
				}
				else {
					$whereClause = '
where';
				}
				if( is_null( $where['value'] )) {
					$whereClause .= '
	`' . $where['table'] . '`.`' . $where['column'] . '` is null';
				}
				elseif( is_bool( $where['value'] )) {
					$whereClause .= '
	`' . $where['table'] . '`.`' . $where['column'] . '` = ' . ($where['value']?'true':'false');
				}
				elseif( is_int( $where['value'] )) {
					$whereClause .= '
	`' . $where['table'] . '`.`' . $where['column'] . '` ' . $where['operand'] . ' ' . (int)$where['value'] . '';
				}
				elseif( is_float( $where['value'] )) {
					$whereClause .= '
	`' . $where['table'] . '`.`' . $where['column'] . '` ' . $where['operand'] . ' ' . (real)$where['value'] . '';
				}
				else {
					$whereClause .= '
	`' . $where['table'] . '`.`' . $where['column'] . '` ' . $where['operand'] . ' "' . $this->getEscapeString( $where['value'] ) . '"';
				}
			}

			if( $queryBuilder->empty ) {
				if( strlen( $whereClause ) === 0 ) {
					$whereClause = '
where
	0';
				}
			}

			$sql .= isset( $whereClause )?$whereClause:'';

			// orderby
			$orderByClause = '';
			foreach( $queryBuilder->getOrderByClauses() as $orderby ) {
				if( strlen( $orderByClause ) > 0 ) {
					$orderByClause .= '
	, `' . $orderby['table'] . '`.`' . $orderby['column'] . '` ' . $orderby['direction'];
				}
				else {
					$orderByClause = '
order
	by `' . $orderby['table'] . '`.`' . $orderby['column'] . '` ' . $orderby['direction'];
				}
			}

			$sql .= isset( $orderByClause )?$orderByClause:'';

			// groupby
			$groupByClause = '';
			foreach( $queryBuilder->getGroupByClauses() as $groupby ) {
				if( strlen( $groupByClause ) > 0 ) {
					$groupByClause .= '
	, `' . $groupby['table'] . '`.`' . $groupby['column'] . '`';
				}
				else {
					$groupByClause = '
group
	by `' . $groupby['table'] . '`.`' . $groupby['column'] . '`';
				}
			}

			$sql .= isset( $groupByClause )?$groupByClause:'';

			// having
			$havingClause = '';
			foreach( $queryBuilder->getHavingClauses() as $having ) {
				if( strlen( $havingClause ) > 0 ) {
					$havingClause .= '
and';
				}
				else {
					$havingClause = '
having';
				}
				$havingClause .= '
	`' . $having['column'] . '` ' . $having['operand'] . ' "' . $this->getEscapeString( $having['value'] ) . '"';
			}

			$sql .= isset( $havingClause )?$havingClause:'';

			return $sql;
		}


		/**
		 * Returns escaped string
		 *
		 * @param  string		$unescaped_string   String to escape
		 * @return string							SQL query
		 */
		protected function getEscapeString( $unescaped_string ) {
			return mysql_real_escape_string( $unescaped_string, $this->link );
		}

private function getTableFromSQL($sql){
$posStart = stripos($sql,'from');
while(!$this->removeWhitespace($sql,$posStart) && $posStart < strlen($sql)){
$posStart++;
}
$posEnd = $posStart + 1;
while(!$this->removeWhitespace($sql,$posEnd) && $posEnd < strlen($sql)){
$posEnd++;
}

$table = substr($sql,$posStart,$posEnd - $posStart + 1);

$table = rtrim(ltrim(str_replace('[','',str_replace(']','',$table))));

return $table;
}


		private function getField($table, $fieldName)
		{
			$sql = "select c.status as status, case when pc.colid = c.colid then '1' else '' end as xtype, case when systypes.name = 'uniqueidentifier' then 1 else 0 end as guid
											from sysobjects o
											left join (sysindexes i
												join sysobjects pk ON i.name = pk.name
												and pk.parent_obj = i.id
												and pk.xtype = 'PK'
												join sysindexkeys ik on i.id = ik.id
												and i.indid = ik.indid
												join syscolumns pc ON ik.id = pc.id
												AND ik.colid = pc.colid) ON i.id = o.id
											join syscolumns c ON c.id = o.id
											left join systypes on c.xusertype = systypes.xusertype
											where o.name = '".$table."'
											AND c.name = '".$fieldName."'
											order by ik.keyno
		";

			$link = $this->executeInternal( $sql );
			$result = sqlsrv_fetch( $link );
dmp($result,0);
dmp($fieldName,0);
			$field = new \System\DB\DataField();
			$field->name = $fieldName;
			$field->table = $table;
			$field->autoIncrement = ($result[0] & 128) == 128;
			$field->primaryKey = ($result[1] == '1');

			return $field;
		}



	private function removeWhitespace($string,$start){
		if($start >= strlen($string)){
			return false;
		}
		return substr($string,$start,1)==' ' ||
				substr($string,$start,1)=="\t" ||
				substr($string,$start,1)=="\n" ||
				substr($string,$start,1)=="\r";
	}
	}
?>