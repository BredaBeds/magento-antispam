<?php

namespace BredaBeds\Antispam\Plugin\Customer\Account;

use Magento\Customer\Controller\Account\CreatePost;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Exception\InputException;

class CreatePostPlugin
{
    public function __construct(
        private RequestInterface $request,
        private ManagerInterface $messageManager,
        private UrlInterface $urlModel
    ) { }

    public function beforeExecute(CreatePost $subject)
    {
        $postData = $this->request->getPostValue();

        $patterns = [
            '/https?:\/\//i', // Matches http/https
            '/www\./i',       // Matches 'www.'
            '/\.(com|net|us|de|cc|ru|cn|info|biz|xyz|top|pw|tk|ga|ml|cf|gq|ph|vn|in|ro|ua|pk|ng)\b/i', // Matches common TLDs
        ];

        foreach (['firstname', 'lastname'] as $field) {
            if (!isset($postData[$field]) || is_null($postData[$field])) continue;

            foreach ($patterns as $pattern) {
                if (!preg_match($pattern, $postData[$field])) continue; // No match / safe

                // Add error message and redirect to the registration page
                $this->messageManager->addErrorMessage(
                    __('Invalid input detected in the name fields. Please remove URLs or inappropriate text.')
                );
                $url = $this->urlModel->getUrl('*/*/create', ['_secure' => true]);
                throw new InputException(__('Invalid name input, suspected spam.'));
            }

        }
    }
}