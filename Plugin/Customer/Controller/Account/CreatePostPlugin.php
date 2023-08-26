<?php

namespace KuS\Antispam\Plugin\Customer\Controller\Account;

class CreatePostPlugin
{

    public function aroundExecute(\Magento\Customer\Controller\Account\CreatePost $subject, callable $proceed)
    {
        $data = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Framework\App\RequestInterface')
                ->getPost();

        $spamContent = array(
            "http://",
            "https://",
            "www.",
            ".com",
            ".de",
            ".cc",
            ".ru",
            ".cn",
            ".net"
        );

        $formFieldsToCheck = array(
            'firstname',
            'lastname'
        );

        $spam = false;

        foreach ($spamContent as $entry) {
            foreach ($formFieldsToCheck as $field) {
                if (is_null($data[$field])) { //ooedit: added this to prevent deprecation error
                    continue; //ooedit
                } //ooedit
                if (strpos($data[$field], $entry) !== false) {
                    $spam = true;				
                }
            }
        }

        if (!$spam) {
            return $proceed();
        }

    }

}