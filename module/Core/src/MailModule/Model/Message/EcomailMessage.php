<?php


namespace Core\MailModule\Model\Message;

use MassimoFilippi\MailModule\Model\Message\Message;
use MassimoFilippi\MailModule\Model\Message\MessageInterface;

class EcomailMessage extends Message implements MessageInterface
{
    /**
     * @var int|null
     */
    protected $templateId = null;

    /**
     * @var array
     */
    protected $mergeTags = [];

    /**
     * @return bool
     */
    public function isTemplate(): bool
    {
        return null !== $this->templateId;
    }

    /**
     * @return int|null
     */
    public function getTemplateId(): ?int
    {
        return $this->templateId;
    }

    /**
     * @param int $templateId
     */
    public function setTemplateId(int $templateId)
    {
        $this->templateId = $templateId;
    }

    /**
     * @return bool
     */
    public function hasMergeTags(): bool
    {
        return false === empty($this->mergeTags);
    }

    /**
     * @return array
     */
    public function getMergeTags(): array
    {
        return $this->mergeTags;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setMergeTag($key, $value)
    {
        $this->mergeTags[(string)$key] = $value;
    }

    public function setMergeTags(array $mergeTags)
    {
        $this->mergeTags = $mergeTags;
    }
}