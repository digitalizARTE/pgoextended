<option value="VARCHAR(255)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(255)'?"selected":'')?>>VARCHAR(255)</option>
<option value="VARCHAR(150)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(150)'?"selected":'')?>>VARCHAR(150)</option>
<option value="VARCHAR(100)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(100)'?"selected":'')?>>VARCHAR(100)</option>
<option value="VARCHAR(50)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(50)'?"selected":'')?>>VARCHAR(50)</option>
<option value="VARCHAR(30)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(30)'?"selected":'')?>>VARCHAR(30)</option>
<option value="VARCHAR(20)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(20)'?"selected":'')?>>VARCHAR(20)</option>
<option value="VARCHAR(10)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(10)'?"selected":'')?>>VARCHAR(10)</option>
<option value="VARCHAR(5)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(5)'?"selected":'')?>>VARCHAR(5)</option>
<option value="VARCHAR(3)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(3)'?"selected":'')?>>VARCHAR(3)</option>
<option value="VARCHAR(2)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(2)'?"selected":'')?>>VARCHAR(2)</option>
<option value="VARCHAR(1)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='VARCHAR(1)'?"selected":'')?>>VARCHAR(1)</option>
<option value="TINYINT" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='TINYINT'?"selected":'')?>>TINYINT</option>
<option value="TEXT" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='TEXT'?"selected":'')?>>TEXT</option>
<option value="DATE" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='DATE'?"selected":'')?>>DATE</option>
<option value="SMALLINT" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='SMALLINT'?"selected":'')?>>SMALLINT</option>
<option value="MEDIUMINT" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='MEDIUMINT'?"selected":'')?>>MEDIUMINT</option>
<option value="INT" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='INT'?"selected":'')?>>INT</option>
<option value="BIGINT" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='BIGINT'?"selected":'')?>>BIGINT</option>
<option value="FLOAT" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='FLOAT'?"selected":'')?>>FLOAT</option>
<option value="DOUBLE" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='DOUBLE'?"selected":'')?>>DOUBLE</option>
<option value="DECIMAL" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='DECIMAL'?"selected":'')?>>DECIMAL</option>
<option value="DATETIME" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='DATETIME'?"selected":'')?>>DATETIME</option>
<option value="TIMESTAMP" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='TIMESTAMP'?"selected":'')?>>TIMESTAMP</option>
<option value="TIME" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='TIME'?"selected":'')?>>TIME</option>
<option value="YEAR" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='YEAR'?"selected":'')?>>YEAR</option>
<option value="CHAR(255)" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='CHAR(255)'?"selected":'')?>>CHAR(255)</option>
<option value="TINYBLOB" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='TINYBLOB'?"selected":'')?>>TINYBLOB</option>
<option value="TINYTEXT" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='TINYTEXT'?"selected":'')?>>TINYTEXT</option>
<option value="BLOB" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='BLOB'?"selected":'')?>>BLOB</option>
<option value="MEDIUMBLOB" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='MEDIUMBLOB'?"selected":'')?>>MEDIUMBLOB</option>
<option value="MEDIUMTEXT" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='MEDIUMTEXT'?"selected":'')?>>MEDIUMTEXT</option>
<option value="LONGBLOB" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='LONGBLOB'?"selected":'')?>>LONGBLOB</option>
<option value="LONGTEXT" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='LONGTEXT'?"selected":'')?>>LONGTEXT</option>
<option value="BINARY" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='BINARY'?"selected":'')?>>BINARY</option>
<option value="OTHER">OTHER...</option>
<option value="HASMANY" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='HASMANY'?"selected":'')?>>{ CHILD }</option>
<option value="BELONGSTO" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='BELONGSTO'?"selected":'')?>>{ PARENT }</option>
<option value="JOIN" <?=(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='JOIN'?"selected":'')?>>{ SIBLING }</option>