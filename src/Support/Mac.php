<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Support;

class Mac
{
    public const SIGNATURE_TYPE_1 = 'V1';
    public const SIGNATURE_TYPE_2 = 'V2';
    public const SIGNATURE_TYPE_MAC = 'MAC';

    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $type = self::SIGNATURE_TYPE_MAC;

    /**
     * @var array
     */
    private $processedAttributes = [
        self::SIGNATURE_TYPE_1 => [
            'paymentId',
            'amount',
            'status'
        ],
        self::SIGNATURE_TYPE_2 => [
            'amount',
            'currency',
            'reference',
            'transaction',
            'status'
        ]
    ];

    /**
     * Mac constructor
     *
     * @param array $data
     * @param string $key
     */
    public function __construct(array $data, string $key)
    {
        $this->data = $data;
        $this->key = $key;
    }

    /**
     * @param string $type
     * @return Mac
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $key
     * @return Mac
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    protected function input(): string
    {
        if ($this->type === self::SIGNATURE_TYPE_MAC) {
            return json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $return = '';
        foreach ($this->getProcessedAttributes() as $attribute) {
            if (empty($this->data[$attribute])) {
                continue;
            }

            $return .= (is_bool($this->data[$attribute])
                ? ($this->data[$attribute] ? 'true' : 'false')
                : (string)$this->data[$attribute]);
        }

        return $return;
    }

    /**
     * @return array
     */
    protected function getProcessedAttributes(): array
    {
        return $this->processedAttributes[$this->type] ?? [];
    }

    /**
     * @return string
     */
    public function hash(): string
    {
        return strtoupper(hash('sha512', $this->input() . $this->key));
    }
}
