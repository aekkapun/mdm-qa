<?php
/**
 * @link      https://github.com/brussens/yii2-maintenance-mode
 * @copyright Copyright (c) since 2015 Dmitry Brusensky
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace brussens\maintenance\states;

use Yii;
use brussens\maintenance\StateInterface;
use yii\base\BaseObject;

/**
 * Class FileState
 *
 * @package brussens\maintenance\states
 */
class FileState extends BaseObject implements StateInterface
{
    /**
     * @var string the filename that will determine if the maintenance mode is enabled
     */
    public $fileName = 'YII_MAINTENANCE_MODE_ENABLED';

    /**
     * @var string the directory in that the file stated in $fileName above is residing
     */
    public $directory = '@runtime';

    /**
     * @var string the complete path of the file - populated in init
     */
    public $path;
    
    /**
     * Initialization
     */
    public function init()
    {
        $this->path = $this->getStatusFilePath();
    }
    
    /**
     * Turn on mode.
     *
     * @since 0.2.5
     */
    public function enable()
    {
        if (file_put_contents($this->path,
                'The maintenance Mode of your Application is enabled if this file exists.') === false) {
            throw new \Exception(
                "Attention: the maintenance mode could not be enabled because {$this->path} could not be created."
            );
        }
    }

    /**
     * Turn off mode.
     *
     * @since 0.2.5
     */
    public function disable()
    {
        if (file_exists($this->path)) {
            if (!unlink($this->path)) {
                throw new \Exception(
                    "Attention: the maintenance mode could not be disabled because {$this->path} could not be removed."
                );
            };
        }
    }

    /**
     * @return bool will return true if the file exists
     */
    public function isEnabled()
    {
        return file_exists($this->path);
    }

    /**
     * Return status file path.
     *
     * @return bool|string
     * @since 0.2.5
     */
    protected function getStatusFilePath()
    {
        return Yii::getAlias($this->directory . '/' . $this->fileName);
    }
}
