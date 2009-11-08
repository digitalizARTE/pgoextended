String.prototype.trim = function(){ return this.replace( /^\s+|\s+$/g,'' ) }

function chglabelpog( el )
{
	var name = '';
	if( el != undefined )
	{
		var elments = $$( ".greybox div input[type=text]" ); 
		var elments2 = $$( ".greybox div select" );
		var elments3 = $$( ".greybox div input[type=checkbox]" ); 
		if( el.checked )
		{
			$( 'lblpogex' ).update( 'POG Extendido' );
		}
		else
		{
			$( 'lblpogex' ).update( 'POG Clasico' );
		}
		elments.each( function( elem )
		{
			if( elem.up( 'div' ).visible() )
			{
				if( elem.visible() )
				{
					name = elem.id.split( '_' )[ 0 ];
					if( name == 'fielddefault' || name == 'fielddefaultdb' )
					{
						elem.disabled= ! el.checked;
					}
				}
			}
			else
			{
				throw $break;
			}
		} );
		elments2.each( function( elem )
		{
			if( elem.up( 'div' ).visible() )
			{
				if( elem.visible() )
				{
					ConvertDDLToTextfield2( elem );
				}
			}
			else
			{
				throw $break;
			}
		} );
 
		elments3.each( function( elem )
		{
			if( elem.up( 'div' ).visible() )
			{
				if( elem.visible() )
				{
					elem.disabled= ! el.checked;
				}
			}
			else
			{
				throw $break;
			}
		} );
	}
} // chglabelpog

function checkkeydown( elem, evt )
{
	var keyCode = document.layers?evt.which:document.all?event.keyCode:document.getElementById?evt.keyCode:0;
	if( keyCode == 27 )
	{
		elem.value = '';
		elem.style.display = 'none';
		num = elem.id.split( '_' )[ 1 ];
		el = $( 'type_' + num );
		el.style.display = 'inline';
	}
}

function AddField()
{
 var control;
	//var trs = document.getElementsByTagName( "div" );
	var trs = $$( 'div' );
	for( var w = 0; w < trs.length; w++ )
	{
		if( trs[ w ].style.display == "none" )
		{
			trs[ w ].style.display="block";
			try
			{
				//control = document.getElementById( "field"+trs[ w ].id );
				control = $( "field" + trs[ w ].id );
				try
				{
					control.focus();
				}
				catch( e ){ }
			}
			catch( e ){}
			break;
		}
	}
}
function ResetFields()
{
	$$( '.greybox div input[ "text" ], .objectname input[ "text" ]' ).each(
			function( elem )
			{
				elem.value = '';
				num = elem.id.split( '_' )[ 1 ];
				sid = 'ttype_' + num;
				if( elem.id == sid )
					elem.style.display = 'none';
			}
		 );
	$$( '.greybox div select' ).each(
			function( elem )
			{
				elem.value = 'VARCHAR( 255 )';
				elem.style.display = 'inline';
			}
		 );
	$$( '.greybox div input[ "checkbox" ]' ).each(
			function( elem )
			{
				elem.disabled= false;
				name = elem.id.split( '_' )[ 0 ];
				if( name == 'chkgetter'
				|| name == 'chksetter'
				|| name == 'chkinsert'
				|| name == 'chkupdate' )
					elem.checked = true;
				else
					elem.checked = false;
			}
		 );
}

function ConvertDDLToTextfield( id )
{
	var thisId=id
	var trs =document.getElementsByTagName( 'select' );
	for( var w=0; w<trs.length; w++ )
	{
		if( trs[ w ].id == thisId )
		{
			if( trs[ w ].value == 'OTHER' )
			{
				trs[ w ].style.display = 'none';
				trs2=document.getElementsByTagName( 'input' );
				for( var v=0; v<trs2.length; v++ )
				{
					if( trs2[ v ].id == "t"+thisId )
					{
						trs2[ v ].style.display= 'inline';
						trs2[ v ].focus();
						break;
					}
				}
			}
			break;
		}
	}
}

function ConvertDDLToTextfield2( elem )
{
	try
	{
		num = elem.id.split( '_' )[ 1 ];
		if( elem.value == 'OTHER' )
		{
			elem.style.display = 'none';
			trs2 = $( 'ttype_'+num );
			trs2.style.display = 'inline';
			trs2.focus();
		}
		else if( elem.value == 'HASMANY' || elem.value == 'BELONGSTO' || elem.value == 'JOIN' )
		{
			trs2=$( 'chkgetter_' + num );
			trs2.disabled= true;
			trs2=$( 'chksetter_' + num );
			trs2.disabled= true;
			trs2=$( 'chkencrypt_' + num );
			trs2.disabled= true;
			trs2=$( 'chklazyload_' + num );
			trs2.disabled= true;
		}
		else
		{
			trs2=$( 'chkgetter_' + num );
			trs2.disabled= false;
			trs2=$( 'chksetter_' + num );
			trs2.disabled= false;
			trs2=$( 'chkencrypt_' + num );
			trs2.disabled= false;
			trs2=$( 'chklazyload_' + num );
			trs2.disabled= false;
		}
	} catch( ex ){}
}

function FocusOnFirstField()
{
	var trs2=document.getElementById( "FirstField" );
	trs2.focus();
}

function IsPDO()
{
	var link, select;
	var trs2=document.getElementById( "wrapper" );
	if( trs2.value.toUpperCase() == "PDO" )
	{
		link=document.getElementById( "disappear" );
		link.style.display="none";
		trs2=document.getElementById( "PDOdriver" );
		trs2.value="mysql";
		trs2.style.display="inline";
	}
	else
	{
		var select = document.getElementById( "PDOdriver" );
		select.style.display="none";
		link=document.getElementById( "disappear" );
		link.style.display="inline";
	}
}

function CascadePhpVersion()
{
	var trs2=document.getElementById( "FirstField" );
	var select=document.getElementById( "wrapper" );
	select.length=0;
	if( trs2.value == "php5.1" )
	{
		optionsArray=new Array( "PDO", "POG" );
	}
	else
	{
		optionsArray=new Array( "POG" );
	}
	for( var i=0; i<optionsArray.length; i++ )
	{
		NewOpt=new Option;
		NewOpt.value=optionsArray[ i ].toLowerCase();
		NewOpt.text=optionsArray[ i ];
		select.options[ i ]=NewOpt;
	}
	IsPDO();
}

function GenerateSQLTypesForDriver( driver )
{
	for( var j=1; j<100; j++ )
	{
		var ddlist=document.getElementById( "type_"+j );
		ddlist.length=0;
		switch( driver )
		{
			case"mysql":
				optionsArray=new Array( "VARCHAR( 255 )", "TINYINT", "TEXT", "DATE", "SMALLINT", "MEDIUMINT", "INT", "BIGINT", "FLOAT", "DOUBLE", "DECIMAL", "DATETIME", "TIMESTAMP", "TIME", "YEAR", "CHAR( 255 )", "TINYBLOB", "TINYTEXT", "BLOB", "MEDIUMBLOB", "MEDIUMTEXT", "LONGBLOB", "LONGTEXT", "BINARY", "OTHER", "{ CHILD }", "{ PARENT }", "{ SIBLING }" );
				break;
			case"oci":
				break;
			case"dblib":
				optionsArray=new Array( "BIGINT", "BINARY", "BIT", "CHAR", "DATETIME", "DECIMAL", "FLOAT", "IMAGE", "INT", "MONEY", "NCHAR", "NTEXT", "NUMERIC", "NVARCHAR", "REAL", "SMALLDATETIME", "SMALLINT", "SMALLMONEY", "TEXT", "TIMESTAMP", "TINYINT", "UNIQUEIDENTIFIER", "VARBINARY", "VARCHAR( 255 )", "OTHER", "{ CHILD }", "{ PARENT }", "{ SIBLING }" );
				break;
			case"firebird":
				optionsArray=new Array( "BLOB", "CHAR", "CHAR( 1 )", "TIMESTAMP", "DECIMAL", "DOUBLE", "FLOAT", "INT64", "INTEGER", "NUMERIC", "SMALLINT", "VARCHAR( 255 )", "OTHER", "{ CHILD }", "{ PARENT }", "{ SIBLING }" );
				break;
			case"odbc":
				optionsArray=new Array( "BIGINT", "BINARY", "BIT", "CHAR", "DATETIME", "DECIMAL", "FLOAT", "IMAGE", "INT", "MONEY", "NCHAR", "NTEXT", "NUMERIC", "NVARCHAR", "REAL", "SMALLDATETIME", "SMALLINT", "SMALLMONEY", "TEXT", "TIMESTAMP", "TINYINT", "UNIQUEIDENTIFIER", "VARBINARY", "VARCHAR( 255 )", "OTHER", "{ CHILD }", "{ PARENT }", "{ SIBLING }" );
				break;
			case"pgsql":
				optionsArray=new Array( "BIGINT", "BIGSERIAL", "BIT", "BOOLEAN", "BOX", "BYTEA", "CIDR", "CIRCLE", "DATE", "DOUBLE PRECISION", "INET", "INTEGER", "LINE", "LSEG", "MACADDR", "MONEY", "OID", "PATH", "POINT", "POLYGON", "REAL", "SMALLINT", "SERIAL", "TEXT", "VARCHAR( 255 )", "OTHER", "{ CHILD }", "{ PARENT }", "{ SIBLING }" );
				break;
			case"sqlite":
				optionsArray=new Array( "TEXT", "NUMERIC", "INTEGER", "BLOB", "OTHER", "{ CHILD }", "{ PARENT }", "{ SIBLING }" );
				break;
		}
		for( var i=0; i<optionsArray.length; i++ )
		{
			NewOpt=new Option;
			if( optionsArray[ i ] == "{ CHILD }" )
			{
				NewOpt.value="HASMANY";
			}
			else if( optionsArray[ i ] == "{ PARENT }" )
			{
				NewOpt.value="BELONGSTO";
			}
			else if( optionsArray[ i ] == "{ SIBLING }" )
			{
				NewOpt.value="JOIN"
			}
			else
			{
				NewOpt.value=optionsArray[ i ];
			}
			NewOpt.text=optionsArray[ i ];
			ddlist.options[ i ]=NewOpt;
		}
	}
}

function Reposition( field, evt )
{
	var keyCode = document.layers?evt.which:document.all?event.keyCode:document.getElementById?evt.keyCode:0;
	var r='';
	var fieldNameParts = field.name.split( "_" );
	if( keyCode == 40 )
	{
		Swap( field.name, "fieldattribute_" + ( parseInt( fieldNameParts[ 1 ] ) + 1 ) );
	}
	else if( keyCode == 38 )
	{
		Swap( field.name, "fieldattribute_" + ( parseInt( fieldNameParts[ 1 ] ) - 1 ) );
	}
	return false
}

function Swap( fieldName1, fieldName2 )
{
	var elem, num, temp1, temp2, temp3;
	//attribute1
	var fieldNameParts = fieldName1.split( "_" );
	num = fieldNameParts[ 1 ];
	elem = document.getElementsByName( "fieldattribute_" + num );
	var attribute1 = elem[ 0 ];
	elem = document.getElementsByName( "ttype_" + num );
	var cf1 = elem[ 0 ];
	elem = document.getElementsByName( "type_" + num );
	var type1 = elem[ 0 ];
	elem = document.getElementsByName( 'chkisbool_' + num );
	var chkisbool1 = elem[ 0 ];
	elem = document.getElementsByName( 'chkgetter_' + num );
	var chkgetter1 = elem[ 0 ];
	elem = document.getElementsByName( 'chksetter_' + num );
	var chksetter1 = elem[ 0 ];
	elem = document.getElementsByName( 'chkencrypt_' + num );
	var chkencrypt1 = elem[ 0 ];
	elem = document.getElementsByName( 'fielddefault_' + num );
	var fielddefault1 = elem[ 0 ];
	elem = document.getElementsByName( 'fielddefaultdb_' + num );
	var fielddefaultdb1 = elem[ 0 ];
	elem = document.getElementsByName( 'chkinsert_' + num );
	var chkinsert1 = elem[ 0 ];
	elem = document.getElementsByName( 'chkupdate_' + num );
	var chkupdate1 = elem[ 0 ];

	//attribute2
	fieldNameParts = fieldName2.split( "_" );
	num = fieldNameParts[ 1 ];
	elem = document.getElementsByName( "fieldattribute_" + num );
	var attribute2 = elem[ 0 ];
	elem = document.getElementsByName( "ttype_" + num );
	var cf2 = elem[ 0 ];
	elem = document.getElementsByName( "type_" + num );
	var type2 = elem[ 0 ];
	elem = document.getElementsByName( 'chkisbool_' + num );
	var chkisbool2 = elem[ 0 ];
	elem = document.getElementsByName( 'chkgetter_' + num );
	var chkgetter2 = elem[ 0 ];
	elem = document.getElementsByName( 'chksetter_' + num );
	var chksetter2 = elem[ 0 ];
	elem = document.getElementsByName( 'chkencrypt_' + num );
	var chkencrypt2 = elem[ 0 ];
	elem = document.getElementsByName( 'fielddefault_' + num );
	var fielddefault2 = elem[ 0 ];
	elem = document.getElementsByName( 'fielddefaultdb_' + num );
	var fielddefaultdb2 = elem[ 0 ];
	elem = document.getElementsByName( 'chkinsert_' + num );
	var chkinsert2 = elem[ 0 ];
	elem = document.getElementsByName( 'chkupdate_' + num );
	var chkupdate2 = elem[ 0 ];

	//Cambio los atributos
	temp1 = attribute1.value;
	attribute1.value = attribute2.value;
	attribute2.value = temp1;
	//cambio los tipos
	if( cf1.value != "" )
		temp2 = cf1.value;
	else
		temp2 = type1.value;

	temp3 = cf1.value;
	if( cf2.value != "" )
	{
		cf1.value = cf2.value;
		type1.style.display = "none";
		cf1.style.display = "inline";
	}
	else
	{
		for( var w = 0; w < type1.length; w++ )
		{
			if( type1.options[ w ].value == type2.value )
			{
				type1.selectedIndex = w;
				break;
			}
		}
		type1.style.display = "inline";
		cf1.value = "";
		cf1.style.display = "none"
	}

	if( temp3 != "" )
	{
		cf2.value = temp2;
		type2.style.display = "none";
		cf2.style.display = "inline";
	}
	else
	{
		for( var w = 0; w < type2.length; w++ )
		{
			if( type2.options[ w ].value == temp2 )
			{
				type2.selectedIndex = w;
				break;
			}
		}
		type2.style.display = "inline";
		cf2.value = "";
		cf2.style.display = "none";
	}

	//Cambio si es booleano
	var tmpchkisbool = chkisbool1.checked;
	chkisbool1.checked = chkisbool2.checked;
	chkisbool2.checked = tmpchkisbool;

	var tmpchkgetter = chkgetter1.checked;
	chkgetter1.checked = chkgetter2.checked;
	chkgetter2.checked = tmpchkgetter;

	var tmpchksetter = chksetter1.checked;
	chksetter1.checked = chksetter2.checked;
	chksetter2.checked = tmpchksetter;

	var tmpchkencrypt = chkencrypt1.checked;
	chkencrypt1.checked = chkencrypt2.checked;
	chkencrypt2.checked = tmpchkencrypt;

	var tmpfielddefault = fielddefault1.vale;
	fielddefault1.vale = fielddefault2.vale;
	fielddefault2.vale = tmpfielddefault;

	var tmpfielddefaultdb = fielddefaultdb1.value;
	fielddefaultdb1.value = fielddefaultdb2.value;
	fielddefaultdb2.value = tmpfielddefaultdb;

	var tmpchkinsert = chkinsert1.checked;
	chkinsert1.checked = chkinsert2.checked;
	chkinsert2.checked = tmpchkinsert;

	var tmpchkupdate = chkupdate1.checked;
	chkupdate1.checked = chkupdate2.checked;
	chkupdate2.checked = tmpchkupdate;

	attribute2.focus();
} // Swap

function Swap_( fieldName1, fieldName2 )
{
	//attribute1
	var fieldNameParts = fieldName1.split( "_" );
	var attribute1 = document.getElementsByName( "fieldattribute_" + fieldNameParts[ 1 ] );
	var cf1 = document.getElementsByName( "ttype_" + fieldNameParts[ 1 ] );
	var type1 = document.getElementsByName( "type_" + fieldNameParts[ 1 ] );

	//attribute2
	fieldNameParts = fieldName2.split( "_" );
	var attribute2 = document.getElementsByName( "fieldattribute_" + fieldNameParts[ 1 ] );
	var cf2 = document.getElementsByName( "ttype_" + fieldNameParts[ 1 ] );
	var type2 = document.getElementsByName( "type_" +fieldNameParts[ 1 ] );

	var temp1 = attribute1[ 0 ].value;
	if( cf1[ 0 ].value != "" )
	{
		var temp2 = cf1[ 0 ].value;
	}
	else
	{
		var temp2 = type1[ 0 ].value;
	}
	attribute1[ 0 ].value = attribute2[ 0 ].value;
	attribute2[ 0 ].value = temp1;
	var temp3 = cf1[ 0 ].value;
	if( cf2[ 0 ].value != "" )
	{
		cf1[ 0 ].value = cf2[ 0 ].value;
		type1[ 0 ].style.display = "none";
		cf1[ 0 ].style.display = "inline";
	}
	else
	{
		for( var w = 0; w < type1[ 0 ].length; w++ )
		{
			if( type1[ 0 ].options[ w ].value == type2[ 0 ].value )
			{
				type1[ 0 ].selectedIndex = w;
				break;
			}
		}
		type1[ 0 ].style.display = "inline";
		cf1[ 0 ].value = "";
		cf1[ 0 ].style.display = "none"
	}

	if( temp3 != "" )
	{
		cf2[ 0 ].value = temp2;
		type2[ 0 ].style.display = "none";
		cf2[ 0 ].style.display = "inline";
	}
	else
	{
		for( var w = 0; w < type2[ 0 ].length; w++ )
		{
			if( type2[ 0 ].options[ w ].value == temp2 )
			{
				type2[ 0 ].selectedIndex = w;
				break;
			}
		}
		type2[ 0 ].style.display = "inline";
		cf2[ 0 ].value = "";
		cf2[ 0 ].style.display = "none";
	}
	attribute2[ 0 ].focus();
}

//TODO revisar detecta nombres de atrributos duplicados
function warnmininput( evt )
{
	var inputcount = 0;
	//var trs = document.getElementsByTagName( "input" );
	var allVals = new Array();
	var allCount = 0;
	var inputcount = 0;
	var boutbybreak = false;
	var typeCount = 0;
	var sobjName = $( 'objName' ).value.strip().toLowerCase();
	$$( 'input.attributo' ).each(
		function( elem )
		{
			if( elem.up( 'div' ).visible() )
			{
				var valor = elem.value.strip().toLowerCase();
				if( valor != '' && valor.length > 0 )
				{
					inputcount++;
					//if( InArray( allVals, valor ) )
					if( allVals.find( function( s ) { return s == valor ; } ) )
					{
						//alert( "Warning:\nYou have more than 1 attribute with the same value. Attributes must be unique." );
						alert( "Advertencia:\nUsted tiene más de 1 atributo con el mismo valor. Atributos deben ser únicos." );
						boutbybreak = true;
						throw $break;
						return false;
					}
					else if( valor == sobjName )
					{
						//alert( "An object cannot relate to itself recursively. Make sure attribute names are different from the object name." );
						alert( "Advertencia:\nUn objeto no puede referirse a sí mismo recursivamente.\n Asegurese de que los nombres de atributo son diferentes de el nombre del objeto.\nPara implementar relaciones reflexibas entre objetos,\ncree una nueva clase que herede de esta clase\ncon otro nombre y agregue ese nombre como atributo de la clase\ny el tipo de relacion que quiere definir." );
						boutbybreak = true;
						throw $break;
						return false;
					}
					else
					{
						allVals.push( valor );
					}
				}
			}
			else
			{
				throw $break;
			}
		}
	 );
	if( boutbybreak )
	{
		Event.stop( evt );
		return false;
	}

	if( inputcount > 0 )
	{
		//trs = document.getElementsByTagName( "select" );
		$$( "select" ).each(
			function( elem )
			{
				if( elem.value == 'HASMANY' || elem.value == 'BELONGSTO' || elem.value == 'JOIN' )
				{
					typeCount++;
				}
			}
		 );
		if( typeCount >= inputcount )
		{
			alert( typeCount + ' ' + inputcount );
			//alert( "Warning:\nYou need to have at least 1 non-parent/child attribute. Else POG will generate an invalid PHP object" );
			alert( "Advertencia:Usted necesita tener al menos 1 non-parent/child atributo.\nOtras POG generara un objeto no valido PHP" );
			Event.stop( evt );
			return false;
		}
	}
	else
	{
		// alert( inputcount );
		//alert( "Warning:\nWithout any object attributes, POG may generate an invalid PHP object. You need to have at least 1 non-parent/child attribute" );
		alert( "Advertencia:\nSin los atributos de los objetos, POG puede generar un objeto no valido PHP.\nUsted necesita tener al menos 1 non-parent/child atributo" );
		Event.stop( evt );
		return false;
	}
	return true;
}


function WarnMinInput_()
{
	var inputcount = 0;
	var trs = document.getElementsByTagName( "input" );
	var allVals = new Array();
	var allCount = 0;
	for( var w = 0; w < trs.length; w++ )
	{
		if( trs[ w ].value.trim() != ""
		&& trs[ w ].value.trim().length > 0
		&& trs[ w ].type != "hidden"
		&& trs[ w ].name.trim() != "object" )
		{
			inputcount++;
			//if( allVals, trs[ w ].value != '' && InArray( allVals, trs[ w ].value ) )
			if( trs[ w ].value.trim() != ''
			&& trs[ w ].value.trim().length > 0
			&& InArray( allVals, trs[ w ].value.trim() ) )
			{
				alert( "Warning:\nYou have more than 1 attribute with the same value. Attributes must be unique." );
				return;
			}
			else if( trs[ w ].value.trim() == document.getElementById( 'objName' ).value.trim() )
			{
				alert( "An object cannot relate to itself recursively. Make sure attribute names are different from the object name." );
				return;
			}
			else
			{
				if( trs[ w ].value.trim() != "" && trs[ w ].value.trim().length > 0 )
				{
					allVals.push( trs[ w ].value.trim() );
					allCount++;
				}
			}
		}
	}
	if( inputcount > 0 )
	{
		var typeCount = 0;
		trs = document.getElementsByTagName( "select" );
		for( var w = 0; w<trs.length; w++ )
		{
			if( trs[ w ].value == "HASMANY" || trs[ w ].value == "BELONGSTO" || trs[ w ].value == "JOIN" )
			{
				typeCount++;
			}
		}
		if( typeCount >= inputcount )
		{
			alert( "Warning:\nYou need to have at least 1 non-parent/child attribute. Else POG will generate an invalid PHP object" );
		}
	}
	else
	{
		alert( "Warning:\nWithout any object attributes, POG may generate an invalid PHP object. You need to have at least 1 non-parent/child attribute" );
	}
	return false;
}

function InArray( array, val )
{
	// var found = false;
	/*
	for( var i=0; i<array.length; i++ )
	{
		if( array[ i ] == val )
		{
			found = true;
			break;
		}
	}
	*/
	var i = 0;
	while( i<array.length && array[ i ] != val )
	{
		i++
	}
	//return found;
	return ( i<array.length && array[ i ] == val );
}
window.onload = function() {
	//AddField();
};