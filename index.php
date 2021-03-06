<?php

    /**
     * Входная точка в движок
     *
     * @author Zmi
     */


    define('BASEPATH', str_replace('\\', '/', dirname(__FILE__)) . '/');
    
    session_start();
    
    require_once BASEPATH . 'core/core.php';

    ob_start();
        $g_config['isControllerLoad'] = IncludeCom(GetQuery());
    $content = ob_get_clean();
    
    if ( ! $g_config['isControllerLoad'])
    {
        $link = GetQuery();
        $_p = new Pages();
        if ($_p->CheckLink($link))
        {
            $g_config['isControllerLoad'] = true;
            ob_start();
                IncludeCom('pages/one', array('link' => $link));
            $content = ob_get_clean();
        }
    }

    // Если страницы небыло то 404-ая
    if ( ! $g_config['isControllerLoad'])
    {
        ob_start();
            IncludeCom('404');
        $content = ob_get_clean();
    }

    // Если страницу нужно загрузить в главном шаблоне
    if ($g_config['isLoadInMainTpl'])
    {
        SetTitleDescKwForComsArchive($content);
        ob_start();
            IncludeCom($g_config['mainTpl'], array('content' => $content));
        $content = ob_get_clean();
    }

    header($g_config['isControllerLoad'] ? Php::Status(200) : Php::Status(404));
    echo PrepareContent($content);
?>