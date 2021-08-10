<?php

declare(strict_types = 1);

namespace Lovetrytry\Jichukuangjia\Key;

use \Exception;
use Lovetrytry\Jichukuangjia\Base;
use Lovetrytry\Jichukuangjia\Path\Project;

/**
 * @author lovetrytry
 * @email lovetrytry@yeah.net
 */
class RSA extends Base
{
    protected $keyDirname = "keys";

    protected $fullDirname = "";

    protected $privateName = "id_rsa";

    protected $publicName = "id_rsa.pub";

    public function __construct()
    {
        $this->fullDirname = (new Project)->getStorage() . DIRECTORY_SEPARATOR . $this->keyDirname . DIRECTORY_SEPARATOR;
    }

    public function getPath($name)
    {
        return $this->fullDirname . $name;
    }

    public function getContent($path)
    {
        $content = file_get_contents($path);

        if ($content === false) {
            throw new Exception('path: ' . $path . " get content fail, check & retry.", 500);
        }

        return $content;
    }

    public function getPublicPath($publicName = "")
    {
        if (empty($publicName)) {
            $publicName = $this->publicName;
        }

        return $this->getPath($publicName);
    }

    public function getPublic($publicName = "")
    {
        return $this->getContent($this->getPublicPath($publicName));
    }

    public function getPrivatePath($privateName = "")
    {
        if (empty($privateName)) {
            $privateName = $this->privateName;
        }

        return $this->getPath($privateName);
    }

    public function getPrivate($privateName = "")
    {
        return $this->getContent($this->getPrivatePath($privateName));
    }
}
