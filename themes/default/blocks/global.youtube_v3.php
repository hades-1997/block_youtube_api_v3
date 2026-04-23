<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_youtube')) {
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

        if (!empty($block_config['youtube_api']) && !empty($block_config['youtube_channel'])) {
            // Chuyển Channel ID (UC...) thành Uploads Playlist ID (UU...)
            // để lấy TẤT CẢ video bao gồm cả Short
            $channel_id = $block_config['youtube_channel'];
            $uploads_playlist_id = 'UU' . substr($channel_id, 2);

            $url_api = 'https://www.googleapis.com/youtube/v3/playlistItems?key=' . $block_config['youtube_api'] . '&playlistId=' . $uploads_playlist_id . '&part=snippet&maxResults=' . $block_config['number'];

            $json = @file_get_contents($url_api);
            $data = json_decode($json, true);
            $array_data = [];
            $video_ids = [];
            $temp_data = [];

            if (!empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    if (isset($item['snippet']['resourceId']['videoId'])) {
                        $vid = $item['snippet']['resourceId']['videoId'];
                        $video_ids[] = $vid;
                        $temp_data[$vid] = [
                            'channel' => $item['snippet']['channelTitle'],
                            'videoid' => $vid,
                            'title' => $item['snippet']['title'],
                            'thumb' => isset($item['snippet']['thumbnails']['high']['url']) ? $item['snippet']['thumbnails']['high']['url'] : (isset($item['snippet']['thumbnails']['default']['url']) ? $item['snippet']['thumbnails']['default']['url'] : ''),
                            'is_short' => '0'
                        ];
                    }
                }
            }

            // Gọi API videos để kiểm tra embeddable status và duration
            // Video không embed được (lỗi 153, thường là Short) sẽ hiển thị thumbnail thay vì iframe
            if (!empty($video_ids)) {
                $ids_string = implode(',', $video_ids);
                $url_details = 'https://www.googleapis.com/youtube/v3/videos?key=' . $block_config['youtube_api'] . '&id=' . $ids_string . '&part=contentDetails,status';

                $json_details = @file_get_contents($url_details);
                $data_details = json_decode($json_details, true);

                if (!empty($data_details['items'])) {
                    foreach ($data_details['items'] as $detail) {
                        $vid = $detail['id'];
                        if (isset($temp_data[$vid])) {
                            $is_not_embeddable = false;
                            $is_short_duration = false;

                            // Kiểm tra nếu video không cho phép embed
                            if (isset($detail['status']['embeddable']) && $detail['status']['embeddable'] === false) {
                                $is_not_embeddable = true;
                            }

                            // Kiểm tra thời lượng (Short có thể tới 3 phút = 180s)
                            if (isset($detail['contentDetails']['duration'])) {
                                try {
                                    $interval = new DateInterval($detail['contentDetails']['duration']);
                                    $seconds = ($interval->d * 24 * 60 * 60) + ($interval->h * 60 * 60) + ($interval->i * 60) + $interval->s;
                                    if ($seconds <= 180) {
                                        $is_short_duration = true;
                                    }
                                } catch (Exception $e) {
                                    // Bỏ qua lỗi parse
                                }
                            }

                            // Đánh dấu là Short nếu: không embed được HOẶC thời lượng ngắn
                            if ($is_not_embeddable || $is_short_duration) {
                                $temp_data[$vid]['is_short'] = '1';
                            }
                        }
                    }
                }

                // Giữ nguyên thứ tự
                foreach ($video_ids as $vid) {
                    if (isset($temp_data[$vid])) {
                        // Nếu chưa phát hiện là Short qua API, kiểm tra bằng URL youtube.com/shorts/
                        if ($temp_data[$vid]['is_short'] === '0' && function_exists('curl_init')) {
                            $ch = curl_init('https://www.youtube.com/shorts/' . $vid);
                            curl_setopt($ch, CURLOPT_NOBODY, true);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_exec($ch);
                            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);
                            // HTTP 200 = URL hợp lệ = đây là Short
                            if ($httpCode == 200) {
                                $temp_data[$vid]['is_short'] = '1';
                            }
                        }
                        $array_data[] = $temp_data[$vid];
                    }
                }
            }
        }

        $xtpl = new XTemplate('global.youtube_v3.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('CHANNEL_ID', $block_config['youtube_channel']);
        $xtpl->assign('NAME_CHANNEL', $block_config['youtube_name']);
        if (!empty($array_data)) {
            $stt = 1;
            foreach ($array_data as $row) {
                if ($stt == 1) {
                    $xtpl->parse('main.loop.one');
                }

                $row['title'] = nv_clean60($row['title'], 60);
                $stt++;
                $xtpl->assign('ROW', $row);
                if ($row['is_short'] == '1') {
                    $xtpl->parse('main.loop.short_badge');
                }
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
