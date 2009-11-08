<?php
class Object
{
	var $string;
	var $sql;
	var $objectName;
	var $attributeList;
	var $typeList;
    var $getterList;
    var $setterList;
    var $encryptList;
    var $insertList;
    var $updatetList;    
    var $defaultListPHP;
    var $defaultListDB;
	var $separator = "\n\t";
	var $pdoDriver = "";
	var $language = 'php5.1';

	// -------------------------------------------------------------
    
	function Object($objectName, $attributeList = '', $typeList ='', $getterList='', $setterList='', 
                    $insertList ='', $updatetList='',
                    $defaultListPHP='', $defaultListDB='',
                    $encryptList='', $pdoDriver = '', $language = 'php5.1')
	{
		$this->objectName = $objectName;
		$this->attributeList = $attributeList;
		$this->typeList = $typeList;
		$this->pdoDriver = $pdoDriver;
		$this->language = $language;
        $this->getterList = $getterList;
        $this->setterList = $setterList;
        $this->encryptList = $encryptList;
        $this->insertList = $insertList;
        $this->updatetList = $updatetList;
        $this->defaultListPHP = $defaultListPHP;
        $this->defaultListDB = $defaultListDB;
	}

	// -------------------------------------------------------------
	function BeginObject()
	{
		$misc = new Misc(array());
		$this->string = "<?php\n";
		$this->string .= $this->CreatePreface();
		$this->string .= "\ninclude_once('class.pog_base.php');";
		foreach ($this->typeList as $key => $type)
		{
			if ($type == "JOIN")
			{
				$this->string .= "\ninclude_once('class.".strtolower($misc->MappingName($this->objectName, $this->attributeList[$key])).".php');";
			}
		}
		$this->string .= "\nclass ".$this->objectName." extends POG_Base\n{\n\t";
		$this->string.="public \$".strtolower($this->objectName)."Id = '';\n\n\t";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] == "BELONGSTO")
			{
				$this->string .="/**\n\t";
				$this->string .=" * @var INT(11)\n\t";
				$this->string .=" */\n\t";
                if( $thi->setterList[$x] && $this->getterList[$x] )
				    $this->string.="public $".strtolower($attribute)."Id;\n\t";
                else
                    $this->string.="private $".strtolower($attribute)."Id;\n\t";
                
				$this->string.="\n\t";
			}
			else if ($this->typeList[$x] == "HASMANY" || $this->typeList[$x] == "JOIN")
			{
				$this->string .="/**\n\t";
				$this->string .=" * @var private array of $attribute objects\n\t";
				$this->string .=" */\n\t";
				$this->string.="private \$_".strtolower($attribute)."List = array();\n\t";
				$this->string.="\n\t";
			}
			else
			{
				$this->string .="/**\n\t";
				$this->string .=" * @var ".stripcslashes($this->typeList[$x])."\n\t";
				$this->string .=" */\n\t";
                if( $thi->setterList[$x] && $this->getterList[$x] )
				    $this->string.="public $".$attribute.";\n\t";
                else
                    $this->string.="private $".$attribute.";\n\t";
				$this->string.="\n\t";
			}
			$x++;
		}
		//	create attribute => type array map
		//	needed for setup
		$this->string .= "public \$pog_attribute_type = array(\n\t\t";
		$this->string .= "\"".strtolower($this->objectName)."Id\" => array('db_attributes' => array(\"NUMERIC\", \"INT\")),\n\t\t";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			//$this->string .= "\"".$attribute."\" => array('db_attributes' => array(\"".$misc->InterpretType($this->typeList[$x])."\", \"".$misc->GetAttributeType($this->typeList[$x])."\"".(($misc->InterpretLength($this->typeList[$x]) != null) ?  ', "'.$misc->InterpretLength($this->typeList[$x]).'"' : '').")),\n\t\t";
            $this->string .= "\"".$attribute."\" => array('db_attributes' => array('".$misc->InterpretType($this->typeList[$x])."', '".$misc->GetAttributeType($this->typeList[$x])."'".(($misc->InterpretLength($this->typeList[$x]) != null) ?  ", '".$misc->InterpretLength($this->typeList[$x])."'" : '').")"
                                                          ."'access_attribute' => array(" . $this->getterList[$x] ."),\n\t\t "
                                                          ."'assign_attribute' => array(" . $this->setterList[$x] ."),\n\t\t "
                                                          ."'encrypt_attribute' => array(" . $this->encryptList[$x] ."),\n\t\t "
                                                          ."'insert_attribute' => array(" . $this->insertList[$x] ."),\n\t\t "
                                                          ."'update_attribute' => array(" . $this->updateList[$x] ."),\n\t\t "
                                                          ."'defaultphp_attribute' => array(" . $this->defaultListPHP[$x] ."),\n\t\t "
                                                          ."'defaultdb_attribute' => array(" . $this->defaultListDB[$x] ."),\n\t\t "
                                                          ."),\n\t\t";
			$x++;
		}
		$this->string .= ");\n\t";
		$this->string .= "public \$pog_query;";
	}

	// -------------------------------------------------------------
	function EndObject()
	{
		$this->string .= "\n}\n?>";
	}

	// -------------------------------------------------------------
	function CreateConstructor()
	{
		$this->string .= "\n\t\n\tfunction ".$this->objectName."(";
		$i = 0;
		$j = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$i] != "BELONGSTO" && $this->typeList[$i] != "HASMANY" && $this->typeList[$i] != "JOIN")
			{
				if ($j == 0)
				{
					$this->string .= '$'.$attribute.'=\'\'';
				}
				else
				{
					$this->string .= ', $'.$attribute.'='.((!empty($this->defaultListPHP[$i]))? $this->defaultListPHP[$i]:'\'\'');
				}
				$j++;
			}
			$i++;
		}
		$this->string .= ")\n\t{";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] == "HASMANY" || $this->typeList[$x] == "JOIN")
			{
				$this->string .="\n\t\t\$this->_".strtolower($attribute)."List = array();";
			}
			else if ($this->typeList[$x] != "BELONGSTO")
			{
				$this->string .= "\n\t\t\$this->".$attribute." = $".$attribute.";";
			}
			$x++;
		}
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSQLQuery()
	{
        $this->sql .= "\tCREATE TABLE `".strtolower($this->objectName)."` (\n\t`".strtolower($this->objectName)."id` int(11) NOT NULL auto_increment,";
        $sql_log  = "\tCREATE TABLE `".strtolower($this->objectName)."_log` (\n\t`".strtolower($this->objectName)."id` int(11),";
        $sql_logtrigger = " delimiter //\n";        
        $sql_logtrigger.= " drop trigger if exists `".strtolower($this->objectName)."_a{TIPO}` //";
        $sql_logtrigger.= " create trigger `".strtolower($this->objectName)."_a{TIPO}` after {ACCION} on `".strtolower($this->objectName)."`";
        $sql_logtrigger.= " for each row begin ";        
		$sql_logtrigger.= " insert into `".strtolower($this->objectName)."_log` (";
        $sql_logtrigger_fields='';
        $sql_logtrigger_value='';
        $sql_foreignkeys="";
		$x=0;
		$indexesToBuild = array();        
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] == "BELONGSTO")
			{
				$indexesToBuild[] = "`".strtolower($attribute)."id`";
				$this->sql .= "\n\t`".strtolower($attribute)."id` int(11) NOT NULL,";
                $sql_log .= "\n\t`".strtolower($attribute)."id` int(11),";
                $sql_logtrigger_fields.= strtolower($attribute).'id,';
                $sql_logtrigger_value.= '{TIPO2}.'.strtolower($attribute).'id,';
                $sql_foreignkeys.= " alter table ".strtolower($this->objectName)." add constraint `fk".strtolower($attribute)."id` foreign key (`".strtolower($attribute)."id`) references ".strtolower($attribute)." (`".strtolower($attribute)."id`) on delete restrict on update no action;\n\n";
			}
			else if ($this->typeList[$x] != "HASMANY" && $this->typeList[$x] != "JOIN")
			{
				$this->sql .= "\n\t`".strtolower($attribute)."` ".stripcslashes($this->typeList[$x])." NOT NULL,";
                $sql_log .= "\n\t`".strtolower($attribute)."` ".stripcslashes($this->typeList[$x]).",";
                $sql_logtrigger_fields.= strtolower($attribute).',';
                $sql_logtrigger_value.= '{TIPO2}.'.strtolower($attribute).',';
			}            
			$x++;
		}
		if (sizeof($indexesToBuild) > 0)
		{
			$this->sql .= " INDEX(".implode(',', $indexesToBuild)."),";
		}
        // foreign key (customer_id) references customer(id)

		//$this->sql .= " PRIMARY KEY  (`".strtolower($this->objectName)."id`)) ENGINE=MyISAM;";
        $this->sql .= " PRIMARY KEY  (`".strtolower($this->objectName)."id`)) ENGINE=InnoDB;";        
        $sql_log .=" `ts_log` datetime, `operacion_log` VARCHAR(1) ) ENGINE=InnoDB;";
        $sql_logtrigger.= $sql_logtrigger_fields."ts_log, operacion_log)values(".$sql_logtrigger_value."now(), '{TIPO}' ); end // ";
        //
        $this->sql .= "\n\n" . $sql_log ;
        $this->sql .= "\n\n" . str_replace( '{ACCION}', 'insert', str_replace( '{TIPO2}','new', str_replace( '{TIPO}', 'i', $sql_logtrigger ) ) );
        $this->sql .= "\n\n" . str_replace( '{ACCION}', 'update', str_replace( '{TIPO2}','new', str_replace( '{TIPO}', 'u', $sql_logtrigger ) ) );
        $this->sql .= "\n\n" . str_replace( '{ACCION}', 'delete', str_replace( '{TIPO2}','old', str_replace( '{TIPO}', 'd', $sql_logtrigger ) ) );        
        $this->sql .= "\n\n" . $sql_foreignkeys;
	}

	// -------------------------------------------------------------
	function CreateComments($description='', $parameterDescriptionArray='', $returnType='')
	{
		$this->string .= "/**\n"
 		."\t* $description\n";
 		if ($parameterDescriptionArray != '')
 		{
	 		foreach ($parameterDescriptionArray as $parameter)
	 		{
	 			$this->string .= "\t* @param $parameter \n";
	 		}
 		}
	     $this->string .= "\t* @return $returnType\n"
	     ."\t*/\n";
	}

	// -------------------------------------------------------------
	function CreatePreface()
	{
		$this->string .= "/*\n\tThis SQL query will create the table to store your object.\n";
		$this->CreateSQLQuery();
		$this->string .= "\n".$this->sql."\n*/";
		$this->string .= "\n\n/**";
		$this->string .= "\n* <b>".$this->objectName."</b> class with integrated CRUD methods.";
		$this->string .= "\n* @author ".$GLOBALS['configuration']['author'];
		$this->string .= "\n* @version POG ".$GLOBALS['configuration']['versionNumber'].$GLOBALS['configuration']['revisionNumber']." / ".strtoupper($this->language)." MYSQL";
		$this->string .= "\n* @see http://www.phpobjectgenerator.com/plog/tutorials/45/pdo-mysql";
		$this->string .= "\n* @copyright ".$GLOBALS['configuration']['copyright'];
		$this->string .= "\n* @link http://www.phpobjectgenerator.com/?language=".$this->language."&wrapper=pdo&pdoDriver=".$this->pdoDriver."&objectName=".urlencode($this->objectName)."&attributeList=".urlencode(var_export($this->attributeList, true))."&typeList=".urlencode(urlencode(var_export($this->typeList, true)));
		$this->string .= "\n*/";
	}


	// Essential functions

	// -------------------------------------------------------------
	function CreateMagicGetterFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Getter for some private attributes",'',"mixed \$attribute");
		$this->string .= "\tpublic function __get(\$attribute)\n\t{";
		$this->string .= "\n\t\tif (isset(\$this->{\"_\".\$attribute}))";
	    $this->string .= "\n\t\t{";
	    $this->string .= "\n\t\t\treturn \$this->{\"_\".\$attribute};";
	    $this->string .= "\n\t\t}";
        $this->string .= "\n\t\telse";
       	$this->string .= "\n\t\t{";
       	$this->string .= "\n\t\t\treturn false;";
       	$this->string .= "\n\t\t}";
	    $this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSaveFunction($deep = false)
	{
		$misc = new Misc(array());
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Saves the object to the database",'',"integer $".strtolower($this->objectName)."Id");
		if ($deep)
		{
			$this->string .= "\tfunction Save(\$deep = true)\n\t{";
		}
		else
		{
			$this->string .= "\tfunction Save()\n\t{";
		}
		$this->string .= "\n\t\t\$connection = Database::Connect();";
		$this->string .= "\n\t\t\$this->pog_query = \"select `".strtolower($this->objectName)."id` from `".strtolower($this->objectName)."` where `".strtolower($this->objectName)."id`='\".\$this->".strtolower($this->objectName)."Id.\"' LIMIT 1\";";
		$this->string .= "\n\t\t\$rows = Database::Query(\$this->pog_query, \$connection);";
		$this->string .= "\n\t\tif (\$rows > 0)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->pog_query = \"update `".strtolower($this->objectName)."` set ";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY" && $this->typeList[$x] != "JOIN")
			{
				if ($x == (count($this->attributeList)-1))
				{
					// don't encode enum values.
					// we could also check the attribute type at runtime using the attribute=>array map
					// but this solution is more efficient
					if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
					{
						if ($this->typeList[$x] == "BELONGSTO")
						{
							$this->string .= "\n\t\t\t`".strtolower($attribute)."id`='\".\$this->".strtolower($attribute)."Id.\"' ";
						}
						else
						{
							$this->string .= "\n\t\t\t`".strtolower($attribute)."`='\".\$this->$attribute.\"' ";
						}
					}
					else
					{
						$this->string .= "\n\t\t\t`".strtolower($attribute)."`='\".\$this->Escape(\$this->$attribute).\"' ";
					}
				}
				else
				{
					if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
					{
						if ($this->typeList[$x] == "BELONGSTO")
						{
							$this->string .= "\n\t\t\t`".strtolower($attribute)."id`='\".\$this->".strtolower($attribute)."Id.\"', ";
						}
						else
						{
							$this->string .= "\n\t\t\t`".strtolower($attribute)."`='\".\$this->$attribute.\"', ";
						}
					}
					else
					{
						$this->string .= "\n\t\t\t`".strtolower($attribute)."`='\".\$this->Escape(\$this->$attribute).\"', ";
					}
				}
			}
			$x++;
		}
		if (substr($this->string, strlen($this->string) - 2) == ", ")
		{
			$this->string = substr($this->string, 0, strlen($this->string) - 2);
		}
		$this->string .= "where `".strtolower($this->objectName)."id`='\".\$this->".strtolower($this->objectName)."Id.\"'\";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\telse";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->pog_query = \"insert into `".strtolower($this->objectName)."` (";
		$y=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$y] != "HASMANY"  && $this->typeList[$y] != "JOIN")
			{
				if ($y == (count($this->attributeList)-1))
				{
					if ($this->typeList[$y] == "BELONGSTO")
					{
						$this->string .= "`".strtolower($attribute)."id` ";
					}
					else
					{
						$this->string .= "`".strtolower($attribute)."` ";
					}
				}
				else
				{
					if ($this->typeList[$y] == "BELONGSTO")
					{
						$this->string .= "`".strtolower($attribute)."id`, ";
					}
					else
					{
						$this->string .= "`".strtolower($attribute)."`, ";
					}
				}
			}
			$y++;
		}
		if (substr($this->string, strlen($this->string) - 2) == ", ")
		{
			$this->string = substr($this->string, 0, strlen($this->string) - 2);
		}
		$this->string .= ") values (";
		$z=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$z] != "HASMANY" && $this->typeList[$z] != "JOIN")
			{
				if ($z == (count($this->attributeList)-1))
				{
					if (strtolower(substr($this->typeList[$z],0,4)) == "enum" || strtolower(substr($this->typeList[$z],0,3)) == "set"  || strtolower(substr($this->typeList[$z],0,4)) == "date" || strtolower(substr($this->typeList[$z],0,4)) == "time" || $this->typeList[$z] == "BELONGSTO")
					{
						if ($this->typeList[$z] == "BELONGSTO")
						{
							$this->string .= "\n\t\t\t'\".\$this->".strtolower($attribute)."Id.\"' ";
						}
						else
						{
							$this->string .= "\n\t\t\t'\".\$this->$attribute.\"' ";
						}
					}
					else
					{
						$this->string .= "\n\t\t\t'\".\$this->Escape(\$this->$attribute).\"' ";
					}
				}
				else
				{
					if (strtolower(substr($this->typeList[$z],0,4)) == "enum" || strtolower(substr($this->typeList[$z],0,3)) == "set"  || strtolower(substr($this->typeList[$z],0,4)) == "date" || strtolower(substr($this->typeList[$z],0,4)) == "time" || $this->typeList[$z] == "BELONGSTO")
					{
						if ($this->typeList[$z] == "BELONGSTO")
						{
							$this->string .= "\n\t\t\t'\".\$this->".strtolower($attribute)."Id.\"', ";
						}
						else
						{
							$this->string .= "\n\t\t\t'\".\$this->$attribute.\"', ";
						}
					}
					else
					{
						$this->string .= "\n\t\t\t'\".\$this->Escape(\$this->$attribute).\"', ";
					}
				}
			}
			$z++;
		}
		if (substr($this->string, strlen($this->string) - 2) == ", ")
		{
			$this->string = substr($this->string, 0, strlen($this->string) - 2);
		}
		$this->string .= ")\";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\t\$insertId = Database::InsertOrUpdate(\$this->pog_query, \$connection);";
		$this->string .= "\n\t\tif (\$this->".strtolower($this->objectName)."Id == \"\")";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->".strtolower($this->objectName)."Id = \$insertId;";
		$this->string .= "\n\t\t}";
		if ($deep)
		{
			$this->string .= "\n\t\tif (\$deep)";
			$this->string .= "\n\t\t{";
			$i = 0;
			foreach ($this->typeList as $type)
			{
				if ($type == "HASMANY")
				{
					$this->string .= "\n\t\t\tforeach (\$this->_".strtolower($this->attributeList[$i])."List as $".strtolower($this->attributeList[$i]).")";
					$this->string .= "\n\t\t\t{";
					$this->string .= "\n\t\t\t\t\$".strtolower($this->attributeList[$i])."->".strtolower($this->objectName)."Id = \$this->".strtolower($this->objectName)."Id;";
					$this->string .= "\n\t\t\t\t\$".strtolower($this->attributeList[$i])."->Save(\$deep);";
					$this->string .= "\n\t\t\t}";
				}
				else if ($type == "JOIN")
				{
					$this->string .= "\n\t\t\tforeach (\$this->_".strtolower($this->attributeList[$i])."List as $".strtolower($this->attributeList[$i]).")";
					$this->string .= "\n\t\t\t{";
					$this->string .= "\n\t\t\t\t\$".strtolower($this->attributeList[$i])."->Save();";
					$this->string .= "\n\t\t\t\t\$map = new ".$misc->MappingName($this->objectName, $this->attributeList[$i])."();";
					$this->string .= "\n\t\t\t\t\$map->AddMapping(\$this, \$".strtolower($this->attributeList[$i]).");";
					$this->string .= "\n\t\t\t}";
				}

				$i++;
			}
			$this->string .= "\n\t\t}";
		}
		$this->string .= "\n\t\treturn \$this->".strtolower($this->objectName)."Id;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSaveNewFunction($deep = false)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Clones the object and saves it to the database",'',"integer $".strtolower($this->objectName)."Id");
		if ($deep)
		{
			$this->string .="\tfunction SaveNew(\$deep = false)\n\t{";
		}
		else
		{
			$this->string .="\tfunction SaveNew()\n\t{";
		}
		$this->string .= "\n\t\t\$this->".strtolower($this->objectName)."Id = '';";
		if ($deep)
		{
			$this->string .= "\n\t\treturn \$this->Save(\$deep);";
		}
		else
		{
			$this->string .= "\n\t\treturn \$this->Save();";
		}
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateDeleteFunction($deep = false)
	{
		$misc = new Misc(array());
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Deletes the object from the database",'',"boolean");
		if ($deep)
		{
			$this->string .= "\tfunction Delete(\$deep = false, \$across = false)\n\t{";
		}
		else
		{
			$this->string .= "\tfunction Delete()\n\t{";
		}
		if ($deep)
		{
			if (in_array("HASMANY", $this->typeList))
			{
				$this->string .= "\n\t\tif (\$deep)";
				$this->string .= "\n\t\t{";
				$i = 0;
				foreach ($this->typeList as $type)
				{
					if ($type == "HASMANY")
					{
						$this->string .= "\n\t\t\t$".strtolower($this->attributeList[$i])."List = \$this->Get".ucfirst(strtolower($this->attributeList[$i]))."List();";
						$this->string .= "\n\t\t\tforeach ($".strtolower($this->attributeList[$i])."List as $".strtolower($this->attributeList[$i]).")";
						$this->string .= "\n\t\t\t{";
						$this->string .= "\n\t\t\t\t\$".strtolower($this->attributeList[$i])."->Delete(\$deep, \$across);";
						$this->string .= "\n\t\t\t}";
					}
					$i++;
				}
				$this->string .= "\n\t\t}";
			}
			if (in_array("JOIN", $this->typeList))
			{
				$this->string .= "\n\t\tif (\$across)";
				$this->string .= "\n\t\t{";
				$i = 0;
				foreach ($this->typeList as $type)
				{
					if ($type == "JOIN")
					{
						$this->string .= "\n\t\t\t$".strtolower($this->attributeList[$i])."List = \$this->Get".ucfirst(strtolower($this->attributeList[$i]))."List();";
						$this->string .= "\n\t\t\t\$map = new ".$misc->MappingName($this->objectName, $this->attributeList[$i])."();";
						$this->string .= "\n\t\t\t\$map->RemoveMapping(\$this);";
						$this->string .= "\n\t\t\tforeach (\$".strtolower($this->attributeList[$i])."List as \$".strtolower($this->attributeList[$i]).")";
						$this->string .= "\n\t\t\t{";
						$this->string .= "\n\t\t\t\t\$".strtolower($this->attributeList[$i])."->Delete(\$deep, \$across);";
						$this->string .= "\n\t\t\t}";
					}
					$i++;
				}
				$this->string .= "\n\t\t}";
				$this->string .= "\n\t\telse";
				$this->string .= "\n\t\t{";
				$j = 0;
				foreach ($this->typeList as $type)
				{
					if ($type == "JOIN")
					{
						$this->string .= "\n\t\t\t\$map = new ".$misc->MappingName($this->objectName, $this->attributeList[$j])."();";
						$this->string .= "\n\t\t\t\$map->RemoveMapping(\$this);";
					}
					$j++;
				}
				$this->string .= "\n\t\t}";
			}
		}
		$this->string .= "\n\t\t\$connection = Database::Connect();";
		$this->string .= "\n\t\t\$this->pog_query = \"delete from `".strtolower($this->objectName)."` where `".strtolower($this->objectName)."id`='\".\$this->".strtolower($this->objectName)."Id.\"'\";";
		$this->string .= "\n\t\treturn Database::NonQuery(\$this->pog_query, \$connection);";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateDeleteListFunction($deep = false)
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Deletes a list of objects that match given conditions",array("multidimensional array {(\"field\", \"comparator\", \"value\"), (\"field\", \"comparator\", \"value\"), ...}","bool \$deep"));
		if ($deep)
		{
			$this->string .= "\tfunction DeleteList(\$fcv_array, \$deep = false, \$across = false)\n\t{";
		}
		else
		{
			$this->string .= "\tfunction DeleteList(\$fcv_array)\n\t{";
		}
		$this->string .= "\n\t\tif (sizeof(\$fcv_array) > 0)";
		$this->string .= "\n\t\t{";
		$indentation = "\n\t\t\t";
		if ($deep)
		{
			$this->string .= "\n\t\t\tif (\$deep || \$across)";
			$this->string .= "\n\t\t\t{";
			$this->string .= "\n\t\t\t\t\$objectList = \$this->GetList(\$fcv_array);";
			$this->string .= "\n\t\t\t\tforeach (\$objectList as \$object)";
			$this->string .= "\n\t\t\t\t{";
			$this->string .= "\n\t\t\t\t\t\$object->Delete(\$deep, \$across);";
			$this->string .= "\n\t\t\t\t}";
			$this->string .= "\n\t\t\t}";
			$this->string .= "\n\t\t\telse";
			$this->string .= "\n\t\t\t{";
			$indentation .= "\t";
		}
		$this->string .= $indentation."\$connection = Database::Connect();";
		$this->string .= $indentation."\$pog_query = \"delete from `".strtolower($this->objectName)."` where \";";
		$this->string .= $indentation."for (\$i=0, \$c=sizeof(\$fcv_array); \$i<\$c; \$i++)";
		$this->string .= $indentation."{";
		$this->string .= $indentation."\tif (sizeof(\$fcv_array[\$i]) == 1)";
		$this->string .= $indentation."\t{";
		$this->string .= $indentation."\t\t\$pog_query .= \" \".\$fcv_array[\$i][0].\" \";";
		$this->string .= $indentation."\t\tcontinue;";
		$this->string .= $indentation."\t}";
		$this->string .= $indentation."\telse";
		$this->string .= $indentation."\t{";
		$this->string .= $indentation."\t\tif (\$i > 0 && sizeof(\$fcv_array[\$i-1]) !== 1)";
		$this->string .= $indentation."\t\t{";
		$this->string .= $indentation."\t\t\t\$pog_query .= \" AND \";";
		$this->string .= $indentation."\t\t}";
		$this->string .= $indentation."\t\tif (isset(\$this->pog_attribute_type[\$fcv_array[\$i][0]]['db_attributes']) && \$this->pog_attribute_type[\$fcv_array[\$i][0]]['db_attributes'][0] != 'NUMERIC' && \$this->pog_attribute_type[\$fcv_array[\$i][0]]['db_attributes'][0] != 'SET')";
		$this->string .= $indentation."\t\t{";
		$this->string .= $indentation."\t\t\t\$pog_query .= \"`\".\$fcv_array[\$i][0].\"` \".\$fcv_array[\$i][1].\" '\".\$this->Escape(\$fcv_array[\$i][2]).\"'\";";
		$this->string .= $indentation."\t\t}";
		$this->string .= $indentation."\t\telse";
		$this->string .= $indentation."\t\t{";
		$this->string .= $indentation."\t\t\t\$pog_query .= \"`\".\$fcv_array[\$i][0].\"` \".\$fcv_array[\$i][1].\" '\".\$fcv_array[\$i][2].\"'\";";
		$this->string .= $indentation."\t\t}";
		$this->string .= $indentation."\t}";
		$this->string .= $indentation."}";
		$this->string .= $indentation."return Database::NonQuery(\$pog_query, \$connection);";
		if ($deep)
		{
			$this->string .= "\n\t\t\t}";
		}
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateGetFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Gets object from database",array("integer \$".strtolower($this->objectName)."Id"),"object \$".$this->objectName);
		$this->string .="\tfunction Get(\$".strtolower($this->objectName)."Id)\n\t{";
		$this->string .= "\n\t\t\$connection = Database::Connect();";
		$this->string .= "\n\t\t\$this->pog_query = \"select * from `".strtolower($this->objectName)."` where `".strtolower($this->objectName)."id`='\".intval(\$".strtolower($this->objectName)."Id).\"' LIMIT 1\";";
		$this->string .= "\n\t\t\$cursor = Database::Reader(\$this->pog_query, \$connection);";
		$this->string .= "\n\t\twhile (\$row = Database::Read(\$cursor))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->".strtolower($this->objectName)."Id = \$row['".strtolower($this->objectName)."id'];";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY" && $this->typeList[$x] != "JOIN")
			{
				if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= "\n\t\t\t\$this->".strtolower($attribute)."Id = \$row['".strtolower($attribute)."id'];";
					}
					else
					{
						$this->string .= "\n\t\t\t\$this->".$attribute." = \$row['".strtolower($attribute)."'];";
					}
				}
				else
				{
					$this->string .= "\n\t\t\t\$this->".$attribute." = \$this->Unescape(\$row['".strtolower($attribute)."']);";
				}
			}
			$x++;
		}
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn \$this;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateGetListFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Returns a sorted array of objects that match given conditions",array("multidimensional array {(\"field\", \"comparator\", \"value\"), (\"field\", \"comparator\", \"value\"), ...}","string \$sortBy","boolean \$ascending","int limit"),"array \$".strtolower($this->objectName)."List");
		$this->string .= "\tfunction GetList(\$fcv_array = array(), \$sortBy='', \$ascending=true, \$limit='')\n\t{";
		$this->string .= "\n\t\t\$connection = Database::Connect();";
		$this->string .= "\n\t\t\$sqlLimit = (\$limit != '' ? \"LIMIT \$limit\" : '');";
		$this->string .= "\n\t\t\$this->pog_query = \"select * from `".strtolower($this->objectName)."` \";";
		$this->string .= "\n\t\t\$".strtolower($this->objectName)."List = Array();";
		$this->string .= "\n\t\tif (sizeof(\$fcv_array) > 0)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->pog_query .= \" where \";";
		$this->string .= "\n\t\t\tfor (\$i=0, \$c=sizeof(\$fcv_array); \$i<\$c; \$i++)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\tif (sizeof(\$fcv_array[\$i]) == 1)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$this->pog_query .= \" \".\$fcv_array[\$i][0].\" \";";
		$this->string .= "\n\t\t\t\t\tcontinue;";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\telse";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\tif (\$i > 0 && sizeof(\$fcv_array[\$i-1]) != 1)";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$this->pog_query .= \" AND \";";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\tif (isset(\$this->pog_attribute_type[\$fcv_array[\$i][0]]['db_attributes']) && \$this->pog_attribute_type[\$fcv_array[\$i][0]]['db_attributes'][0] != 'NUMERIC' && \$this->pog_attribute_type[\$fcv_array[\$i][0]]['db_attributes'][0] != 'SET')";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\tif (\$GLOBALS['configuration']['db_encoding'] == 1)";
		$this->string .= "\n\t\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\t\$value = POG_Base::IsColumn(\$fcv_array[\$i][2]) ? \"BASE64_DECODE(\".\$fcv_array[\$i][2].\")\" : \"'\".\$fcv_array[\$i][2].\"'\";";
		$this->string .= "\n\t\t\t\t\t\t\t\$this->pog_query .= \"BASE64_DECODE(`\".\$fcv_array[\$i][0].\"`) \".\$fcv_array[\$i][1].\" \".\$value;";
		$this->string .= "\n\t\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\t\telse";
		$this->string .= "\n\t\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\t\$value =  POG_Base::IsColumn(\$fcv_array[\$i][2]) ? \$fcv_array[\$i][2] : \"'\".\$this->Escape(\$fcv_array[\$i][2]).\"'\";";
		$this->string .= "\n\t\t\t\t\t\t\t\$this->pog_query .= \"`\".\$fcv_array[\$i][0].\"` \".\$fcv_array[\$i][1].\" \".\$value;";
		$this->string .= "\n\t\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\telse";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$value = POG_Base::IsColumn(\$fcv_array[\$i][2]) ? \$fcv_array[\$i][2] : \"'\".\$fcv_array[\$i][2].\"'\";";
		$this->string .= "\n\t\t\t\t\t\t\$this->pog_query .= \"`\".\$fcv_array[\$i][0].\"` \".\$fcv_array[\$i][1].\" \".\$value;";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\tif (\$sortBy != '')";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\tif (isset(\$this->pog_attribute_type[\$sortBy]['db_attributes']) && \$this->pog_attribute_type[\$sortBy]['db_attributes'][0] != 'NUMERIC' && \$this->pog_attribute_type[\$sortBy]['db_attributes'][0] != 'SET')";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\tif (\$GLOBALS['configuration']['db_encoding'] == 1)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$sortBy = \"BASE64_DECODE(\$sortBy) \";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\telse";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$sortBy = \"\$sortBy \";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$sortBy = \"\$sortBy \";";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\telse";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$sortBy = \"".strtolower($this->objectName)."id\";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\t\$this->pog_query .= \" order by \".\$sortBy.\" \".(\$ascending ? \"asc\" : \"desc\").\" \$sqlLimit\";";
		$this->string .= "\n\t\t\$thisObjectName = get_class(\$this);";
		$this->string .= "\n\t\t\$cursor = Database::Reader(\$this->pog_query, \$connection);";
		$this->string .= "\n\t\twhile (\$row = Database::Read(\$cursor))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$".strtolower($this->objectName)." = new \$thisObjectName();";
		$this->string .= "\n\t\t\t\$".strtolower($this->objectName)."->".strtolower($this->objectName)."Id = \$row['".strtolower($this->objectName)."id'];";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY" && $this->typeList[$x] != "JOIN")
			{
				if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= "\n\t\t\t\$".strtolower($this->objectName)."->".strtolower($attribute)."Id = \$row['".strtolower($attribute)."id'];";
					}
					else
					{
						$this->string .= "\n\t\t\t\$".strtolower($this->objectName)."->".$attribute." = \$row['".strtolower($attribute)."'];";
					}
				}
				else
				{
					$this->string .= "\n\t\t\t\$".strtolower($this->objectName)."->".$attribute." = \$this->Unescape(\$row['".strtolower($attribute)."']);";
				}
			}
			$x++;
		}
		$this->string .= "\n\t\t\t\$".strtolower($this->objectName)."List[] = \$".strtolower($this->objectName).";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn \$".strtolower($this->objectName)."List;";
		$this->string .= "\n\t}";
	}


	// Relations {1-1, 1-Many, Many-1} functions

	// -------------------------------------------------------------
	function CreateAddChildFunction($child)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $child object to this one",'',"");
		$this->string .= "\tfunction Add".ucfirst(strtolower($child))."(&\$".strtolower($child).")\n\t{";
		$this->string .= "\n\t\t\$".strtolower($child)."->".strtolower($this->objectName)."Id = \$this->".strtolower($this->objectName)."Id;";
		$this->string .= "\n\t\t\$found = false;";
		$this->string .= "\n\t\tforeach(\$this->_".strtolower($child)."List as \$".strtolower($child)."2)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\tif (\$".strtolower($child)."->".strtolower($child)."Id > 0 && \$".strtolower($child)."->".strtolower($child)."Id == \$".strtolower($child)."2->".strtolower($child)."Id)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$found = true;";
		$this->string .= "\n\t\t\t\tbreak;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\tif (!\$found)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->_".strtolower($child)."List[] = \$".strtolower($child).";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateGetChildrenFunction($child)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Gets a list of $child objects associated to this one", array("multidimensional array {(\"field\", \"comparator\", \"value\"), (\"field\", \"comparator\", \"value\"), ...}","string \$sortBy","boolean \$ascending","int limit"),"array of $child objects");
		$this->string .= "\tfunction Get".ucfirst(strtolower($child))."List(\$fcv_array = array(), \$sortBy='', \$ascending=true, \$limit='')\n\t{";
		$this->string .= "\n\t\t\$".strtolower($child)." = new ".$child."();";
		$this->string .= "\n\t\t\$fcv_array[] = array(\"".strtolower($this->objectName)."Id\", \"=\", \$this->".strtolower($this->objectName)."Id);";
		$this->string .= "\n\t\t\$dbObjects = \$".strtolower($child)."->GetList(\$fcv_array, \$sortBy, \$ascending, \$limit);";
		$this->string .= "\n\t\treturn \$dbObjects;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSetChildrenFunction($child)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Makes this the parent of all $child objects in the $child List array. Any existing $child will become orphan(s)",'',"null");
		$this->string .= "\tfunction Set".ucfirst(strtolower($child))."List(&\$list)\n\t{";
		$this->string .= "\n\t\t\$this->_".strtolower($child)."List = array();";
		$this->string .= "\n\t\t\$existing".ucfirst(strtolower($child))."List = \$this->Get".ucfirst(strtolower($child))."List();";
		$this->string .= "\n\t\tforeach (\$existing".ucfirst(strtolower($child))."List as \$".strtolower($child).")";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$".strtolower($child)."->".strtolower($this->objectName)."Id = '';";
		$this->string .= "\n\t\t\t\$".strtolower($child)."->Save(false);";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\t\$this->_".strtolower($child)."List = \$list;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSetParentFunction($parent)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $parent object to this one",'',"");
		$this->string .= "\tfunction Set".ucfirst(strtolower($parent))."(&\$".strtolower($parent).")\n\t{";
		$this->string .= "\n\t\t\$this->".strtolower($parent)."Id = $".strtolower($parent)."->".strtolower($parent)."Id;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateGetParentFunction($parent)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $parent object to this one",'',"boolean");
		$this->string .= "\tfunction Get".ucfirst(strtolower($parent))."()\n\t{";
		$this->string .= "\n\t\t\$".strtolower($parent)." = new ".$parent."();";
		$this->string .= "\n\t\treturn $".strtolower($parent)."->Get(\$this->".strtolower($parent)."Id);";
		$this->string .= "\n\t}";
	}


	// Relations {Many-Many} functions

	// -------------------------------------------------------------
	function CreateAddAssociationFunction($sibling)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $sibling object to this one",'',"");
		$this->string .= "\tfunction Add".ucfirst(strtolower($sibling))."(&\$".strtolower($sibling).")\n\t{";
		$this->string .= "\n\t\tif (\$".strtolower($sibling)." instanceof ".$sibling.")";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\tif (in_array(\$this, \$".strtolower($sibling)."->".strtolower($this->objectName)."List, true))";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\treturn false;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$found = false;";
		$this->string .= "\n\t\t\t\tforeach (\$this->_".strtolower($sibling)."List as \$".strtolower($sibling)."2)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\tif (\$".strtolower($sibling)."->".strtolower($sibling)."Id > 0 && \$".strtolower($sibling)."->".strtolower($sibling)."Id == \$".strtolower($sibling)."2->".strtolower($sibling)."Id)";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$found = true;";
		$this->string .= "\n\t\t\t\t\t\tbreak;";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\tif (!\$found)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$this->_".strtolower($sibling)."List[] = \$".strtolower($sibling).";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t}";
	}

	//-------------------------------------------------------------
	function CreateGetAssociationsFunction($sibling)
	{
		$misc = new Misc(array());
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Returns a sorted array of objects that match given conditions",array("multidimensional array {(\"field\", \"comparator\", \"value\"), (\"field\", \"comparator\", \"value\"), ...}","string \$sortBy","boolean \$ascending","int limit"),"array \$".strtolower($this->objectName)."List");
		$this->string .= "\tfunction Get".ucfirst(strtolower($sibling))."List(\$fcv_array = array(), \$sortBy='', \$ascending=true, \$limit='')\n\t{";
		$this->string .= "\n\t\t\$sqlLimit = (\$limit != '' ? \"LIMIT \$limit\" : '');";
		$this->string .= "\n\t\t\$connection = Database::Connect();";
		$this->string .= "\n\t\t\$".strtolower($sibling)." = new ".$sibling."();";
		$this->string .= "\n\t\t\$".strtolower($sibling)."List = Array();";
		$this->string .= "\n\t\t\$this->pog_query = \"select distinct * from `".strtolower($sibling)."` a INNER JOIN `".strtolower($misc->MappingName($this->objectName, $sibling))."` m ON m.".strtolower($sibling)."id = a.".strtolower($sibling)."id where m.".strtolower($this->objectName)."id = '\$this->".strtolower($this->objectName)."Id' \";";
		$this->string .= "\n\t\tif (sizeof(\$fcv_array) > 0)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->pog_query .= \" AND \";";
		$this->string .= "\n\t\t\tfor (\$i=0, \$c=sizeof(\$fcv_array); \$i<\$c; \$i++)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\tif (sizeof(\$fcv_array[\$i]) == 1)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$this->pog_query .= \" \".\$fcv_array[\$i][0].\" \";";
		$this->string .= "\n\t\t\t\t\tcontinue;";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\telse";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\tif (\$i > 0 && sizeof(\$fcv_array[\$i-1]) != 1)";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$this->pog_query .= \" AND \";";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\tif (isset(\$".strtolower($sibling)."->pog_attribute_type[\$fcv_array[\$i][0]]['db_attributes']) && \$".strtolower($sibling)."->pog_attribute_type[\$fcv_array[\$i][0]]['db_attributes'][0] != 'NUMERIC' && \$".strtolower($sibling)."->pog_attribute_type[\$fcv_array[\$i][0]]['db_attributes'][0] != 'SET')";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\tif (\$GLOBALS['configuration']['db_encoding'] == 1)";
		$this->string .= "\n\t\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\t\$value = POG_Base::IsColumn(\$fcv_array[\$i][2]) ? \"BASE64_DECODE(\".\$fcv_array[\$i][2].\")\" : \"'\".\$fcv_array[\$i][2].\"'\";";
		$this->string .= "\n\t\t\t\t\t\t\t\$this->pog_query .= \"BASE64_DECODE(`\".\$fcv_array[\$i][0].\"`) \".\$fcv_array[\$i][1].\" \".\$value;";
		$this->string .= "\n\t\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\t\telse";
		$this->string .= "\n\t\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\t\$value =  POG_Base::IsColumn(\$fcv_array[\$i][2]) ? \$fcv_array[\$i][2] : \"'\".\$this->Escape(\$fcv_array[\$i][2]).\"'\";";
		$this->string .= "\n\t\t\t\t\t\t\t\$this->pog_query .= \"a.`\".\$fcv_array[\$i][0].\"` \".\$fcv_array[\$i][1].\" \".\$value;";
		$this->string .= "\n\t\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\telse";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$value = POG_Base::IsColumn(\$fcv_array[\$i][2]) ? \$fcv_array[\$i][2] : \"'\".\$fcv_array[\$i][2].\"'\";";
		$this->string .= "\n\t\t\t\t\t\t\$this->pog_query .= \"a.`\".\$fcv_array[\$i][0].\"` \".\$fcv_array[\$i][1].\" \".\$value;";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\tif (\$sortBy != '')";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\tif (isset(\$".strtolower($sibling)."->pog_attribute_type[\$sortBy]['db_attributes']) && \$".strtolower($sibling)."->pog_attribute_type[\$sortBy]['db_attributes'][0] != 'NUMERIC' && \$".strtolower($sibling)."->pog_attribute_type[\$sortBy]['db_attributes'][0] != 'SET')";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\tif (\$GLOBALS['configuration']['db_encoding'] == 1)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$sortBy = \"BASE64_DECODE(a.\$sortBy) \";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\telse";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$sortBy = \"a.\$sortBy \";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$sortBy = \"a.\$sortBy \";";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\telse";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$sortBy = \"a.".strtolower($sibling)."id\";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\t\$this->pog_query .= \" order by \".\$sortBy.\" \".(\$ascending ? \"asc\" : \"desc\").\" \$sqlLimit\";";
		$this->string .= "\n\t\t\$cursor = Database::Reader(\$this->pog_query, \$connection);";
		$this->string .= "\n\t\twhile(\$rows = Database::Read(\$cursor))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$".strtolower($sibling)." = new ".$sibling."();";
		$this->string .= "\n\t\t\tforeach (\$".strtolower($sibling)."->pog_attribute_type as \$attribute_name => \$attrubute_type)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\tif (\$attrubute_type['db_attributes'][1] != \"HASMANY\" && \$attrubute_type['db_attributes'][1] != \"JOIN\")";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\tif (\$attrubute_type['db_attributes'][1] == \"BELONGSTO\")";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$".strtolower($sibling)."->{strtolower(\$attribute_name).'Id'} = \$rows[strtolower(\$attribute_name).'id'];";
		$this->string .= "\n\t\t\t\t\t\tcontinue;";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\t\$".strtolower($sibling)."->{\$attribute_name} = \$this->Unescape(\$rows[strtolower(\$attribute_name)]);";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\t\$".strtolower($sibling)."List[] = $".strtolower($sibling).";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn \$".strtolower($sibling)."List;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSetAssociationsFunction($sibling)
	{
		$misc = new Misc(array());
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Creates mappings between this and all objects in the $sibling List array. Any existing mapping will become orphan(s)",'',"null");
		$this->string .= "\tfunction Set".ucfirst(strtolower($sibling))."List(&\$".strtolower($sibling)."List)\n\t{";
		$this->string .= "\n\t\t\$map = new ".$misc->MappingName($this->objectName, $sibling)."();";
		$this->string .= "\n\t\t\$map->RemoveMapping(\$this);";
		$this->string .= "\n\t\t\$this->_".strtolower($sibling)."List = \$".strtolower($sibling)."List;";
		$this->string .= "\n\t}";
	}

}
?>