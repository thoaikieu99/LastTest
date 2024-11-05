<?php

class Database
{
    /**
     * $host Chứa thông tin host
     * @var string
     */
    private $host = '47.129.182.111';
    /**
     * $username Tài khoản truy cập mysql
     * @var string
     */
    private $username = 'monty';
    /**
     * $password Mật khẩu truy cập sql
     * @var string
     */
    private $password = 'some_pass';
    /**
     * $databaseName Tên Database các bạn muốn kết nối
     * @var string
     */
    private $databaseName = 'audiot';
    /**
     * $charset Dạng ký tự
     * @var string
     */
    private $charset = 'utf8mb4';
    /**
     * $conn Lưu trữ lớp kết nối
     * @var [objetc]
     */
    private $conn;
    /**
     * __construct Hàm khởi tạo
     * @return void;
     */
    public function __construct()
    {
        $this->connect();
    }
    /**
     * connect Kết nối
     * @return void
     */
    public function connect()
    {
        if (!$this->conn) {
            $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->databaseName);
            if (mysqli_connect_errno()) {
                echo 'Failed: ' . mysqli_connect_error();
                die();
            }
            mysqli_set_charset($this->conn, $this->charset);
        }
    }
    /**
     * disConnect Ngắt kết nối
     * @return void
     */
    public function disConnect()
    {
        if ($this->conn)
            mysqli_close($this->conn);
    }
    /**
     * error Hiển thị lỗi
     * @return string
     */
    public function error()
    {
        if ($this->conn)
            return mysqli_error($this->conn);
        else
            return false;
    }
    /**
     * insert thêm dữ liẹu
     * @param string $table tên bảng muốn thêm, array $data dữ liệu cần thêm
     * @return boolean
     */
    public function insert($table = '', $data = [])
    {
        $keys = '';
        $values = '';
        foreach ($data as $key => $value) {
            $keys .= ',' . $key;
            $values .= ',"' . mysqli_real_escape_string($this->conn, $value) . '"';
        }
        $sql = 'INSERT INTO ' . $table . '(' . trim($keys, ',') . ') VALUES (' . trim($values, ',') . ')';
        return mysqli_query($this->conn, $sql);
    }
    /**
     * update sửa dữ liệu
     * @param string $table tên bảng muốn sửa, array $data dữ liệu cần sửa, array|int $id điều kiện
     * @return boolean
     */
    public function update($table = '', $data = [], $id)
    {
        $content = '';
        $where = 'id = ' . $id;
        foreach ($data as $key => $value) {
            $content .= ',' . $key . '="' . mysqli_real_escape_string($this->conn, $value) . '"';
        }
        $sql = 'UPDATE ' . $table . ' SET ' . trim($content, ',') . ' WHERE ' . $where;
        return mysqli_query($this->conn, $sql);
    }
    /**
     * delete xóa dữ liệu
     * @param string $table tên bảng muốn xóa, array|int điều kiện
     * @return boolean
     */
    public function delete($table = '', $id = [])
    {
        $content = '';
        if (is_integer($id))
            $where = 'id = ' . $id;
        else if (is_array($id) && count($id) == 1) {
            $listKey = array_keys($id);
            $where = $listKey[0] . '=' . $id[$listKey[0]];
        } else
            die('Không thể có nhiều hơn 1 khóa chính và id truyền vào phải là số');
        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;
        return mysqli_query($this->conn, $sql);
    }
    /**
     * getObject lấy hết dữ liệu trong bảng trả về mảng đối tượng
     * @param string $table tên bảng muốn lấy ra dữ liệu
     * @return array objetc
     */
    public function getObject($table = '')
    {
        $sql = 'SELECT * FROM ' . $table;
        $data = null;
        if ($result = mysqli_query($this->conn, $sql)) {
            while ($row = mysqli_fetch_object($result)) {
                $data[] = $row;
            }
            mysqli_free_result($result);
            return $data;
        }
        return false;
    }
    /**
     * getObject lấy hết dữ liệu trong bảng trả về mảng dữ liệu
     * @param string $table tên bảng muốn lấy dữ liệu
     * @return array
     */
    public function getArray($table = '')
    {
        $sql = 'SELECT * FROM ' . $table;
        $data = null;
        if ($result = mysqli_query($this->conn, $sql)) {
            while ($row = mysqli_fetch_array($result)) {
                $data[] = $row;
            }
            mysqli_free_result($result);
            return $data;
        } else
            return false;
    }
    /**
     * getRowObject lấy một dòng dữ liệu trong bảng trả về mảng dữ liệu
     * @param string $table tên bảng muốn lấy dữ liệu, array|int $id điều kiện
     * @return object
     */
    public function getRowObject($table = '', $id = [])
    {
        if (is_integer($id))
            $where = 'id = ' . $id;
        else if (is_array($id) && count($id) == 1) {
            $listKey = array_keys($id);
            $where = $listKey[0] . '=' . $id[$listKey[0]];
        } else
            die('Không thể có nhiều hơn 1 khóa chính và id truyền vào phải là số');
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $where;

        if ($result = mysqli_query($this->conn, $sql)) {
            $data = mysqli_fetch_object($result);
            mysqli_free_result($result);
            return $data;
        } else
            return false;
    }
    /**
     * getRowArray lấy một dòng dữ liệu trong bảng trả về mảng dữ liệu
     * @param string $table tên bảng muốn lấy dữ liệu, array|int $id điều kiện
     * @return array
     */
    public function getRowArray($table = '', $id = [])
    {
        if (is_integer($id))
            $where = 'id = ' . $id;
        else if (is_array($id) && count($id) == 1) {
            $listKey = array_keys($id);
            $where = $listKey[0] . '=' . $id[$listKey[0]];
        } else
            die('Không thể có nhiều hơn 1 khóa chính và id truyền vào phải là số');
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $where;

        if ($result = mysqli_query($this->conn, $sql)) {
            $data = mysqli_fetch_array($result);
            mysqli_free_result($result);
            return $data;
        } else
            return false;
    }
    /**
     * query thực hiện query
     * @param string $sql
     * @return boolean|array
     */
    public function query($sql = '', $return = true)
    {


        // $sql = "SELECT * FROM MyGuests WHERE lastname='Doe'";
        $result = mysqli_query($this->conn, $sql);

        if ($result->num_rows > 0) {
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            return $data;
        } else {
            return false;
        }
    }
    /**
     * __destruct hàm hủy
     * @param none
     * @return void
     */
    public function __destruct()
    {
        $this->disConnect();
    }
}





$favcolor = "error";

if (isset($_GET["waction"])) {
    $favcolor = $_GET["waction"];
}




switch ($favcolor) {
    case "cate":
        echo getCate();
        break;
    case "audioOne":
        echo audioOne();
        break;
    case "getArch":
        echo getArch();
        break;
    case "audi":
        $getaudi = "https://thaudiotruyen.com/wp-json/wp/v2/posts?order=asc&per_page=20&page=";
        echo getAudio($getaudi);
        break;
    case "lastUpdate":
        $auca = new Database();
        $sql = "SELECT MAX(date) AS maxvl FROM Audios";
        $maxDate = $auca->query($sql);
        $sql = "SELECT MAX(modified) AS maxvl FROM Audios";
        $maxMod = $auca->query($sql);

        for ($x = 1; $x <= 3; $x++) {
            $urla = "https://thaudiotruyen.com/wp-json/wp/v2/posts?order=asc&per_page=30&page=" . (int)$x . "&orderby=modified&modified_after=" .  $maxMod['maxvl'];
            echo getAudio($urla, true, true);
        }
        for ($x = 1; $x <= 3; $x++) {
            $urla = "https://thaudiotruyen.com/wp-json/wp/v2/posts?order=asc&per_page=30&page=" . (int)$x . "&orderby=date&after=" . $maxDate['maxvl'];

            echo getAudio($urla, true, true);
        }
        break;
    case "error":
        echo "error";
        break;
    default:
        echo "error";
}

function audioOne()
{
    $linkA = $_GET["slugOne"];
    $db = new Database();
    $sql = "SELECT id, metalink,slug  FROM Audios WHERE slug='$linkA'";
    $isIsset = $db->query($sql);
    $content = @file_get_contents($isIsset["metalink"]);
    $result  = json_decode($content);


    if (!isset($result->files)) {

        return "errors";
    }
    $resultt = $result->files;
    $link = array();
    foreach ($resultt as $resource) {
        if ($resource->format == "VBR MP3") {
            $st = strpos($isIsset["metalink"], '/', 25);
            $str = substr($isIsset["metalink"], $st + 1);
            $link[] = "https://archive.org/download/" . $str . "/" . $resource->name;
        }
    }
    $idd = (int)$isIsset["id"];


    $sotap = count($link); //
    $link_audio = implode('<br>', $link); //
    $upa = new Database();
    $dataa = [
        'sotap' => $sotap,
        'link_audio' => $link_audio,
    ];


    // var_dump($link_audio);
    // exit();
    if ($upa->update('Audios', $dataa, $idd)) {
        return "Updated";
    } else {
        var_dump($isIsset["metalink"]);
        return "errors";
    }
}



function getArch()
{
    $linkA = $_GET["linkA"];
    $content = @file_get_contents("https://archive.org/metadata/" . $linkA);
    $result  = json_decode($content);
    if (!isset($result->files)) {
        return "errors";
    } else {
        $linkMeta = "https://archive.org/metadata/" . $linkA;
        $resultt = $result->files;
        $link = array();
        foreach ($resultt as $resource) {

            if ($resource->format == "VBR MP3") {

                $link[] = "https://archive.org/download/" . $linkA . "/" . $resource->name;
            }
        }
        $daaa = date("Y-m-d\TH:i");
        $sotap = count($link); //
        $link_audio = implode('<br>', $link); //
        $data = [
            'date' => $daaa,
            'modified' => $daaa,
            'sotap' => $sotap,
            'link_audio' => $link_audio,
            'trang_thai'  => 0,
            'slug' => $linkA,
            'title' => $linkA,
            'content' => '',
            'metalink'  => $linkMeta,
            'image' => 'nonImage.jpg'

        ];
        $dbv = new Database();
        if ($dbv->insert('Audios', $data)) {
            return "succes";
        } else {
            return "errors";
        }
    }
}

function getCate()
{

    $sotrang = (int)$_GET["page"];
    if ($sotrang <= 0) {
        return "nono";
    }

    $content = file_get_contents("https://thaudiotruyen.com/wp-json/wp/v2/categories?order=desc&per_page=10&orderby=id&page=" . $sotrang);
    $result  = json_decode($content);
    if (!isset($result[0])) {
        return 'end';
    }
    // // var_dump($result[0]);
    // exit();

    foreach ($result as $k => $v) {
        $db = new Database();
        $sql = "SELECT id FROM Categories WHERE id='$v->id'";

        if ($db->query($sql)) {
            continue;
        }

        $data = [
            'id'   => $v->id,
            'name'    => $v->name,
            'slug' => $v->slug
        ];
        if ($db->insert('Categories', $data)) {
        } else {
            return "errors";
        }
    }

    return $sotrang + 1;
}
function loadhtml($str)
{
    $url = "https://thaudiotruyen.com/" . $str;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36 Edg/118.0.2088.46');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}
function image($slug, $featured_media)
{

    if ($slug == "dot-thep-chi-hon") {
        return "nonImage.jpg";
    }

    if ($featured_media == 0) {
        return "nonImage.jpg";
    }


    $content = @file_get_contents("https://thaudiotruyen.com/wp-json/wp/v2/media/" . $featured_media);

    if (!$content) {
        return "nonImage.jpg";
    }

    $result  = json_decode($content);
    $linkim =     "https://thaudiotruyen.com/wp-content/uploads/" . $result->media_details->file;

    $size = getimagesize($linkim);

    if (!isset($size[2])) {
        var_dump($size);
        exit();
    }
    $ext = $size['mime'];

    switch ($ext) {
            // Image is a JPG
        case 'image/jpg':
        case 'image/jpeg':
            // create a jpeg extension
            $image = imagecreatefromjpeg($linkim);
            break;

            // Image is a GIF
        case 'image/gif':
            $image = @imagecreatefromgif($linkim);
            break;

            // Image is a PNG
        case 'image/png':
            $image = @imagecreatefrompng($linkim);
            break;

        case 'image/webp':
            $image = @imagecreatefromwebp($linkim);
            break;

            // Mime type not found
        default:
            var_dump($size);
            throw new Exception("File is not an image, please use another file type.", 1);
    }

    $imgResized = imagescale($image, 193, 278);

    $extension = image_type_to_extension($size[2]);

    $folderImage = "./rez/";
    $namANh = $slug . $extension;
    $ppath = $folderImage . $namANh;
    $imageQuality = "100";
    switch ($ext) {
        case 'image/jpg':
        case 'image/jpeg':
            // Check PHP supports this file type
            if (imagetypes() & IMG_JPG) {
                imagejpeg($imgResized, $ppath, $imageQuality);
                return $namANh;
            } else {
                return false;
            }

            break;

        case 'image/png':
            $invertScaleQuality = 9 - round(($imageQuality / 100) * 9);

            // Check PHP supports this file type
            if (imagetypes() & IMG_PNG) {
                imagepng($imgResized, $ppath, $invertScaleQuality);
                return $namANh;
            } else {
                return false;
            }
            break;
        case 'image/webp':
            if (imagetypes() & IMG_WEBP) {
                imagewebp($imgResized, $ppath, $imageQuality);
                return $namANh;
            } else {
                return false;
            }
            break;
    }
    return false;
}


function getAudio($url, $idUpadate = false, $max = false)
{
    set_time_limit(600);


    if ($max) {
        $content = @file_get_contents($url);
    } else {
        $sotrang = (int)$_GET["page"];
        $content = @file_get_contents($url . $sotrang);
    }
    $ss = "[]";

    if ($content == $ss) {
        return "End <br>";
    }

    if (!$content) {
        return "End";
    }
    $result  = json_decode($content);


    foreach ($result as $k => $v) {
        $db = new Database();
        $sql = "SELECT id, metalink,slug  FROM Audios WHERE slug='$v->slug'";
        $isIsset = $db->query($sql);
        if (in_array(6216, $v->loai_truyen) || $v->id === 86124) {
            continue;
        }


        if ($isIsset) {
            if ($isIsset && $idUpadate) {

                $content = @file_get_contents($isIsset["metalink"]);
                $result  = json_decode($content);

                if (!isset($result->files)) {

                    return "errors";
                }
                $resultt = $result->files;
                $link = array();
                foreach ($resultt as $resource) {
                    if ($resource->format == "VBR MP3") {
                        $st = strpos($isIsset["metalink"], '/', 25);
                        $str = substr($isIsset["metalink"], $st + 1);
                        $link[] = "https://archive.org/download/" . $str . "/" . $resource->name;
                    }
                }
                $idd = (int)$isIsset["id"];


                $sotap = count($link); //
                $link_audio = implode('<br>', $link); //
                $upa = new Database();
                $dataa = [
                    'date' => $v->date,
                    'modified' => $v->modified,
                    'sotap' => $sotap,
                    'link_audio' => $link_audio,
                    'trang_thai'  => $v->trang_thai[0]
                ];


                // var_dump($link_audio);
                // exit();
                if ($upa->update('Audios', $dataa, $idd)) {
                    continue;
                } else {
                    var_dump($isIsset["metalink"]);
                    return "errors";
                }
            } else {
                continue;
            }
        }
        if (!isset($v->trang_thai[0])) {
            $v->trang_thai[0] = 1001;
        }

        $a = ucwords($v->slug, '-');
        $str =  str_replace('-', '', $a) . "TH";

        $content = @file_get_contents("https://archive.org/metadata/" . $str);


        $result  = json_decode($content);


        //get Link audio
        if (!isset($result->files)) {
            include_once "simple_html_dom.php";
            $load = loadhtml($v->slug);
            $html = new simple_html_dom();
            $html->load($load);
            $str = $html->find('div[class=tad-field-content-audio]', 0);
            $aaa = $str->find('b');
            if (!isset($aaa[1])) {
                continue;
            }
            $link = array();
            foreach ($aaa as $vla) {
                $link[] = $vla->id;
            }
            $str = $aaa[0]->id;
            $cce = strpos($str, 'mp3');
            if ($cce) {

                $st = strpos($str, '/', 25);
                $en = strpos($str, '/', 32);
                $str = substr($str, $st + 1, $en - ($st + 1));
            }

            $linkMeta = "https://archive.org/metadata/" . $str;
            $metalink = $linkMeta;
        } else {
            $linkMeta = "https://archive.org/metadata/" . $str;

            $metalink = $linkMeta;
            $resultt = $result->files;

            $link = array();
            foreach ($resultt as $resource) {

                if ($resource->format == "VBR MP3") {

                    $link[] = "https://archive.org/download/" . $str . "/" . $resource->name;
                }
            }
        }

        $simage = image($v->slug, $v->featured_media);
        $sotap = count($link);
        $link_audio = implode('<br>', $link);


        //end getLink th\
        $data = [
            'date' => $v->date,
            'modified' => $v->modified,
            'sotap' => $sotap,
            'link_audio' => $link_audio,
            'trang_thai'  => $v->trang_thai[0],
            'slug' => $v->slug,
            'title' => $v->title->rendered,
            'content' => $v->content->rendered,
            'metalink'  => $metalink,
            'image' => $simage

        ];
        $dbv = new Database();
        if ($dbv->insert('Audios', $data)) {
            $auca = new Database();
            $sql = "SELECT *  FROM Audios WHERE slug='$v->slug'";
            $day = $auca->query($sql);


            foreach ($v->categories as $va) {
                $auca = new Database();
                $sql = "SELECT *  FROM Categories WHERE id='$va'";
                $day = $auca->query($sql);
                if (!$day) {

                    var_dump($sql);
                    exit();
                }
                $auca1 = new Database();

                $sql = "SELECT *  FROM Audios WHERE slug='$v->slug'";
                $day = $auca->query($sql);
                if (is_int($va)) {
                    $cate = [
                        'audio_id' => (int)$day["id"],
                        'categorie_id' => (int)$va
                    ];

                    if ($auca1->insert('Aucas', $cate)) {
                    } else {
                        return "errors";
                    }
                } else {
                    var_dump($va);
                    exit();
                }
            }
        } else {
            return "errors";
        }
    }
    if ($max) {
        return $url;
    } else {
        return $sotrang + 1;
    }
}
