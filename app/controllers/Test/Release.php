<?php

namespace controllers\Test;


class Release extends \Controller\ControllerAbstract {
    public function execute($app)
    {

        $repo = new \Model\Repo();
        $repo->setData('repo_url', 'https://github.com/Magestore/Supercampaign-Magento1.git');
        $repo->setData('owner', 'Magestore');
        $repo->setData('repo', 'Supercampaign-Magento1');
        $release = new \Model\Github\Repos();
        $release->releases($repo);

    }


}

