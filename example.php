<?php
/**
 * Pixiv APP API (V2) 示例
 *
 * 2021 Moedog
 *
 */

// 设置响应头
header("Content-type: application/json");

// Pixiv Refresh Token, 参考 https://gist.github.com/ZipFile/c9ebedb224406f4f11845ab700124362 获取
$RefreshToken = "";

// 加载文件
require './vendor/autoload.php';

// 实例化类
$api = new PixivAppAPI;

// 登录，可自行实现定时刷新 Access Token 而不用每次访问都登录一次
$api->login($RefreshToken);

// 登录失败
if (!$api->getAccessToken()) {
    echo json_encode(array("error" => array("message" => "Get Token Failed")));
    exit;
}

// 根据请求获取指定内容
switch(getParam("type"))
{
    case 'illust_bookmark':
        $id = getParam("id");

        $result = $api->illust_bookmark_detail($id);
        break;

    case 'member':
        $user_id = getParam("id");

        $result = $api->user_detail($user_id);
        break;

    case 'member_illust':
        $user_id = getParam("id", 25618720);
        $page = getParam("page", 1);
        $type = getParam("mode", "illust");
        $type = $type == "all" ? null : $type;

        $result = $api->user_illusts($user_id, $page, $type);
        break;

    case 'favorite':
        $user_id = getParam("id", 25618720);
        $page = getParam("page", 1);
        $restrict = getParam("mode", "public");

        $result = $api->user_bookmarks_illust($user_id, $page, $restrict);
        break;

    case 'following':
        $user_id = getParam("id", 25618720);
        $restrict = getParam("mode", "public");
        $page = getParam("page", 1);

        $result = $api->user_following($user_id, $restrict, $page);
        break;

    case 'follower':
        $id = getParam("id", 25618720);
        $page = getParam("page", 1);

        $result = $api->user_follower($id, $page);
        break;

    case 'rank':
        $mode = getParam("mode", "week");
        $page = getParam("page", 1);
        $date = getParam("date");

        $result = $api->illust_ranking($mode, $page, $date);
        break;

    case 'search':
        $word = getParam("word");
        $page = getParam("page", 1);
        $search_target = getParam("mode", "partial_match_for_tags");
        $sort = getParam("order", "popular_desc");
        $start_date = getParam("start_date");
        $end_date = getParam("end_date");
        $bookmark_num_min = getParam("bookmark_num_min");
        $bookmark_num_max = getParam("bookmark_num_max");

        $result = $api->search_illust($word, $page, $search_target, $sort, $start_date, $end_date, $bookmark_num_min, $bookmark_num_max);
        break;

    case 'search_novel':
        $word = getParam("word");
        $page = getParam("page", 1);
        $search_target = getParam("mode", "partial_match_for_tags");
        $sort = getParam("order", "popular_desc");
        $start_date = getParam("start_date");
        $end_date = getParam("end_date");
        $bookmark_num_min = getParam("bookmark_num_min");
        $bookmark_num_max = getParam("bookmark_num_max");

        $result = $api->search_novel($word, $page, $search_target, $sort, $start_date, $end_date, $bookmark_num_min, $bookmark_num_max);
        break;

    case 'search_user':
        $word = getParam("word");
        $page = getParam("page", 1);

        $result = $api->search_user($word, $page);
        break;

    case 'tags':
        $result = $api->trending_tags_illust();
        break;

    case 'related':
        $illust_id = getParam("id");
        $page = getParam("page", 1);
        $seed_illust_ids = getParam("seed");

        $result = $api->illust_related($illust_id, $page, $seed_illust_ids);
        break;

    case 'comments':
        $illust_id = getParam("id");
        $page = getParam("page", 1);
        $include_total_comments = getParam("total", true);

        $result = $api->illust_comments($illust_id, $page, $include_total_comments);
        break;

    case 'mypixiv':
        $user_id = getParam("id", 25618720);
        $page = getParam("page", 1);

        $result = $api->user_mypixiv($user_id, $page);
        break;

    case 'illust_follow':
        $restrict = getParam("mode", "public");
        $page = getParam("page", 1);

        $result = $api->illust_follow($restrict, $page);
        break;

    case 'ugoira_metadata':
        $id = getParam("id");

        $result = $api->ugoira_metadata($id);
        break;

    default:
        $illust_id = getParam("id");

        $result = $api->illust_detail($illust_id);
}

// 输出结果
echo json_encode($result);

// 获取 GET / POST 内容
function getParam($key, $default="") {
    return trim($key && is_string($key) ? (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default)) : $default);
}