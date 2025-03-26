<?php

declare(strict_types=1);

namespace Ecxod\Header;

use \Twig\Loader\FilesystemLoader;
use \Twig\Loader\ArrayLoader;
use \Twig\Environment;

use function Ecxod\Funktionen\{m, isMe, isMobile};

class H extends GLOBALE
{

    protected $cdn_down;
    protected $loader;
    protected $twig;

    function __construct()
    {
        $this->cdn_down = true;

        $this->loader = new FilesystemLoader(paths: $_ENV['TWIG'] . "/header/");

        // Namespacing : wir erstellen ein twig namespace 'index'
        $this->loader->addPath(path: $_ENV['TWIG'] . "/header/", namespace: 'header');

        // twig Objekt erstellen
        $this->twig = new Environment(loader: $this->loader);

        parent::__construct();
    }

    public function head()
    {
        return $this->twig->render(name: '@header/_head.twig', context: [ 
            'lang'                     => explode('_', string: $_ENV['LA'])[0],
            'encTYPE'                  => $this->encTYPE(),
            'viewport'                 => $this->viewport(1.0),
            'cash'                     => $this->cash('no-cache'),
            // 'description' => $this->description(),
            // 'icons' => $this->icons(),
            // 'apple' => $this->apple(),
            // 'twitter' => $this->twitter(),
            'loadBootstrapCssAndIcons' => $this->loadBootstrapCssAndIcons(),
            'loadStyles'               => $this->loadStyles(),
            // 'autor' => $this->autor(),
            // 'datum' => $this->datum(),
            // 'canonical' => $this->canonical(),
            'loadScripts'              => $this->loadScripts(),

        ]);
    }


    /** 
     * @return string
     */
    function encTYPE(): string
    {
        return $this->twig->render(name: '@header/encType.twig', context: [ 
            'lang'    => explode('_', string: $_ENV['LA'])[0],
            'charset' => $_ENV['CHARSET'],
        ]);
    }

    /**
     * @param float $vp 
     * @return string 
     */
    function viewport($vp = 1.0)
    {
        return $this->twig->render(name: '@header/viewport.twig', context: [ 
            'initial-scale' => number_format($vp, 1),
            'maximum-scale' => number_format($vp, 1),
        ]);
    }


    /** Cash Metatag ( no-cash | 3600s )
     * @param string|int $cash 
     * @return string 
     */
    function cash(string|int $cash)
    {
        $txt = m(__METHOD__);

        // todo . nachdenken ob cache sinn macht in diesem fall
        if($cash === "no-cache")
        {
            $txt .=
                '<meta http-equiv="expires" content="0">' . PHP_EOL .
                '<meta http-equiv="CACHE-CONTROL" content="NO-CACHE">' . PHP_EOL;
        }
        else
            if(!empty(intval($cash)))
            {
                if(intval($cash) < 3600)
                {
                    $cash = 3600;
                }
                $txt .= '<meta http-equiv="Cache-Control" content="max-age=' . strval($cash) . ', must-revalidate">' . PHP_EOL;
            }

        $txt .=
            '<meta name="generator" content="MediaWiki 1.34.0-wmf.23">' . PHP_EOL .
            '<meta name="pandemic" content="china_virus_v1.9">' . PHP_EOL .
            '<link rel="license" href="https://creativecommons.org/licenses/by-sa/3.0/">' . PHP_EOL;

        return $txt;
    }





    /** Loading Scripts from CDN ( jquery, bootstrap js)
     * @return string
     */
    function loadScripts()
    {
        $txt = m(__METHOD__);

        // if($this->ping($_ENV['CDN_JQ']) !== "down")
        // {
        //     // defer = laden wenn alles rum ist
        //     $txt .= '<script type="text/javascript" src="' . $_ENV['CDN_JQ'] . '"></script>' . PHP_EOL;
        //     //$txt .= '<script type="text/javascript" src="' . $_ENV['CDN_PO'] . '"></script>' . PHP_EOL;
        //     // popper muss vor bootstrapjs, oder man muss bootsrap.bundle nutzen
        //     $txt .= '<script type="text/javascript" src="' . $_ENV['CDN_JS_BUNDLE'] . '"></script>' . PHP_EOL;

        //     //$txt .= '<script type="text/javascript" src="' . $_ENV['PRISM_JS'] . '"></script>' . PHP_EOL;
        // }
        // else
        // {
        //     $txt .= '<script type="text/javascript" src="/static/jquery/jquery.min.js"></script>' . PHP_EOL;
        //     // popper muss vor bootstrapjs, oder man muss bootsrap.bundle nutzen
        //     //$txt .= '<script type="text/javascript" src="/static/@popperjs/core/dist/umd/popper.min.js"></script>' . PHP_EOL;
        //     $txt .= '<script type="text/javascript" src="/static/bs/dist/js/bootstrap.bundle.min.js"></script>' . PHP_EOL;
        //     //$txt .= '<script type="text/javascript" src="/static/prismjs/prism.js"></script>' . PHP_EOL;
        // }



        $txt .= '<script type="text/javascript" src="/static/jquery/dist/jquery.min.js"></script>' . PHP_EOL;
        // popper muss vor bootstrapjs, oder man muss bootsrap.bundle nutzen
        //$txt .= '<script type="text/javascript" src="/static/@popperjs/core/dist/umd/popper.min.js"></script>' . PHP_EOL;
        $txt .= '<script type="text/javascript" src="/static/bootstrap/dist/js/bootstrap.bundle.min.js"></script>' . PHP_EOL;
        //$txt .= '<script type="text/javascript" src="/static/prismjs/prism.js"></script>' . PHP_EOL;




        return $txt;
    }



    /** Ladet die Bootstrap css und die icons
     * @return string
     */
    function loadBootstrapCssAndIcons()
    {
        $txt = m(__METHOD__);
        //CDN_BI
        // if ($this->ping($_ENV['CDN_BI']) !== "down") {
        //     $txt .= '<link rel="stylesheet" type="text/css" href="' . $_ENV['CDN_BI'] . '" as="style" Cache-Control="max-age=3600">' . PHP_EOL;
        //     $txt .= '<link rel="preload" type="text/css" href="' . $_ENV['CDN_BI'] . '" as="style">' . PHP_EOL;
        //     $txt .= '<link rel="prefetch" href="' . $_ENV['CDN_BI'] . '">' . PHP_EOL;
        // } else {
        //     // CDN_CSS VON LOKAL /usr/lib/composer/vendor/twbs/bootstrap/dist => bs/dist
        //     $txt .= '<link rel="stylesheet" type="text/css" href="/static/bs/font/bootstrap-icons.min.css" as="style" Cache-Control="max-age=3600">' . PHP_EOL;
        //     $txt .= '<link rel="preload" type="text/css" href="/static/bs/font/bootstrap-icons.min.css" as="style">' . PHP_EOL;
        //     $txt .= '<link rel="prefetch" href="/static/bs/font/bootstrap-icons.min.css">' . PHP_EOL;
        // }
        // //CDN_CSS VON OBSI
        // if ($this->ping($_ENV['CDN_CSS']) !== "down") {
        //     $txt .= '<link rel="stylesheet" type="text/css" href="' . $_ENV['CDN_CSS'] . '" as="style" Cache-Control="max-age=3600">' . PHP_EOL;
        //     $txt .= '<link rel="preload" type="text/css" href="' . $_ENV['CDN_CSS'] . '" as="style">' . PHP_EOL;
        //     $txt .= '<link rel="prefetch" href="' . $_ENV['CDN_CSS'] . '">' . PHP_EOL;
        // } else {
        //     // CDN_CSS VON LOKAL /usr/lib/composer/vendor/twbs/bootstrap/dist => bs/dist
        //     $txt .= '<link rel="stylesheet" type="text/css" href="/static/bs/dist/css/bootstrap.min.css" as="style" Cache-Control="max-age=3600">' . PHP_EOL;
        //     $txt .= '<link rel="preload" type="text/css" href="/static/bs/dist/css/bootstrap.min.css" as="style">' . PHP_EOL;
        //     $txt .= '<link rel="prefetch" href="/static/bs/dist/css/bootstrap.min.css">' . PHP_EOL;
        // }


        // CDN_CSS VON LOKAL /usr/lib/composer/vendor/twbs/bootstrap/dist => bs/dist
        $txt .= '<link rel="stylesheet" type="text/css" href="/static/bootstrap-icons/font/bootstrap-icons.min.css" as="style" Cache-Control="max-age=3600">' . PHP_EOL;
        $txt .= '<link rel="preload" type="text/css" href="/static/bootstrap-icons/font/bootstrap-icons.min.css" as="style">' . PHP_EOL;
        $txt .= '<link rel="prefetch" href="/static/bootstrap-icons/font/bootstrap-icons.min.css">' . PHP_EOL;

        // CDN_CSS VON LOKAL /usr/lib/composer/vendor/twbs/bootstrap/dist => bs/dist
        $txt .= '<link rel="stylesheet" type="text/css" href="/static/bootstrap/dist/css/bootstrap.min.css" as="style" Cache-Control="max-age=3600">' . PHP_EOL;
        $txt .= '<link rel="preload" type="text/css" href="/static/bootstrap/dist/css/bootstrap.min.css" as="style">' . PHP_EOL;
        $txt .= '<link rel="prefetch" href="/static/bootstrap/dist/css/bootstrap.min.css">' . PHP_EOL;

















        // //PRISM_CSS
        // $txt .= '<link rel="stylesheet" type="text/css" href="' . $_ENV['PRISM_CSS'] . '" as="style" Cache-Control="max-age=3600">' . PHP_EOL;
        // $txt .= '<link rel="preload" type="text/css" href="' . $_ENV['PRISM_CSS'] . '" as="style">' . PHP_EOL;
        // $txt .= '<link rel="prefetch" href="' . $_ENV['PRISM_CSS'] . '">' . PHP_EOL;

        return $txt;
    }










    function ping($host, $port = 80, $timeout = 10)
    {
        $starttime = microtime(true);
        $file = @fsockopen($host, $port, $errno, $errstr, $timeout);
        $stoptime = microtime(true);

        if(!$file)
        {
            $this->cdn_down = true;
            return "down";
        }
        else
        {
            fclose($file);
            $status = ($stoptime - $starttime) * 1000;
            $this->cdn_down = false;
            return round($status, 0) . " ms";
        }
    }

    /**
     *  SetEnv WEBROOT		/raid/home/christian/wdrive/buchungssatz
     *  SetEnv CSS          /buchungssatz/static/css/
     *  SetEnv JS           /buchungssatz/static/js/
     */
    function loadStyles()
    {
        $txt = m(__METHOD__);
        $ds = "static/css/";

        if(isMobile())
        {
            $files = array(
                'menu-mobile.min.css',
            );

            // CSS wenn Mobile
            foreach($files as $file)
            {
                if(file_exists($_ENV['CSS_ROOT'] . $file))
                {
                    $txt .= '<link rel="stylesheet" type="text/css" href="' . $ds . $file . '" as="style" media="(max-width: 480px)" Cache-Control="max-age=36000">' . PHP_EOL;
                    $txt .= '<link rel="preload" type="text/css" href="' . $ds . $file . '" as="style">' . PHP_EOL;
                    $txt .= '<link rel="prefetch" href="' . $ds . $file . '" >' . PHP_EOL;
                }
                else
                {
                    //F::logg("$file does not exist?", __METHOD__, __LINE__);
                }
            }
        }
        else
        {
            $files = array(
                'menu.min.css',
                'buchungssatz.min.css',
                'switch.min.css',
                'main0.min.css',
                'datalist.min.css',
            );
            // CSS when not mobile
            foreach($files as $file)
            {
                if(file_exists("/raid/home/christian/wdrive/rechts-org/public/static/css/" . $file))
                {
                    $txt .= '<link rel="stylesheet" type="text/css" href="' . $ds . $file . '" media="screen" as="style" Cache-Control="max-age=36000">' . PHP_EOL;
                    $txt .= '<link rel="preload" type="text/css" href="' . $ds . $file . '" as="style">' . PHP_EOL;
                    $txt .= '<link rel="prefetch" href="' . $ds . $file . '" >' . PHP_EOL;
                }
                else
                {
                    //F::logg("$file does not exist?", __METHOD__, __LINE__);
                }
            }
        }


        return $txt;
    }


    function scripteNachladen()
    {
        $txt = m(__METHOD__);
        $txt .= $this->javamuell();
        // $txt .= cHEAD::google();
        // $txt .= cHEAD::google_analytics('UA-5142614-40'); // Lenz
        // $txt .= cHEAD::google_analytics('UA-5142614-21'); // eichert

        return $txt;
    }

    /**
     * javamuell - ladet verschidene Java scripte
     * to.do. überprüfen was da los ist, brauch man noch alle?
     *
     * @param mixed $rhm
     * @return string
     */
    function javamuell()
    {
        $txt = m(__METHOD__);
        $txt .= h::loadScriptSRC(src: 'copyToClipboard_.min.js');

        return $txt;
    }


    /**
     * loadScriptSRC - ladet einen script von einer URI
     *
     * @param string|null $src
     * @param string|null $uri
     * @return string
     */
    static function loadScriptSRC(string $src = null, string $uri = null)
    {
        $txt = m(__METHOD__);

        if(empty($uri) and !empty($src))
        {
            // $uri = $_ENV['HTTP'] . DIRECTORY_SEPARATOR . $_ENV['JS'] . $src;
            $srcuri = DIRECTORY_SEPARATOR . $_ENV['JS'] . $src;
        }

        if(!empty($srcuri))
        {
            $txt .= '<script type="text/javascript" charset="' . $_ENV['CHARSET'] . '" src="' . $srcuri . '"></script>' . PHP_EOL;
        }
        return $txt;
    }
}
