<?php
/**
 * pixiv-api-php
 * PixivApp API for PHP
 *
 * @package  pixiv-api-php
 * @author   Kokororin
 * @license  MIT License
 * @version  2.1
 * @link     https://github.com/kokororin/pixiv-api-php
 */

class PixivAppAPI extends PixivBase
{
    /**
     * @var string
     */
    protected $api_prefix = 'https://app-api.pixiv.net';

    /**
     * @var string
     */
    protected $api_filter = 'for_android';

    /**
     * @var array
     */
    protected $noneAuthHeaders = array(
        'User-Agent' => 'PixivAndroidApp/5.0.200 (Android 10; MI 8 UD)',
        'App-OS' => 'android',
        'App-OS-Version' => '10',
        'App-Version' => '5.0.200',
    );

    /**
     * 作品详情
     *
     * @param  string $illust_id
     * @return array
     */
    public function illust_detail($illust_id)
    {
        return $this->fetch('/v1/illust/detail', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'illust_id' => $illust_id,
            ),
        ));
    }

    /**
     * 作品收藏详情
     *
     * @param  string $illust_id
     * @return array
     */
    public function illust_bookmark_detail($illust_id)
    {
        return $this->fetch('/v2/illust/bookmark/detail', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'illust_id' => $illust_id,
            ),
        ));
    }

    /**
     * 用户详情
     *
     * @param  string $user_id
     * @return array
     */
    public function user_detail($user_id)
    {
        return $this->fetch('/v1/user/detail', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * 用户作品
     *
     * @param  string  $user_id
     * @param  integer $page
     * @param  string  $type
     * @return array
     */
    public function user_illusts($user_id, $page = 1, $type = 'illust')
    {
        return $this->fetch('/v1/user/illusts', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'type' => $type,
                'offset' => ($page - 1) * 30,
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * 用户收藏
     *
     * @param  string $user_id
     * @param  string $restrict
     * @return array
     */
    public function user_bookmarks_illust($user_id, $page = 1, $restrict = 'public')
    {
        return $this->fetch('/v1/user/bookmarks/illust', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'offset' => ($page - 1) * 30,
                'restrict' => $restrict,
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * 用户关注
     * @param  string  $user_id
     * @param  string  $restrict
     * @param  integer $page
     * @return array
     */
    public function user_following($user_id, $restrict = 'public', $page = 1)
    {
        return $this->fetch('/v1/user/following', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'restrict' => $restrict,
                'offset' => ($page - 1) * 30,
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * 用户粉丝
     * @param  string  $user_id
     * @param  integer $page
     * @return array
     */
    public function user_follower($user_id, $page = 1)
    {
        return $this->fetch('/v1/user/follower', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'offset' => ($page - 1) * 30,
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * 排行榜
     *
     * @param  string  $mode
     *                         day
     *                         week
     *                         month
     *                         day_male
     *                         day_female
     *                         week_original
     *                         week_rookie
     * @param  integer $page
     * @param  string  $date  YYYY-MM-DD
     * @return array
     */
    public function illust_ranking($mode = 'day', $page = 1, $date = null)
    {
        $body = array(
            'mode' => $mode,
            'offset' => ($page - 1) * 30,
            'filter' => $this->api_filter,
        );
        if ($date != null) {
            $body['date'] = $date;
        }
        return $this->fetch('/v1/illust/ranking', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => $body,
        ));
    }

    /**
     * 搜索
     *
     * @param  string  $word                          搜索关键词
     * @param  integer $page                          分页
     * @param  string  $search_target                 搜索类型
     *                     partial_match_for_tags         标签部分匹配
     *                     exact_match_for_tags           标签完全匹配
     *                     title_and_caption              标题·简介
     * @param  string  $sort                          排序
     *                     date_desc                      最新作品
     *                     date_asc                       由旧到新
     *                     popular_desc                   热门度顺序
     *                     popular_male_desc              受男性欢迎
     *                     popular_female_desc            受女性欢迎
     * @param  string  $start_date                    起始时间 YYYY-MM-DD
     * @param  string  $end_date                      终止时间 YYYY-MM-DD
     * @param  integer $bookmark_num_min              最小收藏数
     * @param  integer $bookmark_num_max              最大收藏数
     * @return array
     */
    public function search_illust($word, $page = 1, $search_target = 'partial_match_for_tags', $sort = 'date_desc', $start_date = null, $end_date = null, $bookmark_num_min = null, $bookmark_num_max = null)
    {
        $body = array(
            'include_translated_tag_results' => true,
            'merge_plain_keyword_results' => true,
            'word' => $word,
            'search_target' => $search_target,
            'sort' => $sort,
            'offset' => ($page - 1) * 30,
            'filter' => $this->api_filter,
        );
        if ($start_date || $end_date) {
            $body['start_date'] = $start_date ? $start_date : date("Y-m-d",strtotime("-1 year"));
            $body['end_date'] = $end_date ? $end_date : date("Y-m-d",strtotime("-1 day"));
        }
        if ($bookmark_num_min) {
            $body['bookmark_num_min'] = $bookmark_num_min;
        }
        if ($bookmark_num_max) {
            $body['bookmark_num_max'] = $bookmark_num_min;
        }
        return $this->fetch('/v1/search/illust', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => $body,
        ));
    }

    /**
     * 搜索小说
     *
     * @param  string  $word                          搜索关键词
     * @param  integer $page                          分页
     * @param  string  $search_target                 搜索类型
     *                     partial_match_for_tags         标签部分匹配
     *                     exact_match_for_tags           标签完全匹配
     *                     text                           正文
     *                     keyword                        关键词
     * @param  string  $sort                          排序
     *                     date_desc                      最新作品
     *                     date_asc                       由旧到新
     *                     popular_desc                   热门度顺序
     *                     popular_male_desc              受男性欢迎
     *                     popular_female_desc            受女性欢迎
     * @param  string  $start_date                    起始时间 YYYY-MM-DD
     * @param  string  $end_date                      终止时间 YYYY-MM-DD
     * @param  integer $bookmark_num_min              最小收藏数
     * @param  integer $bookmark_num_max              最大收藏数
     * @return array
     */
    public function search_novel($word, $page = 1, $search_target = 'partial_match_for_tags', $sort = 'date_desc', $start_date = null, $end_date = null, $bookmark_num_min = null, $bookmark_num_max = null)
    {
        $body = array(
            'include_translated_tag_results' => true,
            'merge_plain_keyword_results' => true,
            'word' => $word,
            'search_target' => $search_target,
            'sort' => $sort,
            'offset' => ($page - 1) * 30,
            'filter' => $this->api_filter,
        );
        if ($start_date || $end_date) {
            $body['start_date'] = $start_date ? $start_date : date("Y-m-d",strtotime("-1 year"));
            $body['end_date'] = $end_date ? $end_date : date("Y-m-d",strtotime("-1 day"));
        }
        if ($bookmark_num_min) {
            $body['bookmark_num_min'] = $bookmark_num_min;
        }
        if ($bookmark_num_max) {
            $body['bookmark_num_max'] = $bookmark_num_min;
        }
        return $this->fetch('/v1/search/novel', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => $body,
        ));
    }

    /**
     * 搜索用户
     * @param  string   $word
     * @param  integer  $page
     * @return array
     */
    public function search_user($word, $page = 1)
    {
        return $this->fetch('/v1/search/user', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'word' => $word,
                'offset' => ($page - 1) * 30,
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * 热门标签
     *
     * @return array
     */
    public function trending_tags_illust()
    {
        return $this->fetch('/v1/trending-tags/illust', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * 相关作品
     *
     * @param  string  $illust_id
     * @param  integer $page
     * @param  array   $seed_illust_ids
     * @return array
     */
    public function illust_related($illust_id, $page = 1, $seed_illust_ids = null)
    {
        $body = array(
            'illust_id' => $illust_id,
            'offset' => ($page - 1) * 30,
            'filter' => $this->api_filter,
        );
        if (is_array($seed_illust_ids)) {
            $body['seed_illust_ids[]'] = $seed_illust_ids;
        }
        return $this->fetch('/v2/illust/related', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => $body,
        ));
    }

    /**
     * 作品评论
     *
     * @param  string $illust_id
     * @param  integer $page
     * @param  boolean $include_total_comments
     * @return array
     */
    public function illust_comments($illust_id, $page = 1, $include_total_comments = true)
    {
        return $this->fetch('/v1/illust/comments', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'illust_id' => $illust_id,
                'offset' => ($page - 1) * 30,
                'include_total_comments' => $include_total_comments,
            ),
        ));
    }

    /**
     * 用户好P友
     * @param  string   $user_id
     * @param  integer  $page
     * @return array
     */
    public function user_mypixiv($user_id, $page = 1)
    {
        return $this->fetch('/v1/user/mypixiv', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'offset' => ($page - 1) * 30,
            ),
        ));
    }

    /**
     * 获取 ugoira 信息
     *
     * @param  string  $illust_id
     * @return array
     */
    public function ugoira_metadata($illust_id)
    {
        return $this->fetch('/v1/ugoira/metadata', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'illust_id' => $illust_id,
            ),
        ));
    }

    /**
     * 主页作品推荐
     * @param  integer  $page
     * @return array
     */
    public function illust_recommended($page = 1)
    {
        return $this->fetch('/v1/illust/recommended', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'include_ranking_illusts' => true,
                'include_privacy_policy' => true,
                'offset' => ($page - 1) * 30,
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * 新增收藏[登录者]
     *
     * @param  string  $illust_id
     * @param  string  $restrict
     * @param  array   $tags
     * @return array
     */
    public function illust_bookmark_add($illust_id, $restrict = 'public', $tags = null)
    {
        $body = array(
            'illust_id' => $illust_id,
            'restrict' => $restrict,
        );
        if (is_array($tags)) {
            $body['tags[]'] = $tags;
        }
        return $this->fetch('/v2/illust/bookmark/add', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => $body,
        ));
    }

    /**
     * 删除收藏[登录者]
     *
     * @param  string  $illust_id
     * @return array
     */
    public function illust_bookmark_delete($illust_id)
    {
        return $this->fetch('/v1/illust/bookmark/delete', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'illust_id' => $illust_id,
            ),
        ));
    }

    /**
     * 关注用户的新作[登录者]
     * @param  string   $restrict
     * @param  integer  $page
     * @return array
     */
    public function illust_follow($restrict = 'public', $page = 1)
    {
        return $this->fetch('/v2/illust/follow', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'restrict' => $restrict,
                'offset' => ($page - 1) * 30,
            ),
        ));
    }

    /**
     * 用户收藏标签列表[登录者]
     * @param  string   $restrict
     * @param  integer  $page
     * @return array
     */
    public function user_bookmark_tags_illust($restrict = 'public', $page = 1)
    {
        return $this->fetch('/v1/user/bookmark-tags/illust', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'restrict' => $restrict,
                'offset' => ($page - 1) * 30,
            ),
        ));
    }

    /**
     * 用户黑名单[登录者]
     * @param  string   $user_id
     * @param  integer  $page
     * @return array
     */
    public function user_list($user_id, $page = 1)
    {
        return $this->fetch('/v2/user/list', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'offset' => ($page - 1) * 30,
                'filter' => $this->api_filter,
            ),
        ));
    }
}