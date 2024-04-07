<?php
if (!function_exists('db')) {
    function db() {
        $db = \Config\Database::connect();
        return $db;
    }
}
if (!function_exists('adminview')) {
    function adminview($pagename, $data) {
        $company = db()->table('dt_websetting')->select('logo_jpg,logo_webp,logo_alt,company_name,favicon')->get()->getRowArray();
        $data["company"] = $company;
        $data['favicon'] = !empty($company['favicon']) ? base_url('uploads/') . $company['favicon'] : "";
        $data['logo'] = !empty($company['logo_jpg'] || $company['logo_webp']) ? base_url('uploads/') . imgExtension($company['logo_jpg'], $company['logo_webp']) : "";
        echo view(ADMINPATH . "includes/meta_file", $data);
        echo view(ADMINPATH . "includes/all_css", $data);
        echo view(ADMINPATH . "includes/header", $data);
        echo view(ADMINPATH . "includes/sidebar", $data);
        echo view(ADMINPATH . $pagename, $data);
        echo view(ADMINPATH . "includes/all_js", $data);
        echo view(ADMINPATH . "includes/footer", $data);
    }
}
if (!function_exists('hospitalview')) {
    function hospitalview($pagename, $data) {
        $company = db()->table('dt_websetting')->select('logo_jpg,logo_webp,logo_alt,company_name,favicon')->get()->getRowArray();
        $data["company"] = $company;
        $data['favicon'] = !empty($company['favicon']) ? base_url('uploads/') . $company['favicon'] : "";
        $data['logo'] = !empty($company['logo_jpg'] || $company['logo_webp']) ? base_url('uploads/') . imgExtension($company['logo_jpg'], $company['logo_webp']) : "";
        echo view(HOSPITALPATH . "includes/meta_file", $data);
        echo view(HOSPITALPATH . "includes/all_css", $data);
        echo view(HOSPITALPATH . "includes/header", $data);
        echo view(HOSPITALPATH . "includes/sidebar", $data);
        echo view(HOSPITALPATH . $pagename, $data);
        echo view(HOSPITALPATH . "includes/all_js", $data);
        echo view(HOSPITALPATH . "includes/footer", $data);
    }
}
if (!function_exists("frontview")) {
    function frontview($page_name, $data) {
        $company = websetting();
        $data['company'] = $company;
        $data['favicon'] = !empty($company['favicon']) ? base_url('uploads/') . $company['favicon'] : "";
        $data['logo'] = !empty($company['logo_jpg'] || $company['logo_webp']) ? base_url('uploads/') . imgExtension($company['logo_jpg'], $company['logo_webp']) : "";
        echo view("frontend/includes/meta_file", $data);
        echo view("frontend/includes/all_css", $data);
        echo view("frontend/includes/header", $data);
        echo view("frontend/" . $page_name, $data);
        echo view("frontend/includes/footer", $data);
        echo view("frontend/includes/all_js", $data);
    }
}
if (!function_exists("websetting")) {
    function websetting() {
        $company = db()->table('dt_websetting')->select('*')->get()->getRowArray();
        return $company;
    }
}
if (!function_exists("validate_slug")) {
    function validate_slug($text, string $divider = '-') {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }
}
if (!function_exists('convertImageInToWebp')) {
    function convertImageInToWebp($folderPath, $uploaded_file_name, $new_webp_file) {
        $source = $folderPath . '/' . $uploaded_file_name;
        $extension = pathinfo($source, PATHINFO_EXTENSION);
        $quality = 100;
        $image = '';
        if ($extension == 'jpeg' || $extension == 'jpg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($extension == 'gif') {
            $image = imagecreatefromgif($source);
        } elseif ($extension == 'png') {
            $image = imagecreatefrompng($source);
            imagepalettetotruecolor($image);
        } else {
            $image = $uploaded_file_name;
        }
        $destination = $folderPath . '/' . $new_webp_file;
        $webp_upload_done = imagewebp($image, $destination, $quality);
        return $webp_upload_done ? $new_webp_file : '';
    }
}
if (!function_exists('count_data')) {
    function count_data($column, $table, $where = null) {
        $builder = db()->table($table);
        if (!empty($where)) {
            $builder->where($where);
        }
        $count = $builder->countAllResults($column);
        return $count;
    }
}
function random_alphanumeric_string($length) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($chars), 0, $length);
}
function generate_password($length) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$';
    return substr(str_shuffle($chars), 0, $length);
}
function FetchExactBrowserName() {
    $userAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
    if (strpos($userAgent, "opr/") !== false) {
        return "Opera";
    } elseif (strpos($userAgent, "chrome/") !== false) {
        return "Chrome";
    } elseif (strpos($userAgent, "msie") !== false || strpos($userAgent, "trident/") !== false) {
        return "Internet Explorer";
    } elseif (strpos($userAgent, "firefox/") !== false) {
        return "Firefox";
    } elseif (strpos($userAgent, "safari/") !== false) {
        return "Safari";
    } else {
        return "OUT OF DATA";
    }
}
function imgExtension($image_jpg_png_gif, $image_webp = null) {
    $browserName = FetchExactBrowserName();
    if (in_array($browserName, ["chrome"]) && !empty($image_webp)) {
        /*for webp image*/
        return $image_webp; /*you can add image folder path like base_url('<folder name>/'.$image_webp)*/
    } else {
        return $image_jpg_png_gif; /*you can add image folder path like base_url('<folder name>/'.$image_jpg_png_gif)*/
    }
}
if (!function_exists('getTimeInterval')) {
    function getTimeInterval($time) {
        $current_time = new DateTime();
        $from = new DateTime($time);
        $difference = $current_time->diff($from);
        // Calculate the total hours (including days)
        $totalHours = $difference->days * 24 + $difference->h;
        return $totalHours;
    }
}
function getData($table, $keys = null, $where = null, $limit = null, $offset = null, $order_by = null) {
    $builder = db()->table($table);
    if (!empty($keys)) {
        $builder->select($keys);
    }
    if (!empty($where)) {
        $builder->where($where);
    }
    if (!empty($limit) && !empty($offset)) {
        $builder->limit($limit, $offset);
    } elseif (!empty($limit) && empty($offset)) {
        $builder->limit($limit);
    }
    if (!empty($orderby)) {
        $builder->orderBy($orderby);
    }
    return $builder->get()->getResultArray();
}
function getSingle($table, $keys = null, $where = null, $limit = null, $offset = null, $order_by = null) {
    $builder = db()->table($table);
    if (!empty($keys)) {
        $builder->select($keys);
    }
    if (!empty($where)) {
        $builder->where($where);
    }
    if (!empty($limit) && !empty($offset)) {
        $builder->limit($limit, $offset);
    } elseif (!empty($limit) && empty($offset)) {
        $builder->limit($limit);
    }
    if (!empty($orderby)) {
        $builder->orderBy($orderby);
    }
    return $builder->get()->getRowArray();
}
if (!function_exists('getSubMenuList')) {
    function getSubMenuList($menuList, $parent_menu_id) {
        return array_filter($menuList, function ($item) use ($parent_menu_id) {
            return $item['menu_id'] === $parent_menu_id;
        });
    }
}
if (!function_exists('getName')) {
    function getName($id, $table) {
        $name = '';
        if ($table == "employee") {
            $result = getSingle('employee_list', 'full_name', ['id' => $id]);
            $name = $result['full_name'];
        } else if ($table == "volunteer") {
            $result = getSingle('volunteer_list', 'full_name', ['id' => $id]);
            $name = $result['full_name'];
        }
        return $name;
    }
}
if (!function_exists('getCategoryName')) {
    function getCategoryName($id) {
        $result=getSingle('category_list','category_name',['id'=>$id,'status'=>'Active']);
        return $result['category_name'];
    }
}