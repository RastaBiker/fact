<?
class DataBase {

  private static $db = null; // ������������ ��������� ������, ����� �� ��������� ��������� �����������
  private $mysqli; // ������������� ����������
  private $sym_query = "{?}"; // "������ �������� � �������"

  /* ��������� ���������� ������. ���� �� ��� ����������, �� ������������, ���� ��� �� ����, �� �������� � ������������ (������� Singleton) */
  public static function getDB() {
    if (self::$db == null) self::$db = new DataBase();
    return self::$db;
  }

  /* private-�����������, �������������� � ���� ������, ��������������� ������ � ��������� ���������� */
  private function __construct() {
    $this->mysqli = new mysqli("localhost", "fact", "O1t2G5l3", "fact");
    $this->mysqli->query("SET lc_time_names = 'ru_RU'");
    $this->mysqli->query("SET NAMES 'utf8'");
  }

  /* ��������������� �����, ������� �������� "������ �������� � �������" �� ���������� ��������, ������� �������� ����� "������� ������������" */
  private function getQuery($query, $params) {
    if ($params) {
      for ($i = 0; $i < count($params); $i++) {
        $pos = strpos($query, $this->sym_query);
        $arg = "'".$this->mysqli->real_escape_string($params[$i])."'";
        $query = substr_replace($query, $arg, $pos, strlen($this->sym_query));
    }
    }
    return $query;
  }

  /* SELECT-�����, ������������ ������� ����������� */
  public function select($query, $params = false) {
    $result_set = $this->mysqli->query($this->getQuery($query, $params));
    if (!$result_set) return false;
    return $this->resultSetToArray($result_set);
  }

  /* SELECT-�����, ������������ ���� ������ � ����������� */
  public function selectRow($query, $params = false) {
    $result_set = $this->mysqli->query($this->getQuery($query, $params));
    if ($result_set->num_rows != 1) return false;
    else return $result_set->fetch_assoc();
  }

  /* SELECT-�����, ������������ �������� �� ���������� ������ */
  public function selectCell($query, $params = false) {
    $result_set = $this->mysqli->query($this->getQuery($query, $params));
    if ((!$result_set) || ($result_set->num_rows != 1)) return false;
    else {
      $arr = array_values($result_set->fetch_assoc());
      return $arr[0];
    }
  }

  /* ��-SELECT ������ (INSERT, UPDATE, DELETE). ���� ������ INSERT, �� ������������ id ��������� ����������� ������ */
  public function query($query, $params = false) {
    $success = $this->mysqli->query($this->getQuery($query, $params));
    if ($success) {
      if ($this->mysqli->insert_id === 0) return true;
      else return $this->mysqli->insert_id;
    }
    else return false;
  }

  /* �������������� result_set � ��������� ������ */
  private function resultSetToArray($result_set) {
    $array = array();
    while (($row = $result_set->fetch_assoc()) != false) {
      $array[] = $row;
    }
    return $array;
  }

  /* ��� ����������� ������� ����������� ���������� � ����� ������ */
  public function __destruct() {
    if ($this->mysqli) $this->mysqli->close();
  }
}
?>