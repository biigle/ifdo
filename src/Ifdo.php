<?php

namespace Biigle\Ifdo;

class Ifdo
{
    private $jsonArr = [];
    private $validator;
    private $debug = false;

    const AVAILABLE_VERSIONS = ['v2.0.0', 'v2.0.1', 'v2.1.0'];

    public static function fromFile($path, $strict = false): Ifdo
    {
        $data = file_get_contents($path);

        return Ifdo::fromString($data);
    }

    public static function fromString($data, $strict = false): Ifdo
    {
        return new Ifdo($data, $strict);
    }

    public function __construct($json, $strict = false)
    {
        $this->jsonArr = json_decode($json, true);

        if ($strict)
        {
            if ( ! $this->isValid())
            {
                throw new \Exception("Malformed document. See \$obj->getErrors() for more details.");
            }
        }
    }

    public function getValidator()
    {
        if ( ! $this->validator)
        {
            $this->revalidate();
        }
        return $this->validator;
    }

    public function revalidate(): void
    {
        $this->validator = new \JsonSchema\Validator;
        $version         = $this->getIfdoVersion();
        $decoded         = json_decode($this->toString());
        $this->validator->validate($decoded, (object) ['$ref' => 'file://'.__DIR__."/../assets/ifdo-$version.json"]);

        if ($this->getDebug() && ! empty($this->getErrors()))
        {
            $this->printErrors();
        }
    }

    public function isValid(): bool
    {
        return $this->getValidator()->isValid();
    }

    public function getErrors(): Array {
        return $this->getValidator()->getErrors();
    }

    public function setDebug($value): void
    {
        $this->debug = true;
    }

    public function getDebug(): bool
    {
        return $this->debug;
    }

    public function getJsonData()
    {
        return $this->jsonArr;
    }

    public function getImageSetHeader()
    {
        $arr = $this->getJsonData();
        if (is_array($arr) && array_key_exists('image-set-header', $arr)) {
            return $arr['image-set-header'];
        }

        return [];
    }

    public function getImageSetItems()
    {
        $arr = $this->getJsonData();
        if (is_array($arr) && array_key_exists('image-set-items', $arr)) {
            return $arr['image-set-items'];
        }

        return [];
    }

    public function getIfdoVersion(): String
    {
        if (array_key_exists('image-set-items', $this->getImageSetHeader()))
        {
            $dataVersion = $this->getImageSetHeader()['image-set-ifdo-version'];
        }
        else
        {
            $dataVersion = "v2.1.0";
        }

        if ( ! in_array($dataVersion, self::AVAILABLE_VERSIONS))
        {
            throw new \Exception("Unsupported iFDO version `$dataVersion`");
        }
        return $dataVersion;
    }

    public function printErrors(): void
    {
        print "\nJSON does not validate. Violations:\n";
        foreach ($this->getValidator()->getErrors() as $error)
        {
            printf("[%s] %s\n", $error['property'], $error['message']);
        }
    }

    public function toString(): String
    {
        return json_encode($this->getJsonData());
    }
}
