<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (! nv_function_exists('nv_youtube')) {
    function nv_youtube_config($module, $data_block, $lang_block)
    {
        global $lang_global, $selectthemes;

        $html = '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6"> Tên channel hiển thị:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_youtube_name" value="' . $data_block['youtube_name'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6"> Youtube API:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_youtube_api" value="' . $data_block['youtube_api'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6"> Youtube Channel ID:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_youtube_channel" value="' . $data_block['youtube_channel'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6"> Số lượng hiển thị:</label>';
        $html .= '<div class="col-sm-18"><input type="number" class="form-control" name="config_number" value="' . $data_block['number'] . '"></div>';
        $html .= '</div>';
        return $html;
    }

    function nv_youtube_info_submit()
    {
        global $nv_Request;

        $return = [];
        $return['error'] = [];
        $return['config']['youtube_name'] = $nv_Request->get_title('config_youtube_name', 'post');
        $return['config']['youtube_api'] = $nv_Request->get_title('config_youtube_api', 'post');
        $return['config']['youtube_channel'] = $nv_Request->get_title('config_youtube_channel', 'post');
        $return['config']['number'] = $nv_Request->get_title('config_number', 'post');

        return $return;
    }

    /**
     * nv_menu_theme_default_footer()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_youtube($block_config)
    {
        global $global_config, $lang_global;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.youtube_v3.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.youtube_v3.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        if(!empty($block_config['youtube_api']) && !empty($block_config['youtube_channel'])) {
            //key : AIzaSyBYREBgY2-qKQJuUSMvIdPE04dM2YWxQ8I
            // channel id : UCO_Pd0a1E88PWB_C9nobmRQ
            // channelTitle
            $url_api = 'https://www.googleapis.com/youtube/v3/search?key='.$block_config['youtube_api'].'&channelId='.$block_config['youtube_channel'].'&part=snippet,id&order=date&maxResults='.$block_config['number'] ;
            // Lấy nội dung JSON từ API
            $json = file_get_contents($url_api);
            // Chuyển JSON sang mảng PHP
            $data = json_decode($json, true);
           $array_data = [];
            if (!empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    if (isset($item['id']['videoId'])) {
                        $array_data[] = [
                            'channel' => $item['snippet']['channelTitle'],
                            'videoid' => $item['id']['videoId'],
                            'title' => $item['snippet']['title'], // Sửa lỗi: 'title' không nằm trong 'id'
                            'thumb' => $item['snippet']['thumbnails']['high']['url']
                        ];
                    }
                }
            }
        }

        $xtpl = new XTemplate('global.youtube_v3.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('CHANNEL_ID', $block_config['youtube_channel']);
        $xtpl->assign('NAME_CHANNEL', $block_config['youtube_name']);
        if(!empty($array_data)) {
            $stt = 1;
            foreach ($array_data as $row) {
                if($stt == 1) {
                    $xtpl->parse('main.loop.one');
                }

                $row['title'] = nv_clean60($row['title'], 60);
                $stt++;
                $xtpl->assign('ROW', $row);
                $xtpl->parse('main.loop');
            }
        }
      
        $xtpl->assign('SITE_LOGO', NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo']);
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_youtube($block_config);
}
