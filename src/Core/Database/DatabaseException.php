<?php

declare(strict_types=1);

namespace Lea\Core\Database;

use Lea\Core\Logger\Logger;
use mysqli_sql_exception;
use Lea\Core\Reflection\ReflectionClass;
use Lea\Core\Reflection\ReflectionProperty;
use Lea\Response\Response;

final class DatabaseException
{
    private const SQL_TABLE_NOT_EXISTS = 1146;
    private const SQL_UNKNOWN_COLUMN = 1054;
    private const SQL_SYMTAMX_ERRORM = 1064;
    private const SQL_MISSING_DEFAULT_VALUE = 1364;
    private const SQL_FOREIGN_KEY_COHESION = 1451;
    private const SQL_INCORRECT_DATE_VALUE = 1292;
    private const SQL_OUT_OF_RANGE = 1264;
    private const SQL_FOREIGN_KEY_CONSTRAINTS_FAIL = 1452;
    private const SQL_CREATING_REFERENCE_TO_NON_EXISTING_TABLE = 1005;
    private const SQL_DUPLICATE_COLUMN_NAME = 1060;

    public static function handleSqlException(mysqli_sql_exception $e, $connection, object $object, string $query, $parent_class = null)
    {
        $message = $e->getMessage();
        switch ($e->getCode()) {
            case self::SQL_CREATING_REFERENCE_TO_NON_EXISTING_TABLE:
            case self::SQL_TABLE_NOT_EXISTS:
                return self::getCreateTableQueryRecursive($object);
            case self::SQL_UNKNOWN_COLUMN:
                return self::getAlterTableQuery($object, $connection, $parent_class);
            case self::SQL_MISSING_DEFAULT_VALUE:
                Response::internalServerError("TODO - Default Value failure: " . $e->getCode() . "\n" . $e->getMessage());
                break;
            case self::SQL_FOREIGN_KEY_CONSTRAINTS_FAIL:
            case self::SQL_FOREIGN_KEY_COHESION:
                $substr = substr($message, (int)strpos($message, "FOREIGN KEY") + 12);
                $field = substr($substr, 0, (int)strpos($substr, "REFERENCES") - 1);
                $field = ltrim($field, '(`');
                $field = rtrim($field, '`)');
                $field = KeyFormatter::convertToKey($field);
                Response::badRequest("Invalid value of field that has reference: " . $field);
                break;
            case self::SQL_INCORRECT_DATE_VALUE:
                Response::internalServerError($e->getMessage());
                break;
            case self::SQL_SYMTAMX_ERRORM:
                Response::internalServerError("Symtamx error \n $query");
                break;
            case self::SQL_OUT_OF_RANGE:
                $field = substr($message, strpos($message, "'") + 1);
                $field = substr($field, 0, strpos($field, "'"));
                Response::badRequest("Passed value of ``" . $field . "`` out of range - Contact with Administrator to increase it");
                break;
            case self::SQL_DUPLICATE_COLUMN_NAME:
                Logger::save($e);
                Response::notImplemented("SQL_ExceptionNotImplemented");
                break;
            default:
                Response::internalServerError("Error not handled yet: " . $e->getCode() . "\n" . $e->getMessage());
        }

        return "";
    }

    private static function getAlterTableQuery(object $object, $connection, string $parent_class = null): array
    {
        $described = self::describeTable($object, $connection);
        $table_keys = KeyFormatter::convertArrayToKeys($described);
        $entity_methods = $object->getGetters();
        $last = false;
        foreach ($entity_methods as $method) {
            $key = KeyFormatter::processPascalToSnake(str_replace('get', '', $method));
            if (in_array($key, $table_keys)) {
                $last = $key;
                continue;
            }
            try {
                $reflector = new ReflectionProperty(get_class($object), $key);
                if ($reflector->isObject())
                    continue;
            } catch (\ReflectionException $e) {
            }
            $query = 'ALTER TABLE ' . KeyFormatter::getTableNameByObject($object) . ' ADD ';
            $query .= self::parseReflectProperty($reflector);
            $query .= $last ?  ' AFTER ' . KeyFormatter::convertKeyToColumn($last) . ';' : ";";
            $queries[] = $query;
        }
        if ($parent_class) {
            $query = 'ALTER TABLE ' . KeyFormatter::getTableNameByObject($object) . ' ADD ';
            $queries[] = $query . self::getReferencedKeyColumn($parent_class) . ' INT NOT NULL';
            $queries[] = self::getForeignKeyConstraint(KeyFormatter::getTableNameByObject($object), KeyFormatter::getTableNameByClass($parent_class), self::getReferencedKeyColumn($parent_class));
        }
        // TODO - polish collate

        return $queries ?? [];
    }

    private static function getCreateTableQueryRecursive(object $object, string $parent_table = null): array
    {
        if(str_contains($object->getNamespace(), "View")) {
            $message = 'View not exists for: ' . $object->getNamespace();
            Logger::save($message);
            Response::notImplemented($message);
        }
        $table_name = KeyFormatter::getTableNameByObject($object);
        $ddl = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' (';
        try {
            $class = new ReflectionClass($object);
            $primitive = $class->getPrimitiveProperties();
            $referenced = $object->getReferencedProperties();
            $referenced = KeyFormatter::processListOfGettersToToSnakeCase($referenced);
        } catch (\ReflectionException $e) {
        }
        $columns = self::parseReflectProperties($primitive);
        if ($parent_table) {
            $foreign_column = self::getReferencedKeyColumn($parent_table);
            $columns .= ', ' . $foreign_column . ' INT NOT NULL';
            $alter_foreing_constraint = self::getForeignKeyConstraint($table_name, KeyFormatter::getTableNameByClass($parent_table), $foreign_column);
        }
        if ($referenced) {
            foreach ($referenced as $key) {
                $foreign_column = KeyFormatter::convertKeyToColumn($key);
                $Class = str_replace('_id', '', $key);
                $Class = self::getEntityClass($Class);
                if ($Class)
                    $parent_table = KeyFormatter::getTableNameByClass($Class);
                // if ($parent_table !== $tablename && $Class !== null) 
                // $alter_references = array_merge($alter_references ?? [], self::getCreateTableQueryRecursive(new $Class));
                if ($parent_table)
                    $alter_references[] = self::getForeignKeyConstraint($table_name, $parent_table, $foreign_column);
            }
        }
        $ddl .= $columns;
        $ddl .= ') CHARACTER SET utf8 COLLATE utf8_polish_ci';
        $ddl .= self::getEndBracket();
        $queries[] = $ddl;
        if ($parent_table && isset($alter_foreing_constraint))
            $queries[] = $alter_foreing_constraint;
        if ($alter_references ?? false)
            $queries = array_merge($queries, $alter_references);
        $child_objects = $class->getObjectProperties();
        foreach ($child_objects as $obj) {
            $parent = $object->getClassName();
            $Class = $obj->getType2();
            $obj = new $Class;
            $children_queries = array_merge($children_queries ?? [], self::getCreateTableQueryRecursive($obj, $parent));
        }
        return array_merge($queries, $children_queries ?? []);
    }

    private static function parseReflectProperties(iterable $properties): string
    {
        $columns = "";
        foreach ($properties as $property) {
            $columns .= self::parseReflectProperty($property) . ", ";
        }
        return rtrim($columns, ", ");
    }

    private static function parseReflectProperty(ReflectionProperty $property): string
    {
        $comment = $property->getDocComment();
        /* TODO - DefaultValue */
        $column_name = KeyFormatter::convertKeyToColumn($property->getName());
        $column_properties = self::getVarTypeFromComment($comment);
        if ($column_name == '`fld_Id`') {
            $column_properties = $column_properties . ' NOT NULL PRIMARY KEY AUTO_INCREMENT';
        }

        return $column_name . " " . $column_properties;
    }

    private static function getEndBracket(): string
    {
        return ";";
    }

    private static function getAlterTableAddPrimaryKey(string $tablename): string
    {
        return 'ALTER TABLE ' . $tablename . ' ADD CONSTRAINT PK_' . $tablename . ' PRIMARY KEY (`fld_Id`);';
    }

    private static function getForeignKeyConstraint(string $tbl_tablename, string $tbl_parent_table_name, string $fld_Parent): string
    {
        $formatted_parent = rtrim(ltrim($tbl_tablename, '`'), '`');
        $formatted_field = rtrim(ltrim($fld_Parent, '`'), '`');
        return 'ALTER TABLE ' . $tbl_tablename . ' ADD CONSTRAINT FK_' . $formatted_parent . '_' . $formatted_field . ' FOREIGN KEY (' . $fld_Parent .  ') REFERENCES ' . $tbl_parent_table_name . ' (`fld_Id`);';
    }

    private static function getReferencedKeyColumn(string $parent): string
    {
        $column = KeyFormatter::convertKeyToColumn($parent);
        $column = rtrim($column, '`');
        $column .= 'Id`';

        return $column;
    }

    private static function describeTable(object $object, $connection): array
    {
        $query = "DESCRIBE ";
        $query .= KeyFormatter::getTableNameByObject($object);
        $result = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row['Field'];
        }

        return $list ?? [];
    }

    private static function getVarTypeFromComment($comment): string
    {
        if ($comment === FALSE)
            return "VARCHAR(150)";
        $lines = explode("\n", $comment);
        $strictType = self::getStrictTypeFromTokenArray($lines);
        switch ($strictType) {
            case "INT":
            case "INTEGER":
                return "INT";
            case "TEXT":
            case "JSON":
                return "TEXT";
            case "DATE":
                return "DATE";
            case "DATETIME":
                return "DATETIME";
            case "BOOL":
                return "TINYINT(1)";
            default:
                return "VARCHAR(150)";
        }
    }

    private static function getStrictTypeFromTokenArray(array $lines): ?string
    {
        foreach ($lines as $line) {
            if ((int)strpos($line, "@var")) {
                $tokens = explode(" ", $line);
                $index = array_search("@var", $tokens);

                return strtoupper($tokens[$index + 1]);
            }
        }

        return "VARCHAR(150)";
    }

    private static function getEntityClass(string $needle): ?string
    {
        $needle = KeyFormatter::processSnakeToPascal($needle);
        $classes = get_declared_classes();
        foreach ($classes as $registered_class) {
            if (!str_contains($registered_class, $needle))
                continue;
            $tokens = explode("\\", $registered_class);
            $Class = end($tokens);
            if ($Class === $needle)
                return $registered_class;
        }

        return null;
    }
}
