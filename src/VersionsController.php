<?php
namespace jbennecker\SilverstripeVersions;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;

class VersionsController extends Controller
{
    private static $allowed_actions = [
        'index',
    ];

    /**
     * @config
     *
     * @var string
     */
    private static $token = "";

    public function index()
    {
        $token = Config::inst()->get(__CLASS__, 'token');
        if ($this->getRequest()->getVar('token') != $token) {
            return $this->httpError(403);
        }

        $composer = file_get_contents(Director::baseFolder() . '/composer.lock');
        $composer = json_decode($composer, true);
        foreach ($composer['packages'] as $package) {
            if ($package['name'] == 'silverstripe/framework') {
                $version = $package['version'];
                break;
            }
        }

        $versions = [
            'php' => PHP_VERSION,
            'cms' => 'Silverstripe CMS',
            'cms_version' => $version,
        ];

        echo json_encode($versions);
        exit;
    }
}
