<?php
	
	
	define("HAR_LOCK_TABLE",1);
	define("HAR_FULL_SYNTAX",2); 
	define("HAR_DROP_TABLE",4);
	define("HAR_NO_STRUCT",8);
	define("HAR_NO_DATA",16);
	define("HAR_ALL_OPTIONS",HAR_LOCK_TABLE | HAR_FULL_SYNTAX | HAR_DROP_TABLE );
	
	define("HAR_ALL_DB",1);
	define("HAR_ALL_TABLES",1);
	
	define('OS_Unix','u');
	define('OS_Windows','w');
	define('OS_Mac','m');

	
	class MYSQL_DUMP
	{
		var $dbhost = "";
		var $dbuser = "";
		var $dbpwd = "";
		var $database = null;
		var $tables = null;
		
		var $conn = null;
		var $result = null;
		var $error = "";
		var $OS_FullName = null;
		var $lineEnd = null;
		var $OS_local = "";
		
		
		
		function MYSQL_DUMP($host="",$user="",$dbpwd="")
		{
			$this->setDBHost($host,$user,$dbpwd);

			$this->OS_FullName=array(OS_Unix => 'UNIX',OS_Windows => 'WINDOWS',OS_Mac => 'MACOS');
			$this->lineEnd=array(OS_Unix => "\n",OS_Mac => "\r",OS_Windows => "\r\n");
		
			$this->OS_local=OS_Unix;
			if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
				$this->OS_local=OS_Windows;
			elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'MAC') 
				$this->OS_local=OS_Mac;

		}
		
		
		function setDBHost($host,$user,$dbpwd)
		{
			$this->dbhost = $host;
			$this->dbuser = $user;
			$this->dbpwd = $dbpwd;
		}
		
		
		function error()
		{
			return $this->error;
		}
		
		
		function dumpDB($database = HAR_ALL_DB, $tables = HAR_ALL_TABLES,$options = HAR_ALL_OPTIONS) 
		{
			$this->_connect();
			
			if(empty($database))
			{
				$this->error = "Specify the database.";
				return false;
			}
			
			if(empty($tables))
			{
				$this->error = "Specify the tables.";
				return false;
			}
			
			if($database == HAR_ALL_DB)
			{
				$sql = "SHOW DATABASES";
				$this->result = @mysql_query($sql);
				if(mysql_error()!=="")
				{
					$this->error="Error : ".mysql_error();
					return false;
				}
				
				while($row = mysql_fetch_array($this->result,MYSQL_NUM))
				{
					$this->database[]=$row[0];
				}
			}
			else if(is_string($database))
			{
				$this->database = @explode(",",$database);
			}
			
			$lineEnd = $this->lineEnd[$this->OS_local];
			$returnSql = "# MySql Dump".$lineEnd ;
			$returnSql .= "# Host:".$this->dbhost.$lineEnd;
			$returnSql .= "# -------------------------------------------------".$lineEnd;

			$sql = "SELECT VERSION()";
			$this->result = mysql_query($sql);
			$row = mysql_fetch_array($this->result,MYSQL_NUM);
			$returnSql .= "# Server version ".$row[0].$lineEnd.$lineEnd;
			
			
			
			for($i=0; $i < count($this->database) ; $i++)
			{
				if(count($this->database)>1)
					$returnSql.= "USE ".$this->database[$i].";".$lineEnd.$lineEnd;
				
				$this->result = @mysql_query("USE ".$this->database[$i]);
				if(mysql_error()!=="")
				{
					$this->error="Error : ".mysql_error();
					return false;
				}
				
				$this->tables=array();
				if($tables == HAR_ALL_TABLES)
				{
					$sql = "SHOW Tables";
					$this->result = @mysql_query($sql);
					if(mysql_error()!=="")
					{
						$this->error="Error : ".mysql_error();
						return false;
					}

					while($row = mysql_fetch_array($this->result,MYSQL_NUM))
					{
						$this->tables[]=$row[0];
					}
				}
				else if(is_string($tables))
				{
					$this->tables = @explode(",",$tables);
				}
				for($j=0 ; $j < count($this->tables) ; $j++)
				{
					if(($options & HAR_NO_STRUCT ) != HAR_NO_STRUCT)
					{
						$sql = "SHOW CREATE TABLE ".$this->tables[$j];
						$this->result = @mysql_query($sql);
						if(mysql_error()!=="")
						{
							$this->error="Error : ".mysql_error();
							return false;
						}
						$row = mysql_fetch_array($this->result,MYSQL_NUM);
						
						
						$returnSql .= " #".$lineEnd;
						$returnSql .= " # Table structure for table '".$this->tables[$j]."'".$lineEnd;
						$returnSql .= " #".$lineEnd.$lineEnd;
						
						if(($options & HAR_DROP_TABLE) == HAR_DROP_TABLE)
							$returnSql .= "DROP TABLE IF EXISTS ".$this->tables[$j].";".$lineEnd;
						$returnSql .= $row[1].";".$lineEnd.$lineEnd.$lineEnd;
					}
					
					if(($options & HAR_NO_DATA ) != HAR_NO_DATA )
					{
						$returnSql .= " #".$lineEnd;
						$returnSql .= " # Dumping data for table '".$this->tables[$j]."'".$lineEnd;
						$returnSql .= " #".$lineEnd.$lineEnd;
						
						if(($options & HAR_LOCK_TABLE ) == HAR_LOCK_TABLE )
							$returnSql .= "LOCK TABLES ".$this->tables[$j]." WRITE;".$lineEnd;

						$temp_sql = "INSERT INTO ".$this->tables[$j];
						if(($options & HAR_FULL_SYNTAX == HAR_FULL_SYNTAX))
						{
							$sql="SHOW COLUMNS FROM ".$this->tables[$j];
							$this->result = @mysql_query($sql);
							if(mysql_error()!=="")
							{
								$this->error="Error : ".mysql_error();
								return false;
							}
							$fields = array();
							while($row = mysql_fetch_array($this->result,MYSQL_NUM))
							{
								$fields[]=$row[0];
							}
							$temp_sql.=' ('.@implode(',',$fields).')';
						}

						$sql="SELECT * FROM ".$this->tables[$j];
						$this->result = @mysql_query($sql);
						if(mysql_error()!=="")
						{
							$this->error="Error : ".mysql_error();
							return false;
						}
						while($row = mysql_fetch_array($this->result,MYSQL_NUM))
						{
							foreach($row as $key => $value)
								$row[$key] = mysql_escape_string($value);
								
							$returnSql .=$temp_sql.' VALUES ("'.@implode('","',$row).'");'.$lineEnd;
						}
						if(($options & HAR_LOCK_TABLE ) == HAR_LOCK_TABLE )
							$returnSql .= "UNLOCK TABLES;".$lineEnd;

					}
					$returnSql .=$lineEnd.$lineEnd;
				}
			}
			return $returnSql;
		}
		
		
		function save_sql($sql,$sqlfile="")
		{
			if(empty($sqlfile))
			{
				$sqlfile = @implode("_",$this->database).".sql";
			}
			$fp = @fopen($sqlfile,"wb");
			if(!is_resource($fp))
			{
				$this->error = "Error: Unable to save file.";
				return false;
			}
			@fwrite($fp,$sql);
			@fclose($fp);
			return true;	
		}
		
				
		function download_sql($sql,$sqlfile="")
		{
			if(empty($sqlfile))
			{
				$sqlfile = @implode("_",$this->database).".sql";
			}
			@header("Cache-Control: ");// leave blank to avoid IE errors
			@header("Pragma: ");// leave blank to avoid IE errors
			@header("Content-type: application/octet-stream");
			@header("Content-type: application/octet-stream");
			@header("Content-Disposition: attachment; filename=".$sqlfile);
			echo $sql;
		}
		
		
		function restoreDB($sqlfile)
		{
			$this->error = "";
			$this->_connect();
			
			if(!is_file($sqlfile))
			{
				$this->error="Error : Not a valid file.";
				return false;
			}
			
			$lines=@file($sqlfile);
			if(!is_array($lines))
				$uploadMsg= "Sql File is empty.";
			else
			{
				foreach($lines as $line)
				{
					$sql.=trim($line);
					if(empty($sql))
					{
						$sql="";
						continue;
					}
					elseif(preg_match("/^[#-].*+\r?\n?/i",trim($line)))
					{
						$sql="";
						continue;
					}
					elseif(!preg_match("/;[\r\n]+/",$line))
						continue;
					
					@mysql_query($sql);
					if(mysql_error()!="")
					{
						$this->error.="<br>".mysql_error();
					}
			
					$sql="";
				}
				if(!empty($this->error))
					return false;
				return true;
			}
		}
		
		function _connect()
		{
			if(!is_resource($this->conn))
				$this->conn = @mysql_connect($this->dbhost,$this->dbuser,$this->dbpwd);
			if(!is_resource($this->conn))
			{
				$this->error = mysql_error();
				return false;
			}
			return $this->conn;
		}
		
	}
?>